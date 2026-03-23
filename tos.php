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

// tos.php - terms of service page, reads document in markdown format and displays it as HTML
include("functions.php");
drawheader(false);
$tos = $site_vars['current_tos_url'];
echo convertmarkdowninfile($tos);
drawfooter();
?>
