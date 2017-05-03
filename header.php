<?php
/*
*   header.php -- provides php pages with navigation and search; to be #included
*
*   Clifford Black, David Carlin, Nicholas Pieros - 5/3/2017
*/
?>

<div class='header'>
    <div class='logo'><a href='gallery.php?page=1'><img src='images/fbark-logo.png' /></a></div>
    <div class='search-form'>
      <form action='search.php' method='get'>
        <input type='text' name='searchTerm' placeholder='Search...'>
        <input type='submit' name='method' value='Titles'>
        <input type='submit' name='method' value='Tags'>
        <input type='submit' name='method' value='User'>
      </form>
    </div>
    <ul class='menu'>
        <li class='dropdown'>
            <span>Nav &#9662;</span>
            <ul class='features-menu'>
                <li><a href='gallery.php?page=1'>Gallery</a>
                </li>
                <li><a href='profile.php'>Profile</a>
                </li>
                <li><a href='registration.php'>Sign Out</a>
                </li>
            </ul>
        </li>
        <li><a href='ui-create-post.php'>Make a post</a>
        </li>
    </ul>
</div>
