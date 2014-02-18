<?php
	require_once('database.inc.php');
	
	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$db->openConnection();

	$_SESSION['movie'] = $_POST['movieName'];

	$movieNames = $db->getMovieShowing($_SESSION['movie']);


	$db->closeConnection();
?>

<html>
<head><title>Booking 2</title><head>
<body><h1>Booking 2</h1>
	Current user: <?php print $userId ?><br>
	Current movie: <?=$_SESSION['movie']; ?>
	<p>
	Dates showing the movie:
	<p>
	<form method=post action="booking3.php">
		<select name="time" size=10>
		<?php
			$first = true;
			foreach ($movieNames as $name) {
				if ($first) {
					print "<option selected>";
					$first = false;
				} else {
					print "<option>";
				}
				print $name;
			}
		?>
		</select>		
		<input type=submit value="Select the shit out of it!">
	</form>
</body>
</html>
