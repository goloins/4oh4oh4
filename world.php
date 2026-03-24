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

$world_posts = build_sample_feed(50);

foreach($world_posts as $post){
  //create rows with post content, user info, etc.
  //similar to how we do it in home.php
  //ensure if logged in we draw the fave/repost button.
  

}