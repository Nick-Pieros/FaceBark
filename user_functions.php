<?php
/* user_functions.php -- Provides various user-centric functions 
 *
 * Nick Pieros 3 May 2017
 */

/*
 * register usr function
 * attempts to register a user to the site
 * add them to a tmp tampe until they are validated
 */
function RegisterUser($username, $email, $pass, $f_name, $l_name, $dbh)
{
	
    try
    {
	//hashing a temp key to be used for validation
	$key = sha1($username . $email . "salt");

	//salting and hashing the password
	$pass = sha1("salt" . $pass);

	/*
	 * calling the register user procedure
	 * Does the following:
	 * 	checks to see if username or email is already used in the users table
	 * 		if not, attempt to add to the tmp_users table
	 * 	Delete any tmp users who have been in the table for more than 24hrs
	 * the original plan was to make deleting from the tmp_users table happen as
	 * 	an event, but MySQL on elvis has events dissabled
	 */
	$query = "CALL RegisterUser(:uname, :pass, :email, :f_name, ".
	       	 ":l_name, :key)";

        $stmt = $dbh->prepare($query);

	//binding the different paramater
	$stmt->bindParam(':uname', $username);
	$stmt->bindParam(':pass', $pass);
	$stmt->bindParam(':email', $email);
	$stmt->bindParam(':f_name', $f_name);
	$stmt->bindParam(':l_name', $l_name);
	$stmt->bindParam(':key', $key);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $howmany = count($result);
	//returning 0 if the user wasn't added to the tmp table and their tmp id
	//	if they were
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
/*
 * function to log the user in
 * Input:
 * 	pass:	password
 * 	username:	the username of the user
 */
function LoginUser($username, $pass, $dbh)
{
    try
    {
	//salting and hashing the password
	$pass = sha1("salt" . $pass);
	//attempting to select the user's id from the users table
	$query = "SELECT user_id " .
	 	 "FROM Users " .
		 "WHERE (username = :username AND password = :password " .
		 "AND user_deleted = 0)";
	$stmt  = $dbh->prepare($query);
	//binding params
	$stmt->bindValue('username', $username);
	$stmt->bindValue('password', $pass);
	$stmt->execute();
	$result        = $stmt->fetchAll(PDO::FETCH_OBJ);
	$howmany       = count($result);
	$curr_user     = $result[0];
	//if no users were found in the user's table check the tmp users table
	if ($howmany == 0)
       	{

	    //trying to select the user id from the tmp user table
	    $query = "SELECT tmp_user_id " .
              	     "FROM Tmp_Users " .
                     "WHERE (tmp_username = :username AND tmp_password = :password)";
            $stmt  = $dbh->prepare($query);
       	    $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $pass);
            $stmt->execute();
	    $result        = $stmt->fetchAll(PDO::FETCH_OBJ);
	    $howmany       = count($result);
	    $curr_user     = $result[0];
	    //return null if still nothing was found otherwise return tmp user id
	    if ($howmany == 0) {
              	$curr_user = null;
            }

	}

    }
    catch (PDOException $e) {
        $curr_user = -1;
    	die('PDO error logging in user: ' . $e->getMessage());
    }
    return $curr_user;
}

/*
 * Function for deleteing a user from the website
 * Inputs:
 * user_id	id of the user to delete
 * admin	id of an admin user (optional)
 */
function DeleteUser($dbh, $user_id, $admin)
{
	
    try
    {
	/*
	 * calling the delete users procedure
	 * does the following
	 * 	check that the admin id is either 0 or part of the admin table
	 * 		if it's not, rollback and return 0
	 * 	update the users table and set the seleted users 
	 * 		user_deleted to be 1
	 * 	if that was successful, delete the user's dog
	 * 	update the uploads table and set all of the uploads made by 
	 * 		the user to 1
	 * 	update the comments table
	 * 		set the text to [deleted]
	 * 		set the id to 1
	 * 	delete from the posts table
	 * 		posts_uploads and posts_text deleted automatically
	 * 	delete from the Voted_Posts table. 
	 *	return the deleted user's id
	 */
	$query = "CALL DeleteUser(:uid, :admin)";
        $stmt = $dbh->prepare($query);
	//binding the params
	$stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
	$stmt->bindParam(':admin', $admin, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $howmany = count($result);
	//return 0 if the user couldn't be or wasn't deleted
	//return the user id if they were deleted
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

/*
 * function for validating the user
 * Input:
 * 	$key	the key sent out in the email
 */
function ValidateUser($dbh, $key) {
       
    try
    {
	/*
	 * Calling the Validate User Function
	 * moves the user from the tmp table to the full users table if the key
	 * 	 matches a user in the tmp table
	 * Deletes the user from the tmp_user table
	 * deletes any user in the tmp_user table for over 24hrs
	 */
        $query = "CALL ValidateUser(:key)";

        $stmt = $dbh->prepare($query);

        $stmt->bindParam(':key', $key);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $howmany = count($result);
	//returning the user id if successful and 0 if not
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

/*
 * \function for senind the validation email
 * input:
 * tmp_user_id:		the tmp_user to send the validation email
 */
function SendValidationEmail($dbh, $tmp_user_id) {
    try
    {
	$now = time();
	/*
	 * selecting the username, email, last validation attempt and number 
	 * 	of attempts for the tmp_user
	 */
	$query = "SELECT tmp_username, tmp_email, last_validation_attempt, " .
		 "num_validation_attempts " .
		 "FROM Tmp_Users " .
		 "WHERE tmp_user_id = :uid";

        $stmt = $dbh->prepare($query);
	//binding the tmp_user_id       
	$stmt->bindParam(':uid', $tmp_user_id, PDO::PARAM_INT);
       	$stmt->execute();

       	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
	$howmany = count($result);
	//if no tmp_user exists with the given id return a -404
	if($howmany == 0)
	{
	    //user not found
            $result = -404;
	}
        else
	{

	    //setting the time zone for PHP to america/newyork to deal with
	    //mysql storing the time zone in EST and php's time() function
	    //only working in GMT	
	    date_default_timezone_set("America/New_York");
	    $result = $result[0];
	    //getting the time of last attempt
	    $last_validation_attempt = 
		    strtotime($result->last_validation_attempt);
	    //getting the time one hour ago
	    $one_hour_ago = $now - 3600;
	    //getting the number of validation attempts
	    $num_validation_attempts = $result->num_validation_attempts;
	    //getting the email
	    $email = $result->tmp_email;
	    //getting the username
	    $username = $result->tmp_username;
	    //making the key to use for the current attempt
	    $key = sha1($username . $now . $email . "salt");
	    if($num_validation_attempts >= 5)
	    {
	    	//too many attempts
					$result = -1;
	    }
	    elseif($last_validation_attempt > $one_hour_ago && 
		    $num_validation_attempts > 0)
	    {
	    	//too recent of an attept
	    	$result = -2;
	    }
	    else
	    {
		//setting up the params for sending out the email
	    	$host = "elvis.rowan.edu";
	        $site = "FaceBark";
		$confirmsite = "/~blackc6/awp/FaceBark/confirmation.php?key=";
		$myemail = "pierosn0@elvis.rowan.edu";
		$subject = "$site: Verify Your Account!";
		$headers = "From: $myemail \r\n" .
			   "Reply-To: $myemail \r\n" .
			   'X-Mailer: PHP/' . phpversion();
		$message = "Hi $username, welcome to $site! \r\n\r\n" .
			   "To confirm your username, please click this link:\r\n\r\n".
			   "http://$host$confirmsite$key \r\n" .
			   "(If you did not register at $site, " .
			   "just ignore this message.)\r\n";
		//sending the email
		mail($email, $subject, $message, $headers);
		/*
		 * upsating the tmp user who just got sent an email
		 * updating the key to the newly created key
		 * updating the number of attempts and last attempt
		 */
		$query = "UPDATE Tmp_Users " .
			 "SET num_validation_attempts = " .
			 "num_validation_attempts + 1, " .
			 "last_validation_attempt = CURRENT_TIMESTAMP(), " .
			 "tmp_user_key = :key " .
                         "WHERE tmp_user_id = :uid";
        	$stmt = $dbh->prepare($query);
		//binding the params
		$stmt->bindParam(':uid', $tmp_user_id, PDO::PARAM_INT);
		$stmt->bindParam(':key', $key);
		$stmt->execute();
		$result=1;
	    }

	}

	//deleting tmp_users who have been in the table for at least 24hrs
	$query = "DELETE FROM Tmp_Users " .
		 "WHERE last_validation_attempt <= " .
		 "SUBTIME(CURRENT_TIMESTAMP, '24:00:00')";
        $stmt = $dbh->prepare($query);
        $stmt->execute();


    }
    catch (PDOException $e) {
        $result = 0;
        die('PDO error validating the user: ' . $e->getMessage());
    }
    return $result;
}
/*
 * Function to send reset password links
 * user_id	user's id who requested the password reset
 */
function SendResetPasswordEmail($dbh, $user_id)	{
    try
    {
	//getting the current utc time stamp
	$now = time();

	/*
	 * query to get the user's email, username, 
	 * last attempt and number of attempts
	 * based on the user id provided
	 */
        $query = "SELECT username, email, last_attempt, num_attempts " .
                 "FROM Users JOIN User_Keys USING(user_id)" .
                 "WHERE user_id = :uid AND user_deleted = 0";
        $stmt = $dbh->prepare($query);
	//binding the user_id
	$stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $howmany = count($result);
	//if there's no user return -404
	if($howmany == 0)
        {
             //user not found
             $result = -404;
        }
        else
	{
	    //more or less the same as SendValidationEmail

	    //setting the php time zeon to America/New_York
	    date_default_timezone_set("America/New_York");

            $result = $result[0];
	    //getting the time of the last attempt by the user
	    $last_attempt = strtotime($result->last_attempt);
	    //getting the time one hour in the past
	    $one_hour_ago = time() - 3600;
	    //getting the number of attempts the user has made
	    $num_attempts = $result->num_attempts;
	    //getting the email address of the user
	    $email = $result->email;
	    //getting the username
	    $username = $result->username;
	    //creating the key to email to the user
	    $key = sha1($username . $now . $email . "salt");
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
	         //setting up the paramaters used for emailing
                 $host = "elvis.rowan.edu";
                 $site = "FaceBark";
                 $confirmsite = "/~blackc6/awp/FaceBark/reset-pass.php?key=";
                 $myemail = "pierosn0@elvis.rowan.edu";
                 $subject = "$site: Password Reset!";
                 $headers = "From: $myemail \r\n" .
                            "Reply-To: $myemail \r\n" .
                            'X-Mailer: PHP/' . phpversion();
		 $message = "Hi $username, we heard you wanted to change " . 
			    "your password! \r\n\r\n" .
                            "To do so, please click this link:\r\n\r\n".
                            "http://$host$confirmsite$key \r\n" .
			    "(If you did not request this email please " .
			    "consider changing your password " .
			    "as your account may be under attack ) \r\n";
		 //sending the email to the user 
		 mail($email, $subject, $message, $headers);
		 
		 // query to update the user's key, last attempt time and the 
		 // number of attempts
                 $query = "UPDATE User_Keys " .
		          "SET num_attempts = num_attempts + 1, " . 
			  "last_attempt = CURRENT_TIMESTAMP(), " .
			  "user_key = :key " .
                          "WHERE user_id = :uid";
                 $stmt = $dbh->prepare($query);
		 $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
		 $stmt->bindParam(':key', $key);
		 $stmt->execute();
		 //it worked!
		 $result = 1;

             }

	}

	//resetting everyone's attempts who haven't made an attempt in 24hrs
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

/*
 * function used to reset the user's password
 * input:
 * new_pass:	the new password
 * key:		the key that was sent to the user
 */
function ResetPassword($dbh, $new_pass, $key) {

    try
    {
	//salting the password 
        $new_pass = sha1("salt" . $new_pass);
	// attempting to update the user to add the new password
	// update using the user_id from User_Keys
	// only update if the user is not deleted
	$query = "UPDATE Users  " .
		 "SET password = :new_pass " .
		 "WHERE user_deleted = 0 AND user_id = (SELECT user_id " .
		 "FROM User_Keys " .
		 "WHERE user_key = :key)";
	$stmt = $dbh->prepare($query);
	//binding params
	$stmt->bindParam(':new_pass', $new_pass);
	$stmt->bindParam(':key', $key);
	$stmt->execute();
	$result = $stmt->rowCount();
	if($result > 0)
	{
	    //getting the user_id based on the key provided
            $query = "SELECT user_id " .
	    	     "FROM User_Keys " .
		     "WHERE user_key = :key";
	    $stmt = $dbh->prepare($query);
	    //binding the key
	    $stmt->bindParam(':key', $key);

            $stmt->execute();

	    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
	    //
	    $howmany = count($result);
	    //returning 0 if no response was returned
	    if($howmany == 0)
	    {
	        $result = 0;
	    }
	    else
	    {
		//return the user_id if the user was updated
	        $result = $result[0];
	        $result = $result->user_id;
	    }
	}
	else
	{
	    $result = 0;
	}
    }
    catch (PDOException $e) {
        $result = 0;
        die('PDO error validating the user: ' . $e->getMessage());
    }
    return $result;
}


/*
 * function for sending notification to users who were tagged or 
 * had their comments replied to
 * Inputs:
 * 	user_list:	list of users to send emails to
 * 	type:		where the user was tagged("post" or "comment")
 * 	link:		the link to send to the users
 */
function SendNotification($dbh, $user_list, $type, $link) {
    try
    {
	//size of the list of users
	$size = count($user_list);
	for($i =0; $i<$size; $i++)
	{
	    //grabbing a new user name from the list
	    $username = $user_list[$i];
	    //getting the user's email
            $query = "SELECT email " .
                     "FROM Users " .
                     "WHERE username like :uname AND user_deleted = 0";

            $stmt = $dbh->prepare($query);
	    //binding the username
	    $stmt->bindParam(':uname', $username);
   	    $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
	    $howmany = count($result);
	    //if no result was found set result to 0
            if($howmany == 0)
            {
                //user not found
                $result = 0;
	    }
	    //otherwise continue emailing the user
            else
            {
		$result = $result[0];
		//grabbing the email
		$email = $result->email;
		//setting the email paramaters
                $host = "elvis.rowan.edu";
                $site = "FaceBark";
                $myemail = "pierosn0@elvis.rowan.edu";
                $subject = "$site: Someone Mentioned You!";
                $headers = "From: $myemail \r\n" .
                           "Reply-To: $myemail \r\n" .
                           'X-Mailer: PHP/' . phpversion();
                $message = "Hi $username, You were mentioned in someone's $type! \r\n\r\n" .
                           "why don't you come check it out?:\r\n\r\n".
                           "http://$link \r\n";
		$result = 1;
		//mailing the current user
	        mail($email, $subject, $message, $headers);

	    }
        }


    }
    catch (PDOException $e) {
		$result = 0;
	        die('PDO error sending a notification: ' . $e->getMessage());
    }
        return $result;
}
?>
