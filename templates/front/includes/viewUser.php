<?php include("templates/front/header.php"); ?>

<section id="user-profile">
  <div class="container">
    <div class="row">

      <div class="col-md-3"></div>
      <div class="col-md-6">

        <div class="profile-content__container">
          <div class="p-c__wrapper">
            <div class="p-c__media centered">
              <?php if (empty($results['user']->user_img_ext)) { ?>
                <img class="profile-img" src="assets/img/profile-img-placeholder.png">
              <?php } else { ?>
                <img class="profile-img" src="<?php echo $results['userImage']; ?>">
              <?php } ?>
            </div>

            <div class="p-c__info">
              <h3 class="p-c__name centered"><?php echo $results['user']->user_name; ?></h3>
              <h4>Wohnort: <?php echo $results['user']->user_plz . " " . $results['user']->user_city; ?></h4>
              <h4>Wie kann <?php echo $results['user']->user_name; ?> helfen:</h4>
              <p>
                <?php echo $results['user']->user_description; ?>
              </p>
            </div>

            <div class="p-c__contact-form">
              <?php if ($results['alert'] == "emptyFields") { ?>
                <div class="alert alert-danger centered">
                  <p class="alert-message">Bitte alle Felder ausfüllen!</p>
                </div>
              <?php } elseif ($results['alert'] == "msgSentSuccessfully") { ?>
                <div class="alert alert-success centered">
                  <p class="alert-message">Die Nachricht wurde gesendet</p>
                </div>
              <?php } ?>
              <h2>Schreibe eine Nachricht an <?php echo $results['user']->user_name; ?></h2>
              <form method="post">
                <input type="hidden" name="msg_recipient_id" value="<?php echo $results['user']->user_id; ?>"/>
                <div class="row">
                  <div class="col-md-6">
                    <p class="input-field">
                      <input id="msg_sender_name" type="text" name="msg_sender_name" placeholder="Name" onfocus="emptyElement('status')" maxlength="100"/>
                    </p>
                  </div>
                  <div class="col-md-6">
                    <p class="input-field">
                      <input id="msg_sender_age" type="text" name="msg_sender_age" placeholder="Alter" onfocus="emptyElement('status')" maxlength="100"/>
                    </p>
                  </div>
                  <div class="col-md-12">
                    <p class="input-field">
                      <input id="msg_sender_email" type="email" name="msg_sender_email" placeholder="Email" onfocus="emptyElement('status')" onkeyup="restrict('user_email')" maxlength="100"/>
                    </p>
                  </div>
                  <div class="col-md-12">
                    <p class="input-field">
                      <input id="msg_sender_tel" type="text" name="msg_sender_tel" placeholder="Tel." onfocus="emptyElement('status')" maxlength="100"/>
                    </p>
                  </div>
                  <div class="col-md-12">
                    <p class="input-field">
                      <textarea id="msg_sender_msg" type="text" name="msg_sender_msg" placeholder="Deine Nachricht..." onfocus="emptyElement('status')" rows="4" cols="10"></textarea>
                    </p>
                  </div>
                  <div class="col-md-6">
                    <p class="input-field">
                      <button id="signupBtn" class="input-submit-btn">
                        <input type="submit" name="sendMessage" value="Senden"/>
                      </button>
                    </p>
                    <p class="input-field">
                      <span id="status"></span>
                    </p>
                  </div>
                </div><!--/row-->
              </form>
            </div>
          </div>
        </div>

      </div>
      <div class="col-md-3"></div>

    </div><!-- /row -->
  </div>
</section>

<?php include("templates/front/footer.php"); ?>
