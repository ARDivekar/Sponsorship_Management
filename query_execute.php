<?php
	include_once "library_functions.php";
	/*Resume old session:*/
	if(!isset($_SESSION[SessionEnums::UserLoginID]))
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

		function setSystemGenerated (){ //fields that should use the $_SESSION values, and not those passed in the $_POST.
			$_POST[QueryFieldNames::SponsOrganization] = $_SESSION[SessionEnums::UserOrganization];
			$_POST[QueryFieldNames::SponsFestival] = $_SESSION[SessionEnums::UserFestival];
			$_POST[QueryFieldNames::SponsDepartment] = $_SESSION[SessionEnums::UserDepartment];
			$_POST[QueryFieldNames::SponsID] = $_SESSION[SessionEnums::UserLoginID];
			$_POST[QueryFieldNames::SponsTransType] = TransType::Deposit;
			$_POST[QueryFieldNames::SponsSector] = $_SESSION[SessionEnums::UserSector];
			$_POST[QueryFieldNames::SponsDateAssigned] = getCurrentDate();

			$unhashedPassword = extractValueFromPOST(QueryFieldNames::SponsPassword);
			if($unhashedPassword != NULL)
				$_POST[QueryFieldNames::SponsPassword] = generatePasswordHash($unhashedPassword);

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

		function checkValue($tableName, $fieldName, $value){
			$db = new SponsorshipDB();
			$selectQuery = new SQLQuery();
			if(count($db->select("SELECT $fieldName FROM $tableName WHERE $fieldName='$value';")) != 0)
				return true;
			return false;
		}





/*
		function getInsert(){
			$q = new SQLQuery();
			$insertFields = [];
			$insertFieldValues = [[]];
			if($this->checkRequiredFields()){
				foreach( QueryFieldNames::$TableToFieldNameOrdering[$this->tableName] as $possibleField){
					if($possibleField==QueryFieldNames::Submit)
						continue;

					$sysGenVal = QueryFieldNames::systemGenerated($possibleField);
					if($sysGenVal){
						array_push($insertFields, QueryFieldNamesToSQLTableFields::$map[$possibleField]);
						array_push($insertFieldValues[0], $sysGenVal);
					}
					else{
						$val = extractValueFromPOST($possibleField);
						if($val){
							array_push($insertFields, QueryFieldNamesToSQLTableFields::$map[$possibleField]);
							array_push($insertFieldValues[0], $val);
						}
					}

				}
				$q->setInsertQuery($this->tableName, $insertFields, $insertFieldValues);
				return $q->getQuery();
			}
			return NULL;
		}
*/


		function executeQuery(){
			if (Authorization::checkValidAuthorization($this->userType, $this->tableName, $this->queryType)){
				//user is authorized to run this query

				if(!$this->checkRequiredFields())
					return false;

				$db = new SponsorshipDB();
				$sqlQueriesToExecute = [];

				switch ($this->userType){
					case UserTypes::CSO:
						$sqlQueriesToExecute = $this->getCSOQuery();
						break;
					case UserTypes::SectorHead:
						$sqlQueriesToExecute = $this->getSectorHeadQuery();
						break;
					case UserTypes::SponsRep:
						$sqlQueriesToExecute = $this->getSponsRepQuery();
						break;
				}

				/*
				$sqlStringsToExecute = [];
				foreach($sqlQueriesToExecute as $tableName => $queryObj)
					$sqlStringsToExecute[$tableName] = $queryObj->getQuery();

				if($db->performTransaction($sqlStringsToExecute))
					return true;

				echo "<hr>Could not execute queries:";
				foreach($sqlQueriesToExecute as $query)
					echo "<br> $query";
				*/


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


		function makeInsert($tableName){
			if(!SQLTables::isValidValue($tableName))
				return NULL;

			$q = new SQLQuery();

			$queryVals = [];
			foreach(SQLTables::$DBTableStructure[$tableName] as $dbField){
				$val = extractValueFromPOST(QueryFieldNames::mapDBToQueryForm($tableName)[$dbField]);
				array_push($queryVals, $val ? $val : "NULL");
			}

			$q->setInsertQuery($tableName, SQLTables::$DBTableStructure[$tableName], [$queryVals]);
			return $q;
		}

		function getCSOSponsRepSQLQuery(){
			/*For reference:
				SQLTables::SponsRep => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			*/
			echo $this->makeInsert(SQLTables::CommitteeMember)->getQuery();
			echo $this->makeInsert(SQLTables::SponsLogin)->getQuery();
			echo $this->makeInsert(SQLTables::SponsRep)->getQuery();


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



		function getSectorHeadQuery(){
			switch($this->tableName){
				case SQLTables::Event :
					return $this->getSectorHeadEventSQLQuery();
					break;
				case SQLTables::SponsLogin :
//					return $this->setSectorHeadSponsLoginSQLQuery();
					break;
				case SQLTables::SponsRep :
					return $this->getSectorHeadSponsRepSQLQuery();
					break;
				case SQLTables::SectorHead :
					return $this->getSectorHeadSectorHeadSQLQuery();
					break;
				case SQLTables::AccountLog :
					return $this->getSectorHeadAccountLogSQLQuery();
					break;
				case SQLTables::Company :
					return $this->getSectorHeadCompanySQLQuery();
					break;
				case SQLTables::CompanyExec :
					return $this->getSectorHeadCompanyExecSQLQuery();
					break;
				case SQLTables::Meeting :
					return $this->getSectorHeadMeetingSQLQuery();
					break;
			}
		}


		function getSectorHeadEventSQLQuery(){

		}

		function getSectorHeadSponsRepSQLQuery(){

		}

		function getSectorHeadSectorHeadSQLQuery(){

		}

		function getSectorHeadAccountLogSQLQuery(){

		}

		function getSectorHeadCompanySQLQuery(){

		}

		function getSectorHeadCompanyExecSQLQuery(){

		}

		function getSectorHeadMeetingSQLQuery(){

		}




		function getSponsRepQuery(){
			switch($this->tableName){
				case SQLTables::Event :
					return $this->getSponsRepEventSQLQuery();
					break;
				case SQLTables::SponsLogin :
//					return $this->setSponsRepSponsLoginSQLQuery();
					break;
				case SQLTables::SponsRep :
					return $this->getSponsRepSponsRepSQLQuery();
					break;
				case SQLTables::SectorHead :
					return $this->getSponsRepSectorHeadSQLQuery();
					break;
				case SQLTables::AccountLog :
					return $this->getSponsRepAccountLogSQLQuery();
					break;
				case SQLTables::Company :
					return $this->getSponsRepCompanySQLQuery();
					break;
				case SQLTables::CompanyExec :
					return $this->getSponsRepCompanyExecSQLQuery();
					break;
				case SQLTables::Meeting :
					return $this->getSponsRepMeetingSQLQuery();
					break;
			}
		}


		function getSponsRepEventSQLQuery(){

		}

		function getSponsRepSponsRepSQLQuery(){

		}

		function getSponsRepSectorHeadSQLQuery(){

		}

		function getSponsRepAccountLogSQLQuery(){

		}

		function getSponsRepCompanySQLQuery(){

		}

		function getSponsRepCompanyExecSQLQuery(){

		}

		function getSponsRepMeetingSQLQuery(){

		}



	}

	/*
	foreach($_POST as $key => $value){
		echo "<br>".$key." : ".$value;
	}
	echo "<hr>";
	*/

	$e = new QueryExecute($_SESSION[SessionEnums::UserAccessLevel], $_SESSION[QueryFormSessionEnums::TableName], $_SESSION[QueryFormSessionEnums::QueryType]);
	$e->setSystemGenerated();
	$e->executeQuery();
//	echo $e->getInsert();







?>