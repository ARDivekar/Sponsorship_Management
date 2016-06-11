<?php
	
	
	class SQLQuery{
		var $queryType = NULL; //must be in the QueryTypes enum
		var $tableName = NULL;
		var $tableFields = NULL;
		var $whereClause = NULL;

		var $tableInsertFieldValues = NULL; //a 2-D array;
		var $tableUpdateFieldValues = NULL; //a 2-D array;


		function SQLQuery($tableName=NULL, $tableFields=NULL){
			$this->tableName = $tableName;
			$this->tableFields = $tableFields;
		}

		public function setSelectQuery($tableName=NULL, $tableFields=NULL, $whereClause=NULL){
			$this->queryType = QueryTypes::View;
			if($tableName)
				$this->tableName = $tableName;
			if($tableFields)
				$this->tableFields = $tableFields;
			if($whereClause)
				$this->whereClause = $whereClause;
		}

		public function setInsertQuery($tableName=NULL, $tableFields=NULL, $tableInsertFieldValues, $whereClause=NULL){
			//$tableInsertFieldValues MUST be a 2-D array, with each row having same length as $this->tableFields.

			$this->queryType = QueryTypes::Insert;
			if($tableName)
				$this->tableName = $tableName;
			if($tableFields)
				$this->tableFields = $tableFields;
			$this->tableInsertFieldValues = $tableInsertFieldValues;
			$this->whereClause = $whereClause;
		}

		public function setUpdateQuery($tableName=NULL, $tableUpdateFieldValues, $whereClause=NULL){
			$this->queryType = QueryTypes::Modify;
			if($tableName)
				$this->tableName = $tableName;

			$this->tableUpdateFieldValues = $tableUpdateFieldValues;
			$this->whereClause = $whereClause;
		}


		public function setDeleteQuery($tableName=NULL, $whereClause=NULL){
			$this->queryType = QueryTypes::Delete;
			if($tableName)
				$this->tableName = $tableName;

			$this->whereClause = $whereClause;
		}


		public function getQuery(){
			switch($this->queryType){
				case QueryTypes::Insert :
					return $this->generateInsertQuery();
					break;
				case QueryTypes::Modify :
					return $this->generateUpdateQuery();
					break;
				case QueryTypes::Delete :
					return $this->generateDeleteQuery();
					break;
				case QueryTypes::View :
					return $this->generateSelectQuery();
			}
			return NULL;
		}


		private static function convertArrayToCommaSeparatedTuple($array, $surrounder="'", $parens=true){
			$out = " ";
			if($parens)
				$out .= "(";
			for($i=0; $i<count($array) - 1; $i++){
				$out .= self::surroundWith($array[$i], $surrounder).", ";
			}
			$out .= self::surroundWith($array[$i], $surrounder);
			if($parens)
				$out .= ") ";
			$out .= " ";
			return $out;
		}


		private function generateSelectQuery(){
			$out = "SELECT ";

			if($this->tableFields == "*")
				$out .= " * ";
			else{
				$len = count($this->tableFields);
				/* Allowed values for the tableFields array:
				 * ["ID", "Name", "Company"]
				 * [["ID"], ["Name"], ["Company"]]
				 * [["ID"], "Name", ["Company"]]
				 * [["ID"], ["Name"], ["Company", "Company I work for"]]
				 * ["ID", ["Name"], ["Company", "Company I work for"]]
				 * */
				for($i=0; $i < $len ; $i++){
					$field = $this->tableFields[$i];
					if(!is_array($field))
						$out .= " $field ";
					else if(count($field) == 1)
						$out .= " $field[0] ";
					else if(count($field) == 2)
						$out .= " $field[0] AS '$field[1]' ";
					else return NULL;

					if($i != $len-1)
						$out .= ", ";
				}
			}


			$out .=  "FROM $this->tableName ";
			if($this->whereClause)
				$out .= " WHERE ".$this->whereClause;

			if($this->checkForSQLInjection($out)){
				return NULL;
			}

			$out .= ";";
			return $out;

		}




		private function generateInsertQuery(){
			$out = "";
			$out .= self::convertArrayToCommaSeparatedTuple($this->tableFields, $surrounder="`");
			$out .= " VALUES \n";

			$len = count($this->tableInsertFieldValues);
			for($i=0; $i<$len; $i++){
				$row = $this->tableInsertFieldValues[$i];
				if(count($row) != count($this->tableFields)){
					echo "<br>Length of ".self::convertArrayToCommaSeparatedTuple($this->tableFields, $surrounder="`")." and ". $this->convertArrayToCommaSeparatedTuple($row, $surrounder="'")." are different.";
					return NULL;
				}

				if($i != $len-1)
					 $out .= $this->convertArrayToCommaSeparatedTuple($row, $surrounder="'").",\n";
				else $out .= $this->convertArrayToCommaSeparatedTuple($row, $surrounder="'")."\n";
			}

			if($this->checkForSQLInjection($out)){
				return NULL;
			}

			$out = "INSERT INTO $this->tableName " . $out;
			$out .= ";";
			return $out;

		}


		private function generateUpdateQuery(){
			$out = "";
			$out .= " SET ";
			$len = count($this->tableUpdateFieldValues);
			for($i=0; $i < $len; $i++){
				$row = $this->tableUpdateFieldValues[$i];
				if(count($row)!=2)
					return NULL;
				$out .= " " . self::surroundWith($row[0], $surrounder=" ") ." = ". self::surroundWith($row[1], $surrounder="'") ." ";

				if($i != $len-1)
					$out .= ", ";
			}

			if($this->whereClause)
				$out .= " WHERE ".$this->whereClause;

			if($this->checkForSQLInjection($out)){
				return NULL;
			}

			$out = "UPDATE $this->tableName " . $out;
			$out .= ";";
			return $out;
		}



		private function generateDeleteQuery(){
			$out = "";

			if($this->whereClause)
				$out .= " WHERE ".$this->whereClause;

			if($this->checkForSQLInjection($out)){
				return NULL;
			}

			$out = "DELETE FROM $this->tableName " . $out;
			$out .= ";";
			return $out;

		}




		private function checkForSQLInjection($text){
			if(!$text)
				return false;
			$text = strtolower($text); //to lowercase
			if( 	//Source: http://php.net/manual/en/function.strpos.php
					strpos($text, ";") === FALSE &&
					strpos($text, " insert ") === FALSE &&
					strpos($text, " update ") === FALSE &&
					strpos($text, " delete ") === FALSE
				)
				return false;

//			echo "<br>$text<br>";
			echo "NO SQL INJECTION ALLOWED";
			return true;
		}


		public static function surroundWith($string, $surrounder="'", $unlessWordIs="NULL"){

			$out = trim(
					trim(trim(trim(trim($string), '"'), "'"), '"')
			);
			if($out == $unlessWordIs)
				return $out;
			//Source: http://stackoverflow.com/questions/1555434/php-wrap-a-string-in-double-quotes
			return $surrounder . $out . $surrounder; //This removes __all__ kinds of nested single and double quotes from around a word.

		}

		public static function getWhereEquality($whereArray, $joiner="AND"){ //an array of arrays in which each sub-array has two values which must be equal. One must be a field in the database, at that must come first.
			if(count($whereArray) == 0)
				return NULL;

			$out = "";
			$len = count($whereArray);
			for($i=0; $i<$len; $i++){
				$wherePair = $whereArray[$i];
				if(count($wherePair) != 2){
					echo "$wherePair must be an array of exactly two elements";
					return NULL;
				}
				if($i != $len-1)
					$out .= " ".$wherePair[0]." = ".self::surroundWith($wherePair[1])." $joiner ";
				else $out .= " ".$wherePair[0]." = ".self::surroundWith($wherePair[1])." ";

			}
			return $out;

		}



		public static function getInnerJoin($table1, $field1, $table2, $field2, $alias=NULL){
			if($table1 && $field1 && $table2 && $field2){
				$out = " ( $table1 INNER JOIN $table2 ON ($table1.$field1 = $table2.$field2) ) ";
				if($alias)
					$out .= " AS ".self::surroundWith($alias, "'")." ";
				return $out;
			}
			return NULL;
		}

		public static function convertEmptyToNULLString($field){
			if($field == NULL || $field == "" ||$field == []){
				return "NULL";
			}
			else return $field;
		}


		public function __toString(){
			return $this->getQuery();
		}
	}


	/*##------------------------------------------------TESTS------------------------------------------------##

	$a = new SQLQuery();
	echo $a->surroundWithSingleQuotes("\"'Hello'\"");
	echo $a->surroundWithSingleQuotes("'\"Hello\"'");


	$a = new SQLQuery();
	$a->setInsertQuery("Committeemember",["ID", "Name"],[["lol","lol"],["lol2","lol3"]]);
	echo $a->getQuery();




	echo "<hr>";
	$a->setSelectQuery(
			$tableName = "CommitteeMember",
			$tableFields = ["ID", "Name"],
			$whereClause = SQLQuery::getWhereEquality([["ID",131080051], ["Name","Abhishek Divekar"]])
	);
	echo $a->getQuery();





	echo "<hr>";
	$a->setSelectQuery(
		$tableName = SQLQuery::getInnerJoin("CommitteeMember", "ID", "AccountLog", "SponsID"),
		$tableFields = [["CommitteeMember.ID", "ID"], "Name", ["AccountLog.Title", "Company Name"]],
		$whereClause = SQLQuery::getWhereEquality([
								["CommitteeMember.ID",131080051],
								["Name","Abhishek Divekar"]
							])
	);
	echo $a->getQuery();





	echo "<hr>";
	$a->setUpdateQuery(
		$tableName = "CommitteeMember",
		$tableUpdateFieldValues = [["ID", "123"],["Title", "Heelo world"]],
		$whereClause = SQLQuery::getWhereEquality([
								["CommitteeMember.ID",131080051],
								["Name","Abhishek Divekar"]
							])
	);
	echo $a->getQuery();





	echo "<hr>";
	$a->setDeleteQuery(
		$tableName = "CommitteeMember",
		$whereClause = SQLQuery::getWhereEquality([
								["CommitteeMember.ID",131080051],
								["Name","Abhishek Divekar"]
							])
	);
	echo $a->getQuery();



	/*##---------------------------------------------END OF TESTS---------------------------------------------##*/
?>