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

//build a hashtag cloud here maybe?
//a method that gets all posts in the last 24 hours
//pulls out the tags and then ranks them.


// get lastest posts from everyone

$world_posts = build_sample_feed(50);