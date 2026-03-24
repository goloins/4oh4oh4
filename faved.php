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

// faved.php - displays a list of fave'd posts for a given user.
include("functions.php");
drawheader(true);
$user = '';
if(isset($_GET['username'])){
    $username = $_GET['username'];
    $user = get_user_by_username($username);
    if(!$user){
        echo "<h1>User not found</h1>";
        drawfooter();
        exit;
    }
} else {
    echo "<h1>No user specified</h1>";
    drawfooter();
    exit;
}

$faved_posts = get_faved_posts_by_user_id($user['id']);

foreach($faved_posts as $post){
    $op_data = get_reposter_info_for_post($post['id']);
    $op_username = $op_data['username'];
    echo '<div class="desc">
          <p><a href="/user/' . $op_username . '">@' . $op_username . '</a>: ' . $post['content'] . '</p>
      <p class="meta">
        <a href="/status/' . $post['id'] . '">' . format_time_ago($post['created_at']) . '</a>
        from ' . $post['source'] . '
      </p>
    </div>';
}

drawfooter();
?>