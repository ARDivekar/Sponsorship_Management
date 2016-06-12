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
	include_once "SQLQuery.php";
	include_once "SponsEnums.php";


	class TableOutput{
		var $userType = NULL;
		var $tableName = NULL;

		function TableOutput($userType, $tableName){
			$this->userType = $userType;
			$this->tableName = $tableName;
		}

		function retrieveOutputTable(){
			if (Authorization::checkValidAuthorization($this->userType, $this->tableName, QueryTypes::View)){
				$db = new SponsorshipDB();

				$q = $this->getOutputQuery();
//				echo "<hr>".$q->getQuery()."<hr>";
				if(!$q)
					return NULL;

				$tableResult = $db->select($q->getQuery());
				if(count($tableResult)>0){
					return make_simple_table($tableResult);
				}
			}
			return NULL;
		}


		function getOutputQuery(){
			if (Authorization::checkValidAuthorization($this->userType, $this->tableName, QueryTypes::View)){
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
			}
			return new SQLQuery();
		}

		function getWhereClauseIfFieldInDBStrucutre($tableNamesList=NULL, $whereArray){
			if(!$tableNamesList)
				$tableNamesList = [$this->tableName];

			$existingWhereArray = [];
			foreach($tableNamesList as $tableName){
				foreach($whereArray as $wherePair){
					if(!$wherePair || count($wherePair)!=2)
						continue;
					if (in_array($wherePair[0], SQLTables::$DBTableStructure[$tableName]))
						array_push($existingWhereArray, [$tableName.".".$wherePair[0], $wherePair[1]]);
				}
			}
			return SQLQuery::getWhereEquality($existingWhereArray);
		}


		static $tableNamesList = [
			SQLTables::SponsRep => [SQLTables::CommitteeMember, SQLTables::SponsRep],
			SQLTables::SectorHead => [SQLTables::CommitteeMember, SQLTables::SectorHead],
			SQLTables::AccountLog => [SQLTables::AccountLog, SQLTables::SponsOfficer],
			SQLTables::Company => [SQLTables::Company],
			SQLTables::CompanyExec => [SQLTables::Company, SQLTables::CompanyExec],
			SQLTables::Meeting => [SQLTables::SponsOfficer, SQLTables::Meeting]
		];


		private function getCSOQuery(){
			$CSOSelectQuery = new SQLQuery();
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
					break;

				case SQLTables::AccountLog :
					$CSOSelectQuery->setSelectQuery(
						$tableName = SQLQuery::getInnerJoin(SQLTables::AccountLog, "SponsID", SQLTables::SponsOfficer, "SponsID"),
						$tableFields = [
							[SQLTables::AccountLog.".ID", "Entry ID"], [SQLQuery::format(SQLTables::AccountLog.".Amount"), "Amount (Rs.)"],
							[SQLTables::AccountLog.".Title", "Company"], [SQLTables::SponsOfficer.".Sector", "Sector"],
							[SQLTables::AccountLog.".Date", "Entry Date"],
							[SQLTables::SponsOfficer.".SponsID", "SponsID"], [SQLTables::SponsOfficer.".Name", "Name"],
							[SQLTables::SponsOfficer.".Role", "Role"]
						]
					);
					break;

				case SQLTables::Company :
					$CSOSelectQuery->setSelectQuery(
						$tableName = SQLTables::Company,
						$tableFields = [
							[SQLTables::Company.".CMPName", "Company Name"], [SQLTables::Company.".Sector", "Sector"],
							[SQLTables::Company.".CMPStatus", "Current Status"],
							[SQLTables::Company.".PreviouslySponsoredYear", "Last Sponsored"],
							[SQLTables::Company.".SponsoredOtherOrganization", "Sponsored Other Organizations"],
							[SQLTables::Company.".CMPAddress", "Address"]
						]
					);
					break;

				case SQLTables::CompanyExec :
					$CSOSelectQuery->setSelectQuery(
						$tableName = SQLQuery::getInnerJoin(SQLTables::CompanyExec, "CMPName", SQLTables::Company, "CMPName"),
						$tableFields = [
							[SQLTables::Company.".CMPName", "Company Name"],
							[SQLTables::Company.".CMPStatus", "Current Status"], [SQLTables::Company.".Sector", "Sector"],
							[SQLTables::CompanyExec.".CEName", "Executive Name"], [SQLTables::CompanyExec.".CEPosition", "Position"],
							[SQLTables::CompanyExec.".CEMobile", "Mobile"], [SQLTables::CompanyExec.".CEEmail", "Email"]
						]
					);
					break;

				case SQLTables::Meeting :
					$CSOSelectQuery->setSelectQuery(
						$tableName = SQLQuery::getInnerJoin(SQLTables::Meeting, "SponsID", SQLTables::SponsOfficer, "SponsID"),
						$tableFields = [
							[SQLTables::Meeting.".ID", "Meeting ID"], [SQLTables::Meeting.".Outcome", "Outcome"],
							[SQLTables::Meeting.".CMPName", "Company Name"], [SQLTables::SponsOfficer.".Sector", "Company Sector"],
							[SQLTables::Meeting.".CEName", "Executive Name"],
							[SQLTables::SponsOfficer.".SponsID", "Meeter ID"], [SQLTables::SponsOfficer.".Name", "Meeter Name"],
							[SQLTables::Meeting.".MeetingType", "Meeting Type"], [SQLTables::Meeting.".Date", "Date"],
							[SQLTables::Meeting.".Time", "Time"], [SQLTables::Meeting.".Address", "Address"]
						]
					);
					break;

				default:
					return NULL;
			}

			$CSOSelectQuery->whereClause = $this->getWhereClauseIfFieldInDBStrucutre(
				$tableNamesList = self::$tableNamesList[$this->tableName],
				$whereArray = [
					["Organization", 	$_SESSION[SessionEnums::UserOrganization]],
					["EventName", 		$_SESSION[SessionEnums::UserFestival]],
				]
			);


			return $CSOSelectQuery;
		}


		private function getSectorHeadQuery(){
			$SectorHeadSelectQuery = $this->getCSOQuery();
			$SectorHeadSelectQuery->whereClause = $this->getWhereClauseIfFieldInDBStrucutre(
				$tableNamesList = self::$tableNamesList[$this->tableName],
				$whereArray = [
				["Organization", 	$_SESSION[SessionEnums::UserOrganization]],
				["EventName", 		$_SESSION[SessionEnums::UserFestival]],
				["Sector",	 		$_SESSION[SessionEnums::UserSector]]
			]);
			return $SectorHeadSelectQuery;
		}


		private function getSponsRepQuery(){
			$SponsRepSelectQuery = $this->getCSOQuery();
			$SponsRepSelectQuery->whereClause = $this->getWhereClauseIfFieldInDBStrucutre(
				$tableNamesList = self::$tableNamesList[$this->tableName],
				$whereArray = [
				["Organization", 	$_SESSION[SessionEnums::UserOrganization]],
				["EventName", 		$_SESSION[SessionEnums::UserFestival]],
				["Sector",	 		$_SESSION[SessionEnums::UserSector]],
				$this->tableName == SQLTables::Meeting ? NULL : ["SponsID", $_SESSION[SessionEnums::UserLoginID]]
			]);
			return $SponsRepSelectQuery;
		}

	}


	/*##------------------------------------------------TESTS------------------------------------------------##

	$t = new TableOutput(UserTypes::CSO, SQLTables::AccountLog);
	echo $t->getWhereClauseIfFieldInDBStrucutre([["SponsID", "123"], ["Amount", 5000], ["Title", "Loi"], ["Bible", "Loi"]]);


	/*##---------------------------------------------END OF TESTS---------------------------------------------##*/





?>