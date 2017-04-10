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
	echo "<p> in register user $username!</p>";


        echo "in register users";
	$dbh = ConnectDB();
	
	try{
		
                $dbh->beginTransaction();

		
                $query = "INSERT INTO Users(username, password, email, f_name, l_name ) " .
                        "VALUES(:u_name, :pass, :e_mail, :f_name, :l_name)";

                //$dbh->query($query);
                $stmt = $dbh->prepare($query);
                // copy $_POST variable to local variable, Just In Case

                // NOTE: Third argument means binding as an integer.
                // Default is "string", so 3rd arg not needed for strings.
                // (There isn't one for floats, just use string.)

                $stmt->bindValue('u_name', $username);
                $stmt->bindValue('pass', $pass);
                $stmt->bindValue('e_mail', $email);
                $stmt->bindValue('f_name', $f_name);
                $stmt->bindValue('l_name', $l_name);

                $stmt->execute();
                $dbh->commit();
			
		$query = "SELECT user_id " .
                        "FROM Users " .
                        "WHERE username = :u_name";


		$stmt = $dbh->prepare($query);

                $stmt->bindValue('u_name', $username);

		print "<p> $username</p>";
                $stmt->execute();
	
                // There should only be one, but this means if we get
                // more than one match we can find out easily.
		$new_user = $stmt->fetchAll(PDO::FETCH_OBJ);
		$howmany = count($new_user);

		$new_user_id = $new_user[0]->user_id;

		/*
		foreach ($new_user as $curr_user)
		{
			$new_user_id = $curr_user->user_id;
	        }
		*/
        	echo "<p> how many: $howmany </p>";

		//$dbh->commit();
        } catch(PDOException $e) {

                $dbh->rollback();
                die ('PDO error fetching grade: ' . $e->getMessage() );
	}

	echo "at the end of register user";

       return $new_user_id;

}
 


?>

