<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
  <title> Employment Information </title>
</head>

<body>
  <h1> Employment Information </h1>

  <h2> Jobs Information </h2>

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



<b> What major leads to the highest proportion of non-college jobs for employed recent graduates? </b>

<table border="1" align="center">
<tr>
  <td>Major Name</td>
  <td>Prop Non College</td>
</tr>

<?php

$query = mysqli_query($conn, "select major_name, prop_non_college from (select major_id, prop_non_college from (select NonCollegeJobs.major_id, non_college_jobs / employed as prop_non_college from ((select major_id, non_college_jobs from RecentGradsJobs) as NonCollegeJobs join (select major_id, employed from RecentGradsEmployment) as Total on NonCollegeJobs.major_id = Total.major_id)) as PropsNonCollege where prop_non_college = (select max(prop_non_college) from (select NonCollegeJobs.major_id, non_college_jobs / employed as prop_non_college from ((select major_id, non_college_jobs from RecentGradsJobs) as NonCollegeJobs join (select major_id, employed from RecentGradsEmployment) as Total on NonCollegeJobs.major_id = Total.major_id)) as PropsNonCollege)) as MaxPropNonCollege join Majors on MaxPropNonCollege.major_id = Majors.major_id;")
  or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
   "<tr>
    <td>{$row['major_name']}</td>
    <td>{$row['prop_non_college']}</td>
   </tr>";
}

?>
</table>



<br><br>

<b> What major leads to the highest proportion of low wage jobs for employed recent graduates? </b>

<table border="1" align="center">
<tr>
  <td>Major Name</td>
  <td>Prop Low Wage</td>
</tr>

<?php

$query = mysqli_query($conn, "select major_name, prop_low_wage from (select major_id, prop_low_wage from (select LowWageJobs.major_id, low_wage_jobs / employed as prop_low_wage from ((select major_id, low_wage_jobs from RecentGradsJobs) as LowWageJobs join (select major_id, employed from RecentGradsEmployment) as Total on LowWageJobs.major_id = Total.major_id)) as PropsLowWage where prop_low_wage = (select max(prop_low_wage) from (select LowWageJobs.major_id, low_wage_jobs / employed as prop_low_wage from ((select major_id, low_wage_jobs from RecentGradsJobs) as LowWageJobs join (select major_id, employed from RecentGradsEmployment) as Total on LowWageJobs.major_id = Total.major_id)) as PropsLowWage)) as MaxPropLowWage join Majors on MaxPropLowWage.major_id = Majors.major_id;")
   or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
   "<tr>
    <td>{$row['major_name']}</td>
    <td>{$row['prop_low_wage']}</td>
   </tr>";
}

?>
</table>



<br><br>

<b> What major leads to the highest proportion of full-time year-round jobs for employed recent graduates? </b>

<table border="1" align="center">
<tr>
  <td>Major Name</td>
  <td>Prop Full-Time Year-Round</td>
</tr>

<?php

$query = mysqli_query($conn, "select major_name, prop_full from (select major_id, prop_full from (select major_id, employed_full_time_year_round / employed as prop_full from RecentGradsEmployment) as PropsFull where prop_full = (select max(prop_full) from (select major_id, employed_full_time_year_round / employed as prop_full from RecentGradsEmployment) as PropsFull)) as MaxPropFull join Majors on MaxPropFull.major_id = Majors.major_id;")
   or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
   "<tr>
    <td>{$row['major_name']}</td>
    <td>{$row['prop_full']}</td>
   </tr>";
}

?>
</table>



<br><br>

<b> What major leads to the highest proportion of full-time year-round jobs for employed graduates of all ages? </b>

<table border="1" align="center">
<tr>
  <td>Major Name</td>
  <td>Prop Full-Time Year-Round</td>
</tr>

<?php

$query = mysqli_query($conn, "select major_name, prop_full from (select major_id, prop_full from (select major_id, employed_full_time_year_round / employed as prop_full from AllAgesEmployment) as PropsFull where prop_full = (select max(prop_full) from (select major_id, employed_full_time_year_round / employed as prop_full from AllAgesEmployment where employed_full_time_year_round / employed <= 1) as PropsFull)) as MaxPropFull join Majors on MaxPropFull.major_id = Majors.major_id;")
   or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
   "<tr>
    <td>{$row['major_name']}</td>
    <td>{$row['prop_full']}</td>
   </tr>";
}

?>
</table>



<h2> Unemployment Information </h2>

<b>Which degree has the highest unemployment rate for all ages? </b>

<table border="1" align="center">
<tr>
  <td>Major Name</td>
  <td>Unemployment Rate</td>
</tr>

<?php

$query = mysqli_query($conn, "SELECT a.unemployment_rate, m.major_name
        FROM AllAgesEmployment as a, Majors as m
        WHERE m.major_id = a.major_id
        AND unemployment_rate=
        (SELECT MAX(unemployment_rate) FROM AllAgesEmployment);
        ")
  or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
   "<tr>
    <td>{$row['major_name']}</td>
    <td>{$row['unemployment_rate']}</td>
   </tr>";
}

?>
</table>



<br><br>

<b>Which degree has the lowest unemployment rate for all ages? </b>

<table border="1" align="center">
<tr>
  <td>Major Name</td>
  <td>Unemployment Rate</td>
</tr>

<?php

$query = mysqli_query($conn, "SELECT a.unemployment_rate, m.major_name
        FROM AllAgesEmployment as a, Majors as m
        WHERE m.major_id = a.major_id
        AND unemployment_rate=
        (SELECT MIN(unemployment_rate) FROM AllAgesEmployment);
        ")
  or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
   "<tr>
    <td>{$row['major_name']}</td>
    <td>{$row['unemployment_rate']}</td>
   </tr>";
}

?>
</table>



<br><br>

<form name = "form" action = "" method = "get">
  <b> Which majors have an unemployment rate less than </b> <input type = "text" value = "<?php echo $_GET['max_unrate']; ?>" name = "max_unrate"> <b>for all ages?</b>
  <input type = "submit" value = "Submit">
</form>

<table border="1" align="center">
<tr>
  <td>Major Name</td>
  <td>Unemployment Rate</td>
</tr>

<?php

$max_unrate = floatval($_GET['max_unrate']);

$query = mysqli_query($conn, "select major_name, unemployment_rate from (select major_id, unemployment_rate from AllAgesEmployment where unemployment_rate < $max_unrate) as Unemployment join Majors on Unemployment.major_id = Majors.major_id order by unemployment_rate;")
   or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
   "<tr>
    <td>{$row['major_name']}</td>
    <td>{$row['unemployment_rate']}</td>
   </tr>";
}

?>
</table>



<br><br>

<a href="index.html">Return to Home Page</a>

</body>
</html>
