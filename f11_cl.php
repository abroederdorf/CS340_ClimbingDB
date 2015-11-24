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
			<p>Below are the climbers with the skills  
			<?php
				//First skill
				if(!($stmt = $mysqli->prepare("SELECT name FROM mtn_skill WHERE id = ?"))){
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}

				if(!($stmt->bind_param("i",$_POST['f11Sk1']))){
					echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				}

				if(!$stmt->execute()){
					echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				if(!$stmt->bind_result($sname)){
					echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				while($stmt->fetch()){
				 echo $sname . ",  ";
				}
				$stmt->close();
				
				//Second skill
				if(!($stmt = $mysqli->prepare("SELECT name FROM mtn_skill WHERE id = ?"))){
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}

				if(!($stmt->bind_param("i",$_POST['f11Sk2']))){
					echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				}

				if(!$stmt->execute()){
					echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				if(!$stmt->bind_result($sname)){
					echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				while($stmt->fetch()){
				 echo $sname . ", and ";
				}
				$stmt->close();
				
				//Third skill
				if(!($stmt = $mysqli->prepare("SELECT name FROM mtn_skill WHERE id = ?"))){
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}

				if(!($stmt->bind_param("i",$_POST['f11Sk3']))){
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
			<table id="f11Tbl" class="filterTbls">
				<tr>
					<th class="txtCenter">First Name</th>
					<th class="txtCenter">Last Name</th>
				</tr>
				<?php
					if(!($stmt = $mysqli->prepare("SELECT tbl3.fname, tbl3.lname FROM (SELECT tbl1.id FROM (SELECT c.id FROM mtn_climber c INNER JOIN mtn_climberSkill cs ON cs.cid = c.id INNER JOIN mtn_skill s ON s.id = cs.sid WHERE s.id = ?) AS tbl1 INNER JOIN (SELECT c.id FROM mtn_climber c INNER JOIN mtn_climberSkill cs ON cs.cid = c.id INNER JOIN mtn_skill s ON s.id = cs.sid WHERE s.id = ?) AS tbl2 ON tbl1.id = tbl2.id) AS tbl4 INNER JOIN (SELECT c.id, c.fname, c.lname FROM mtn_climber c INNER JOIN mtn_climberSkill cs ON cs.cid = c.id INNER JOIN mtn_skill s ON s.id = cs.sid WHERE s.id = ?) AS tbl3 ON tbl3.id = tbl4.id ORDER BY tbl3.lname ASC"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}

					if(!($stmt->bind_param("iii",$_POST['f11Sk1'],$_POST['f11Sk2'],$_POST['f11Sk3']))){
						echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					}

					if(!$stmt->execute()){
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					if(!$stmt->bind_result($fname, $lname)){
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					while($stmt->fetch()){
					 echo "<tr>\n<td class='txtCenter'>\n" . $fname . "\n</td>\n<td class='txtCenter'>\n" . $lname . "\n</td>\n</tr>";
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