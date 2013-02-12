<!DOCTYPE html>
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : EarthlingTwo  
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20090918
-->

<head>
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">

<meta name="keywords" content="sencha, extjs, sencha touch, sencha JavaScript Framework" />
<meta name="description" content="" />

<title>Jason Levinsohn</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" media="screen" />
<style type="text/css">


</style>
<?php

error_reporting(E_ALL);
$webSiteName="Portfolio";


?>

<?php #include('loginScript.php'); ?>



</head>
<body>
<div id="wrapper">
	<div id="header">
		
			<div id="logo">
				<h1><a href="#"><?php echo $webSiteName ?> </a></h1>
				<p>of <a href="http://www.jasonlevinsohn.com">Jason Levinsohn</a></p>
			</div>
			
	</div>
	<!-- end #header -->
	<div id="menu">
		<ul class="tabs">
			<li ><a href="http://www.jasonlevinsohn.com">Home</a></li>
			
			<li class="current_page_item"><a href="/sm4/intro.php" style="padding-bottom: 30px; padding-top: 5px;">Sencha Example</a>
				<!--
				<ul class="dropdown">
					<li><a href="#">Services</a></li>
					<li><a href="#">New Services</a></li>
					<li><a href="#">Old Services</a></li>
				</ul>
				-->
			</li>
			<!-- <li><a href="#">Quantum</a></li> -->
			<li><a target="_blank" href="http://www.levsdelight.com" style="padding-bottom: 30px; padding-top: 5px;">LevsDelight Web Site</a>
			<li><a href="http://www.jasonlevinsohn.com/portfolio/index.htm" style="padding-bottom: 30px; padding-top: 5px;">Graduate Portfolio</a></li>
			
			
		</ul>
	</div>
	<!-- end #menu -->
	<div id="page">
		<div id="content">
			<!-- <div id="banner"><img src="images/img07.jpg" width="436px" alt="" /></div> -->
			<div class="post">
				<h2 class="title"><a href="#">Sencha Framework Demonstration</a></h2>
				<p class="meta">Task and Project Manager</p>
				<div class="entry">
					<p>
					I have built this application in my spare time.  I would love to make it my career.
					It is a fully functional application demonstrating the awesomeness of the 
					   <a target="_blank" href="http://www.sencha.com/products/extjs/">Sencha JavaScript Framework ExtJS</a> for building Rich 
					   Interactive Applications.  It works perfectly in all major browsers including Chrome, FireFox, Safari,
					  IE, and even the Apple iPad.
					  <ul>
						<li>MySQL database</li>
						<li>PHP Server-side code</li>
						<li>JSON for data-transfer</li>
					  </ul>
					</p>
				
					
						<h1>Tutorial to see features</h1>
						<ol>
							<li>Click on any of the tasks to change the status</li>
							<li>Click Profiles on the East Border to view Site Information</li>
						</ol>

						<h3>Login Required to follow these steps</h3>
						<ol>
							<li>Select (New Site) in Main Command Bar</li>
							<li>Enter Site Number and Name</li>
							<li>Select (New Profile) in Main Command Bar</li>
							<li>Select Profile from Drop Down</li>
							<li>Enter profile Data</li>
							<ul>
								<li>Be selecting a date, this sites will be ordered accordingly in Task List and Profile List</li>
							</ul>
							<li>Save Profile Data</li>
							<li>Create a "Site Note"</li>
							
							

						</ol>
						
					</p>

					
					
				</div>
			</div>
			<div style="clear: both;">&nbsp;</div>
		</div>
		<!-- end #content -->
		<div id="sidebar">
			<ul>
				<li>
					<h2>The Demo</h2>
					<ul>
						<li><span class="fall">Version: SM4</span><a target="_blank" href="http://www.jasonlevinsohn.com/sm4/">ExtJS Demo</a></li>
						
					</ul>
				</li>
			</ul>
		</div>
		<!-- end #sidebar -->
		<div style="clear: both;">&nbsp;</div>
	</div>
	<!-- end #page -->
</div>
<div id="footer-content">
	<div class="column1">
		<!--
		<h2>Volutpat quisque sed et aliquam</h2>
		<p><strong>Maecenas ut ante</strong> eu velit laoreet tempor accumsan vitae nibh. Aenean commodo, tortor eu porta convolutpat elementum. Proin fermentum molestie erat eget vehicula. Aenean eget tellus mi. Fusce scelerisque odio quis ante bibendum sollicitudin. Suspendisse potenti. Vivamus quam odio, facilisis at ultrices nec, sollicitudin ac risus. Donec ut odio ipsum, sed tincidunt. <a href="#">Learn more&#8230;</a></p>
		-->
	</div>
	<div class="column2">
		<!--
		<ul class="list">
			<li><a href="#">Tempor accumsan vitae sed nibh dolore</a></li>
			<li><a href="#">Aenean commodo, tortor eu porta veroeros</a></li>
			<li><a href="#">Fermentum molestie erat eget consequat</a></li>
			<li><a href="#">Donec vestibulum interdum diam etiam</a></li>
			<li><a href="#">Vehicula aenean eget sed tellus blandit</a></li>
		</ul>
		-->
	</div>
</div>
<div id="footer">
	<p> (c) 2012 jasonlevinsohn.com. </p>
</div>
<!-- end #footer -->
<div id="login">
				<?php #if($isLoggedIn) { ?>
						<form action="index.php" name="logout" id="logout">
							<?php #echo "Welcome, $_GET['email']"; ?>
							<input type="submit" value="Logout" />
							
						</form>
				<?php #} else { ?>
						<form action="index.php" name="login" id="login">
						Email: <input type="email" name="email"/>
						Pass: <input type="password" name="pass" />
						Encryption: <keygen name="key" />
							  
						<input type="submit" value="Login" />
						<?php #if(isLoginAttempted) {echo 'User/pass invalid';} ?>
					</form>
				<?php #} ?>	
			</div>
</body>
</html>
