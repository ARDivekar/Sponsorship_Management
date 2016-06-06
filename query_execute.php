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


		function QueryExecute($userType, $tableName, $queryType){
			if($queryType == QueryTypes::View)
				header("Location: table_output.php");
			$this->userType = $userType;
			$this->tableName = $tableName;
			$this->queryType = $queryType;
		}

		function executeQuery(){
			if (Authorization::checkValidAuthorization($this->userType, $this->tableName, $this->queryType)){
				//user is authorized to run this query
				$db = new SponsorshipDB();
				switch ($this->userType){
					case UserTypes::CSO:
						$this->executeCSOQuery();
						break;
					case UserTypes::SectorHead:
						$this->executeSectorHeadQuery();
						break;
					case UserTypes::SponsRep:
						$this->executeSponsRepQuery();
						break;
				}
			}
		}


		function executeCSOQuery(){
			switch($this->tableName){
				case SQLTables::Event :
					$this->getCSOEventSQLQuery();
					break;
				case SQLTables::SponsLogin :
//					return $this->setCSOSponsLoginSQLQuery();
					break;
				case SQLTables::SponsRep :
					$this->getCSOSponsRepSQLQuery();
					break;
				case SQLTables::SectorHead :
					$this->getCSOSectorHeadSQLQuery();
					break;
				case SQLTables::AccountLog :
					$this->getCSOAccountLogSQLQuery();
					break;
				case SQLTables::Company :
					$this->getCSOCompanySQLQuery();
					break;
				case SQLTables::CompanyExec :
					$this->getCSOCompanyExecSQLQuery();
					break;
				case SQLTables::Meeting :
					$this->getCSOMeetingSQLQuery();
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


	$e = new QueryExecute($_SESSION[SessionEnums::UserAccessLevel], extractValueFromPOST(QueryFormSessionEnums::TableName), extractValueFromPOST(QueryFormSessionEnums::QueryType));
	$e->executeQuery();






?>