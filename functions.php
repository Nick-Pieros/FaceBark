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

        $dbh->beginTransaction();

        $query = "INSERT INTO Users(username, password, email, f_name, l_name ) " . "VALUES(:u_name, :pass, :e_mail, :f_name, :l_name)";
        $stmt  = $dbh->prepare($query);
        // NOTE: Third argument means binding as an integer.
        // Default is "string", so 3rd arg not needed for strings.
        // (There isn't one for floats, just use string.)
        $stmt->bindValue('u_name', $username);
        $stmt->bindValue('pass', $pass);
        $stmt->bindValue('e_mail', $email);
        $stmt->bindValue('f_name', $f_name);
        $stmt->bindValue('l_name', $l_name);
        $stmt->execute();
        $new_user_id = $dbh->lastInsertId();
        $dbh->commit();
    }
    catch (PDOException $e) {
        $dbh->rollback();
        $new_user_id = -1;
        die('PDO error fetching grade: ' . $e->getMessage());
    }
    return $new_user_id;
}
function LoginUser($username, $pass, $dbh)
{
    try {
        $query = "SELECT user_id " . "FROM Users " . "WHERE (username = :username AND password = :password)";
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
        die('PDO error fetching grade: ' . $e->getMessage());
    }
    return $curr_user_id;
}
function GetRecentPosts($page_num, $user_id, $dbh)
{
    $num_per_page = 8;
    $last_post    = $page_num * $num_per_page;
    $first_post   = ($last_post - $num_per_page);
    try {
        if ($user_id == 0) {
		$query = "SELECT post_id, post_title, post_timestamp, post_votes, " . 
			"username, post_text, file_path, file_name " . 
			"FROM ((((Posts LEFT JOIN Users using(user_id)) " . 
			"LEFT JOIN Posts_Text using (post_id)) " . 
			"LEFT JOIN Posts_Uploads using (post_id)) " . 
			"LEFT JOIN Uploads using (upload_id))" . 
			"ORDER BY post_timestamp DESC " . 
			"LIMIT :first,:num_pages";
            $stmt  = $dbh->prepare($query);
        } else {
		$query = "SELECT post_id, post_title, post_timestamp, post_votes, " . 
			"username, post_text, file_path, file_name " . 
			"FROM ((((Posts LEFT JOIN Users using(user_id)) " . 
			"LEFT JOIN Posts_Text using (post_id)) " . 
			"LEFT JOIN Posts_Uploads using (post_id)) " . 
			"LEFT JOIN Uploads using (upload_id)) " . 
			"WHERE Posts.user_id = :uid " . 
			"ORDER BY post_timestamp DESC " . 
			"LIMIT :first,:num_pages";
            $stmt  = $dbh->prepare($query);
            $stmt->bindValue('uid', $user_id, PDO::PARAM_INT);
        }
        // copy $_POST variable to local variable, Just In Case
        // NOTE: Third argument means binding as an integer.
        // Default is "string", so 3rd arg not needed for strings.
        // (There isn't one for floats, just use string.)
        $stmt->bindValue('first', $first_post, PDO::PARAM_INT);
        $stmt->bindValue('num_pages', $num_per_page, PDO::PARAM_INT);
        $stmt->execute();
        $result  = $stmt->fetchAll(PDO::FETCH_OBJ);
        $howmany = count($result);
        if ($howmany < 1) {
            $result = 0;
        }
    }
    catch (PDOException $e) {
        die('PDO error fetching grade: ' . $e->getMessage());
    }
    return $result;
}
function GetPost($post_id, $dbh)
{
    try {
	    $query = "SELECT post_id, post_title, post_timestamp, post_votes, " . 
		    "username, post_text, file_path, file_name " . 
		    "FROM ((((Posts LEFT JOIN Users using(user_id)) " . 
		    "LEFT JOIN Posts_Text using (post_id)) " . 
		    "LEFT JOIN Posts_Uploads using (post_id)) " . 
		    "LEFT JOIN Uploads using (upload_id)) " . 
		    "WHERE Posts.post_id = :pid ";
        $stmt  = $dbh->prepare($query);
        // NOTE: Third argument means binding as an integer.
        // Default is "string", so 3rd arg not needed for strings.
        // (There isn't one for floats, just use string.)
        $stmt->bindValue('pid', $post_id, PDO::PARAM_INT);
        $stmt->execute();
        $result  = $stmt->fetchAll(PDO::FETCH_OBJ);
        $howmany = count($result);
        if ($howmany < 1) {
            $result = 0;
        }
    }
    catch (PDOException $e) {
        die('PDO error fetching grade: ' . $e->getMessage());
    }
    return $result;
}



function GetDogInfo($user_id, $dbh)
{
    try {
	    $query = "SELECT dog_name, dog_breed, dog_weight, dog_bio, file_name, file_path, file_type " . 
		    "FROM Dogs LEFT JOIN Uploads using(upload_id)" . 
		    "WHERE Dogs.user_id = :uid";
        $stmt  = $dbh->prepare($query);
        // copy $_POST variable to local variable, Just In Case
        // NOTE: Third argument means binding as an integer.
        // Default is "string", so 3rd arg not needed for strings.
        // (There isn't one for floats, just use string.)
        $stmt->bindValue('uid', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    catch (PDOException $e) {
        die('PDO error fetching grade: ' . $e->getMessage());
    }
    return $result;
}
function GetUserInfo($user_id, $dbh)
{
    try {
	    $query = "SELECT * " . 
		    "FROM Users " . 
		    "WHERE user_id = :uid";
        $stmt  = $dbh->prepare($query);
        // copy $_POST variable to local variable, Just In Case
        // NOTE: Third argument means binding as an integer.
        // Default is "string", so 3rd arg not needed for strings.
        // (There isn't one for floats, just use string.)
        $stmt->bindValue('uid', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $howmany = count($result);
        if ($howmany = 0) {
            $result = 0;
        }
    }
    catch (PDOException $e) {
        die('PDO error fetching grade: ' . $e->getMessage());
    }
    return $result;
}
//TODO make this take an array instead of a signle value
function GetPostUpload($post_id, $dbh)
{
    try {
	    $query = "SELECT * " . 
		    "FROM Uploads " . 
		    "WHERE upload_id in( " . 
		    "SELECT upload_id " . 
		    "FROM Posts_Uploads " . 
		    "WHERE post_id = :pid) ";
        $stmt  = $dbh->prepare($query);
        // copy $_POST variable to local variable, Just In Case
        // NOTE: Third argument means binding as an integer.
        // Default is "string", so 3rd arg not needed for strings.
        // (There isn't one for floats, just use string.)
        $stmt->bindValue('pid', $post_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    catch (PDOException $e) {
        die('PDO error fetching grade: ' . $e->getMessage());
    }
    return $result;
}
//TODO make this take an array instead of a signle value
function GetPostText($post_id, $dbh)
{
    try {
        $query = "SELECT post_text " . "FROM  Posts_Text " . "WHERE post_id = :pid ";
        $stmt  = $dbh->prepare($query);
        // copy $_POST variable to local variable, Just In Case
        // NOTE: Third argument means binding as an integer.
        // Default is "string", so 3rd arg not needed for strings.
        // (There isn't one for floats, just use string.)
        $stmt->bindValue('pid', $post_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    catch (PDOException $e) {
        die('PDO error fetching grade: ' . $e->getMessage());
    }
    return $result;
}

function CreateDogInfo($dbh, $name, $breed, $weight, $bio, $user_id, $upload_id) {

	try {
		$dbh->beginTransaction();
		if($upload_id == 0)
		{
			
			$query = "INSERT into Dogs(dog_name, dog_breed, dog_weight, dog_bio, user_id) " .
				"VALUES(:name, :breed, :weight, :bio, :uid)";
			$stmt  = $dbh->prepare($query);
		}
		else
		{
			 $query = "INSERT into Dogs(dog_name, dog_breed, dog_weight, dog_bio, user_id, upload_id) " .
				 "VALUES(:name, :breed, :weight, :bio, :uid, :upid)";
			 $stmt  = $dbh->prepare($query);
			 $stmt->bindValue('upid', $upload_id, PDO::PARAM_INT);
		}
        	// copy $_POST variable to local variable, Just In Case
        	// NOTE: Third argument means binding as an integer.
        	// Default is "string", so 3rd arg not needed for strings.
        	// (There isn't one for floats, just use string.)
		$stmt->bindValue('uid', $user_id, PDO::PARAM_INT);
		$stmt->bindValue('weight', $weight, PDO::PARAM_INT);
		$stmt->bindValue('name', $name);
		$stmt->bindValue('breed', $breed);
		$stmt->bindValue('bio', $bio);
        	$stmt->execute();
		$new_dog_id = $dbh->lastInsertId();
        	$dbh->commit();

    	}
    catch (PDOException $e) {
	    $dbh->rollback();
	    $new_dog_id = 0;
	    die('PDO error fetching grade: ' . $e->getMessage());
    }
    return $new_dog_id;
}

function UpdateDogInfo($dbh, $name, $breed, $weight, $bio, $user_id, $upload_id)
{
	try {
		
		$query = "UPDATE Dogs " .
			"SET dog_name=:name, dog_breed=:breed, dog_weight=:weight, " .
			"dog_bio=:bio, upload_id=:upid " .
			"WHERE user_id = :uid";
		$stmt  = $dbh->prepare($query);
		$stmt->bindValue('uid', $user_id, PDO::PARAM_INT);
                $stmt->bindValue('weight', $weight, PDO::PARAM_INT);
                $stmt->bindValue('name', $name);
                $stmt->bindValue('breed', $breed);
                $stmt->bindValue('bio', $bio);
		$stmt->bindValue('upid', $upload_id, PDO::PARAM_INT);
		$stmt->execute();
		
	}      
	catch (PDOException $e) {
            die('PDO error fetching grade: ' . $e->getMessage());
    }
}

function NewUpload($dbh, $file_name, $file_path, $file_type, $user_id)
{
        try {
		$dbh->beginTransaction();
                $query = "INSERT into Uploads(file_name, file_path, file_type, user_id) " .
			"VALUES (:fname, :fpath, :ftype, :uid)";
			
		$stmt  = $dbh->prepare($query);
                $stmt->bindValue('uid', $user_id, PDO::PARAM_INT);
                $stmt->bindValue('fname', $file_name);
                $stmt->bindValue('fpath', $file_path);
                $stmt->bindValue('ftype', $file_type);
		$stmt->execute();
                $new_upload_id = $dbh->lastInsertId();
                $dbh->commit();

        }
	catch (PDOException $e) {
		$new_upload_id = 0;
		$dbh->rollback();
            die('PDO error fetching grade: ' . $e->getMessage());
	}
	return $new_upload_id;
}


function GetDogUpload($dbh, $user_id)
{
	try {
		$query = "SELECT * " .
			"FROM Uploads " .
			"WHERE upload_id in " .
			"(SELECT upload_id " .
			"FROM Dogs ".
			"WHERE user_id = :uid)";

		$stmt = $dbh->prepare($query);
		$stmt->bindValue('uid', $user_id, PDO::PARAM_INT);
		$stmt->execute();

		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
    	}
    	catch (PDOException $e) {
    	    die('PDO error fetching grade: ' . $e->getMessage());
    	}
    	return $result;
}

?>
