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
			<p>Below are the climbers who have
			<?php
				//Display message if search is for climbers who have or have not climbed the route
				if (isset($_POST['f4NotBox']))
					echo " not climbed the ";
				else
					echo " climbed the ";
			
				if(!($stmt = $mysqli->prepare("SELECT r.name, m.name FROM mtn_route r INNER JOIN mtn_mountain m ON r.mtid = m.id WHERE r.id = ?"))){
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}

				if(!($stmt->bind_param("i",$_POST['f4Rt']))){
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
			<table id="f4Tbl" class="filterTbls">
				<tr>
					<th class="txtCenter">First Name</th>
					<th class="txtCenter">Last Name</th>
				<?php
					//Search for climbers who have not climbed the route
					if (isset($_POST['f4NotBox']))
					{
						echo "</tr>";
						
						if(!($stmt = $mysqli->prepare("SELECT c.fname, c.lname FROM mtn_climber c WHERE c.id NOT IN (SELECT c.id FROM mtn_climber c INNER JOIN mtn_routeClimbed rc ON rc.cid = c.id INNER JOIN mtn_route r ON r.id = rc.rid WHERE r.id = ?)"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!($stmt->bind_param("i",$_POST['f4Rt']))){
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
					}
					else
					{
						//Search for climbers who have climbed the route
						echo "<th class='txtCenter'>Date Climbed</th>\n</tr>";
						
						if(!($stmt = $mysqli->prepare("SELECT c.fname, c.lname, rc.climbDate FROM mtn_climber c INNER JOIN mtn_routeClimbed rc ON rc.cid = c.id INNER JOIN mtn_route r ON r.id = rc.rid WHERE r.id = ?"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!($stmt->bind_param("i",$_POST['f4Rt']))){
							echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!$stmt->execute()){
							echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						if(!$stmt->bind_result($fname, $lname, $climbDate)){
							echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						while($stmt->fetch()){
							echo "<tr>\n<td class='txtCenter'>\n" . $fname . "\n</td>\n<td class='txtCenter'>\n" . $lname . "\n</td>\n<td class='txtCenter'>\n" . $climbDate . "\n</td>\n</tr>";
						}
						
						if ($stmt->num_rows == 1)
							echo "<p><em>(1 result returned)</em></p>";
						else
							echo "<p><em>(".$stmt->num_rows . " reults returned)</em></p>";
						
						$stmt->close();						
					}
					?>
			</table>
			<!--Source: http://stackoverflow.com/questions/5025941/is-there-a-way-to-get-a-button-element-to-link-to-a-location-without-wrapping-->
			<button onclick="window.location='http://web.engr.oregonstate.edu/~broedera/CS340/project/mtnClmbDBPHP.php';">Back</button>
		</div>
	</body>
</html>