<?php session_start();
  require("config.php");

  if (isset($_POST['search'])) {

    $searchTerm = $_POST['search_term'];
    header("Location: search.php?searchquery=" . $searchTerm);

  } else {

    $results = array();
    $results['pageTitle'] = $_GET['searchquery'] . " | Find Solidarity";

    if (!isset($_GET['searchquery'])) {
      // if someone visits search.php page redirect them to index page
      header("Location: index.php");
    }

    $searchQuery = $_GET['searchquery'];
    $data = App::getListByZipcode("", "", $searchQuery);
    $searchResults = $data['results'];
    $app = new App;

  }


include("templates/front/header.php");
?>

<section id="search">
  <div class="container">
    <div class="row">
      <div class="col-md-3"></div>
      <div class="col-md-6">
        <div class="search-form">
          <form action="" method="post">
            <input type="text" name="search_term" id="search-field" class="search_field" placeholder="Z.B. 79112"/>
            <input class="btn search-btn button-btn" type="submit" name="search" value="Suchen" />
          </form>
        </div>
      </div>
      <div class="col-md-3"></div>
    </div>
  </div>
</section>

<section id="results">
  <div class="container">
    <div class="search-results__wrapper">
      <div class="row">
        <?php
          if (is_null($searchResults)) {
            echo "Keiner gefunden";
          } else {
            foreach($searchResults as $searchRes) {
              $imagePath = $app->profileImagePath(
                            IMG_TYPE_THUMB,
                            $searchRes->user_unique_id,
                            $searchRes->user_id,
                            $searchRes->user_img_ext
                          );
        ?>

            <div class="col-md-4">
              <div class="sr-item__wrapper">

                <div class="sr-item__media">
                  <?php if (empty($searchRes->user_img_ext)) { ?>
                    <img class="sr-item__img" src="assets/img/profile-img-placeholder.png">
                  <?php } else { ?>
                    <img class="sr-item__img" src="<?php echo $imagePath; ?>">
                  <?php } ?>
                </div>
                <div class="sr-item__content">
                  <p class="title"><?php echo $searchRes->user_name; ?></p>
                  <p class="parag"><?php echo $searchRes->user_plz . ", " . $searchRes->user_city; ?></p>
                  <a class="btn contact-btn no-txt-decoration" href="index.php?action=viewUser&userId=<?php echo $searchRes->user_id; ?>">Kontaktieren</a>
                </div>

              </div>
            </div>

        <?php } } ?>
      </div><!--/row-->
    </div>
  </div>
</section>

<?php include("templates/front/footer.php"); ?>
