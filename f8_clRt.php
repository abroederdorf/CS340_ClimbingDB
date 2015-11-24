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
			<p>Below are the number of times each climber has climbed the  
			<?php
				if(!($stmt = $mysqli->prepare("SELECT r.name, m.name FROM mtn_route r INNER JOIN mtn_mountain m ON r.mtid = m.id WHERE r.id = ?"))){
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}

				if(!($stmt->bind_param("i",$_POST['f8Rt']))){
					echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				}

				if(!$stmt->execute()){
					echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				if(!$stmt->bind_result($rname, $mname)){
					echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				while($stmt->fetch()){
				 echo $rname . " on " . $mname . ".";
				}
				$stmt->close();
			?>
			</p>
			<table id="f8Tbl" class="filterTbls">
				<tr>
					<th class="txtCenter">First Name</th>
					<th class="txtCenter">Last Name</th>
					<th class="txtCenter">Times Climbed</th>
				</tr>
				<?php
					if(!($stmt = $mysqli->prepare("SELECT c.fname, c.lname, tbl1.times FROM mtn_climber c LEFT JOIN (SELECT rc.cid, count(rc.cid) AS times FROM mtn_routeClimbed rc INNER JOIN mtn_route r ON r.id = rc.rid WHERE r.id = ? GROUP BY rc.cid) AS tbl1 ON c.id = tbl1.cid ORDER BY c.lname ASC"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}

					if(!($stmt->bind_param("i",$_POST['f8Rt']))){
						echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					}

					if(!$stmt->execute()){
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					if(!$stmt->bind_result($fname, $lname, $times)){
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					while($stmt->fetch()){
						if ($times == NULL)
							$timesMod = 0;
						else
							$timesMod = $times;
					 echo "<tr>\n<td class='txtCenter'>\n" . $fname . "\n</td>\n<td class='txtCenter'>\n" . $lname . "\n</td>\n<td class='txtCenter'>\n" . $timesMod . "\n</td>\n</tr>";
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