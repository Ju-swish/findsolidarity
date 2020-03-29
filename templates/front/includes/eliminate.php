<?php include("templates/front/header.php"); ?>

<section>
  <div class="container">
    <div class="row">
      <div class="col-md-3"></div>
      <div class="col-md-6">
        <div class="eliminate-user centered">
          <h1>Bist du sicher, dass du dein Konto löschen willst?</h1>
          <p>Beim Klicken auf Ja wird dein gelöscht und wirst zum Login-Page umgeleitet.</p>
          <form action="user.php?action=eliminate" method="post">
            <p class="input-field">
              <button id="signupBtn" class="submit-btn input-submit-btn">
                <input type="submit" name="deleteUser" value="Ja"/>
              </button>
              <button id="signupBtn" class="submit-btn input-submit-btn">
                <input type="submit" formnovalidate name="cancel" value="Nein"/>
              </button>
            </p>
          </form>
        </div>
      </div>
      <div class="col-md-3"></div>
    </div>
  </div>
</section>

<?php include("templates/front/footer.php"); ?>
