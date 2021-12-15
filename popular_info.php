<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
  <title> Popularity Information </title>
</head>

<body>
<h1> Popularity Information </h1>

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



<b> What is the most popular major for recent graduates? </b>

<table border="1" align="center">
<tr>
  <td>Major ID</td>
  <td>Major Name</td>
  <td>Total</td>
  <td>Men</td>
  <td>Women</td>
</tr>

<?php

$query = mysqli_query($conn, "SELECT a.major_id, a.major_name, r.total, r.men, r.women from Majors AS a INNER JOIN  RecentGradsGender AS r USING(major_id) GROUP BY total DESC LIMIT 1;")
   or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
"<tr>
    <td>{$row['major_id']}</td>
    <td>{$row['major_name']}</td>
    <td>{$row['total']}</td>
    <td>{$row['men']}</td>
    <td>{$row['women']}</td>
   </tr>";
}

?>
</table>



<br><br>

<b> What is the most popular major for all ages? </b>

<table border="1" align="center">
<tr>
  <td>Major ID </td>
  <td>Major Name</td>
  <td>Total</td>
  <td>Employed</td>
  <td>Full-Time</td>
  <td>Unemployed</td>
  <td>Unemployed Rate</td>
</tr>

<?php

$query = mysqli_query($conn, "SELECT a.major_id, r.major_name, a.total, a.employed, a.employed_full_time_year_round, a.unemployed, a.unemployment_rate FROM AllAgesEmployment AS a INNER JOIN Majors AS r USING(major_id) GROUP BY total DESC LIMIT 1;")
   or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
   "<tr>
    <td>{$row['major_id']}</td>
    <td>{$row['major_name']}</td>
    <td>{$row['total']}</td>
    <td>{$row['employed']}</td>
    <td>{$row['employed_full_time_year_round']}</td>
    <td>{$row['unemployed']}</td>
    <td>{$row['unemployment_rate']}</td>
   </tr>";
}

?>
</table>



<br><br>

<b> Which major category has the largest number of unique majors? </b>

<table border="1" align="center">
<tr>
  <td>Major Category</td>
  <td>COUNT</td>
</tr>

<?php

$query = mysqli_query($conn, "SELECT major_category, COUNT(major_category) as count FROM Majors GROUP BY major_category ORDER BY count DESC LIMIT 1;")
   or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
"<tr>

    <td>{$row['major_category']}</td>
    <td>{$row['count']}</td>
   </tr>";
}

?>
</table>



<br><br>

<b> What is the total number of recent graduates for each major category? </b>

<table border="1" align="center">
<tr>
  <td>Major Category</td>
  <td>Total Recent Graduates</td>

<?php

$query = mysqli_query($conn, "select m.major_category, sum(rg.total)
        from Majors as m, RecentGradsGender as rg
        Where m.major_id = rg.major_id
        Group by m.major_category order by sum(rg.total) desc;")
          or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
   "<tr>
   <td>{$row['major_category']}</td>
   <td>{$row['sum(rg.total)']}</td>
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
  <b> What is the most popular major in the </b>
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
  <td>Major Name</td>
  <td>Total Graduates</td>
</tr>

<?php
$category = strval($_GET['category']);

$query = mysqli_query($conn, "select major_name, total from (select major_id, total from (select major_id, total from AllAgesEmployment where major_id in (select major_id from Majors where major_category = '$category')) as Totals where total = (select max(total) from (select major_id, total from AllAgesEmployment where major_id in (select major_id from Majors where major_category = '$category')) as Totals)) as MaxTotal join Majors on MaxTotal.major_id = Majors.major_id;")
   or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
   "<tr>
    <td>{$row['major_name']}</td>
    <td>{$row['total']}</td>
   </tr>";
}

?>
</table>



<br><br>

<a href="index.html">Return to Home Page</a>

</body>
</html>
