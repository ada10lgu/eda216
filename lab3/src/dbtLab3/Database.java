package dbtLab3;

import java.sql.*;
import java.util.ArrayList;

/**
 * Database is a class that specifies the interface to the movie database. Uses
 * JDBC and the MySQL Connector/J driver.
 */
public class Database {
	/**
	 * The database connection.
	 */
	private Connection conn;
	private Statement stmt;
	private ResultSet rs;
	private ArrayList<String> result;
	private int seatsAvailable;
	private String movieId;

	/**
	 * Create the database interface object. Connection to the database is
	 * performed later.
	 */
	public Database() {
		stmt = null;
		conn = null;
		rs = null;
		result = new ArrayList<String>();
		seatsAvailable = 0;
		movieId = "";
	}

	/**
	 * Open a connection to the database, using the specified user name and
	 * password.
	 * 
	 * @param userName
	 *            The user name.
	 * @param password
	 *            The user's password.
	 * @return true if the connection succeeded, false if the supplied user name
	 *         and password were not recognized. Returns false also if the JDBC
	 *         driver isn't found.
	 */
	public boolean openConnection(String userName, String password) {
		try {
			Class.forName("com.mysql.jdbc.Driver");
			conn = DriverManager.getConnection(
					"jdbc:mysql://puccini.cs.lth.se/" + userName, userName,
					password);
		} catch (SQLException e) {
			e.printStackTrace();
			return false;
		} catch (ClassNotFoundException e) {
			e.printStackTrace();
			return false;
		}
		return true;
	}

	/**
	 * Close the connection to the database.
	 */
	public void closeConnection() {
		try {
			if (conn != null) {
				conn.close();
			}
		} catch (SQLException e) {
		}
		conn = null;
	}

	public ArrayList<String> fillDateList(String query) {
		runSQL(query);
		result.clear();
		try {
			while (rs.next()) {
				result.add(rs.getString(1));
			}
		} catch (SQLException e) {
			e.printStackTrace();
		}
		return result;
	}

	public ArrayList<String> dateChanged(String query) {
		result.clear();
		runSQL(query);
		try {
			if (rs.next()) {
				// seatsAvailable = Integer.parseInt(rs.getString(6));
				// movieId = Integer.parseInt(rs.getString(1));
				result.add(rs.getString(3));
				result.add(rs.getString(2));
				result.add(rs.getString(4));
				// result.add(rs.getString(1));
				movieId = rs.getString(1);
				seatsAvailable = Integer.parseInt(rs.getString(6));
			}
		} catch (SQLException e1) {
			e1.printStackTrace();
		}

		return result;
	}

	public ArrayList<String> dateChangedCountSeats() {

		/**
		 * Seperate query for # of seats availalble
		 */
		result.clear();
		String query = "select count(*) from reservation where venue=" + "\""
				+ movieId + "\"";

		runSQL(query);
		try {
			if (rs.next()) {
				seatsAvailable = seatsAvailable
						- Integer.parseInt(rs.getString(1));
			}
		} catch (SQLException e1) {
			e1.printStackTrace();
		}
		result.add(Integer.toString(seatsAvailable));
		return result;
	}

	public String userLogedOn(String query) {
		String checkUser = null;
		try {

			runSQL(query);
			if (rs.first()) {
				checkUser = rs.getString(1);
				return checkUser;
			}

		} catch (SQLException e1) {
			e1.printStackTrace();
		}
		return null;

	}

	public ArrayList<String> fillNameList(String query) {
		runSQL(query);
		result.clear();
		try {

			while (rs.next()) {
				result.add(rs.getString(3));
			}
		} catch (SQLException e) {
			e.printStackTrace();
		}
		return result;
	}

	public boolean booking(CurrentUser instance) throws SQLException {
		String query = "INSERT INTO reservation (venue, user) "
				+ "SELECT ?, ? FROM reservation "
				+ "WHERE (SELECT ("
				+ "SELECT seats FROM theatre as t LEFT join venue as v on v.theatre = t.name WHERE v.id = ?)-("
				+ "SELECT COUNT(*) FROM reservation as r left join venue as v on v.id = r.venue WHERE v.id = ?) as seats) > 0 "
				+ "LIMIT 1";

		int rows = update(query, movieId, instance.getCurrentUserId(), movieId,
				movieId);

		return rows > 0;
	}

	/**
	 * Check if the connection to the database has been established
	 * 
	 * @return true if the connection has been established
	 */
	public boolean isConnected() {
		return conn != null;
	}

	public void runSQL(String str) {

		try {
			stmt = conn.createStatement();
			// ResultSet.TYPE_SCROLL_INSENSITIVE,
			// ResultSet.CONCUR_READ_ONLY);
			rs = stmt.executeQuery(str);
			rs.beforeFirst();

			// rs.first();
		} catch (SQLException e) {
			e.printStackTrace();
		}

	}

	/* --- insert own code here --- */
	public int update(String sql, Object... o) {

		try {
			PreparedStatement stmt = conn.prepareStatement(sql);

			for (int i = 0; i < o.length; i++) {
				switch (o[i].getClass().toString()) {
				case "class java.lang.Integer":
					stmt.setInt(i + 1, (int) o[i]);
					break;
				default:
					stmt.setString(i + 1, o[i].toString());
				}
			}

			return stmt.executeUpdate();

		} catch (SQLException e) {
			e.printStackTrace();
		}
		return 0;
	}
}
