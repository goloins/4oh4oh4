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

// do/favorite_post.php - /do/ framework strikes again.

// this favorites a post for the logged in user. 
// the _GET['post_id'] needs to be valid, thats about it.
// and at the end, we redirect them back to where they came from
// and that's basically the /do/ framework in a nutshell. 
// see also my ezbbs project.

include("functions.php");
ensure_logged_in();
$me = $_SESSION['user_id'];
$post_id = $_GET['post_id'];

if (!is_numeric($post_id)) {
    //invalid post id, just redirect back
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

favorite_post($me, $post_id);

//redirect back to where they came from, or to home if we don't have that info for some reason.
$redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/latest';
header("Location: " . $redirect_url);
exit();