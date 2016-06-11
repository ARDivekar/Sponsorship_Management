<?php

	function makeRandomString($length = 5, $alphanumeric=true, $lowerCase=false){ //takes from 36-character alphabet & number charset.
		$charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		if($alphanumeric)
			$charset .= "0123456789";
		if($lowerCase)
			$charset .= "abcdefghijklmnopqrstuvwxyz";

		$charsetLen = strlen($charset);
		$out = "";
		for($i=0; $i<$length; $i++)
			$out .= substr($charset, mt_rand(0,$charsetLen-1), 1);
		return $out;
	}


	function makeRandomHexString($length = 6){
		//Source: http://stackoverflow.com/questions/5438760/generate-random-5-characters-string
		$rand = substr(md5(microtime()),rand(0,26),$length);
		return $rand;
	}


	function makeIncrementalString($len = 5){ //Note: incremental means easier to guess; If you're using this as a salt or a verification token, don't. A salt (now) of "WCWyb" means 5 seconds from now it's "WCWyg")

		//Source: http://stackoverflow.com/questions/5438760/generate-random-5-characters-string
		$charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
		$base = strlen($charset);
		$result = '';

		$now = explode(' ', microtime())[1];
		while ($now >= $base){
			$i = $now % $base;
			$result = $charset[$i] . $result;
			$now /= $base;
		}
		return substr($result, -5);
	}


	function getHash($algo="sha1", $string){	//DO NOT USE FOR PASSWORDS

		$algo = strtolower(trim($algo));

		if ($algo == "sha1"){
			return sha1($string, false); 	//FALSE returns the hash as a string of hex numbers.
		}

		if ($algo == "md5"){
			return md5($string, false);		//FALSE returns the hash as a string of hex numbers.
		}
	}



	function generatePasswordHash($password){
		/* 	The salt used MUST be unique for each user, but can be known to everyone (in that it can
		be seen if the database is comprimised. It should be system-generated).
			The purpose of a salt is that it makes lookup-table & rainbow table attacks more difficult
		as it blows up the size of the table exponentially. See these links for	explanations of salts
		and salting:
			http://security.stackexchange.com/q/51959
			http://crypto.stackexchange.com/a/2010
			http://stackoverflow.com/a/401684/4900327
		*/

		//Source for below: http://stackoverflow.com/a/14992543/4900327
		$hashAndSalt = password_hash($password, PASSWORD_BCRYPT, array("cost" => 11)); // "cost" as in computational cost, increasing CPU time exponentially, read https://github.com/ircmaxell/password_compat

		return $hashAndSalt; // Insert $hashAndSalt into database against user

	}


	function checkPasswordHash($enteredPassword, $hashAndSaltFromDB){
		//Source for below: http://stackoverflow.com/a/14992543/4900327
		// Fetch hash+salt from database, place in $hashAndSalt variable and then to verify $enteredPassword:
		if (password_verify($enteredPassword, $hashAndSaltFromDB)){
			return true;
		}
		return false;
	}




	/*##------------------------------------------------TESTS------------------------------------------------##

	echo "<hr>.makeRandomHexString();
	echo "<hr>.makeRandomString(50);


	$unhashedPassword = "lol";
	$timeStart = microtime(TRUE);
	echo "<hr>";
	echo checkPasswordHash($unhashedPassword, generatePasswordHash($unhashedPassword)) ? "true, password works!" : "false, unauthorized";
	$timeEnd = microtime(TRUE);
	echo "<br>Hashing the password '$unhashedPassword' took ". 1000*($timeEnd-$timeStart)." milliseconds";
	echo "<hr>";

	/*##---------------------------------------------END OF TESTS---------------------------------------------##*/