<?php
if (!isset($fromhome)) {
	die ("You can't access this file directly");
}
date_default_timezone_set('UTC');
$newdate= date('Y-m-d H:i:s');
$pair = "BTC-USD";

//Validate the last time our exchangne rate has been updated
    include 'mysqlcon.php';
    $stmt = $conn->prepare('SELECT id FROM exchange WHERE pair = ? AND lastupd >= NOW() - INTERVAL 15 MINUTE');
	$stmt->bind_param('s',$pair);
	$stmt->execute();
	$result = $stmt->get_result();
    mysqli_close($conn);
    //echo "Initial rows returned: " . $result->num_rows . "<br>";

//If the data is more than 15 minutes old query the Coinbase APIs and update the DB
if ($result->num_rows < 1){
    $url = "https://api.pro.coinbase.com/products/BTC-USD/ticker";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_USERAGENT,"www.rigmanager.xyz");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

    $json_string = $result;
    $parsed_json = json_decode($json_string);

    $btcusd_price =  $parsed_json->{'price'};

    include 'mysqlcon.php';
    $stmt = $conn->prepare('UPDATE exchange SET val = ?, lastupd = ? WHERE pair = ?');
	$stmt->bind_param('dss',$btcusd_price,$newdate,$pair);
	$stmt->execute();
    mysqli_close($conn);

    //echo "Updated Price: " . number_format($btcusd_price, 2) . "<br>";
}

//Get the latest from the DB and store it in our variable
    include 'mysqlcon.php';
    $stmt = $conn->prepare('SELECT val FROM exchange WHERE pair = ? LIMIT 1');
	$stmt->bind_param('s',$pair);
	$stmt->execute();
	$exchangeprice = $stmt->get_result()->fetch_object()->val;
    mysqli_close($conn);
    
    //echo "Price from DB: " . number_format($exchangeprice, 2);
?>