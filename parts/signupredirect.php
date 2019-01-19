<?php
if (isset($_POST['submit'])) {
    include '../mysqlcon.php';
	$stmt = $conn->prepare('INSERT INTO users (userid) VALUES (?)');
	$stmt->bind_param('s',$_SERVER['REMOTE_USER']);
	$stmt->execute();
	$result = $stmt->get_result();
	mysqli_close($conn);
    header("Location:https://www.rigmanager.xyz");
} else {
    header("Location:https://www.rigmanager.xyz");
}
?>