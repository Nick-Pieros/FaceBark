<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
 "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>Safer PHP / SQL Page</title>
  <meta http-equiv="Content-Type"
        content="application/xhtml+xml; charset=UTF-8" />
  <meta name="Author" content="Darren Provine" />
</head>

<body>

<p>
This page uses PHP to connect to MySQL, but does so more safely. <br />
It breaks the SQL query down into separate chunks, so the logic <br />
("where person_id=") does not get mixed up with the data ("4").
</p>
<p>
As a result, setting the person_id to "4 or 1=1" won't work.
</p>

<p>Enter your person_id and I will look up your grade:</p>

<form action="safer_sql.php" method="post">
<table>
<tr>
    <th>username:
    </th>
    <td><input type="text" name="username" />
    </td>
</tr>
<tr>
    <th>password:
    </th>
    <td><input type="text" name="password" />
    </td>
</tr>
<tr>
    <th>email:
    </th>
    <td><input type="text" name="email" />
    </td>
</tr>
<tr>
    <th>first name:
    </th>
    <td><input type="text" name="f_name" />
    </td>
</tr>
<tr>
    <th>last name:
    </th>
    <td><input type="text" name="l_name" />
    </td>
</tr>
<tr>
    <td>
    </td>
    <td> <input type="submit" />
    </td>
</tr>
</table>
</form>

<p> Here is your grade from when you were here before (if ever): </p>
<?php
require_once("connect.php");
$dbh = ConnectDB();
?>

<div style="margin-left: 20px;">
<?php
//if ( isset($_POST['username'], $_POST['password'], $_POST['email'], $_POST['f_name'], $_POST['l_name'] ) ) {
if ( isset($_POST['username'] ) ) {
	echo "<p>";
	echo $_POST['username'];
	echo "</p>";
	try {

        //$dbh->beginTransaction();



        $query = "INSERT INTO Users(username, password, email, f_name, l_name ) " .
                 "VALUES(:u_name, :pass, :e_mail, :f_name, :l_name)";

	//$dbh->query($query);
        $stmt = $dbh->prepare($query);
        // copy $_POST variable to local variable, Just In Case

	$username = $_POST['username'];
	$email = $_POST['email'];
	$f_name = $_POST['f_name'];
	$l_name = $_POST['l_name'];
	$pass = $_POST['password'];

        // NOTE: Third argument means binding as an integer.
        // Default is "string", so 3rd arg not needed for strings.
        // (There isn't one for floats, just use string.)
	
	$stmt->bindValue('u_name', $username);
	$stmt->bindValue('pass', $pass);
	$stmt->bindValue('e_mail', $email);
	$stmt->bindValue('f_name', $f_name);
	$stmt->bindValue('l_name', $l_name);

        $stmt->execute();
	
        $query = "SELECT * " .
		"FROM Users ";


	$stmt = $dbh->prepare($query);
        $stmt->execute();

        // There should only be one, but this means if we get
        // more than one match we can find out easily.
	$user = $stmt->fetchAll(PDO::FETCH_OBJ);
	$howmany = count($user);
	echo "<p> how many: $howmany </p>";
	foreach ($user as $curr_user)
	{
		echo "<p> ";
		echo "Username: $curr_user->username , email: $curr_user->email , password: $curr_user->password, first name: $curr_user->f_name, last name: $curr_user->l_name";
		echo "</p>\n";
	}

  	echo "<p>Here is what we got: <br/> $Users<p>";

        /*
        $howmany = count($grade);
        if ( $howmany != 1 ) {
            echo "<p>That's funny; I got $howmany results....</p>\n";
        }
        foreach ($grade as $g_info) {
            echo "<p> ";
            echo "Name: $g_info->fname $g_info->lname - Grade $g_info->grade";
            echo "</p>\n";
        }
         */

        //$dbh->commit();
        } catch(PDOException $e) {
	//	$dbh->rollback();
		echo "<p> ";
		echo "shit's dead yo";
		echo "</p>";
                die ('PDO error fetching grade: ' . $e->getMessage() );
        }
} else {
    echo "<p><i>(Please fill out all the fields)</i></p>\n";
}
?>


</div>

<p>
End of grade report.
</p>

<p>
<a href="http://validator.w3.org/check/referer">
<img style="border:0;width:88px;height:31px"
       src="http://www.w3.org/Icons/valid-xhtml11"
       alt="Valid XHTML 1.1!" />
</a>
</p>

<p>
<a href="http://jigsaw.w3.org/css-validator/check/referer">
<img style="border:0;width:88px;height:31px"
       src="http://jigsaw.w3.org/css-validator/images/vcss" 
       alt="Valid CSS!" />
</a>
</p>

</body>
</html>
