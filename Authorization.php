<?php

	include_once "SponsEnums.php";

	abstract class Authorization{
		static $CSOAuth = [
			SQLTables::Event => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//can only insert an Event, not an Organization
			SQLTables::SponsLogin => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			SQLTables::SponsRep => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			SQLTables::SectorHead => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			SQLTables::AccountLog => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			SQLTables::Company => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			SQLTables::CompanyExec => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			SQLTables::Meeting => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View]
		];

		static $SectorHeadAuth = [
			SQLTables::Event => [],	//empty means no queries allowed
			SQLTables::SponsLogin => [QueryTypes::Modify, QueryTypes::View],	//Can only view and modify own password
			SQLTables::SponsRep => [QueryTypes::Delete],	//Can remove SponsReps from their sector.
			SQLTables::SectorHead => [],
			SQLTables::AccountLog => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//Can only insert, modify, delete, and view for own sector
			SQLTables::Company => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			SQLTables::CompanyExec => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			SQLTables::Meeting => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View] //Can only view for own sector, and only modify own.
		];

		static $SponsRepAuth = [
			SQLTables::Event => [QueryTypes::View],		//Can only view own details.
			SQLTables::SponsLogin => [QueryTypes::Modify, QueryTypes::View],	//Can only view and modify own password
			SQLTables::SponsRep => [QueryTypes::View],
			SQLTables::SectorHead => [],
			SQLTables::AccountLog => [QueryTypes::View],	//can only view own sponsorships
			SQLTables::Company => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			SQLTables::CompanyExec => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			SQLTables::Meeting => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View] //Can only view for own sector, and only modify own.
		];

		static function checkValidAuthorization($userType, $tableName, $queryType){
			if (UserTypes::isValidValue($userType) && SQLTables::isValidValue($tableName) && QueryTypes::isValidValue($queryType)){
				switch($userType){
					case UserTypes::CSO:
						if(in_array($queryType, Authorization::$CSOAuth[$tableName])){
							return true;
						}
						break;

					case UserTypes::SectorHead:
						if(in_array($queryType, Authorization::$SectorHeadAuth[$tableName])){
							return true;
						}
						break;

					case UserTypes::SponsRep:
						if(in_array($queryType, Authorization::$SponsRepAuth[$tableName])){
							return true;
						}
						break;
				}

			}
			else{
				echo "input values are not valid";
			}
			return false;
		}
	}


