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
session_start();
include("functions.php");
drawheader(false);

// trending tag cloud: get_trending_hashtags() returns ['tag' => count] sorted by count desc.
// shuffle the keys so it reads like a cloud, not a sorted list.
$trending_hashtags = get_trending_hashtags();
if (!empty($trending_hashtags)) {
    $tag_names = array_keys($trending_hashtags);
    shuffle($tag_names);
    echo "<div id='trending_tags' style='width:100%; overflow:hidden; margin-bottom:20px; line-height:2;'>";
    foreach ($tag_names as $tag) {
        $count     = $trending_hashtags[$tag];
        $font_size = 10 + ($count * 2);
        echo '<a href="/tag/' . urlencode($tag) . '" style="font-size:' . (int)$font_size . 'px; margin-right:10px; text-decoration:none; color:#333;">#' . htmlspecialchars($tag) . '</a>';
    }
    echo "</div>";
}

// latest posts from everyone
echo '<table class="doing" id="timeline" cellspacing="0">';
$world_posts = build_sample_feed(50);

foreach ($world_posts as $index => $post) {
    $user      = get_user_by_id($post['user_id']);
    $avatar    = $user['avatar_url'] ?: 'res/default_avatar.png';
    $dispname  = $user['displayname'] ?: $user['username'];
    $trclass   = $index % 2 == 0 ? 'even' : 'odd';

    echo '<tr class="' . $trclass . '" id="status_' . (int)$post['id'] . '">';
    echo '<td class="thumb"><a href="/user/' . htmlspecialchars($user['username']) . '"><img alt="' . htmlspecialchars($dispname) . '\'s Avatar" src="' . htmlspecialchars($avatar) . '"/></a></td>';
    echo '<td><strong><a href="/user/' . htmlspecialchars($user['username']) . '">' . htmlspecialchars($dispname) . '</a></strong>';
    echo '<p>' . htmlspecialchars($post['content']) . '</p>';
    echo '<span class="meta"><a href="/status/' . (int)$post['id'] . '">' . format_time_ago($post['created_at']) . '</a> from web';
    if (is_logged_in()) {
        echo ' <span id="status_actions_' . (int)$post['id'] . '">'
            . '<font color="' . htmlspecialchars($site_vars['fave_color']) . '"><a href="/fave/' . (int)$post['id'] . '">[' . htmlspecialchars($site_vars['fave_name']) . ']</a></font>'
            . ' | <font color="' . htmlspecialchars($site_vars['repost_color']) . '"><a href="/repost/' . (int)$post['id'] . '">[' . htmlspecialchars($site_vars['repost_name']) . ']</a></font>'
            . '</span>';
    }
    echo '</span></td></tr>';
}
echo '</table>';

        echo '</div></div><hr/>';
        echo '<div id="side">';

if (is_logged_in()) {
    $me = get_user_by_id($_SESSION['user_id']);
    echo '<div class="msg"><h3><a href="/user/' . htmlspecialchars($me['username']) . '">@' . htmlspecialchars($me['username']) . '</a></h3></div>';
    echo '<div class="actions"><a href="/latest">My timeline</a><br/><a href="/user/' . htmlspecialchars($me['username']) . '">My profile</a></div>';
} else {
    echo '<div class="msg"><h3>Join the conversation!</h3></div>';
    echo '<div class="notify"><a href="/register" class="join">Join for Free!</a><br/>Have an account? <a href="/login">Sign in!</a></div>';
}

$featured = get_featured_users(5);
if (!empty($featured)) {
    echo '<div class="featured"><strong>New members</strong><br/>';
    foreach ($featured as $fu) {
        echo '<a href="/user/' . htmlspecialchars($fu['username']) . '">@' . htmlspecialchars($fu['username']) . '</a><br/>';
    }
    echo '</div>';
}

echo '<span class="statuses_options"><a href="/rss/world.rss">RSS Feed</a></span>';
echo '</div>';

drawfooter();

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