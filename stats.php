<table>
<?php
include 'mysqlcon.php';
$totalusers = mysqli_fetch_array(mysqli_query($conn, "SELECT count(id) as totalusers from rigmanager.users"))['totalusers'];
echo "<tr><td>Total Users</td><td>" . $totalusers . "</td></tr>";
$activeusers = mysqli_fetch_array(mysqli_query($conn, "SELECT count(distinct userid) as activeusers FROM rigmanager.workers WHERE lastupd >= NOW() - INTERVAL 1 DAY"))['activeusers'];
echo "<tr><td>Users Active (last 24 hours)</td><td>" . $activeusers . "</td></tr>";
$activerigs = mysqli_fetch_array(mysqli_query($conn, "select count(distinct userid,worker) as activerigs from rigmanager.workers WHERE lastupd >= NOW() - INTERVAL 1 DAY"))['activerigs'];
echo "<tr><td>Rigs Active (last 24 hours)</td><td>" . $activerigs . "</td></tr>";
$updatecount = mysqli_fetch_array(mysqli_query($conn, "SELECT count(id) as updatecount FROM rigmanager.rigstats WHERE dtm >= NOW() - INTERVAL 1 DAY"))['updatecount'];
echo "<tr><td>Number of Updates (last 24 hours)</td><td>" . $updatecount . "</td></tr>";
?>
</table>