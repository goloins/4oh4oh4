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

// rss.php - RSS feed for user posts, or hashtags
include("functions.php");

//determine if this is a user feed or a hashtag feed based on the URL structure
//if its /rss/@username, or /rss/#hashtag then we know what to do, otherwise show an error message

$path = trim($_SERVER['REQUEST_URI'], '/');
$path_parts = explode('/', $path);

if (count($path_parts) < 2) {
    die("Invalid RSS feed URL.");
}

$workingwith = "";

$feed_type = $path_parts[1][0];
$feed_identifier = substr($path_parts[1], 1);

if ($feed_type === '@') {
    // User feed
    $user = get_user_by_username($feed_identifier);
    $workingwith = "User";
    if (!$user) {
        die("User not found.");
    }
    $feed_items = get_userfeed($user['id'], 10, 0);
} elseif ($feed_type === '#') {
    // Hashtag feed
    $workingwith = "Hashtag";
    $feed_items = get_hashtag_feed($feed_identifier, 10, 0);
} else {
    die("Invalid RSS feed type.");
}


//todo: add rss generation function in functions.php.

switch ($workingwith) {
    case "User":
        header("Content-Type: application/rss+xml; charset=UTF-8");
        echo generate_rss_feed($feed_items, "Posts by user @" . $feed_identifier);
        break;
    case "Hashtag":
        header("Content-Type: application/rss+xml; charset=UTF-8");
        echo generate_rss_feed($feed_items, "Posts with hashtag #" . $feed_identifier);
        break;
    default:
        die("Unknown feed type.");
}