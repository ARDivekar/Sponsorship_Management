<?php
	/**
	 * Created by PhpStorm.
	 * User: Abhishek Divekar
	 * Date: 05-06-2016
	 * Time: 11:52
	 */
	/*Resume old session:*/
	session_start();
	include_once "library_functions.php";
	include_once "Authorization.php";

	if (!extractValueFromGET(QueryFormSessionEnums::TableName)) {
		header("Location: home.php");
	}

	if( !Authorization::checkValidAuthorization(
			$_SESSION[SessionEnums::UserAccessLevel],
			extractValueFromGET(QueryFormSessionEnums::TableName),
			QueryTypes::View
		)){
		header("Location: home.php");
	}

	include_once "SQLQuery.php";
	include_once "SponsEnums.php";


	class TableOutput{
		var $userType = NULL;
		var $tableName = NULL;
		var $tableResult = NULL;

		function TableOutput($userType, $tableName){
			$this->userType = $userType;
			$this->tableName = $tableName;
		}

		function retrieveOutputTable(){
			if (Authorization::checkValidAuthorization($this->userType, $this->tableName, QueryTypes::View)){
				$db = new SponsorshipDB();

				$q = $this->getOutputQuery();
				echo "<hr>".$q->getQuery()."<hr>";
				if(!$q)
					return false;

				$tableResult = $db->select($q->getQuery());
				if(count($tableResult)>0){
					$this->tableResult = $tableResult;
					return true;
				}
			}
			return false;
		}


		function getOutputQuery(){
			switch ($this->userType){
				case UserTypes::CSO:
					return $this->getCSOQuery();
					break;
				case UserTypes::SectorHead:
					return $this->getSectorHeadQuery();
					break;
				case UserTypes::SponsRep:
					return $this->getSponsRepQuery();
					break;
			}
			return new SQLQuery();
		}

		function getWhereClauseIfFieldInDBStrucutre($tableNamesList=NULL, $whereArray){
			if(!$tableNamesList)
				$tableNamesList = [$this->tableName];

			$existingWhereArray = [];
			foreach($tableNamesList as $tableName){
				foreach($whereArray as $wherePair){
					if (in_array($wherePair[0], SQLTables::$DBTableStructure[$tableName]))
						array_push($existingWhereArray, [$tableName.".".$wherePair[0], $wherePair[1]]);
				}
			}
			return SQLQuery::getWhereEquality($existingWhereArray);
		}


		function getCSOQuery(){
			$CSOSelectQuery = new SQLQuery();
			$tableNamesList = [];
			switch($this->tableName){
				case SQLTables::Event :
					break;
				case SQLTables::SponsLogin :
//					return $this->setCSOSponsLoginSQLQuery();
					break;
				case SQLTables::SponsRep :
					$CSOSelectQuery->setSelectQuery(
						$tableName = SQLQuery::getInnerJoin(SQLTables::CommitteeMember, "ID", SQLTables::SponsRep, "SponsID"),
						$tableFields = [
							[SQLTables::CommitteeMember.".ID", "ID"], [SQLTables::CommitteeMember.".Name", "Name"],
							[SQLTables::CommitteeMember.".Role", "Role"], [SQLTables::SponsRep.".Sector", "Sector"],
							[SQLTables::CommitteeMember.".Mobile", "Mobile"], [SQLTables::CommitteeMember.".Email", "Email"],
							[SQLTables::CommitteeMember.".Year", "Year"], [SQLTables::CommitteeMember.".Branch", "Branch"],
						]
					);
					$tableNamesList = [SQLTables::CommitteeMember, SQLTables::SponsRep];
					break;

				case SQLTables::SectorHead :
					$CSOSelectQuery->setSelectQuery(
						$tableName = SQLQuery::getInnerJoin(SQLTables::CommitteeMember, "ID", SQLTables::SectorHead, "SponsID"),
						$tableFields = [
							[SQLTables::CommitteeMember.".ID", "ID"], [SQLTables::CommitteeMember.".Name", "Name"],
							[SQLTables::CommitteeMember.".Role", "Role"], [SQLTables::SponsRep.".Sector", "Sector"],
							[SQLTables::CommitteeMember.".Mobile", "Mobile"], [SQLTables::CommitteeMember.".Email", "Email"],
							[SQLTables::CommitteeMember.".Year", "Year"], [SQLTables::CommitteeMember.".Branch", "Branch"],
						]
					);
					$tableNamesList = [SQLTables::CommitteeMember, SQLTables::SectorHead];
					break;

				case SQLTables::AccountLog :
					$CSOSelectQuery->setSelectQuery(
						$tableName = SQLQuery::getInnerJoin(SQLTables::AccountLog, "SponsID", SQLViews::SponsOfficer, "SponsID"),
						$tableFields = [
							[SQLTables::AccountLog.".ID", "Entry ID"], [SQLQuery::format(SQLTables::AccountLog.".Amount"), "Amount (Rs.)"],
							[SQLTables::AccountLog.".Title", "Company"], [SQLViews::SponsOfficer.".Sector", "Sector"],
							[SQLTables::AccountLog.".Date", "Entry Date"],
							[SQLViews::SponsOfficer.".SponsID", "SponsID"], [SQLViews::SponsOfficer.".Name", "Name"],
							[SQLViews::SponsOfficer.".Role", "Role"]
						]
					);
					$tableNamesList = [SQLTables::AccountLog];
					break;

				case SQLTables::Company :
					$CSOSelectQuery->setSelectQuery(
						$tableName = SQLTables::Company,
						$tableFields = []
					);
					$tableNamesList = [SQLTables::Company];
					break;

				case SQLTables::CompanyExec :
					$CSOSelectQuery->setSelectQuery(
						$tableName = SQLTables::CompanyExec,
						$tableFields = []
					);
					$tableNamesList = [SQLTables::Company, SQLTables::CompanyExec];
					break;

				case SQLTables::Meeting :
					$CSOSelectQuery->setSelectQuery(
						$tableName = SQLTables::Meeting,
						$tableFields = []
					);
					$tableNamesList = [SQLTables::Company, SQLTables::CompanyExec, SQLTables::Meeting];
					break;

				default:
					return NULL;
			}

			$CSOSelectQuery->whereClause = $this->getWhereClauseIfFieldInDBStrucutre(
				$tableNamesList, $whereArray = [
					["Organization", 	$_SESSION[SessionEnums::UserOrganization]],
					["EventName", 		$_SESSION[SessionEnums::UserFestival]],
				]
			);


			return $CSOSelectQuery;
		}


		function getSectorHeadQuery(){
			$SectorHeadSelectQuery = $this->getCSOQuery();
			$SectorHeadSelectQuery->whereClause = $this->getWhereClauseIfFieldInDBStrucutre([
				["Organization", 	$_SESSION[SessionEnums::UserOrganization]],
				["EventName", 		$_SESSION[SessionEnums::UserFestival]],
				["Sector",	 		$_SESSION[SessionEnums::UserSector]]
			]);
			return $SectorHeadSelectQuery;
		}


		function getSponsRepQuery(){
			$SponsRepSelectQuery = $this->getCSOQuery();
			$SponsRepSelectQuery->whereClause = $this->getWhereClauseIfFieldInDBStrucutre([
				["Organization", 	$_SESSION[SessionEnums::UserOrganization]],
				["EventName", 		$_SESSION[SessionEnums::UserFestival]],
				["Sector",	 		$_SESSION[SessionEnums::UserSector]],
				["ID",		 		$_SESSION[SessionEnums::UserLoginID]],
				["SponsID",	 		$_SESSION[SessionEnums::UserLoginID]]
			]);
			return $SponsRepSelectQuery;
		}

	}


	/*##------------------------------------------------TESTS------------------------------------------------##

	$t = new TableOutput(UserTypes::CSO, SQLTables::AccountLog);
	echo $t->getWhereClauseIfFieldInDBStrucutre([["SponsID", "123"], ["Amount", 5000], ["Title", "Loi"], ["Bible", "Loi"]]);


	/*##---------------------------------------------END OF TESTS---------------------------------------------##*/

	$t = new TableOutput(
			$_SESSION[SessionEnums::UserAccessLevel],
			extractValueFromGET(QueryFormSessionEnums::TableName)
	);

	$t->retrieveOutputTable();


?>