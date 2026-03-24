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
// so we need a box to contain the replies, should be a slightly darkened div below the post content.
?>
<div id="replies" style="background-color: #f0f0f0; padding: 10px; margin-top: 20px;">
    <h3>Replies</h3>
    <?php
    $replies = get_replies_for_post($post_id);
    if (count($replies) == 0) {
        echo "<p>No replies yet.</p>";
    } else {
        foreach ($replies as $reply) {
            echo "<div class='reply' style='border-bottom: 1px solid #ccc; padding: 5px 0;'>";
            echo "<p><strong>@" . htmlspecialchars(get_user_by_id($reply['user_id'])['username']) . "</strong>: " . htmlspecialchars($reply['content']) . "</p>";
            echo "<p>Posted " . format_time_ago($reply['created_at']) . "</p>";
            echo "</div>";
        }
    }
    ?>
</div>


<?php

drawfooter();
