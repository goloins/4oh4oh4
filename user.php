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

// user.php - user profile page, displays user information and posts
include("functions.php");
drawheader(true);
$username = $_GET['username'];
$user = get_user_by_username($username);
if (!$user) {
    echo "<h1>User not found</h1>";
    drawfooter();
    exit();
}

//okay here we'll handle the $_GET['pageid'] for pagination of user posts. if it aint there, page 1. 
$page_id = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 1;





//here we go



?>


<h2 class="thumb">
			<a href="/<?php echo $user['avatar_url']; ?>"><img alt="<?php echo $user['displayname']; ?>" border="0" src="<?php echo $user['avatar_url']; ?>" valign="middle"/></a>
		
	<?php echo $user['displayname']; ?>
  
</h2>
<?php

$myfeed = get_userfeed($user['id'], 10, ($page_id - 1) * 10);
?>

<div class="desc">
		  <p><?php echo $myfeed[0]['content']; ?></p>
	  <p class="meta">
  		<a href="/status/<?php echo $myfeed[0]['id']; ?>"><?php echo format_time_ago($myfeed[0]['created_at']); ?></a>
  		from <?php echo $myfeed[0]['source']; ?>
			<span id="status_actions_<?php echo $myfeed[0]['id']; ?>">
</span>

  	</p>
	</div>

  <ul class="tabMenu">
  	<!--li> figure out later lol
  	  <a href="https://web.archive.org/web/20070316094528/http://twitter.com/merlinblack/with_friends">With Friends (24h)</a>
  	</li-->
  	<li class="active">
  	  <a href="https://web.archive.org/web/20070316094528/http://twitter.com/merlinblack">Previous</a>
  	</li>
  </ul>

  <div class="tab">
  	  	  <table class="doing" id="timeline" cellspacing="0">   			
<?php 
for($index = 1; $index < count($myfeed); $index++) {
    $post = $myfeed[$index];
    if($index % 2 == 0) {
        $trclass = "even";
    } else {
        $trclass = "odd";
    }
      echo     '<tr class="' . $trclass . '" id="status_' . $post['id'] . '">
        	<td>' . $post['content'] . '</td>
			
				
		<span class="meta">
						  <a href="/status/' . $post['id'] . '">' . format_time_ago($post['created_at']) . '</a>
						from ' . $post['source'] . '
      
			<span id="status_actions_' . $post['id'] . '">
</span>

		</span>
	</td>
</tr>';
}
?>

  
    		    	</table>
    	
    	<div class="pagination"> TODO: pagination logic here, for now just a placeholder
  <ul>
           <li class="disablepage">&#171; previous</li>
	   
                           <li class="currentpage"><?php echo $page_id; ?></li>
       	      	         	          <li>
	           <a href="/user/<?php echo $user['username']; ?>?pageid=<?php echo $page_id + 1; ?>"><?php echo $page_id + 1; ?></a>
	          </li>
	       	      	         	          <li>
	           <a href="/user/<?php echo $user['username']; ?>?pageid=<?php echo $page_id + 2; ?>"><?php echo $page_id + 2; ?></a>
	          </li>
	       	      	   
         <li class="nextpage">
        <a href="/user/<?php echo $user['username']; ?>?pageid=<?php echo $page_id + 1; ?>">next &#187;</a>
     </li>
      </ul>
</div-->


 
    	       
    	<span class="statuses_options">
    		<a href="/rss/@<?php echo $user['username']; ?>.rss">RSS Feed</a>
      </span>
  	  </div>


		</div></div><hr/>

	
	<div id="side">
			
  

<div class="msg">
	About <strong><?php echo $user['username']; ?></strong>  
</div>

<ul class="about">
	<li>Name: <?php echo $user['displayname']; ?></li>
			</ul>

<ul>
	<li><a href="/faved/<?php echo $user['username']; ?>">0 Favorites</a></li> <!-- todo: add faving logic -->
	<li><?php echo count($user['follows']); ?> Following</li>
	<li>1 Follower</li>  <!-- todo: add loging to enumerate followers, for now just a placeholder -->
	<li><?php echo get_user_total_posts_number($user['id']); ?> Updates</li>
</ul>
  <div id="friends">
<?php 

$topfollowers = get_top_followers($user['id'], 5);
if(count($topfollowers) > 0) {
    for($index = 0; $index < count($topfollowers); $index++) {
        $follower = $topfollowers[$index];
        echo '<a href="/user/' . htmlspecialchars($follower['username']) . '" rel="contact" title="' . htmlspecialchars($follower['displayname']) . '"><img alt="100x100_' . htmlspecialchars($follower['username']) . '" height="24" src="' . htmlspecialchars($follower['avatar_url']) . '" width="24"/></a>';
    }   
} else{
    echo '<p>No followers yet. Be the first to follow ' . htmlspecialchars($user['displayname']) . '!</p>';
}
  	
?>
  </div>


<div class="notify">
	Want an account?<br/>
	<a href="/register" class="join">Join for Free!</a><br/>
	Have an account? <a href="/login">Sign in!</a>
</div>



	</div><hr/>
			
		
		<hr/>


<?php
drawfooter();
?>