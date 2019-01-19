<?php
//Make sure it's a valid user and worker, else exit
	//Are the variables set?  If not exit.
	if (empty($_REQUEST['userid']) OR empty($_REQUEST['worker'])) { echo "UserID and/or Worker required"; exit; }
	
//Set variables
date_default_timezone_set('UTC');
$newdate= date('Y-m-d H:i:s');

//Confirm they are legit user and workers
	$userid = $_REQUEST['userid'];
	$worker = $_REQUEST['worker'];
	if (strpos($worker, '.') !== false) {
		$workersearch =strstr($_REQUEST['worker'], '.', true) . "%";
	} else {
		$workersearch = $worker;
	}
	if (strpos($worker, '.') !== false) {
		$workercore =strstr($_REQUEST['worker'], '.', true) . "";
	} else {
		$workercore = $worker;
	}
	$workerkey = $_REQUEST['workerkey'];
	
	include 'mysqlcon.php';
	$stmt = $conn->prepare('SELECT users.id FROM users JOIN workers ON users.id = workers.userid WHERE users.userid = ? AND workers.worker LIKE ? AND workers.workerkey = ?');
	$stmt->bind_param('sss',$userid, $workersearch, $workerkey);
	$stmt->execute();
	$result = $stmt->get_result();
	if (mysqli_num_rows($result) < 1) { echo "Invalid UserID and/or Worker"; exit; }
	mysqli_close($conn);

//Set other parameters
	$algo = $_REQUEST['algo'];
	$hashrate = $_REQUEST['hashrate'];
	$diff = $_REQUEST['diff'];
	$fiat_revenue = $_REQUEST['fiat_revenue'];
	$btc_revenue = $_REQUEST['btc_revenue'];
	$pool = $_REQUEST['pool'];
	$miner = $_REQUEST['miner'];
	$temp = $_REQUEST['temp'];
	$watts = $_REQUEST['watts'];

//add to data
	include 'mysqlcon.php';
	$stmt = $conn->prepare('INSERT INTO rigstats (userid, worker, algo, hashrate, diff, fiat_revenue, btc_revenue, pool, miner, temp, watts) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
	$stmt->bind_param('sssiiddssii',$userid, $worker, $algo, $hashrate, $diff, $fiat_revenue, $btc_revenue, $pool, $miner, $temp, $watts);
	$stmt->execute();
	mysqli_close($conn);

//update last seen timestamp
	include 'mysqlcon.php';
	$stmt = $conn->prepare('UPDATE rigmanager.workers JOIN rigmanager.users ON users.id = workers.userid SET workers.lastupd = ? WHERE users.userid = ? AND workers.worker = ?');
	$stmt->bind_param('sss',$newdate, $userid, $workercore);
	$stmt->execute();
	mysqli_close($conn);

//Display what was posted for debugging purposes
if (isset($_REQUEST['debug_enabled']) AND $_REQUEST['debug_enabled'] == '1') {
	print_r($_REQUEST);
}

echo "success";

?>