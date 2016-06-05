<?php
	include_once "library_functions.php";
	/*Resume old session:*/
	session_start();
/*
	if(!isset($_POST[QueryFieldNames::Submit])){ //can only be set from submitting a form at query.php
		header("Location: home.php");
	}

	if( !Authorization::checkValidAuthorization(
			$_SESSION[SessionEnums::UserAccessLevel],
			$_SESSION[QueryFormSessionEnums::TableName],
			$_SESSION[QueryFormSessionEnums::QueryType]
		)){
		header("Location: home.php");
	}

*/
	class SQLQuery{
		var $queryType = NULL; //must be in the QueryTypes enum
		var $tableName = NULL;
		var $tableFields = NULL;
		var $necessaryFields = NULL;
		var $whereClause = NULL;

		var $tableInsertFieldValues = NULL; //a 2-D array;


		function SQLQuery($tableName=NULL, $tableFields=NULL){
			$this->tableName = $tableName;
			$this->tableFields = $tableFields;
		}

		public function setSelectQuery($selectQuery){

		}

		public function setInsertQuery($tableName=NULL, $tableFields=NULL, $tableInsertFieldValues, $whereClause=NULL, $necessaryFields=NULL){
			//$tableInsertFieldValues MUST be a 2-D array, with each row having same length as $this->tableFields.

			$this->queryType = QueryTypes::Insert;
			if($tableName)
				$this->tableName = $tableName;
			if($tableFields)
				$this->tableFields = $tableFields;
			$this->tableInsertFieldValues = $tableInsertFieldValues;
			$this->whereClause = $whereClause;
			$this->necessaryFields = $necessaryFields;
		}

		public function getQuery(){
			switch($this->queryType){
				case QueryTypes::Insert :
					return $this->generateInsertQuery();
					break;
				case QueryTypes::Modify :
					break;
				case QueryTypes::Delete :

					break;
			}
			return NULL;
		}

		public function executeQuery(){
			$db = new SponsorshipDB();
			switch($this->queryType){
				case QueryTypes::Insert :
				case QueryTypes::Modify :
				case QueryTypes::Delete :
					$res = $db->query($this->getQuery());
					if($res === FALSE){
						return false;
					} else return true;
					break;

				case QueryTypes::View :
					$res = $db->select($this->getQuery());
					break;
			}
			return NULL;
		}

		private function checkNecessaryFields(){
			if($this->tableFields){
				if($this->necessaryFields == NULL || $this->necessaryFields == [])
					return true;
				foreach($this->necessaryFields as $necessaryField){
					if(!in_array($necessaryField, $this->tableFields))
						return false;
				}
				return true;
			}
			return false;
		}

		private function convertArrayToCommaSeparatedTuple($array, $surrounder="'"){
			$out = " (";
			for($i=0; $i<count($array) - 1; $i++){
				$out .= $this->surroundWith($array[$i], $surrounder).", ";
			}
			$out .= $this->surroundWith($array[$i], $surrounder);
			$out .= ") ";
			return $out;
		}

		private function generateInsertQuery(){
			if($this->checkNecessaryFields()){
				$out = "";
				$out .= $this->convertArrayToCommaSeparatedTuple($this->tableFields, $surrounder="`");
				$out .= " VALUES \n";

				$len = count($this->tableInsertFieldValues);
				for($i=0; $i<$len; $i++){
					$row = $this->tableInsertFieldValues[$i];
					if(count($row) != count($this->tableFields)){
						echo "<br>Length of ".$this->convertArrayToCommaSeparatedTuple($this->tableFields, $surrounder="`")." and ". $this->convertArrayToCommaSeparatedTuple($row, $surrounder="'")." are different.";
						return NULL;
					}

					if($i != $len-1)
						 $out .= $this->convertArrayToCommaSeparatedTuple($row, $surrounder="'").",\n";
					else $out .= $this->convertArrayToCommaSeparatedTuple($row, $surrounder="'")."\n";
				}

				if($this->checkForSQLInjection($out)){
					echo "NO SQL INJECTION ALLOWED";
					return NULL;
				}

				$out = "INSERT INTO $this->tableName " . $out;
				$out .= ";";
				return $out;
			}
			return NULL;
		}

		private function checkForSQLInjection($text){
			return false;
		}

		public function surroundWith($string, $surrounder="'"){
			//Source: http://stackoverflow.com/questions/1555434/php-wrap-a-string-in-double-quotes
			return $surrounder . trim(
					trim(trim(trim(trim($string), '"'), "'"), '"')
			) . $surrounder; //This removes __all__ kinds of nested single and double quotes from around a word.

			//	$a = new SQLQuery();
			//	echo $a->surroundWithSingleQuotes("\"'Hello'\"");
			//	echo $a->surroundWithSingleQuotes("'\"Hello\"'");
		}

	}

//	/*
	$a = new SQLQuery();
	$a->setInsertQuery("Committeemember",["ID", "Name"],[["lol","lol"],["lol2","lol3"]]);
	echo $a->getQuery();
//	*/
?>