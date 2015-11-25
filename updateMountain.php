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
					//Update name
					if (!empty($_POST['mtnUp']))
					{
						if(!($stmt = $mysqli->prepare("UPDATE mtn_mountain SET name = ? WHERE id = ?"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}
						if(!($stmt->bind_param("si",$_POST['mtnUp'],$_POST['mtnIdUp']))){
							echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
						}
						if(!$stmt->execute()){
							echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
						} else 
						{
							 echo "Updated mountain name. ";
						}
							$stmt->close();
					}
					//Update elevation
					if (!empty($_POST['elevUp']))
					{
						if(!($stmt = $mysqli->prepare("UPDATE mtn_mountain SET elevation = ? WHERE id = ?"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}
						if(!($stmt->bind_param("ii",$_POST['elevUp'],$_POST['mtnIdUp']))){
							echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
						}
						if(!$stmt->execute()){
							echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
						} else 
						{
							 echo "Updated elevation. ";
						}
							$stmt->close();
					}
					//Update location
					if (!empty($_POST['locationUp']))
					{
						if(!($stmt = $mysqli->prepare("UPDATE mtn_mountain SET location = ? WHERE id = ?"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}
						if(!($stmt->bind_param("si",$_POST['locationUp'],$_POST['mtnIdUp']))){
							echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
						}
						if(!$stmt->execute()){
							echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
						} else 
						{
							 echo "Updated location. ";
						}
							$stmt->close();
					}
				?>
			</p>
			<!--Source: http://stackoverflow.com/questions/5025941/is-there-a-way-to-get-a-button-element-to-link-to-a-location-without-wrapping-->
			<button onclick="window.location='http://web.engr.oregonstate.edu/~broedera/CS340/project/mtnClmbDB.php';">Back</button>
		</div>
	</body>
</html>