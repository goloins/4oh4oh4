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
// tag.php - shows posts associated with a specific tag.
include("functions.php");
drawheader(false);

$tag = $_GET['tag'];
if(!$tag){
    //no tag specified, just redirect to home
    header("Location: /");
    exit();
}


drawfooter();