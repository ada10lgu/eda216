<?php
/*
 * Class Database: interface to the movie database from PHP.
 *
 * You must:
 *
 * 1) Change the function userExists so the SQL query is appropriate for your tables.
 * 2) Write more functions.
 *
 */
class Database {
	private $host;
	private $userName;
	private $password;
	private $database;
	private $conn;
	
	/**
	 * Constructs a database object for the specified user.
	 */
	public function __construct($host, $userName, $password, $database) {
		$this->host = $host;
		$this->userName = $userName;
		$this->password = $password;
		$this->database = $database;
	}
	
	/** 
	 * Opens a connection to the database, using the earlier specified user
	 * name and password.
	 *
	 * @return true if the connection succeeded, false if the connection 
	 * couldn't be opened or the supplied user name and password were not 
	 * recognized.
	 */
	public function openConnection() {
		try {
			$this->conn = new PDO("mysql:host=$this->host;dbname=$this->database", 
					$this->userName,  $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			$error = "Connection error: " . $e->getMessage();
			print $error . "<p>";
			unset($this->conn);
			return false;
		}
		return true;
	}
	
	/**
	 * Closes the connection to the database.
	 */
	public function closeConnection() {
		$this->conn = null;
		unset($this->conn);
	}

	/**
	 * Checks if the connection to the database has been established.
	 *
	 * @return true if the connection has been established
	 */
	public function isConnected() {
		return isset($this->conn);
	}
	
	/**
	 * Execute a database query (select).
	 *
	 * @param $query The query string (SQL), with ? placeholders for parameters
	 * @param $param Array with parameters 
	 * @return The result set
	 */
	private function executeQuery($query, $param = null) {
		try {
			$stmt = $this->conn->prepare($query);
			$stmt->execute($param);
			$result = $stmt->fetchAll();
		} catch (PDOException $e) {
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $result;
	}
	
	/**
	 * Execute a database update (insert/delete/update).
	 *
	 * @param $query The query string (SQL), with ? placeholders for parameters
	 * @param $param Array with parameters 
	 * @return The number of affected rows
	 */
	private function executeUpdate($query, $param = null) {
		try {
			$stmt = $this->conn->prepare($query);
			$stmt->execute($param);
			$result = $this->conn->lastInsertId();
		} catch (PDOException $e) {
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $result;
	}
	
	/**
	 * Check if a user with the specified user id exists in the database.
	 * Queries the Users database table.
	 *
	 * @param userId The user id 
	 * @return true if the user exists, false otherwise.
	 */
	public function userExists($userId) {
		$sql = "select userName from user where userName = ? LIMIT 1";
		$result = $this->executeQuery($sql, array($userId));
		return count($result) == 1; 
	}

	/*
	 * *** Add functions ***
	 */

	public function getMovieNames() {
		$sql = "Select movie from venue group by movie";
		$result = $this->executeQuery($sql);
		$returnData = array();
		foreach ($result as $movie) {
			$returnData[] = $movie['movie'];
		}
		return $returnData;
	}

	public function getMovieShowing($movieName) {

		$sql = "Select date FROM venue WHERE movie = ?";;
		$result = $this->executeQuery($sql,array($movieName));
		$returnData = array();
		foreach ($result as $venue) {
			$returnData[] = $venue['date'];
		}
		return $returnData;
	}

	public function getFreeSeats($movie,$date) {
		$sql = "SELECT (SELECT seats FROM theatre as t LEFT join venue as v on v.theatre = t.name WHERE v.date = ? AND v.movie = ?)-(SELECT COUNT(*) FROM reservation as r left join venue as v on v.id = r.venue WHERE v.date = ? and v.movie = ?) as seats";
		$result = $this->executeQuery($sql,array($date,$movie,$date,$movie));
		return $result[0]["seats"];	
	}

	public function bookSeat($userId,$movie,$date) {
		$sql = "INSERT INTO reservation (venue, user) SELECT (SELECT id FROM venue WHERE date = ? AND movie = ?) AS venue, ? FROM reservation WHERE (SELECT (SELECT seats FROM theatre as t LEFT join venue as v on v.theatre = t.name WHERE v.date = ? AND v.movie = ?)-(SELECT COUNT(*) FROM reservation as r left join venue as v on v.id = r.venue WHERE v.date = ? and v.movie = ?)) > 0 LIMIT 1";
		$result = $this->executeUpdate($sql,array($date,$movie,$userId,$date,$movie,$date,$movie));
		return $result;
	}

	
}
?>
