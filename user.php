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
session_start();
include("functions.php");

$username = isset($_GET['username']) ? trim($_GET['username']) : '';
$user = get_user_by_username($username);
if (!$user) {
    drawheader(false);
    echo "<h1>User not found</h1>";
    drawfooter();
    exit();
}

$page_id      = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? max(1, intval($_GET['pageid'])) : 1;
$posts_per_page = $site_vars['site_posts_per_page'];
$myfeed       = get_userfeed($user['id'], $posts_per_page, ($page_id - 1) * $posts_per_page);
$follows_list = json_decode($user['follows'], true) ?? [];
$follower_count = ui_num_followers($user['id']);

drawheader(true);
?>

<h2 class="thumb">
	<a href="/user/<?php echo htmlspecialchars($user['username']); ?>">
		<img alt="<?php echo htmlspecialchars($user['displayname'] ?: $user['username']); ?>'s Avatar"
			border="0" src="<?php echo htmlspecialchars($user['avatar_url'] ?: 'res/default_avatar.png'); ?>"
			valign="middle"/>
	</a>
	<?php echo htmlspecialchars($user['displayname'] ?: $user['username']); ?>
	<small>@<?php echo htmlspecialchars($user['username']); ?></small>
</h2>

<?php if (!empty($myfeed)): $latest = $myfeed[0]; ?>
<div class="desc">
	<p><?php echo htmlspecialchars($latest['content']); ?></p>
	<p class="meta">
		<a href="/status/<?php echo (int)$latest['id']; ?>"><?php echo format_time_ago($latest['created_at']); ?></a>
		from <?php echo htmlspecialchars($latest['source'] ?? 'web'); ?>
		<?php if (is_logged_in()): ?>
		<span id="status_actions_<?php echo (int)$latest['id']; ?>">
			<font color="<?php echo htmlspecialchars($site_vars['fave_color']); ?>"><a href="/fave/<?php echo (int)$latest['id']; ?>">[<?php echo htmlspecialchars($site_vars['fave_name']); ?>]</a></font>
			| <font color="<?php echo htmlspecialchars($site_vars['repost_color']); ?>"><a href="/repost/<?php echo (int)$latest['id']; ?>">[<?php echo htmlspecialchars($site_vars['repost_name']); ?>]</a></font>
		</span>
		<?php endif; ?>
	</p>
</div>
<?php endif; ?>

<ul class="tabMenu">
	<?php if ($page_id > 1): ?>
	<li><a href="/user/<?php echo htmlspecialchars($user['username']); ?>?pageid=<?php echo $page_id - 1; ?>">&laquo; newer</a></li>
	<?php else: ?>
	<li class="disablepage">&laquo; newer</li>
	<?php endif; ?>
	<li class="active currentpage"><?php echo (int)$page_id; ?></li>
	<?php if (count($myfeed) === $posts_per_page): ?>
	<li><a href="/user/<?php echo htmlspecialchars($user['username']); ?>?pageid=<?php echo $page_id + 1; ?>">older &raquo;</a></li>
	<?php else: ?>
	<li class="disablepage">older &raquo;</li>
	<?php endif; ?>
</ul>

<div class="tab">
<?php if (empty($myfeed)): ?>
	<p style="padding:1em;">No updates yet.</p>
<?php else: ?>
	<table class="doing" id="timeline" cellspacing="0">
	<?php foreach ($myfeed as $index => $post):
		$is_repost = (int)$post['user_id'] !== (int)$user['id'];
		if ($is_repost) {
			$op = get_user_by_id($post['user_id']);
		}
		$trclass = $index % 2 == 0 ? 'even' : 'odd';
	?>
		<tr class="<?php echo $trclass; ?>" id="status_<?php echo (int)$post['id']; ?>">
			<td>
				<?php if ($is_repost): ?>
				<span class="meta" style="font-style:italic;">
					<?php echo htmlspecialchars($site_vars['repost_short_name'] ?? 'RT'); ?>
					<a href="/user/<?php echo htmlspecialchars($op['username']); ?>">@<?php echo htmlspecialchars($op['username']); ?></a>
				</span><br/>
				<?php endif; ?>
				<?php echo htmlspecialchars($post['content']); ?>
				<span class="meta">
					<a href="/status/<?php echo (int)$post['id']; ?>"><?php echo format_time_ago($post['created_at']); ?></a>
					from <?php echo htmlspecialchars($post['source'] ?? 'web'); ?>
					<?php if (is_logged_in()): ?>
					<span id="status_actions_<?php echo (int)$post['id']; ?>">
						<font color="<?php echo htmlspecialchars($site_vars['fave_color']); ?>"><a href="/fave/<?php echo (int)$post['id']; ?>">[<?php echo htmlspecialchars($site_vars['fave_name']); ?>]</a></font>
						| <font color="<?php echo htmlspecialchars($site_vars['repost_color']); ?>"><a href="/repost/<?php echo (int)$post['id']; ?>">[<?php echo htmlspecialchars($site_vars['repost_name']); ?>]</a></font>
					</span>
					<?php endif; ?>
				</span>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<span class="statuses_options">
		<a href="/rss/@<?php echo htmlspecialchars($user['username']); ?>.rss">RSS Feed</a>
	</span>
</div>

		</div></div><hr/>

	<div id="side">

		<div class="msg">
			About <strong><?php echo htmlspecialchars($user['displayname'] ?: $user['username']); ?></strong>
		</div>

		<ul class="about">
			<?php if ($user['bio']): ?>
			<li><?php echo htmlspecialchars($user['bio']); ?></li>
			<?php endif; ?>
		</ul>

		<ul>
			<li><strong><?php echo (int)get_user_total_posts_number($user['id']); ?></strong> updates</li>
			<li><strong><?php echo count($follows_list); ?></strong> following</li>
			<li><strong><?php echo (int)$follower_count; ?></strong> follower<?php echo $follower_count == 1 ? '' : 's'; ?></li>
			<li><a href="/faved/<?php echo htmlspecialchars($user['username']); ?>"><?php echo (int)ui_get_number_favorited($user['id']); ?> favorites</a></li>
		</ul>

		<div id="friends">
		<?php
		$topfollowers = get_top_followers($user['id']);
		if (count($topfollowers) > 0) {
			foreach ($topfollowers as $follower) {
				echo '<a href="/user/' . htmlspecialchars($follower['username']) . '" rel="contact" title="' . htmlspecialchars($follower['displayname'] ?: $follower['username']) . '">'
					. '<img alt="' . htmlspecialchars($follower['username']) . '" height="24" width="24" src="' . htmlspecialchars($follower['avatar_url'] ?: 'res/default_avatar.png') . '"/>'
					. '</a>';
			}
		} else {
			echo '<p>No followers yet.</p>';
		}
		?>
		</div>

		<?php if (!is_logged_in()): ?>
		<div class="notify">
			Want an account?<br/>
			<a href="/register" class="join">Join for Free!</a><br/>
			Have an account? <a href="/login">Sign in!</a>
		</div>
		<?php elseif ((int)$_SESSION['user_id'] !== (int)$user['id']):
			$viewer_follows = is_following($_SESSION['user_id'], $user['id']); ?>
		<div class="actions">
			<?php if ($viewer_follows): ?>
			<a href="/unfollow/<?php echo htmlspecialchars($user['username']); ?>">Unfollow</a>
			<?php else: ?>
			<a href="/follow/<?php echo htmlspecialchars($user['username']); ?>" style="font-weight:bold;">Follow <?php echo htmlspecialchars($user['displayname'] ?: $user['username']); ?>!</a>
			<?php endif; ?>
		</div>
		<?php endif; ?>

	</div>

<?php drawfooter(); ?>

