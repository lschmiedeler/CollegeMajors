<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
  <title> Salary Information </title>
</head>

<body>
  <h1> Salary Information </h1>

<?php
$servername = "localhost";
$username = "root";
$password = "password";
$db = "CollegeMajors";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
	  die("Connection failed: " . $conn->connect_error);
}
?>



<b> Which major has the highest median salary for all ages? </b>

<table border="1" align="center">
<tr>
  <td>Major Name</td>
  <td>Median Salary</td>

<?php

$query = mysqli_query($conn, "SELECT a.median, m.major_name 
	FROM AllAgesEarnings as a, Majors as m
	WHERE m.major_id = a.major_id
	AND median= 
	(SELECT MAX(median) FROM AllAgesEarnings);")
          or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
   "<tr>
   <td>{$row['major_name']}</td>
   <td>{$row['median']}</td>
   </tr>";
}

?>
</table>



<br><br>

<?php
$categories = array();

$query = mysqli_query($conn, "select distinct major_category from Majors;")
   or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  array_push($categories, $row['major_category']);
}
?>


<form name = "form" action = "" method = "get">
  <b> What is the average median salary for recent graduates for all majors in the  </b> 

  <select name = "category">
  <option value = ""><?php echo $_GET['category']; ?></option>
  <?php
  foreach ($categories as &$value) {
    echo '<option value = "'.$value.'">'.$value.'</option>';
  }
  ?>
  </select>
  <b>category?</b>
  <input type = "submit" value = "Submit">
</form>

<table border="1" align="center">
<tr>
  <td>Major Category</td>
  <td>Average Median Salary</td>
</tr>

<?php
$category = strval($_GET['category']);

$query = mysqli_query($conn, "Select m.major_category, AVG(rg.median) 
	from RecentGradsEarnings as rg, Majors as m
	Where m.major_id = rg.major_id 
	And m.major_category = '$category'
	Group by m.major_category;")
	   or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
   "<tr>
    <td>{$row['major_category']}</td>
    <td>{$row['AVG(rg.median)']}</td>
   </tr>";
}

?>
</table>



<br><br>

<form name = "form" action = "" method = "get">
  <b> Which majors have a median salary greater than or equal to </b> <input type = "text" value = "<?php echo $_GET['min_median']; ?>" name = "min_median"> <b>for all ages?</b>
  <input type = "submit" value = "Submit">
</form>

<table border="1" align="center">
<tr>
  <td>Major Name</td>
  <td>Median Salary</td>
</tr>

<?php
$min_median = floatval($_GET['min_median']);

$query = mysqli_query($conn, "select m.major_name, aa.median
	                      from Majors as m, AllAgesEarnings as aa
			      where m.major_id = aa.major_id
			      and aa.median >= $min_median order by aa.median desc;")
  or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
  "<tr>
   <td>{$row['major_name']}</td>
   <td>{$row['median']}</td>
  </tr>";
}

?>
</table>



<br><br>

<a href="index.html">Return to Home Page</a>

</body>
</html>
