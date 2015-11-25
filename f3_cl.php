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
			<p>Below are the climbers that 
			<?php		
			//Create message to let user know what the search criteria was
			
				//Check gender
				$genStat = false;
				if (isset($_POST['f3Gender']))
				{
					if (!($_POST['f3Gender'] == 'all'))
					{
						echo "are " . $_POST['f3Gender'];
						$genStat = true;
					}
				}
				
				//Check if a zip code is provided
				if (!empty($_POST['f3Zip']))
				{
					if ($genStat)
						echo ", ";
					//Check if minimum age is provided
					if (!empty($_POST['f3AgeMin']))
					{
						//Check if maximum age is provided
						if (!empty($_POST['f3AgeMax']))
							echo "live in " . $_POST['f3Zip'] . ", are over " . $_POST['f3AgeMin'] . ", and are under " . $_POST['f3AgeMax'] . ".";
						else
							echo "live in " . $_POST['f3Zip'] . ", and are over " . $_POST['f3AgeMin'] . ".";
					}
					else
					{
						//Check if maximum age is provided
						if (!empty($_POST['f3AgeMax']))
							echo "live in " . $_POST['f3Zip'] . ", and are under " . $_POST['f3AgeMax'] . ".";
						else
						{
							if ($genStat)
								echo "and ";
							echo "live in " . $_POST['f3Zip'] . ".";
						}
					}
				}
				else
				{
					//Check if minimum age is provided
					if (!empty($_POST['f3AgeMin']))
					{
						if ($genStat)
							echo ", ";
						//Check if maximum age is provided
						if (!empty($_POST['f3AgeMax']))
							echo " are over " . $_POST['f3AgeMin'] . ", and are under " . $_POST['f3AgeMax'] . ".";
						else
						{
							if ($genStat)
								echo "and ";
							echo " are over " . $_POST['f3AgeMin'] . ".";
						}
					}
					else
					{
						//Check if maximum age is provided
						if (!empty($_POST['f3AgeMax']))
						{
							if ($genStat)
								echo ", and are";
							
							echo " under " . $_POST['f3AgeMax'] . ".";
						}
						else
						{
							if (!($genStat))
								echo "include everyone";
							echo ".";
						}
					}
				}
			?>
			</p>
			<table id="f3Tbl" class="filterTbls">
				<tr>
					<th class="txtCenter">First Name</th>
					<th class="txtCenter">Last Name</th>
					<th class="txtCenter">Age</th>
					<th class="txtCenter">Gender</th>
					<th class="txtCenter">Zip Code</th>
				</tr>
				<?php
				//Create table to show results
				
					//Get current year for determining ages
					$curYear = date("Y");
					
					//Check gender, if specified true otherwise false
					$genStat = false;
					if (isset($_POST['f3Gender']))
					{
						if (!($_POST['f3Gender'] == 'all'))
							$genStat = true;
					}
					
					//Check that gender is specified
					if ($genStat)
					{
						//Check if a zip code is provided
						if (!empty($_POST['f3Zip']))
						{
							//Check if minimum age is provided
							if (!empty($_POST['f3AgeMin']))
							{
								//Calculate max birth year
								$maxYear = $curYear - $_POST['f3AgeMin'];
								
								//Check if maximum age is provided
								if (!empty($_POST['f3AgeMax']))
								{
									//Calculate min birth year
									$minYear = $curYear - $_POST['f3AgeMax'];
									
									if(!($stmt = $mysqli->prepare("SELECT fname, lname, birthYear, gender, zip FROM mtn_climber c WHERE gender = ? AND zip = ? AND birthYear < ? AND birthYear > ?"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!($stmt->bind_param("siii",$_POST['f3Gender'],$_POST['f3Zip'],$maxYear,$minYear))){
										echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!$stmt->execute()){
										echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
								}
								else
								{
									if(!($stmt = $mysqli->prepare("SELECT fname, lname, birthYear, gender, zip FROM mtn_climber c WHERE gender = ? AND zip = ? AND birthYear < ?"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!($stmt->bind_param("sii",$_POST['f3Gender'],$_POST['f3Zip'],$maxYear))){
										echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!$stmt->execute()){
										echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
								}
							}
							else
							{
								//Check if maximum age is provided
								if (!empty($_POST['f3AgeMax']))
								{
									//Calculate min birth year
									$minYear = $curYear - $_POST['f3AgeMax'];
									
									if(!($stmt = $mysqli->prepare("SELECT fname, lname, birthYear, gender, zip FROM mtn_climber c WHERE gender = ? AND zip = ? AND birthYear > ?"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!($stmt->bind_param("sii",$_POST['f3Gender'],$_POST['f3Zip'],$minYear))){
										echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!$stmt->execute()){
										echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
								}
								else
								{
									if(!($stmt = $mysqli->prepare("SELECT fname, lname, birthYear, gender, zip FROM mtn_climber c WHERE gender = ? AND zip = ?"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!($stmt->bind_param("si",$_POST['f3Gender'],$_POST['f3Zip']))){
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
							//Check if minimum age is provided
							if (!empty($_POST['f3AgeMin']))
							{
								//Calculate max birth year
								$maxYear = $curYear - $_POST['f3AgeMin'];
								
								//Check if maximum age is provided
								if (!empty($_POST['f3AgeMax']))
								{
									//Calculate min birth year
									$minYear = $curYear - $_POST['f3AgeMax'];
									
									if(!($stmt = $mysqli->prepare("SELECT fname, lname, birthYear, gender, zip FROM mtn_climber c WHERE gender = ? AND birthYear < ? AND birthYear > ?"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!($stmt->bind_param("sii",$_POST['f3Gender'],$maxYear,$minYear))){
										echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!$stmt->execute()){
										echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
								}
								else
								{
									if(!($stmt = $mysqli->prepare("SELECT fname, lname, birthYear, gender, zip FROM mtn_climber c WHERE gender = ? AND birthYear < ?"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!($stmt->bind_param("si",$_POST['f3Gender'],$maxYear))){
										echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!$stmt->execute()){
										echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
								}
							}
							else
							{
								//Check if maximum age is provided
								if (!empty($_POST['f3AgeMax']))
								{
									//Calculate min birth year
									$minYear = $curYear - $_POST['f3AgeMax'];
									
									if(!($stmt = $mysqli->prepare("SELECT fname, lname, birthYear, gender, zip FROM mtn_climber c WHERE gender = ? AND birthYear > ?"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!($stmt->bind_param("si",$_POST['f3Gender'],$minYear))){
										echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!$stmt->execute()){
										echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
								}
								else
								{
									if(!($stmt = $mysqli->prepare("SELECT fname, lname, birthYear, gender, zip FROM mtn_climber c WHERE gender = ?"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!($stmt->bind_param("s",$_POST['f3Gender']))){
										echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!$stmt->execute()){
										echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
								}
							}
						}
					}
					else
					{
						//Check if a zip code is provided
						if (!empty($_POST['f3Zip']))
						{
							//Check if minimum age is provided
							if (!empty($_POST['f3AgeMin']))
							{
								//Calculate max birth year
								$maxYear = $curYear - $_POST['f3AgeMin'];
								
								//Check if maximum age is provided
								if (!empty($_POST['f3AgeMax']))
								{
									//Calculate min birth year
									$minYear = $curYear - $_POST['f3AgeMax'];
									
									if(!($stmt = $mysqli->prepare("SELECT fname, lname, birthYear, gender, zip FROM mtn_climber c WHERE zip = ? AND birthYear < ? AND birthYear > ?"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!($stmt->bind_param("iii",$_POST['f3Zip'],$maxYear,$minYear))){
										echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!$stmt->execute()){
										echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
								}
								else
								{
									if(!($stmt = $mysqli->prepare("SELECT fname, lname, birthYear, gender, zip FROM mtn_climber c WHERE zip = ? AND birthYear < ?"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!($stmt->bind_param("ii",$_POST['f3Zip'],$maxYear))){
										echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!$stmt->execute()){
										echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
								}
							}
							else
							{
								//Check if maximum age is provided
								if (!empty($_POST['f3AgeMax']))
								{
									//Calculate min birth year
									$minYear = $curYear - $_POST['f3AgeMax'];
									
									if(!($stmt = $mysqli->prepare("SELECT fname, lname, birthYear, gender, zip FROM mtn_climber c WHERE zip = ? AND birthYear > ?"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!($stmt->bind_param("ii",$_POST['f3Zip'],$minYear))){
										echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!$stmt->execute()){
										echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
								}
								else
								{
									if(!($stmt = $mysqli->prepare("SELECT fname, lname, birthYear, gender, zip FROM mtn_climber c WHERE zip = ?"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!($stmt->bind_param("i",$_POST['f3Zip']))){
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
							//Check if minimum age is provided
							if (!empty($_POST['f3AgeMin']))
							{
								//Calculate max birth year
								$maxYear = $curYear - $_POST['f3AgeMin'];
								
								//Check if maximum age is provided
								if (!empty($_POST['f3AgeMax']))
								{
									//Calculate min birth year
									$minYear = $curYear - $_POST['f3AgeMax'];
									
									if(!($stmt = $mysqli->prepare("SELECT fname, lname, birthYear, gender, zip FROM mtn_climber c WHERE birthYear < ? AND birthYear > ?"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!($stmt->bind_param("ii",$maxYear,$minYear))){
										echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!$stmt->execute()){
										echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
								}
								else
								{
									if(!($stmt = $mysqli->prepare("SELECT fname, lname, birthYear, gender, zip FROM mtn_climber c WHERE birthYear < ?"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!($stmt->bind_param("i",$maxYear))){
										echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!$stmt->execute()){
										echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
								}
							}
							else
							{
								//Check if maximum age is provided
								if (!empty($_POST['f3AgeMax']))
								{
									//Calculate min birth year
									$minYear = $curYear - $_POST['f3AgeMax'];
									
									if(!($stmt = $mysqli->prepare("SELECT fname, lname, birthYear, gender, zip FROM mtn_climber c WHERE birthYear > ?"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!($stmt->bind_param("i",$minYear))){
										echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!$stmt->execute()){
										echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
								}
								else
								{
									if(!($stmt = $mysqli->prepare("SELECT fname, lname, birthYear, gender, zip FROM mtn_climber c"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!$stmt->execute()){
										echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
								}
							}
						}
					}

					if(!$stmt->bind_result($fname, $lname, $birthYear, $gender, $zip)){
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					while($stmt->fetch()){
						$curAge = $curYear - $birthYear;
						
					 echo "<tr>\n<td class='txtCenter'>\n" . $fname . "\n</td>\n<td class='txtCenter'>\n" . $lname . "\n</td>\n<td class='txtCenter'>\n" . $curAge . "\n</td>\n<td class='txtCenter'>\n" . $gender . "\n</td>\n<td class='txtCenter'>\n" . $zip . "\n</td>\n</tr>";
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