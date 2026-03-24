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
// world.php - the main feed page, shows the latest posts from everyone.
include("functions.php");
drawheader(false);

//okay so we have the tags 
// in get_trending_hashtags. 
// so im thinking we'll randomize the array
// and create a specific sized div to 
// put them in. the size of each tag will be 
// determined by the number of posts with that tag
// maybe this will work. multiplying font size by post count?

$trending_hashtags = get_trending_hashtags();
shuffle($trending_hashtags);

echo "<div id='trending_tags' style='width: 100%; height: 100px; overflow: hidden; margin-bottom: 20px;'>";
foreach($trending_hashtags as $tag){
    $post_count = get_post_count_with_tag($tag);
    $font_size = 10 + ($post_count * 2); // base font size
    echo "<a href='/tag/" . urlencode($tag) . "' style='font-size: " . $font_size . "px; margin-right: 10px; text-decoration: none; color: #333;'>#" . htmlspecialchars($tag) . "</a>";
}
echo "</div>";



// get lastest posts from everyone
echo '<table class="doing" id="timeline" cellspacing="0"> <!-- building table -->';
$world_posts = build_sample_feed(50);

for($index = 0; $index < count($world_posts); $index++) {
	$post = $world_posts[$index];
	$user = get_user_by_id($post['user_id']);
	echo '<tr class="' . ($index % 2 == 0 ? 'even' : 'odd') . '" id="status_' . htmlspecialchars($post['id']) . '">';
	echo '<td class="thumb"><a href="/user/' . htmlspecialchars($post['username']) . '"><img alt="' . htmlspecialchars($user['displayname']) . '\'s Avatar" src="' . htmlspecialchars($user['avatar_url']) . '"/></a></td>';
	echo '<td><strong><a href="/user/' . htmlspecialchars($post['username']) . '" title="User ' . htmlspecialchars($user['displayname']) . '">' . htmlspecialchars($user['displayname']) . '</a></strong>';
	echo '<p>' . htmlspecialchars($post['content']) . '</p>';
	echo '<span class="meta"><a href="/status/' . htmlspecialchars($post['id']) . '">' . time_elapsed_string(strtotime($post['created_at'])) . '</a> from web</span>';
    if(ensure_logged_in()){
        echo '<span id="status_actions_' . htmlspecialchars($post['id']) . '"><font color="' . $site_vars['fave_color'] . '"><a href="/fave/' . htmlspecialchars($post['id']) . '">[' . htmlspecialchars($site_vars['fave_name']) . ']</a></font> | <font color="' . $site_vars['repost_color'] . '"><a href="/repost/' . htmlspecialchars($post['id']) . '">[' . htmlspecialchars($site_vars['repost_name']) . ']</a></font></span>';
    }
    echo '</td></tr>';
}
echo '</table>';
drawfooter();