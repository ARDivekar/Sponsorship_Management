<?php
	include_once "library_functions.php";
	/*Resume old session:*/
	session_start();

	if(!isset($_POST[QueryFieldNames::Submit])){ //can only be set from submitting a form from query.php
		header("Location: home.php");
	}

	if( !Authorization::checkValidAuthorization(
			$_SESSION[SessionEnums::UserAccessLevel],
			$_SESSION[QueryFormSessionEnums::TableName],
			$_SESSION[QueryFormSessionEnums::QueryType]
		)){
		header("Location: home.php");
	}



	class QueryExecute{

		var $userType = NULL;
		var $tableName = NULL;
		var $queryType = NULL;

		var $tableFields = NULL;


		function QueryExecute($userType, $tableName, $queryType){
			if($queryType == QueryTypes::View)
				header("Location: table_output.php");
			$this->userType = $userType;
			$this->tableName = $tableName;
			$this->queryType = $queryType;
		}


		function checkRequiredFields(){
			foreach( QueryFieldNames::$requiredFields[$this->tableName][$this->queryType] as $requiredField ){
				if(! ($this->userType == UserTypes::CSO && $requiredField == QueryFieldNames::SponsSector)){
					if(!isset($_POST[$requiredField])){
						echo "required field(s) : $requiredField are not set";
						return false;
					}
				}
			}
			return true;
		}


		function getInsert(){
			$q = new SQLQuery();
			$insertFields = [];
			$insertFieldValues = [[]];
			if($this->checkRequiredFields()){
				foreach( QueryFieldNames::$TableToFieldNameOrdering[$this->tableName] as $possibleField){
					if($possibleField==QueryFieldNames::Submit)
						continue;

					$val = extractValueFromPOST($possibleField);
					if($val != NULL){
						array_push($insertFields, QueryFieldNamesToSQLTableFields::$map[$possibleField]);
						array_push($insertFieldValues[0], $val);
					}
				}
				$q->setInsertQuery($this->tableName, $insertFields, $insertFieldValues);
				return $q->getQuery();
			}
			return NULL;
		}


		function executeQuery(){
			if (Authorization::checkValidAuthorization($this->userType, $this->tableName, $this->queryType)){
				//user is authorized to run this query

				if(!$this->checkRequiredFields())
					return false;

				$db = new SponsorshipDB();
				$sqlQueryObj = new SQLQuery();

				switch ($this->userType){
					case UserTypes::CSO:
						$sqlQueryObj = $this->getCSOQuery();
						break;
					case UserTypes::SectorHead:
						$sqlQueryObj = $this->getSectorHeadQuery();
						break;
					case UserTypes::SponsRep:
						$sqlQueryObj = $this->getSponsRepQuery();
						break;
				}

				echo $sqlQueryObj->getQuery();
				if($db ->query($sqlQueryObj->getQuery()))
					return true;
			}

			return false;
		}


		function getCSOQuery(){
			switch($this->tableName){
				case SQLTables::Event :
					return $this->getCSOEventSQLQuery();
					break;
				case SQLTables::SponsLogin :
//					return $this->setCSOSponsLoginSQLQuery();
					break;
				case SQLTables::SponsRep :
					return $this->getCSOSponsRepSQLQuery();
					break;
				case SQLTables::SectorHead :
					return $this->getCSOSectorHeadSQLQuery();
					break;
				case SQLTables::AccountLog :
					return $this->getCSOAccountLogSQLQuery();
					break;
				case SQLTables::Company :
					return $this->getCSOCompanySQLQuery();
					break;
				case SQLTables::CompanyExec :
					return $this->getCSOCompanyExecSQLQuery();
					break;
				case SQLTables::Meeting :
					return $this->getCSOMeetingSQLQuery();
					break;
			}
		}




		function getCSOEventSQLQuery(){
			/*For reference:
				SQLTables::Event => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//can only insert an Event, not an Organization
			*/
			switch($this->queryType){
				case QueryTypes::Insert :

					break;
				case QueryTypes::Modify :
					break;
				case QueryTypes::Delete :
					break;
			}
			return NULL;
		}


		function getCSOSponsRepSQLQuery(){
			/*For reference:
				SQLTables::SponsRep => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			*/
			$q = new SQLQuery();
			switch($this->queryType){
				case QueryTypes::Insert :

					break;
				case QueryTypes::Modify :
					break;
				case QueryTypes::Delete :
					break;
			}
			return NULL;
		}


		function getCSOSectorHeadSQLQuery(){
			/*For reference:
				SQLTables::SectorHead => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			*/

			switch($this->queryType){
				case QueryTypes::Insert :
					break;
				case QueryTypes::Modify :
					break;
				case QueryTypes::Delete :
					break;
			}
			return NULL;

		}

		function getCSOAccountLogSQLQuery(){
			/*For reference:
				SQLTables::AccountLog => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			*/
			switch($this->queryType){
				case QueryTypes::Insert :
					break;
				case QueryTypes::Modify :
					break;
				case QueryTypes::Delete :
					break;
			}
			return NULL;
		}


		function getCSOCompanySQLQuery(){
			/*For reference:
				SQLTables::Company => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			*/
			switch($this->queryType){
				case QueryTypes::Insert :
					break;
				case QueryTypes::Modify :
					break;
				case QueryTypes::Delete :
					break;
			}
			return NULL;
		}


		function getCSOCompanyExecSQLQuery(){
			/*For reference:
				SQLTables::CompanyExec => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			*/
			switch($this->queryType){
				case QueryTypes::Insert :
					break;
				case QueryTypes::Modify :
					break;
				case QueryTypes::Delete :
					break;
			}
			return NULL;
		}

		function getCSOMeetingSQLQuery(){
			/*For reference:
				SQLTables::Meeting => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View]
			*/
			switch($this->queryType){
				case QueryTypes::Insert :
					break;
				case QueryTypes::Modify :
					break;
				case QueryTypes::Delete :
					break;
			}
			return NULL;
		}







		function executeSectorHeadQuery(){

		}


		function executeSponsRepQuery(){

		}


	}

	/*
	foreach($_POST as $key => $value){
		echo "<br>".$key." : ".$value;
	}
	echo "<hr>";
	*/

	$e = new QueryExecute($_SESSION[SessionEnums::UserAccessLevel], $_SESSION[QueryFormSessionEnums::TableName], $_SESSION[QueryFormSessionEnums::QueryType]);
	echo $e->getInsert();







?>