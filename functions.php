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

// functions.php - utility functions for 4oh4oh4

include("settings.php");

$sql_helper = new mysqli($site_vars['db_host'], $site_vars['db_user'], $site_vars['db_password'], $site_vars['db_name']);
if ($sql_helper->connect_error) {
    admin_log("SQL", "Database connection failed: " . $sql_helper->connect_error);
    die("Connection failed: " . $sql_helper->connect_error);
}


function admin_log($module, $message) {
    global $site_vars;
    $log_file = __DIR__ . '/' . $site_vars['admin_log_filename'];
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$timestamp]: [$module] $message\n", FILE_APPEND);
}

function sanitize_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

//user structure: id, username, password_hash, email, name, created_at, isbanned, displayname, bio, avatar_url, follows (array of user ids)

function get_user_by_id($user_id) {
    global $sql_helper;
    $stmt = $sql_helper->prepare("SELECT id, username, email, name, created_at, isbanned, displayname, bio, avatar_url, follows FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function get_user_by_username($username) {
    global $sql_helper;
    $stmt = $sql_helper->prepare("SELECT id, username, email, name, created_at, isbanned, displayname, bio, avatar_url, follows FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function get_user_total_posts_number($user_id) {
    global $sql_helper;
    $stmt = $sql_helper->prepare("SELECT COUNT(*) as post_count FROM posts WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['post_count'];
}


function is_user_banned($user_id) {
    global $sql_helper;
    $stmt = $sql_helper->prepare("SELECT isbanned FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    return $user['isbanned'] == 1;
}

function create_user($username, $password, $email, $name) {
    global $sql_helper;
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $sql_helper->prepare("INSERT INTO users (username, password_hash, email, name, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $username, $password_hash, $email, $name);
    return $stmt->execute();
}


//posts structure: id, user_id, content, created_at, attached_media

function create_post($user_id, $content, $attached_media = null) {
    global $sql_helper;
    $source = "Web"; //todo: support for source (mobile, IM, etc)
    $stmt = $sql_helper->prepare("INSERT INTO posts (user_id, content, created_at, attached_media, source) VALUES (?, ?, NOW(), ?, ?)");
    $stmt->bind_param("isss", $user_id, $content, $attached_media, $source);
    return $stmt->execute();
}

function get_post_by_id($post_id) {
    global $sql_helper;
    $stmt = $sql_helper->prepare("SELECT id, user_id, content, created_at, attached_media FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// userfeeds: because users can repost items, each user will have a "feed" associted with their account.
// this is what you'll see when you look at a users profile. this has nothing to do with the feed you're
// served based on your follows.

function add_post_to_userfeed($user_id, $post_id) {
    global $sql_helper;
    $stmt = $sql_helper->prepare("INSERT INTO userfeeds (user_id, post_id, time_added) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_id, $post_id);
    return $stmt->execute();
}

function get_userfeed($user_id, $limit = 10, $offset = 0) {
    global $sql_helper;
    $stmt = $sql_helper->prepare("SELECT p.id, p.user_id, p.content, p.created_at, p.attached_media FROM userfeeds uf JOIN posts p ON uf.post_id = p.id WHERE uf.user_id = ? ORDER BY p.created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("iii", $user_id, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
    //this result should look like [ {id: 1, user_id: 1, content: "Hello world", created_at: "2026-01-01 00:00:00", attached_media: null}, {...}, ...]
}

function get_hashtag_feed($hashtag, $limit = 10, $offset = 0) {
    global $sql_helper;
    $like_pattern = '%' . $sql_helper->real_escape_string($hashtag) . '%';
    $stmt = $sql_helper->prepare("SELECT id, user_id, content, created_at, attached_media FROM posts WHERE content LIKE ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("sii", $like_pattern, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function generate_rss_feed($items, $title) {
    $rss_feed = '<?xml version="1.0" encoding="UTF-8" ?>';
    $rss_feed .= '<rss version="2.0"><channel>';
    $rss_feed .= '<title>' . htmlspecialchars($title) . '</title>';
    $rss_feed .= '<link>'.$site_vars['site_url'].'</link>';
    $rss_feed .= '<description>RSS feed for ' . htmlspecialchars($title) . '</description>';

    foreach ($items as $item) {
        $rss_feed .= '<item>';
        $rss_feed .= '<title>' . htmlspecialchars(substr($item['content'], 0, 50)) . '...</title>';
        $rss_feed .= '<link>'.$site_vars['site_url'].'/status/' . $item['id'] . '</link>';
        $rss_feed .= '<description>' . htmlspecialchars($item['content']) . '</description>';
        $rss_feed .= '<pubDate>' . date(DATE_RSS, strtotime($item['created_at'])) . '</pubDate>';
        $rss_feed .= '</item>';
    }

    $rss_feed .= '</channel></rss>';
    return $rss_feed;
}

function format_time_ago($timestamp): string {
    $time = is_numeric($timestamp) ? (int)$timestamp : strtotime((string)$timestamp);

    if ($time === false || $time <= 0) {
        return "just now";
    }

    $diff = time() - $time;

    if ($diff <= 0) {
        return "just now";
    }

    $units = [
        31536000 => "year",
        2592000  => "month",
        604800   => "week",
        86400    => "day",
        3600     => "hour",
        60       => "minute",
        1        => "second",
    ];

    foreach ($units as $seconds => $label) {
        if ($diff >= $seconds) {
            $value = (int) floor($diff / $seconds);
            return $value . " " . $label . ($value === 1 ? "" : "s") . " ago";
        }
    }

    return "just now";
}

// global feeds: get a chronological list of posts from users in the current users follows list.
// this is the feed you see when you log in. it should be ordered by created_at desc and paginated. 
// it will be sourced from the userfeeds table, but only for the users in the current users follows list.

function build_user_feed($user_id, $limit = 10, $offset = 0) {
    global $sql_helper;
    // get the list of user ids that the current user follows
    $stmt = $sql_helper->prepare("SELECT follows FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $follows = json_decode($user['follows'], true);
    
    if (empty($follows)) {
        return [];
    }

    // get posts from the userfeeds of the followed users
    $placeholders = implode(',', array_fill(0, count($follows), '?'));
    $types = str_repeat('i', count($follows));
    
    $query = "SELECT p.id, p.user_id, p.content, p.created_at, p.attached_media FROM userfeeds uf JOIN posts p ON uf.post_id = p.id WHERE uf.user_id IN ($placeholders) ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
    
    $stmt = $sql_helper->prepare($query);
    
    // bind the followed user ids and pagination parameters
    $params = array_merge($follows, [$limit, $offset]);
    $stmt->bind_param($types . 'ii', ...$params);
    
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
//sample feed for the homepage showing the $number latest posts from everyone.
//homepage will have 10, /public_timeline will have 50 by default.
function build_sample_feed($count){
    global $sql_helper;
    $stmt = $sql_helper->prepare("SELECT id, user_id, content, created_at, attached_media FROM posts ORDER BY created_at DESC LIMIT ?");
    $stmt->bind_param("i", $count);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function get_featured_users($limit = 5) {
    global $sql_helper;
    $stmt = $sql_helper->prepare("SELECT id, username, displayname FROM users ORDER BY created_at DESC LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


function get_top_followers($user_id){
    global $sql_helper;
    $stmt = $sql_helper->prepare("SELECT id, username, displayname FROM users WHERE JSON_CONTAINS(follows, JSON_QUOTE(?), '$') ORDER BY created_at DESC LIMIT 5");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $okay = $result->fetch_all(MYSQLI_ASSOC);
    //should be all we need for the little icons in the box
    for($index = 0; $index < count($okay); $index++) {
        $okay[$index]['avatar_url'] = get_user_by_id($okay[$index]['id'])['avatar_url'];
        $okay[$index]['username'] = get_user_by_id($okay[$index]['id'])['username'];
        $okay[$index]['displayname'] = get_user_by_id($okay[$index]['id'])['displayname'];
    }
    return $okay;
}


function convertmarkdowninfile($filepath) {
    $content = file_get_contents($filepath);
    // very basic markdown conversion for demonstration purposes
    $content = htmlspecialchars($content); // prevent XSS
    $content = nl2br($content); // convert newlines to <br>
    $content = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $content); // bold
    $content = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $content); // italic
    $content = preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="$2">$1</a>', $content); // links
    $content = preg_replace('/\n\# (.*?)\n/', '<h1>$1</h1>', $content); // h1
    $content = preg_replace('/\n\#\# (.*?)\n/', '<h2>$1</h2>', $content); // h2
    return $content;
}


function drawheader($access){
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="Content-Language" content="en-us"/>
	<title>'. htmlspecialchars($site_vars['site_name']) . ': What are you doing?</title>
	
	<link href="css/screen.css" media="screen, projection" rel="Stylesheet" type="text/css"/>

	
	<link rel="shortcut icon" href="res/favicon.ico" type="image/x-icon"/>
	
	
</head>
<body class="account" id="front">
 
	<style type="text/css">
	
    body {background: #9ae4e8 url(res/bg.gif) fixed no-repeat top left; text-align: center; font: 0.75em/1.5 Helvetica, Arial, sans-serif; color: #333;}
	  </style>
	';
if($access) {
    echo '<ul id="accessibility">
		<li>
			<a href="#navigation" accesskey="2">Skip to navigation</a>
		</li>
		<li>
			<a href="#side">Skip to sidebar</a>
		</li>
	</ul>';
}
echo '<div id="container" class="subpage">
		<h1 id="header">
	<a href="/" title="'. htmlspecialchars($site_vars['site_name']) . ': home" accesskey="1">
				<img alt="'. htmlspecialchars($site_vars['site_name']) . ' " height="49" src="res/logo.png" width="210"/>
			</a>
</h1>
		
				
		<div id="content"><div class="wrapper">	';
}


function drawfooter(){
    echo '<div id="footer">
	<h3>Footer</h3>
	<ul>
		<li class="first">&copy; 2026 4oh4oh4</li>
		<li><a href="/help/aboutus">About Us</a></li>
		<li><a href="/help/contact">Contact</a></li>
		<li><a href="/blog">Blog</a></li>
		<li><a href="/help/api">API</a></li>
		<li><a href="/help">Help</a></li>
		<li><a href="/tos">Terms of Service</a></li>
	</ul>
</div></div>';
}



/*
Login flow. 

so obviously the user doesn't need to be logged in on some pages

but others they do, so we'll have a function called is_logged_in() that checks if the user is logged in.
if they try to access a page that requires login and they're not logged in, we'll redirect them to the login/home page.

*/

function ensure_logged_in() {
    if (!is_logged_in()) {
        set_notif_banner("You must be logged in to view that page.");
        redirect("home.php");
    }
}

//these should be elsewhere in the file but who care 
function set_notif_banner($message){
    setcookie("notif_banner", $message, time() + 5, "/"); // expires in 5 seconds
}

function get_notif_banner() {
    if (isset($_COOKIE['notif_banner'])) {
        $message = $_COOKIE['notif_banner'];
        set_notif_banner("", time() - 3600); // clear the cookie
        return $message;
    }
    return null;
}


function set_login_cookie($user_id) {
    global $site_vars;
    // for simplicity, we'll just set a cookie with the user id.
    setcookie("user_id", $user_id, time() + (86400 * $site_vars['max_login_days']), "/"); // 30 days logged in
    setcookie("session_id", session_id(), time() + (86400 * $site_vars['max_login_days']), "/"); // 30 days logged in
}

function do_login($username, $password) {
    global $sql_helper;
    $stmt = $sql_helper->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        set_notif_banner("Invalid username. Make sure you're using your username, not your display name.");
        return false; // user not found
    }
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password_hash'])) {
        session_start();
        set_login_cookie($user['id']); 
        $_SESSION['user_id'] = $user['id'];
        return true; // login successful
    } else {
        set_notif_banner("Incorrect password. Please try again.");
        return false; // incorrect password
    }
}

