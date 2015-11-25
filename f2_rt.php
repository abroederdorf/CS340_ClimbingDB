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
			<p>Below are the routes 
			<?php
			//Create message to let user know what the search criteria was
			
				//check if a location is provided
				if ($_POST['f2Loc'] != 0)
				{
					echo "in the " . $_POST['f2Loc'];
					//Check if minimum elevation is provided
					if (!empty($_POST['f2ElMin']))
					{
						echo " over " . $_POST['f2ElMin'] . " feet";
						//Check if maximum elevation is provided
						if (!empty($_POST['f2ElMax']))
							echo " and under " . $_POST['f2ElMax'] . " feet.";
						else
							echo ".";
					}
					else
					{
						//Check if maximum elevation is provided
						if (!empty($_POST['f2ElMax']))
							echo " under " . $_POST['f2ElMax'] . " feet.";
						else
							echo ".";
					}
				}
				else
				{
					//Check if minimum elevation is provided
					if (!empty($_POST['f2ElMin']))
					{
						echo " in all locations over " . $_POST['f2ElMin'] . " feet";
						//Check if maximum elevation is provided
						if (!empty($_POST['f2ElMax']))
							echo " and under " . $_POST['f2ElMax'] . " feet.";
						else
							echo ".";
					}
					else
					{
						//Check if maximum elevation is provided
						if (!empty($_POST['f2ElMax']))
							echo " in all locations under " . $_POST['f2ElMax'] . " feet.";
						else
							echo "in all locations.";
					}
				}
			?>
			</p>
			<table id="f2Tbl" class="filterTbls">
				<tr>
					<th class="txtCenter">Route</th>
					<th class="txtCenter">Mountain</th>
					<th class="txtCenter">Elevation [feet]</th>
					<th class="txtCenter">Location</th>
				</tr>
				<?php
				//Create table to show results
				
					//check if a location is provided
					if ($_POST['f2Loc'] != "0")
					{
						//Check if minimum elevation is provided
						if (!empty($_POST['f2ElMin']))
						{
							//Check if maximum elevation is provided
							if (!empty($_POST['f2ElMax']))
							{
								if(!($stmt = $mysqli->prepare("SELECT r.name, m.name, m.elevation, m.location FROM mtn_route r INNER JOIN mtn_mountain m ON r.mtid = m.id WHERE m.location = ? AND m.elevation > ? AND m.elevation < ?"))){
									echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
								}

								if(!($stmt->bind_param("sii",$_POST['f2Loc'],$_POST['f2ElMin'],$_POST['f2ElMax']))){
									echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
								}

								if(!$stmt->execute()){
									echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
							}
							else
							{
								if(!($stmt = $mysqli->prepare("SELECT r.name, m.name, m.elevation, m.location FROM mtn_route r INNER JOIN mtn_mountain m ON r.mtid = m.id WHERE m.location = ? AND m.elevation > ?"))){
									echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
								}

								if(!($stmt->bind_param("si",$_POST['f2Loc'],$_POST['f2ElMin']))){
									echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
								}

								if(!$stmt->execute()){
									echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
							}
						}
						else
						{
							//Check if maximum elevation is provided
							if (!empty($_POST['f2ElMax']))
							{
								if(!($stmt = $mysqli->prepare("SELECT r.name, m.name, m.elevation, m.location FROM mtn_route r INNER JOIN mtn_mountain m ON r.mtid = m.id WHERE m.location = ? AND m.elevation < ?"))){
									echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
								}

								if(!($stmt->bind_param("si",$_POST['f2Loc'],$_POST['f2ElMax']))){
									echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
								}

								if(!$stmt->execute()){
									echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
							}								
							else
							{
								if(!($stmt = $mysqli->prepare("SELECT r.name, m.name, m.elevation, m.location FROM mtn_route r INNER JOIN mtn_mountain m ON r.mtid = m.id WHERE m.location = ?"))){
									echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
								}

								if(!($stmt->bind_param("s",$_POST['f2Loc']))){
									echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
								}

								if(!$stmt->execute()){
									echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
							}		
						}
					}
					else
					{
						//Check if minimum elevation is provided
						if (!empty($_POST['f2ElMin']))
						{
							//Check if maximum elevation is provided
							if (!empty($_POST['f2ElMax']))
							{
								if(!($stmt = $mysqli->prepare("SELECT r.name, m.name, m.elevation, m.location FROM mtn_route r INNER JOIN mtn_mountain m ON r.mtid = m.id WHERE m.elevation > ? AND m.elevation < ?"))){
									echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
								}

								if(!($stmt->bind_param("ii",$_POST['f2ElMin'],$_POST['f2ElMax']))){
									echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
								}

								if(!$stmt->execute()){
									echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
							}
							else
							{
								if(!($stmt = $mysqli->prepare("SELECT r.name, m.name, m.elevation, m.location FROM mtn_route r INNER JOIN mtn_mountain m ON r.mtid = m.id WHERE m.elevation > ?"))){
									echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
								}

								if(!($stmt->bind_param("i",$_POST['f2ElMin']))){
									echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
								}

								if(!$stmt->execute()){
									echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
							}
						}
						else
						{
							//Check if maximum elevation is provided
							if (!empty($_POST['f2ElMax']))
							{
								if(!($stmt = $mysqli->prepare("SELECT r.name, m.name, m.elevation, m.location FROM mtn_route r INNER JOIN mtn_mountain m ON r.mtid = m.id WHERE m.elevation < ?"))){
									echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
								}

								if(!($stmt->bind_param("i",$_POST['f2ElMax']))){
									echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
								}

								if(!$stmt->execute()){
									echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
							}								
							else
							{
								if(!($stmt = $mysqli->prepare("SELECT r.name, m.name, m.elevation, m.location FROM mtn_route r INNER JOIN mtn_mountain m ON r.mtid = m.id"))){
									echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
								}

								if(!$stmt->execute()){
									echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
								}
							}
						}
					}
					if(!$stmt->bind_result($rname, $mname, $elev, $loc)){
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					while($stmt->fetch()){
					 echo "<tr>\n<td class='txtCenter'>\n" . $rname . "\n</td>\n<td class='txtCenter'>\n" . $mname . "\n</td>\n<td class='txtCenter'>\n" . $elev . "\n</td>\n<td class='txtCenter'>\n" . $loc . "\n</td>\n</tr>";
					}
					
					if ($stmt->num_rows == 1)
						echo "<p><em>(1 result returned)</em></p>";
					else
					{
						//Print how many results were returned
						echo "<p><em>(".$stmt->num_rows . " reults returned)</em></p>";
					}
					
					$stmt->close();
					?>
			</table>
			
			<!--Source: http://stackoverflow.com/questions/5025941/is-there-a-way-to-get-a-button-element-to-link-to-a-location-without-wrapping-->
			<button onclick="window.location='http://web.engr.oregonstate.edu/~broedera/CS340/project/mtnClmbDB.php';">Back</button>
		</div>
	</body>
</html>