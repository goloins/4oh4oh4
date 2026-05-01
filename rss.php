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

// rss.php - RSS feed dispatcher
// Supported URLs:
//   /rss/@username.rss        — a user's posts
//   /rss/tag/tagname.rss      — posts tagged with #tagname
//   /rss/world.rss            — public timeline (latest posts from everyone)
//   /rss/blog.rss             — site blog (posts by @4)
include("functions.php");

function rss_strip_ext(string $s): string {
    return preg_replace('/\.rss$/i', '', $s);
}

$path       = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$path_parts = explode('/', $path);
// $path_parts[0] = 'rss'
// $path_parts[1] = '@user.rss' | 'world.rss' | 'blog.rss' | 'tag'
// $path_parts[2] = 'tagname.rss'  (only for tag feeds)

if (count($path_parts) < 2 || $path_parts[0] !== 'rss') {
    http_response_code(400);
    die("Invalid RSS feed URL.");
}

header("Content-Type: application/rss+xml; charset=UTF-8");

$segment    = $path_parts[1];
$first_char = $segment[0] ?? '';

if ($first_char === '@') {
    // /rss/@username.rss
    $username = rss_strip_ext(substr($segment, 1));
    if (!preg_match('/^[A-Za-z0-9_]{1,20}$/', $username)) {
        http_response_code(400); die("Invalid username.");
    }
    $user = get_user_by_username($username);
    if (!$user) { http_response_code(404); die("User not found."); }
    $items = get_userfeed($user['id'], 20, 0);
    echo generate_rss_feed($items, "Posts by @" . $username);

} elseif ($segment === 'tag' && isset($path_parts[2])) {
    // /rss/tag/tagname.rss
    $tag = rss_strip_ext($path_parts[2]);
    $tag = preg_replace('/[^A-Za-z0-9_\-]/', '', $tag);
    if ($tag === '') { http_response_code(400); die("Invalid tag."); }
    $items = get_hashtag_feed($tag, 20, 0);
    echo generate_rss_feed($items, "Posts tagged #" . $tag);

} elseif (rss_strip_ext($segment) === 'world') {
    // /rss/world.rss
    $items = build_sample_feed(20);
    echo generate_rss_feed($items, "Public Timeline");

} elseif (rss_strip_ext($segment) === 'blog') {
    // /rss/blog.rss
    $blog_user = get_user_by_username("4");
    if (!$blog_user) { http_response_code(404); die("Blog not available."); }
    $items = get_userfeed($blog_user['id'], 20, 0);
    echo generate_rss_feed($items, "Site Blog");

} else {
    http_response_code(400);
    die("Unknown feed type. Try /rss/@username.rss, /rss/tag/tagname.rss, /rss/world.rss, or /rss/blog.rss");
}