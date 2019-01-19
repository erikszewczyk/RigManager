<?php
if (!isset($fromhome)) {
	die ("You can't access this file directly");
}
//Get a list of the offline workers, if > 0 display else do nothing
	include 'mysqlcon.php';
	$stmt = $conn->prepare('SELECT worker, dtm FROM rigstats WHERE userid = ? AND dtm < NOW() - INTERVAL 15 MINUTE AND id IN (SELECT MAX(id) FROM rigstats GROUP BY worker) ORDER BY worker');
	$stmt->bind_param('s',$_SERVER['REMOTE_USER']);
	$stmt->execute();
	$result = $stmt->get_result();
	mysqli_close($conn);

if ($result->num_rows > 0) {
?>
<table style="width:auto" id="workers">
	<caption>Offline Workers</caption>
	<tr>
		<th>Worker Name</th>
		<th>Last seen (UTC)</th>
	</tr>
<?php
	while ($row = $result->fetch_assoc()) {
    		echo "<tr>";
			echo "<td>" . $row['worker'] . "</td>";
			//echo "<td>" . $row['dtm'] . "<br>" . time_elapsed_string($row['dtm']) . "</td>";
			if (empty($row['dtm'])) { echo "N/A"; } else { echo "<td>" . $row['dtm'] . "<br>" . time_elapsed_string($row['dtm']) . "</td>"; }
		echo "</tr>";
	}
?>
</table>
<?php
}
?>