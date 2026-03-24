<?php
/* 
* 4oh4oh4 - simple microblogging platform
*       designed for the small web
*       and the big hearted
*       inspired by early twitter.
*  
* Copyleft 2026 by 4oh4oh4 contributors
* License: Kopimi (Copy me, Copy my code)
*/

// do/post.php - /do/ framework strikes again.

//this does all the processing work for posting a status update.


include("functions.php");
ensure_logged_in();
$me = $_SESSION['user_id'];

if(!$_POST['content']){
    //no content, just redirect back
    set_notif_banner("Post cannot be empty, try again.");
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

create_post($me, $_POST['content']);

//redirect back to where they came from, or to home if we don't have that info for some reason.
$redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/latest';
header("Location: " . $redirect_url);
exit();