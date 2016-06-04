<?php 
/*Connection stuff
the statement:
	require('DBconnect');
in each of the other php files, basically imports this.
*/


	/*
//$person_name = "Divekar";

$servername = 'localhost';
$username = 'root'; //you might need to change this based on your WAMP/XAMP/LAMP username
$password=''; //you might need to change this based on your WAMP/XAMP/LAMP username
$db_name='sponsorshipmanagement';
$sql='CREATE DATABASE IF NOT EXISTS '.$db_name; //I realized that nowhere in this code is this being __used___. Still, don't delete it.


//we are connecting with MYSQLI PROCEDURAL, __not__ object oriented.
error_reporting(E_ALL ^ E_DEPRECATED);

$conn = mysql_connect($servername, $username, $password); //sets up connection.
if (!$conn) {
    die("Connection failed: " . mysql_connect_error());
}
//else echo "Connected successfully, $person_name.<br>";

mysql_select_db($db_name); 
  

/*from: 
http://www.w3schools.com/php/func_mysqli_select_db.asp   
___AND___   
http://stackoverflow.com/questions/2261624/using-same-mysql-connection-in-different-php-pages
*/



	class SponsorshipDB {
		//source: https://www.binpress.com/tutorial/using-php-with-mysql-the-right-way/17

    /**
     * Connect to the database
     *
     * @return bool false on failure / mysqli MySQLi object instance on success
     */

		protected static $connection; // The database connection
		private static $hostname = NULL;
		private static $username = NULL;
		private static $password = NULL;
		private static $dbname = NULL;
		private static $validConnectionDetails = false;

		function SponsorshipDB($config = NULL){
			$this->set($config);
		}


		function set($config = NULL){
			if($config){
				if(array_key_exists("hostname", $config))
					SponsorshipDB::$hostname = $config["hostname"];
				else SponsorshipDB::$hostname = NULL;

				if(array_key_exists("username", $config))
					SponsorshipDB::$username = $config["username"];
				else SponsorshipDB::$username = NULL;

				if(array_key_exists("password", $config))
					SponsorshipDB::$password = $config["password"];
				else SponsorshipDB::$password = NULL;

				if(array_key_exists("dbname", $config))
					SponsorshipDB::$dbname = $config["dbname"];
				else SponsorshipDB::$dbname = NULL;
			}

			if(SponsorshipDB::$hostname && SponsorshipDB::$username && SponsorshipDB::$dbname){	//password is allowed to be an empty string
				SponsorshipDB::$validConnectionDetails = true;
				$this->resetConnection(); //try to reset the connection
			} else SponsorshipDB::$validConnectionDetails = false;

		}

		private function resetConnection(){ //this code must be swapped when changing PHP database handlers
			if(SponsorshipDB::$validConnectionDetails){
				self::$connection = new mysqli(SponsorshipDB::$hostname, SponsorshipDB::$username, SponsorshipDB::$password, SponsorshipDB::$dbname);
				return true;
			} else self::$connection = NULL;
			return false;
		}


		public function connect() {
			//see if there is an existing connection
			if (self::$connection){
				return true;
			}

			// Try and connect to the database
			$this->resetConnection();

			if (self::$connection == NULL)
				return false;

			// If connection was not successful, handle the error
			if (self::$connection === false){
				// Handle error - notify administrator, log to a file, show an error screen, etc.
				return false;
			}
			echo "New connection made to database";
			return true;

		}


		/**
		 * Query the database
		 *
		 * @param $query The query string
		 * @return mixed The result of the mysqli::query() function
		 */
		public function query($query) { //this code must be swapped when changing PHP database handlers
			// Query the database
			$result = SponsorshipDB::$connection->query($query);

			return $result;
		}

		/**
		 * Fetch rows from the database (SELECT query)
		 *
		 * @param $query The query string
		 * @return bool False on failure / array Database rows on success
		 */

		public function select($query) {
			$rows = array();
			$result = $this -> query($query);
			if($result === false) {
				return false;
			}
			while ($row = $result -> fetch_assoc()) {
				$rows[] = $row;
			}
			return $rows;
		}

		/**
		 * Fetch the last error from the database
		 *
		 * @return string Database error message
		 */
		public function error() {
			return SponsorshipDB::$connection->error;
		}

		/**
		 * Quote and escape value for use in a database query
		 *
		 * @param string $value The value to be quoted and escaped
		 * @return string The quoted and escaped string
		 */
		public function quote($value) {
			return "'" . SponsorshipDB::$connection->real_escape_string($value) . "'";
		}
	}

	$db = new SponsorshipDB([
		"hostname" => "localhost",
		"username" => "root",
		"password" => "",
		"dbname" =>"sponsorshipmanagement"
		 ]);



?>
