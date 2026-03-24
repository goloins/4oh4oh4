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

// tag_feed_json.php - returns a hashtag's feed in JSON format, for use in the API.
include("functions.php");

$tag = $_GET['tag'];
if(!$tag){
    //no tag specified, just return an error
    header('Content-Type: application/json');
    echo json_encode(array("error" => "No tag specified"));
    exit();
}
$feed = get_hashtag_feed($tag, 20, 0);
if(!$feed){
    //feed not found, return an error
    header('Content-Type: application/json');
    echo json_encode(array("error" => "Feed not found"));
    exit();
}
//feed found, return it in JSON format  
header('Content-Type: application/json');
echo json_encode($feed);
exit();