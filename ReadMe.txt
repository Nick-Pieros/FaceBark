Welcome to Facebark!

When you first come to Facebark's landing page (registration.php) you are offered the ability to:
1. Log in with an exsisting user name and password.
	If you click the 'Log In' button with empty fields or only one of the fields filled in you are redirected to log in.
	Here a user can log in with their exsisting username and password. If a user wishes to go back to the landing page they can simply click the Facebark logo!
	All fields are sanitized!
2. Sign up and create a Facebark account.
	If you with to sign up for a Facebark account the user must enter all the information required:
		Username - a unique identifier to distinguish you from others (not case sensitive)
		Email - a unique email to recieve emails 
		First name - the user's first name
		Last name - the user's last name
		Password - the password must contain 1 number, 1 letter, and be between 8-24 characters
		Confirm password - the user must type the same password again to confirm
	Once a user presses the 'Sign Up!' button all the fields are checked and sanitized and the user is redirected to the page that displays:
	"An email has been sent out. Please follow the link inside the email.
	 You will be redirected back to the registration page momentarily."
	 The user is then directed back to the landing page and can log in once they have confirmed their email.
3. Change your password in the event that you have forgotten it.
	If a user wishes to reset their password the user must enter their username and then they will recieve a an email which directs them to a new page to change their password.
	If the username does not exsist they will be prompted with:
	"We apologize, but we could not find your Username within our system. 
	 You will be redirected back to the registration page momentarily."

One way or another a user will eventually (hopefully) log in! When a user logs in they are placed on their profile page where they can see all the posts they have created
along with their avatar and bio information. From here a user can change their avatar at any time and update their bio information at any time. The bio consists of:
	Name - The name of the dog (30 characters)
	Breed - The breed of the dog (30 characters)
	Weight - The weight of the dog in lbs (4 digits)
	Bio - A short description of the dog (250 characters)
	
On any page on Facebark a user has the ability to search the site. They can search based on:
	Titles - The user can search all post titles.
	Tags - The user can search for a specific hashtag by either typing #hashtag_name_here or hashtag_name_here.
	User - The user can search for a specific user by either typing @user_name_here or user_name_here. A blank field for a user search will return all users on Facebark sorted
		   alphabetically.
		   
The user also has a 'Nav' menu where they can be directed to the gallery, their profile, or sign out. 
	Gallery - If a user selects the gallery they will be directed to the main page where 8 posts at a time will be displayed from all users. The user can navigate through these pages by selecting
			  the 'Next' or 'Prev' links. From the gallery a user can click on any post's picture to access the post. They can also like or dislike a post from the gallery. 
	Profile - If a user selects the profile they will be directed to their profile page.
	Sign Out - If a user sslects the sign out link they will be signed out of Facebark and directed to the login page.
	
The user can also select the 'Make a post' link from the header where they will be directed to make a post! When directed to make a post the user must first select a file (jpg, png, gif)
and then press 'Next!' A user will then be directed to give the post a title (1-144 characters) and enter an optional description (0-512 characters).
	Title - A title can take any information but it will be sanitized and remove the unsanitary data.
	Description - A description can take in any information but it will be sanitized and remove the unsanitary data. A description can also include hashtags (#) and user mention (@).
				  Hashtags will become links and when clicked will take you to all posts that also share that hashtag. Mentions will become links and when clicked take you directly
				  to that user's profile page. If a user is mentioned they will recieve an email notifying them and the email will provide them a link to the specific post that mentioned them.
				  
When a user has clicked on a post from the gallery or after a post has been created they are taking to the post page. One the post page the user can read the title, see who the post
was created by, when it was created, the description of the post, the popularity of the post, and they can see the comments on a post. A user can also leave their own comment(s) on 
any post they like. However, the OWNER of a post has the power to remove any and all comments on their post even if they did not post them. Any user can remove their OWN comments at any
time.
	Comments - Comments are 1-255 characters long and can include hashtags (#) or mentions (@). If a user mentions another user then that user will recieve an email letting them know
	they were mentioned! The email will also include a link to the post they were mentioned in. All comments are marked with a timestamp of when they were created.
	Voting - A user can only vote ONCE on a post and they cannot change their vote after it has been cast. Votes effect Popularity; popularity can be positive or negative.

If at any point a mention (@) is used in a description or comment and the mentioned user does not exsist (possible mispelling) then the link will take the user to a 404 page.
	
Things to check out through search box:
	User: Max - Max's profile and posts use the max character limits in everything he does. By accessing his profile you can see the max character limits in use.
	User: Min - Min's profile and posts use the min character limits in everything he does. By accessing his profile you can see the min character limits in use.
	User: Boneappletea - Has enough posts to page through posts on his profile page.
	Hashtag: confused
	Hashtag: dog
	Title: Run