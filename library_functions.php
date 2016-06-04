<?php
//	session_start();
//	$_SESSION[SessionEnums::UserLoginID] = $_SESSION['loginID'];
	require('DBconnect.php');
	$_SESSION[SessionEnums::UserFestival] = "Techno";
	$_SESSION[SessionEnums::UserLoginID] = 131080052;
	$_SESSION[SessionEnums::UserAccessLevel] = UserTypes::CSO;
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



	abstract class UserTypes extends BasicEnum{
		const CSO = "CSO";
		const SponsRep = "Sponsorship Representative";
		const SponsorshipRepresentative = "Sponsorship Representative";
		const SectorHead = "Sector Head";
	}



	function get_person_name($SponsID){
		require('DBconnect.php'); //this is needed in every function that uses mySQL
		$rep_name = mysql_query("SELECT Name FROM CommitteeMember WHERE ID = " . $SponsID);
		$rep_name = mysql_fetch_assoc($rep_name);
		$rep_name = $rep_name["Name"];

		return $rep_name;
	}



	function get_access_level($SponsID){
		require('DBconnect.php'); //this is needed in every function that uses mySQL
		$rep_access_level = mysql_query("SELECT AccessLevel FROM SponsLogin WHERE SponsID = $SponsID");
		$rep_access_level = mysql_fetch_assoc($rep_access_level);
		$rep_access_level = $rep_access_level["AccessLevel"];

		return $rep_access_level;
	}




	function get_person_sector($SponsID){
		require('DBconnect.php'); //this is needed in every function that uses mySQL
		$rep_sector = mysql_query("SELECT Sector FROM SponsRep WHERE SponsID = $SponsID");
		if (mysql_num_rows($rep_sector) == 0)//i.e. you don't find the person with that SponsID in the SponsRep table.
		{
			$rep_sector = mysql_query("SELECT Sector FROM SectorHead WHERE SponsID = $SponsID");
		} //look in the SectorHead table.

		$rep_sector = mysql_fetch_assoc($rep_sector);
		$rep_sector = $rep_sector["Sector"];

		return $rep_sector;
	}



	function get_earning_report($SponsID){
		require('DBconnect.php'); //this is needed in every function that uses mySQL
		$rep_name = get_person_name($SponsID);
		$rep_amount = 0;
		$rep_num_companies = 0; //gets nummber of companies signed by that spons rep
		$rep_amount_query = "SELECT Amount FROM AccountLog WHERE SponsID = $SponsID";
		$result = mysql_query($rep_amount_query);
		if (mysql_num_rows($result) > 0){
			while ($row = mysql_fetch_assoc($result)){
				$rep_num_companies++;
				$rep_amount = $rep_amount + $row["Amount"];
			}
		}

		return array($rep_name, $rep_num_companies, $rep_amount);
	}




	function get_meeting_report($SponsID){
		require('DBconnect.php'); //this is needed in every function that uses mySQL
		$rep_name = get_person_name($SponsID);
		$rep_calls = 0;
		$rep_emails = 0;
		$rep_meetings = 0;
		$rep_meeting_query = "SELECT * FROM Meeting WHERE SponsID = $SponsID";
		$result = mysql_query($rep_meeting_query);
		if (mysql_num_rows($result) > 0){
			while ($row = mysql_fetch_assoc($result)){
				switch ($row["MeetingType"]){
					case "Call":
						$rep_calls++;
						break;
					case "Email":
						$rep_emails++;
						break;
					case "Meet":
						$rep_meetings++;
						break;

				}
			}
		}

		return array($rep_name, $rep_calls, $rep_meetings, $rep_emails);
	}



	function get_sector_details($SponsSector){
		require('DBconnect.php'); //this is needed in every function that uses mySQL
		$num_spons_reps = 0;
		$num_sector_heads = 0;
		$num_companies_in_sector = 0;
		$total_companies_signed = 0;
		$total_earned = 0;
		$max_earned = 0;
		$max_earner_ID = -1;

		$Sector_SR_query = "SELECT * FROM SponsRep WHERE Sector = '$SponsSector'";
		$result = mysql_query($Sector_SR_query);

		if (mysql_num_rows($result) > 0){
			while ($row = mysql_fetch_assoc($result)){
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
		$result = mysql_query($Sector_SH_query);
		if (mysql_num_rows($result) > 0){
			while ($row = mysql_fetch_assoc($result)) $num_sector_heads++;
		}

		$Sector_CMP_query = "SELECT * FROM Company WHERE Sector='$SponsSector'";
		$result = mysql_query($Sector_CMP_query);
		if (mysql_num_rows($result) > 0){
			while ($row = mysql_fetch_assoc($result)) $num_companies_in_sector++;
		}

		return array("num_spons_reps" => $num_spons_reps, "num_sector_heads" => $num_sector_heads, "num_companies_in_sector" => $num_companies_in_sector, "total_companies_signed" => $total_companies_signed, "total_earned" => $total_earned, "max_earner_ID" => $max_earner_ID, "max_earned" => $max_earned);
	}


	function select_single_column_from_table($column_name, $table_name, $where_params=NULL){
		$out_list = [];

		$single_col_select_query = "SELECT DISTINCT $column_name FROM $table_name";
		if($where_params)
			$single_col_select_query.= " WHERE $where_params;";
		$single_col_select_query .= ";";

//		echo $single_col_select_query;
		$result = mysql_query($single_col_select_query);
		if (mysql_num_rows($result) > 0){
			while ($row = mysql_fetch_assoc($result))
				array_push($out_list, $row[$column_name]);
		}
		return $out_list;
	}

	/*
	function print_table($result){ //array of arritubtes and corresponding sql result we get from querying the attributes
		echo '<table style=\"width:100%\" class="output_table">';
		echo "<tr>";
		$i=0;
		//$attributes_info = mysql_fetch_field($result, $i); //gets a lot of data about the attributes...their names, their types etc.
		while($i < mysql_num_fields($result)) {
			$attr=mysql_fetch_field($result, $i);
			echo "<th>".$attr->name."</th>";
			$i++;
		}

		while($row=mysql_fetch_assoc($result)){
			echo '<tr>';
			foreach ($row as $key => $value) {
				echo '<td>'.$value.'</td>';
			}
			echo "</tr>";
		}
		echo "</table>";
	}
	*/

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



	abstract class FormMethod extends BasicEnum{
		const GET = "GET";
		const POST = "POST";
	}



	abstract class TransType extends BasicEnum{
		const Deposit = "Deposit";
		const Withdraw = "Withdraw";
	}



	abstract class SQLTables extends BasicEnum{
		const Event = "Event";
		const SponsLogin = "SponsLogin";

		const SponsRep = "SponsRep";
		const SectorHead = "SectorHead";

		const AccountLog = "AccountLog";

		const Company = "Company";
		const CompanyExec = "CompanyExec";
		const Meeting = "Meeting";
	}



	abstract class QueryTypes extends BasicEnum{
		const Insert = "Insert";
		const Modify = "Modify";
		const Delete = "Delete";
		const View = "View";
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

	abstract class QueryFormSessionEnums extends BasicEnum{
		const QueryTypeName = "QueryType";
		const InputTypeName = "InputType";
	}

	abstract class CompanyStatus extends BasicEnum{
		const NotCalled = "Not Called";
		const PendingReply = "Pending reply";
		const NotInterested = "Not Interested";
		const AlreadyASponsor = "Already a sponsor";
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
		const SponsCompanyExecPosition = "SponsCompanyExecPosition";
		const SponsMeetingAddress = "SponsMeetingAddress";
		const SponsMeetingType = "SponsMeetingType";
		const SponsMeetingOutcome = "SponsMeetingOutcome";
		const SponsMeetingEntryID = "SponsMeetingEntryID";
		const CSOSector = "All";
		const Submit = "Submit";



		static $TableToFieldNameOrdering = [ //used to specify ordering in forms
			SQLTables::AccountLog => [
				QueryFieldNames::SponsFestival,
				QueryFieldNames::SponsAccountLogEntryID,
				QueryFieldNames::SponsTransType,
				QueryFieldNames::SponsID,
				QueryFieldNames::SponsSector,
				QueryFieldNames::SponsCompany,
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
				QueryFieldNames::SponsCompanyExec,
				QueryFieldNames::SponsEmail,
				QueryFieldNames::SponsMobile,
				QueryFieldNames::SponsCompanyExecPosition,
				QueryFieldNames::Submit
			],

			SQLTables::Meeting => [
				QueryFieldNames::SponsFestival,
				QueryFieldNames::SponsID,
				QueryFieldNames::SponsMeetingEntryID,
				QueryFieldNames::SponsCompany,
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


	abstract class PredefinedQueryInputFields extends BasicEnum{
		public static function get($queryFieldName){
			switch($queryFieldName){

				case QueryFieldNames::SponsFestival :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value = $_SESSION[SessionEnums::UserFestival],
						$disabled = true, $inputCSSClass = NULL,
						$labelText = "Festival", $labelCSSClass = NULL
					);
					break;

				case QueryFieldNames::SponsSector :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsSector,
						$value = ($_SESSION[SessionEnums::UserAccessLevel] == UserTypes::CSO ? "" : $_SESSION[SessionEnums::UserSector] ),
						$disabled = ($_SESSION[SessionEnums::UserAccessLevel] == UserTypes::CSO ? false : true ), $inputCSSClass = NULL,
						$labelText = "Sector", $labelCSSClass = NULL,
						$inputDataListID="SectorInDB",
						$inputDataList=select_single_column_from_table("Sector", "Company")
					);
					break;


				case QueryFieldNames::SponsID :
					return new InputField(
						$inputType = InputTypes::number, $name = QueryFieldNames::SponsID, $value = $_SESSION[SessionEnums::UserLoginID], $disabled = true, $inputCSSClass = NULL,
						$labelText = "My ID", $labelCSSClass = NULL
					);
					break;


				case QueryFieldNames::SponsOthersID :
					return new InputField(
						$inputType = InputTypes::number, $name = QueryFieldNames::SponsOthersID, $value = "", $disabled = false, $inputCSSClass = NULL,
						$labelText = "Person's ID", $labelCSSClass = NULL
					);
					break;


				case QueryFieldNames::SponsName :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsName, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Name",
						$labelCSSClass = NULL, $inputDataListID="NameInDB",
						$inputDataList=select_single_column_from_table("Name", "CommitteeMember")
					);
					break;


				case QueryFieldNames::SponsPassword :
					return new InputField(
						$inputType = InputTypes::password, $name = QueryFieldNames::SponsPassword, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Password",
						$labelCSSClass = NULL
					);
					break;


				case QueryFieldNames::SponsRePassword :
					return new InputField(
						$inputType = InputTypes::password, $name = QueryFieldNames::SponsRePassword, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Re-enter Password",
						$labelCSSClass = NULL
					);
					break;


				case QueryFieldNames::SponsEmail :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsEmail, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Email",
						$labelCSSClass = NULL, $inputDataListID="EmailInDB", $inputDataList=select_single_column_from_table("Email", "CommitteeMember")
					);
					break;


				case QueryFieldNames::SponsMobile :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsMobile, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Mobile",
						$labelCSSClass = NULL, $inputDataListID="MobileInDB", $inputDataList=select_single_column_from_table("Mobile", "CommitteeMember")
					);
					break;


				case QueryFieldNames::SponsYear :
					return new InputField(
					$inputType = InputTypes::number, $name = QueryFieldNames::SponsYear, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Year", $labelCSSClass = NULL, $inputDataListID="YearEnum", $inputDataList=["1","2","3","4"]
					);
					break;


				case QueryFieldNames::SponsBranch :
					return new InputField(
					$inputType = InputTypes::text, $name = QueryFieldNames::SponsBranch, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Branch",
					$labelCSSClass = NULL, $inputDataListID="BranchInDB", $inputDataList=select_single_column_from_table("Branch", "CommitteeMember")
					);
					break;


				case QueryFieldNames::SponsCompany :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $disabled = false, $inputCSSClass = NULL,
						$labelText = "Company Name", $labelCSSClass = NULL, $inputDataListID="CompanyInDB",
						$inputDataList= ($_SESSION[SessionEnums::UserAccessLevel] == UserTypes::CSO ? select_single_column_from_table("CMPName", "Company") : select_single_column_from_table("CMPName", "Company", "Sector = \"".$_SESSION[SessionEnums::UserSector]."\""))
					);
					break;


				case QueryFieldNames::SponsTransType :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsTransType, $value = TransType::Deposit, $disabled = true,
						$inputCSSClass = NULL, $labelText = "Transaction Type", $labelCSSClass = NULL
					);
					break;


				case QueryFieldNames::SponsDate :
					return new InputField(
						$inputType = InputTypes::date, $name = QueryFieldNames::SponsDate, $value = "", $disabled = false, $inputCSSClass = NULL,
						$labelText = "Date", $labelCSSClass = NULL, $inputDataListID="TodaysDate",
						$inputDataList=[date('Y-m-d', time())]
					);
					break;


				case QueryFieldNames::SponsTime :
					return new InputField(
						$inputType = InputTypes::time, $name = QueryFieldNames::SponsTime, $value = "", $disabled = false, $inputCSSClass = NULL,
						$labelText = "Time", $labelCSSClass = NULL
					);
					break;


				case QueryFieldNames::SponsAmount :
					return new InputField(
						$inputType = InputTypes::number, $name = QueryFieldNames::SponsAmount, $value = "", $disabled = false,
						$inputCSSClass = NULL, $labelText = "Amount (Rs.)", $labelCSSClass = NULL
					);
					break;


				case QueryFieldNames::SponsAccountLogEntryID :
					return new InputField(
						$inputType = InputTypes::number, $name = QueryFieldNames::SponsAccountLogEntryID, $value = "", $disabled = false,
						$inputCSSClass = NULL, $labelText = "Account Transaction ID", $labelCSSClass = NULL
					);
					break;


				case QueryFieldNames::SponsCompanyStatus :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyStatus, $value = "", $disabled = false, $inputCSSClass = NULL,
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
						$inputType = InputTypes::textarea, $name = QueryFieldNames::SponsCompanyAddress, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Company Address",
						$labelCSSClass = NULL, $inputDataListID="CompanyAddressInDB",
						$inputDataList= ($_SESSION[SessionEnums::UserAccessLevel] == UserTypes::CSO ? select_single_column_from_table("CMPAddress", "Company") : select_single_column_from_table("CMPAddress", "Company", "Sector = \"".$_SESSION[SessionEnums::UserSector]."\""))
					);
					break;


				case QueryFieldNames::SponsCompanyExec :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExec, $value = "", $disabled = false, $inputCSSClass = NULL,
						$labelText = "Company Exec. Name", $labelCSSClass = NULL, $inputDataListID="CompanyExecInDB",
						$inputDataList= ($_SESSION[SessionEnums::UserAccessLevel] == UserTypes::CSO ? select_single_column_from_table("CEName", "CompanyExec") : select_single_column_from_table("CEName", "Company INNER JOIN CompanyExec ON (Company.CMPName = CompanyExec.CMPName)", "Sector = \"".$_SESSION[SessionEnums::UserSector]."\""))
					);
					break;


				case QueryFieldNames::SponsCompanyExecPosition :
					return new InputField(
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExecPosition, $value = "", $disabled = false, $inputCSSClass = NULL,
						$labelText = "Exec. Position", $labelCSSClass = NULL, $inputDataListID="CompanyExecPositionInDB",
						$inputDataList= select_single_column_from_table("CEPosition", "CompanyExec")
					);
					break;


				case QueryFieldNames::SponsMeetingAddress :
					return new InputField(
						$inputType = InputTypes::textarea, $name = QueryFieldNames::SponsMeetingAddress, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Meeting Address",
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
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsMeetingOutcome, $value = "(Update after meeting)", $disabled = true, $inputCSSClass = NULL, $labelText = "Meeting Outcome",
						$labelCSSClass = NULL, $inputDataListID="MeetingOutcomeEnum", $inputDataList= MeetingOutcomes::getConstants()
					);
					break;


				case QueryFieldNames::SponsMeetingEntryID :
					return new InputField(
						$inputType = InputTypes::number, $name = QueryFieldNames::SponsMeetingEntryID, $value ="",
						$disabled = false, $inputCSSClass = NULL,
						$labelText = "Meeting ID", $labelCSSClass = NULL
					);
					break;


				case QueryFieldNames::Submit :
					return new InputField(
						$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $disabled = false, $inputCSSClass = "query_forms"
					);
					break;

			}

			return new InputField(NULL, NULL);
		}


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
			SQLTables::Meeting => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::View] //Can only view for own sector, and only modify own.
		];

		static $SponsRepAuth = [
			SQLTables::Event => [QueryTypes::View],		//Can only view own details.
			SQLTables::SponsLogin => [QueryTypes::Modify, QueryTypes::View],	//Can only view and modify own password
			SQLTables::SponsRep => [QueryTypes::View],
			SQLTables::SectorHead => [],
			SQLTables::AccountLog => [QueryTypes::View],	//can only view own sponsorships
			SQLTables::Company => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			SQLTables::CompanyExec => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			SQLTables::Meeting => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::View] //Can only view for own sector, and only modify own.
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
					$this->HTMLQueryForm->rearrangeFields(QueryFieldNames::$TableToFieldNameOrdering[$this->tableName]);
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
			switch($this->queryType){
				case QueryTypes::Insert :
					return new HTMLForm(
						$formName = $this->tableName.$this->queryType, $formAction = "view_table.php", $formMethod = FormMethod::POST,
						$fields = array(
							PredefinedQueryInputFields::get(QueryFieldNames::SponsFestival),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsSector),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsRole, $value = UserTypes::SponsRep, $disabled = true, $inputCSSClass = NULL,
								$labelText = "Role", $labelCSSClass = NULL
							),
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
								$disabled = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsSector, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Sector", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsRole, $value = UserTypes::SponsRep, $disabled = true, $inputCSSClass = NULL,
								$labelText = "Role", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsID, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Reg. ID of SponsRep", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsName, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Name",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::password, $name = QueryFieldNames::SponsPassword, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Password",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::password, $name = QueryFieldNames::SponsRePassword, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Re-enter Password",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsEmail, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Email",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsMobile, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Mobile",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsYear, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Year",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsBranch, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Branch",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $disabled = false, $inputCSSClass = "query_forms"
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
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsRole, $value = UserTypes::SponsRep, $disabled = true, $inputCSSClass = NULL,
								$labelText = "Role", $labelCSSClass = NULL
							),
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
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value = $_SESSION[SessionEnums::UserFestival], $disabled = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsSector, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Sector", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsRole, $value = UserTypes::SponsRep, $disabled = true, $inputCSSClass = NULL,
								$labelText = "Role", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsID, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Reg. ID", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsName, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Name",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsEmail, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Email",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsMobile, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Mobile",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsYear, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Year",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsBranch, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Branch",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $disabled = false, $inputCSSClass = "query_forms"
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
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsRole, $value = UserTypes::SponsRep, $disabled = true, $inputCSSClass = NULL,
								$labelText = "Role", $labelCSSClass = NULL
							),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsOthersID),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsName),
							PredefinedQueryInputFields::get(QueryFieldNames::Submit)
							/*
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value = $_SESSION[SessionEnums::UserFestival], $disabled = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsRole, $value = UserTypes::SponsRep, $disabled = true, $inputCSSClass = NULL,
								$labelText = "Role", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsID, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Reg. ID", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsName, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Name",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $disabled = false, $inputCSSClass = "query_forms"
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
			$CSOSectorHeadHTMLForm->fields[QueryFieldNames::SponsOthersID]->labelText = "Reg. ID of Sector Head";
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
//			$CSOSectorHeadHTMLForm->removeField(QueryFieldNames::SponsRole);
//			$CSOSectorHeadHTMLForm->addField(
//					new InputField(
//								$inputType = InputTypes::text, $name = QueryFieldNames::SponsRole, $value = UserTypes::SectorHead, $disabled = true, $inputCSSClass = NULL,
//								$labelText = "Role", $labelCSSClass = NULL
//					)
//			);

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
								$disabled = true, $inputCSSClass = NULL, $labelText = "Festival", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsTransType, $value = TransType::Deposit, $disabled = true,
								$inputCSSClass = NULL, $labelText = "Transaction Type", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsID, $value = $_SESSION[SessionEnums::UserLoginID], $disabled = true, $inputCSSClass = NULL,
								$labelText = "Reg. ID", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::date, $name = QueryFieldNames::SponsDate, $value = date('Y-m-d', time()), $disabled = false, $inputCSSClass = NULL,
								$labelText = "Date of Transaction", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsAmount, $value = "", $disabled = false,
								$inputCSSClass = NULL, $labelText = "Amount (Rs.)", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $disabled = false, $inputCSSClass = "query_forms"
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
								$disabled = true, $inputCSSClass = NULL, $labelText = "Festival", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsAccountLogEntryID, $value = "", $disabled = false,
								$inputCSSClass = NULL, $labelText = "ID of transaction", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsID, $value = $_SESSION[SessionEnums::UserLoginID], $disabled = true, $inputCSSClass = NULL,
								$labelText = "Reg. ID", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::date, $name = QueryFieldNames::SponsDate, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Date of Transaction", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsAmount, $value = "", $disabled = false,
								$inputCSSClass = NULL, $labelText = "Amount (Rs.)", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $disabled = false, $inputCSSClass = "query_forms"
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
								$disabled = true, $inputCSSClass = NULL, $labelText = "Festival", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsAccountLogEntryID, $value = "", $disabled = false,
								$inputCSSClass = NULL, $labelText = "ID of transaction", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							), new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $disabled = false, $inputCSSClass = "query_forms"
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
								$disabled = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsSector, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Sector", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyStatus, $value = CompanyStatus::NotCalled, $disabled = true, $inputCSSClass = NULL,
								$labelText = "Status", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::textarea, $name = QueryFieldNames::SponsCompanyAddress, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Company Address",
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
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $disabled = false, $inputCSSClass = "query_forms"
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
								$disabled = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsSector, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Sector", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyStatus, $value = CompanyStatus::NotCalled, $disabled = true, $inputCSSClass = NULL,
								$labelText = "Status", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::textarea, $name = QueryFieldNames::SponsCompanyAddress, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Company Address",
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
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $disabled = false, $inputCSSClass = "query_forms"
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
								$disabled = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $disabled = false, $inputCSSClass = "query_forms"
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
							PredefinedQueryInputFields::get(QueryFieldNames::SponsEmail),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsMobile),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompanyExecPosition),
							PredefinedQueryInputFields::get(QueryFieldNames::Submit)
							/*
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value = $_SESSION[SessionEnums::UserFestival],
								$disabled = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExec, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Company Exec. Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsEmail, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Exec. Email", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsMobile, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Exec. Mobile", $labelCSSClass = NULL
							),

							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExecPosition, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Exec. Position", $labelCSSClass = NULL
							),

							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $disabled = false, $inputCSSClass = "query_forms"
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
							PredefinedQueryInputFields::get(QueryFieldNames::SponsEmail),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsMobile),
							PredefinedQueryInputFields::get(QueryFieldNames::SponsCompanyExecPosition),
							PredefinedQueryInputFields::get(QueryFieldNames::Submit)
							/*
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value = $_SESSION[SessionEnums::UserFestival],
								$disabled = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExec, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Company Exec. Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsEmail, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Exec. Email", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsMobile, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Exec. Mobile", $labelCSSClass = NULL
							),

							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExecPosition, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Exec. Position", $labelCSSClass = NULL
							),

							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $disabled = false, $inputCSSClass = "query_forms"
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
								$disabled = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExec, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Company Exec. Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $disabled = false, $inputCSSClass = "query_forms"
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
								$disabled = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsID, $value = $_SESSION[SessionEnums::UserLoginID], $disabled = true, $inputCSSClass = NULL,
								$labelText = "Reg. ID", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExec, $value = "", $disabled = false, $inputCSSClass = NULL,
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
								$inputType = InputTypes::textarea, $name = QueryFieldNames::SponsMeetingAddress, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Meeting Address",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsMeetingOutcome, $value = "(Update after meeting)", $disabled = true, $inputCSSClass = NULL, $labelText = "Meeting Outcome",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $disabled = false, $inputCSSClass = "query_forms"
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
								$disabled = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsID, $value = $_SESSION[SessionEnums::UserLoginID], $disabled = true, $inputCSSClass = NULL,
								$labelText = "Reg. ID", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsMeetingEntryID, $value ="",
								$disabled = false, $inputCSSClass = NULL,
								$labelText = "Meeting ID", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompany, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Company Name", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsCompanyExec, $value = "", $disabled = false, $inputCSSClass = NULL,
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
								$inputType = InputTypes::textarea, $name = QueryFieldNames::SponsMeetingAddress, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Meeting Address",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsMeetingOutcome, $value = "(Update after meeting)", $disabled = true, $inputCSSClass = NULL, $labelText = "Meeting Outcome",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $disabled = false, $inputCSSClass = "query_forms"
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
								$disabled = true, $inputCSSClass = NULL,
								$labelText = "Festival", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::number, $name = QueryFieldNames::SponsID, $value = $_SESSION[SessionEnums::UserLoginID], $disabled = true, $inputCSSClass = NULL,
								$labelText = "Reg. ID", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsMeetingEntryID, $value ="",
								$disabled = false, $inputCSSClass = NULL,
								$labelText = "Meeting ID", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = QueryFieldNames::Submit, $value = QueryFieldNames::Submit, $disabled = false, $inputCSSClass = "query_forms"
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
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsSector, $value = $_SESSION[SessionEnums::UserSector], $disabled = true, $inputCSSClass = NULL,
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
						$inputType = InputTypes::text, $name = QueryFieldNames::SponsSector, $value = $_SESSION[SessionEnums::UserSector], $disabled = true, $inputCSSClass = NULL,
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
			return NULL;
		}
		function parseSectorHeadCompanyExecQuery(){
			/*For reference:
				SQLTables::CompanyExec => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			*/
			return NULL;
		}
		function parseSectorHeadMeetingQuery(){
			/*For reference:
				SQLTables::Meeting => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::View] //Can only view for own sector, and only modify own.
			*/
			return NULL;
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
			return NULL;
		}
		function parseSponsRepCompanyExecQuery(){
			/*For reference:
				SQLTables::CompanyExec => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			*/
			return NULL;
		}
		function parseSponsRepMeetingQuery(){
			/*For reference:
				SQLTables::Meeting => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::View] //Can only view for own sector, and only modify own.
			*/
			return NULL;
		}





		function rearrangeFields($orderedFieldNames){
			if($this->HTMLQueryForm != NULL){
				$this->HTMLQueryForm->rearrangeFields($orderedFieldNames);
			}
		}

		function extractFromGET(){
			if($this->isValidForm){
				foreach($this->HTMLQueryForm->fields as $fieldName => $inputField){
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






	/*##------------------------------------------------TESTS------------------------------------------------##
	echo new HTMLForm(
		$formName = "SponsRepInsert", $formAction = "view_table.php", $formMethod = FormMethod::POST,
		$fields = array(
			new InputField(
				$inputType = InputTypes::text, $name = "TransType", $value = TransType::Deposit, $disabled = true, $inputCSSClass = NULL,
				$labelText = "Transaction Type", $labelCSSClass = NULL
			),
			new InputField(
				$inputType = InputTypes::text, $name = "CMPName", $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Company Name",
				$labelCSSClass = NULL
			),
			new InputField(
				$inputType = InputTypes::text, $name = "Amount", $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Amount",
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
				$inputType = InputTypes::submit, $name = "Submit", $value = "Submit", $disabled = false, $inputCSSClass = "query_forms"
			)
		),
		$formCSSClass=NULL,
		$title = "Insert details of sponsorship received",
		$fieldSeparator = "<br>"
	);

	echo $_SESSION[SessionEnums::UserLoginID];
*/

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


/*
	echo new InputField(
		$inputType = InputTypes::text, $name = QueryFieldNames::SponsSector, $value ="", $disabled = false, $inputCSSClass = NULL,
		$labelText = "Company Sector", $labelCSSClass = NULL, $inputDataListID="SectorsInDB", $inputDataList=select_single_column_from_table("CMPName", "Company")
	);
*/


	/*##---------------------------------------------END OF TESTS---------------------------------------------##*/

?>