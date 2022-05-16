<!-- Part 1 -->
<?php require_once 'nay.php'; ?>
<br><br>
<?php
if (!isset($_POST["btnSearch"])){
?>
<!-- create a form to search for patrol car based on id -->
<form name="forml" method="post"
 action="<?php echo htmlentities(S_SERVER['PHP_SELF']); ?> ">
 <table class="ContentStyle>
 <tr></tr>
 <tr>
 <td>Patrol Car ID :</td>
 <td><input type="text" name="patrolCarId" id="patrolCarId"></td>
 <td><input type="submit" name="btnSearch" id="btnSearch" value=
 "Search"></td>
 </tr>
 </table>
 </form>
 <?php 
 } ?>
 
 
<!-- Part 2 -->
<?php
}
/* if postback via clicking Search button */
else
{
require_once 'db.php'; 

// create database connection
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error); 
} 

// retrieve patrol car detail
 $sql = "SELECT FROM patrolcar WHERE patrolcar_id = ".$_POST['patrolCarId']. 
"'"; 
$result = $conn->query($sql); 
// if the patrol car does not exist, redirect back to update.php
if ($result->num_rows == 0) {
	?>
	<script type="text/javascript">window.location="./update.php";</script>
<?php }
	// else if the patrol car found
	while ($row = $result->fetch_assoc()) {
		$patrolCarId = $row['patrolcar_id'];
		SpatrolCarStatusld = $row['patrolcar_status_id']; 
} 
// retrieve from patrolcar_status table for populating the combo box
 $sql = "SELECT * FROM patrolcar_status";
 $result = $conn->query($sql);
 if ($result->num_rows > 0)	 {
	 while ($row = $result->fetch_assoc()) {
		 $patrolCarStatusArray[$row['patrolcar_status_id']] = $row[		
		 'patrolcar_status_desc']; 
 } 
} 
while ($row = $result->fetch_assoc()) {
	$patrolCarStatusArray[$row['patrolcar_status_id']] = $row[
	'patrolcar_status_desc']; 
	} 
Sconn->close(); 
?>



<!-- display a form for operator to update status of patrol car -->
 <form name="form2" method="post"
 action="<?php echo htmlentities($_SERVERUPHP_SELF'D; "> 
 <table>
 <tr></tr>
 <tr>
 <td>ID :</td>
 <td><?php echo SpatrolCarId ?>
 <input type="hidden" name="patrolCarId" id="patrolCarId"
 value="<?php echo SpatrolCarld ?>">
 </td>
 </tr>
 <tr>
 <td>Status :</td>
 <td><select name="patrolCarStatus" id="patrolCarStatus"›
 <?php foreach( $patrolCarStatusArray as $key => Svalue){ 
 <option value="<?php echo Skey ?>" 
 <?php if ($key==$patrolCarStatusId) 0> selected="selected"
 <?php }?> 
 >
<?php echo $value ?>
 </option>
 <?php } ?>
 </select></td>
 </tr>
 <tr>
 <td><input type="reset" name="btnCancel" id="btnCancel" value="Reset"></td>
 <td>&nbsp;&nbsp;&hbsp;&hbsp;&hbsp;&hbsp;&hbsp;<input type="submit" name=
 "btnUpdate" id="btnUpdate" value="Update">
 </td>
 </tr>
 </table>
 </form>
 
 
 
 <!-- Part 3 -->
 <?php
 /* if postback via clicking Update button */
 if (isset($_POST["btnUpdate"])){
	 require_once 'db.php'; 
	 // create database connection
	 Sconn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	 // Check connection
	 if ($conn->connect_error) { 
	 die("Connection failed: " . Sconn->connect_error);
	 }	 
// update patrol car status
 $sql = "UPDATE patrolcar SET patrolcar_status_id = '".S_POST['patrolCarStatus']."' WHERE patrolcar_id = ".$_POST[ 'patrolCarId']."'"; 
 if ($conn->query($sql)===FALSE) {
	 echo "Error: " . $sql . "<br>" . Sconn->error; 
} 
/* if patrol car status is Arrived (4) then capture the time of arrival */
 if ($_POST["patrolCarStatus"] == '4'){
	 $sql = "UPDATE dispatch SET time_arrived = NOW() WHERE time_arrived is NULL AND patrolcar_id = 1".$_POSTUpatrolCarId'
	 if ($conn->query($sql)===FALSE) {
		 echo "Error: " . $sql . "<br>" . $conn->error; 
} 
} 
/* else if patrol car status is FREE (3) then capture the time of completion / else if ($_POST["patrolCarStatus"] == '3'){
 / First, retrieve the incident ID from dispatch table handled by that patrol car */
 $sql = "SELECT incident_id FROM dispatch WHERE time_completed IS NULL AND patrolcar_id = ".$_POST['patrolCarId']."'";
 Sresult = $conn->query($sql);
 if ($result->num_rows > 0) {
	 while ($row = $result->fetch_assoc()) { 
	 $incidentId = $row['incident_id']; 
} 
}
 // next update dispatch table
 $sql = "UPDATE dispatch SET time_completed = NOW() WHERE time_completed is NULL AND patrolcar_id = '".$_POST[ 'patrolCarId']."'";
 if ($conn->query($sql)===FALSE) { 
 echo "Error: " . $sql . "<br>" . Sconn->error; 
} 
/* update incident table to completed (3) all patrol car attended to it are FREE now */ 
$sql = "UPDATE incident SET incident_status_id = '3' WHERE incident_id = '$incidentId' AND NOT EXISTS (SELECT * FROM dispatch WHERE time_completed IS NULL AND incident_id = '$incidentId')";
 if ($conn->query($sql)===FALSE) { 
 echo "Error: " . $sql . "<br>" . Sconn->error; 
} 
} 
$conn->close();
 ?> 
 <script type="text/javascript">window.location="./logcall.php";</script>
 <?php } ?>