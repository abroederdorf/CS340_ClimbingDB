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
		<div id="pageDiv">
			<h3>Status</h3>
			<p>
				<?php	
					//Create add query and execute
					if(!($stmt = $mysqli->prepare("INSERT INTO mtn_mountain(name, elevation, location) VALUES (?,?,?)"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					if(!($stmt->bind_param("sis",$_POST['mname'],$_POST['elev'],$_POST['location']))){
						echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					}
					if(!$stmt->execute()){
						echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
					} else {
						if ($stmt->affected_rows == 0)
							echo "Error, added " . $stmt->affected_rows . " rows to mtn_mountain.";
						else
							echo "Added " . $stmt->affected_rows . " row to mtn_mountain.";
					}
				?>
			</p>
			<!--Source: http://stackoverflow.com/questions/5025941/is-there-a-way-to-get-a-button-element-to-link-to-a-location-without-wrapping-->
			<button onclick="window.location='http://web.engr.oregonstate.edu/~broedera/CS340/project/mtnClmbDBPHP.php';">Back</button>
		</div>
	</body>
</html>