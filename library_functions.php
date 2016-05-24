<?php



	abstract class BasicEnum{
		private static $constCacheArray = NULL;


		private static function getConstants(){
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


		function InputField($inputType, $name, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = NULL, $labelCSSClass = NULL){

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
		}


		function __toString(){
			$out = "";
			if ($this->labelText){
				if ($this->labelCSSClass){
					$out .= '<label for="' . $this->name . '" class="' . $this->labelCSSClass . '">' . $this->labelText . ':</label> ';
				}
				else $out .= '<label for="' . $this->name . '">' . $this->labelText . ':</label> ';
			}

			$out .= '<input type="' . $this->inputType . '" name="' . $this->name . '" value="' . $this->value . '" ';
			if ($this->disabled){
				$out .= " disabled ";
			}
			if ($this->inputCSSClass){
				$out .= ' class="' . $this->inputCSSClass . '"';
			}
			$out .= '/>';

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

	abstract class QueryFieldNames extends BasicEnum{
		const SponsFestival = "SponsFestival";
		const SponsSector = "SponsSector";
		const SponsRole = "SponsRole";
		const SponsID = "SponsID";
		const SponsName = "SponsName";
		const SponsPassword = "SponsPassword";
		const SponsRePassword = "SponsRePassword";
		const SponsEmail = "SponsEmail";
		const SponsMobile = "SponsMobile";
		const SponsYear = "SponsYear";
		const SponsBranch = "SponsBranch";
	}


	class QueryForm{ // this has all the settings and restrictions we require for the various users.
		var $userType = NULL;
		var $tableName = NULL;
		var $queryType = NULL;
		var $isValidForm = NULL;
		var $HTMLQueryForm = NULL;
		var $UnauthorizedMessage = '<div align="center"><h3 align="center" style="padding: 40px; font-size:28px; line-height:50px;" class="invalid_message">Sorry, you are not permitted to run this query.</h3> </div>';



		var $CSOAuth = [
			SQLTables::Event => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//can only insert an Event, not an Organization
			SQLTables::SponsLogin => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			SQLTables::SponsRep => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			SQLTables::SectorHead => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			SQLTables::AccountLog => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			SQLTables::Company => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			SQLTables::CompanyExec => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			SQLTables::Meeting => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View]
		];

		var $SectorHeadAuth = [
			SQLTables::Event => [],	//empty means no queries allowed
			SQLTables::SponsLogin => [QueryTypes::Modify, QueryTypes::View],	//Can only view and modify own password
			SQLTables::SponsRep => [QueryTypes::Delete],	//Can remove SponsReps from their sector.
			SQLTables::SectorHead => [],
			SQLTables::AccountLog => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//Can only insert, modify, delete, and view for own sector
			SQLTables::Company => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			SQLTables::CompanyExec => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			SQLTables::Meeting => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::View] //Can only view for own sector, and only modify own.
		];

		var $SponsRepAuth = [
			SQLTables::Event => [QueryTypes::View],		//Can only view own details.
			SQLTables::SponsLogin => [QueryTypes::Modify, QueryTypes::View],	//Can only view and modify own password
			SQLTables::SponsRep => [QueryTypes::View],
			SQLTables::SectorHead => [],
			SQLTables::AccountLog => [QueryTypes::View],	//can only view own sponsorships
			SQLTables::Company => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			SQLTables::CompanyExec => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],	//only own sector
			SQLTables::Meeting => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::View] //Can only view for own sector, and only modify own.
		];



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
				if($this->userType == UserTypes::CSO){
					if(in_array($this->queryType, $this->CSOAuth[$this->tableName])){
						$this->HTMLQueryForm = $this->parseCSOQuery();
					} else echo $this->UnauthorizedMessage;

				}
				else if($this->userType == UserTypes::SectorHead){
					if(in_array($this->queryType, $this->SectorHeadAuth[$this->tableName])){
						$this->HTMLQueryForm = $this->parseSectorHeadQuery();
					} else echo $this->UnauthorizedMessage;
				}
				else if($this->userType == UserTypes::SponsorshipRepresentative){
					if(in_array($this->queryType, $this->SponsRepAuth[$this->tableName])){
						$this->HTMLQueryForm = $this->parseSponsRepQuery();
					} else echo $this->UnauthorizedMessage;
				}
			}
		}

		function parseCSOQuery(){
			switch($this->tableName){
				case SQLTables::Event :
					return $this->parseCSOEventQuery();
					break;
				case SQLTables::SponsLogin :
					return $this->parseCSOSponsLoginQuery();
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
					return new HTMLForm(
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
					break;
				case QueryTypes::Modify :
					break;
				case QueryTypes::Delete :
					break;
			}
			return NULL;
		}

		function parseCSOSponsLoginQuery(){
			/*For reference:
				SQLTables::SponsLogin => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			*/
			return NULL;
		}
		function parseCSOSponsRepQuery(){
			/*For reference:
				SQLTables::SponsRep => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			*/
			switch($this->queryType){
				case QueryTypes::Insert :
					return new HTMLForm(
						$formName = "SponsRepInsert", $formAction = "view_table.php", $formMethod = FormMethod::POST,
						$fields = array(
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsFestival, $value ="",
									//$_SESSION[SessionEnums::UserFestival],
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
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsID, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Reg. ID", $labelCSSClass = NULL
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
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsYear, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Year",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsBranch, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Branch",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = "Submit", $value = "Submit", $disabled = false, $inputCSSClass = "query_forms"
							)
						),
						$formCSSClass=NULL,
						$title = "Insert a new ".UserTypes::SponsRep.":",
						$fieldSeparator = "<br>"
					);
					break;
				case QueryTypes::Modify :
					return new HTMLForm(
						$formName = "SponsRepInsert", $formAction = "view_table.php", $formMethod = FormMethod::POST,
						$fields = array(
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
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsID, $value = "", $disabled = false, $inputCSSClass = NULL,
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
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsYear, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Year",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsBranch, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Branch",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = "Submit", $value = "Submit", $disabled = false, $inputCSSClass = "query_forms"
							)
						),
						$formCSSClass=NULL,
						$title = "Modify details of a ".UserTypes::SponsRep.":",
						$fieldSeparator = "<br>"
					);
					break;
				case QueryTypes::Delete :
					return new HTMLForm(
						$formName = "SponsRepInsert", $formAction = "view_table.php", $formMethod = FormMethod::POST,
						$fields = array(
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
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsID, $value = "", $disabled = false, $inputCSSClass = NULL,
								$labelText = "Reg. ID", $labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsName, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Name",
								$labelCSSClass = NULL
							),
							new InputField(
								$inputType = InputTypes::submit, $name = "Submit", $value = "Submit", $disabled = false, $inputCSSClass = "query_forms"
							)
						),
						$formCSSClass=NULL,
						$title = "Completely Remove ".UserTypes::SponsRep.":",
						$fieldSeparator = "<br>"
					);
					break;
			}
			return "EMPT STRING";
		}


		function parseCSOSectorHeadQuery(){
			/*For reference:
				SQLTables::SectorHead => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			*/
			$CSOSectorHeadHTMLForm = $this->parseCSOSponsRepQuery();
			$CSOSectorHeadHTMLForm->removeField(QueryFieldNames::SponsRole);
			$CSOSectorHeadHTMLForm->addField(
					new InputField(
								$inputType = InputTypes::text, $name = QueryFieldNames::SponsRole, $value = UserTypes::SectorHead, $disabled = true, $inputCSSClass = NULL,
								$labelText = "Role", $labelCSSClass = NULL
					)
			);
			return $CSOSectorHeadHTMLForm;
		}
		function parseCSOAccountLogQuery(){
			/*For reference:
				SQLTables::AccountLog => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			*/
			return NULL;
		}
		function parseCSOCompanyQuery(){
			/*For reference:
				SQLTables::Company => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			*/
			return NULL;
		}
		function parseCSOCompanyExecQuery(){
			/*For reference:
				SQLTables::CompanyExec => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View],
			*/
			return NULL;
		}
		function parseCSOMeetingQuery(){
			/*For reference:
				SQLTables::Meeting => [QueryTypes::Insert, QueryTypes::Modify, QueryTypes::Delete, QueryTypes::View]
			*/
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
			return NULL;
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
			return NULL;
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






		function generateForm(){
			$out = "";
			if ($this->isValidForm){

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
*/

	$r = new QueryForm(UserTypes::CSO, SQLTables::SectorHead, QueryTypes::Insert);
	$r->parseQuery();
	echo $r->HTMLQueryForm;

	/*##---------------------------------------------END OF TESTS---------------------------------------------##*/


?>