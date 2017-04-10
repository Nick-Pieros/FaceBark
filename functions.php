<?php
/* connect.php -- connect to MySQL and select webuser database
 *
 * Darren Provine, 8 March 2011
 */

// ConnectDB() - takes no arguments, returns database handle
// USAGE: $dbh = ConnectDB();

function ConnectDB() {

    /*** mysql server info ***/
    $hostname = '127.0.0.1';
    $username = 'pierosn0';
    $password = 'FaceBark2017';
    $dbname   = 'pierosn0';

    try {
        $dbh = new PDO("mysql:host=$hostname;dbname=$dbname",
		$username, $password);
	echo "<p> connected successfuly!</p>";
    }
    catch(PDOException $e)
    {
        die ('PDO error in "ConnectDB()": ' . $e->getMessage() );
    }

    return $dbh;
}

function Test() {
	return "<p>test</p>";
}

function RegisterUser($username, $email, $pass, $f_name, $l_name){


	$dbh = ConnectDB();
	
	try{
		
                $dbh->beginTransaction();

		
                $query = "INSERT INTO Users(username, password, email, f_name, l_name ) " .
                        "VALUES(:u_name, :pass, :e_mail, :f_name, :l_name)";

                $stmt = $dbh->prepare($query);

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
        } catch(PDOException $e) {

		$dbh->rollback();
		$new_user_id = -1;
                die ('PDO error fetching grade: ' . $e->getMessage() );
	}


       return $new_user_id;

}


function LoginUser($username, $pass){
	
	$dbh = ConnectDB();

        try{


                $query = "SELECT user_id " .
			"FROM Users " .
			"WHERE (username = :username AND password = :password)";

                $stmt = $dbh->prepare($query);
                // copy $_POST variable to local variable, Just In Case

                // NOTE: Third argument means binding as an integer.
                // Default is "string", so 3rd arg not needed for strings.
                // (There isn't one for floats, just use string.)

                $stmt->bindValue('username', $username);
                $stmt->bindValue('password', $pass);

		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);

		$howmany = count($result);
		$curr_urser_id = 0;
		$curr_user = $result[0];
		$curr_user_id = 0;	
		if ( $howmany == 1 ) {
            		$curr_user_id = $curr_user->user_id;
		}


        } catch(PDOException $e) {

                $curr_user_id = -1;
                die ('PDO error fetching grade: ' . $e->getMessage() );
        }

       return $curr_user_id;

}

function GetRecentPosts($page_num) {

	$num_per_page = 8;
	$last_post = $page_num * $num_per_page;
	$first_post = ($last_post - $num_per_page);

	$dbh = ConnectDB();
	 try{

                $query = "SELECT * " .
                        "FROM Posts " .
			"ORDER BY post_timestamp DESC " .
			"LIMIT :first,:num_pages";
		$stmt = $dbh->prepare($query);
                // copy $_POST variable to local variable, Just In Case

                // NOTE: Third argument means binding as an integer.
                // Default is "string", so 3rd arg not needed for strings.
                // (There isn't one for floats, just use string.)
                $stmt->bindValue('first', $first_post, PDO::PARAM_INT);
                $stmt->bindValue('num_pages', $num_per_page, PDO::PARAM_INT);

                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_OBJ);

		$howmany = count($result);
                if ( $howmany < 1 ) {
                        $result = 0;
                }


        } catch(PDOException $e) {

                die ('PDO error fetching grade: ' . $e->getMessage() );
        }

       return $result;
}

function GetUsersRecentPosts($page_num, $user_id) {
        $num_per_page = 8;
        $last_post = $page_num * $num_per_page;
        $first_post = ($last_post - $num_per_page);

        $dbh = ConnectDB();
         try{

                $query = "SELECT * " .
			"FROM Posts " .
			"WHERE user_id = :uid " .
                        "ORDER BY post_timestamp DESC " .
                        "LIMIT :first,:num_pages";
                $stmt = $dbh->prepare($query);
                // copy $_POST variable to local variable, Just In Case

                // NOTE: Third argument means binding as an integer.
                // Default is "string", so 3rd arg not needed for strings.
                // (There isn't one for floats, just use string.)
                $stmt->bindValue('first', $first_post, PDO::PARAM_INT);
                $stmt->bindValue('num_pages', $num_per_page, PDO::PARAM_INT);
		$stmt->bindValue('uid', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_OBJ);

                $howmany = count($result);
        } catch(PDOException $e) {

                die ('PDO error fetching grade: ' . $e->getMessage() );
        }

       return $result;
}

//TODO make this take an array instead of a signle value
function GetPostUpload($post_id) {
        $dbh = ConnectDB();
         try{

                $query = "SELECT * " .
                        "FROM Uploads " .
			"WHERE upload_id in( " .
		       		"SELECT upload_id " .
				"FROM Posts_Uploads " .	
				"WHERE post_id = :pid) ";
                $stmt = $dbh->prepare($query);
                // copy $_POST variable to local variable, Just In Case

                // NOTE: Third argument means binding as an integer.
                // Default is "string", so 3rd arg not needed for strings.
                // (There isn't one for floats, just use string.)
                $stmt->bindValue('pid', $post_id, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_OBJ);

        } catch(PDOException $e) {

                die ('PDO error fetching grade: ' . $e->getMessage() );
        }

       return $result;
}


//TODO make this take an array instead of a signle value
function GetPostText($post_id) {
        $dbh = ConnectDB();
         try{

                $query = "SELECT post_text " .
                        "FROM  Posts_Text " .
                        "WHERE post_id = :pid ";
                $stmt = $dbh->prepare($query);
                // copy $_POST variable to local variable, Just In Case

                // NOTE: Third argument means binding as an integer.
                // Default is "string", so 3rd arg not needed for strings.
                // (There isn't one for floats, just use string.)
                $stmt->bindValue('pid', $post_id, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_OBJ);

        } catch(PDOException $e) {

                die ('PDO error fetching grade: ' . $e->getMessage() );
        }

       return $result;
}



?>

