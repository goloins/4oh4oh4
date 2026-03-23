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

// latest.php - shows the lastest posts for the logged in user (home timeline)
include("functions.php");

ensure_logged_in();
drawheader(false);
// so if there's no $_GET['pageid'] or it's not a valid number, default to page 1
$page_id = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 1;

//so what we're gonna do here is alter the value of the posts we fetch based on the page number
//as well as $site_vars['site_posts_per_page'] to determine how many posts to show per page
$posts_per_page = $site_vars['site_posts_per_page'];
$offset = ($page_id - 1) * $posts_per_page;

$letsgetit = build_user_feed($_SESSION['user_id'], $posts_per_page, $offset);

?>
