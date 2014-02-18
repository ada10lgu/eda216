<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$db->openConnection();
	$movie = $_SESSION['movie'];
	$time = $_SESSION['date'];

	$reservationNumber = $db->bookSeat($userId,$movie,$time);

	$db->closeConnection();
?>

<html>
<head><title>Booking 3</title><head>
<body><h1>Booking 3</h1>
<?php
	if ($reservationNumber) {
		print <<<HTML
		You booked the shit out of that movie you son of a gun, here is your booking information: (like a boss!)<br>
		Movie: $movie<br>
		Date: $time<br>
		Name: $userId<br>
		Booking number: $reservationNumber<br>
		<br>
		<a href="index.html">Go back for more reservations...</a>
HTML;
	} else {
		print <<<HTML
		NO SOUP FOR YOU !<br>
<br>
<br>
		<a href="index.html">Go back for more reservations...</a>
HTML;
	}




?>



</body>
</html>
