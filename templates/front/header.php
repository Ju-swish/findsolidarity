<!doctype html>
<html lang="de" prefix="og: http://ogp.me/ns#">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO -->
    <title><?php echo htmlspecialchars($results['pageTitle']); ?></title>
    <meta name="description" content="Zeige Solidarität und hilf! Wegen Coronavirus oder auch covid-19, SARS-CoV19">

    <!-- URL CANONICAL -->
    <link rel="canonical" href="http://findsolidarity.com/">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,700,700i%7CMaitree:200,300,400,600,700&amp;subset=latin-ext" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" type='text/css' media='all' href="assets/css/genesis.css">
    <link rel="stylesheet" type='text/css' media='all' href="assets/css/custom.css">

    <!-- Optional - CSS SVG Icons (Font Awesome) -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <!-- SOCIAL CARDS (ADD YOUR INFO) -->

    <!-- FACEBOOK -->
    <meta property="og:url" content="http://findsolidarity.com/">
    <meta property="og:type" content="article">
    <meta property="og:title" content="Find Solidarity - Zusammen gegen Coronavirus">
    <meta property="og:description" content="Zeige Solidarität und hilf den Menschen in deiner Umgebung!">
    <meta property="og:updated_time" content="15.03.2020">
    <meta property="og:image" content="assets/img/logo.png">

    <!-- TWITTER -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@findsolidarity">
    <meta name="twitter:creator" content="@realKhani">
    <meta name="twitter:title" content="Find Solidarity - Zusammen gegen Coronavirus">
    <meta name="twitter:description" content="Zeige Solidarität und hilf den Menschen in deiner Umgebung!">
    <meta name="twitter:image" content="assets/img/logo.png">

    <!-- FAVICONS -->
    <link rel="shortcut icon" sizes="16x16" href="assets/img/favicons/favicon.png">
    <link rel="shortcut icon" sizes="32x32" href="assets/img/favicons/favicon-32.png">
    <link rel="apple-touch-icon icon" sizes="76x76" href="assets/img/favicons/favicon-76.png">
    <link rel="apple-touch-icon icon" sizes="120x120" href="assets/img/favicons/favicon-120.png">
    <link rel="apple-touch-icon icon" sizes="152x152" href="assets/img/favicons/favicon-152.png">
    <link rel="apple-touch-icon icon" sizes="180x180" href="assets/img/favicons/favicon-180.png">
    <link rel="apple-touch-icon icon" sizes="192x192" href="assets/img/favicons/favicon-192.png">

  </head>
  <body>

   <header class="site-header" role="banner">
		<div class="navbar-wrapper">

			<!-- Nav -->
			<nav>
				<div class="header-logo">
					<a href="<?php echo base_url(TRUE); ?>">Solidarity</a>
        </div>
        <div class="login-btn">
          <?php if (isset($_SESSION['userId'])) { ?>
            <a class="login-trigger no-txt-decoration" href="user.php?action=logout" data-target="#logout">Logout</a>
            <a class="login-trigger no-txt-decoration" href="user.php" data-target="#profile">Profil</a>
          <?php } else { ?>
            <a class="login-trigger no-txt-decoration" href="user.php?action=signup" data-target="#signup">Signup</a>
            <a class="login-trigger no-txt-decoration" href="user.php?action=login" data-target="#login">Login</a>
          <?php } ?>
        </div>
				<div class="button">
					<a class="btn-open" href="#"></a>
				</div>
			</nav>

			<!-- Overley -->
			<div class="overlay">
				<div class="wrapper">
					<ul class="nav-wrapper">
						<li><a href="<?php echo base_url(TRUE); ?>">Home</a></li>
						<li><a href="#">FAQ</a></li>
					</ul>
				</div>
			</div>

		</div>
  </header>
