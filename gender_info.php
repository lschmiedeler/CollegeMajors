<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
  <title> Gender Information </title>
</head>

<body>
  <h1> Gender Information </h1>

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
# echo "Connected successfully";
?>



<b> What major category for recent graduates has the highest numbers of women and men? </b>
<br><br>
<b> WOMEN </b>
<!--  RecentGradsGender(major_id, total, men, women) -->
<table border="1" align="center">
<tr>
  <td>Major ID</td>
  <td>Major Name</td>
  <td>Women</td>
  <td>Total</td>
</tr>

<?php

$query = mysqli_query($conn, "SELECT a.major_id, r.major_name, a.women, a.total  from Majors AS r INNER JOIN  RecentGradsGender AS a USING(major_id) GROUP BY women DESC LIMIT 1;")
   or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
"<tr>
    <td>{$row['major_id']}</td>
    <td>{$row['major_name']}</td>
    <td>{$row['women']}</td>
    <td>{$row['total']}</td>
   </tr>";
}

?>

</table>
<b> MEN </b>
<!--  RecentGradsGender(major_id, total, men, women) -->
<table border="1" align="center">
<tr>
  <td>Major ID</td>
  <td>Major Name</td>
  <td>Men</td>
  <td>Total</td>
</tr>

<?php

$query = mysqli_query($conn, "SELECT a.major_id, r.major_name, a.men, a.total  from Majors AS r INNER JOIN  RecentGradsGender AS a USING(major_id) GROUP BY men DESC LIMIT 1;")
   or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
"<tr>
    <td>{$row['major_id']}</td>
    <td>{$row['major_name']}</td>
    <td>{$row['men']}</td>
    <td>{$row['total']}</td>
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
  <b> What is the number of recent graduate women in the </b>
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
  <td>Major ID</td>
  <td>Major Name</td>
  <td>Major Category</td>
  <td>Women</td>
  <td>Total</td>
</tr>
<?php
$category = strval($_GET['category']);
$query = mysqli_query($conn, "SELECT a.major_id, a.major_name, a.major_category, r.women, r.total FROM Majors AS a INNER JOIN RecentGradsGender AS r USING(major_id) where (major_category = '$category') GROUP BY women DESC LIMIT 1;")
or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
"<tr>
    <td>{$row['major_id']}</td>
    <td>{$row['major_name']}</td>
    <td>{$row['major_category']}</td>
    <td>{$row['women']}</td>
    <td>{$row['total']}</td>
   </tr>";
}

?>
</table>



<br><br>
<a href="index.html">Return to Home Page</a>

</body>

</html>
