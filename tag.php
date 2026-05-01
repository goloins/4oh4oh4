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
// tag.php - shows posts associated with a specific tag.
session_start();
include("functions.php");

$tag = isset($_GET['tag']) ? trim($_GET['tag']) : '';
if ($tag === '') {
    header("Location: /");
    exit();
}

// strip a leading # if someone passes it in the URL
$tag = ltrim($tag, '#');

$page_id        = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? max(1, intval($_GET['pageid'])) : 1;
$posts_per_page = $site_vars['site_posts_per_page'];
$offset         = ($page_id - 1) * $posts_per_page;

$tag_posts = get_hashtag_feed($tag, $posts_per_page, $offset);

drawheader(false);
?>

<h2>Posts tagged <strong>#<?php echo htmlspecialchars($tag); ?></strong></h2>

<?php if (empty($tag_posts)): ?>
<p style="margin:1em 0;">No posts found for <strong>#<?php echo htmlspecialchars($tag); ?></strong>.</p>
<?php else: ?>

<table class="doing" id="timeline" cellspacing="0">
<?php foreach ($tag_posts as $index => $post):
    $poster   = get_user_by_id($post['user_id']);
    $avatar   = $poster['avatar_url'] ?: 'res/default_avatar.png';
    $dispname = $poster['displayname'] ?: $poster['username'];
    $trclass  = $index % 2 == 0 ? 'even' : 'odd';
?>
    <tr class="<?php echo $trclass; ?>" id="status_<?php echo (int)$post['id']; ?>">
        <td class="thumb">
            <a href="/user/<?php echo htmlspecialchars($poster['username']); ?>">
                <img src="<?php echo htmlspecialchars($avatar); ?>" alt="<?php echo htmlspecialchars($dispname); ?>'s Avatar"/>
            </a>
        </td>
        <td>
            <strong><a href="/user/<?php echo htmlspecialchars($poster['username']); ?>"><?php echo htmlspecialchars($dispname); ?></a></strong>
            <p><?php echo htmlspecialchars($post['content']); ?></p>
            <span class="meta">
                <a href="/status/<?php echo (int)$post['id']; ?>"><?php echo format_time_ago($post['created_at']); ?></a> from web
                <?php if (is_logged_in()): ?>
                &middot; <a href="/fave/<?php echo (int)$post['id']; ?>">[<?php echo htmlspecialchars($site_vars['fave_name']); ?>]</a>
                &middot; <a href="/repost/<?php echo (int)$post['id']; ?>">[<?php echo htmlspecialchars($site_vars['repost_name']); ?>]</a>
                <?php endif; ?>
            </span>
        </td>
    </tr>
<?php endforeach; ?>
</table>

<div style="text-align:right; margin-top:8px; font-size:0.9em;">
    <?php if ($page_id > 1): ?>
        <a href="/tag/<?php echo urlencode($tag); ?>?pageid=<?php echo $page_id - 1; ?>">&laquo; newer</a> &nbsp;
    <?php endif; ?>
    <?php if (count($tag_posts) === $posts_per_page): ?>
        <a href="/tag/<?php echo urlencode($tag); ?>?pageid=<?php echo $page_id + 1; ?>">older &raquo;</a>
    <?php endif; ?>
</div>

<?php endif; ?>

        </div></div><hr/>

    <div id="side">
        <div class="msg">
            <h3>#<?php echo htmlspecialchars($tag); ?></h3>
        </div>
        <div class="notify">
            <a href="/rss/#<?php echo urlencode($tag); ?>.rss">RSS Feed</a>
        </div>
        <?php if (!is_logged_in()): ?>
        <div class="notify">
            Want an account?<br/>
            <a href="/register" class="join">Join for Free!</a><br/>
            Have an account? <a href="/login">Sign in!</a>
        </div>
        <?php endif; ?>
    </div>

<?php drawfooter(); ?>