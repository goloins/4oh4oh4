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

// status.php - status page, displays a single post and its details. 
// great for permalinks and sharing individual posts.
session_start();
include("functions.php");

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post    = get_post_by_id($post_id);

if (!$post) {
    drawheader(false);
    echo "<h1>Post not found.</h1>";
    drawfooter();
    exit();
}

$poster   = get_user_by_id($post['user_id']);
$avatar   = $poster['avatar_url'] ?: 'res/default_avatar.png';
$dispname = $poster['displayname'] ?: $poster['username'];
$replies  = get_replies_for_post($post_id);

drawheader(false);
?>

<h2 class="thumb">
    <a href="/user/<?php echo htmlspecialchars($poster['username']); ?>">
        <img src="<?php echo htmlspecialchars($avatar); ?>" alt="<?php echo htmlspecialchars($dispname); ?>'s Avatar" border="0"/>
    </a>
    <a href="/user/<?php echo htmlspecialchars($poster['username']); ?>"><?php echo htmlspecialchars($dispname); ?></a>
</h2>

<div class="desc">
    <p><?php echo htmlspecialchars($post['content']); ?></p>
    <p class="meta">
        <a href="/status/<?php echo (int)$post['id']; ?>"><?php echo format_time_ago($post['created_at']); ?></a>
        from <?php echo htmlspecialchars($post['source'] ?? 'web'); ?>
        <?php if (is_logged_in()): ?>
        <span id="status_actions_<?php echo (int)$post['id']; ?>">
            <font color="<?php echo htmlspecialchars($site_vars['fave_color']); ?>"><a href="/fave/<?php echo (int)$post['id']; ?>">[<?php echo htmlspecialchars($site_vars['fave_name']); ?>]</a></font>
            | <font color="<?php echo htmlspecialchars($site_vars['repost_color']); ?>"><a href="/repost/<?php echo (int)$post['id']; ?>">[<?php echo htmlspecialchars($site_vars['repost_name']); ?>]</a></font>
        </span>
        <?php endif; ?>
    </p>
</div>

<?php if (is_logged_in()): ?>
<div id="doingForm">
    <div class="bar">
        <h3>Reply to <?php echo htmlspecialchars($dispname); ?></h3>
        <span id="char_count">140</span>
    </div>
    <div class="info">
        <form action="/reply/<?php echo (int)$post_id; ?>" method="post">
            <textarea name="content" id="status" maxlength="140" rows="3"
                onkeyup="document.getElementById('char_count').innerHTML = 140 - this.value.length;"
                ><?php echo '@' . htmlspecialchars($poster['username']) . ' '; ?></textarea>
            <div class="submit">
                <input type="submit" id="submit" value="Reply"/>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($replies)): ?>
<table class="doing" id="timeline" cellspacing="0">
<?php foreach ($replies as $index => $reply):
    $rep_user = get_user_by_id($reply['user_id']);
    $rep_avatar = $rep_user['avatar_url'] ?: 'res/default_avatar.png';
    $rep_disp   = $rep_user['displayname'] ?: $rep_user['username'];
    $trclass    = $index % 2 == 0 ? 'even' : 'odd';
?>
    <tr class="<?php echo $trclass; ?>">
        <td class="thumb">
            <a href="/user/<?php echo htmlspecialchars($rep_user['username']); ?>">
                <img src="<?php echo htmlspecialchars($rep_avatar); ?>" alt="<?php echo htmlspecialchars($rep_disp); ?>'s Avatar"/>
            </a>
        </td>
        <td>
            <strong><a href="/user/<?php echo htmlspecialchars($rep_user['username']); ?>"><?php echo htmlspecialchars($rep_disp); ?></a></strong>
            <p><?php echo htmlspecialchars($reply['content']); ?></p>
            <span class="meta"><?php echo format_time_ago($reply['created_at']); ?> from web</span>
        </td>
    </tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

        </div></div><hr/>

    <div id="side">
        <div class="msg">
            <h3>About <a href="/user/<?php echo htmlspecialchars($poster['username']); ?>">@<?php echo htmlspecialchars($poster['username']); ?></a></h3>
        </div>
        <ul>
            <li><strong><?php echo (int)get_user_total_posts_number($poster['id']); ?></strong> updates</li>
            <li><strong><?php echo (int)ui_num_followers($poster['id']); ?></strong> followers</li>
            <?php if (!empty($replies)): ?>
            <li><strong><?php echo count($replies); ?></strong> repl<?php echo count($replies) == 1 ? 'y' : 'ies'; ?></li>
            <?php endif; ?>
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

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = get_post_by_id($post_id);

if (!$post) {
    echo "<p>Post not found.</p>";
} else {
    echo "<h2>Post by @" . htmlspecialchars(get_user_by_id($post['user_id'])['username']) . "</h2>";
    echo "<p>" . htmlspecialchars($post['content']) . "</p>";
    echo "<p>Posted " . format_time_ago($post['created_at']) . "</p>";
}
// so we need a box to contain the replies, should be a slightly darkened div below the post content.
?>
<div id="replies" style="background-color: #f0f0f0; padding: 10px; margin-top: 20px;">
    <h3>Replies</h3>
    <?php
    $replies = get_replies_for_post($post_id);
    if (count($replies) == 0) {
        echo "<p>No replies yet.</p>";
    } else {
        foreach ($replies as $reply) {
            echo "<div class='reply' style='border-bottom: 1px solid #ccc; padding: 5px 0;'>";
            echo "<p><strong>@" . htmlspecialchars(get_user_by_id($reply['user_id'])['username']) . "</strong>: " . htmlspecialchars($reply['content']) . "</p>";
            echo "<p>Posted " . format_time_ago($reply['created_at']) . "</p>";
            echo "</div>";
        }
    }
    ?>
</div>


<?php

drawfooter();
