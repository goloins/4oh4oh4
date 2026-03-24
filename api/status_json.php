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

// status_json.php - returns a single post in JSON format, for use in the API.
include("functions.php");

$post_id = $_GET['id'];
if(!$post_id){
    //no post id specified, just return an error
    header('Content-Type: application/json');
    echo json_encode(array("error" => "No post ID specified"));
    exit();
}
$post = get_post_by_id($post_id);
if(!$post){
    //post not found, return an error
    header('Content-Type: application/json');
    echo json_encode(array("error" => "Post not found"));
    exit();
}
//post found, return it in JSON format
header('Content-Type: application/json');
echo json_encode($post);
exit();