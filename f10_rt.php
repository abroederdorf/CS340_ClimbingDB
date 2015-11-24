<?php
	//Turn on error reporting
	ini_set('display_errors', 'On');
	
	//Connects to the database
	$mysqli = new mysqli("oniddb.cws.oregonstate.edu","broedera-db","u00kFHGxpWuH5GTI","broedera-db");
	if(!$mysqli || $mysqli->connect_errno){
		echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Mountain Climbing Database</title>
		<link rel="stylesheet" type="text/css" href="PHPstyle.css">
	</head>
	<body>
		<div id="searchDiv">
			<h3>Search Results</h3>
			<p>Below are the routes the require the skills  
			<?php
				//First climber
				if(!($stmt = $mysqli->prepare("SELECT name FROM mtn_skill WHERE id = ?"))){
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}

				if(!($stmt->bind_param("i",$_POST['f10Sk1']))){
					echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				}

				if(!$stmt->execute()){
					echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				if(!$stmt->bind_result($sname)){
					echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				while($stmt->fetch()){
				 echo $sname . " and ";
				}
				$stmt->close();
				
				//Second climber
				if(!($stmt = $mysqli->prepare("SELECT name FROM mtn_skill WHERE id = ?"))){
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}

				if(!($stmt->bind_param("i",$_POST['f10Sk2']))){
					echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				}

				if(!$stmt->execute()){
					echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				if(!$stmt->bind_result($sname)){
					echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				while($stmt->fetch()){
				 echo $sname . ".";
				}
				$stmt->close();
			?>
			</p>
			<table id="f10Tbl" class="filterTbls">
				<tr>
					<th class="txtCenter">Route</th>
					<th class="txtCenter">Mountain</th>
				</tr>
				<?php
					if(!($stmt = $mysqli->prepare("SELECT tbl2.rname, tbl2.mname FROM (SELECT r.id FROM mtn_route r INNER JOIN mtn_routeSkill rs ON rs.rid = r.id INNER JOIN mtn_skill s ON s.id = rs.sid WHERE s.id = ?) AS tbl1 INNER JOIN (SELECT r.name AS rname, m.name AS mname, r.id FROM mtn_route r INNER JOIN mtn_routeSkill rs ON rs.rid = r.id INNER JOIN mtn_skill s ON s.id = rs.sid INNER JOIN mtn_mountain m ON m.id = r.mtid WHERE s.id = ?) AS tbl2 ON tbl1.id = tbl2.id ORDER BY tbl2.mname ASC"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}

					if(!($stmt->bind_param("ii",$_POST['f10Sk1'],$_POST['f10Sk2']))){
						echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					}

					if(!$stmt->execute()){
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					if(!$stmt->bind_result($rname, $mname)){
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					while($stmt->fetch()){
					 echo "<tr>\n<td class='txtCenter'>\n" . $rname . "\n</td>\n<td class='txtCenter'>\n" . $mname . "\n</td>\n</tr>";
					}
					
					if ($stmt->num_rows == 1)
						echo "<p><em>(1 result returned)</em></p>";
					else
						echo "<p><em>(".$stmt->num_rows . " reults returned)</em></p>";
					
					$stmt->close();
					?>
			</table>
			<!--Source: http://stackoverflow.com/questions/5025941/is-there-a-way-to-get-a-button-element-to-link-to-a-location-without-wrapping-->
			<button onclick="window.location='http://web.engr.oregonstate.edu/~broedera/CS340/project/mtnClmbDBPHP.php';">Back</button>
		</div>
	</body>
</html>