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

// settings.php - site configuration variables

$site_vars = array();
$site_vars['site_name'] = '4oh4oh4';
$site_vars['site_url'] = 'http://localhost/4oh4oh4';
$site_vars['site_description'] = 'A simple microblogging platform for the small web';
$site_vars['site_language'] = 'en';
$site_vars['site_timezone'] = 'UTC';
$site_vars['site_theme'] = 'default';
$site_vars['site_posts_per_page'] = 10;
$site_vars['site_allow_registration'] = true;
$site_vars['site_allow_anonymous_posts'] = false;

$site_vars['admin_username'] = 'admin';
$site_vars['admin_password'] = 'admin'; // Change this in production lol
$site_vars['admin_email'] = '';
$site_vars['admin_name'] = '4oh4oh4';
$site_vars['admin_log_filename'] = 'admin.log';

$site_vars['db_host'] = 'localhost';
$site_vars['db_name'] = '4oh4oh4';
$site_vars['db_user'] = 'root';
$site_vars['db_password'] = ''; 

$site_vars['max_user_post_length'] = 420; //blaze it
$site_vars['max_admin_post_length'] = 9001; //over 9000!
$site_vars['max_login_days'] = 30; //30 days

$site_vars['current_tos_url'] = '/static/tos.md'; //update this when you change your terms of service
$site_vars['current_privacy_policy_url'] = '/static/privacy.md'; //privacy policy in md
$site_vars['about_us_url'] = '/static/aboutus.md'; //same thing for about us page
$site_vars['contact_us_url'] = '/static/contact.md'; //you get it, same for contact info
$site_vars['help_index_url'] = '/static/help_index.md'; //halp pege 
$site_vars['api_docs_url'] = '/static/api_docs.md'; //api docs, good luck chief