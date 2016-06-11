<?php

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


	/*##---------------------------------------------END OF TESTS---------------------------------------------##*/


?>