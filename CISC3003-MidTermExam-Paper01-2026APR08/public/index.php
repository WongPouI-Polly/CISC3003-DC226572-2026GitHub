<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Form</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx40nYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="css/styles.css">
<script src="js/script.js" defer></script>
</head>
<body>

<div class="container" id="container">
	<div class="form-container sign-up-container">
		<form action="#">
			<h1>Join Us</h1>
			<div class="social-container">
				<a href="#" class="social"><i class="fab fa-facebook"></i></a>
				<a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
				<a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
			</div>
			<span>Use Your Email To SignUp</span>
			<input type="text" name="name" placeholder="Enter your name">
			<input type="password" name="password" placeholder="Create password">
			<button type="submit">Register</button>
		</form>
	</div>

	<div class="form-container sign-in-container">
		<form action="#">
			<h1>Login</h1>
			<div class="social-container">
				<a href="#" class="social"><i class="fab fa-facebook"></i></a>
				<a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
				<a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
			</div>
			<span>Use Your account login</span>
			<input type="text" name="name" placeholder="Enter your name">
			<input type="password" name="password" placeholder="Enter password">
			<button type="submit">Login</button>
		</form>
	</div>

	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Hello, Again</h1>
				<img src="images/website_7376495.png" style="width: 150px; margin-top: 20px;">
				<p>Log in to stay connected with us</p>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Welcome</h1>
				<img src="images/unsecure_10399884.png" style="width: 150px; margin-top: 20px;">
				<p>Log in to stay connected with us</p>
				<button class="ghost" id="signUp">Sign Up</button>
			</div>
		</div>
	</div>
</div>

</body>
</html>