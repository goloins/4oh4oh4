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

// faved.php - displays a list of fave'd posts for a given user.
session_start();
include("functions.php");

$username = isset($_GET['username']) ? trim($_GET['username']) : '';
if ($username === '') {
    drawheader(false);
    echo "<h1>No user specified</h1>";
    drawfooter();
    exit;
}

$user = get_user_by_username($username);
if (!$user) {
    drawheader(false);
    echo "<h1>User not found</h1>";
    drawfooter();
    exit;
}

// get_user_favorites returns [['user_id' => X, 'post_id' => Y], ...]
$favorite_rows = get_user_favorites($user['id']);

drawheader(false);
?>

<h2><?php echo htmlspecialchars($user['displayname'] ?: $user['username']); ?>&rsquo;s favorites</h2>

<?php if (empty($favorite_rows)): ?>
<p style="margin:1em 0;"><?php echo htmlspecialchars($user['username']); ?> hasn&rsquo;t faved anything yet.</p>
<?php else: ?>

<table class="doing" id="timeline" cellspacing="0">
<?php foreach ($favorite_rows as $index => $row):
    $post = get_post_by_id($row['post_id']);
    if (!$post) continue; // post may have been deleted
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
            </span>
        </td>
    </tr>
<?php endforeach; ?>
</table>

<?php endif; ?>

        </div></div><hr/>

    <div id="side">
        <div class="msg">
            <h3><a href="/user/<?php echo htmlspecialchars($user['username']); ?>">@<?php echo htmlspecialchars($user['username']); ?></a></h3>
        </div>
        <ul>
            <li><strong><?php echo count($favorite_rows); ?></strong> favorites</li>
            <li><strong><?php echo (int)get_user_total_posts_number($user['id']); ?></strong> updates</li>
        </ul>
        <?php if (!is_logged_in()): ?>
        <div class="notify">
            Want an account?<br/>
            <a href="/register" class="join">Join for Free!</a><br/>
            Have an account? <a href="/login">Sign in!</a>
        </div>
        <?php endif; ?>
    </div>

<?php drawfooter(); ?>