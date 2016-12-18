<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title>HTML</title>
		<meta name="description" content="">
		<meta name="author" content="root">

		<meta name="viewport" content="width=device-width; initial-scale=1.0">

		<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
		<link rel="shortcut icon" href="/favicon.ico">
		<link rel="apple-touch-icon" href="/apple-touch-icon.png">
	</head>

	<body>
		<div>
			<header>
				<h1>HTML</h1>
			</header>
			<nav>
				<p>
					<a href="/">Home</a>
				</p>
				<p>
					<a href="/contact">Contact</a>
				</p>
				
				<form action="test_page.php" method="post">
					<select name='select'><option>Shit</option></select>
					<input type="checkbox" name="tester" value="check me" />
					<input type="checkbox" name="tester2" value="check_me2"	/>
					<input type="submit" />
				</form>
				
			</nav>

			<div>

			</div>

			<footer>
				<p>
					&copy; Copyright  by root
				</p>
			</footer>
		</div>
	</body>
</html>

<?php

	$test['Candidate'][0][1]= array('score' => 100);
	$test['candidate'][1][2]=array('score'=>99);
	
	$arr_len = count($test);

	$my_arra[10] = 10;
	$my_arra[23] = 23;
	
	var_dump($_POST);
	
	//var_dump($test);
?>