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

// user_feed_json.php - returns a user's feed in JSON format, for use in the API.
include("functions.php");

$user_id = $_GET['id'];
if(!$user_id){
    //no user id specified, just return an error
    header('Content-Type: application/json');
    echo json_encode(array("error" => "No user ID specified"));
    exit();
}
$user = get_user_by_id($user_id);
if(!$user){
    //user not found, return an error
    header('Content-Type: application/json');
    echo json_encode(array("error" => "User not found"));
    exit();
}
//user found, return it in JSON format
$feed = get_userfeed($user_id, 20, 0);
header('Content-Type: application/json');
echo json_encode($feed);
exit();