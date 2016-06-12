<?php
	include_once "library_functions.php";
	include_once "Authorization.php";

	/*Resume old session:*/
	if(!isset($_SESSION[SessionEnums::UserLoginID]))
		session_start();

	if(!extractValueFromPOST(QueryFieldNames::Submit)){ //can only be set from submitting a form from query.php
		header("Location: home.php");
	}

	if( !Authorization::checkValidAuthorization(
			$_SESSION[SessionEnums::UserAccessLevel],
			$_SESSION[QueryFormSessionEnums::TableName],
			$_SESSION[QueryFormSessionEnums::QueryType]
		)){
		header("Location: home.php");
	}



	include_once "DBconnect.php";
	include_once "SQLQuery.php";
	include_once "SecurityFunctions.php";





	class QueryExecute{

		var $userType = NULL;
		var $tableName = NULL;
		var $queryType = NULL;

		var $tableFields = NULL;


		function QueryExecute($userType, $tableName, $queryType){
			if($queryType == QueryTypes::View){
				header("Location: table_output.php?"
				   .QueryFormSessionEnums::QueryType."=".QueryTypes::View
				   ."&"
				   .TableOutputSessionEnums::TableName."=".$tableName
				 );
			}
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

			if(!extractValueFromPOST(QueryFieldNames::SponsSector) || $_SESSION[SessionEnums::UserAccessLevel] != UserTypes::CSO)
				$_POST[QueryFieldNames::SponsSector] = $_SESSION[SessionEnums::UserSector];
			$_POST[QueryFieldNames::SponsDateAssigned] = getCurrentDate();

			if(!extractValueFromPOST(QueryFieldNames::SponsAccountLogEntryID))
				$_POST[QueryFieldNames::SponsAccountLogEntryID] = "ACC-".makeRandomString();

			if(!extractValueFromPOST(QueryFieldNames::SponsMeetingEntryID))
				$_POST[QueryFieldNames::SponsMeetingEntryID] = "MEET-".makeRandomString();

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

		function checkIfFieldIsPresent($fieldNamesList, $formMethod=FormMethod::POST){
			if($formMethod==FormMethod::POST){
				foreach($fieldNamesList as $fieldName)
					if(extractValueFromPOST($fieldName))
						return true;
			}

			else if($formMethod==FormMethod::GET){
				foreach($fieldNamesList as $fieldName)
					if(extractValueFromGET($fieldName))
						return true;
			}


			return false;
		}




		function getWhereClauseRequiredInDB($tableName){
			$queryType = $this->queryType;

			$whereClauseArray = [];
			foreach(SQLTables::$DBTableStructure[$tableName] as $dbTableField){
				$possibleQueryFieldName = QueryFieldNames::mapDBToQueryForm($tableName)[$dbTableField];
				if(in_array($possibleQueryFieldName, QueryFieldNames::$requiredFields[$tableName][$queryType])){
					$valFromPOST = extractValueFromPOST($possibleQueryFieldName);
					if($valFromPOST)
						array_push($whereClauseArray, [$dbTableField, $valFromPOST]);
				}
			}
			return SQLQuery::getWhereEquality($whereClauseArray);
		}



		function checkRequiredExistsInDB($tableName, $whereClause){
			if(!SQLTables::isValidValue($tableName))
				throw new Exception();

			$db = new SponsorshipDB();
			$q = new SQLQuery();

			$q->setSelectQuery($tableName, $tableFields="*", $whereClause);

//			echo "<hr>".$q->getQuery()."<hr>";

			if( count($db->select($q->getQuery())) > 0 )
				return true;

			return false;
		}


		function checkRequiredExistsInDBDefaultWhere($tablesList){
			foreach($tablesList as $tableName){
				if (!SQLTables::isValidValue($tableName)){
					throw new Exception();
				}

				$tableRequiredWhereClause = $this->getWhereClauseRequiredInDB($tableName);
				if (!$this->checkRequiredExistsInDB($tableName, $tableRequiredWhereClause)){
					return false;
				}
			}
			return true;
		}



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


				$sqlStringsToExecute = [];
				foreach($sqlQueriesToExecute as $queryObj){
					array_push($sqlStringsToExecute, $queryObj->getQuery());
				}

				if($db->performTransaction($sqlStringsToExecute)){
//					echo "<hr>Successfully executed queries:";
//					foreach($sqlQueriesToExecute as $query)
//						echo "<br> $query";
					return true;
				}

				echo "<hr>Could not execute queries:";
				foreach($sqlQueriesToExecute as $query)
					echo "<br> $query";


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

			$insertQueryVals = [];
			foreach(SQLTables::$DBTableStructure[$tableName] as $dbField){
				$val = extractValueFromPOST(QueryFieldNames::mapDBToQueryForm($tableName)[$dbField]);
				array_push($insertQueryVals, $val ? $val : "NULL");
			}

			$q->setInsertQuery($tableName, SQLTables::$DBTableStructure[$tableName], [$insertQueryVals]);
			return $q;
		}


		function makeMultipleInsertQueryObjs($tablesList){
			$queryObjs = [];
			foreach($tablesList as $tableName){
				array_push($queryObjs, $this->makeInsert($tableName));
			}

			return $queryObjs;
		}





		function makeUpdate($tableName, $whereClause=NULL){
			if(!SQLTables::isValidValue($tableName))
				return NULL;

			$q = new SQLQuery();

			$updateQueryVals = [];
			foreach(SQLTables::$DBTableStructure[$tableName] as $dbField){
				$val = extractValueFromPOST(QueryFieldNames::mapDBToQueryForm($tableName)[$dbField]);
				if($val){
					array_push($updateQueryVals, [$dbField, $val] );
				}
			}

			$q->setUpdateQuery($tableName, $tableUpdateFieldValues = $updateQueryVals, $whereClause = $whereClause);
			return $q;
		}


		function makeDelete($tableName, $whereClause=NULL){
			if(!SQLTables::isValidValue($tableName))
				return NULL;

			$q = new SQLQuery();

			$q->setDeleteQuery($tableName, $whereClause);
			return $q;

		}


		function checkExistsAndMakeMultipleQueryObjs($tablesList, $queryType){	//used for Update and Delete Queries.
			$queryObjs = [];

			foreach($tablesList as $tableName){
				$tableRequiredWhereClause = $this->getWhereClauseRequiredInDB($tableName);

				// Check:
				if(!$this->checkRequiredExistsInDB($tableName, $tableRequiredWhereClause))
					return NULL;

				// Make:
				if($queryType == QueryTypes::Modify)
					array_push($queryObjs, $this->makeUpdate($tableName,$tableRequiredWhereClause));
				else if($queryType == QueryTypes::Delete)
					array_push($queryObjs, $this->makeDelete($tableName,$tableRequiredWhereClause));
				else return NULL;

			}

			return $queryObjs;

		}







		function getCSOSponsRepSQLQuery(){
			/*For reference:
				SQLTables::SponsRep => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
				However, CSO cannot modify password.
			*/

			switch($this->queryType){
				case QueryTypes::Insert :
					return $this->makeMultipleInsertQueryObjs([SQLTables::CommitteeMember, SQLTables::SponsLogin, $this->tableName]);
					break;

				case QueryTypes::Modify :
					if($this->checkIfFieldIsPresent([QueryFieldNames::SponsPassword, QueryFieldNames::SponsRePassword], FormMethod::POST))
						return NULL;
					return $this->checkExistsAndMakeMultipleQueryObjs(
							$tablesList = [SQLTables::CommitteeMember, $this->tableName],
							$queryType = $this->queryType
					);
					break;

				case QueryTypes::Delete :
					$this->checkExistsAndMakeMultipleQueryObjs(
							$tablesList = [SQLTables::CommitteeMember, $this->tableName],
							$queryType = $this->queryType
					);
					break;
			}
			return NULL;
		}



		function getCSOSectorHeadSQLQuery(){
			/*For reference:
				SQLTables::SectorHead => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
				However, CSO cannot modify password.
			*/

			switch($this->queryType){
				case QueryTypes::Insert :
					break;
				case QueryTypes::Modify :
					if($this->checkIfFieldIsPresent(
							[QueryFieldNames::SponsPassword, QueryFieldNames::SponsRePassword], FormMethod::POST)
					) return NULL;
					return $this->checkExistsAndMakeMultipleQueryObjs(
							$tablesList = [SQLTables::CommitteeMember, $this->tableName],
							$queryType = $this->queryType
					);
					break;


				case QueryTypes::Delete :
					return $this->checkExistsAndMakeMultipleQueryObjs(
							$tablesList = [SQLTables::CommitteeMember, $this->tableName],
							$queryType = $this->queryType
					);
					break;

			}
			return NULL;

		}


		function getCSOAccountLogSQLQuery(){
			/*For reference:
				SQLTables::AccountLog => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			*/
			if(!$this->checkRequiredExistsInDBDefaultWhere([SQLTables::Company, SQLTables::CommitteeMember]))
				return NULL;

			switch($this->queryType){
				case QueryTypes::Insert :
					return $this->makeMultipleInsertQueryObjs([$this->tableName]);
					break;
				case QueryTypes::Modify :
					return $this->checkExistsAndMakeMultipleQueryObjs(
							$tablesList = [$this->tableName],
							$queryType = $this->queryType
					);
					break;
				case QueryTypes::Delete :
					return $this->checkExistsAndMakeMultipleQueryObjs(
							$tablesList = [$this->tableName],
							$queryType = $this->queryType
					);
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
					return $this->makeMultipleInsertQueryObjs([$this->tableName]);
					break;
				case QueryTypes::Modify :
					return $this->checkExistsAndMakeMultipleQueryObjs(
							$tablesList = [$this->tableName],
							$queryType = $this->queryType
					);
					break;
				case QueryTypes::Delete :
					return $this->checkExistsAndMakeMultipleQueryObjs(
							$tablesList = [$this->tableName],
							$queryType = $this->queryType
					);
					break;
			}
			return NULL;
		}


		function getCSOCompanyExecSQLQuery(){
			/*For reference:
				SQLTables::CompanyExec => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			*/
			if(!$this->checkRequiredExistsInDBDefaultWhere([SQLTables::Company]))
				return NULL;

			switch($this->queryType){
				case QueryTypes::Insert :
					return $this->makeMultipleInsertQueryObjs([$this->tableName]);
					break;
				case QueryTypes::Modify :
					return $this->checkExistsAndMakeMultipleQueryObjs(
							$tablesList = [$this->tableName],
							$queryType = $this->queryType
					);
					break;
				case QueryTypes::Delete :
					return $this->checkExistsAndMakeMultipleQueryObjs(
							$tablesList = [$this->tableName],
							$queryType = $this->queryType
					);
					break;
			}
			return NULL;
		}

		function getCSOMeetingSQLQuery(){
			/*For reference:
				SQLTables::Meeting => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View]
			*/
			if(!$this->checkRequiredExistsInDBDefaultWhere([SQLTables::CompanyExec]))
				return NULL;

			switch($this->queryType){
				case QueryTypes::Insert :
					return $this->makeMultipleInsertQueryObjs([$this->tableName]);
					break;
				case QueryTypes::Modify :
					return $this->checkExistsAndMakeMultipleQueryObjs(
							$tablesList = [$this->tableName],
							$queryType = $this->queryType
					);
					break;
				case QueryTypes::Delete :
					return $this->checkExistsAndMakeMultipleQueryObjs(
							$tablesList = [$this->tableName],
							$queryType = $this->queryType
					);
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
			/*For reference:
				SQLTables::Event => [],	//empty means no queries allowed
			*/
		}

		function getSectorHeadSponsRepSQLQuery(){
			/*For reference:
				SQLTables::SponsRep => [QueryTypes::Delete],	//Can remove SponsReps from their sector.
			*/
			return $this->getCSOSponsRepSQLQuery();
		}

		function getSectorHeadSectorHeadSQLQuery(){
			/*For reference:
				SQLTables::SectorHead => [],
			*/
			return NULL;
		}

		function getSectorHeadAccountLogSQLQuery(){
			/*For reference:
				SQLTables::AccountLog => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//Can only insert, modify, delete, and view for own sector
			*/
			return $this->getCSOAccountLogSQLQuery();
		}

		function getSectorHeadCompanySQLQuery(){
			/*For reference:
				SQLTables::Company => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			*/
			return $this->getCSOCompanySQLQuery();
		}

		function getSectorHeadCompanyExecSQLQuery(){
			/*For reference:
				SQLTables::CompanyExec => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			*/
			return $this->getCSOCompanyExecSQLQuery();
		}

		function getSectorHeadMeetingSQLQuery(){
			/*For reference:
				SQLTables::Meeting => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::View] //Can only view for own sector, and only modify own.
			*/
			return $this->getCSOMeetingSQLQuery();
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
			/*For reference:
				SQLTables::Event => [QueryTypes::View],		//Can only view own details.
			*/
			return NULL;
		}

		function getSponsRepSponsRepSQLQuery(){
			/*For reference:
				SQLTables::SponsRep => [QueryTypes::View],
			*/
			return NULL;
		}

		function getSponsRepSectorHeadSQLQuery(){
			/*For reference:
				SQLTables::SectorHead => [],
			*/
			return NULL;
		}

		function getSponsRepAccountLogSQLQuery(){
			/*For reference:
				SQLTables::AccountLog => [QueryTypes::View],	//can only view own sponsorships
			*/
			return NULL;
		}

		function getSponsRepCompanySQLQuery(){
			/*For reference:
				SQLTables::Company => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			*/
			return $this->getCSOCompanySQLQuery();
		}

		function getSponsRepCompanyExecSQLQuery(){
			/*For reference:
				SQLTables::CompanyExec => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			*/
			return $this->getCSOCompanyExecSQLQuery();
		}

		function getSponsRepMeetingSQLQuery(){
			/*For reference:
				SQLTables::Meeting => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::View] //Can only view for own sector, and only modify own.
			*/
			return $this->getCSOMeetingSQLQuery();
		}



	}


	$e = new QueryExecute($_SESSION[SessionEnums::UserAccessLevel], $_SESSION[QueryFormSessionEnums::TableName], $_SESSION[QueryFormSessionEnums::QueryType]);
	$e->setSystemGenerated();
	$e->executeQuery();








?>