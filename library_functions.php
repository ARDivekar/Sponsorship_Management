<?php

	include_once "Authorization.php";

	function echo_1d_array($array, $arrayName = "1D-Array"){
		if(!$array)
			return;

		echo "<hr>$arrayName: ";
		foreach($array as $key=>$value)
			echo "<br>$key => $value";
		echo "<hr>";
	}


	function echo_2d_array($array, $arrayName = "2D-Array"){
		if(!$array)
			return;

		echo "<hr>$arrayName: ";
		foreach($array as $key1=>$value1){
			echo "<br><br><br>$key1 => ";
			foreach($value1 as $key2=>$value2){
				echo "<br>$key2 => $value2";
			}
		}
		echo "<hr>";
	}


	function getCurrentDate(){ //returns date in MySQL format.
		return date("Y-m-d");
	}

	function getCurrentDateTime(){ //returns date and time in MySQL format.
		return date("Y-m-d H:i:s");
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



	function print_table($result){ //array of attributes and corresponding sql result we get from querying the attributes
		echo '<div align="center">';
		echo '<table align="center" class="output_table">';
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


	function make_simple_table($result, $table_classes=NULL, $table_id=NULL){
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


	function generate_table_button($tableName){
		if(!SQLTables::isValidValue($tableName))
			return NULL;

		$out = "";

		foreach(QueryTypes::getConstants() as $queryType){
			if ($queryType == QueryTypes::View){
				continue;
			}

			if (Authorization::checkValidAuthorization($_SESSION[SessionEnums::UserAccessLevel], $tableName, $queryType)){
				$CSSClass = "";
				$linkText = "";
				switch ($queryType){
					case QueryTypes::Insert:
						$CSSClass .= "glyphicon glyphicon-plus";
						$linkText .= "Add ";
						break;
					case QueryTypes::Modify:
						$CSSClass .= "glyphicon glyphicon-pencil";
						$linkText .= "Edit ";
						break;
					case QueryTypes::Delete:
						$CSSClass .= "glyphicon glyphicon-remove";
						$linkText .= "Remove ";
						break;
				}

				switch ($tableName){
					case SQLTables::SectorHead:
						$linkText .= "a Sector Head";
						break;
					case SQLTables::SponsRep:
						$linkText .= "Sponsorship Representative";
						break;
					case SQLTables::Meeting:
						$linkText .= "a Meeting";
						break;
					case SQLTables::AccountLog:
						$linkText .= "a Transaction";
						break;
					case SQLTables::Company:
						$linkText .= "a Company";
						break;
					case SQLTables::CompanyExec:
						$linkText .= "a Company Executive";
						break;
				}

				$out .= '<div class="col-md-6">
				<a href="query.php?submit=true&QueryType='.$queryType.'&TableName='.$tableName.'"><h4><i class="'.$CSSClass.'"></i>
				'.$linkText.'</h4></a>
				</div>';

			}
		}

		return $out;

	}


	function echo_QueryResultText(){
		if(isset($_SESSION[QueryExecSessionEnums::QueryResultText])){
			echo $_SESSION[QueryExecSessionEnums::QueryResultText];
		}
		$_SESSION[QueryExecSessionEnums::QueryResultText] = NULL;
	}


?>