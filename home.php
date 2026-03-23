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

// home.php - main page showing public timeline and login form
include("functions.php");
drawheader(true);
?>


			<h2><?php echo htmlspecialchars($site_vars['site_description']); ?>: <em>What are you doing?</em> Answer on your phone, IM (escargot support!), or right here on the web!</h2>

<h3>Look at what <a href="/public_timeline">these people</a> are doing right now&hellip;</h3>

<?php

$build_public_timeline_preview = build_sample_feed(10);

?>


<table class="doing" id="timeline" cellspacing="0"> <!-- building table -->


<?php
for($index = 0; $index < count($build_public_timeline_preview); $index++) {
	$post = $build_public_timeline_preview[$index];
	$user = get_user_by_id($post['user_id']);
	echo '<tr class="' . ($index % 2 == 0 ? 'even' : 'odd') . '" id="status_' . htmlspecialchars($post['id']) . '">';
	echo '<td class="thumb"><a href="/user/' . htmlspecialchars($post['username']) . '"><img alt="' . htmlspecialchars($user['displayname']) . '\'s Avatar" src="' . htmlspecialchars($user['avatar_url']) . '"/></a></td>';
	echo '<td><strong><a href="/user/' . htmlspecialchars($post['username']) . '" title="User ' . htmlspecialchars($user['displayname']) . '">' . htmlspecialchars($user['displayname']) . '</a></strong>';
	echo '<p>' . htmlspecialchars($post['content']) . '</p>';
	echo '<span class="meta"><a href="/status/' . htmlspecialchars($post['id']) . '">' . time_elapsed_string(strtotime($post['created_at'])) . '</a> from web</span>';
	echo '</td></tr>';
}


?>
</table>



		</div></div><hr/>

	
	<div id="side">
		  <div class="msg">
  	<h3>Please Sign In!</h3>
  </div>

  <form action="/login" class="signin" method="post" name="f">    <fieldset>
    	<div>
    		<label for="username">Username</label>
    		<input id="username" name="username" type="text"/>
    	</div>
    	<div>
    		<label for="password">Password</label>
    		<input id="password" name="password" type="password"/>
    	</div>
    	<input id="remember_me" name="remember_me" type="checkbox" value="1"/> <label for="remember_me">Remember me</label>
    	<small><a href="/account/resend_password">Forgot?</a></small>
    	<input id="submit" name="commit" type="submit" value="Sign In!"/>
    </fieldset>
  </form>  <script type="text/javascript">
//<![CDATA[
document.getElementById('username').focus()
//]]>
</script>

  <div class="notify">
  	Want an account?<br/>
  	<a href="/account/create" class="join">Join for Free!</a><br/>
  	It&rsquo;s fast and easy!
  </div>
  	<li><strong>Featured!</strong></li>
	  <ul class="featured">

<?php
$featured_users = get_featured_users(5);
for($index = 0; $index < count($featured_users); $index++) {
	$user = get_user_by_id($featured_users[$index]['id']);
	echo '<li><div class="featured_user">';
	echo '<a href="/user/' . htmlspecialchars($user['username']) . '"><img alt="' . htmlspecialchars($user['displayname']) . '\'s Avatar" src="' . htmlspecialchars($user['avatar_url']) . '"/></a>';
	echo '<a href="/user/' . htmlspecialchars($user['username']) . '" title="' . htmlspecialchars($user['displayname']) . '">' . htmlspecialchars($user['displayname']) . '</a>';
	echo '</div></li>';
}


?>
</ul>

	</div><hr/>
			
		
		<hr/>
<?php drawfooter(); ?>
			</div>
	
	<!-- script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
	<script type="text/javascript">
	_uacct = "UA-30775-6";
	urchinTracker();
	</script-->
</body></html>