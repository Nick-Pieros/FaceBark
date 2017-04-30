<?php
/* connect.php -- connect to MySQL and select webuser database
 *
 * Darren Provine, 8 March 2011
 */
// ConnectDB() - takes no arguments, returns database handle
// USAGE: $dbh = ConnectDB();
function RegisterUser($username, $email, $pass, $f_name, $l_name, $dbh)
{
	try {
		$key = sha1($username . $email . "salt");
		$password = sha1("salt" . $pass);
                $query = "CALL RegisterUser(:uname, :pass, :email, :f_name, :l_name, :key)";

                $stmt = $dbh->prepare($query);

		$stmt->bindParam(':uname', $username);
		$stmt->bindParam(':pass', $pass);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':f_name', $f_name);
		$stmt->bindParam(':l_name', $l_name);
		$stmt->bindParam(':key', $key);
                $stmt->execute();

                $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                $howmany = count($result);
                if($howmany == 0)
                {
                        $result = 0;
                }
                else
                {
                        $result = $result[0];
			$result = $result->uid;
                }

	}
	catch (PDOException $e) {
		$dbh->rollback();
		$result = 0;
		die('PDO error registering a new user: ' . $e->getMessage());
	}
	return $result;
}
function LoginUser($username, $pass, $dbh)
{
	try {
		$query = "SELECT user_id " . 
			"FROM Users " . 
			"WHERE (username = :username AND password = :password)";
		$stmt  = $dbh->prepare($query);
		// copy $_POST variable to local variable, Just In Case
		// NOTE: Third argument means binding as an integer.
		// Default is "string", so 3rd arg not needed for strings.
		// (There isn't one for floats, just use string.)
		$stmt->bindValue('username', $username);
		$stmt->bindValue('password', $pass);
		$stmt->execute();
		$result        = $stmt->fetchAll(PDO::FETCH_OBJ);
		$howmany       = count($result);
		$curr_urser_id = 0;
		$curr_user     = $result[0];
		$curr_user_id  = 0;
		if ($howmany == 1) {
			$curr_user_id = $curr_user->user_id;
		}
	}
	catch (PDOException $e) {
		$curr_user_id = -1;
		die('PDO error logging in user: ' . $e->getMessage());
	}
	return $curr_user_id;
}


function DeleteUser($dbh, $user_id)
{
	try {
		$query = "CALL DeleteUser(:uid)";

                $stmt = $dbh->prepare($query);

                $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                $howmany = count($result);
                if($howmany == 0)
                {
                        $result = 0;
                }
                else
                {
                        $result = $result[0];
                        $result = $result->uid;
                }

        }
        catch (PDOException $e) {
                $result = 0;
                die('PDO error deleting user: ' . $e->getMessage());
        }
        return $result;
}

function ValidateUser($dbh, $key) {
	 try {
                $query = "CALL ValidateUser(:key)";

                $stmt = $dbh->prepare($query);

                $stmt->bindParam(':key', $key);
                $stmt->execute();

                $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                $howmany = count($result);
                if($howmany == 0)
                {
                        $result = 0;
                }
                else
                {
                        $result = $result[0];
                        $result = $result->uid;
                }

        }
        catch (PDOException $e) {
                $result = 0;
                die('PDO error validating the user: ' . $e->getMessage());
        }
        return $result;
}

function SendValidationEmail($dbh, $tmp_user_id) {
	try {
		$query = "SELECT tmp_username, tmp_email, tmp_user_key, last_validation_attempt, num_validation_attempts " .
			"FROM Tmp_Users " .
			"WHERE tmp_user_id = :uid";

                	$stmt = $dbh->prepare($query);

                	$stmt->bindParam(':uid', $tmp_user_id, PDO::PARAM_INT);
                	$stmt->execute();

                	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
                	$howmany = count($result);
			if($howmany == 0)
			{
				//user not found
                	        $result = -404;
                	}
                	else
			{
				date_default_timezone_set("America/New_York");
                        	$result = $result[0];
				$last_validation_attempt = strtotime($result->last_validation_attempt);
				$one_hour_ago = time() - 3600;
				$num_validation_attempts = $result->num_validation_attempts;
				$email = $result->tmp_email;
				$username = $result->tmp_username;
				$key = $result->tmp_user_key;
				if($num_validation_attempts >= 5)
				{
					//too many attempts
					$result = -1;
				}
				elseif($last_validation_attempt > $one_hour_ago && $num_validation_attempts > 0)
				{
					//too recent of an attept
					$result = -2;
				}
				else
				{
					$host = "elvis.rowan.edu";
					$site = "FaceBark";
				        $confirmsite = "/~pierosn0/awp/FaceBark/confirmation.php?key=";
				        $myemail = "pierosn0@elvis.rowan.edu";

				        $subject = "$site: Verify Your Account!";
					$headers = "From: $myemail \r\n" .
						   "Reply-To: $myemail \r\n" .
						   'X-Mailer: PHP/' . phpversion();
					$message = "Hi $username, welcome to $site! \r\n\r\n" .
						   "To confirm your username, please click this link:\r\n\r\n".
						   "http://$host$confirmsite$key \r\n" .
						   "(If you did not register at $site, \r\n" .
						   "just ignore this message.)\r\n";
					mail($email, $subject, $message, $headers);

					 $query = "UPDATE Tmp_Users " .
                                        "SET num_validation_attempts = num_validation_attempts + 1, last_validation_attempt = CURRENT_TIMESTAMP() ".
                                        "WHERE tmp_user_id = :uid";
        	                        $stmt = $dbh->prepare($query);
	                                $stmt->bindParam(':uid', $tmp_user_id, PDO::PARAM_INT);
					$stmt->execute();

					$result=1;

				}

			}

			$query = "DELETE FROM Tmp_Users " .
                        	 "WHERE last_validation_attempt <= SUBTIME(CURRENT_TIMESTAMP, '24:00:00')";
                        $stmt = $dbh->prepare($query);
                        $stmt->execute();


        }
        catch (PDOException $e) {
                $result = 0;
                die('PDO error validating the user: ' . $e->getMessage());
	}
	return $result;
}

function SendResetPasswordEmail($dbh, $user_id)	{
        try {
                $query = "SELECT username, email, user_key, last_attempt, num_attempts " .
                        "FROM Users JOIN User_Keys USING(user_id)" .
                        "WHERE user_id = :uid";

                $stmt = $dbh->prepare($query);

                $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                $howmany = count($result);
                if($howmany == 0)
                {
                        //user not found
                        $result = -404;
                }
                else
                {
                        date_default_timezone_set("America/New_York");
                        $result = $result[0];
                        $last_attempt = strtotime($result->last_attempt);
                        $one_hour_ago = time() - 3600;
                        $num_attempts = $result->num_attempts;
                        $email = $result->email;
                        $username = $result->username;
			$key = $result->user_key;
                        if($num_attempts >= 5)
                        {
                                //too many attempts
                                $result = -1;
                        }
                        elseif($last_attempt > $one_hour_ago && $num_attempts > 0)
                        {
                                //too recent of an attept
                                $result = -2;
                        }
                        else
                        {
                                $host = "elvis.rowan.edu";
                                $site = "FaceBark";
                                $confirmsite = "/~pierosn0/awp/FaceBark/reset-pass.php?key=";
                                $myemail = "pierosn0@elvis.rowan.edu";
                                $subject = "$site: Password Reset!";
                                $headers = "From: $myemail \r\n" .
                                           "Reply-To: $myemail \r\n" .
                                           'X-Mailer: PHP/' . phpversion();
                                $message = "Hi $username, we heard you wanted to change your password! \r\n\r\n" .
                                           "To do so, please click this link:\r\n\r\n".
                                           "http://$host$confirmsite$key \r\n" .
					   "(If you did not request this email please consider changing your password \r\n" .
					   "as your account may be under attack ) \r\n";
                                mail($email, $subject, $message, $headers);

                                $query = "UPDATE User_Keys " .
                                	 "SET num_attempts = num_attempts + 1, last_attempt = CURRENT_TIMESTAMP() ".
                                         "WHERE user_id = :uid";
                                $stmt = $dbh->prepare($query);
                                $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
				$stmt->execute();
				//it worked!			
				$result = 1;

                        }

		}
		
		$query = "UPDATE User_Keys " .
                         "SET num_attempts = 0 ".
                         "WHERE last_attempt <= SUBTIME(CURRENT_TIMESTAMP, '24:00:00')";
                $stmt = $dbh->prepare($query);
                $stmt->execute();



        }
        catch (PDOException $e) {
                $result = 0;
                die('PDO error validating the user: ' . $e->getMessage());
	}
	return $result;

}
?>

