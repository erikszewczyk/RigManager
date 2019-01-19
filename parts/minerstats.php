<h2>Miner Stats</h2>
<?php
if (!isset($fromhome)) {
	die ("You can't access this file directly");
}

if (empty($_SERVER['REMOTE_USER'])) {
	echo "It doesnt look like you're signed in yet, sign in above to get started.";
} else {
	if (isset($_GET['worker'])) {
		$worker = $_GET['worker'];
	} else {
		$worker = "";
    }
    if (isset($_GET['duration'])) {
		$duration = $_GET['duration'];
	} else {
		$duration = "1";
	}
    
    include 'mysqlcon.php';
	if (isset($_GET['stat']) AND $_GET['stat'] == 'hashrate') {
		$stmt = $conn->prepare('SELECT dtm, hashrate as mystat FROM rigmanager.rigstats where userid = ? AND worker = ? AND dtm >= NOW() - INTERVAL ? DAY;');
		$mystat = 'Hashrate';
	} elseif (isset($_GET['stat']) AND $_GET['stat'] == 'diff') {
		$stmt = $conn->prepare('SELECT dtm, diff as mystat FROM rigmanager.rigstats where userid = ? AND worker = ? AND dtm >= NOW() - INTERVAL ? DAY;');
        $mystat = 'Difficulty';
    } elseif (isset($_GET['stat']) AND $_GET['stat'] == 'watts') {
		$stmt = $conn->prepare('SELECT dtm, watts as mystat FROM rigmanager.rigstats where userid = ? AND worker = ? AND dtm >= NOW() - INTERVAL ? DAY;');
		$mystat = 'Watts';
	} elseif (isset($_GET['stat']) AND $_GET['stat'] == 'temp') {
		$stmt = $conn->prepare('SELECT dtm, temp as mystat FROM rigmanager.rigstats where userid = ? AND worker = ? AND dtm >= NOW() - INTERVAL ? DAY;');
		$mystat = 'Temperature';
	} elseif (isset($_GET['stat']) AND $_GET['stat'] == 'fiat_revenue') {
		$stmt = $conn->prepare('SELECT dtm, fiat_revenue as mystat FROM rigmanager.rigstats where userid = ? AND worker = ? AND dtm >= NOW() - INTERVAL ? DAY;');
		$mystat = 'Fiat Revenue';
	} elseif (isset($_GET['stat']) AND $_GET['stat'] == 'btc_revenue') {
		$stmt = $conn->prepare('SELECT dtm, btc_revenue as mystat FROM rigmanager.rigstats where userid = ? AND worker = ? AND dtm >= NOW() - INTERVAL ? DAY;');
		$mystat = 'BTC Revenue';
	} else {
		$stmt = $conn->prepare('SELECT dtm, hashrate as mystat FROM rigmanager.rigstats where userid = ? AND worker = ? AND dtm >= NOW() - INTERVAL ? DAY;');
		$mystat = 'Hashrate';
	}
	
	$stmt->bind_param('ssi',$_SERVER['REMOTE_USER'],$worker,$duration);
	$stmt->execute();
	$result = $stmt->get_result();
	mysqli_close($conn);
}
?>
<div style="width:800px; height: 300px;">
            <canvas id="myChart"></canvas>
        </div>
        <script>
        var ctx = document.getElementById("myChart");
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [<?php foreach($result as $L1) { echo "'" . $L1['dtm'] . "', "; } ?>],
                datasets: [{
                    label: '<?php echo $worker . " " . $mystat; ?>',
                    data: [<?php foreach($result as $L2) { echo "'" . $L2['mystat'] . "', "; } ?>],
                    backgroundColor: '#b30000',
                    fill: 'origin',
                    pointRadius: '2',
                    spanGaps: false
                }]
            },
            options: {
                maintainAspectRatio:false,
                showLines: false,
                
                
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                        displayFormats: {
                            'hour': 'M/D HH:mm',
                        }
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
        window.onload = function() {
            var ctx = document.getElementById("myChart").getContext("2d");
        };
        </script>