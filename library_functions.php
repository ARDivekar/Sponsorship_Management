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



	class InputField{
		var $inputType = NULL;
		var $inputName = NULL;
		var $disabled = NULL;
		var $value = NULL;
		var $inputCSSClass = NULL;
		var $labelText = NULL;
		var $labelCSSClass = NULL;


		function InputField($inputType, $inputName, $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = NULL, $labelCSSClass = NULL){

			if (!InputTypes::isValidValue($inputType)){
				echo "Invalid type passed to constructor of class InputField.";

				return;
			}
			$this->inputType = $inputType;
			$this->inputName = $inputName;
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
					$out .= '<label for="' . $this->inputName . '" class="' . $this->labelCSSClass . '">' . $this->labelText . ':</label> ';
				}
				else $out .= '<label for="' . $this->inputName . '">' . $this->labelText . ':</label> ';
			}

			$out .= '<input type="' . $this->inputType . '" name="' . $this->inputName . '" value="' . $this->value . '" ';
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



	class HTMLForm{
		var $formName = NULL;
		var $formAction = NULL;
		var $formMethod = NULL;
		var $inputFields = NULL;    //an array of InputField objects.
		var $title = NULL;


		function HTMLForm($formName, $formAction, $formMethod, $inputFields, $title = NULL){
			if (!FormMethod::isValidValue($formMethod)){
				echo "Invalid formMethod passed to constructor of class HTMLForm.";

				return;
			}
			$this->formName = $formName;
			$this->formAction = $formAction;
			$this->formMethod = $formMethod;
			foreach ($inputFields as $inputField){
				$this->inputFields[$inputField->inputName] = $inputField;
			}
			$this->title = $title;
		}


		function addInputField($inputField){
			try{
				$this->inputFields[$inputField->inputName] = $inputField;

				return true;
			} catch (Exception $e){
				return false;
			}

		}


		function removeInputField($inputFieldName){
			if (in_array($inputFieldName, $this->inputFields)){
				unset($this->inputFields[array_search($inputFieldName, $this->inputFields)]);

				return true;
			}

			return false;
		}



		function __toString(){
			$out = "";
			$out .= "<h2 align=\"center\">$this->title:</h2>";
			$out .= "<form action= \"$this->formAction\"  method= \"$this->formMethod\" name=\"$this->formName\">";
			foreach ($this->inputFields as $value){
				$out .= $value . "<br>";
			}
			$out .= "</form>";

			return $out;
		}
	}





	/*##------------------------------------------------TESTS------------------------------------------------##
	echo new HTMLForm(
		$formName = "SponsRepInsert", $formAction = "view_table.php", $formMethod = FormMethod::POST, array(new InputField(
		$inputType = InputTypes::text, $inputName = "TransType", $value = TransType::Deposit, $disabled = true, $inputCSSClass = NULL,
		$labelText = "Transaction Type", $labelCSSClass = NULL
	), new InputField(
		$inputType = InputTypes::text, $inputName = "CMPName", $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Company Name",
		$labelCSSClass = NULL
	), new InputField(
		$inputType = InputTypes::text, $inputName = "Amount", $value = "", $disabled = false, $inputCSSClass = NULL, $labelText = "Amount",
		$labelCSSClass = NULL
	), new InputField(
		$inputType = InputTypes::submit, $inputName = "Submit", $value = "Submit", $disabled = false, $inputCSSClass = "query_forms"
	)),

		$title = "Insert details of sponsorship received"
	);

	/*##---------------------------------------------END OF TESTS---------------------------------------------##*/


?>