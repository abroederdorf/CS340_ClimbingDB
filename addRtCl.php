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
					//Validate date format
					$myDate = $_POST['dateClimbed'];
					
					//Check that date only contains numbers or -
					$dateLen = strlen($myDate);
					$rightFormat = true;
					$total = 0;
					for ($i = 0; $i < $dateLen; $i++)
					{
						$char = substr($myDate, $i, 1);
						if (!(is_numeric($char) ||  $char == '-'))
							$total++;
					}
					if ($total)
					{
						echo "Error: Incorrect date format. You entered ".$myDate.". Please enter the date in the format YYYY-MM-DD";
						exit;
					}
					else
					{
						//Explode string to contain separate year, month, and day
						list($year, $month, $day) = explode('-', $myDate);

						//Check that date is valid
						if (!(checkdate($month,$day,$year)))
						{
							echo "Error: Incorrect date format. You entered ".$myDate.". Please enter the date in the format YYYY-MM-DD";
							exit;
						}

						if ($myDate > date("Y/m/d"))
						{
							echo "Error: Incorrect date. You entered ".$myDate.". Please pick a date that has already occurred";
							exit;
						}	
					}
				
					//Create add query and execute
					if(!($stmt = $mysqli->prepare("INSERT INTO mtn_routeClimbed(cid, rid, climbDate) VALUES (?,?,?)"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					if(!($stmt->bind_param("iis",$_POST['climberRtCl'],$_POST['routeRtCl'],$_POST['dateClimbed']))){
						echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					}
					if(!$stmt->execute()){
						echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
					} else {
						if ($stmt->affected_rows == 0)
							echo "Error, added " . $stmt->affected_rows . " rows to mtn_routeClimbed.";
						else
							echo "Added " . $stmt->affected_rows . " row to mtn_routeClimbed.";
					}
				?>
			</p>
			<!--Source: http://stackoverflow.com/questions/5025941/is-there-a-way-to-get-a-button-element-to-link-to-a-location-without-wrapping-->
			<button onclick="window.location='http://web.engr.oregonstate.edu/~broedera/CS340/project/mtnClmbDB.php';">Back</button>
		</div>
	</body>
</html>