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
			<p>Below are the routes on 
			<?php
			//Create message to let user know what the search criteria was
			
				if(!($stmt = $mysqli->prepare("SELECT name FROM mtn_mountain WHERE id = ?"))){
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}

				if(!($stmt->bind_param("i",$_POST['f1Mt']))){
					echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				}

				if(!$stmt->execute()){
					echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				if(!$stmt->bind_result($name)){
					echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				while($stmt->fetch()){
				 echo $name . ".";
				}
				$stmt->close();
			?>
			</p>
			<table id="f1Tbl" class="filterTbls">
				<tr>
					<th class="txtCenter">Route</th>
				</tr>
				<?php			
				//Create table to show results
				
					if(!($stmt = $mysqli->prepare("SELECT r.name FROM mtn_route r INNER JOIN mtn_mountain m ON r.mtid = m.id WHERE m.id = ?"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}

					if(!($stmt->bind_param("i",$_POST['f1Mt']))){
						echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					}

					if(!$stmt->execute()){
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						$numRows = $stmt->affected_rows;
					}
					
					if(!$stmt->bind_result($name)){
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					while($stmt->fetch()){
					 echo "<tr>\n<td class='txtCenter'>\n" . $name . "\n</td>\n</tr>";
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