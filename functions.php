<?php
/* functions.php -- php functions for inferfacing with the database
 *
 * nick pieros, May 5th, 2017
 */


/*
 * function for getting a set of recent posts
 * Input:
 * 	page_num:	number of the page the user is on
 * 	user_id:	the user whose posts we are searching for
 * 				if it is 0, search for all users
 * 	dbh:		database handler
 * output:
 * 	0	-	query failed
 * 	else	-	result of the MySQL statement
 */
function GetRecentPosts($page_num, $user_id, $dbh) {
    //number of posts per page	
    $num_per_page = 8;
    //last post to display
    $last_post    = $page_num * $num_per_page;
    //first post to display
    $first_post   = ($last_post - $num_per_page);
    try 
    {
        //checking to see if we're looking for posts from a specific user
	if ($user_id == 0) {
	/*
	 * selecting the information about the post and the user who created it
	 * ordering results by the time(oldest to newest) they were created
	 * limiting the number of results to 8
	 */
	$query = "SELECT post_id, post_title, post_timestamp, " .
		 "post_votes, username, post_text, file_path, file_name " .
		 "FROM ((((Posts LEFT JOIN Users using(user_id)) " .
		 "LEFT JOIN Posts_Text using (post_id)) " .
		 "LEFT JOIN Posts_Uploads using (post_id)) " .
		 "LEFT JOIN Uploads using (upload_id))" .
		 "ORDER BY post_timestamp DESC " .
		 "LIMIT :first,:num_pages";
	//preaparing the statement
	$stmt  = $dbh->prepare($query);
        //if a user id is given
	}
	else 
	{
		$query = "SELECT post_id, post_title, post_timestamp, " .
			 "post_votes, username, post_text, file_path, " .
			 "file_name " .
			 "FROM ((((Posts LEFT JOIN Users using(user_id)) " .
			 "LEFT JOIN Posts_Text using (post_id)) " .
			 "LEFT JOIN Posts_Uploads using (post_id)) " .
			 "LEFT JOIN Uploads using (upload_id)) " .
			 "WHERE Posts.user_id = :uid " .
			 "ORDER BY post_timestamp DESC " .
			 "LIMIT :first,:num_pages";
		// preparing the statement
		$stmt  = $dbh->prepare($query);
         	//binding uid
		$stmt->bindValue('uid', $user_id, PDO::PARAM_INT);
        }

        //binding first
        $stmt->bindValue('first', $first_post, PDO::PARAM_INT);
        //binding num pages
        $stmt->bindValue('num_pages', $num_per_page, PDO::PARAM_INT);
        //executing the statement
        $stmt->execute();
        //getting the result of the query
        $result  = $stmt->fetchAll(PDO::FETCH_OBJ);
        //counting how many results were returned
        $howmany = count($result);
        //if there was nothing returned set result to 0
        if ($howmany < 1) {
            $result = 0;
        }
    }

    //catching any PDO errors
    catch (PDOException $e) {
        die('PDO error fetching grade: ' . $e->getMessage());
    }
    //returing the reslut
    return $result;
}

/*
 * function to get the information on a specific post
 * input:
 * 	post_id:	id of the post to get
 * 	dbh:		database handler
 * Output:
 * 	the information onf the post if successful
 * 	0 if it was unsuccessful
 */
function GetPost($post_id, $dbh)
{
    try 
    {
	/*
	 * selecting the information about the post
	 * where the post id is the id provided
	 */
	$query = "SELECT post_id, post_title, post_timestamp, post_votes, " .
		 "username, post_text, file_path, file_name " .
		 "FROM ((((Posts LEFT JOIN Users using(user_id)) " .
		 "LEFT JOIN Posts_Text using (post_id)) " .
		 "LEFT JOIN Posts_Uploads using (post_id)) " .
		 "LEFT JOIN Uploads using (upload_id)) " .
		 "WHERE Posts.post_id = :pid ";
	
	$stmt  = $dbh->prepare($query);

	//binding the post id
	$stmt->bindValue('pid', $post_id, PDO::PARAM_INT);
	
	$stmt->execute();

	//fetching the result
	$result  = $stmt->fetchAll(PDO::FETCH_OBJ);
	
	$howmany = count($result);

	//if the result doesn't contain at least one item set result to 0
	if ($howmany < 1) 
	{
            $result = 0;
        }
    }
    catch (PDOException $e) {
        die('PDO error fetching a post: ' . $e->getMessage());
    }
    return $result;
}

/*
 * function for getting the information on a dog owned by a user
 * input:
 * 	user_id:	user whose dog we're looking for
 * 	dbh:		database handler
 * Output:
 * 	information on the dog owned by the user provided if the user provided exists
 * 	an empty object if the user does not exist
 */
function GetDogInfo($user_id, $dbh)
{
    try 
    {
	/*
	 * Selecting the information and the upload for the dog
	 * where the user_id of the user who owns the dog matches the user id provided
	 */
        $query = "SELECT dog_name, dog_breed, dog_weight, dog_bio, file_name, " .
		 "file_path, file_type " .
		 "FROM Dogs LEFT JOIN Uploads using(upload_id)" .
		 "WHERE Dogs.user_id = :uid";
	
	$stmt  = $dbh->prepare($query);

	//binding the user_id
	$stmt->bindValue('uid', $user_id, PDO::PARAM_INT);
	
	$stmt->execute();
	//fetching the result
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    catch (PDOException $e) {
        die('PDO error fetching dog info: ' . $e->getMessage());
    }
    return $result;
}

/*
 * function to get the user's information
 * Inputs:
 * 	user_id:	id of the user to get the information on
 * 	dbh:		database handler
 * Output:
 * 	the information on the user if the user exists and is not deleted
 * 	0 if the user does not exist or is deleted
 */
function GetUserInfo($user_id, $dbh)
{
    try
    {
	/*
	 * selecting the user's information from the Users table
	 * where the user id matches the one provided
	 * and where the user is not marked as deleted
	 */
        $query = "SELECT user_id, username, email, f_name, l_name " .
		 "FROM Users " .
		 "WHERE user_id = :uid AND user_deleted = 0";
	
	$stmt  = $dbh->prepare($query);
	//binding the user id
	$stmt->bindValue('uid', $user_id, PDO::PARAM_INT);

	$stmt->execute();

	//getting the result
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
	
	$howmany = count($result);
	//setting the result to 0 if nothing was returned from the query
	if ($howmany = 0) 
	{
            $result = 0;
        }
    }
    catch (PDOException $e) {
        die('PDO error fetching the user\'s info: ' . $e->getMessage());
    }
    return $result;
}

/*
 * getting the information about the upload of the post
 * inputs:
 * 	post_id:	id of the post to get the information on
 * 	dbh:		database handler
 * Ouput:
 * 	the information about the upload if the post exists and has an upload
 * 	an empty object if the post doesn't exist or if it has no upload
 */
function GetPostUpload($post_id, $dbh)
{
    try 
    {
	/*
	 * selecting everything (post_id and upload_id) about the post's upload
	 * where the post_id is equal to the post id provided
	 */
        $query = "SELECT * " .
	         "FROM Uploads " .
	         "WHERE upload_id in( " .
	         "SELECT upload_id " .
	         "FROM Posts_Uploads " .
	         "WHERE post_id = :pid) ";
	
	$stmt  = $dbh->prepare($query);

	//binding the post id
	$stmt->bindValue('pid', $post_id, PDO::PARAM_INT);
	
	$stmt->execute();

	//getting the result
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    catch (PDOException $e) {
        die('PDO error fetching the post\'s upload: ' . $e->getMessage());
    }
    return $result;
}

/*
 * getting the text of a post
 * Inputs:
 * 	post_id:	id of the post to get the text for
 * 	dbh:		database handler
 * Outputs:
 * 	the text for the post if the post exists and has text
 *	an empty object if the post doesn't exist or has no text
 */
function GetPostText($post_id, $dbh)
{
    try 
    {
	/*
	 * selecting the text associated with the post
	 * where the post_id matches the id provided
	 */
        $query = "SELECT post_text " . 
		 "FROM  Posts_Text " . 
		 "WHERE post_id = :pid ";
        $stmt  = $dbh->prepare($query);

	//binding the post_id
	$stmt->bindValue('pid', $post_id, PDO::PARAM_INT);
	
	$stmt->execute();

	//getting the result
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    catch (PDOException $e) {
        die('PDO error fetching the post\'s text: ' . $e->getMessage());
    }
    return $result;
}

/*
 * function for creating a Dog entry in the database
 * inputs:
 * 	name:		name of the dog
 * 	breed:		breed of the dog
 * 	weight:		weight of the dog
 * 	bio:		bio for the dog
 * 	user_id:	id of the user who owns the dog
 * 	upload_id:	the upload associated with the dog (optional)
 * Output:
 * 	the id of the newly created dog
 * 	0 if the dog wasn't created
 */
function CreateDogInfo($dbh, $name, $breed, $weight, $bio, $user_id, $upload_id)
{

    try 
    {
	//starting a transaction
        $dbh->beginTransaction();
	if($upload_id == 0)
	{
	    //preparing to insert a dog with no associated upload id
	    $query = "INSERT into Dogs(dog_name, dog_breed, dog_weight, " .
	             "dog_bio, user_id) " .
		     "VALUES(:name, :breed, :weight, :bio, :uid)";
	    $stmt  = $dbh->prepare($query);
	}
	else
	{
	    //preparing to insert a dog with an upload associated with it
	    $query = "INSERT into Dogs(dog_name, dog_breed, dog_weight, " . 
	             "dog_bio, user_id, upload_id) " .
	             "VALUES(:name, :breed, :weight, :bio, :uid, :upid)";
            $stmt  = $dbh->prepare($query);
	    //binding the upload id for the dog
	    $stmt->bindValue('upid', $upload_id, PDO::PARAM_INT);
	}
	//binding the user_id, name, weight, breed and bio
	$stmt->bindValue('uid', $user_id, PDO::PARAM_INT);
	$stmt->bindValue('weight', $weight, PDO::PARAM_INT);
	$stmt->bindValue('name', $name);
	$stmt->bindValue('breed', $breed);
	$stmt->bindValue('bio', $bio);
	$stmt->execute();
	//getting the newly created dog or 0 if the dog wasn't created
	$new_dog_id = $dbh->lastInsertId();
        $dbh->commit();

    }
    catch (PDOException $e) {
	//rolling back the db if an error occured    
	$dbh->rollback();
	$new_dog_id = 0;
	die('PDO error creating the dog\'s info: ' . $e->getMessage());
    }
    return $new_dog_id;
}

/*
 * function for updating the dog info
 * inputs:
 *      name:           name of the dog
 *      breed:          breed of the dog
 *      weight:         weight of the dog
 *      bio:            bio for the dog
 *      user_id:        id of the user who owns the dog
 *      upload_id:      the upload associated with the dog (optional)
 */

function UpdateDogInfo($dbh, $name, $breed, $weight, $bio, $user_id, $upload_id)
{
    try 
    {
	//updating the information for the dog with the new information provided
	//fields are preset with the old info so that everything gets updated
	//	only the changes are kept
        $query = "UPDATE Dogs " .
	        "SET dog_name=:name, dog_breed=:breed, dog_weight=:weight, " .
		"dog_bio=:bio, upload_id=:upid " .
		"WHERE user_id = :uid";
	$stmt  = $dbh->prepare($query);
	//binding the user_id, name, weight, breed and bio
	$stmt->bindValue('uid', $user_id, PDO::PARAM_INT);
        $stmt->bindValue('weight', $weight, PDO::PARAM_INT);
        $stmt->bindValue('name', $name);
        $stmt->bindValue('breed', $breed);
        $stmt->bindValue('bio', $bio);
	$stmt->bindValue('upid', $upload_id, PDO::PARAM_INT);
	$stmt->execute();

    }
    catch (PDOException $e) {
        die('PDO error updating the dog\'s info: ' . $e->getMessage());
    }
}
/*
 * function fro creating a new upload
 * Inputs:
 * 	dbh:		database handler
 * 	file_name:	name of the file
 * 	file_path:	path for where the file is located
 * 	file_type:	type of file that's being uploaded
 * 	user_id:	id of the user who is making the upload
 * Outputs:
 * 	the newly created upload id or 0 if it failed
 */
function NewUpload($dbh, $file_name, $file_path, $file_type, $user_id)
{
    try 
    {
	//starting a trnasaction
	$dbh->beginTransaction();
	
	//inserting the upload id into the uploads table
        $query = "INSERT into Uploads(file_name, file_path, file_type, user_id) " .
		 "VALUES (:fname, :fpath, :ftype, :uid)";

	$stmt  = $dbh->prepare($query);
	//binding file_name, file_path,  file_type, and user_id
	$stmt->bindValue('uid', $user_id, PDO::PARAM_INT);
        $stmt->bindValue('fname', $file_name);
        $stmt->bindValue('fpath', $file_path);
        $stmt->bindValue('ftype', $file_type);
	$stmt->execute();
	//getting the last inserted id or 0 if no id was inserted
	$new_upload_id = $dbh->lastInsertId();
	//commiting the transaction
        $dbh->commit();

    }
    catch (PDOException $e) {
        $new_upload_id = 0;
	//rolling back the tranasaction
	$dbh->rollback();
        die('PDO error creating a new upload: ' . $e->getMessage());
    }
    return $new_upload_id;
}
/*
 * function for getting the upload id associated with a dog
 * inputs:
 * 	user_id:	user who owns the dog
 * 	dbh:		database handler
 * outputs:
 *	the upload id associated with the dog or 
 *		nothing if the user doesn't exist
 */
function GetDogUpload($dbh, $user_id)
{
    try
    {
	//selecting all of the upload information for the upload associted 
	//	with the dog that has the user id that matches the id provided
	$query = "SELECT * " .
	         "FROM Uploads " .
		 "WHERE upload_id in " .
		 "(SELECT upload_id " . 
		 "FROM Dogs ".
		 "WHERE user_id = :uid)";

	$stmt = $dbh->prepare($query);
	//binding the user id
	$stmt->bindValue('uid', $user_id, PDO::PARAM_INT);
	$stmt->execute();

	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    catch (PDOException $e) {
        die('PDO error getting the dog\'s upload: ' . $e->getMessage());
    }
    return $result;
}
/*
 * function for creating a post
 * Input:
 * 	dbh:		database handler
 * 	user_id:	user who is creating the post
 * 	title:		title of the post
 * 	text:		text description of the post (optional)
 * 	upload:		upload id assocated with the post (optional)
 */
function CreatePost($dbh, $user_id, $title, $text, $upload)
{
    try
    {
	/*
	 * Calling the create post procedure
	 * procedure does the following:
	 * 	creates the entry in the post table for the given title and user
	 * 	if the text is not null
	 * 		create the entry in the posts_text table for the text
	 * 	if the upload is not null
	 * 		create the entry in the posts_uploads table for the upload
	 * returns the id of the newly entered post
	 */
	$query = "CALL CreatePost(:uid, :title, :text, :upload)";

	$stmt = $dbh->prepare($query);
	//binding user id and title
	$stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
	$stmt->bindParam(':title', $title);

	//checking if the text is null
	if(is_null($text))
	{
	    //if it is bind the text to null
	    $stmt->bindParam(':text', $text, PDO::PARAM_NULL);
	}
	else
	{
	    //if not, bind the text
	    $stmt->bindParam(':text', $text);
	}
	//check if the upload is null
	if(is_null($upload))
	{
	    //if it is, bind the upload to null
	    $stmt->bindParam(':upload', $upload, PDO::PARAM_NULL);
	}
	else
	{
	    //if not, bind the upload
	    $stmt->bindParam(':upload', $upload, PDO::PARAM_INT);
	}

	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
	
	$howmany = count($result);
	//if no result was obtained, return 0
	if($howmany == 0)
	{
	    $result = 0;
	}
	else
	{
	    //otherwise return the newly created post's id
	    $result = $result[0];
	    $result = $result->new_post_id;
	}

    }
    catch (PDOException $e) {
        die('PDO error creating the post: ' . $e->getMessage());
    }
    return $result;
}
/*
 * function for getting the userid based on their user name
 * this function was added in the hopes of future use 
 * 	but may not be used much currently
 * Input:
 * 	dbh:		database handler
 * 	username:	username of the user to search for
 */
function GetUserByUsername($dbh, $username)
{
    try
    {
	  //selecting the user_id where the username is equal 
	  //	to the one provided (case insensitive)
	  $query = "SELECT user_id " .
	 	   "FROM Users " .
		   "WHERE username like :uname AND user_deleted = 0";

          $stmt = $dbh->prepare($query);
	  //binding the username
  	  $stmt->bindParam(':uname', $username);
  	  $stmt->execute();
  	  $result = $stmt->fetchAll(PDO::FETCH_OBJ);
	  $howmany = count($result);
	  //if there was no result, return 0
          if($howmany == 0)
          {
              $result = 0;
          }
          else
	  {
	      //oththerwise return the user_id obtained
              $result = $result[0];
              $result = $result->user_id;
  	  }
  }
  catch (PDOException $e) {
      die('PDO error fetching the user by name: ' . $e->getMessage());
  }
  return $result;
}


/*
 * function for getting the user by their email
 * Inpupts:
 * 	email:		email address
 * 	dbh:		database handler
 * Outputs:
 * 	the user_id if the email is used by someone
 * 	0 if no user uses that email
 * Was implemented for future use but is not used as of now
 */
function GetUserByEmail($dbh, $email)
{
    try 
    {
	//selecting the userid where the email matches the one provided
	// and the user is not deleted
        $query = "SELECT user_id " .
                 "FROM Users " .
                 "WHERE email = :mail  AND user_deleted = 0";

        $stmt = $dbh->prepare($query);

	//binding the email
        $stmt->bindParam(':mail', $email);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $howmany = count($result);
        if($howmany == 0)
	{
	    //returning 0 if no user found
            $result = 0;
        }
        else
	{
	    //returning the user_id if successful
            $result = $result[0];
            $result = $result->user_id;
        }
    }
    catch (PDOException $e) {
        die('PDO error fetching the user by email: ' . $e->getMessage());
    }
        return $result;
}

/*
 * function for getting the upload id based on the file name
 * inputs:
 * 	filename:	name of the file
 * 	dbh:		databse handler
 * Outputs:
 * 	the upload id if successful or 0
 */
function GetUploadId($dbh, $filename)
{
    try
    {
	//selecting the upload id where the file name is equal to the name provided
        $query = "SELECT upload_id " .
                 "FROM Uploads " .
		 "WHERE file_name=:fname";

      	$stmt = $dbh->prepare($query);
	//binding filename
      	$stmt->bindParam(':fname', $filename);
      	$stmt->execute();
      	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
      	$howmany = count($result);
        if($howmany == 0)
        {
	    //returning 0 if no file is found
            $result = 0;
        }
        else
	{
	    //returning the upload id if the file was found
            $result = $result[0];
            $result = $result->upload_id;
	}
  }
  catch (PDOException $e) {
      die('PDO error fetching the upload id of the file: ' . $e->getMessage());
  }
  return $result;
}

/*
 * Getting a hashtag based on the hastag text provided
 * Inputs:
 * 	dbh:		database handler
 * 	hashtag:	string representing the hastag
 * Outputs
 * 	hashtag id or 0 if no hashtag is found
 */
function GetHashtag($dbh, $hashtag) {

    try
    {
	$query = "SELECT hashtag_id " .
                 "FROM Hashtags " .
		 "WHERE hashtag_text like :hashtag";
	$stmt = $dbh->prepare($query);
	//binding hashtag
	$stmt->bindParam(':hashtag', $hashtag);
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
	$howmany = count($result);

	//returning 0 if no result and the hashtag_id if there was
	if($howmany == 0)
	{
	    $result = 0;
	}
	else
	{
	    $result = $result[0];
      	    $result = $result->hashtag_id;
	}
    }
    catch (PDOException $e) {
  	die('PDO error fetching the id of the hastag: ' . $e->getMessage());
    }
    return $result;

}

/*
 * function for creating a hastag
 * Inputs:
 * 	dbh:		database handler
 * 	hashtag:	text of the hashtag
 * 	post_id:	id of the post to add the hastag on (optional)
 * 	comment_id	id of the comment to add the hastag on (optional)
 * Output:
 * 	hastag id of existing hastag or newly created hashtag
 */
function CreateHashtag($dbh, $hashtag, $post_id, $comment_id) {

    try
    {
	/*
	 * calling the create hashtag procedure
	 * does the following:
	 * 	check if the hastag already exists in the hastag table
	 * 		if not, add the hastag to the table
	 * 	if the post_id is not 0
	 * 		create an entry in the Posts_Hashtags table using
	 * 			the post and hastag id
	 * 	if the comment_id is not 0
	 * 		create an entry in the Comments_Hashtags table using
	 * 			the comment_id and hastag_id
	 * 	return the hastag id used
	 */
	$query = "CALL CreateHashtag(:hashtag, :pid, :cid)";

        $stmt = $dbh->prepare($query);
	//bnding the hastag text, post id and comment id
	$stmt->bindParam(':hashtag', $hashtag);
	$stmt->bindParam(':pid', $post_id, PDO::PARAM_INT);
	$stmt->bindParam(':cid', $comment_id, PDO::PARAM_INT);
	$stmt->execute();

	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
	//returning 0 if nothing was added and the hastag id if it was
        $howmany = count($result);
        if($howmany == 0)
        {
             $result = 0;
        }
        else
        {
             $result = $result[0];
             $result = $result->hash_id;
        }

    }
        catch (PDOException $e) {
		$result = 0;
		die('PDO error creating the hashtags: ' . $e->getMessage());
        }
        return $result;

}
/*
 * function for creating the comment
 * input:
 * 	post_id:	id of the post the comment is on
 * 	user_id:	id of the user whoe is  making the commnet
 * 	comment:	text of the comment
 * Output:
 * 	newly created comment id or 0 if unsuccessful
 */
function CreateComment($dbh, $post_id, $user_id, $comment) {

    try 
    {
	/*
	 * call create comment procedure
	 * simply adds a comment to the comments table
	 * originally needed to add comment to comment table then add comment child
	 * no longer use comment child table sot his was left as is.
	 */
        $query = "CALL CreateComment(:pid, :uid, :comment)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':pid', $post_id, PDO::PARAM_INT);
        stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
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
           $result = $result->new_comment_id;
	}

    }
    catch (PDOException $e) {
        $result = 0;
        die('PDO error creating comments: ' . $e->getMessage());
   }
   return $result;

}

/*
 * function for getting all the comments on a post
 * input
 * 	post_id		id of the post to get the comments for
 * out put
 * 	noting if no comments or wrong post
 * 	all comments on post if they exist
 */
function GetAllComments($dbh, $post_id) {

    try
    {
	$query = "SELECT comment_id, comment_text, comment_timestamp, comment_votes, " .
	         "username " .
 		 "FROM Comments LEFT JOIN Users using(user_id) " .
		 "WHERE post_id = :pid " .
		 "ORDER BY comment_timestamp ASC";

	$stmt = $dbh->prepare($query);
	//binding post id
	$stmt->bindParam(':pid', $post_id, PDO::PARAM_INT);
	$stmt->execute();
	//setting the result equal to the result from the sql query
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
	catch (PDOException $e) {
		$result = [];
		die('PDO error fetching all comments for the post: ' . $e->getMessage());
	}
	return $result;
}
/*
 * function for voting on post
 * input
 * 	user_id:	user making vote
 * 	post_id:	post vote is on
 * 	vote:		vote user made
 */
function VoteOnPost($dbh, $user_id, $post_id, $vote) {

    try
    {
	//making sure vote either +1 or -1
	if($vote >= 0)
	{
            $vote = 1;
	}
	else
	{
	    $vote = -1;
	}
	/*
	 * calling VoteOnPost procedure
	 * inserst the user's vote into the VotedPost table along with the post id
	 * updates the vote count on the post
	 */
        $query = "CALL VoteOnPost(:uid, :pid, :vote)";

        $stmt = $dbh->prepare($query);

	//binding input params
        $stmt->bindParam(':pid', $post_id, PDO::PARAM_INT);
	$stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
	$stmt->bindParam(':vote', $vote, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
	$howmany = count($result);
	//returning 0 if nothing was returned and true if it was successful
        if($howmany == 0)
        {
            $result = 0;
        }
        else
        {
            $result = $result[0];
            $result = $result->was_success;
	}
    }
    catch (PDOException $e) {
        $result = 0;
        die('PDO error voting on the post: ' . $e->getMessage());
    }
    return $result;

}

/*
 * function for deleting comments
 * post_id:	id of post to delete the comment on
 * comment_id:	id of the comment to delete
 * user_id:	user attempting to delete
 */
function DeleteComment($dbh, $post_id, $comment_id, $user_id) {

    try 
    {
	/*
	 * updating comment to set user id to 1 and text to deleted
	 * where the comment_id matches the one provided and provided user id
	 * 	matches one of the following
	 * 		user_id of the user who made the comment
	 * 		user_id of the user who made the post
	 * 		user id of an admin user
	 */
	$query = "UPDATE Comments " .
 		 "SET user_id = 1, comment_text = '[deleted]' " .
		 "WHERE comment_id = :cid AND (user_id = :uid OR :uid = ( " .
		 "SELECT user_id " .
		 "FROM Posts " .
		 "WHERE post_id = :pid) " .
		 "OR :uid in (SELECT * From Admin_Users))";

        $stmt = $dbh->prepare($query);

	//binding params
        $stmt->bindParam(':pid', $post_id, PDO::PARAM_INT);
	$stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
	$stmt->bindParam(':cid', $comment_id, PDO::PARAM_INT);


        $stmt->execute();

	//returning the number of rows edited
        $result = $stmt->rowCount();
    }
    catch (PDOException $e) {
        $result = 0;
        die('PDO error deleting comments: ' . $e->getMessage());
    }
        return $result;

}

/*
 * function for delting post
 * post_id:	id of post
 * user_id:	id of user attemtping delete
 */
function DeletePost($dbh, $post_id, $user_id)
{
    try 
    {
	/*
	 * deleting post where post_id matches id provided and user matches one of 
	 * 	the followiung
	 * 	user_id of the user who made the post
	 * 	user_id of an admin
	 */
        $query = "DELETE FROM Posts " .
     		 "WHERE post_id = :pid AND (user_id = :uid OR :uid in ( " .
       		 "SELECT * " .
      		 "FROM Admin_Users))";

    	$stmt = $dbh->prepare($query);
	//binding params
        $stmt->bindParam(':pid', $post_id, PDO::PARAM_INT);
        $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    	$stmt->execute();
	//returning rows affected
        $result = $stmt->rowCount();

    }
    catch (PDOException $e) {
        $result = 0;
        die('PDO error deleting post: ' . $e->getMessage());
    }
    return $result;

}

/*
 * functing for deleting upload of post
 * post_id	id of post to delete upload
 * user_id	id of user attempting to delete
 */
function DeletePostUpload($dbh, $post_id, $user_id) {

    try 
    {
	//deleting the upload provided if the user provided is the user who
	//made the post or the user provided is an admin
        $query = "DELETE FROM Posts_Uploads " .
                 "WHERE post_id = :pid  AND post_id in ( " .
                 "SELECT post_id " .
                 "FROM Posts " .
                 "WHERE user_id = :uid OR :uid in ( " .
                 "SELECT * " .
                 "FROM Admin_Users )) ";

        $stmt = $dbh->prepare($query);

	$stmt->bindParam(':pid', $post_id, PDO::PARAM_INT);
	$stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
        $stmt->execute();
	//returning rows afected
	$result = $stmt->rowCount();

   }
   catch (PDOException $e) {
       $result = 0;
       die('PDO error deleting post upload: ' . $e->getMessage());
   }
   return $result;

}

/*
 * Fucntion for deleting text of post
 * post_id	post to delete from
 * user_id	user who is deleting
 */
function DeletePostText($dbh, $post_id, $user_id) {

    try
    {
	//update post text entry and set text to deleted where the post_id matches
	//the post id provided and the user is either the user who made the post
	//or an admin user
	$query = "UPDATE Posts_Text " .
	 	 "SET post_text = '[deleted]' " .
		 "WHERE post_id = :pid AND post_id in ( " .
		 "SELECT post_id " .
		 "FROM Posts " .
		 "WHERE user_id = :uid OR :uid in ( " .
		 "SELECT * " .
		 "FROM Admin_Users )) ";

	$stmt = $dbh->prepare($query);

	//binding params
	$stmt->bindParam(':pid', $post_id, PDO::PARAM_INT);
	$stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
        $stmt->execute();
	//returning rows affected
	$result = $stmt->rowCount();
    }
    catch (PDOException $e) {
        $result = 0;
        die('PDO error deleting post text: ' . $e->getMessage());
    }
    return $result;

}

/*
 * function for searching posts by hashtag
 * page_num	number of the page the user is on
 * hashtag	hashtag to search for
 */
function SearchPostsByHashtags($dbh, $page_num, $hashtag) {
    $num_per_page = 8;
    $last_post    = $page_num * $num_per_page;
    $first_post   = ($last_post - $num_per_page);
    try 
    {
	//selecting the needed post, upload, text, and user info for the posts
	//where their post id is in the posts_hashtags table
	//and ordering ti by date desc and limiting it to 8 per page
        $query = "SELECT post_id, post_title, post_timestamp, post_votes, " .
                 "username, post_text, file_path, file_name " .
                 "FROM ((((Posts LEFT JOIN Users using(user_id)) " .
                 "LEFT JOIN Posts_Text using (post_id)) " .
                 "LEFT JOIN Posts_Uploads using (post_id)) " .
                 "LEFT JOIN Uploads using (upload_id)) " .
		 "WHERE Users.user_deleted = 0 AND Posts.post_id in (SELECT post_id " .
		 "FROM Posts_Hashtags " .
		 "WHERE hashtag_id = (SELECT hashtag_id " .
		 "FROM Hashtags " .
		 "WHERE hashtag_text like :hashtag )) " .
                 "ORDER BY post_timestamp DESC " .
                 "LIMIT :first,:num_pages";
	$stmt  = $dbh->prepare($query);
	//binding params
	$stmt->bindValue('first', $first_post, PDO::PARAM_INT);
	$stmt->bindValue('hashtag', $hashtag);
        $stmt->bindValue('num_pages', $num_per_page, PDO::PARAM_INT);
        $stmt->execute();
        $result  = $stmt->fetchAll(PDO::FETCH_OBJ);
        $howmany = count($result);
        if ($howmany < 1) {
            $result = 0;
	}
    }
    catch (PDOException $e) {
        die('PDO error getting recent posts: ' . $e->getMessage());
    }
    return $result;
}

/*
 * function for searching posts by title
 * page_num	number of the page the user is on
 * title	title to search for
 */
function SearchPostsByTitle($dbh, $page_num, $title) {
    $num_per_page = 8;
    $last_post    = $page_num * $num_per_page;
    $first_post   = ($last_post - $num_per_page);
    try 
    {
	//pretty much the same as every other function that gets posts
	//except this time limit posts where the tite contains the search string
        $query = "SELECT post_id, post_title, post_timestamp, post_votes, " .
                 "username, post_text, file_path, file_name " .
                 "FROM ((((Posts LEFT JOIN Users using(user_id)) " .
                 "LEFT JOIN Posts_Text using (post_id)) " .
                 "LEFT JOIN Posts_Uploads using (post_id)) " .
                 "LEFT JOIN Uploads using (upload_id)) " .
		 "WHERE Users.user_deleted = 0 AND Posts.post_title like ".
		 " CONCAT('%', :title , '%') " .
                 "ORDER BY post_timestamp DESC " .
                 "LIMIT :first,:num_pages";
	$stmt  = $dbh->prepare($query);
	//binding params
        $stmt->bindValue('first', $first_post, PDO::PARAM_INT);
        $stmt->bindValue('title', $title);
        $stmt->bindValue('num_pages', $num_per_page, PDO::PARAM_INT);
        $stmt->execute();
        $result  = $stmt->fetchAll(PDO::FETCH_OBJ);
	$howmany = count($result);
	//returning 0 if no post found
        if ($howmany < 1) {
            $result = 0;
        }
    }
    catch (PDOException $e) {
        die('PDO error getting recent posts: ' . $e->getMessage());
    }
    return $result;
}

/*
 * function for searching for users
 * username	string to search usernames for
 */
function SearchUsers($dbh, $username) {
    try
    {
	//selecting the usernames of undeleted useres that have a name
	//similar to the string provided
	$query = "SELECT username " .
		 "FROM Users " .
		 "WHERE user_deleted = 0 AND user_id > 1 AND username like " .
		 "CONCAT('%', :username, '%') " .
		 "ORDER BY username ASC ";
	$stmt  = $dbh->prepare($query);
	//binding params
	$stmt->bindValue('username', $username);
	$stmt->execute();
	$result  = $stmt->fetchAll(PDO::FETCH_OBJ);
	$howmany = count($result);
	//return 0 if no match found
	if ($howmany < 1) {
		$result = 0;
	}
   }
   catch (PDOException $e) {
       die('PDO error getting recent posts: ' . $e->getMessage());
   }
   return $result;
}
/*
 * function for checking if user is an admin
 * user_id	id of the usre to check
 */
function IsAdminUser($dbh, $user_id) {

    try 
    {
	//selecting the count from the admin user table where the
	//user_id matches the one provided
        $query = "SELECT * " .
                 "FROM Admin_Users " .
                 "WHERE user_id = :uid";
        $stmt  = $dbh->prepare($query);
        $stmt->bindValue('uid', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result  = $stmt->fetchAll(PDO::FETCH_OBJ);
	$howmany = count($result);
	//returning 0 if no result is found
        if ($howmany < 1) {
            $result = 0;
	}
	//returning 1 if a result was found
	else
	{
	    $result = 1;
	}
    }
    catch (PDOException $e) {
        die('PDO error getting recent posts: ' . $e->getMessage());
    }
    return $result;
}
?>
