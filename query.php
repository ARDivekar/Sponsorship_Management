<!DOCTYPE html PUBLIC "-/W3C//DTD HTML 4.01 Transitional/EN"
	"http://www.w3.org/TR/html4/loose.dtd">


<html lang="en">
<head>
<!--	<link rel="stylesheet" type="text/css" href="style.css">-->
<!--	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>-->
<!--	<script type="text/javascript" src="./User_GUI_CSS/disable_code_view.js"></script>-->
</head>

<body>

<?php
	/*Resume old session:*/
	session_start();
	include_once('library_functions.php');

	if (!isset($_GET['Submit']) && !isset($_GET['submit'])) {
		header("Location: home.php");
	}

	if( !Authorization::checkValidAuthorization(
			$_SESSION[SessionEnums::UserAccessLevel],
			extractValueFromGET(QueryFormSessionEnums::TableName),
			extractValueFromGET(QueryFormSessionEnums::QueryType)
		)){
		header("Location: home.php");
	}

	$_SESSION[QueryFormSessionEnums::TableName] = extractValueFromGET(QueryFormSessionEnums::TableName);
	$_SESSION[QueryFormSessionEnums::QueryType]= extractValueFromGET(QueryFormSessionEnums::QueryType);


	/* For testing purposes:
	$_SESSION[SessionEnums::UserFestival] = "Techno";
	$_SESSION[SessionEnums::UserLoginID] = 131080052;
	$_SESSION[SessionEnums::UserAccessLevel] = UserTypes::SponsRep; //for testing purposes
	$_SESSION[SessionEnums::UserSector] = "Music Stores";
	*/





	abstract class PredefinedQueryInputFields extends BasicEnum{
		public static function get($queryFieldName){
			switch($queryFieldName){
				/* Some rules:
				 * 	- Anything which is readonly should also be required.
				 *  - Keys should be required.
				 */

				case QueryFieldNames::SponsFestival :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value = $_SESSION[SessionEnums::UserFestival],
						$readonly = true, $inputCSSClass = NULL,
						$labelText = "Festival", $labelCSSClass = NULL, $inputDataListID = NULL, $inputDataList = NULL, $required = true
					);
					break;

				case QueryFieldNames::SponsSector :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsSector,
						$value = ($_SESSION[SessionEnums::UserAccessLevel] == UserTypes::CSO ? "" : $_SESSION[SessionEnums::UserSector] ),
						$readonly = ($_SESSION[SessionEnums::UserAccessLevel] == UserTypes::CSO ? false : true ), $inputCSSClass = NULL,
						$labelText = "Sector", $labelCSSClass = NULL,
						$inputDataListID="SectorInDB",
						$inputDataList=select_single_column_from_table("Sector", "Company")
					);
					break;


				case QueryFieldNames::SponsID :
					return new InputField(
						$inputType = InputTypes::number, $name = QueryFieldNames::SponsID, $value = $_SESSION[SessionEnums::UserLoginID], $readonly = true, $inputCSSClass = NULL,
						$labelText = "My ID", $labelCSSClass = NULL, $inputDataListID = NULL, $inputDataList = NULL, $required = true
					);
					break;


				case QueryFieldNames::SponsOthersID :
					return new InputField(
						$inputType = InputTypes::number, $name = QueryFieldNames::SponsOthersID, $value = "", $readonly = false, $inputCSSClass = NULL,
						$labelText = "Person's ID", $labelCSSClass = NULL, $inputDataListID = NULL, $inputDataList = NULL, $required = true
					);
					break;


				case QueryFieldNames::SponsName :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsName, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Name",
						$labelCSSClass = NULL, $inputDataListID="NameInDB",
						$inputDataList=select_single_column_from_table("Name", "CommitteeMember")
					);
					break;


				case QueryFieldNames::SponsPassword :
					return new InputField(
						$inputType = InputTypes::password, $name = QueryFieldNames::SponsPassword, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Password",
						$labelCSSClass = NULL
					);
					break;


				case QueryFieldNames::SponsRePassword :
					return new InputField(
						$inputType = InputTypes::password, $name = QueryFieldNames::SponsRePassword, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Re-enter Password",
						$labelCSSClass = NULL
					);
					break;


				case QueryFieldNames::SponsEmail :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsEmail, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Email",
						$labelCSSClass = NULL, $inputDataListID="EmailInDB", $inputDataList=select_single_column_from_table("Email", "CommitteeMember")
					);
					break;


				case QueryFieldNames::SponsMobile :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsMobile, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Mobile",
						$labelCSSClass = NULL, $inputDataListID="MobileInDB", $inputDataList=select_single_column_from_table("Mobile", "CommitteeMember")
					);
					break;


				case QueryFieldNames::SponsYear :
					return new InputField(
					$inputType = InputTypes::number, $name = QueryFieldNames::SponsYear, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Year", $labelCSSClass = NULL, $inputDataListID="YearEnum", $inputDataList=["1","2","3","4"]
					);
					break;


				case QueryFieldNames::SponsBranch :
					return new InputField(
					$inputType = InputTypes::text, $name = QueryFieldNames::SponsBranch, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Branch",
					$labelCSSClass = NULL, $inputDataListID="BranchInDB", $inputDataList=select_single_column_from_table("Branch", "CommitteeMember")
					);
					break;


				case QueryFieldNames::SponsCompany :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $readonly = false, $inputCSSClass = NULL,
						$labelText = "Company Name", $labelCSSClass = NULL, $inputDataListID="CompanyInDB",
						$inputDataList= ($_SESSION[SessionEnums::UserAccessLevel] == UserTypes::CSO ? select_single_column_from_table("CMPName", "Company") : select_single_column_from_table("CMPName", "Company", "Sector = \"".$_SESSION[SessionEnums::UserSector]."\""))
					);
					break;


				case QueryFieldNames::SponsTransType :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsTransType, $value = TransType::Deposit, $readonly = true,
						$inputCSSClass = NULL, $labelText = "Transaction Type", $labelCSSClass = NULL, $inputDataListID = NULL, $inputDataList = NULL, $required = true
					);
					break;


				case QueryFieldNames::SponsDate :
					return new InputField(
						$inputType = InputTypes::date, $name = QueryFieldNames::SponsDate, $value = "", $readonly = false, $inputCSSClass = NULL,
						$labelText = "Date", $labelCSSClass = NULL, $inputDataListID="TodaysDate",
						$inputDataList=[date('Y-m-d', time())]
					);
					break;


				case QueryFieldNames::SponsTime :
					return new InputField(
						$inputType = InputTypes::time, $name = QueryFieldNames::SponsTime, $value = "", $readonly = false, $inputCSSClass = NULL,
						$labelText = "Time", $labelCSSClass = NULL
					);
					break;


				case QueryFieldNames::SponsAmount :
					return new InputField(
						$inputType = InputTypes::number, $name = QueryFieldNames::SponsAmount, $value = "", $readonly = false,
						$inputCSSClass = NULL, $labelText = "Amount (Rs.)", $labelCSSClass = NULL
					);
					break;


				case QueryFieldNames::SponsAccountLogEntryID :
					return new InputField(
						$inputType = InputTypes::number, $name = QueryFieldNames::SponsAccountLogEntryID, $value = "", $readonly = false,
						$inputCSSClass = NULL, $labelText = "Account Transaction ID", $labelCSSClass = NULL, $inputDataListID = NULL, $inputDataList = NULL, $required = true
					);
					break;


				case QueryFieldNames::SponsCompanyStatus :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyStatus, $value = "", $readonly = false, $inputCSSClass = NULL,
						$labelText = "Company's status", $labelCSSClass = NULL, $inputDataListID="CompanyStatusEnum",
						$inputDataList=CompanyStatus::getConstants()
					);
					break;


				case QueryFieldNames::SponsCompanySponsoredOthers :
					return new SelectField(
						$options = [
							new OptionField("Select an option","Select an option",true,true),
							new OptionField(CompanySponsoredOthers::Yes, CompanySponsoredOthers::Yes),
							new OptionField(CompanySponsoredOthers::No, CompanySponsoredOthers::No)
						],
						$name = QueryFieldNames::SponsCompanySponsoredOthers, $selectCSSClass=NULL, $labelText="Sponsored Others?", $labelCSSClass=NULL
					);
					break;


				case QueryFieldNames::SponsCompanyAddress :
					return new InputField(
						$inputType = InputTypes::textarea, $name = QueryFieldNames::SponsCompanyAddress, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Company Address",
						$labelCSSClass = NULL, $inputDataListID="CompanyAddressInDB",
						$inputDataList= ($_SESSION[SessionEnums::UserAccessLevel] == UserTypes::CSO ? select_single_column_from_table("CMPAddress", "Company") : select_single_column_from_table("CMPAddress", "Company", "Sector = \"".$_SESSION[SessionEnums::UserSector]."\""))
					);
					break;


				case QueryFieldNames::SponsCompanyExec :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExec, $value = "", $readonly = false, $inputCSSClass = NULL,
						$labelText = "Company Exec. Name", $labelCSSClass = NULL, $inputDataListID="CompanyExecInDB",
						$inputDataList= ($_SESSION[SessionEnums::UserAccessLevel] == UserTypes::CSO ? select_single_column_from_table("CEName", "CompanyExec") : select_single_column_from_table("CEName", "Company INNER JOIN CompanyExec ON (Company.CMPName = CompanyExec.CMPName)", "Sector = \"".$_SESSION[SessionEnums::UserSector]."\""))
					);
					break;


				case QueryFieldNames::SponsCompanyExecMobile :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExecMobile, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Mobile",
						$labelCSSClass = NULL, $inputDataListID="CompanyExecMobileInDB",
						$inputDataList=($_SESSION[SessionEnums::UserAccessLevel] == UserTypes::CSO ? select_single_column_from_table("CEMobile", "CompanyExec") : select_single_column_from_table("CEMobile", "Company INNER JOIN CompanyExec ON (Company.CMPName = CompanyExec.CMPName)", "Sector = \"".$_SESSION[SessionEnums::UserSector]."\""))
					);
					break;


				case QueryFieldNames::SponsCompanyExecEmail :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExecEmail, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Email",
						$labelCSSClass = NULL, $inputDataListID="CompanyExecEmailInDB",
						$inputDataList=($_SESSION[SessionEnums::UserAccessLevel] == UserTypes::CSO ? select_single_column_from_table("CEEmail", "CompanyExec") : select_single_column_from_table("CEEmail", "Company INNER JOIN CompanyExec ON (Company.CMPName = CompanyExec.CMPName)", "Sector = \"".$_SESSION[SessionEnums::UserSector]."\""))
					);
					break;


				case QueryFieldNames::SponsCompanyExecPosition :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExecPosition, $value = "", $readonly = false, $inputCSSClass = NULL,
						$labelText = "Exec. Position", $labelCSSClass = NULL, $inputDataListID="CompanyExecPositionInDB",
						$inputDataList= select_single_column_from_table("CEPosition", "CompanyExec")
					);
					break;


				case QueryFieldNames::SponsMeetingAddress :
					return new InputField(
						$inputType = InputTypes::textarea, $name = QueryFieldNames::SponsMeetingAddress, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Meeting Address",
						$labelCSSClass = NULL, $inputDataListID="CompanyExecPositionInDB",
						$inputDataList = ["VJTI"]
					);
					break;


				case QueryFieldNames::SponsMeetingType :
					return new SelectField(
						$options = [
							new OptionField("Select a Meeting type","Select a Meeting type",true,true),
							new OptionField(MeetingTypes::Call, MeetingTypes::Call),
							new OptionField(MeetingTypes::Email, MeetingTypes::Email),
							new OptionField(MeetingTypes::FaceToFace, MeetingTypes::FaceToFace)
						],
						$name = QueryFieldNames::SponsMeetingType, $selectCSSClass=NULL, $labelText="Meeting type", $labelCSSClass=NULL
					);
					break;


				case QueryFieldNames::SponsMeetingOutcome :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsMeetingOutcome, $value = "(Update after meeting)", $readonly = false, $inputCSSClass = NULL, $labelText = "Meeting Outcome",
						$labelCSSClass = NULL, $inputDataListID="MeetingOutcomeEnum", $inputDataList= MeetingOutcomes::getConstants()
					);
					break;


				case QueryFieldNames::SponsMeetingEntryID :
					return new InputField(
						$inputType = InputTypes::number, $name = QueryFieldNames::SponsMeetingEntryID, $value ="",
						$readonly = false, $inputCSSClass = NULL,
						$labelText = "Meeting ID", $labelCSSClass = NULL, $inputDataListID = NULL, $inputDataList = NULL, $required = true
					);
					break;


				case QueryFieldNames::Submit :
					return new InputField(
						$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $readonly = false, $inputCSSClass = "query_forms"
					);
					break;

			}

			return new InputField(NULL, NULL);
		}


	}






	class QueryForm{ // this has all the settings and restrictions we require for the various users.
		var $userType = NULL;
		var $tableName = NULL;
		var $queryType = NULL;
		var $isValidForm = NULL;
		var $HTMLQueryForm = NULL;
		var $UnauthorizedMessage = '<div align="center"><h3 align="center" style="padding: 40px; font-size:28px; line-height:50px;" class="invalid_message">Sorry, you are not permitted to run this query.</h3> </div>';





		function QueryForm($userType, $tableName, $queryType, $UnauthorizedMessage = NULL){
			$this->isValidForm = true;
			if (!UserTypes::isValidValue($userType)){
				$this->isValidForm = false;
			}
			else $this->userType = $userType;

			if (!SQLTables::isValidValue($tableName)){
				$this->isValidForm = false;
			}
			else $this->tableName = $tableName;

			if (!QueryTypes::isValidValue($queryType)){
				$this->isValidForm = false;
			}
			else {
				if($queryType == QueryTypes::View){
					header("Location: view_table.php");
				}
				else $this->queryType = $queryType;
			}

			if($UnauthorizedMessage != NULL)
				$this->UnauthorizedMessage = $UnauthorizedMessage;

		}



		function parseQuery(){
			if ($this->isValidForm){ //all values must be valid
				if (Authorization::checkValidAuthorization($this->userType, $this->tableName, $this->queryType)){
					//user is authorized to run this query
					switch ($this->userType){
						case UserTypes::CSO:
							$this->HTMLQueryForm = $this->parseCSOQuery();
							break;
						case UserTypes::SectorHead:
							$this->HTMLQueryForm = $this->parseSectorHeadQuery();
							break;
						case UserTypes::SponsRep:
							$this->HTMLQueryForm = $this->parseSponsRepQuery();
							break;
					}
					if($this->HTMLQueryForm){
						$this->rearrangeFields(QueryFieldNames::$TableToFieldNameOrdering[$this->tableName]);
						$this->setRequiredFields(QueryFieldNames::$requiredFields, $this->tableName, $this->queryType);
						$this->HTMLQueryForm->formAction = "query_execute.php";
					}
					else $this->isValidForm = false;

				} else {
					$this->isValidForm = false;
					echo $this->UnauthorizedMessage;
				}
			}
		}

		function parseCSOQuery(){
			switch($this->tableName){
				case SQLTables::Event :
					return $this->parseCSOEventQuery();
					break;
				case SQLTables::SponsLogin :
//					return $this->parseCSOSponsLoginQuery();
					break;
				case SQLTables::SponsRep :
					return $this->parseCSOSponsRepQuery();
					break;
				case SQLTables::SectorHead :
					return $this->parseCSOSectorHeadQuery();
					break;
				case SQLTables::AccountLog :
					return $this->parseCSOAccountLogQuery();
					break;
				case SQLTables::Company :
					return $this->parseCSOCompanyQuery();
					break;
				case SQLTables::CompanyExec :
					return $this->parseCSOCompanyExecQuery();
					break;
				case SQLTables::Meeting :
					return $this->parseCSOMeetingQuery();
					break;
			}
			return NULL;
		}


		function parseCSOEventQuery(){
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


		function parseCSOSponsRepQuery(){
			/*For reference:
				SQLTables::SponsRep => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			*/
			$SponsRepRole = new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsRole, $value = UserTypes::SponsRep, $readonly = true, $inputCSSClass = NULL,
								$labelText = "Role", $labelCSSClass = NULL
							);
			switch($this->queryType){
				case QueryTypes::Insert :
					return new HTMLForm(
						$formName = $this->tableName.$this->queryType, $formAction = "view_table.php", $formMethod = FormMethod::POST,
						$fields = array(
							PredefinedQueryInputFields::get(QueryFieldNames::SponsFestival),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsSector),
							$SponsRepRole,
							PredefinedQueryInputFields::get(QueryFieldNames::SponsOthersID),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsName),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsPassword),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsRePassword),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsEmail),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsMobile),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsYear),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsBranch),
							PredefinedQueryInputFields::get(QueryFieldNames::Submit)

							/*
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value = $_SESSION[SessionEnums::UserFestival],
								$readonly = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsSector, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Sector", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsRole, $value = UserTypes::SponsRep, $readonly = true, $inputCSSClass = NULL,
								$labelText = "Role", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsID, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Reg. ID of SponsRep", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsName, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Name",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::password, $name = QueryFieldNames::SponsPassword, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Password",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::password, $name = QueryFieldNames::SponsRePassword, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Re-enter Password",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsEmail, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Email",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsMobile, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Mobile",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsYear, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Year",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsBranch, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Branch",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $readonly = false, $inputCSSClass = "query_forms"
							)
							*/
						),
						$formCSSClass=NULL,
						$title = "Insert a ".UserTypes::SponsRep,
						$fieldSeparator = "<br>"
					);
					break;
				case QueryTypes::Modify :
					return new HTMLForm(
						$formName = $this->tableName.$this->queryType, $formAction = "view_table.php", $formMethod = FormMethod::POST,
						$fields = array(
							PredefinedQueryInputFields::get(QueryFieldNames::SponsFestival),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsSector),
							$SponsRepRole,
							PredefinedQueryInputFields::get(QueryFieldNames::SponsOthersID),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsName),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsPassword),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsRePassword),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsEmail),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsMobile),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsYear),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsBranch),
							PredefinedQueryInputFields::get(QueryFieldNames::Submit)
							/*
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value = $_SESSION[SessionEnums::UserFestival], $readonly = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsSector, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Sector", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsRole, $value = UserTypes::SponsRep, $readonly = true, $inputCSSClass = NULL,
								$labelText = "Role", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsID, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Reg. ID", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsName, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Name",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsEmail, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Email",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsMobile, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Mobile",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsYear, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Year",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsBranch, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Branch",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $readonly = false, $inputCSSClass = "query_forms"
							)
							*/
						),
						$formCSSClass=NULL,
						$title = "Modify details of a ".UserTypes::SponsRep,
						$fieldSeparator = "<br>"
					);
					break;
				case QueryTypes::Delete :
					return new HTMLForm(
						$formName = $this->tableName.$this->queryType, $formAction = "view_table.php", $formMethod = FormMethod::POST,
						$fields = array(
							PredefinedQueryInputFields::get(QueryFieldNames::SponsFestival),
							$SponsRepRole,
							PredefinedQueryInputFields::get(QueryFieldNames::SponsOthersID),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsName),
							PredefinedQueryInputFields::get(QueryFieldNames::Submit)
							/*
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value = $_SESSION[SessionEnums::UserFestival], $readonly = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsRole, $value = UserTypes::SponsRep, $readonly = true, $inputCSSClass = NULL,
								$labelText = "Role", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsID, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Reg. ID", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsName, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Name",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $readonly = false, $inputCSSClass = "query_forms"
							)
							*/
						),
						$formCSSClass=NULL,
						$title = "Completely Remove ".UserTypes::SponsRep,
						$fieldSeparator = "<br>"
					);
					break;
			}
			return new HTMLForm(NULL, NULL, NULL, NULL);
		}


		function parseCSOSectorHeadQuery(){
			/*For reference:
				SQLTables::SectorHead => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			*/
			$CSOSectorHeadHTMLForm = $this->parseCSOSponsRepQuery();
			$CSOSectorHeadHTMLForm->fields[QueryFieldNames::SponsRole]->value = UserTypes::SectorHead;
			switch($this->queryType){
				case QueryTypes::Insert;
					$CSOSectorHeadHTMLForm->title = "Insert a new ".UserTypes::SectorHead;
					break;
				case QueryTypes::Modify;
					$CSOSectorHeadHTMLForm->title = "Modify details of a ".UserTypes::SectorHead;
					break;
				case QueryTypes::Delete;
					$CSOSectorHeadHTMLForm->title = "Completely remove a ".UserTypes::SectorHead;
					break;
			}

			return $CSOSectorHeadHTMLForm;
		}

		function parseCSOAccountLogQuery(){
			/*For reference:
				SQLTables::AccountLog => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			*/
			switch($this->queryType){
				case QueryTypes::Insert :
					return new HTMLForm(
						$formName = $this->tableName . $this->queryType, $formAction = "view_table.php", $formMethod = FormMethod::POST,
						$fields = array(
							PredefinedQueryInputFields::get(QueryFieldNames::SponsFestival),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsTransType),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsID),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompany),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsDate),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsAmount),
							PredefinedQueryInputFields::get(QueryFieldNames::Submit)
							/*
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value = $_SESSION[SessionEnums::UserFestival],
								$readonly = true, $inputCSSClass = NULL, $labelText = "Festival", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsTransType, $value = TransType::Deposit, $readonly = true,
								$inputCSSClass = NULL, $labelText = "Transaction Type", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsID, $value = $_SESSION[SessionEnums::UserLoginID], $readonly = true, $inputCSSClass = NULL,
								$labelText = "Reg. ID", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::date, $name = QueryFieldNames::SponsDate, $value = date('Y-m-d', time()), $readonly = false, $inputCSSClass = NULL,
								$labelText = "Date of Transaction", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsAmount, $value = "", $readonly = false,
								$inputCSSClass = NULL, $labelText = "Amount (Rs.)", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $readonly = false, $inputCSSClass = "query_forms"
							)
							*/
						), $formCSSClass = NULL, $title = "Enter a new transaction into the Festival Account", $fieldSeparator = "<br>"
					);
					break;


				case QueryTypes::Modify :
					return new HTMLForm(
						$formName = $this->tableName . $this->queryType, $formAction = "view_table.php", $formMethod = FormMethod::POST,
						$fields = array(
							PredefinedQueryInputFields::get(QueryFieldNames::SponsFestival),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsAccountLogEntryID),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsID),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompany),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsDate),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsAmount),
							PredefinedQueryInputFields::get(QueryFieldNames::Submit)

							/*
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value = $_SESSION[SessionEnums::UserFestival],
								$readonly = true, $inputCSSClass = NULL, $labelText = "Festival", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsAccountLogEntryID, $value = "", $readonly = false,
								$inputCSSClass = NULL, $labelText = "ID of transaction", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsID, $value = $_SESSION[SessionEnums::UserLoginID], $readonly = true, $inputCSSClass = NULL,
								$labelText = "Reg. ID", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::date, $name = QueryFieldNames::SponsDate, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Date of Transaction", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsAmount, $value = "", $readonly = false,
								$inputCSSClass = NULL, $labelText = "Amount (Rs.)", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $readonly = false, $inputCSSClass = "query_forms"
							)
							*/
						), $formCSSClass = NULL, $title = "Enter the values of the modified transaction", $fieldSeparator = "<br>"
					);
					break;
				case QueryTypes::Delete :
					return new HTMLForm(
						$formName = $this->tableName . $this->queryType, $formAction = "view_table.php", $formMethod = FormMethod::POST,
						$fields = array(
							PredefinedQueryInputFields::get(QueryFieldNames::SponsFestival),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsAccountLogEntryID),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompany),
							PredefinedQueryInputFields::get(QueryFieldNames::Submit)
							/*
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value = $_SESSION[SessionEnums::UserFestival],
								$readonly = true, $inputCSSClass = NULL, $labelText = "Festival", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsAccountLogEntryID, $value = "", $readonly = false,
								$inputCSSClass = NULL, $labelText = "ID of transaction", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $readonly = false, $inputCSSClass = "query_forms"
							)
							*/
						), $formCSSClass = NULL, $title = "Delete an account transaction", $fieldSeparator = "<br>"
					);
					break;
			}
			return new HTMLForm(NULL, NULL, NULL, NULL);
		}


		function parseCSOCompanyQuery(){
			/*For reference:
				SQLTables::Company => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			*/
			switch($this->queryType){
				case QueryTypes::Insert :
					return new HTMLForm(
						$formName = $this->tableName.$this->queryType, $formAction = "view_table.php", $formMethod = FormMethod::POST,
						$fields = array(
							PredefinedQueryInputFields::get(QueryFieldNames::SponsFestival),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompany),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsSector),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompanyStatus),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompanyAddress),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompanySponsoredOthers),
							PredefinedQueryInputFields::get(QueryFieldNames::Submit)
							/*
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value = $_SESSION[SessionEnums::UserFestival],
								$readonly = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsSector, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Sector", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyStatus, $value = CompanyStatus::NotCalled, $readonly = true, $inputCSSClass = NULL,
								$labelText = "Status", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::textarea, $name = QueryFieldNames::SponsCompanyAddress, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Company Address",
								$labelCSSClass = NULL
							),
							new SelectField(
								$options = [
									new OptionField(CompanySponsoredOthers::Yes, CompanySponsoredOthers::Yes),
									new OptionField(CompanySponsoredOthers::No, CompanySponsoredOthers::No)
								],
								$name = QueryFieldNames::SponsCompanySponsoredOthers, $selectCSSClass=NULL, $labelText="Sponsored Others", $labelCSSClass=NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $readonly = false, $inputCSSClass = "query_forms"
							)
							*/
						),
						$formCSSClass=NULL,
						$title = "Insert a new Company",
						$fieldSeparator = "<br>"

					);
					break;

				case QueryTypes::Modify:
					return new HTMLForm(
						$formName = $this->tableName.$this->queryType, $formAction = "view_table.php", $formMethod = FormMethod::POST,
						$fields = array(
							PredefinedQueryInputFields::get(QueryFieldNames::SponsFestival),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompany),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsSector),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompanyStatus),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompanyAddress),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompanySponsoredOthers),
							PredefinedQueryInputFields::get(QueryFieldNames::Submit)
							/*
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value =$_SESSION[SessionEnums::UserFestival],
								$readonly = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsSector, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Sector", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyStatus, $value = CompanyStatus::NotCalled, $readonly = true, $inputCSSClass = NULL,
								$labelText = "Status", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::textarea, $name = QueryFieldNames::SponsCompanyAddress, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Company Address",
								$labelCSSClass = NULL
							),
							new SelectField(
								$options = [
									new OptionField(CompanySponsoredOthers::Yes, CompanySponsoredOthers::Yes),
									new OptionField(CompanySponsoredOthers::No, CompanySponsoredOthers::No)
								],
								$name = QueryFieldNames::SponsCompanySponsoredOthers, $selectCSSClass=NULL, $labelText="Sponsored Others", $labelCSSClass=NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $readonly = false, $inputCSSClass = "query_forms"
							)*/
						),
						$formCSSClass=NULL,
						$title = "Update Company details",
						$fieldSeparator = "<br>"

					);
					break;

				case QueryTypes::Delete:
					return new HTMLForm(
						$formName = $this->tableName.$this->queryType, $formAction = "view_table.php", $formMethod = FormMethod::POST,
						$fields = array(
							PredefinedQueryInputFields::get(QueryFieldNames::SponsFestival),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompany),
							PredefinedQueryInputFields::get(QueryFieldNames::Submit)
							/*
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value =$_SESSION[SessionEnums::UserFestival],
								$readonly = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $readonly = false, $inputCSSClass = "query_forms"
							)
							*/
						),
						$formCSSClass=NULL,
						$title = "Remove details of a Company",
						$fieldSeparator = "<br>"
					);
					break;
			}
			return NULL;
		}


		function parseCSOCompanyExecQuery(){
			/*For reference:
				SQLTables::CompanyExec => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			*/
			switch($this->queryType){
				case QueryTypes::Insert :
					return new HTMLForm(
						$formName = $this->tableName.$this->queryType, $formAction = "view_table.php", $formMethod = FormMethod::POST,
						$fields = array(
							PredefinedQueryInputFields::get(QueryFieldNames::SponsFestival),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompany),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompanyExec),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompanyExecEmail),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompanyExecMobile),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompanyExecPosition),
							PredefinedQueryInputFields::get(QueryFieldNames::Submit)
							/*
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value = $_SESSION[SessionEnums::UserFestival],
								$readonly = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExec, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Company Exec. Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsEmail, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Exec. Email", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsMobile, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Exec. Mobile", $labelCSSClass = NULL
							),

							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExecPosition, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Exec. Position", $labelCSSClass = NULL
							),

							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $readonly = false, $inputCSSClass = "query_forms"
							)
							*/
						),
						$formCSSClass=NULL,
						$title = "Insert a new Company Executive",
						$fieldSeparator = "<br>"
					);

				case QueryTypes::Modify :
					return new HTMLForm(
						$formName = $this->tableName.$this->queryType, $formAction = "view_table.php", $formMethod = FormMethod::POST,
						$fields = array(
							PredefinedQueryInputFields::get(QueryFieldNames::SponsFestival),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompany),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompanyExec),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompanyExecEmail),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompanyExecMobile),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompanyExecPosition),
							PredefinedQueryInputFields::get(QueryFieldNames::Submit)
							/*
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value = $_SESSION[SessionEnums::UserFestival],
								$readonly = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExec, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Company Exec. Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsEmail, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Exec. Email", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsMobile, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Exec. Mobile", $labelCSSClass = NULL
							),

							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExecPosition, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Exec. Position", $labelCSSClass = NULL
							),

							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $readonly = false, $inputCSSClass = "query_forms"
							)
							*/
						),
						$formCSSClass=NULL,
						$title = "Edit details of Company Executive",
						$fieldSeparator = "<br>"
					);
					break;

				case QueryTypes::Delete:
					return new HTMLForm(
						$formName = $this->tableName.$this->queryType, $formAction = "view_table.php", $formMethod = FormMethod::POST,
						$fields = array(
							PredefinedQueryInputFields::get(QueryFieldNames::SponsFestival),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompany),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompanyExec),
							PredefinedQueryInputFields::get(QueryFieldNames::Submit)
							/*
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value = $_SESSION[SessionEnums::UserFestival],
								$readonly = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExec, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Company Exec. Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $readonly = false, $inputCSSClass = "query_forms"
							)
							*/
						),
						$formCSSClass=NULL,
						$title = "Remove a Company Executive",
						$fieldSeparator = "<br>"
					);

			}


			return NULL;
		}

		function parseCSOMeetingQuery(){
			/*For reference:
				SQLTables::Meeting => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View]
			*/
			switch($this->queryType){
				case QueryTypes::Insert :
					return new HTMLForm(
						$formName = $this->tableName.$this->queryType, $formAction = "view_table.php", $formMethod = FormMethod::POST,
						$fields = array(
							PredefinedQueryInputFields::get(QueryFieldNames::SponsFestival),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsID),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompany),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompanyExec),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsMeetingType),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsDate),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsTime),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsMeetingAddress),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsMeetingOutcome),
							PredefinedQueryInputFields::get(QueryFieldNames::Submit)
							/*
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value =$_SESSION[SessionEnums::UserFestival],
								$readonly = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsID, $value = $_SESSION[SessionEnums::UserLoginID], $readonly = true, $inputCSSClass = NULL,
								$labelText = "Reg. ID", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExec, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Company Exec. Name", $labelCSSClass = NULL
							),
							new SelectField(
								$options = [
									new OptionField(MeetingTypes::Call, MeetingTypes::Call),
									new OptionField(MeetingTypes::Email, MeetingTypes::Email),
									new OptionField(MeetingTypes::FaceToFace, MeetingTypes::FaceToFace)
								],
								$name = QueryFieldNames::SponsMeetingType, $selectCSSClass=NULL, $labelText="Meeting type", $labelCSSClass=NULL
							),
							new InputField(
								$inputType = InputTypes::textarea, $name = QueryFieldNames::SponsMeetingAddress, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Meeting Address",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsMeetingOutcome, $value = "(Update after meeting)", $readonly = true, $inputCSSClass = NULL, $labelText = "Meeting Outcome",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $readonly = false, $inputCSSClass = "query_forms"
							)
							*/
						),
						$formCSSClass=NULL,
						$title = "Enter details of a meeting",
						$fieldSeparator = "<br>"
					);
					break;

				case QueryTypes::Modify :
					return new HTMLForm(
						$formName = $this->tableName.$this->queryType, $formAction = "view_table.php", $formMethod = FormMethod::POST,
						$fields = array(
							PredefinedQueryInputFields::get(QueryFieldNames::SponsFestival),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsID),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsMeetingEntryID),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompany),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompanyExec),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsMeetingType),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsDate),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsTime),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsMeetingAddress),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsMeetingOutcome),
							PredefinedQueryInputFields::get(QueryFieldNames::Submit)
							/*
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value =$_SESSION[SessionEnums::UserFestival],
								$readonly = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsID, $value = $_SESSION[SessionEnums::UserLoginID], $readonly = true, $inputCSSClass = NULL,
								$labelText = "Reg. ID", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsMeetingEntryID, $value ="",
								$readonly = false, $inputCSSClass = NULL,
								$labelText = "Meeting ID", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExec, $value = "", $readonly = false, $inputCSSClass = NULL,
								$labelText = "Company Exec. Name", $labelCSSClass = NULL
							),
							new SelectField(
								$options = [
									new OptionField(MeetingTypes::Call, MeetingTypes::Call),
									new OptionField(MeetingTypes::Email, MeetingTypes::Email),
									new OptionField(MeetingTypes::FaceToFace, MeetingTypes::FaceToFace)
								],
								$name = QueryFieldNames::SponsMeetingType, $selectCSSClass=NULL, $labelText="Meeting type", $labelCSSClass=NULL
							),
							new InputField(
								$inputType = InputTypes::textarea, $name = QueryFieldNames::SponsMeetingAddress, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Meeting Address",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsMeetingOutcome, $value = "(Update after meeting)", $readonly = true, $inputCSSClass = NULL, $labelText = "Meeting Outcome",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $readonly = false, $inputCSSClass = "query_forms"
							)
							*/
						),
						$formCSSClass=NULL,
						$title = "Update details of a meeting",
						$fieldSeparator = "<br>"
					);
					break;
				case QueryTypes::Delete :
					return new HTMLForm(
						$formName = $this->tableName.$this->queryType, $formAction = "view_table.php", $formMethod = FormMethod::POST,
						$fields = array(
							PredefinedQueryInputFields::get(QueryFieldNames::SponsFestival),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsID),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsMeetingEntryID),
							PredefinedQueryInputFields::get(QueryFieldNames::Submit)
							/*
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value =$_SESSION[SessionEnums::UserFestival],
								$readonly = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsID, $value = $_SESSION[SessionEnums::UserLoginID], $readonly = true, $inputCSSClass = NULL,
								$labelText = "Reg. ID", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsMeetingEntryID, $value ="",
								$readonly = false, $inputCSSClass = NULL,
								$labelText = "Meeting ID", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $readonly = false, $inputCSSClass = "query_forms"
							)
							*/
						),
						$formCSSClass=NULL,
						$title = "Delete a meeting",
						$fieldSeparator = "<br>"
					);
					break;
			}
			return NULL;
		}





		function parseSectorHeadQuery(){
			switch($this->tableName){
				case SQLTables::Event :
					return $this->parseSectorHeadEventQuery();
					break;
				case SQLTables::SponsLogin :
					return $this->parseSectorHeadSponsLoginQuery();
					break;
				case SQLTables::SponsRep :
					return $this->parseSectorHeadSponsRepQuery();
					break;
				case SQLTables::SectorHead :
					return $this->parseSectorHeadSectorHeadQuery();
					break;
				case SQLTables::AccountLog :
					return $this->parseSectorHeadAccountLogQuery();
					break;
				case SQLTables::Company :
					return $this->parseSectorHeadCompanyQuery();
					break;
				case SQLTables::CompanyExec :
					return $this->parseSectorHeadCompanyExecQuery();
					break;
				case SQLTables::Meeting :
					return $this->parseSectorHeadMeetingQuery();
					break;
			}
			return NULL;
		}


		function parseSectorHeadEventQuery(){
			/*For reference:
				SQLTables::Event => [],	//empty means no queries allowed
			*/
			return NULL;
		}

		function parseSectorHeadSponsLoginQuery(){
			/*For reference:
				SQLTables::SponsLogin => [QueryTypes::Modify, QueryTypes::View],	//Can only view and modify own password
			*/

			return NULL;
		}
		function parseSectorHeadSponsRepQuery(){
			/*For reference:
				SQLTables::SponsRep => [QueryTypes::Delete],	//Can remove SponsReps from their sector.
			*/
			$SectorHeadSponsRepForm = $this->parseCSOSponsRepQuery();
			$SectorHeadSponsRepForm->addField(
					PredefinedQueryInputFields::get(QueryFieldNames::SponsSector)
					/*
					new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsSector, $value = $_SESSION[SessionEnums::UserSector], $readonly = true, $inputCSSClass = NULL,
						$labelText = "Sector", $labelCSSClass = NULL
					)
					*/
			);

			return $SectorHeadSponsRepForm;
		}

		function parseSectorHeadSectorHeadQuery(){
			/*For reference:
				SQLTables::SectorHead => [],
			*/
			return NULL;
		}

		function parseSectorHeadAccountLogQuery(){
			/*For reference:
				SQLTables::AccountLog => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//Can only insert, modify, delete, and view for own sector
			*/
			$SectorHeadAccountLogForm = $this->parseCSOAccountLogQuery();
			$SectorHeadAccountLogForm->addField(
					PredefinedQueryInputFields::get(QueryFieldNames::SponsSector)
					/*
					new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsSector, $value = $_SESSION[SessionEnums::UserSector], $readonly = true, $inputCSSClass = NULL,
						$labelText = "Sector", $labelCSSClass = NULL
					)
					*/
			);

			//These are all the possible fields
			return $SectorHeadAccountLogForm;
		}
		function parseSectorHeadCompanyQuery(){
			/*For reference:
				SQLTables::Company => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			*/
			$SectorHeadCompanyQuery = $this->parseCSOCompanyQuery();
			$SectorHeadCompanyQuery->addField(PredefinedQueryInputFields::get(QueryFieldNames::SponsSector));
			return $SectorHeadCompanyQuery;
		}
		function parseSectorHeadCompanyExecQuery(){
			/*For reference:
				SQLTables::CompanyExec => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			*/
			$SectorHeadCompanyExecQuery = $this->parseCSOCompanyExecQuery();
			$SectorHeadCompanyExecQuery->addField(PredefinedQueryInputFields::get(QueryFieldNames::SponsSector));
			return $SectorHeadCompanyExecQuery;
		}
		function parseSectorHeadMeetingQuery(){
			/*For reference:
				SQLTables::Meeting => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::View] //Can only view for own sector, and only modify own.
			*/
			$SectorHeadMeetingQuery = $this->parseCSOMeetingQuery();
			$SectorHeadMeetingQuery->addField(PredefinedQueryInputFields::get(QueryFieldNames::SponsSector));
			return $SectorHeadMeetingQuery;
		}






		function parseSponsRepQuery(){
			switch($this->tableName){
				case SQLTables::Event :
					return $this->parseSponsRepEventQuery();
					break;
				case SQLTables::SponsLogin :
					return $this->parseSponsRepSponsLoginQuery();
					break;
				case SQLTables::SponsRep :
					return $this->parseSponsRepSponsRepQuery();
					break;
				case SQLTables::SectorHead :
					return $this->parseSponsRepSectorHeadQuery();
					break;
				case SQLTables::AccountLog :
					return $this->parseSponsRepAccountLogQuery();
					break;
				case SQLTables::Company :
					return $this->parseSponsRepCompanyQuery();
					break;
				case SQLTables::CompanyExec :
					return $this->parseSponsRepCompanyExecQuery();
					break;
				case SQLTables::Meeting :
					return $this->parseSponsRepMeetingQuery();
					break;
			}
			return NULL;
		}



		function parseSponsRepEventQuery(){
			/*For reference:
				SQLTables::Event => [QueryTypes::View],		//Can only view own details.
			*/
			return NULL;
		}

		function parseSponsRepSponsLoginQuery(){
			/*For reference:
				SQLTables::SponsLogin => [QueryTypes::Modify, QueryTypes::View],	//Can only view and modify own password
			*/
			return NULL;
		}

		function parseSponsRepSponsRepQuery(){
			/*For reference:
				SQLTables::SponsRep => [QueryTypes::View],
			*/
			return NULL;
		}

		function parseSponsRepSectorHeadQuery(){
			/*For reference:
				SQLTables::SectorHead => [],
			*/
			return NULL;
		}

		function parseSponsRepAccountLogQuery(){
			/*For reference:
				SQLTables::AccountLog => [QueryTypes::View],	//can only view own sponsorships
			*/
			return NULL;
		}

		function parseSponsRepCompanyQuery(){
			/*For reference:
				SQLTables::Company => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			*/
			return $this->parseSectorHeadCompanyQuery();
		}

		function parseSponsRepCompanyExecQuery(){
			/*For reference:
				SQLTables::CompanyExec => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			*/
			return $this->parseSectorHeadCompanyExecQuery();
		}

		function parseSponsRepMeetingQuery(){
			/*For reference:
				SQLTables::Meeting => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::View] //Can only view for own sector, and only modify own.
			*/
			return $this->parseSectorHeadMeetingQuery();
		}


		function setRequiredFields($requiredFieldsLookup, $tableName, $queryType){
			if($this->HTMLQueryForm != NULL){
				$this->HTMLQueryForm->setRequiredFields($requiredFieldsLookup, $tableName, $queryType);
			}
		}


		function rearrangeFields($orderedFieldNames){
			if($this->HTMLQueryForm != NULL){
				$this->HTMLQueryForm->rearrangeFields($orderedFieldNames);
			}
		}

		function extractFromGET(){
			if($this->isValidForm){
				foreach($this->HTMLQueryForm->fields as $fieldName => $inputField){
					if($fieldName == QueryFieldNames::Submit || $fieldName == QueryFieldNames::SponsID || $fieldName == QueryFieldNames::SponsSector || $fieldName == QueryFieldNames::SponsRole)
						continue;
					$valueFromGET = extractValueFromGET($fieldName);
					if ($valueFromGET != NULL){
						$inputField->value = $valueFromGET;
					}
				}
			}
		}


		function generateForm(){
			$out = "";
			if ($this->isValidForm){
				$this->extractFromGET();
				$out.=$this->HTMLQueryForm;
			}
			else echo "The Query form is not valid";
			return $out;
		}

		function __toString(){
			return $this->generateForm();
		}
	}




	$r = new QueryForm($_SESSION[SessionEnums::UserAccessLevel], extractValueFromGET(QueryFormSessionEnums::TableName), extractValueFromGET(QueryFormSessionEnums::QueryType));
	//$userType, $tableName, $queryType
	$r->parseQuery();
	echo $r;
	echo "<br><br>";




	/*##------------------------------------------------TESTS------------------------------------------------##


	echo $_SESSION[SessionEnums::UserLoginID];





	$r = new QueryForm($_SESSION[SessionEnums::UserAccessLevel], SQLTables::SponsRep, QueryTypes::Insert);
	$r->parseQuery();
	echo $r;
	echo "<br><br>";

	$r = new QueryForm($_SESSION[SessionEnums::UserAccessLevel], SQLTables::SponsRep, QueryTypes::Modify);
	$r->parseQuery();
	echo $r;
	echo "<br><br>";

	$r = new QueryForm($_SESSION[SessionEnums::UserAccessLevel], SQLTables::SponsRep, QueryTypes::Delete);
	$r->parseQuery();
	echo $r;
	echo "<br><br>";



	$r = new QueryForm($_SESSION[SessionEnums::UserAccessLevel], SQLTables::SectorHead, QueryTypes::Insert);
	$r->parseQuery();
	echo $r;
	echo "<br><br>";

	$r = new QueryForm($_SESSION[SessionEnums::UserAccessLevel], SQLTables::SectorHead, QueryTypes::Modify);
	$r->parseQuery();
	echo $r;
	echo "<br><br>";

	$r = new QueryForm($_SESSION[SessionEnums::UserAccessLevel], SQLTables::SectorHead, QueryTypes::Delete);
	$r->parseQuery();
	echo $r;
	echo "<br><br>";



	$r = new QueryForm($_SESSION[SessionEnums::UserAccessLevel], SQLTables::AccountLog, QueryTypes::Insert);
	$r->parseQuery();
	echo $r;
	echo "<br><br>";

	$r = new QueryForm($_SESSION[SessionEnums::UserAccessLevel], SQLTables::AccountLog, QueryTypes::Modify);
	$r->parseQuery();
	echo $r;
	echo "<br><br>";

	$r = new QueryForm($_SESSION[SessionEnums::UserAccessLevel], SQLTables::AccountLog, QueryTypes::Delete);
	$r->parseQuery();
	echo $r;
	echo "<br><br>";



	$r = new QueryForm($_SESSION[SessionEnums::UserAccessLevel], SQLTables::Company, QueryTypes::Insert);
	$r->parseQuery();
	echo $r;
	echo "<br><br>";

	$r = new QueryForm($_SESSION[SessionEnums::UserAccessLevel], SQLTables::Company, QueryTypes::Modify);
	$r->parseQuery();
	echo $r;
	echo "<br><br>";

	$r = new QueryForm($_SESSION[SessionEnums::UserAccessLevel], SQLTables::Company, QueryTypes::Delete);
	$r->parseQuery();
	echo $r;
	echo "<br><br>";



	$r = new QueryForm($_SESSION[SessionEnums::UserAccessLevel], SQLTables::CompanyExec, QueryTypes::Insert);
	$r->parseQuery();
	echo $r;
	echo "<br><br>";

	$r = new QueryForm($_SESSION[SessionEnums::UserAccessLevel], SQLTables::CompanyExec, QueryTypes::Modify);
	$r->parseQuery();
	echo $r;
	echo "<br><br>";

	$r = new QueryForm($_SESSION[SessionEnums::UserAccessLevel], SQLTables::CompanyExec, QueryTypes::Delete);
	$r->parseQuery();
	echo $r;
	echo "<br><br>";



	$r = new QueryForm($_SESSION[SessionEnums::UserAccessLevel], SQLTables::Meeting, QueryTypes::Insert);
	$r->parseQuery();
	echo $r;
	echo "<br><br>";

	$r = new QueryForm($_SESSION[SessionEnums::UserAccessLevel], SQLTables::Meeting, QueryTypes::Modify);
	$r->parseQuery();
	echo $r;
	echo "<br><br>";

	$r = new QueryForm($_SESSION[SessionEnums::UserAccessLevel], SQLTables::Meeting, QueryTypes::Delete);
	$r->parseQuery();
	echo $r;
	echo "<br><br>";



	/*##---------------------------------------------END OF TESTS---------------------------------------------##*/



?>

<?php



/*
	$SponsRepBackButton = "<h2><a href='Sponsorship Representative.php' class='back_button'>Go back</a></h2><br>";
	$SectorHeadBackButton = "<h2><a href='Sector Head.php' class='back_button'>Go back</a></h2><br>";
	$CSOBackButton = "<h2><a href='CSO.php' class='back_button'>Go back</a></h2><br>";


	$SponsAccessLevel = get_access_level($SponsID);
	$SponsName = get_person_name($SponsID);
	$SponsSector = "";
	if ($SponsAccessLevel != UserTypes::CSO) {
		$SponsSector = get_person_sector($SponsID);
	}




	if (isset($_GET['submit'])) {
		$query_type = $_GET['query_type'];
		$table_name = $_GET['table_name'];
		//echo $query_type;
		//echo $table_name;

		$UnauthorizedMessage = '<div align="center"><h3 align="center" style="padding: 40px; font-size:28px; line-height:50px;" class="invalid_message">Sorry, you are not permitted to run this query.</h3> </div>';


		echo '<header align="center">
			<h1>Sponsorship Department</h1>';


		if ($SponsAccessLevel == "SectorHead") {
			echo $SectorHeadBackButton;
		}

		if ($SponsAccessLevel == "SponsRep") {
			echo $SponsRepBackButton;
		}

		if ($SponsAccessLevel == "CSO") {
			echo $CSOBackButton;
		}

		echo '</header>';


		$AccountLogInsert = "";
		$AccountLogUpdate = "";
		$AccountLogDelete = "";
		$SponsRepInsert = "";
		$SponsRepUpdate = "";
		$SponsRepDelete = "";
		$MeetingLogInsert = "";
		$MeetingLogUpdate = "";
		$MeetingLogDelete = "";
		$CompanyInsert = "";
		$CompanyUpdate = "";
		$CompanyDelete = "";
		$CompanyExecInsert = "";
		$CompanyExecUpdate = "";
		$CompanyExecDelete = "";


		if ($SponsAccessLevel != "CSO") {
			$AccountLogInsert = '
				<h2 align="center">Insert details of the sponsorship received:</h2>

			<div>
				<form action="view_table.php" method="post"  class="Insert">
					<label>Transaction Type:</label>          <input type="text" name="TransType" readonly value="Deposit">
					<br>
					<br>
					<label>Company Name:</label>          <input type="text" name="Title">
					<br>
					<br>
					<label>Sponsorship ID:</label>			  <input type="text" name="SponsID" readonly value="' . $SponsID . '"  >
					<br>
					<br>
					<label>Date:</label>     <input type="date" name="Date" >
					<br>
					<br>
					<label>Amount:</label><input type="text" name="Amount">
					<br>
					<br>
					<input class="query_forms" type="submit" name="submit" >Insert Account Entry Details</input>

				</form>
			</div>';


			$AccountLogUpdate = '
			<h2 align="center">Update Event Account:</h2>

			<div>
					<form action="view_table.php" method="post"  class="Update">
					<label>Transaction Type:</label>          <input type="text" name="TransType" readonly value="Deposit">
					<br>
					<br>
					<label>Company Name:</label> <input type="text" name="Title">
					<br>
					<br>
				    <label>Sponsorship ID:</label> <input type="text" name="SponsID" readonly value="' . $SponsID . '" >
					<br>
					<br>
					<!--<input type="checkbox" name="DateCheckbox">--> <label>Date:</label> <input type="date" name="Date">
					<br>
					<br>
					<!--<input type="checkbox" name="AmountCheckbox">--> <label>Amount:</label> <input type="text" name="Amount">
					<br>
				    <br>
				    <input class="query_forms" type="submit" name="submit">Update Account Entry Details</input>

				</form>
			</div>';


			$AccountLogDelete = '

				<h2 align="center">Delete entry from Event Account:</h2>

			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>Company Name:</label><input type="text" name="Title">
					<br>
					<br>
					<label>Sponsorship ID:</label> <input type="text" name="SponsID" >
					<br>
					<br>
					<input class="query_forms" type="submit" name="submit">Delete Account Entry</input>

				</form>
			</div>
			';


			$CompanyInsert = '<h2 align="center">Add a Company to the ' . $SponsSector . ' sector:</h2>

			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>Company Name:</label> <input type="text" name="CMPName">
					<br>
					<br>
					<label>Company Status:</label> <input type="text" name="CMPStatus" readonly value="Not called">
					<br>
					<br>
					<label>Sector:</label> <input type="text" name="Sector" readonly  value="' . $SponsSector . '">
					<br>
					<br>
					<label>Address:</label> <input type="text"  name="CMPAddress">
					<input class="query_forms" type="submit" name="submit">Insert Company Details</input>

				</form>
			</div>';


			$CompanyUpdate = '<h2 align="center">Update Company Details:</h2>

			<div>
					<form action="view_table.php" method="post"  class="Update">
					<label>Company Name:</label> <input type="text" name="CMPName">
					<br>
					<br>
					<label>Sector:</label> <input type="text" name="Sector"  readonly value="' . $SponsSector . '">
					<br>
					<br>
					<!--<input type="checkbox" name="CMPStatusCheckbox">--><label>Status:	</label>		  <input type="text" name="CMPStatus">
					<br>
					<br>
					<!--<input type="checkbox" name="CMPAdressCheckbox">--><label>Address:</label>         <input type="text" max-length="50" name="CMPAddress">

					<input class="query_forms" type="submit" name="submit">Update Company Details</input>

				</form>
			</div>';


			$CompanyDelete = '<h2 align="center">Remove a Company and all it\'s associated data from sector ' . $SponsSector . ':</h2>

			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>Company Name:</label> <input type="text" name="CMPName">
					<br>
					<br>
					<label>Sector:</label> <input type="text" name="Sector" readonly  value="' . $SponsSector . '">
					<input class="query_forms" type="submit" name="submit">Delete Company</input>


				</form>
			</div>';


			$CompanyExecInsert = '<h2 align="center">Add an Executive to a Company in the ' . $SponsSector . ' sector:</h2>

			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>Company Name:</label>
					<input type="text" name="CMPName">
					<br>
					<br>
					<label>Company Executive Name:</label>
					<input type="text" name="CEName">
					<br>
					<br>
					<label>Phone Number:</label>
					<input type="text" name="CEMobile">
					<br>
					<br>
					<label>Email ID:</label>
					<input type="text" name="CEEmail">
					<br>
					<br>
					<label>Company Position:</label>
					<input type="text" name="CEPosition">

					<input class="query_forms" type="submit" name="submit">Insert Company and Executive Details</input>

				</form>
			</div>';


			$CompanyExecUpdate = '<h2 align="center">Update Details of a Company Executive:</h2>

			<div>
					<form action="view_table.php" method="post"  class="Update">
					<label>Company Name:</label> <input type="text" name="CMPName">
					<br>
					<br>
					<label>Company Executive:</label><input type="text" name="CEName">
					<br>
					<br>
					<!--<input type="checkbox" name="CEMobileCheckbox">--><label>Phone Number:</label>     <input type="text" name="CEMobile">
					<br>
					<br>
					<!--<input type="checkbox" name="CEEmailCheckbox">--><label>Email ID:</label>         <input type="text" name="CEEmail">
					<br>
					<br>
					<!--<input type="checkbox" name="CEPositionCheckbox">--><label>Company Position:</label>     <input type="text" name="CEPosition">
					<br>
					<br>
					<input class="query_forms" type="submit" name="submit">Update Company and Executive Details</input>

				</form>
			</div>';

			$CompanyExecDelete = '<h2 align="center">Remove a Company Executive:</h2>

			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>Company Name:</label>          <input type="text" name="CMPName">
					<br>
					<br>
					<label>Company Executive Name:</label>    <input type="text" name="CEName">
					<br>
					<br>
					<input class="query_forms" type="submit" name="submit">Delete Company Executive</input>

				</form>
			</div>';


			$MeetingLogInsert = '<h2 align="center">Add a Meeting to the log of Sector ' . $SponsSector . ':</h2>

			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>Sponsorship ID:</label><input type="text" name="SponsID" readonly  value="' . $SponsID . '">
					<br>
					<br>
					<label>Meeting Type:</label>
					<select name="MeetingType">
						<option>Call</option>
						<option>Meet</option>
						<option>Email</option>
					</select>

					<br>
					<br>
					<label>Company Name:</label>           <input type="text" name="CMPName">
					<br>
					<br>
					<label>Executive Name:</label>			  <input type="text" name="CEName">
					<br>
					<br>
					<label>Date:</label>     <input type="date" name="Date">
					<br>
					<br>
					<label>Time:</label><input type="time" name="Time">
					<br>
					<br>
					<label>Address:</label>         <input type="text" name="Address">
					<br>
					<br>
					<label>Outcome:</label>         <input type="text" name="Output" readonly  value="(Update after meeting)" >
					<br>
					<br>
					<input class="query_forms" type="submit" name="submit">Insert Meeting Details</input>

				</form>
			</div>';


			$MeetingLogUpdate = '<h2 align="center">update the Outcome of a Meeting:</h2>

			<div>
					<form action="view_table.php" method="post"  class="Update">
					<label>Sponsorship ID:</label><input type="text" name="SponsID"  readonly value="' . $SponsID . '">
					<!--<br>
					<br>
					<label>Meeting Type:</label>
					<select name="MeetingType">
						<option>Call</option>
						<option>Meet</option>
						<option>Email</option>
					</select>-->
					<br>
					<br>
					<label>Company:</label><input type="text" name="CMPName">
					<br>
					<br>
					<label>Executive Name:</label><input type="text" name="CEName">
					<br>
					<br>
					<label>Date:</label><input type="date" name="Date">
					<br>
					<br>
					<label>Time:</label><input type="time" name="Time">
					<br>
					<br>
					<label>Outcome:</label><input type="text" name="Outcome">
					<br>
					<br>

					<input class="query_forms" type="submit" name="submit">Update Meeting Details</input>

				</form>
			</div>';


			$MeetingLogDelete = '<h2 align="center">Delete a meeting thhat was previously planned:</h2>


			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>Sponsorship ID:</label><input type="text" name="SponsID" readonly  value="' . $SponsID . '">
					<br>
					<br>
					<label>Company Name:</label><input type="text" name="CMPName">
					<br>
					<br>
					<label>Company Executive:</label><input type="text" name="CEName">
					<br>
					<br>
					<label>Date:</label><input type="date" name="Date">
					<br>
					<br>
					<label>Time:</label><input type="time" name="Time">
					<input class="query_forms" type="submit" name="submit">Delete Meeting</input>

				</form>
			</div>';


			$SponsRepInsert = '
			<h2 align="center">Insert a Sponsorship Representative into any sector:</h2>
			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>SponsID:</label>    <input type="text" name="SponsID">
					<br><br>
					<label>Sector:</label> <input type="text" name="Sector" readonly  value="' . $SponsSector . '">
					<br><br>
					<!--<label>Date Assigned:</label>			  <input type="date" name="DateAssigned">
					<br><br>-->
					<input class="query_forms" type="submit" name="submit">Insert SponRep Details</input>

				</form>
			</div>';


			$SponsRepUpdate = '<h2 align="center">Update the details of a SponsRep in sector ' . $SponsSector . ':</h2>

			<div>
					<form action="view_table.php" method="post"  class ="Update">
					<label>SponsID:</label><input type="text" name="SponsID">
					<br>
					<br>

					<label>Sector:</label><input type="text" name="Sector">
					<br>
					<br>

					<label>Organization:</label>          <input type="text" name="Organization" value="">
					<br>
					<br>

					<label>Event Name:</label>          <input type="text" name="EventName" value="">
					<br>
					<br>

					<label>Date Assigned:</label> <input type="date" name="DateAssigned">
					<br>
					<br>-->
					<input class="query_forms" type="submit" name="submit">Update SponsRep Details</input>

				</form>
			</div>';


			$SponsRepDelete = '
				<h2 align="center">Delete Sponsorship Representative</h2>

			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>Sponsorship ID:</label><input type="text" name="SponsID">
					<br>
					<br>
					<label>Sector:</label> <input type="text" name="Sector" readonly  value="' . $SponsSector . '">
					<br>
					<br>
					<input class="query_forms" type="submit" name="submit">Delete SponsRep</input>

				</form>
			</div>';


		}

		if ($SponsAccessLevel == "CSO") {


			$CSOAccountLogInsert = '<h2 align="center">Insert details of the sponorship recieved:</h2>

				<div>
					<form action="view_table.php" method="post"  class="Insert">
						<label>Transaction Type:</label>          <input type="text" name="TransType" readonly value="Deposit">
						<br>
						<br>
						<label>Company Name:</label>          <input type="text" name="Title">
						<br>
						<br>
						<label>Sponsorship ID:</label>			  <input type="text" name="SponsID">
						<br>
						<br>
						<label>Date:</label>     <input type="date" name="Date" value="'.date('Y-m-d', time()).'" >
						<br>
						<br>
						<label>Amount:</label><input type="text" name="Amount">
						<br>
						<br>
						<input class="query_forms" type="submit" name="submit" >Insert Account Entry Details</input>

					</form>
				</div>';


			$CSOAccountLogUpdate = '<h2 align="center">Update Event Account:</h2>

					<div>
							<form action="view_table.php" method="post"  class="Update">
							<label>Transaction Type:</label>          <input type="text" name="TransType" readonly value="Deposit">
							<br>
							<br>
							<label>Company Name:</label> <input type="text" name="Title">
							<br>
							<br>
							<label>Sponsorship ID:</label> <input type="text" name="SponsID">
							<br>
							<br>
							<!--<input type="checkbox" name="DateCheckbox">--> <label>Date:</label> <input type="date" name="Date">
							<br>
							<br>
							<!--<input type="checkbox" name="AmountCheckbox">--> <label>Amount:</label> <input type="text" name="Amount">
							<br>
							<br>
							<input class="query_forms" type="submit" name="submit">Update Account Entry Details</input>

						</form>
					</div>';


			$CSOAccountLogDelete = '
					<h2 align="center">Delete entry from Event Account:</h2>

					<div>
							<form action="view_table.php" method="post"  class="Insert">
							<label>Company Name:</label><input type="text" name="Title">
							<br>
							<br>
							<label>Sponsorship ID:</label> <input type="text" name="SponsID" >
							<br>
							<br>
							<input class="query_forms" type="submit" name="submit">Delete Account Entry</input>

						</form>
					</div>
					';


			$CSOCompanyInsert = '<h2 align="center">Add a Company to the any sector:</h2>

					<div>
							<form action="view_table.php" method="post"  class="Insert">
							<label>Company Name:</label> <input type="text" name="CMPName">
							<br>
							<br>
							<label>Company Status:</label> <input type="text" name="CMPStatus" readonly value="Not called">
							<br>
							<br>
							<label>Sector:</label> <input type="text" name="Sector">
							<br>
							<br>
									<label>Address:</label> <input type="text"  name="CMPAddress">
						<input class="query_forms" type="submit" name="submit">Insert Company Details</input>

					</form>
				</div>';


			$CSOCompanyUpdate = '<h2 align="center">Update Company Details:</h2>

				<div>
						<form action="view_table.php" method="post"  class="Update">
						<label>Company Name:</label> <input type="text" name="CMPName">
						<br>
						<br>
						<label>Sector:</label> <input type="text" name="Sector">
						<br>
						<br>
						<!--<input type="checkbox" name="CMPStatusCheckbox">--><label>Status:	</label>		  <input type="text" name="CMPStatus">
						<br>
						<br>
						<!--<input type="checkbox" name="CMPAdressCheckbox">--><label>Address:</label>         <input type="text" max-length="50" name="CMPAddress">

						<input class="query_forms" type="submit" name="submit">Update Company Details</input>

					</form>
				</div>';


			$CSOCompanyDelete = '<h2 align="center">Remove a Company and all it\'s associated data:</h2>

				<div>
						<form action="view_table.php" method="post"  class="Insert">
						<label>Company Name:</label> <input type="text" name="CMPName">
						<br>
						<br>
						<label>Sector:</label> <input type="text" name="Sector">
						<input class="query_forms" type="submit" name="submit">Delete Company</input>


					</form>
				</div>';


			$CSOMeetingLogInsert = '<h2 align="center">Add a Meeting to the log of any sector:</h2>

			<div>
				<form action="view_table.php" method="post"  class="Insert">
				<label>Sponsorship ID:</label><input type="text" name="SponsID">
				<br>
				<br>
				<label>Meeting Type:</label>
				<select name="MeetingType">
					<option>Call</option>
					<option>Meet</option>
					<option>Email</option>
				</select>

				<br>
				<br>
				<label>Company Name:</label>           <input type="text" name="CMPName">
				<br>
				<br>
				<label>Executive Name:</label>			  <input type="text" name="CEName">
				<br>
				<br>
				<label>Date:</label>     <input type="date" name="Date">
				<br>
				<br>
				<label>Time:</label><input type="time" name="Time">
				<br>
				<br>
				<label>Address:</label>         <input type="text" name="Address">
				<br>
				<br>
				<label>Outcome:</label>         <input type="text" name="Output" readonly  value="(Update after meeting)" >
				<br>
				<br>
				<input class="query_forms" type="submit" name="submit">Insert Meeting Details</input>

			</form>
			</div>';


			$CSOMeetingLogUpdate = '<h2 align="center">update the Outcome of a Meeting:</h2>

			<div><h2 align=center>Please check the boxes which you want to update and enter details</h2>
					<form action="view_table.php" method="post"  class="Update">
					<label>Sponsorship ID:</label><input type="text" name="SponsID">
					<!--<br>
					<br>
					<label>Meeting Type:</label>
					<select name="MeetingType">
						<option>Call</option>
						<option>Meet</option>
						<option>Email</option>
					</select>-->
					<br>
					<br>
					<label>Company:</label><input type="text" name="CMPName">
					<br>
					<br>
					<label>Executive Name:</label><input type="text" name="CEName">
					<br>
					<br>
					<label>Date:</label><input type="date" name="Date">
					<br>
					<br>
					<label>Time:</label><input type="time" name="Time">
					<br>
					<br>
					<label>Outcome:</label><input type="text" name="Outcome">
					<br>
					<br>

					<input class="query_forms" type="submit" name="submit">Update Meeting Details</input>

				</form>
			</div>';


			$CSOMeetingLogDelete = '<h2 align="center">Delete a meeting thhat was previously planned:</h2>

			<div>
					<form action="view_table.php" method="post"  class="Insert">
					<label>Sponsorship ID:</label><input type="text" name="SponsID">
					<br>
					<br>
					<label>Company Name:</label><input type="text" name="CMPName">
					<br>
					<br>
					<label>Company Executive:</label><input type="text" name="CEName">
					<br>
					<br>
					<label>Date:</label><input type="date" name="Date">
					<br>
					<br>
					<label>Time:</label><input type="time" name="Time">
					<input class="query_forms" type="submit" name="submit">Delete Meeting</input>

				</form>
			</div>';


			$CSOSectorHeadInsert = '<h2 align="center">Insert a Sector Head into any sector:</h2>
		<div>
				<form action="view_table.php" method="post"  class="Insert">
				<label>SponsID:</label>       <input type="text" name="SponsIDForm" value="">
				<br>
				<br>
				<label>Name:</label>          <input type="text" name="SponsName" value="">
				<br>
				<br>
				<label>Password:</label>          <input type="password" name="SponsPasswordForm" value="">
				<br>
				<br>
				<label>Organization:</label>          <input type="text" name="Organization" value="">
				<br>
				<br>

				<label>Event Name:</label>          <input type="text" name="EventName" value="">
				<br>
				<br>
				<label>Department:</label>          <input type="text" name="Dept" readonly="readonly" value="Sponsorship">
				<br>
				<br>
				<label>Role:</label>          <input type="text" name="Role" readonly="readonly" value="SectorHead">

				<br>
				<br>
				<label>Sector:</label>        <input type="text" name="SponsSectorForm" value="">
				<br>	<br>
				<label>Email:</label>         <input type="text" name="Email" max-length="50" value="">
				<br><br>
				<label>Mobile Number:</label> <input type="text" name="Mobile" value="">
				<br><br>
				<label>Year:</label> <input type="text" name="Year" value="">
				<br><br>
				<label>Branch:</label>        <input type="text" name="Branch" value="">
				<br>
				<input class="query_forms" type="submit" name="submit">Insert SectorHead Details</input>
			</form>
		</div>
			';
			$CSOSectorHeadUpdate = '<h2 align="center">Edit Sector Head details:</h2>

			<div>
					<form action="view_table.php" method="post"  class ="Update">
					<label>SponsID:</label><input type="text" name="SponsIDForm">
					<br>
					<br>

					<label>Sector:</label><input type="text" name="SponsSectorForm">
					<br>
					<br>

					<label>Organization:</label>          <input type="text" name="Organization" value="">
					<br>
					<br>

					<label>Event Name:</label>          <input type="text" name="EventName" value="">
					<br>
					<br>

					<label>Password:</label>          <input type="password" name="SponsPasswordForm" value="">
					<br>
					<br>


					<input class="query_forms" type="submit" name="submit">Update SectorHead Details</input>

				</form>
			</div>';
			$CSOSectorHeadDelete = '<h2 align="center">Delete a Sector Head from any sector:</h2>

				<div>
						<form action="view_table.php" method="post"  class="Delete">
						<label>SponsID:</label><input type="text" name="SponsIDForm">
						<br>
						<br>

						<input class="query_forms" type="submit" name="submit">Delete SectorHead</input>

					</form>
				</div>';

			$CSOSponsRepInsert = '<h2 align="center">Insert a Sponsorship Representative into any sector:</h2>

					<div>
				<form action="view_table.php" method="post"  class="Insert">
				<label>SponsID:</label>       <input type="text" name="SponsIDForm" value="">
				<br>
				<br>
				<label>Name:</label>          <input type="text" name="SponsName" value="">
				<br>
				<br>
				<label>Password:</label>          <input type="password" name="SponsPasswordForm" value="">
				<br>
				<br>
				<label>Organization:</label>          <input type="text" name="Organization" value="">
				<br>
				<br>
				<label>Event Name:</label>          <input type="text" name="EventName" value="">
				<br>
				<br>
				<label>Department:</label>          <input type="text" name="Dept" readonly="readonly" value="Sponsorship">
				<br>
				<br>

				<label>Role:</label>          <input type="text" name="Role" readonly="readonly" value="SponsRep">
				<br><br>
				<label>Sector:</label>        <input type="text" name="SponsSectorForm" value="">
				<br>
				<br>
				<label>Email:</label>         <input type="text" name="Email" max-length="50" value="">
				<br><br>
				<label>Mobile Number:</label><input type="text" name="Mobile" value="">
				<br><br>
				<label>Year:</label> <input type="text" name="Year" value="">
				<br><br>
				<label>Branch:</label>        <input type="text" name="Branch" value="">
				<br>
				<input class="query_forms" type="submit" name="submit">Insert SponRep Details</input>
			</form>
		</div>';


			$CSOSponsRepUpdate = '<h2 align="center">Edit Sponsorship Representative details:</h2>

			<div>
					<form action="view_table.php" method="post"  class ="Update">
					<label>SponsID:</label><input type="text" name="SponsIDForm">
					<br>
					<br>

					<label>Organization:</label>          <input type="text" name="Organization" value="">
					<br>
					<br>

					<label>Event Name:</label>          <input type="text" name="EventName" value="">
					<br>
					<br>

					<label>Sector:</label><input type="text" name="SponsSectorForm">
					<br>
					<br>

					<label>Password:</label>          <input type="password" name="SponsPasswordForm" value="">
					<br>
					<br>


					<input class="query_forms" type="submit" name="submit">Update SponsRep Details</input>

				</form>
			</div>';


			$CSOSponsRepDelete = '<h2 align="center">Delete a Sponsorship Representative from any sector:</h2>

				<div>
						<form action="view_table.php" method="post"  class="Delete">
						<label>SponsID:</label><input type="text" name="SponsIDForm">
						<br>
						<br>
						<input class="query_forms" type="submit" name="submit" style="width:150px;">Delete SponsRep</input>
						
					</form>
				</div>';

		}
		*/

		/*THE FOLLOWING CODE SELECTS WHICH TABLE SHOULD BE DISPLAYED,
		BASED ON THE INPUT AND WHO IS RUNNING THE QUERY.
		*/

		/*THIS TABLES ALSO TELLS US WHO HAS WHAT ACCESS RIGHTS (NEEDS TO E UPDATED IN SCHEMA).*/

		/*
		$_SESSION["SponsAccessLevel"] = $SponsAccessLevel;
		$_SESSION["query_type"] = $query_type;
		$_SESSION["table_name"] = $table_name;


		if ($SponsAccessLevel == "SponsRep") {


			if ($table_name == "Meeting Log") {
				if ($query_type == "View") {
					header("Location: view_table.php");
				}
				else {
					if ($query_type == "Insert") {
						echo $MeetingLogInsert;
					}
					else {
						if ($query_type == "Update") {
							echo $MeetingLogUpdate;
						}
						else {
							if ($query_type = "Delete") {
								echo $MeetingLogDelete;
							}
						}
					}
				}

			}
			else {
				if ($table_name == "Company") {
					if ($query_type == "View") {
						header("Location: view_table.php");
					}
					else {
						if ($query_type == "Insert") {
							echo $CompanyInsert;
						}
						else {
							if ($query_type == "Update") {
								echo $CompanyUpdate;
							}
							else {
								if ($query_type = "Delete") {
									echo $CompanyDelete;
								}
							}
						}
					}

				}
				else {
					if ($table_name == "Company Executive") {
						if ($query_type == "View") {
							header("Location: view_table.php");
						}
						else {
							if ($query_type == "Insert") {
								echo $CompanyExecInsert;
							}
							else {
								if ($query_type == "Update") {
									echo $CompanyExecUpdate;
								}
							}
						}
						if ($query_type == "Delete") {
							echo $CompanyExecDelete;
						}
					}
					else {
						if ($table_name == "Event Account") {
							if ($query_type == "View") {
								header("Location: view_table.php");
							}
							else {
								if ($query_type == "Insert") {
									echo $AccountLogInsert;
								}
								else exit ($UnauthorizedMessage);
							}
						}
					}
				}
			}

		}


		if ($SponsAccessLevel == "SectorHead") {


			if ($table_name == "Sponsorship Representative") {


				if ($query_type == "Insert") {
					exit($UnauthorizedMessage);
				}
				else {
					if ($query_type == "Update") {
						echo $SponsRepUpdate;
					}
					else {
						if ($query_type == "View") {
							header("Location: view_table.php");
						}
						else {
							if ($query_type = "Delete") {
								echo $SponsRepDelete;
							}
							else exit($UnauthorizedMessage);
						}
					}
				}


			}
			else {
				if ($table_name == "Event Account") {
					if ($query_type == "View") {
						header("Location: view_table.php");
					}
					else {
						if ($query_type == "Insert") {
							echo $AccountLogInsert;
						}
						else {
							if ($query_type == "Delete") {
								echo $AccountLogDelete;
							}
							else exit ($UnauthorizedMessage);
						}
					}
				}
				else {
					if ($table_name == "Company") {
						if ($query_type == "View") {
							header("Location: view_table.php");
						}
						else {
							if ($query_type == "Insert") {
								echo $CompanyInsert;
							}
							else {
								if ($query_type == "Update") {
									echo $CompanyUpdate;
								}
								else {
									if ($query_type == "Delete") {
										echo $CompanyDelete;
									}
								}
							}
						}

					}
					else {
						if ($table_name == "Company Executive") {
							if ($query_type == "View") {
								header("Location: view_table.php");
							}
							else {
								if ($query_type == "Insert") {
									echo $CompanyExecInsert;
								}
								else {
									if ($query_type == "Update") {
										echo $CompanyExecUpdate;
									}
								}
							}

							if ($query_type == "Delete") {
								echo $CompanyExecDelete;
							}
						}
						else {
							if ($table_name == "Meeting Log") {
								if ($query_type == "View") {
									header("Location: view_table.php");
								}
								else {
									if ($query_type == "Insert") {
										echo $MeetingLogInsert;
									}
									else {
										if ($query_type == "Update") {
											echo $MeetingLogUpdate;
										}
										else {
											if ($query_type == "Delete") {
												echo $MeetingLogDelete;
											}
										}
									}
								}
							}
						}
					}
				}
			}


		}


		if ($SponsAccessLevel == "CSO") {

			if ($table_name == "Sponsorship Representative") {


				if ($query_type == "Insert") {
					echo $CSOSponsRepInsert;
				}
				else {
					if ($query_type == "Update") {
						echo $CSOSponsRepUpdate;
					}
					else {
						if ($query_type == "View") {
							header("Location: view_table.php");
						}
						else {
							if ($query_type = "Delete") {
								echo $CSOSponsRepDelete;
							}
							else exit($UnauthorizedMessage);
						}
					}
				}


			}
			else {
				if ($table_name == "Sector Head") {


					if ($query_type == "Insert") {
						echo $CSOSectorHeadInsert;
					}
					else {
						if ($query_type == "Update") {
							echo $CSOSectorHeadUpdate;
						}
						else {
							if ($query_type == "View") {
								header("Location: view_table.php");
							}
							else {
								if ($query_type = "Delete") {
									echo $CSOSectorHeadDelete;
								}
								else exit($UnauthorizedMessage);
							}
						}
					}


				}
				else {
					if ($table_name == "Event Account") {
						if ($query_type == "View") {
							header("Location: view_table.php");
						}
						else {
							if ($query_type == "Insert") {
								echo $CSOAccountLogInsert;
							}
							else {
								if ($query_type == "Delete") {
									echo $CSOAccountLogDelete;
								}
								else exit ($UnauthorizedMessage);
							}
						}
					}
					else {
						if ($table_name == "Company") {
							if ($query_type == "View") {
								header("Location: view_table.php");
							}
							else {
								if ($query_type == "Insert") {
									echo $CSOCompanyInsert;
								}
								else {
									if ($query_type == "Update") {
										echo $CSOCompanyUpdate;
									}
									else {
										if ($query_type == "Delete") {
											echo $CSOCompanyDelete;
										}
									}
								}
							}

						}
						else {
							if ($table_name == "Company Executive") {
								if ($query_type == "View") {
									header("Location: view_table.php");
								}
								else {
									if ($query_type == "Insert") {
										echo $CompanyExecInsert;
									}
									else {
										if ($query_type == "Update") {
											echo $CompanyExecUpdate;
										}
									}
								}

								if ($query_type == "Delete") {
									echo $CompanyExecDelete;
								}
							}
							else {
								if ($table_name == "Meeting Log") {
									if ($query_type == "View") {
										header("Location: view_table.php");
									}
									else {
										if ($query_type == "Insert") {
											echo $CSOMeetingLogInsert;
										}
										else {
											if ($query_type == "Update") {
												echo $CSOMeetingLogUpdate;
											}
											else {
												if ($query_type == "Delete") {
													echo $CSOMeetingLogDelete;
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

	}
	else echo "\$_POST not set";
	*/
?>

</body>
</html>