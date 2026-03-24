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

// status.php - status page, displays a single post and its details. 
//great for peramalinks and sharing individual posts. 
include("functions.php");
drawheader(false);

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = get_post_by_id($post_id);

if (!$post) {
    echo "<p>Post not found.</p>";
} else {
    echo "<h2>Post by @" . htmlspecialchars(get_user_by_id($post['user_id'])['username']) . "</h2>";
    echo "<p>" . htmlspecialchars($post['content']) . "</p>";
    echo "<p>Posted " . format_time_ago($post['created_at']) . "</p>";
}


drawfooter();
