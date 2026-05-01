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

// latest.php - shows the latest posts for the logged in user (home timeline)
session_start();
include("functions.php");

ensure_logged_in();

$page_id        = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? max(1, intval($_GET['pageid'])) : 1;
$posts_per_page = $site_vars['site_posts_per_page'];
$offset         = ($page_id - 1) * $posts_per_page;

$me          = get_user_by_id($_SESSION['user_id']);
$my_follows  = json_decode($me['follows'], true) ?? [];
$letsgetit   = build_user_feed($_SESSION['user_id'], $posts_per_page, $offset);
$notif       = get_notif_banner();

drawheader(true);
?>

<div id="doingForm">
	<div class="bar">
		<h3>What are you doing?</h3>
		<span id="char_count">140</span>
	</div>
	<div class="info">
		<form action="/post" method="post">
			<textarea name="content" id="status" maxlength="140" rows="3"
				onkeyup="document.getElementById('char_count').innerHTML = 140 - this.value.length;"></textarea>
			<div class="submit">
				<input type="submit" id="submit" value="Update"/>
			</div>
		</form>
	</div>
</div>

<?php if ($notif): ?>
<p style="background:#ffdddd; border:1px solid #cc0000; color:#cc0000; padding:5px 10px; margin:6px 0;">
	<?php echo htmlspecialchars($notif); ?>
</p>
<?php endif; ?>

<?php if (empty($letsgetit)): ?>
<p style="margin:1em 0;">Your timeline is empty! Follow some people from the <a href="/world">world timeline</a> to get started.</p>
<?php else: ?>

<table class="doing" id="timeline" cellspacing="0">
<?php foreach ($letsgetit as $index => $post):
	$poster      = get_user_by_id($post['user_id']);
	$avatar      = $poster['avatar_url'] ?: 'res/default_avatar.png';
	$displayname = $poster['displayname'] ?: $poster['username'];
	$is_repost   = (int)$post['feed_user_id'] !== (int)$post['user_id'];
?>
	<tr class="<?php echo $index % 2 == 0 ? 'even' : 'odd'; ?>" id="status_<?php echo (int)$post['id']; ?>">
		<td class="thumb">
			<a href="/user/<?php echo htmlspecialchars($poster['username']); ?>">
				<img src="<?php echo htmlspecialchars($avatar); ?>" alt="<?php echo htmlspecialchars($displayname); ?>'s Avatar"/>
			</a>
		</td>
		<td>
			<?php if ((int)$post['feed_user_id'] !== (int)$post['user_id']):
				$reposter = get_user_by_id($post['feed_user_id']); ?>
			<span class="meta" style="font-style:italic;">&#8635; reposted by <a href="/user/<?php echo htmlspecialchars($reposter['username']); ?>"><?php echo htmlspecialchars($reposter['displayname'] ?: $reposter['username']); ?></a></span><br/>
			<?php endif; ?>
			<strong><a href="/user/<?php echo htmlspecialchars($poster['username']); ?>"><?php echo htmlspecialchars($displayname); ?></a></strong>
			<p><?php echo htmlspecialchars($post['content']); ?></p>
			<span class="meta">
				<a href="/status/<?php echo (int)$post['id']; ?>"><?php echo format_time_ago($post['created_at']); ?></a> from web
				&middot; <a href="/fave/<?php echo (int)$post['id']; ?>">fave</a>
				&middot; <a href="/repost/<?php echo (int)$post['id']; ?>">repost</a>
			</span>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<div style="text-align:right; margin-top:8px; font-size:0.9em;">
	<?php if ($page_id > 1): ?>
		<a href="/latest?pageid=<?php echo $page_id - 1; ?>">&laquo; newer</a> &nbsp;
	<?php endif; ?>
	<?php if (count($letsgetit) === $posts_per_page): ?>
		<a href="/latest?pageid=<?php echo $page_id + 1; ?>">older &raquo;</a>
	<?php endif; ?>
</div>

<?php endif; ?>

		</div></div><hr/>

	<div id="side">

		<div class="msg">
			<strong><?php echo htmlspecialchars($me['displayname'] ?: $me['username']); ?></strong>
			<h3><a href="/user/<?php echo htmlspecialchars($me['username']); ?>">@<?php echo htmlspecialchars($me['username']); ?></a></h3>
		</div>

		<ul>
			<li><strong><?php echo (int)get_user_total_posts_number($me['id']); ?></strong> updates</li>
			<li><strong><?php echo count($my_follows); ?></strong> following</li>
			<li><strong><?php echo (int)ui_num_followers($me['id']); ?></strong> followers</li>
		</ul>

		<div class="actions">
			<a href="/user/<?php echo htmlspecialchars($me['username']); ?>">My profile</a><br/>
			<a href="/faved/<?php echo htmlspecialchars($me['username']); ?>">My favorites</a>
		</div>

		<div class="notify">
			<a href="/world">World timeline</a><br/>
			<a href="/rss/@<?php echo htmlspecialchars($me['username']); ?>.rss">My RSS feed</a>
		</div>

	</div>

<?php drawfooter(); ?>
