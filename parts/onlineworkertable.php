<?php
if (!isset($fromhome)) {
	die ("You can't access this file directly");
}
//Get a list of the online workers, if > 0 display else suggest they add some
	include 'mysqlcon.php';
	$stmt = $conn->prepare('SELECT * FROM rigstats WHERE userid = ? AND dtm >= NOW() - INTERVAL 15 MINUTE AND id IN (SELECT MAX(id) FROM rigstats GROUP BY worker) ORDER BY worker');
	$stmt->bind_param('s',$_SERVER['REMOTE_USER']);
	$stmt->execute();
	$result = $stmt->get_result();
	mysqli_close($conn);

if ($result->num_rows > 0) {
?>
<table style="width:auto" id="workers">
	<caption>Online Workers</caption>
	<tr>
		<th>Worker Name</th>
		<th>Algorithm<br>Hashes (or Solutions)/sec</th>
		<th>Watts<br>Temp &#8451;</th>
		<th>Projected Revenue<br>(Coin)</th>
		<th>Projected Revenue<br>(Fiat)</th>
		<th>Projected Revenue<br>(BTC)</th>
	</tr>

<?php
	$FiatSum = '0';
	$BTCSum = '0';
	$wattsum = '0';
	while ($row = $result->fetch_assoc()) {
		echo "<tr>";
			echo "<td>" . $row['worker'] . "</td>";
			echo "<td>" . $row['algo'] . "<br><a href='./?page=minerstats&worker=" . $row['worker'] . "&stat=hashrate'>" . ConvertToHashrate($row['hashrate']) . "</a></td>";
			echo "<td>"; if (!empty($row['watts'])) { echo "<a href='.?page=minerstats&worker=" . $row['worker'] . "&stat=watts'>" . $row['watts'] . "</a>w<br><a href='./?page=minerstats&worker=" . $row['worker'] . "&stat=temp'>" . $row['temp'] . "</a>°"; } else { echo '&nbsp;'; } echo "</td>";
			echo "<td>"; if (!empty($row['coin_revenue'])) { echo "<a href='./?page=minerstats&worker=" . $row['worker'] . "&stat=coin_revenue'>" . (float)$row['coin_revenue'] . "</a>"; } else { echo '&nbsp;'; } echo "</td>";
			echo "<td><a href='./?page=minerstats&worker=" . $row['worker'] . "&stat=fiat_revenue'>" . $row['fiat_revenue'] . "</a></td>";
			echo "<td><a href='./?page=minerstats&worker=" . $row['worker'] . "&stat=btc_revenue'>" . $row['btc_revenue'] . "</a></td>";
		echo "</tr>";
		$FiatSum += $row['fiat_revenue'];
		$BTCSum += $row['btc_revenue'];
		$wattsum += $row['watts'];
	}
	echo "<tr><th colspan='2' style='text-align: right'>Projected:</th><th>" . ConvertToWattrate($wattsum*24) . "H/Day<br>" . ConvertToWattrate($wattsum*24*30) . "H/Month<br>" . ConvertToWattrate($wattsum*24*365) . "H/Year</th><th>&nbsp;</th><th>" . number_format($FiatSum, 2) . "/Day<br>" . number_format($FiatSum * 30, 2) . "/Month<br>" . number_format($FiatSum * 365, 2) . "/Year</th><th>" . $BTCSum . "/Day<br>" . $BTCSum * 30 . "/Month<br>" . $BTCSum * 365 . "/Year</th></tr>";

?>
</table>
<?php
}
?>