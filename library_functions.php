<?php
//	session_start();
//	$_SESSION[SessionEnums::UserLoginID] = $_SESSION['loginID'];
	include_once('DBconnect.php');


//	$_SESSION[SessionEnums::UserFestival] = "Techno";
//	$_SESSION[SessionEnums::UserLoginID] = 131080052;
//	$_SESSION[SessionEnums::UserAccessLevel] = UserTypes::SponsRep; //for testing purposes
//	$_SESSION[SessionEnums::UserSector] = "Music Stores";

	abstract class BasicEnum{
		private static $constCacheArray = NULL;

		public static function getConstants(){
			if (self::$constCacheArray == NULL){
				self::$constCacheArray = [];
			}
			$calledClass = get_called_class();
			if (!array_key_exists($calledClass, self::$constCacheArray)){
				$reflect = new ReflectionClass($calledClass);
				self::$constCacheArray[$calledClass] = $reflect->getConstants();
			}

			return self::$constCacheArray[$calledClass];
		}


		public static function isValidName($name, $strict = false){
			$constants = self::getConstants();

			if ($strict){
				return array_key_exists($name, $constants);
			}

			$keys = array_map('strtolower', array_keys($constants));

			return in_array(strtolower($name), $keys);
		}


		public static function isValidValue($value, $strict = true){
			$values = array_values(self::getConstants());

			return in_array($value, $values, $strict);
		}
	}




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
	}





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







	abstract class InputTypes extends BasicEnum{
		const text = "text";
		const radio = "radio";
		const password = "password";
		const date = "date";
		const time = "time";
		const submit = "submit";
		const number = "number";
		const textarea = "textarea"; //though not technically an <input> type, this is too convenient not to use.
	}



	class InputField{
		var $inputType = NULL;
		var $name = NULL;
		var $disabled = NULL;
		var $value = NULL;
		var $inputCSSClass = NULL;
		var $labelText = NULL;
		var $labelCSSClass = NULL;
		var $inputDataListID = NULL;
		var $inputDataList = NULL;


		function InputField($inputType, $name, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = NULL, $labelCSSClass = NULL, $inputDataListID = NULL, $inputDataList = NULL){

			if (!InputTypes::isValidValue($inputType)){
				echo "Invalid type passed to constructor of class InputField.";

				return;
			}
			$this->inputType = $inputType;
			$this->name = $name;
			$this->disabled = $disabled;
			$this->value = $value;
			$this->inputCSSClass = $inputCSSClass;
			$this->labelText = $labelText;
			$this->labelCSSClass = $labelCSSClass;
			$this->inputDataListID = $inputDataListID;
			$this->inputDataList = $inputDataList;
		}


		function __toString(){
			$out = "";
			if ($this->labelText){
				if ($this->labelCSSClass){
					$out .= '<label for="' . $this->name . '" class="' . $this->labelCSSClass . '">' . $this->labelText . ':</label> ';
				}
				else $out .= '<label for="' . $this->name . '">' . $this->labelText . ':</label> ';
			}

			if($this->inputType == InputTypes::textarea){
				$out .= '<textarea ';
			}
			else {
				$out .= '<input type="'.$this->inputType . '"';
			}

			$out.= ' name="' . $this->name . '" value="' . $this->value . '" ';
			if ($this->disabled){
				$out .= " disabled ";
			}
			if ($this->inputCSSClass){
				$out .= ' class="' . $this->inputCSSClass . '"';
			}

			//add datalist if present:
			if( ($this->inputType==InputTypes::number ||  $this->inputType==InputTypes::text) && $this->inputDataListID && $this->inputDataList){
				$out .= " list=\"$this->inputDataListID\"";
			}


			if($this->inputType == InputTypes::textarea){
				$out .= '></textarea> ';
			}
			else{
				$out .= '/>';
			}

			//add datalist items if present:
			if( ($this->inputType==InputTypes::number ||  $this->inputType==InputTypes::text) && $this->inputDataListID && $this->inputDataList){
				$out .= "<datalist id= \"$this->inputDataListID\">";
				foreach($this->inputDataList as $datalistItem){
					$out .= "<option value=\"$datalistItem\" />";
				}
				$out .= "</datalist>";
			}

			return $out;
		}
	}



	class OptionField{ //goes inside a selectField
		var $value = NULL;
		var $optionText = NULL;
		var $selected = NULL;
		var $disabled = NULL;
		function OptionField($value, $optionText, $selected=false, $disabled=false){
			if($selected != true && $selected != false)
				echo "Invalid option selected parameter passed";
			else $this->selected = $selected;
			$this->value= $value;
			$this->optionText= $optionText;
			$this->disabled = $disabled;
		}

		function __toString(){
			$out = "<option value=\"$this->value\" ";
			if($this->selected)
				$out.=" selected";
			if ($this->disabled){
				$out .= " disabled ";
			}
			$out.=">$this->optionText</option>";
			return $out;
		}

	}

	class SelectField{
		var $options = NULL;
		var $name = NULL;
		var $selectCSSClass = NULL;
		var $labelText = NULL;
		var $labelCSSClass = NULL;

		function SelectField($options, $name, $selectCSSClass=NULL, $labelText=NULL, $labelCSSClass=NULL){
			$this->options = $options;
			$this->name = $name;
			$this->selectCSSClass = $selectCSSClass;
			$this->labelText = $labelText;
			$this->labelCSSClass = $labelCSSClass;
		}


		function __toString(){
			$out = "";
			if ($this->labelText){
				if ($this->labelCSSClass){
					$out .= '<label for="' . $this->name . '" class="' . $this->labelCSSClass . '">' . $this->labelText . ':</label> ';
				}
				else $out .= '<label for="' . $this->name . '">' . $this->labelText . ':</label> ';
			}

			$out .= "<select name=\"$this->name\" class=\"$this->selectCSSClass\">";
			foreach($this->options as $option){
				$out.=$option;
			}
			$out.="</select>";
			return $out;
		}

	}


	class HTMLForm{
		var $formName = NULL;
		var $formAction = NULL;
		var $formMethod = NULL;
		var $formCSSClass = NULL;
		var $fields = NULL;    //an array of InputField, SelectField objects.
		var $title = NULL;
		var $fieldSeparator = NULL;


		function HTMLForm($formName, $formAction, $formMethod, $fields, $formCSSClass=NULL, $title = NULL, $fieldSeparator="<br>"){
			if (!FormMethod::isValidValue($formMethod)){
				echo "Invalid formMethod passed to constructor of class HTMLForm.";

				return;
			}
			$this->formName = $formName;
			$this->formAction = $formAction;
			$this->formMethod = $formMethod;
			foreach ($fields as $field){
				$this->fields[$field->name] = $field;
			}
			$this->formCSSClass = $formCSSClass;
			$this->title = $title;
			$this->fieldSeparator = $fieldSeparator;
		}


		function addField($field){
			try{
				$this->fields[$field->name] = $field;

				return true;
			} catch (Exception $e){
				return false;
			}

		}


		function removeField($fieldName){
			if (in_array($fieldName, $this->fields)){
				unset($this->fields[array_search($fieldName, $this->fields)]);

				return true;
			}

			return false;
		}

		function echoFieldNames(){
			foreach ($this->fields as $fieldName => $field){
				echo $fieldName."<br>";
			}
		}

		function rearrangeFields($orderedFieldNames){
			$tempArr = [];
			foreach($orderedFieldNames as $orderedFieldName){
				if(array_key_exists($orderedFieldName,$this->fields))
					$tempArr[$orderedFieldName] = $this->fields[$orderedFieldName];
			}
			$this->fields = $tempArr;
		}

		function __toString(){
//			$this->echoFieldNames();
			$out = "";
			if($this->title)
				$out .= "<h2 align=\"center\">$this->title:</h2>";
			$out .= "<form action= \"$this->formAction\"  method= \"$this->formMethod\" name=\"$this->formName\" ";
			if($this->formCSSClass)
				$out.=" class=\"$this->formCSSClass\"";
			$out.=">";
			foreach ($this->fields as $value){
				$out .= $value . $this->fieldSeparator;
			}
			$out .= "</form>";

			return $out;
		}

	}




	function extractValueFromGET($valueName){
		if(array_key_exists($valueName, $_GET)){
			return $_GET[$valueName];
		}
		return NULL;
	}


	function extractValueFromPOST($valueName){
		if(array_key_exists($valueName, $_POST)){
			return $_POST[$valueName];
		}
		return NULL;
	}



	function get_person_name($SponsID){
		$db = new SponsorshipDB();
		$rep_access_level = $db->select("SELECT Name FROM CommitteeMember WHERE ID = " . $SponsID);
		if(count($rep_access_level)==0)
			return NULL;

		return $rep_access_level[0]["Name"];

	}



	function get_access_level($SponsID){
		$db = new SponsorshipDB();
		$rep_access_level = $db->select("SELECT AccessLevel FROM SponsLogin WHERE SponsID = $SponsID");
		if(count($rep_access_level)==0)
			return NULL;

		return $rep_access_level[0]["AccessLevel"];
	}




	function get_person_sector($SponsID){
		$db = new SponsorshipDB();
		$rep_sector = $db->select("SELECT Sector FROM ((Select SponsID, Sector from SponsRep) UNION (Select SponsID, Sector from SectorHead)) as SponsOfficer  WHERE SponsOfficer.SponsID = $SponsID");
		if (count($rep_sector) == 0)//i.e. you don't find the person with that SponsID in the SponsRep table.
			return NULL;

		return $rep_sector[0]["Sector"];
	}



	function get_earning_report($SponsID){
		$rep_name = get_person_name($SponsID);
		$rep_amount = 0;
		$rep_num_companies = 0; //gets nummber of companies signed by that spons rep

		$rep_amount_query = "SELECT Amount FROM AccountLog WHERE SponsID = $SponsID";

		$db = new SponsorshipDB();
		$result = $db->select($rep_amount_query);
		if (count($result) > 0){
			foreach ($result as $row){
				$rep_num_companies++;
				$rep_amount = $rep_amount + $row["Amount"];
			}
		}

		return array($rep_name, $rep_num_companies, $rep_amount);
	}




	function get_meeting_report($SponsID){
		$rep_name = get_person_name($SponsID);
		$rep_calls = 0;
		$rep_emails = 0;
		$rep_meetings = 0;

		$rep_meeting_query = "SELECT * FROM Meeting WHERE SponsID = $SponsID";
		$db = new SponsorshipDB();
		$result = $db->select($rep_meeting_query);
		if (count($result) > 0){
			foreach ($result as $row){
				switch ($row["MeetingType"]){
					case MeetingTypes::Call:
						$rep_calls++;
						break;
					case MeetingTypes::Email:
						$rep_emails++;
						break;
					case MeetingTypes::FaceToFace:
						$rep_meetings++;
						break;

				}
			}
		}

		return array($rep_name, $rep_calls, $rep_meetings, $rep_emails);
	}



	function get_sector_details($SponsSector){
		$num_spons_reps = 0;
		$num_sector_heads = 0;
		$num_companies_in_sector = 0;
		$total_companies_signed = 0;
		$total_earned = 0;
		$max_earned = 0;
		$max_earner_ID = -1;

		$Sector_SR_query = "SELECT * FROM SponsRep WHERE Sector = '$SponsSector'";
		$db = new SponsorshipDB();
		$result = $db->select($Sector_SR_query);

		if (count($result) > 0){
			foreach ($result as $row){
				$num_spons_reps++;
				$SponsRepID = $row['SponsID'];
				$SponsRepEarningReport = get_earning_report($SponsRepID);
				$total_companies_signed = $total_companies_signed + $SponsRepEarningReport[1];
				$total_earned = $total_earned + $SponsRepEarningReport[2];
				if ($max_earned < $SponsRepEarningReport[2]){
					$max_earned = $SponsRepEarningReport[2];
					$max_earner_ID = $SponsRepID;
				}
			}
		}

		$Sector_SH_query = "SELECT * FROM SectorHead WHERE Sector='$SponsSector'";
		$result = $db->select($Sector_SH_query);
		$num_sector_heads = count($result);


		$Sector_CMP_query = "SELECT * FROM Company WHERE Sector='$SponsSector'";
		$result = $db->select($Sector_CMP_query);
		$num_companies_in_sector = count($result);

		return array("num_spons_reps" => $num_spons_reps, "num_sector_heads" => $num_sector_heads, "num_companies_in_sector" => $num_companies_in_sector, "total_companies_signed" => $total_companies_signed, "total_earned" => $total_earned, "max_earner_ID" => $max_earner_ID, "max_earned" => $max_earned);
	}


	function select_single_column_from_table($column_name, $table_name, $where_params=NULL){
		$out_list = [];

		$single_col_select_query = "SELECT DISTINCT $column_name FROM $table_name";
		if($where_params)
			$single_col_select_query.= " WHERE $where_params;";
		$single_col_select_query .= ";";

//		echo $single_col_select_query;
		$db = new SponsorshipDB();
		$result = $db->select($single_col_select_query);
		if (count($result) > 0){
			foreach ($result as $row)
				array_push($out_list, $row[$column_name]);
		}
		return $out_list;
	}



	function print_table($result){ //array of attributes and corresponding sql result we get from querying the attributes
		echo '<div align="center">';
		echo '<table align="center" style=\"width:100%\" class="output_table">';
		echo "<tr>";
		$i = 0;
		//$attributes_info = mysql_fetch_field($result, $i); //gets a lot of data about the attributes...their names, their types etc.
		while ($i < mysql_num_fields($result)){
			$attr = mysql_fetch_field($result, $i);
			echo "<th>" . $attr->name . "</th>";
			$i++;
		}

		while ($row = mysql_fetch_assoc($result)){
			echo '<tr>';
			foreach ($row as $key => $value){
				echo '<td>' . $value . '</td>';
			}
			echo "</tr>";
		}
		echo "</table>";
		echo '</div>';
	}


	function print_simple_table($result, $table_classes=NULL, $table_id=NULL){
		$out = "<table ";
		if ($table_classes){
			$out .= "class =\"";
			foreach($table_classes as $table_class){
				$out.= $table_class." ";
			}
			$out .= "\" ";
		}

		if($table_id){
			$out.= " id = \"$table_id\"";
		}
		$out .= ">";


		//table headers:
		$i = 0;
		$out.= "<thead><tr>";
		while ($i < mysql_num_fields($result)){
			$attr = mysql_fetch_field($result, $i);
			$out .= "<th>".$attr->name."</th>";
			$i++;
		}
		$out.= "</tr></thead>";


		while ($row = mysql_fetch_assoc($result)){
			$out.= '<tr>';
			foreach ($row as $key => $value){
				if($value!=NULL && $value!=""){
					$out.= '<td>' . $value . '</td>';
				}
				else{
					$out.= '<td class="center">-</td>';
				}
			}
			$out.= "</tr>";
		}

		$out .="</table>";
		echo $out;
		return $out;
	}



	function print_sort($result){
		echo '<form action="sort_search_table.php" class="sort_form" method="post" align="center">';
		echo 'Sort by:<select name="order_by">';
		$i = 0;

		while ($i < mysql_num_fields($result)){
			$attr = mysql_fetch_field($result, $i);
			echo "<option>" . $attr->name . "</option>";
			$i++;
		}
		echo '</select> ';
		echo '<button type="submit" name="submit">Sort</button>';
		echo '</form>';
	}



	function print_search($result){
		echo '<form action="sort_search_table.php" class="search_form" method="post" align="center">';
		echo 'Search by:<select name="search_by">';
		$i = 0;

		while ($i < mysql_num_fields($result)){
			$attr = mysql_fetch_field($result, $i);
			echo "<option>" . $attr->name . "</option>";
			$i++;
		}
		echo '</select> ';
		echo '<input type="text" name="search_field">';
		echo '<button type="submit" name="submit">Search</button>';
		echo '</form>';
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



	abstract class QueryFieldNames extends BasicEnum{
		const SponsFestival = "SponsFestival";
		const SponsSector = "SponsSector";
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
		const SponsCompany = "SponsCompany";
		const SponsTransType = "SponsTransType";
		const SponsDate = "SponsDate";
		const SponsTime = "SponsTime";
		const SponsAmount = "SponsAmount";
		const SponsAccountLogEntryID = "SponsAccountLogEntryID";
		const SponsCompanyStatus = "SponsCompanyStatus";
		const SponsCompanySponsoredOthers = "SponsCompanySponsoredOthers";
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

		static $systemGenerated = [ //fields that should not be replaced by GET parameters, etc.
			QueryFieldNames::SponsFestival,
			QueryFieldNames::SponsID,
			QueryFieldNames::SponsTransType,
			QueryFieldNames::SponsSector
		];

		static $TableToFieldNameOrdering = [ //used to specify ordering in forms
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

			SQLTables::SponsRep => [
				QueryFieldNames::SponsFestival,
				QueryFieldNames::SponsSector,
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

	}


	/*##------------------------------------------------TESTS------------------------------------------------##

	foreach($db->select("Select * from committeemember") as $key1 => $val1){
		echo "<br><br><br>".$key1." : ";
		foreach($val1 as $key2 => $val2) {
			echo "<br>\t" . $key2 . " => " . $val2;
		}
	}

	*/

	echo get_person_sector("131010004")."<hr>";
	echo get_access_level(131010004)."<hr>";
	echo get_person_name("131010004")."<hr>";
	foreach(get_earning_report("131010004") as $x){
		echo "<br>".$x;
	}
	echo "<hr>";
	foreach(get_meeting_report("131010004") as $x){
		echo "<br>".$x;
	}
	echo "<hr>";
	foreach(get_sector_details(get_person_sector("131010004")) as $x){
		echo "<br>".$x;
	}


	/*##---------------------------------------------END OF TESTS---------------------------------------------##*/


?>