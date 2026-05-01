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
// join.php
// you put in your username, email, and password; if they're not already used we create a new account.

session_start();
include("../functions.php");

if (is_logged_in()) {
    redirect("/latest");
}

$error   = null;
$success = false;
$fields  = ['username' => '', 'email' => '', 'name' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = trim($_POST['username'] ?? '');
    $email     = trim($_POST['email']    ?? '');
    $name      = trim($_POST['name']     ?? '');
    $password  = $_POST['password']              ?? '';
    $password2 = $_POST['password_confirm']      ?? '';

    $fields = ['username' => $username, 'email' => $email, 'name' => $name];

    if ($username === '' || $email === '' || $name === '' || $password === '') {
        $error = "Please fill in all fields.";
    } elseif (!preg_match('/^[A-Za-z0-9_]{1,20}$/', $username)) {
        $error = "Username may only contain letters, numbers, and underscores, and must be 20 characters or fewer.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "That doesn&rsquo;t look like a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $password2) {
        $error = "Passwords don&rsquo;t match.";
    } else {
        if (get_user_by_username($username) !== null) {
            $error = "That username is already taken. Try another!";
        } else {
            if (create_user($username, $password, $email, $name)) {
                // log them straight in
                do_login($username, $password);
                redirect("/latest");
            } else {
                $error = "Something went wrong creating your account. Please try again.";
            }
        }
    }
}

$error = $error ?? get_notif_banner();

drawheader(false);
?>

		<h2>Join <?php echo htmlspecialchars($site_vars['site_name']); ?> for free!</h2>

		<p style="margin: 1em 0;"><?php echo htmlspecialchars($site_vars['site_name']); ?> tracks what you&rsquo;re doing &mdash; and lets your friends know, too.
		Sign up in seconds and start sharing what you&rsquo;re up to right now.</p>

		<p style="margin: 1em 0;">Already have an account? <a href="/login">Sign in!</a></p>

		</div></div><hr/>

	<div id="side">

		<?php if ($error): ?>
		<div class="notify" style="background:#ffdddd; border-color:#cc0000; color:#cc0000; margin-bottom:8px;">
			<?php echo $error; ?>
		</div>
		<?php endif; ?>

		<div class="msg">
			<h3>Create your account.</h3>
		</div>

		<form action="/account/create" class="signin" method="post" name="f">
			<fieldset>
				<div>
					<label for="username">Username</label>
					<input id="username" name="username" type="text" maxlength="20"
						value="<?php echo htmlspecialchars($fields['username']); ?>"/>
				</div>
				<div>
					<label for="email">Email</label>
					<input id="email" name="email" type="text"
						value="<?php echo htmlspecialchars($fields['email']); ?>"/>
				</div>
				<div>
					<label for="name">Full name</label>
					<input id="name" name="name" type="text" maxlength="255"
						value="<?php echo htmlspecialchars($fields['name']); ?>"/>
				</div>
				<div>
					<label for="pass">Password</label>
					<input id="pass" name="password" type="password"/>
				</div>
				<div>
					<label for="pass2">Confirm password</label>
					<input id="pass2" name="password_confirm" type="password"/>
				</div>
				<input id="submit" name="commit" type="submit" value="Join &rarr;"/>
			</fieldset>
		</form>

		<script type="text/javascript">
		//<![CDATA[
		document.getElementById('username').focus();
		//]]>
		</script>

		<div class="notify">
			Already have an account?<br/>
			<a href="/login">Sign in!</a>
		</div>

	</div>

<?php drawfooter(); ?>