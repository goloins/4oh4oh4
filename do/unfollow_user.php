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
// do/unfollow_user.php - unfollows a user.

session_start();
include("../functions.php");
ensure_logged_in();

$username = isset($_GET['username']) ? trim($_GET['username']) : '';
if ($username === '') {
    set_notif_banner("No user specified.");
    redirect($_SERVER['HTTP_REFERER'] ?? '/');
}

$target = get_user_by_username($username);
if (!$target) {
    set_notif_banner("User not found.");
    redirect($_SERVER['HTTP_REFERER'] ?? '/');
}

unfollow_user($_SESSION['user_id'], $target['id']);

redirect('/user/' . $username);
