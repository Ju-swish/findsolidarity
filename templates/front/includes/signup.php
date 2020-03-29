<?php

  if (isset($_SESSION['userId'])) {
    header("Location: user.php");
    exit();
  }

include("templates/front/header.php");
?>

<section id="signup-form">
  <div class="container">
    <div class="row">
      <div class="col-md-3"></div>
      <!-- Signup Form -->
      <div class="col-md-6">
        <div class="signup-page-content padding-left-twenty padding-right-twenty">
          <form id="signupForm" name="signupForm" onsubmit="return false;">
            <h2 class="padding-left-ten form-title">Registrieren</h2>
            <p class="padding-left-ten">Geht ganz easy, mach's einfach!</p>

            <div class="row">
              <div class="col-md-12">
                <p class="input-field">
                  <input id="user_name" type="text" name="user_name" placeholder="Name" onfocus="emptyElement('status')" maxlength="25"/>
                </p>
              </div>
              <div class="col-md-12">
                <p class="input-field">
                  <input id="user_email" type="email" name="user_email" placeholder="email" onfocus="emptyElement('status')" onkeyup="restrict('user_email')" maxlength="100"/>
                </p>
              </div>
              <div class="col-md-6">
                <p class="input-field">
                  <input id="user_pwd" type="password" name="user_pwd" placeholder="Password"onfocus="emptyElement('status')" maxlength="100"/>
                </p>
              </div>
              <div class="col-md-6">
                <p class="input-field">
                  <input id="confirm_user_pwd" type="password" placeholder="Confirm Password" onfocus="emptyElement('status')" maxlength="100"/>
                </p>
              </div>
              <div class="col-md-6">
                <p class="input-field">
                  <input id="user_plz" type="text" name="user_plz" placeholder="Postleitzahl" onfocus="emptyElement('status')" maxlength="100"/>
                </p>
              </div>
              <div class="col-md-6">
                <p class="input-field">
                  <input id="user_city" type="text" name="user_city" placeholder="Stadt" onfocus="emptyElement('status')" maxlength="100"/>
                </p>
              </div>
              <div class="col-md-12">
                <p class="input-field">
                  <textarea id="user_description" type="text" name="user_description" placeholder="Ein bisschen über dich..." onfocus="emptyElement('status')" rows="4" cols="10"></textarea>
                </p>
              </div>
              <div class="col-md-12">
                <p class="input-field">
                  <a href="#" onclick="return false" onmousedown="openTerms()">
                    Schaue unsere Regeln an
                  </a>
                </p>
              </div>
              <div id="terms" style="display:none;">
                <h3>Vielen Dank, dass du helfen willst! Bitte beachte bei Nutzung dieser Plattform die folgenden Regeln!</h3>
                <p>1. Helft nur in euren Möglichkeiten und wenn ihr euch dabei wohl fühlt.</p>
                <p>2. Physischen Kontakt vermeiden. Nach der Übergabe gründlich Hände waschen. Bargeld in einem Brief oder
                ähnlichen Behälter übergeben, sodass kein physischer Kontakt zustande kommt.</p>
                <p>3. Versucht auf jede Nachricht zu antworten, auch wenn ihr nicht helfen könnt.
                  So haben die Hilfesuchenden eine Chance, einen neuen Helfer zu kontaktieren</p>
              </div>
              <div class="col-md-6">
                <p class="input-field">
                  <button id="signupBtn" class="input-submit-btn">
                    <input type="submit" name="sendMessage" onclick="signup()" value="Registrieren"/>
                  </button>
                </p>
                <p class="input-field">
                  <span id="status"></span>
                </p>
              </div>
            </div><!-- row -->

          </form>
        </div>
      </div>
      <div class="col-md-3"></div>
    </div><!-- row -->
  </div>
</section>

<?php include("templates/front/footer.php"); ?>
