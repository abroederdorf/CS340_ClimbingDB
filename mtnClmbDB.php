<?php
//Turn on error reporting
ini_set('display_errors', 'On');
//Connects to the database
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","broedera-db","u00kFHGxpWuH5GTI","broedera-db");
if($mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	//echo "This works";
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Mountain Climbing Database</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<div id="topDiv">
			<h1>Mountain Climbing Database</h1>
			<p>This database tracks climbs people have completed. </p>
			<div id="tabButtons">
				<button type="button" id="searchButton" class="tabBut">Search Data</button>
				<button type="button" id="addButton" class="tabBut">Add Data</button>
			</div>
		</div>
				  
		<!--Add content -->
		<div id="addDiv">
			<h3>Add Data</h3>
			<p>Add new mountains, routes, people, skills, and climbs completed using the forms. </p>
			
			<!-- Section - Relationship Table: Add routes climbed -->
			<div id="routeClimbed">
				<h3>Routes Climbed</h3>
				<!-- Form to add data -->
				<div class="buttonDiv">
					<div id="rtClAddDiv">
						<form method="post" action="addRtCl.php" autocomplete="off">
							<fieldset>
							<legend>Add a Route Climbed</legend>
								<p>Route:
									<select name="climberRtCl">
										<!--value corresponds to climber id-->
										<?php
											if(!($stmt = $mysqli->prepare("SELECT id, fname, lname FROM mtn_climber ORDER BY lname ASC"))){
												echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
											}

											if(!$stmt->execute()){
												echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
											}
											if(!$stmt->bind_result($id, $fname, $lname)){
												echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
											}
											while($stmt->fetch()){
												echo '<option value=" '. $id . ' "> ' . $fname .' ',' '.$lname . '</option>\n';
											}
											$stmt->close();
										?>
									</select>
								</p>
								<p>Route:
									<select name="routeRtCl">
										<!--value corresponds to route id-->
										<?php
											if(!($stmt = $mysqli->prepare("SELECT r.id, r.name, m.name FROM mtn_route r INNER JOIN mtn_mountain m ON r.mtid = m.id ORDER BY m.name ASC"))){
												echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
											}

											if(!$stmt->execute()){
												echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
											}
											if(!$stmt->bind_result($id, $rname, $mname)){
												echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
											}
											while($stmt->fetch()){
												echo '<option value=" '. $id . ' "> ' . $rname . ", " .$mname . '</option>\n';
											}
											$stmt->close();
										?>
										
									</select>
								</p>
								<p>Date Climbed: <input type="text" name="dateClimbed"> <em>(YYYY-MM-DD)</em></p>
								<input type="submit" id="addRtCl" class="buttons">
							</fieldset>
						</form>
					</div>
					<button onclick="toggle('rtClTblDiv')" class="showButton">Show Table of Data</button>
				</div>
				
				<!-- Form to delete row -->
				<div id="rtClDelDiv">
					<form method="post" action="delRtCl.php" autocomplete="off">
						<fieldset>
							<legend>Delete specific route climbed</legend>
							<p>Climber:
								<select name="delClimberRtCl">
									<!--value corresponds to climber id-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT id, fname, lname FROM mtn_climber ORDER BY lname ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $fname, $lname)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value=" '. $id . ' "> ' . $fname .' ',' '.$lname . '</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<p>Route:
								<select name="delRouteRtCl">
									<!--value corresponds to route id-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT r.id, r.name, m.name FROM mtn_route r INNER JOIN mtn_mountain m ON r.mtid = m.id ORDER BY m.name ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $rname, $mname)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value=" '. $id . ' "> ' . $rname . ", " .$mname . '</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<p>Date Climbed: <input type="text" name="delDateClimbed"> <em>(YYYY-MM-DD)</em></p>
							<input type="submit" id="delRtCl" class="buttons">
						</fieldset>
					</form>
				</div>
				
				<!-- Table to view data in table -->
				<div id="rtClTblDiv">
					<table id="rtSkTbl">
						<tr>
							<th class="txtCenter">First Name</th>
							<th class="txtCenter">Last Name</th>
							<th class="txtCenter">Route</th>
							<th class="txtCenter">Mountain</th>
							<th class="txtCenter">Date Climbed</th>
						</tr>
						<?php
							if(!($stmt = $mysqli->prepare("SELECT c.fname, c.lname, r.name AS rname, m.name, rc.climbDate FROM mtn_climber c INNER JOIN mtn_routeClimbed rc ON rc.cid = c.id INNER JOIN mtn_route r ON r.id = rc.rid INNER JOIN mtn_mountain m ON m.id = r.mtid ORDER BY m.name ASC"))){
								echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
							}

							if(!$stmt->execute()){
								echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
							}
							if(!$stmt->bind_result($fname, $lname, $rname, $name, $climbDate)){
								echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
							}
							while($stmt->fetch()){
							 echo "<tr>\n<td>\n" . $fname . "\n</td>\n<td>\n" . $lname . "\n</td>\n<td class='txtCenter'>\n" . $rname . "\n</td>\n<td class='txtCenter'>\n" . $name . "\n</td>\n<td class='txtCenter'>\n" . $climbDate . "\n</td>\n</tr>";
							}
							$stmt->close();
						?>
					</table>
				</div>
			</div>
		
			<!-- Section - Entity Table: Add climbers -->
			<div id="peopleDiv">
				<h3>Climber</h3>
				<!-- Form to add data -->
				<div class="buttonDiv">
					<div id="peopleAddDiv">
						<form method="post" action="addClimber.php" autocomplete="off">
							<fieldset>
							<legend>Add Climber</legend>
								<p>First Name: <input type="text" name="fname"></p>
								<p>Last Name: <input type="text" name="lname"></p>
								<p>Birth Year: <input type="number" name="birthYear"></p>
								<span>Gender:
									<select name="gender">
										<option value="female">Female</option>
										<option value="male">Male</option>
										<option value="other">Other</option>
										<option value="not disclosed">Not Disclosed</option>
									</select>
								</span>
								<p>Zip Code: <input type="number" name="zip"></p>
								<input type="submit" id="addClimber" class="buttons">
							</fieldset>
						</form>
					</div>
					<button onclick="toggle('peopleTblDiv')" class="showButton">Show Table of Data</button>
				</div>
				
				<!-- Form to update climber information -->
				<div class="UpdateDiv">
					<form method="post" action="updateClimber.php" autocomplete="off">
						<fieldset>
						<legend>Update Climber Information</legend>
							<p>Name:
								<select name="climberUp">
									<!--value corresponds to climber id-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT id, fname, lname FROM mtn_climber ORDER BY lname ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $fname, $lname)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value=" '. $id . ' "> ' . $fname .' ',' '.$lname . '</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<p>First Name: <input type="text" name="fnameUp"></p>
							<p>Last Name: <input type="text" name="lnameUp"></p>
							<p>Birth Year: <input type="number" name="birthYearUp"></p>
							<span>Gender:
								<select name="genderUp">
									<option value="No Change">No Change</option>
									<option value="female">Female</option>
									<option value="male">Male</option>
									<option value="other">Other</option>
									<option value="not disclosed">Not Disclosed</option>
								</select>
							</span>
							<p>New Zip Code: <input type="number" name="zipUp"></p>
							<input type="submit" id="updateClimber" class="buttons">
						</fieldset>
					</form>
				</div>
				
				<!-- Table to view data in table -->
				<div id="peopleTblDiv">
					<table id="peopleTbl">
						<tr>
							<th class="txtCenter">First Name</th>
							<th class="txtCenter">Last Name</th>
							<th class="txtCenter">Birth Year</th>
							<th class="txtCenter">Gender</th>
							<th class="txtCenter">Zip Code</th>
						</tr>
						<?php
							if(!($stmt = $mysqli->prepare("SELECT fname, lname, birthYear, gender, zip FROM mtn_climber ORDER BY lname ASC"))){
								echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
							}

							if(!$stmt->execute()){
								echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
							}
							if(!$stmt->bind_result($fname, $lname, $birthYear, $gender, $zip)){
								echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
							}
							while($stmt->fetch()){
							 echo "<tr>\n<td>\n" . $fname . "\n</td>\n<td>\n" . $lname . "\n</td>\n<td class='txtCenter'>\n" . $birthYear . "\n</td>\n<td class='txtCenter'>\n" . $gender . "\n</td>\n<td class='txtCenter'>\n" . $zip . "\n</td>\n</tr>";
							}
							$stmt->close();
						?>
					</table>
				</div>
			</div>
			
			<!-- Section - Entity Table: Add mountains -->
			<div id="mtnDiv">
				<h3>Mountain</h3>
				<!-- Form to add data -->
				<div class="buttonDiv">
					<div id="mtnAddDiv">
						<form method="post" action="addMountain.php" autocomplete="off">
							<fieldset>
							<legend>Add Mountain</legend>
								<p>Name: <input type="text" name="mname"></p>
								<p>Elevation: <input type="number" name="elev"></p>
								<p>Location: <input type="text" name="location"></p>
								<input type="submit" id="addMtn" class="buttons">
							</fieldset>
						</form>
					</div>
					<button onclick="toggle('mtnTblDiv')" class="showButton">Show Table of Data</button>
				</div>
				
				<!-- Form to update mountain information -->
				<div class="UpdateDiv">
					<form method="post" action="updateMountain.php" autocomplete="off">
						<fieldset>
						<legend>Update Mountain Information</legend>
							<p>Name:
								<select name="mtnIdUp">
									<!--value corresponds to mountain id-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT id, name FROM mtn_mountain ORDER BY name ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $name)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value=" '. $id . ' "> ' . $name . '</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<p>New Name: <input type="text" name="mtnUp"></p>
							<p>New Elevation: <input type="number" name="elevUp"></p>
							<p>New Location: <input type="text" name="locationUp"></p>
							<input type="submit" class="buttons">
						</fieldset>
					</form>
				</div>
				
				<!-- Table to view data in table -->
				<div id="mtnTblDiv">
					<table id="mtnTbl">
						<tr>
							<th class="txtCenter">Name</th>
							<th class="txtCenter">Elevation [ft]</th>
							<th class="txtCenter">Location</th>
						</tr>
						<?php
							if(!($stmt = $mysqli->prepare("SELECT name, elevation, location FROM mtn_mountain ORDER BY name ASC"))){
								echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
							}

							if(!$stmt->execute()){
								echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
							}
							if(!$stmt->bind_result($name, $elevation, $location)){
								echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
							}
							while($stmt->fetch()){
							 echo "<tr>\n<td>\n" . $name . "\n</td>\n<td class='txtCenter'>\n" . $elevation . "\n</td>\n<td class='txtCenter'>\n" . $location . "\n</td>\n</tr>";
							}
							$stmt->close();
						?>
					</table>
				</div>
			</div>
			
			<!-- Section - Entity Table: Add routes on mountains -->
			<div id="routeDiv">
				<h3>Route</h3>
				<!-- Form to add data -->
				<div class="buttonDiv">
					<div id="routeAddDiv">
						<form method="post" action="addRoute.php" autocomplete="off">
							<fieldset>
							<legend>Add Route</legend>
								<p>Name: <input type="text" name="rname"></p>
								<span>Mountain:
									<select name="mtn">
										<!--value corresponds to mountain id-->
										<?php
											if(!($stmt = $mysqli->prepare("SELECT id, name FROM mtn_mountain m ORDER BY name ASC"))){
												echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
											}

											if(!$stmt->execute()){
												echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
											}
											if(!$stmt->bind_result($id, $name)){
												echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
											}
											while($stmt->fetch()){
												echo '<option value=" '. $id . ' "> ' . $name . '</option>\n';
											}
											$stmt->close();
										?>
									</select>
								</span>
								<br>
								<input type="submit" id="addRoute" class="buttons">
							</fieldset>
						</form>
					</div>
					<button onclick="toggle('routeTblDiv')" class="showButton">Show Table of Data</button>
				</div>
				
				<!-- Form to update route information -->
				<div class="UpdateDiv">
					<form method="post" action="updateRoute.php" autocomplete="off">
						<fieldset>
						<legend>Update Route Name</legend>
							<p>Name:
								<select name="routeIdUp">
									<!--value corresponds to route id-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT r.id, r.name, m.name FROM mtn_route r INNER JOIN mtn_mountain m ON r.mtid = m.id ORDER BY m.name ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $rname, $mname)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value=" '. $id . ' "> ' . $rname . ", " .$mname . '</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<p>New Name: <input type="text" name="routeUp"></p>
							<p>New Mountain:
								<select name="newMtn">
									<option value="No Change">No Change</option>
									<!-- Value corresponds to mountain id -->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT id, name FROM mtn_mountain ORDER BY name ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $name)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value=" '. $id . ' "> ' . $name . '</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<input type="submit" class="buttons">
						</fieldset>
					</form>
				</div>
				
				<!-- Table to view data in table -->
				<div id="routeTblDiv">
					<table id="routeTbl">
						<tr>
							<th class="txtCenter">Name</th>
							<th class="txtCenter">Mountain</th>
						</tr>
						<?php
							if(!($stmt = $mysqli->prepare("SELECT r.name, m.name FROM mtn_route r INNER JOIN mtn_mountain m ON r.mtid = m.id ORDER BY m.name ASC"))){
								echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
							}

							if(!$stmt->execute()){
								echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
							}
							if(!$stmt->bind_result($rname, $mname)){
								echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
							}
							while($stmt->fetch()){
							 echo "<tr>\n<td class='txtCenter'>\n" . $rname . "\n</td>\n<td class='txtCenter'>\n" . $mname . "\n</td>\n</tr>";
							}
							$stmt->close();
						?>
					</table>
				</div>
			</div>
			
			<!-- Section - Entity Table: Add skills -->
			<div id="skillDiv">
				<h3 id="skillHdr">Skill</h3>
				<!-- Form to add data -->
				<div class="buttonDiv">
					<div id="skillAddDiv">
						<form method="post" action="addSkill.php" autocomplete="off">
							<fieldset>
							<legend>Add Skill</legend>
								<p>Name: <input type="text" name="sname"></p>
								<input type="submit" id="addSkill" class="buttons">
							</fieldset>
						</form>
					</div>
					<button onclick="toggle('skillTblDiv')" class="showButton">Show Table of Data</button>
				</div>
				
				<!-- Form to update skill name -->
				<div class="UpdateDiv">
					<form method="post" action="updateSkill.php" autocomplete="off">
						<fieldset>
						<legend>Update Skill Name</legend>
							<p>Name:
								<select name="skillIdUp">
									<!--value corresponds to skill id-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT id, name FROM mtn_skill ORDER BY name ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $name)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value="'. $id .'"> ' . $name . '</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<p>New Name: <input type="text" name="skillUp"></p>
							<input type="submit" class="buttons">
						</fieldset>
					</form>
				</div>
				
				<!-- Table to view data in table -->
				<div id="skillTblDiv">
					<table id="skillTbl">
						<tr>
							<th class="txtCenter">Name</th>
						</tr>
						<?php
							if(!($stmt = $mysqli->prepare("SELECT name FROM mtn_skill ORDER BY name ASC"))){
								echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
							}

							if(!$stmt->execute()){
								echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
							}
							if(!$stmt->bind_result($name)){
								echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
							}
							while($stmt->fetch()){
							 echo "<tr>\n<td class='txtCenter'>\n" . $name . "\n</td>\n</tr>";
							}
							$stmt->close();
						?>
					</table>
				</div>
			</div>
			
			<!-- Section - Relationship Table: Add skills to climbers -->
			<div id="climberSkill">
				<h3>Climber Skills</h3>
				<!-- Form to add data -->
				<div class="buttonDiv">
					<div id="cliSkAddDiv">
						<form method="post" action="addClSk.php" autocomplete="off">
							<fieldset>
							<legend>Add Skill to a Climber</legend>
								<p>Climber:
									<select name="climberCliSk">
										<!--value corresponds to climber id-->
										<?php
											if(!($stmt = $mysqli->prepare("SELECT id, fname, lname FROM mtn_climber ORDER BY lname ASC"))){
												echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
											}

											if(!$stmt->execute()){
												echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
											}
											if(!$stmt->bind_result($id, $fname, $lname)){
												echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
											}
											while($stmt->fetch()){
												echo '<option value=" '. $id . ' "> ' . $fname .' ',' '.$lname . '</option>\n';
											}
											$stmt->close();
										?>
									</select>
								</p>
								<p>Skill:
									<select name="skillCliSk">
										<!--value corresponds to skill id-->
										<?php
											if(!($stmt = $mysqli->prepare("SELECT id, name FROM mtn_skill ORDER BY name ASC"))){
												echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
											}

											if(!$stmt->execute()){
												echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
											}
											if(!$stmt->bind_result($id, $name)){
												echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
											}
											while($stmt->fetch()){
												echo '<option value=" '. $id . ' "> ' . $name .'</option>\n';
											}
											$stmt->close();
										?>
									</select>
								</p>
								<input type="submit" id="addCliSk" class="buttons">
							</fieldset>
						</form>
					</div>
					<button onclick="toggle('cliSkTblDiv')" class="showButton">Show Table of Data</button>
				</div>
				
				<!-- Form to delete row -->
				<div id="clSkDelDiv">
					<form method="post" action="delClSk.php" autocomplete="off">
						<fieldset>
						<legend>Delete Climber and Skill Pair</legend>
							<p>Climber:
									<select name="delClimberCliSk">
										<!--value corresponds to climber id-->
										<?php
											if(!($stmt = $mysqli->prepare("SELECT id, fname, lname FROM mtn_climber ORDER BY lname ASC"))){
												echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
											}

											if(!$stmt->execute()){
												echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
											}
											if(!$stmt->bind_result($id, $fname, $lname)){
												echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
											}
											while($stmt->fetch()){
												echo '<option value=" '. $id . ' "> ' . $fname .' ',' '.$lname . '</option>\n';
											}
											$stmt->close();
										?>
									</select>
								</p>
								<p>Skill:
									<select name="delSkillCliSk">
										<!--value corresponds to skill id-->
										<?php
											if(!($stmt = $mysqli->prepare("SELECT id, name FROM mtn_skill ORDER BY name ASC"))){
												echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
											}

											if(!$stmt->execute()){
												echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
											}
											if(!$stmt->bind_result($id, $name)){
												echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
											}
											while($stmt->fetch()){
												echo '<option value=" '. $id . ' "> ' . $name .'</option>\n';
											}
											$stmt->close();
										?>
									</select>
								</p>
							<input type="submit" id="delClSk" class="buttons">
						</fieldset>
					</form>
				</div>
				
				<!-- Table to view data in table -->
				<div id="cliSkTblDiv">
					<table id="cliSkTbl">
						<tr>
							<th class="txtCenter">First Name</th>
							<th class="txtCenter">Last Name</th>
							<th class="txtCenter">Skill</th>
						</tr>
						<?php
							if(!($stmt = $mysqli->prepare("SELECT c.fname, c.lname, s.name FROM mtn_climber c INNER JOIN mtn_climberSkill cs ON cs.cid = c.id INNER JOIN mtn_skill s ON s.id = cs.sid ORDER BY lname ASC"))){
								echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
							}

							if(!$stmt->execute()){
								echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
							}
							if(!$stmt->bind_result($fname, $lname, $sname)){
								echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
							}
							while($stmt->fetch()){
							 echo "<tr>\n<td>\n" . $fname . "\n</td>\n<td>\n" . $lname . "\n</td>\n<td class='txtCenter'>\n" . $sname . "\n</td>\n</tr>";
							}
							$stmt->close();
						?>
					</table>
				</div>
			</div>
			
			<!-- Section - Relationship Table: Add reqired skills to routes -->
			<div id="routeSkill">
				<h3>Route Skills</h3>
				<!-- Form to add data -->
				<div class="buttonDiv">
					<div id="rtSkAddDiv">
						<form method="post" action="addRtSk.php" autocomplete="off">
							<fieldset>
							<legend>Add required Skill to a Route</legend>
								<p>Route:
									<select name="routeRtSk">
										<!--value corresponds to route id-->
										<?php
											if(!($stmt = $mysqli->prepare("SELECT r.id, r.name, m.name FROM mtn_route r INNER JOIN mtn_mountain m ON r.mtid = m.id ORDER BY m.name ASC"))){
												echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
											}

											if(!$stmt->execute()){
												echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
											}
											if(!$stmt->bind_result($id, $rname, $mname)){
												echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
											}
											while($stmt->fetch()){
												echo '<option value=" '. $id . ' "> ' . $rname . ", " .$mname . '</option>\n';
											}
											$stmt->close();
										?>
									</select>
								</p>
								<p>Skill:
									<select name="skillRtSk">
										<!--value corresponds to skill id-->
										<?php
											if(!($stmt = $mysqli->prepare("SELECT id, name FROM mtn_skill ORDER BY name ASC"))){
												echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
											}

											if(!$stmt->execute()){
												echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
											}
											if(!$stmt->bind_result($id, $name)){
												echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
											}
											while($stmt->fetch()){
												echo '<option value=" '. $id . ' "> ' . $name .'</option>\n';
											}
											$stmt->close();
										?>
									</select>
								</p>
								<input type="submit" id="addRtSk" class="buttons">
							</fieldset>
						</form>
					</div>
					<button onclick="toggle('rtSkTblDiv')" class="showButton">Show Table of Data</button>
				</div>
				
				<!-- Form to delete row -->
				<div id="rtSkDelDiv">
					<form method="post" action="delRtSk.php" autocomplete="off">
						<fieldset>
						<legend>Delete route and required skill pair</legend>
							<p>Route:
								<select name="delRouteRtSk">
									<!--value corresponds to route id-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT r.id, r.name, m.name FROM mtn_route r INNER JOIN mtn_mountain m ON r.mtid = m.id ORDER BY m.name ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $rname, $mname)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value=" '. $id . ' "> ' . $rname . ", " .$mname . '</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<p>Skill:
								<select name="delSkillRtSk">
									<!--value corresponds to skill id-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT id, name FROM mtn_skill ORDER BY name ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $name)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value=" '. $id . ' "> ' . $name .'</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<input type="submit" id="delRtSk" class="buttons">
						</fieldset>
					</form>
				</div>
				
				<!-- Table to view data in table -->
				<div id="rtSkTblDiv">
					<table id="rtSkTbl">
						<tr>
							<th class="txtCenter">Route</th>
							<th class="txtCenter">Mountain</th>
							<th class="txtCenter">Skill</th>
						</tr>
						<?php
							if(!($stmt = $mysqli->prepare("SELECT r.name, m.name, s.name FROM mtn_route r INNER JOIN mtn_mountain m ON m.id = r.mtid INNER JOIN mtn_routeSkill rs ON rs.rid = r.id INNER JOIN mtn_skill s ON s.id = rs.sid ORDER BY m.name ASC"))){
								echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
							}

							if(!$stmt->execute()){
								echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
							}
							if(!$stmt->bind_result($rname, $mname, $sname)){
								echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
							}
							while($stmt->fetch()){
							 echo "<tr>\n<td>\n" . $rname . "\n</td>\n<td class='txtCenter'>\n" . $mname . "\n</td>\n<td class='txtCenter'>\n" . $sname . "\n</td>\n</tr>";
							}
							$stmt->close();
						?>
					</table>
				</div>
			</div>
		</div>

		<!-- Search Content-->
		<div id="searchDiv">
			<h3>Search</h3>
			<p>Use the filters to search for routes, climbing partners, or to find a list of who has climbed specific routes.</p>
			
			<div class="filterRow">
				<!-- Filter 1: Routes on mountains -->
				<div id="f1Div" class="filterDiv">
					<fieldset>
						<legend>Routes</legend>
						<p>Find routes on a selected mountain.</p>
						<form  method="post" action="f1_rt.php" autocomplete="off">
							<p>Mountain:
								<select name="f1Mt">
									<!--value corresponds to mountain id-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT id, name FROM mtn_mountain m ORDER BY name ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $name)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value=" '. $id . ' "> ' . $name . '</option>\n';
										}
										$stmt->close();
									?>
								</select>
								<input type="submit" class="filterBut"" value="Search">
							</p>
						</form>
					</fieldset>
				</div>
				
				<!-- Filter 2: Routes based on location and/or elevation -->
				<div id="f2Div" class="filterDiv">
					<fieldset>
						<legend>Routes</legend>
						<p>Find routes on a mountain in a selected location and/or within a specified elevation range.</p>
						<form  method="post" action="f2_rt.php" autocomplete="off">
						<p>Location:
								<select name="f2Loc">
									<option value="0">All locations</option>
									<!--value corresponds to location-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT location FROM mtn_mountain GROUP BY location ORDER BY location ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($loc)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value="'.$loc.'"> ' . $loc .'</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<p>Minimum Elevation: <input type="number" name="f2ElMin"></p>
							<p>Maximum Elevation: <input type="number" name="f2ElMax"></p>
							<input type="submit" class="filterBut"" value="Search">
						</form>
					</fieldset>
				</div>					
			</div>
			
			<div class="filterRow">
				<!-- Filter 10: Routes based on selected skills -->
				<div id="f10Div" class="filterDiv">
					<fieldset>
						<legend>Routes</legend>
						<p>Find routes that require two selected skills</p>
						<form  method="post" action="f10_rt.php" autocomplete="off">
							<p>Skill 1:
								<select name="f10Sk1">
									<!--value corresponds to skill id-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT id, name FROM mtn_skill ORDER BY name ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $name)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value=" '. $id . ' "> ' . $name .'</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<p>Skill 2:
								<select name="f10Sk2">
									<!--value corresponds to skill id-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT id, name FROM mtn_skill ORDER BY name ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $name)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value=" '. $id . ' "> ' . $name .'</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<input type="submit" class="filterBut"" value="Search">
						</form>
					</fieldset>
				</div>
				
				<!-- Filter 11: Climbers based on selected skills -->
				<div id="f11Div" class="filterDiv">
					<fieldset>
						<legend>Climbers</legend>
						<p>Find all climbers that have three selected skills.</p>
						<form  method="post" action="f11_cl.php" autocomplete="off">
							<p>Skill 1:
							<select name="f11Sk1">
								<!--value corresponds to skill id-->
								<?php
									if(!($stmt = $mysqli->prepare("SELECT id, name FROM mtn_skill ORDER BY name ASC"))){
										echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
									}

									if(!$stmt->execute()){
										echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
									if(!$stmt->bind_result($id, $name)){
										echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
									}
									while($stmt->fetch()){
										echo '<option value=" '. $id . ' "> ' . $name .'</option>\n';
									}
									$stmt->close();
								?>
							</select>
							</p>
							<p>Skill 2:
								<select name="f11Sk2">
									<!--value corresponds to skill id-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT id, name FROM mtn_skill ORDER BY name ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $name)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value=" '. $id . ' "> ' . $name .'</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<p>Skill 3:
								<select name="f11Sk3">
									<!--value corresponds to skill id-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT id, name FROM mtn_skill ORDER BY name ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $name)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value=" '. $id . ' "> ' . $name .'</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<input type="submit" class="filterBut"" value="Search">	
						</form>
					</fieldset>
				</div>
			</div>
			
			<div class="filterRow">
				<!-- Filter 3: Climber basesd on zip code, age, and gender -->
				<div id="f3Div" class="filterDiv">
					<fieldset>
						<legend>Climbing Partner</legend>
						<p>Find a climbing partner based on selected gender, zip code, and/or age.</p>
						<form  method="post" action="f3_cl.php" autocomplete="off">
							<p>Gender:
								<select name="f3Gender">
									<option value="all">All</option>
									<option value="female">Female</option>
									<option value="male">Male</option>
									<option value="other">Other</option>
									<option value="not disclosed">Not Disclosed</option>
								</select>
							</p>
							<p>Zip Code: <input type="number" name="f3Zip"></p>
							<p>Minimum Age: <input type="number" name="f3AgeMin"></p>
							<p>Maximum Age: <input type="number" name="f3AgeMax"></p>
							<input type="submit" class="filterBut"" value="Search">
						</form>
					</fieldset>
				</div>
				
				<!-- Filter 4: Climbers who have or have not climbed a specified route -->
				<div id="f4Div" class="filterDiv">
					<fieldset>
						<legend>Climbed Routes</legend>
						<p>Find who has climbed a specified route.</p>
						<form  method="post" action="f4_clRt.php" autocomplete="off">
							<p>Route, Mountain:
								<select name="f4Rt">
									<!--value corresponds to mountain id-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT r.id, r.name, m.name FROM mtn_route r INNER JOIN mtn_mountain m ON r.mtid = m.id ORDER BY m.name ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $rname, $mname)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value=" '. $id . ' "> ' . $rname . ", " .$mname . '</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<p><label><input type="checkbox" name="f4NotBox" value="Not">Find who has not climbed the route</label></p>
							<input type="submit" class="filterBut"" value="Search">
						</form>
					</fieldset>
				</div>
			</div>	
			
			<div class="filterRow">
				<!-- Filter 8: Count of how many times all climbers have climbed a specified route -->
				<div id="f8Div" class="filterDiv">
					<fieldset>
						<legend>Climbed Routes</legend>
						<p>Find how many times every climber in the database has climbed a route.</p>
						<form  method="post" action="f8_clRt.php" autocomplete="off">
							<p>Route, Mountain:
								<select name="f8Rt">
									<!--value corresponds to mountain id-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT r.id, r.name, m.name FROM mtn_route r INNER JOIN mtn_mountain m ON r.mtid = m.id ORDER BY m.name ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $rname, $mname)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value=" '. $id . ' "> ' . $rname . ", " .$mname . '</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<input type="submit" class="filterBut"" value="Search">
						</form>
					</fieldset>
				</div>
				
				<!-- Filter 9: Routes two climbers have climbed together -->
				<div id="f9Div" class="filterDiv">
					<fieldset>
						<legend>Climbed Routes</legend>
						<p>Find all routes that two selected people have climbed together.</p>
						<form  method="post" action="f9_clRt.php" autocomplete="off">
							<p>Climber 1:
								<select name="f9Cl1">
									<!--value corresponds to climber id-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT id, fname, lname FROM mtn_climber ORDER BY lname ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $fname, $lname)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value=" '. $id . ' "> ' . $fname .' ',' '.$lname . '</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<p>Climber 2:
								<select name="f9Cl2">
									<!--value corresponds to climber id-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT id, fname, lname FROM mtn_climber ORDER BY lname ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $fname, $lname)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value=" '. $id . ' "> ' . $fname .' ',' '.$lname . '</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<input type="submit" class="filterBut"" value="Search">
						</form>
					</fieldset>
				</div>
			</div>
			
			<div class="filterRow">
				<!-- Filter 6: Routes a specified climber has climbed -->
				<div id="f6Div" class="filterDiv">
					<fieldset>
						<legend>Climber's Routes</legend>
						<p>Find all the routes a specified climber has climbed.</p>
						<form  method="post" action="f6_clRt.php" autocomplete="off">
							<p>Climber:
								<select name="f6Cl">
									<!--value corresponds to climber id-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT id, fname, lname FROM mtn_climber ORDER BY lname ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $fname, $lname)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value=" '. $id . ' "> ' . $fname .' ',' '.$lname . '</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<input type="submit" class="filterBut"" value="Search">
						</form>
					</fieldset>
				</div>
				
				<!-- Filter 7: Average elevation of all routes a climber has climbed -->
				<div id="f7Div" class="filterDiv">
					<fieldset>
						<legend>Climber's Routes</legend>
						<p>Find the average elevation of all the routes a climber has climbed.</p>
						<form  method="post" action="f7_clRt.php" autocomplete="off">
							<p>Climber:
								<select name="f7Cl">
									<!--value corresponds to climber id-->
									<?php
										if(!($stmt = $mysqli->prepare("SELECT id, fname, lname FROM mtn_climber ORDER BY lname ASC"))){
											echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
										}

										if(!$stmt->execute()){
											echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										if(!$stmt->bind_result($id, $fname, $lname)){
											echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
										}
										while($stmt->fetch()){
											echo '<option value=" '. $id . ' "> ' . $fname .' ',' '.$lname . '</option>\n';
										}
										$stmt->close();
									?>
								</select>
							</p>
							<input type="submit" class="filterBut"" value="Search">
						</form>
					</fieldset>
				</div>
			</div>			
			
		</div>
		
		<!-- Scripts -->
		<script src="tabScript.js"></script>
	</body>
</html>