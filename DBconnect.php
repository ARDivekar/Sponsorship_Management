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



	class SponsorshipDB{
		//source: https://www.binpress.com/tutorial/using-php-with-mysql-the-right-way/17

    /**
     * Connect to the database
     *
     * @return bool false on failure / mysqli MySQLi object instance on success
     */

		protected static $connection; // The database connection
		private static $hostname = NULL;
		private static $portnumber = NULL;
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
					self::$hostname = $config["hostname"];
				else self::$hostname = NULL;

				if(array_key_exists("portnumber", $config))
					self::$portnumber = $config["portnumber"];
				else self::$portnumber = NULL;

				if(array_key_exists("username", $config))
					self::$username = $config["username"];
				else self::$username = NULL;

				if(array_key_exists("password", $config))
					self::$password = $config["password"];
				else self::$password = NULL;

				if(array_key_exists("dbname", $config))
					self::$dbname = $config["dbname"];
				else self::$dbname = NULL;
			}

			if(self::$hostname && self::$username && self::$dbname){	//password is allowed to be an empty string
				self::$validConnectionDetails = true;
				$this->resetConnection(); //try to reset the connection
			} else self::$validConnectionDetails = false;

		}

		private function resetConnection(){ //this code must be swapped when changing PHP database handlers
			if(self::$validConnectionDetails){
				if(self::$portnumber == NULL || self::$portnumber == null || self::$portnumber == ""){
					self::$connection = new mysqli(self::$hostname, self::$username, self::$password, self::$dbname);
					// echo "<br>NO PORT NUMBER PROVIDED!!<br>";
				}
				else
					self::$connection = new mysqli(self::$hostname, self::$username, self::$password, self::$dbname, self::$portnumber);
				return true;
			}
			// if no valid details:
			self::$connection = NULL;
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

		public function isConnected($printIfError=false){	// Source: http://stackoverflow.com/a/19188412/4900327
			if(!self::$connection)
				return false;
			if(!self::$connection->connect_errno){ // Check if connection is alive
				if(self::$connection->ping())	// Check if server is alive
					return true;
			}
			if($printIfError)
				echo "<br>Error in connection: ".self::$connection->error;
			return false;
		}

		/**
		 * Query the database
		 *
		 * @param $query The query string
		 * @return mixed The result of the mysqli::query() function
		 */

		public function query($query) { //this code must be swapped when changing PHP database handlers
			// Query the database
			if(!$query){
				echo "Query passed is NULL";
				return FALSE;
			}

			$result = self::$connection->query($query);

			return $result; //From: http://php.net/manual/en/mysqli.query.php ...
			//Returns FALSE on failure. For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries mysqli_query() will return a mysqli_result object. For other successful queries mysqli_query() will return TRUE.
		}

		/**
		 * Fetch rows from the database (SELECT query)
		 *
		 * @param $query The query string
		 * @return bool False on failure / array Database rows on success
		 */

		public function select($query) {
			$rows = array();
			$result = self::query($query);
			if($result === false ) {
				return NULL;
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
			return self::$connection->error;
		}

		/**
		 * Quote and escape value for use in a database query
		 *
		 * @param string $value The value to be quoted and escaped
		 * @return string The quoted and escaped string
		 */
		public function quote($value) {
			return "'" . self::$connection->real_escape_string($value) . "'";
		}

		public function getTableColumns($tableName){
			$tableCols = [];

			$structure = self::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".self::$dbname."' AND TABLE_NAME = '$tableName';");

			if(!$structure)
				return NULL;

			foreach($structure as $column){
				array_push($tableCols, $column["COLUMN_NAME"]);
			}

			return $tableCols;


		}

		public function startTransaction(){
			if(!self::$connection)
				return false;
			self::$connection->autocommit(FALSE);
			return true;
		}

		public function rollbackTransaction(){
			if(!self::$connection)
				return false;
			self::$connection->rollback();
			self::$connection->autocommit(TRUE);
			return true;
		}


		public function endTransaction(){
			if(!self::$connection)
				return false;
			self::$connection->commit();
			self::$connection->autocommit(TRUE);
			return true;
		}


		public function performTransaction($queryList){
			if(!self::$connection)
				return false;

			if(count($queryList)==0)
				return false;

			$this->startTransaction();
			foreach($queryList as $query){
				if($this->query($query) === FALSE){
					$this->rollbackTransaction();
					return false;
				}
			}
			$this->endTransaction();
			return true;
		}

		public function getDBName(){
		   return self::$dbname;
	   }
	}


	$db = new SponsorshipDB([
			"hostname" => $clearDB_url["host"] ,
			"username" => $clearDB_url["user"] ,
			"password" => $clearDB_url["pass"] ,
			"dbname" => substr($clearDB_url["path"], 1)
		]);

	// $db = new SponsorshipDB([
	// 	"hostname" => "localhost",
	// 	"username" => "root",
	// 	"password" => "",
	// 	"dbname" =>"sponsorshipmanagement"
	// 	 ]);



	/*##------------------------------------------------TESTS------------------------------------------------##

	echo "<hr>CommitteeMember:<br>";
	foreach( $db->getTableColumns("CommitteeMember") as $col)
		echo "<br>".$col;

	$db = new SponsorshipDB();
	$x = $db->query("INSERT INTO committeemember
		(ID, Organization, EventName, Name, Department, Role, Mobile, Email, Year, Branch)
		VALUES
		(".mt_rand(10,1000).", 'Technovanza', 'Technovanza','Lol', 'LOL', 'CSO', '989', NULL, 2, 'Comps');
		"
	);


	echo $x."<hr>";
	$x = $db->query("UPDATE committeemember SET Name = 'AAAA' WHERE Name = 'Hi';");
	echo $x;


	/*##---------------------------------------------END OF TESTS---------------------------------------------##*/


?>
