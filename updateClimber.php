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
					//Update first name
					if (!empty($_POST['fnameUp']))
					{
						if(!($stmt = $mysqli->prepare("UPDATE mtn_climber SET fname = ? WHERE id = ?"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}
						if(!($stmt->bind_param("si",$_POST['fnameUp'],$_POST['climberUp']))){
							echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
						}
						if(!$stmt->execute()){
							echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
						} else 
						{
							 echo "Updated first name. ";
						}
							$stmt->close();
					}
					//Update last name
					if (!empty($_POST['lnameUp']))
					{
						if(!($stmt = $mysqli->prepare("UPDATE mtn_climber SET lname = ? WHERE id = ?"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}
						if(!($stmt->bind_param("si",$_POST['lnameUp'],$_POST['climberUp']))){
							echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
						}
						if(!$stmt->execute()){
							echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
						} else 
						{
							 echo "Updated last name. ";
						}
							$stmt->close();
					}
					//Update birth year
					if (!empty($_POST['birthYearUp']))
					{
						if(!($stmt = $mysqli->prepare("UPDATE mtn_climber SET birthYear = ? WHERE id = ?"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}
						if(!($stmt->bind_param("ii",$_POST['birthYearUp'],$_POST['climberUp']))){
							echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
						}
						if(!$stmt->execute()){
							echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
						} else 
						{
							 echo "Updated birth year. ";
						}
							$stmt->close();
					}
					//Update gender
					if ($_POST['genderUp'] != "No Change")
					{
						if(!($stmt = $mysqli->prepare("UPDATE mtn_climber SET gender = ? WHERE id = ?"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}
						if(!($stmt->bind_param("si",$_POST['genderUp'],$_POST['climberUp']))){
							echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
						}
						if(!$stmt->execute()){
							echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
						} else 
						{
							 echo "Updated gender. ";
						}
							$stmt->close();
					}
					//Update zip code
					if (!empty($_POST['zipUp']))
					{
						if(!($stmt = $mysqli->prepare("UPDATE mtn_climber SET zip = ? WHERE id = ?"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}
						if(!($stmt->bind_param("ii",$_POST['zipUp'],$_POST['climberUp']))){
							echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
						}
						if(!$stmt->execute()){
							echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
						} else 
						{
							 echo "Updated zip code. ";
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