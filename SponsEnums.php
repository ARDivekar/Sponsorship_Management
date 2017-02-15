<?php
	include_once "DBconnect.php";
	include_once "library_functions.php";
	include_once "BasicEnum.php";

	abstract class FormMethod extends BasicEnum{
		const GET = "GET";
		const POST = "POST";
	}



	abstract class SessionEnums extends BasicEnum{
		const UserLoginID = "UserLoginID";
		const UserAccessLevel = "UserAccessLevel";
		const UserOrganization = "UserOrganization";
		const UserFestival = "UserFestival";
		const UserDepartment = "UserDepartment";
		const UserName = "UserName";
		const UserRole = "UserRole";
		const UserSector = "UserSector";
		const UserEmail = "UserEmail";
		const UserMobile = "UserMobile";
		const UserYear = "UserYear";
		const UserBranch = "UserBranch";
	}


	abstract class UserTypes extends BasicEnum{
		const CSO = "CSO";
		const SponsRep = "Sponsorship Representative";
		const SponsorshipRepresentative = "Sponsorship Representative";
		const SectorHead = "Sector Head";
	}



	abstract class QueryTypes extends BasicEnum{
		const Insert = "Insert";
		const Modify = "Modify";
		const Delete = "Delete";
		const View = "View";
	}



	abstract class SQLTables extends BasicEnum{
		const Event = "Event";
		const SponsLogin = "SponsLogin";
		const CommitteeMember = "CommitteeMember";
		const SponsRep = "SponsRep";
		const SectorHead = "SectorHead";
		const AccountLog = "AccountLog";
		const Company = "Company";
		const CompanyExec = "CompanyExec";
		const Meeting = "Meeting";

		//Views:
		const SponsOfficer = "SponsOfficer";
		const SponsWorker = "SponsWorker";

		static $DBTableStructure = [
		];

		static function setDBStructure(){
			if(self::$DBTableStructure != []) //don't reset it multiple times.
				return;
			$db = new SponsorshipDB();
			SQLTables::$DBTableStructure[SQLTables::Event] = $db->getTableColumns(SQLTables::Event);
			SQLTables::$DBTableStructure[SQLTables::SponsLogin] = $db->getTableColumns(SQLTables::SponsLogin);
			SQLTables::$DBTableStructure[SQLTables::CommitteeMember] = $db->getTableColumns(SQLTables::CommitteeMember);
			SQLTables::$DBTableStructure[SQLTables::SponsRep] = $db->getTableColumns(SQLTables::SponsRep);
			SQLTables::$DBTableStructure[SQLTables::SectorHead] = $db->getTableColumns(SQLTables::SectorHead);
			SQLTables::$DBTableStructure[SQLTables::AccountLog] = $db->getTableColumns(SQLTables::AccountLog);
			SQLTables::$DBTableStructure[SQLTables::Company] = $db->getTableColumns(SQLTables::Company);
			SQLTables::$DBTableStructure[SQLTables::CompanyExec] = $db->getTableColumns(SQLTables::CompanyExec);
			SQLTables::$DBTableStructure[SQLTables::Meeting] = $db->getTableColumns(SQLTables::Meeting);

			SQLTables::$DBTableStructure[SQLTables::SponsOfficer] = $db->getTableColumns(SQLTables::SponsOfficer);
			SQLTables::$DBTableStructure[SQLTables::SponsWorker] = $db->getTableColumns(SQLTables::SponsWorker);

		}

	}







	abstract class TransType extends BasicEnum{
		const Deposit = "Deposit";
		const Withdraw = "Withdraw";
	}


	abstract class CompanyStatus extends BasicEnum{
		const NotCalled = "Not Called";
		const PendingReply = "Pending reply";
		const NotInterested = "Not Interested";
		const AlreadyASponsor = "Already a sponsor";
	}


	abstract class CompanySponsoredOthers extends BasicEnum{
		const Yes = "Yes";
		const No = "No";
	}


	abstract class MeetingTypes extends BasicEnum{
		const Call = "Call";
		const Email = "Email";
		const FaceToFace = "Face-To-Face meeting";
	}


	abstract class MeetingOutcomes extends BasicEnum{
		const CompanyAgreedToSponsor = "Company has agreed to sponsor";
		const ScheduledFurtherMeeting = "Another meeting has been scheduled";
		const CompanyIsNotInterested = "Company is not interested at this moment";
	}




	abstract class QueryFormSessionEnums extends BasicEnum{
		const QueryType = "QueryType";
		const TableName = "TableName";
	}

	abstract class QueryExecSessionEnums extends BasicEnum{
		const QueryResultText = "QueryResultText";
	}

	abstract class TableOutputSessionEnums extends BasicEnum{
		const TableName = "TableName";
		const TableSelectQuery = "TableSelectQuery";
	}




	abstract class QueryFieldNames extends BasicEnum{
		const SponsOrganization = "SponsOrganization";
		const SponsFestival = "SponsFestival";
		const SponsSector = "SponsSector";
		const SponsOthersSector = "SponsOthersSector";
		const SponsDepartment = "SponsDepartment";
		const SponsRole = "SponsRole";
		const SponsID = "SponsID";
		const SponsOthersID = "SponsOthersID";
		const SponsName = "SponsName";
		const SponsPassword = "SponsPassword";
		const SponsRePassword = "SponsRePassword";
		const SponsEmail = "SponsEmail";
		const SponsMobile = "SponsMobile";
		const SponsYear = "SponsYear";
		const SponsBranch = "SponsBranch";
		const SponsDateAssigned = "SponsDateAssigned";
		const SponsCompany = "SponsCompany";
		const SponsTransType = "SponsTransType";
		const SponsDate = "SponsDate";
		const SponsTime = "SponsTime";
		const SponsAmount = "SponsAmount";
		const SponsAccountLogEntryID = "SponsAccountLogEntryID";
		const SponsCompanyStatus = "SponsCompanyStatus";
		const SponsCompanySponsoredOthers = "SponsCompanySponsoredOthers";
		const SponsCompanyPreviouslySponsoredYear = "SponsCompanyPreviouslySponsoredYear";
		const SponsCompanyAddress = "SponsCompanyAddress";
		const SponsCompanyExec = "SponsCompanyExec";
		const SponsCompanyExecEmail = "SponsCompanyExecEmail";
		const SponsCompanyExecMobile = "SponsCompanyExecMobile";
		const SponsCompanyExecPosition = "SponsCompanyExecPosition";
		const SponsMeetingAddress = "SponsMeetingAddress";
		const SponsMeetingType = "SponsMeetingType";
		const SponsMeetingOutcome = "SponsMeetingOutcome";
		const SponsMeetingEntryID = "SponsMeetingEntryID";
		const CSOSector = "All";
		const Submit = "Submit";


		static $TableToFieldNameOrdering = [ //used to specify ordering in forms
		//IMP! Each of these arrays must be a global list of ALL POSSIBLE fields in the form, many things depend on this variable!

			SQLTables::AccountLog => [
				QueryFieldNames::SponsFestival,
				QueryFieldNames::SponsAccountLogEntryID,
				QueryFieldNames::SponsTransType,
				QueryFieldNames::SponsID,
				QueryFieldNames::SponsCompany,
				QueryFieldNames::SponsSector,
				QueryFieldNames::SponsDate,
				QueryFieldNames::SponsAmount,
				QueryFieldNames::Submit
			],

			SQLTables::Company => [
				QueryFieldNames::SponsFestival,
				QueryFieldNames::SponsCompany,
				QueryFieldNames::SponsSector,
				QueryFieldNames::SponsCompanyStatus,
				QueryFieldNames::SponsCompanyAddress,
				QueryFieldNames::SponsCompanySponsoredOthers,
				QueryFieldNames::Submit
			],

			SQLTables::CompanyExec => [
				QueryFieldNames::SponsFestival,
				QueryFieldNames::SponsCompany,
				QueryFieldNames::SponsSector,
				QueryFieldNames::SponsCompanyExec,
				QueryFieldNames::SponsCompanyExecEmail,
				QueryFieldNames::SponsCompanyExecMobile,
				QueryFieldNames::SponsCompanyExecPosition,
				QueryFieldNames::Submit
			],

			SQLTables::Meeting => [
				QueryFieldNames::SponsFestival,
				QueryFieldNames::SponsMeetingEntryID,
				QueryFieldNames::SponsID,
				QueryFieldNames::SponsCompany,
				QueryFieldNames::SponsSector,
				QueryFieldNames::SponsCompanyExec,
				QueryFieldNames::SponsMeetingType,
				QueryFieldNames::SponsDate,
				QueryFieldNames::SponsTime,
				QueryFieldNames::SponsMeetingAddress,
				QueryFieldNames::SponsMeetingOutcome,
				QueryFieldNames::Submit
			],


			SQLTables::CommitteeMember => [
				QueryFieldNames::SponsFestival,
				QueryFieldNames::SponsSector,
				QueryFieldNames::SponsOthersSector,
				QueryFieldNames::SponsRole,
				QueryFieldNames::SponsOthersID,
				QueryFieldNames::SponsName,
				QueryFieldNames::SponsPassword,
				QueryFieldNames::SponsRePassword,
				QueryFieldNames::SponsEmail,
				QueryFieldNames::SponsMobile,
				QueryFieldNames::SponsYear,
				QueryFieldNames::SponsBranch,
				QueryFieldNames::Submit
			],


			SQLTables::SponsRep => [
				QueryFieldNames::SponsFestival,
				QueryFieldNames::SponsSector,
				QueryFieldNames::SponsOthersSector,
				QueryFieldNames::SponsRole,
				QueryFieldNames::SponsOthersID,
				QueryFieldNames::SponsName,
				QueryFieldNames::SponsPassword,
				QueryFieldNames::SponsRePassword,
				QueryFieldNames::SponsEmail,
				QueryFieldNames::SponsMobile,
				QueryFieldNames::SponsYear,
				QueryFieldNames::SponsBranch,
				QueryFieldNames::Submit
			],


			SQLTables::SectorHead => [
				QueryFieldNames::SponsFestival,
				QueryFieldNames::SponsSector,
				QueryFieldNames::SponsOthersSector,
				QueryFieldNames::SponsRole,
				QueryFieldNames::SponsOthersID,
				QueryFieldNames::SponsName,
				QueryFieldNames::SponsPassword,
				QueryFieldNames::SponsRePassword,
				QueryFieldNames::SponsEmail,
				QueryFieldNames::SponsMobile,
				QueryFieldNames::SponsYear,
				QueryFieldNames::SponsBranch,
				QueryFieldNames::Submit
			],



			SQLTables::Event => [

			],

			SQLTables::SponsLogin => [

			]

		];



		static $requiredFields = [ //used to specify ordering in forms
			SQLTables::AccountLog => [
				QueryTypes::Insert => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsTransType,
					QueryFieldNames::SponsID,
					QueryFieldNames::SponsCompany,
					QueryFieldNames::SponsDate,
					QueryFieldNames::SponsAmount,
					QueryFieldNames::SponsSector
				],

				QueryTypes::Modify => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsAccountLogEntryID,
					QueryFieldNames::SponsSector
				],

				QueryTypes::Delete => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsAccountLogEntryID,
					QueryFieldNames::SponsCompany,
					QueryFieldNames::SponsSector
				]

			],



			SQLTables::Company => [
				QueryTypes::Insert => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsCompany,
					QueryFieldNames::SponsSector
				],

				QueryTypes::Modify => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsCompany,
					QueryFieldNames::SponsSector
				],

				QueryTypes::Delete => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsCompany,
					QueryFieldNames::SponsSector
				]

			],



			SQLTables::CompanyExec => [
				QueryTypes::Insert => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsCompany,
					QueryFieldNames::SponsCompanyExec,
					QueryFieldNames::SponsSector
				],

				QueryTypes::Modify => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsCompany,
					QueryFieldNames::SponsCompanyExec,
					QueryFieldNames::SponsSector
				],

				QueryTypes::Delete => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsCompany,
					QueryFieldNames::SponsCompanyExec,
					QueryFieldNames::SponsSector
				]

			],


			SQLTables::Meeting => [
				QueryTypes::Insert => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsID,
					QueryFieldNames::SponsCompany,
					QueryFieldNames::SponsCompanyExec,
					QueryFieldNames::SponsMeetingType,
					QueryFieldNames::SponsDate,
					QueryFieldNames::SponsSector
				],

				QueryTypes::Modify => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsID,
					QueryFieldNames::SponsMeetingEntryID,
					QueryFieldNames::SponsSector
				],

				QueryTypes::Delete => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsID,
					QueryFieldNames::SponsMeetingEntryID,
					QueryFieldNames::SponsSector
				]
			],



			SQLTables::CommitteeMember => [
				QueryTypes::Insert => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsSector,
					QueryFieldNames::SponsRole,
					QueryFieldNames::SponsOthersID,
					QueryFieldNames::SponsName,
					QueryFieldNames::SponsPassword
				],

				QueryTypes::Modify => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsSector,
					QueryFieldNames::SponsRole,
					QueryFieldNames::SponsOthersID
				],

				QueryTypes::Delete => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsSector,
					QueryFieldNames::SponsRole,
					QueryFieldNames::SponsOthersID,
					QueryFieldNames::SponsName
				]
			],


			SQLTables::SponsRep => [
				QueryTypes::Insert => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsSector,
					QueryFieldNames::SponsRole,
					QueryFieldNames::SponsOthersID,
					QueryFieldNames::SponsName,
					QueryFieldNames::SponsPassword
				],

				QueryTypes::Modify => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsSector,
					QueryFieldNames::SponsRole,
					QueryFieldNames::SponsOthersID
				],

				QueryTypes::Delete => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsSector,
					QueryFieldNames::SponsRole,
					QueryFieldNames::SponsOthersID,
					QueryFieldNames::SponsName
				]
			],


			SQLTables::SectorHead => [ //mostly identical to SponsRep
				QueryTypes::Insert => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsSector,
					QueryFieldNames::SponsRole,
					QueryFieldNames::SponsOthersID,
					QueryFieldNames::SponsName,
					QueryFieldNames::SponsPassword
				],

				QueryTypes::Modify => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsOthersID
				],

				QueryTypes::Delete => [
					QueryFieldNames::SponsFestival,
					QueryFieldNames::SponsOthersID,
					QueryFieldNames::SponsName
				]
			],



			SQLTables::Event => [

			],

			SQLTables::SponsLogin => [

			]

		];




		static $inapplicableFields = [
			UserTypes::CSO => [
				QueryFieldNames::SponsSector
			],

			UserTypes::SectorHead => [
			],

			UserTypes::SponsRep => [
			],
		];





		static function mapDBToQueryForm($tableName){

			switch($tableName){

				case SQLTables::Event :
					return  [
						"EventName" => QueryFieldNames::SponsFestival
					];
					break;


				case SQLTables::CommitteeMember :
					return  [
						"Organization" => QueryFieldNames::SponsOrganization,
						"EventName" => QueryFieldNames::SponsFestival,
						"ID" => QueryFieldNames::SponsOthersID,
						"Name" => QueryFieldNames::SponsName,
						"Department" => QueryFieldNames::SponsDepartment,
						"Role" => QueryFieldNames::SponsRole,
						"Mobile" => QueryFieldNames::SponsMobile,
						"Email" => QueryFieldNames::SponsEmail,
						"Year" => QueryFieldNames::SponsYear,
						"Branch" => QueryFieldNames::SponsBranch,
					];
					break;


				case SQLTables::SponsRep :
					return  [
						"SponsID" => QueryFieldNames::SponsOthersID,
						"Sector" => $_SESSION[SessionEnums::UserAccessLevel] == UserTypes::CSO ? QueryFieldNames::SponsOthersSector : QueryFieldNames::SponsSector,
						"DateAssigned" => QueryFieldNames::SponsDateAssigned
					];
					break;


				case SQLTables::SectorHead :
					return  [
						"SponsID" => QueryFieldNames::SponsOthersID,
						"Sector" => $_SESSION[SessionEnums::UserAccessLevel] == UserTypes::CSO ? QueryFieldNames::SponsOthersSector : QueryFieldNames::SponsSector,
						"DateAssigned" => QueryFieldNames::SponsDateAssigned
					];
					break;


				case SQLTables::SponsLogin :
					return  [
						"SponsID" => QueryFieldNames::SponsOthersID,
						"Password" => QueryFieldNames::SponsPassword,
						"AccessLevel" => QueryFieldNames::SponsRole
					];
					break;



				case SQLTables::AccountLog :
					return  [
						"ID" => QueryFieldNames::SponsAccountLogEntryID,
						"Organization" => QueryFieldNames::SponsOrganization,
						"EventName" => QueryFieldNames::SponsFestival,
						"Title" => QueryFieldNames::SponsCompany,
						"SponsID" => QueryFieldNames::SponsID,
						"Amount" => QueryFieldNames::SponsAmount,
						"TransType" => QueryFieldNames::SponsTransType,
						"Date" => QueryFieldNames::SponsDate
					];
					break;



				case SQLTables::Company :
					return  [
						"CMPName" => QueryFieldNames::SponsCompany,
						"CMPStatus" => QueryFieldNames::SponsCompanyStatus,
						"Sector" => QueryFieldNames::SponsSector,
						"CMPAddress" => QueryFieldNames::SponsCompanyAddress,
						"PreviouslySponsoredYear" => QueryFieldNames::SponsCompanyPreviouslySponsoredYear,
						"SponsoredOtherOrganization" => QueryFieldNames::SponsCompanySponsoredOthers
					];
					break;


				case SQLTables::CompanyExec :
					return  [
						"CMPName" => QueryFieldNames::SponsCompany,
						"CEName" => QueryFieldNames::SponsCompanyExec,
						"CEMobile" => QueryFieldNames::SponsCompanyExecMobile,
						"CEEmail" => QueryFieldNames::SponsCompanyExecEmail,
						"CEPosition" => QueryFieldNames::SponsCompanyExecPosition
					];
					break;



				case SQLTables::Meeting :
					return  [

						"ID" => QueryFieldNames::SponsMeetingEntryID,
						"Organization" => QueryFieldNames::SponsOrganization,
						"EventName" => QueryFieldNames::SponsFestival,
						"Date" => QueryFieldNames::SponsDate,
						"Time" => QueryFieldNames::SponsTime,
						"SponsID" => QueryFieldNames::SponsID,
						"MeetingType" => QueryFieldNames::SponsMeetingType,
						"CMPName" => QueryFieldNames::SponsCompany,
						"CEName" => QueryFieldNames::SponsCompanyExec,
						"Outcome" => QueryFieldNames::SponsMeetingOutcome,
						"Address" => QueryFieldNames::SponsMeetingAddress

					];
					break;

			}

		}


	}



	$db = new SponsorshipDB();
	SQLTables::setDBStructure();	//set all the table columns for easy access.




	/*##------------------------------------------------TESTS------------------------------------------------##

	SQLTables::setDBStructure();	//set all the table columns for easy access.
	echo_1d_array(SQLTables::$DBTableStructure[SQLTables::AccountLog], SQLTables::AccountLog);
	echo_1d_array(SQLTables::$DBTableStructure[SQLTables::SponsOfficer], SQLTables::SponsOfficer);


	QueryFieldNames::setSystemGenerated();		//set all system generated variables for later use.
	echo_1d_array(QueryFieldNames::$systemGenerated, "System generated:");

	echo_2d_array(SQLTables::$DBTableStructure, "DB Structure");

	/*##---------------------------------------------END OF TESTS---------------------------------------------##*/


