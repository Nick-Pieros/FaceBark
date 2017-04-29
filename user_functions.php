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

		echo json_encode($result);
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


?>

