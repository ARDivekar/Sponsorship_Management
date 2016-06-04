<?php
	include_once "library_functions.php";
	/*Resume old session:*/
	session_start();

	if(!isset($_POST[QueryFieldNames::Submit])){ //can only be set from submitting a form at query.php
		header("Location: home.php");
	}

	if( !Authorization::checkValidAuthorization(
			$_SESSION[SessionEnums::UserAccessLevel],
			$_SESSION[QueryFormSessionEnums::TableName],
			$_SESSION[QueryFormSessionEnums::QueryType]
		)){
		header("Location: home.php");
	}


	class SQLQuery{
		public function surroundWithSingleQuotes($string){
			//Source: http://stackoverflow.com/questions/1555434/php-wrap-a-string-in-double-quotes

			return "'" . trim(
					trim(trim(trim(trim($string), '"'), "'"), '"')
			) . "'"; //This removes __all__ kinds of nested single and double quotes from around a word.

			//	$a = new SQLQuery();
			//	echo $a->surroundWithSingleQuotes("\"'Hello'\"");
			//	echo $a->surroundWithSingleQuotes("'\"Hello\"'");
		}

	}
?>