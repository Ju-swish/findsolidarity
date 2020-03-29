<?php include "templates/front/header.php" ?>

  <!-- HERO
	+++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
	<section id="hero">
		<div class="container">
			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6 centered">
          <h1 class="hero-title">Gib deine Postleitzahl ein, um die HELFER in deiner Umgebung zu finden</h1>
          <form action="" method="post">
            <input type="text" name="search_term" id="search-field" class="search_field" placeholder="z.B. 79112"/>
            <input class="btn search-btn button-btn" type="submit" name="search" value="Suchen" />
          </form>
				</div>
				<div class="col-md-3"></div>
			</div>
		</div>
	</section>

	<section id="hotline">
		<div class="container centered">
			<p class="parag">
				Du brauchst Hilfe bei der Kontaktherstellung? Täglich zwischen 13
				und 16 Uhr erreichst du uns unter der Telefonnummer:
			</p>
			<p class="title">01573 7991853</p>
		</div>
	</section>

  <!-- CONTENT
	+++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
<?php include "templates/front/includes/content.php" ?>

<?php include "templates/front/footer.php" ?>
