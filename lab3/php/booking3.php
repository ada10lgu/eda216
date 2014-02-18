<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$db->openConnection();
	$movie = $_SESSION['movie'];
	$time = $_POST['time'];
	$_SESSION['date'] = $time;
	$freeSeats = $db->getFreeSeats($movie,$time);


	$db->closeConnection();
?>

<html>
<head><title>Booking 3</title><head>
<body><h1>Booking 3</h1>
	Current user: <?php print $userId ?><br>
	Current movie: <?=$_SESSION['movie']; ?><br>
	Current date: <?=$_SESSION['date'];?><br>
	Free seats: <?=$freeSeats;?><br>
	<form method=post action="booking4.php">
		<input type=submit value="Book the shit out of it!">
	</form>
</body>
</html>
