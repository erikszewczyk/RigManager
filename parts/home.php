<p>Free Cryptocurrencing revenue monitoring that can be integrated with any Miner, Staker or MasterNode!</p>
<?php
if (!isset($fromhome)) {
	die ("You can't access this file directly");
}
if (empty($_SERVER['REMOTE_USER'])) {
    echo "<p>It doesnt look like you're signed in yet, sign in above to get started.</p>";
?>
<h2>What is RigManager?</h2>
<p>Rig Manager is a service for monitoring your cryptocurrency mining rigs that is designed to be:</p>
<ul align='left'>
    <li>Fast and effective at answering the key question – “Are my miners running and how are they doing?”</li>
    <li>Able to be integrated with any mining approach (software mining, MasterNodes, etc) and any mining hardware (ASIC, GPU, etc) based on an API that’s simple to interface with</li>
    <li>Store/display history statistics</li>
    <li>Have as little personal information as possible about you or your mining operation</li>
    <li>Have a free hosted option (as in “Free Beer”) – core functionality without any dev or subscription fees</li>
    <li>Is open sourced for full transparency</li>
</ul>
<?php
} else {
    $userid = $_SERVER['REMOTE_USER'];
    include 'mysqlcon.php';
	$stmt = $conn->prepare('SELECT id FROM users WHERE userid = ?');
	$stmt->bind_param('s',$userid);
	$stmt->execute();
	$result = $stmt->get_result();
    mysqli_close($conn);
    
    if (mysqli_num_rows($result) < 1) { 
        echo "<p>Welcome " . $_SERVER['REMOTE_USER'] . " <strong>it doesnt look like you're signed up for Rig Manager, <a href='?page=signup'>click here to sign up now</a></strong>.</p>";
    } else {
        $numworkers = 0;
        include 'parts/onlineworkertable.php';
        $numworkers += $result->num_rows;
        include 'parts/offlineworkertable.php';
        $numworkers += $result->num_rows;
        if ($numworkers == 0) {
            echo "<p>Welcome to RigManager.  You're signed in but it looks like either you have no workers or they've yet to check in for the first time.  There are a couple things you'll need to do in order to get started:</p>
            <ol align='left'>
                <li>Visit <a href='?page=settings'>settings</a> and add new workers</li>
                <li>Create or configure scripts that get you miners' status and report in (you can read more about this in <a href='http://docs.rigmanager.xyz/configuring-your-miner-to-work-with-rig-manager/'>the setup guide</a>)</li>
            </ol>
            <p>If you get stuck be sure to reach out on Reddit using the link at the bottom of this page.</p>";
        }
    }
}
?>