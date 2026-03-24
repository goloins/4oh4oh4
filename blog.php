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

// blog.php - main blog page, displays recent posts from @4oh4oh4
include("functions.php");
drawheader(false);

echo "<h1>Welcome to the 4oh4oh4 blog!</h1>";
echo "<p>Here you'll find updates, news, and musings from the 4oh4oh4 team. Stay tuned for more content coming soon!</p>";
echo '<p>This is a mirror of our @4 account. <a href="/user/4oh4oh4">Follow us! @4oh4oh4</a></p>';
echo '<p>...yes we do have a higher post length here on the blog. shhh ;)</p>';


//so the plan is:
// fetch the most recent 20 blog posts, which are just posts by the @4 account.
$whoshouse = get_user_by_username("4");
$runshouse = $whoshouse['id'];
$blog_posts = get_userfeed($runshouse, 20, 0);

//so unlike a typical user page with the newest post highlighted
// in the large box atop the previous ones, each of these will 
// just be that big box again. remember, this is a blog page, not a user profile page.
foreach ($blog_posts as $post) {
echo '<div class="desc">';
echo '<p>' . $post['content'] . '</p>';
echo '<p class="meta">';
echo '<a href="/status/' . $post['id'] . '">' . format_time_ago($post['created_at']) . '</a>';
echo ' from ' . $post['source'];
echo '<span id="status_actions_' . $post['id'] . '"></span>';
echo '</p>';
echo '</div>';
}
 


drawfooter();
?>
