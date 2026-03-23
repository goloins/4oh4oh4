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

// blog.php - main blog page, displays recent posts from @4oh4oh4
include("functions.php");
drawheader(false);

//todo: logic yet to be written to read posts from database and display them here, for now just a placeholder  
echo "<h1>Welcome to the 4oh4oh4 blog!</h1>";
echo "<p>Here you'll find updates, news, and musings from the 4oh4oh4 team. Stay tuned for more content coming soon!</p>";
echo '<p>This is a mirror of our @4 account. <a href="/user/4oh4oh4">Follow us! @4oh4oh4</a></p>';
echo '<p>...yes we do have a higher post length here on the blog. shhh ;)</p>';

drawfooter();
?>
