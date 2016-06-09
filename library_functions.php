<?php
	if(!isset($_SESSION[SessionEnums::UserLoginID]))
		session_start();
//	$_SESSION[SessionEnums::UserLoginID] = $_SESSION['loginID'];
	include_once('DBconnect.php');



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
		}

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
		var $readonly = NULL;
		var $value = NULL;
		var $inputCSSClass = NULL;
		var $labelText = NULL;
		var $labelCSSClass = NULL;
		var $inputDataListID = NULL;
		var $inputDataList = NULL;
		var $required = NULL; //required="required"


		function InputField($inputType, $name, $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = NULL, $labelCSSClass = NULL, $inputDataListID = NULL, $inputDataList = NULL, $required = NULL){

			if (!InputTypes::isValidValue($inputType)){
				echo "Invalid type passed to constructor of class InputField.";

				return;
			}
			$this->inputType = $inputType;
			$this->name = $name;
			$this->readonly = $readonly;
			$this->value = $value;
			$this->inputCSSClass = $inputCSSClass;
			$this->labelText = $labelText;
			$this->labelCSSClass = $labelCSSClass;
			$this->inputDataListID = $inputDataListID;
			$this->inputDataList = $inputDataList;
			$this->required = $required;
		}

		function setReadOnly(){
			$this->readonly = true;
		}


		function setRequired(){
			$this->required = true;
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
			if ($this->readonly){
				$out .= " readonly=\"readonly\" ";
			}
			if ($this->required){
				$out .= " required=\"required\" ";
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
		var $required = NULL;

		function SelectField($options, $name, $selectCSSClass=NULL, $labelText=NULL, $labelCSSClass=NULL, $required = NULL){
			$this->options = $options;
			$this->name = $name;
			$this->selectCSSClass = $selectCSSClass;
			$this->labelText = $labelText;
			$this->labelCSSClass = $labelCSSClass;
			$this->required = $required;
		}

		function setRequired(){
			$this->required = true;
		}


		function __toString(){
			$out = "";
			if ($this->labelText){
				if ($this->labelCSSClass){
					$out .= '<label for="' . $this->name . '" class="' . $this->labelCSSClass . '">' . $this->labelText . ':</label> ';
				}
				else $out .= '<label for="' . $this->name . '">' . $this->labelText . ':</label> ';
			}

			$out .= "<select name=\"$this->name\" class=\"$this->selectCSSClass\" ";
			if($this->required)
				$out .= " required = \"required\" ";
			$out .= ">";
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

		function setRequiredFields($requiredFieldsLookup, $tableName, $queryType){
			if(array_key_exists($tableName,$requiredFieldsLookup) && array_key_exists($queryType,$requiredFieldsLookup[$tableName])){
				foreach ($requiredFieldsLookup[$tableName][$queryType] as $requiredField){
					if(array_key_exists($requiredField,$this->fields))
						$this->fields[$requiredField]->setRequired();
				}
				return true;
			}
			return false;
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



	function echo_1d_array($array, $arrayName = "1D-Array"){
		echo "<hr>$arrayName: ";
		foreach($array as $key=>$value)
			echo "<br>$key => $value";
		echo "<hr>";
	}


	function echo_2d_array($array, $arrayName = "2D-Array"){
		echo "<hr>$arrayName: ";
		foreach($array as $key1=>$value1){
			echo "<br><br><br>$key1 => ";
			foreach($value1 as $key2=>$value2){
				echo "<br>$key2 => $value2";
			}
		}
		echo "<hr>";
	}



	function extractValueFromGET($valueName){
		if(array_key_exists($valueName, $_GET) && $_GET[$valueName]!=NULL && $_GET[$valueName]!="" && strtolower($_GET[$valueName]) != 'null'){
			return $_GET[$valueName];
		}
		return NULL;
	}


	function extractValueFromPOST($valueName){
		if(array_key_exists($valueName, $_POST) && $_POST[$valueName]!=NULL && $_POST[$valueName]!="" && strtolower($_POST[$valueName]) != 'null'){
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
		foreach($result[0] as $columnName => $value){
			$out .= "<th>".$columnName."</th>";
		}
		$out.= "</tr></thead>";


		foreach($result as $row){
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


			SQLTables::CommitteeMember => [
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
					QueryFieldNames::SponsOthersID
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
					QueryFieldNames::SponsOthersID
				]
			],


			SQLTables::SectorHead => [ //identical to SponsRep
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
					QueryFieldNames::SponsOthersID
				]
			],



			SQLTables::Event => [

			],

			SQLTables::SponsLogin => [

			]

		];


		static $mapToDBFieldNames = [
			QueryFieldNames::SponsFestival => "EventName",
			QueryFieldNames::SponsSector => "Sector",
			QueryFieldNames::SponsRole => "Role",
			QueryFieldNames::SponsID => "SponsID",
			QueryFieldNames::SponsOthersID => "SponsID",
			QueryFieldNames::SponsName => "Name",
			QueryFieldNames::SponsPassword => "Password",
			QueryFieldNames::SponsEmail => "Email",
			QueryFieldNames::SponsMobile => "Mobile",
			QueryFieldNames::SponsYear => "Year",
			QueryFieldNames::SponsBranch => "Branch",
			QueryFieldNames::SponsCompany => "CMPName",
			QueryFieldNames::SponsTransType => "TransType",
			QueryFieldNames::SponsDate => "Date",
			QueryFieldNames::SponsTime => "Time",
			QueryFieldNames::SponsAmount => "Amount",
			QueryFieldNames::SponsAccountLogEntryID => "ID",
			QueryFieldNames::SponsCompanyStatus => "CMPStatus",
			QueryFieldNames::SponsCompanySponsoredOthers => "SponsoredOtherOrganization",
			QueryFieldNames::SponsCompanyAddress => "CMPAddress",
			QueryFieldNames::SponsCompanyExec => "CEName",
			QueryFieldNames::SponsCompanyExecEmail => "CEEmail",
			QueryFieldNames::SponsCompanyExecMobile => "CEMobile",
			QueryFieldNames::SponsCompanyExecPosition => "CEPosition",
			QueryFieldNames::SponsMeetingAddress => "Address",
			QueryFieldNames::SponsMeetingType => "MeetingType",
			QueryFieldNames::SponsMeetingOutcome => "Outcome",
			QueryFieldNames::SponsMeetingEntryID => "ID"
		];


		static $systemGenerated = [ //fields that should use the $_SESSION values and not those passed in the form.
		];


		static function setSystemGenerated (){ //fields that should use the $_SESSION values, and not those passed in the form.
			if(self::$systemGenerated != []) //don't reset it multiple times.
				return;
			 QueryFieldNames::$systemGenerated[QueryFieldNames::SponsFestival] = $_SESSION[SessionEnums::UserFestival];
			 QueryFieldNames::$systemGenerated[QueryFieldNames::SponsID] = $_SESSION[SessionEnums::UserLoginID];
			 QueryFieldNames::$systemGenerated[QueryFieldNames::SponsTransType] = TransType::Deposit;
			 QueryFieldNames::$systemGenerated[QueryFieldNames::SponsSector] = $_SESSION[SessionEnums::UserSector];

		}

	}



	/*##------------------------------------------------TESTS------------------------------------------------##

	echo new HTMLForm(
		$formName = "SponsRepInsert", $formAction = "view_table.php", $formMethod = FormMethod::POST,
		$fields = array(
			new InputField(
				$inputType = InputTypes::text, $name = "TransType", $value = TransType::Deposit, $readonly = true, $inputCSSClass = NULL,
				$labelText = "Transaction Type", $labelCSSClass = NULL
			),
			new InputField(
				$inputType = InputTypes::text, $name = "CMPName", $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Company Name",
				$labelCSSClass = NULL
			),
			new InputField(
				$inputType = InputTypes::text, $name = "Amount", $value = "", $readonly = false, $inputCSSClass = NULL, $labelText = "Amount",
				$labelCSSClass = NULL
			),
			new SelectField(
				$options = [
					new OptionField(UserTypes::SponsRep, UserTypes::SponsRep),
					new OptionField(UserTypes::SectorHead, UserTypes::SectorHead)
				],
				$name = "UserType",$selectCSSClass=NULL, $labelText="User Type", $labelCSSClass=NULL
			),
			new InputField(
				$inputType = InputTypes::submit, $name = "Submit", $value = "Submit", $readonly = false, $inputCSSClass = "query_forms"
			)
		),
		$formCSSClass=NULL,
		$title = "Insert details of sponsorship received",
		$fieldSeparator = "<br>"
	);


	echo new InputField(
		$inputType = InputTypes::text, $name = QueryFieldNames::SponsSector, $value ="", $readonly = false, $inputCSSClass = NULL,
		$labelText = "Company Sector", $labelCSSClass = NULL, $inputDataListID="SectorsInDB", $inputDataList=select_single_column_from_table("CMPName", "Company"), $required=true
	);



	foreach($db->select("Select * from committeemember") as $key1 => $val1){
		echo "<br><br><br>".$key1." : ";
		foreach($val1 as $key2 => $val2) {
			echo "<br>\t" . $key2 . " => " . $val2;
		}
	}



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


	$db = new SponsorshipDB();
	$x = $db->query("INSERT INTO committeemember
		(ID, Organization, EventName, Name, Department, Role, Mobile, Email, Year, Branch)
		VALUES
		(".mt_rand(10,1000).", 'Technovanza', 'Technovanza','Lol', 'LOL', 'CSO', '989', NULL, 2, 'Comps');
		"
	);
	echo $x."<hr>";
	$x = $db->query("UPDATE committeemember SET Name = 'AAAA' WHERE Name = 'Hi';");
	echo $x;


	/*##---------------------------------------------END OF TESTS---------------------------------------------##*/







	class SQLQuery{
		var $queryType = NULL; //must be in the QueryTypes enum
		var $tableName = NULL;
		var $tableFields = NULL;
		var $necessaryFields = NULL;
		var $whereClause = NULL;

		var $tableInsertFieldValues = NULL; //a 2-D array;
		var $tableUpdateFieldValues = NULL; //a 2-D array;


		function SQLQuery($tableName=NULL, $tableFields=NULL){
			$this->tableName = $tableName;
			$this->tableFields = $tableFields;
		}

		public function setSelectQuery($tableName=NULL, $tableFields=NULL, $whereClause=NULL){
			$this->queryType = QueryTypes::View;
			if($tableName)
				$this->tableName = $tableName;
			if($tableFields)
				$this->tableFields = $tableFields;
			if($whereClause)
				$this->whereClause = $whereClause;
		}

		public function setInsertQuery($tableName=NULL, $tableFields=NULL, $tableInsertFieldValues, $whereClause=NULL, $necessaryFields=NULL){
			//$tableInsertFieldValues MUST be a 2-D array, with each row having same length as $this->tableFields.

			$this->queryType = QueryTypes::Insert;
			if($tableName)
				$this->tableName = $tableName;
			if($tableFields)
				$this->tableFields = $tableFields;
			$this->tableInsertFieldValues = $tableInsertFieldValues;
			$this->whereClause = $whereClause;
			$this->necessaryFields = $necessaryFields;
		}

		public function setUpdateQuery($tableName=NULL, $tableUpdateFieldValues, $whereClause=NULL, $necessaryFields=NULL){
			$this->queryType = QueryTypes::Modify;
			if($tableName)
				$this->tableName = $tableName;

			$this->tableUpdateFieldValues = $tableUpdateFieldValues;
			$this->whereClause = $whereClause;
			$this->necessaryFields = $necessaryFields;
		}


		public function setDeleteQuery($tableName=NULL, $whereClause=NULL, $necessaryFields=NULL){
			$this->queryType = QueryTypes::Delete;
			if($tableName)
				$this->tableName = $tableName;

			$this->whereClause = $whereClause;
			$this->necessaryFields = $necessaryFields;
		}


		public function getQuery(){
			switch($this->queryType){
				case QueryTypes::Insert :
					return $this->generateInsertQuery();
					break;
				case QueryTypes::Modify :
					return $this->generateUpdateQuery();
					break;
				case QueryTypes::Delete :
					return $this->generateDeleteQuery();
					break;
				case QueryTypes::View :
					return $this->generateSelectQuery();
			}
			return NULL;
		}

		public function executeQuery(){
			$db = new SponsorshipDB();
			switch($this->queryType){
				case QueryTypes::Insert :
				case QueryTypes::Modify :
				case QueryTypes::Delete :
					$res = $db->query($this->getQuery());
					if($res === FALSE){
						return false;
					} else return true;
					break;

				case QueryTypes::View :
					$res = $db->select($this->getQuery());
					break;
			}
			return NULL;
		}

		private function checkNecessaryFields(){
			if($this->tableFields){
				if($this->necessaryFields == NULL || $this->necessaryFields == [])
					return true;
				foreach($this->necessaryFields as $necessaryField){
					if(!in_array($necessaryField, $this->tableFields))
						return false;
				}
				return true;
			}
			return false;
		}

		private static function convertArrayToCommaSeparatedTuple($array, $surrounder="'", $parens=true){
			$out = " ";
			if($parens)
				$out .= "(";
			for($i=0; $i<count($array) - 1; $i++){
				$out .= self::surroundWith($array[$i], $surrounder).", ";
			}
			$out .= self::surroundWith($array[$i], $surrounder);
			if($parens)
				$out .= ") ";
			$out .= " ";
			return $out;
		}


		private function generateSelectQuery(){
			if($this->checkNecessaryFields()){
				$out = "SELECT ";

				$len = count($this->tableFields);
				/* Allowed values for the tableFields array:
				 * ["ID", "Name", "Company"]
				 * [["ID"], ["Name"], ["Company"]]
				 * [["ID"], "Name", ["Company"]]
				 * [["ID"], ["Name"], ["Company", "Company I work for"]]
				 * ["ID", ["Name"], ["Company", "Company I work for"]]
				 * */
				for($i=0; $i < $len ; $i++){
					$field = $this->tableFields[$i];
					if(!is_array($field))
						$out .= " $field ";
					else if(count($field) == 1)
						$out .= " $field[0] ";
					else if(count($field) == 2)
						$out .= " $field[0] AS '$field[1]' ";
					else return NULL;

					if($i != $len-1)
						$out .= ", ";
				}

				$out .=  "FROM $this->tableName ";
				if($this->whereClause)
					$out .= " WHERE ".$this->whereClause;

				if($this->checkForSQLInjection($out)){
					return NULL;
				}

				$out .= ";";
				return $out;
			}
			return NULL;
		}




		private function generateInsertQuery(){
			if($this->checkNecessaryFields()){
				$out = "";
				$out .= self::convertArrayToCommaSeparatedTuple($this->tableFields, $surrounder="`");
				$out .= " VALUES \n";

				$len = count($this->tableInsertFieldValues);
				for($i=0; $i<$len; $i++){
					$row = $this->tableInsertFieldValues[$i];
					if(count($row) != count($this->tableFields)){
						echo "<br>Length of ".self::convertArrayToCommaSeparatedTuple($this->tableFields, $surrounder="`")." and ". $this->convertArrayToCommaSeparatedTuple($row, $surrounder="'")." are different.";
						return NULL;
					}

					if($i != $len-1)
						 $out .= $this->convertArrayToCommaSeparatedTuple($row, $surrounder="'").",\n";
					else $out .= $this->convertArrayToCommaSeparatedTuple($row, $surrounder="'")."\n";
				}

				if($this->checkForSQLInjection($out)){
					return NULL;
				}

				$out = "INSERT INTO $this->tableName " . $out;
				$out .= ";";
				return $out;
			}
			return NULL;
		}


		private function generateUpdateQuery(){
			if($this->checkNecessaryFields()){
				$out = "";
				$out .= " SET ";
				$len = count($this->tableUpdateFieldValues);
				for($i=0; $i < $len; $i++){
					$row = $this->tableUpdateFieldValues[$i];
					if(count($row)!=2)
						return NULL;
					$out .= " " . self::surroundWith($row[0], $surrounder=" ") ." = ". self::surroundWith($row[1], $surrounder="'") ." ";

					if($i != $len-1)
						$out .= ", ";
				}

				if($this->whereClause)
					$out .= " WHERE ".$this->whereClause;

				if($this->checkForSQLInjection($out)){
					return NULL;
				}

				$out = "UPDATE $this->tableName " . $out;
				$out .= ";";
				return $out;
			}
			return NULL;
		}



		private function generateDeleteQuery(){
			if($this->checkNecessaryFields()){
				$out = "";

				if($this->whereClause)
					$out .= " WHERE ".$this->whereClause;

				if($this->checkForSQLInjection($out)){
					return NULL;
				}

				$out = "DELETE FROM $this->tableName " . $out;
				$out .= ";";
				return $out;
			}
			return NULL;
		}




		private function checkForSQLInjection($text){
			if(!$text)
				return false;
			$text = strtolower($text); //to lowercase
			if( 	//Source: http://php.net/manual/en/function.strpos.php
					strpos($text, ";") === FALSE &&
					strpos($text, " insert ") === FALSE &&
					strpos($text, " update ") === FALSE &&
					strpos($text, " delete ") === FALSE
				)
				return false;

//			echo "<br>$text<br>";
			echo "NO SQL INJECTION ALLOWED";
			return true;
		}

		public static function surroundWith($string, $surrounder="'"){
			//Source: http://stackoverflow.com/questions/1555434/php-wrap-a-string-in-double-quotes
			return $surrounder . trim(
					trim(trim(trim(trim($string), '"'), "'"), '"')
			) . $surrounder; //This removes __all__ kinds of nested single and double quotes from around a word.

			//	$a = new SQLQuery();
			//	echo $a->surroundWithSingleQuotes("\"'Hello'\"");
			//	echo $a->surroundWithSingleQuotes("'\"Hello\"'");
		}

		public static function getWhereEquality($whereArray, $joiner="AND"){ //an array of arrays in which each sub-array has two values which must be equal. One must be a field in the database, at that must come first.
			if(count($whereArray) == 0)
				return NULL;

			$out = "";
			$len = count($whereArray);
			for($i=0; $i<$len; $i++){
				$wherePair = $whereArray[$i];
				if(count($wherePair) != 2){
					echo "$wherePair must be an array of exactly two elements";
					return NULL;
				}
				if($i != $len-1)
					$out .= " ".$wherePair[0]." = ".self::surroundWith($wherePair[1])." $joiner ";
				else $out .= " ".$wherePair[0]." = ".self::surroundWith($wherePair[1])." ";

			}
			return $out;

		}



		public static function getInnerJoin($table1, $field1, $table2, $field2, $alias=NULL){
			if($table1 && $field1 && $table2 && $field2){
				$out = " ( $table1 INNER JOIN $table2 ON ($table1.$field1 = $table2.$field2) ) ";
				if($alias)
					$out .= " AS ".self::surroundWith($alias, "'")." ";
				return $out;
			}
			return NULL;
		}

		public static function convertEmptyToNULLString($field){
			if($field == NULL || $field == "" ||$field == []){
				return "NULL";
			}
			else return $field;
		}


		public function __toString(){
			return $this->getQuery();
		}
	}





	/*##------------------------------------------------TESTS------------------------------------------------##

	$a = new SQLQuery();
	$a->setInsertQuery("Committeemember",["ID", "Name"],[["lol","lol"],["lol2","lol3"]]);
	echo $a->getQuery();





	echo "<hr>";
	$a->setSelectQuery(
			$tableName = "CommitteeMember",
			$tableFields = ["ID", "Name"],
			$whereClause = SQLQuery::getWhereEquality([["ID",131080051], ["Name","Abhishek Divekar"]])
	);
	echo $a->getQuery();





	echo "<hr>";
	$a->setSelectQuery(
		$tableName = SQLQuery::getInnerJoin("CommitteeMember", "ID", "AccountLog", "SponsID"),
		$tableFields = [["CommitteeMember.ID", "ID"], "Name", ["AccountLog.Title", "Company Name"]],
		$whereClause = SQLQuery::getWhereEquality([
								["CommitteeMember.ID",131080051],
								["Name","Abhishek Divekar"]
							])
	);
	echo $a->getQuery();





	echo "<hr>";
	$a->setUpdateQuery(
		$tableName = "CommitteeMember",
		$tableUpdateFieldValues = [["ID", "123"],["Title", "Heelo world"]],
		$whereClause = SQLQuery::getWhereEquality([
								["CommitteeMember.ID",131080051],
								["Name","Abhishek Divekar"]
							])
	);
	echo $a->getQuery();





	echo "<hr>";
	$a->setDeleteQuery(
		$tableName = "CommitteeMember",
		$whereClause = SQLQuery::getWhereEquality([
								["CommitteeMember.ID",131080051],
								["Name","Abhishek Divekar"]
							])
	);
	echo $a->getQuery();



	SQLTables::setDBStructure();	//set all the table columns for easy access.
	echo_1d_array(SQLTables::$DBTableStructure[SQLTables::AccountLog], SQLTables::AccountLog);


	QueryFieldNames::setSystemGenerated();		//set all system generated variables for later use.
	echo_1d_array(QueryFieldNames::$systemGenerated, "System generated:");


	/*##---------------------------------------------END OF TESTS---------------------------------------------##*/

	$db = new SponsorshipDB();
	SQLTables::setDBStructure();	//set all the table columns for easy access.
	QueryFieldNames::setSystemGenerated();		//set all system generated variables for later use.


?>