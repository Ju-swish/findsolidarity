<?php

  if (isset($_SESSION['userId'])) {
    header("Location: user.php");
    exit;
  } else {
    header("Locatin: user.php?action=login");
  }

include("templates/front/header.php");
?>

<section id="login-form">
  <div class="container">
    <div class="row">
      <div class="col-md-4"></div>
      <!-- Signup Form -->
      <div class="col-md-4">
        <h1 class="centered login-form-title">Helfer Login</h1>

          <div id="login-form-wrapper">
            <div class="modal-content">
              <div class="login-form-body">

                <?php
                  if ($results['errorMessage'] != "") {
                    echo $results['errorMessage'];
                  }
                ?>

                <form action="user.php?action=login" method="post">
                  <input type="hidden" name="login" value="true"/>

                  <input type="text" name="user_email" class="username form-control" placeholder="Email"/>
                  <input type="password" name="user_pwd" class="password form-control" placeholder="Password"/>
                  <!--<p class="forgot-password centered"><a href="#"><i class="fas fa-sad-tear"></i> Passwort Vergessen ArrggGGhh!</a></p>-->
                  <input class="btn login" type="submit" value="Login" />
                  <p class="register-link centered">Was?! Willst du helfen aber bist nicht registriert? <a href="user.php?action=signup">Registriere Dich!</a></p>
                </form>
              </div>
            </div>
          </div>

        </div>
      <div class="col-md-4"></div>
    </div>
  </div>
</section>

<?php include("templates/front/footer.php"); ?>
