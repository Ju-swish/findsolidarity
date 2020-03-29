<?php

  /*if (isset($_SESSION['session'])) {
    header("Location: user.php");
    exit();
  }*/

include("templates/front/header.php");
?>

<section id="user-profile">
  <div class="container">
    <!-- PROFILE IMAGE MODAL -->
    <div id="open-img-modal" class="modal modal-overlay">
      <div class="modal--wrapper m-wrapper__media">
        <a class="modal-close-btn" href="#" title="Close"><i class="fas fa-times"></i></a>
        <div class="modal--content centered">
          <h1>Upload Header Image</h1>
          <form action="user.php?action=edit" method="post" enctype="multipart/form-data" onsubmit="closeKeepAlive()">
            <p class="input-field">
              <label>Bild Hochladen</label>
              <span>Das Bild muss quadratisch sein z.B. 500x500px</span>
              <input type="file" name="image" id="image" class="input-fields input-file hide-input-file-btn"/>
              <label for="image" class="custom-input"><span><i class="fas fa-file-upload"></i> Wähle eine Datei</span></label>
            </p>
            <p class="input-field">
              <button id="signupBtn" class="submit-btn input-submit-btn">
                <input type="submit" name="uploadImage" value="Upload Image"/>
              </button>
            </p>
          </form>
        </div>
      </div>
    </div>
    <!-- END PROFILE IMAGE MODAL -->
    <!-- PROFILE SETTINGS MODAL -->
    <div id="open-settings-modal" class="modal modal-overlay">
      <div class="modal--wrapper m-wrapper__settings">
        <a class="modal-close-btn" href="#" title="Close"><i class="fas fa-times"></i></a>
        <div class="modal--content centered">
          <h2>Profileinstellungen</h2>
          <form action="user.php?action=edit" method="post">
            <input id="user_id" type="hidden" name="user_id" value="<?php echo $results['user']->user_id; ?>"/>

            <p class="input-field">
              <input id="user_name" type="text" name="user_name" placeholder="Name" onfocus="emptyElement('status')" value="<?php echo $results['user']->user_name; ?>" maxlength="25"/>
            </p>
            <p class="input-field">
              <input id="user_email" type="email" name="user_email" placeholder="email" onfocus="emptyElement('status')" onkeyup="restrict('user_email')" value="<?php echo $results['user']->user_email; ?>" maxlength="100"/>
            </p>
            <p class="input-field">
              <input id="user_plz" type="text" name="user_plz" placeholder="Postleitzahl" onfocus="emptyElement('status')" value="<?php echo $results['user']->user_plz; ?>" maxlength="100"/>
            </p>
            <p class="input-field">
              <input id="user_city" type="text" name="user_city" placeholder="Stadt" onfocus="emptyElement('status')" value="<?php echo $results['user']->user_city; ?>" maxlength="100"/>
            </p>
            <p class="input-field">
              <textarea id="user_description" type="text" name="user_description" placeholder="Ein bisschen über dich..." onfocus="emptyElement('status')" rows="4" cols="1 0"><?php echo $results['user']->user_description; ?></textarea>
            </p>
            <?php if ($results['user']->user_img_ext) { ?>
              <img class="m-settings__header-img" src="<?php echo $results['userImage']; ?>">
              <label></label>
              <input type="checkbox" name="deleteImage" id="deleteImage" value="yes"/> <span>Delete current image</span>
            <?php } ?>
            <p class="input-field">
              <button id="signupBtn" class="submit-btn input-submit-btn">
                <input type="submit" name="saveChanges" value="Änderungen Speichern"/>
              </button>
              <button id="signupBtn" class="submit-btn input-submit-btn">
                <input type="submit" formnovalidate name="cancel" value="Schließen"/>
              </button>
            </p>
            <p class="input-field">
              <a class="m-settings__delete parag" href="user.php?action=eliminate"><i class="fas fa-tired"></i> Ich möchte mein Konto löschen!</a>
            </p>
          </form>
        </div>
      </div>
    </div>
    <!-- END PROFILE SETTINGS MODAL -->
    <div class="row">

      <div class="col-md-3"></div>
      <div class="col-md-6">

        <div class="profile-content__container">
          <div class="p-c__wrapper">
            <div class="p-c__media centered">
              <?php if (empty($results['user']->user_img_ext)) { ?>
                <img class="profile-img" src="assets/img/profile-img-placeholder.png">
                <?php if (isset($_SESSION['userId'])) { ?>
                  <a class="modal-btn" href="#open-img-modal" title="Bild hochladen"><i class="fas fa-camera fa-2x"></i></a>
                <?php } ?>
              <?php } else { ?>
                <img class="profile-img" src="<?php echo $results['userImage']; ?>">
              <?php } ?>
            </div>
            <div class="p-c__setting">
              <a class="modal-btn-setting float-right no-txt-decoration" href="#open-settings-modal" title="Bild hochladen"><i class="fas fa-user-cog"></i> Einstellungen</a>
            </div>

            <div class="p-c__info">
              <p class="p-c__name parag"><?php echo $results['user']->user_name; ?></p>
              <p class="parag"><?php echo $results['user']->user_plz . ", " . $results['user']->user_city; ?></p>
              <p>
                <?php echo $results['user']->user_description; ?>
              </p>
            </div>

            <div class="p-c__contact-form">
              <h2>Deine Nachrichten:</h2>
              <div class="p-messages__container">

                <?php if (empty($results['messages'])) { ?>
                  <div class="no-messages-message">
                    <p class="title">Du hast noch keine Nachrichten bekommen</p>
                  </div>
                <?php } else {
                  foreach($results['messages'] as $message) {
                ?>
                  <div class="p-messages__wrapper">
                    <p class="p-messages__title parag collapse-trigger clear">
                      <i class="fas fa-envelope"></i><span class="margin-left-twenty"></span> <?php echo $message->msg_sender_name; ?>
                    </p>
                    <div class="p-c-messages__content">
                      <p class="parag"><i class="fas fa-envelope-open-text"></i> <?php echo $message->msg_sender_email; ?></p>
                      <p class="parag"><i class="fas fa-phone"></i> <?php echo $message->msg_sender_tel; ?></p>
                      <p><i class="fas fa-comment parag"></i> <?php echo $message->msg_sender_msg; ?></p>
                    </div>
                  </div><!-- /p-messages__wrapper -->
                <?php } } ?>

              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="col-md-3"></div>

    </div><!-- /row -->
  </div>
</section>
<script>
var inputs = document.querySelectorAll( '.input-file' );

Array.prototype.forEach.call( inputs, function( input ) {
  var label = input.nextElementSibling,
      labelVal = label.innerHTML;

  input.addEventListener( 'change', function( e ) {
    var fileName = '';

    if ( this.files && this.files.length > 1 ) {
      fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
    } else {
      fileName = e.target.value.split( '\\' ).pop();
    }

    if ( fileName ) {
      //console.log(fileName);
      label.querySelector( 'span' ).innerHTML = fileName;
    } else {
      label.innerHTML = labelVal;
    }
  });

});
</script>

<?php include("templates/front/footer.php"); ?>
