<?php
//Make sure it's a valid user and worker, else exit
	//Are the variables set?  If not exit.
	if (empty($_REQUEST['address']) OR empty($_REQUEST['workername'])) { echo "Address and/or Worker required"; exit; }
	
//Set variables
date_default_timezone_set('UTC');
$newdate= date('Y-m-d H:i:s');

//Confirm they are legit user and workers
	$mpmaddress = $_REQUEST['address'];
	$worker = $_REQUEST['workername'];
	if (strpos($worker, '.') !== false) {
		$workersearch =strstr($_REQUEST['workername'], '.', true) . "%";
	} else {
		$workersearch = $worker;
	}
if (strpos($worker, '.') !== false) {
		$workercore =strstr($_REQUEST['workername'], '.', true) . "";
	} else {
		$workercore = $worker;
	}

	include 'mysqlcon.php';
	$stmt = $conn->prepare('SELECT users.id FROM users JOIN workers ON users.id = workers.userid WHERE users.mpmaddress = ? AND workers.worker LIKE ?');
	$stmt->bind_param('ss',$mpmaddress, $workersearch);
	$stmt->execute();
	$result = $stmt->get_result();
	if (mysqli_num_rows($result) < 1) { echo "Invalid UserID and/or Worker"; exit; }
	mysqli_close($conn);


if(empty($_REQUEST['miners'])) { $_REQUEST['miners'] = ''; }
if(empty($_REQUEST['profit'])) { $_REQUEST['profit'] = 0; }

//Determine their userid
	include 'mysqlcon.php';
	$stmt = $conn->prepare('SELECT userid FROM users WHERE mpmaddress = ? LIMIT 1');
	$stmt->bind_param('s',$mpmaddress);
	$stmt->execute();
	$result = $stmt->get_result();
	mysqli_close($conn);
	while ($row = $result->fetch_assoc()) {
    		$userid = $row['userid'];
	}

//update last seen timestamp
	include 'mysqlcon.php';
	$stmt = $conn->prepare('UPDATE rigmanager.workers JOIN rigmanager.users ON users.id = workers.userid SET workers.lastupd = ? WHERE users.mpmaddress = ? AND workers.worker = ?');
	$stmt->bind_param('sss',$newdate, $mpmaddress, $workercore);
	$stmt->execute();
	mysqli_close($conn);

//Set other parameters
	$parsed_json = json_decode($_REQUEST['miners']);
	//var_dump($parsed_json);
	$miner_poola = $parsed_json[0]->Pool;
	$pool = implode(", ", $miner_poola);
	$miner = $parsed_json[0]->Name;
	$algo = implode(",", $parsed_json[0]->Algorithm);
	$hashrate = implode(",", $parsed_json[0]->CurrentSpeed);
	$temp = $_REQUEST['temp'];
	$watts = $_REQUEST['watts'];
	$btc_revenue = $_REQUEST['profit'];
	if (isset($_REQUEST['fiat_revenue'])) {
		$fiat_revenue = $_REQUEST['fiat_revenue'];
	} else {
		//They havent sent fiat (which is normal for MPM) so lets do it for them
		$fromhome = "1";
		include 'parts/nhupd.php';
		$fiat_revenue = $btc_revenue * $exchangeprice;
	}
	

//add to data
	include 'mysqlcon.php';
	$stmt = $conn->prepare('INSERT INTO rigstats (userid, worker, pool, miner, algo, hashrate, temp, watts, fiat_revenue, btc_revenue) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
	$stmt->bind_param('sssssiiidd',$userid, $worker, $pool, $miner, $algo, $hashrate, $temp, $watts, $fiat_revenue, $btc_revenue);
	$stmt->execute();
	mysqli_close($conn);

//Display what was posted for debugging purposes
//print_r($_REQUEST);

echo "success";

?>