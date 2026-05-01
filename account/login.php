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
// login.php
// you put in your username and password, and if they're correct, we log you in.

session_start();
include("../functions.php");

if (is_logged_in()) {
    redirect("/latest");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        set_notif_banner("Please fill in both fields.");
    } else {
        if (do_login($username, $password)) {
            redirect("/latest");
        }
        // do_login() sets notif_banner on failure; fall through to show form
    }
}

$error = get_notif_banner();

drawheader(false);
?>

		<h2><?php echo htmlspecialchars($site_vars['site_name']); ?> tracks what you&rsquo;re doing &mdash; and lets your friends know, too.</h2>

		<p style="margin: 1em 0;">It&rsquo;s the easiest way to stay in touch. Type a short message from the web, your phone,
		or your instant messenger and <?php echo htmlspecialchars($site_vars['site_name']); ?> sends it to all your friends.</p>

		<p style="margin: 1em 0;">Not a member yet? <a href="/account/create">Join for free!</a> It only takes a second.</p>

		</div></div><hr/>

	<div id="side">

		<?php if ($error): ?>
		<div class="notify" style="background:#ffdddd; border-color:#cc0000; color:#cc0000; margin-bottom:8px;">
			<?php echo htmlspecialchars($error); ?>
		</div>
		<?php endif; ?>

		<div class="msg">
			<h3>Sign in.</h3>
		</div>

		<form action="/login" class="signin" method="post" name="f">
			<fieldset>
				<div>
					<label for="username">Username</label>
					<input id="username" name="username" type="text" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"/>
				</div>
				<div>
					<label for="pass">Password</label>
					<input id="pass" name="password" type="password"/>
				</div>
				<input id="remember" name="remember_me" type="checkbox" value="1"/> <label for="remember">Remember me</label>
				&nbsp; <small><a href="/account/reset_password">Forgot?</a></small>
				<input id="submit" name="commit" type="submit" value="Sign in &rarr;"/>
			</fieldset>
		</form>

		<script type="text/javascript">
		//<![CDATA[
		document.getElementById('username').focus();
		//]]>
		</script>

		<div class="notify">
			New to <?php echo htmlspecialchars($site_vars['site_name']); ?>?<br/>
			<a href="/account/create" class="join">Join for Free!</a><br/>
			It&rsquo;s fast and easy!
		</div>

	</div>

<?php drawfooter(); ?>