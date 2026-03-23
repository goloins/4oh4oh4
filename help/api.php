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

// api.php - API documentation page, reads document in markdown format and displays it as HTML
include("functions.php");
drawheader(false);
    $api_docs = $site_vars['api_docs_url'];
echo convertmarkdowninfile($api_docs);
drawfooter();
?>
