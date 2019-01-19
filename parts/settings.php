<h2>Settings</h2>
<?php
if (!isset($fromhome)) {
	die ("You can't access this file directly");
}
if (empty($_SERVER['REMOTE_USER'])) {
	echo "It doesnt look like you're signed in yet, sign in above to get started.";
} else {
    $userid = $_SERVER['REMOTE_USER'];
    include 'mysqlcon.php';
	$stmt = $conn->prepare('SELECT id FROM users WHERE userid = ?');
	$stmt->bind_param('s',$userid);
	$stmt->execute();
    $usernum = $stmt->get_result()->fetch_object()->id;
    mysqli_close($conn);
    
    if (empty($usernum) == true) {
        echo "Welcome " . $_SERVER['REMOTE_USER'] . " it doesnt look like you're signed up for Rig Manager, <a href='?page=signup'>click here to sign up now</a>.";
    } else {
        //Add worker
        if (isset($_POST['addworker'])) {
            $hash = bin2hex(random_bytes(16));
            include 'mysqlcon.php';
            $stmt = $conn->prepare('INSERT INTO workers (worker, workerkey, userid) VALUES (?, ?, ?)');
            $stmt->bind_param('sss',$_POST['worker'],$hash,$usernum);
            $stmt->execute();
            $result = $stmt->get_result();
            mysqli_close($conn);
        }

        //Remove worker
        if (isset($_POST['removeworker'])) {
            include 'mysqlcon.php';
            $stmt = $conn->prepare('DELETE FROM workers WHERE id = ?');
            $stmt->bind_param('i',$_POST['worker']);
            $stmt->execute();
            $result = $stmt->get_result();
            mysqli_close($conn);
        }

        //MPM worker update
        if (isset($_POST['mpmupdate'])) {
            include 'mysqlcon.php';
            $stmt = $conn->prepare('UPDATE users SET mpmaddress = ? WHERE id = ?');
            $stmt->bind_param('si',$_POST['mpmaddress'],$usernum);
            $stmt->execute();
            $result = $stmt->get_result();
            mysqli_close($conn);
        }
        include 'mysqlcon.php';
        $stmt = $conn->prepare('SELECT mpmaddress FROM users WHERE id = ?');
        $stmt->bind_param('i',$usernum);
        $stmt->execute();
        $mpmaddress = $stmt->get_result()->fetch_object()->mpmaddress;
        mysqli_close($conn);
        echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?page=settings'>MPM Key: <input type='text' name='mpmaddress' value='" . $mpmaddress . "'><input type='submit' value='Update' name='mpmupdate'></form>";

        //Get a list of workers and allow them to modify
        include 'mysqlcon.php';
        $stmt = $conn->prepare('SELECT id, worker, workerkey FROM workers WHERE userid = ?');
        $stmt->bind_param('i',$usernum);
        $stmt->execute();
        $result = $stmt->get_result();
        mysqli_close($conn);
        
        if ($result > 0) {
            ?>
            <table style="width:auto" id="workers">
                <caption>Your Workers</caption>
                <tr>
                    <th>Worker Name</th>
                    <th>Worker Security Key</th>
                    <th>Actions</th>
                </tr>
            <?php
            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>" . $row['worker'] . "</td>";
                echo "<td>" . $row['workerkey'] . "</td>";
                echo "<td><form method='post' action='" . $_SERVER['PHP_SELF'] . "?page=settings'><input type='hidden' name='worker' value='" . $row['id'] . "'><input type='submit' value='Remove' name='removeworker'></form></td>";
                echo "</tr>";
            }
            ?>
            </table>
            <?php
        } else {
            echo "It looks like you dont have any workers currently configured, add them now:";
        }
        
        //If they have less than 50 workers allow them to add more
        if ($result < 50) {
            echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "?page=settings'>Add worker: <input type='text' name='worker'><input type='submit' value='Add' name='addworker'></form>";
        }
    }
}
?>