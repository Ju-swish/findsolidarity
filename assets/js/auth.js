/**
* This script is mostly responsible for Signup and Login page
* The code is used just for Ajax - The old way javaScript Ajax
*/

// JavaScript getElementById function
function _(x){
	return document.getElementById(x);
}

// For handling the Terms - If someone viewd it or not
function openTerms() {
  _("terms").style.display = "block";
  emptyElement("status");
}

/**
* Restricts certain characters in user_email and user_username fields
*
* @param str are basically IDs of fields
* @return str replaced characters
* @see _(x) function in main.js for _(param)
*/
function restrict(param) {

  var textField = _(param);
  var regex = new RegExp;

  if (param == "user_email") {
    regex = /[' "]/gi;
  } else if (param == "user_username") {
    regex = /[^a-z0-9]/gi;
  } else if (param == "brand_email") {
		regex = /[' "]/gi;
	} else if (param == "brand_name") {
    regex = /[^a-z0-9]/gi;
	}

  // replace the restriced characters with nothing (none)
  textField.value = textField.value.replace(regex, "");

}// restrict()

/**
* Emptys all the fields where the function is called
*
* @see _(x) function in main.js for _(param)
*/
function emptyElement(param) {
  _(param).innerHTML = "";
}

/**
* Checks if the Username already exists
*/
function checkUsername() {

  var username = _("user_username").value;

  if (username != "") {

    // Shows the progress of checking
    _("usernameStatus").innerHTML = 'checking ...';
    var ajax = ajaxObj("POST", "includes/usernameCheck.php");

    ajax.onreadystatechange = function() {

      if (ajaxReturn(ajax) == true) {
        _("usernameStatus").innerHTML = ajax.responseText;
      }

    }// onreadystatechange()

    ajax.send("userNameCheck="+username);
  }

}// checkUsername()

/**
* Checks if the Username already exists
*/

/**
* The function responsible for signup form to check if values are correct
* or form is filled and eventually send the fields data to php script
*/
function signup() {

  var a_userName = _("user_name").value;
  var a_userEmail = _("user_email").value;
  var a_userPwd = _("user_pwd").value;
  var a_c_userPwd = _("confirm_user_pwd").value;
  var a_userDesc = _("user_description").value;
  var a_userCity = _("user_city").value;
  var a_userPlz = _("user_plz").value;
  var status = _("status");

  if ( a_userName == ""
    || a_userEmail == ""
    || a_userPwd == ""
    || a_c_userPwd == ""
    || a_userDesc == ""
	 	|| a_userCity == ""
		|| a_userPlz == "" ) {

      status.innerHTML = "Bitte alle Felder ausfüllen!";

      // Passwords do not match
    } else if (a_userPwd != a_c_userPwd) {

      status.innerHTML = "Deine Passwörter stimmen nicht überein";

      // Terms are not viewed
    } else if (_("terms").style.display == "none") {

      status.innerHTML = "Bitte lese die AGB um fortzufahren";

    } else {

      _("signupBtn").style.display = "none";
      status.innerHTML = 'warte kurz ...';

      var ajax = ajaxObj("POST", "includes/signupCheck.php");

      ajax.onreadystatechange = function() {

        if (ajaxReturn(ajax) == true) {

          if (ajax.responseText != 1) {
            status.innerHTML = ajax.responseText;
            _("signupBtn").style.display = "block";
          } else if (ajax.responseText == 1) {
            window.scrollTo(0,0);
            _("signupForm").innerHTML = "Vielen Dank " + a_userName + ", Hilf den Menschen die wirklich Hilfe brauchen!";
          } else {
						status.innerHTML = "Irgendwas ist schief gelauft, weiß net was!";
					}

        }// End-(ajaxReturn())-If

      }// onreadystatechange()

      ajax.send("user_name="+a_userName+
								"&user_email="+a_userEmail+
								"&user_pwd="+a_userPwd+
								"&user_description="+a_userDesc+
								"&user_city="+a_userCity+
								"&user_plz="+a_userPlz);

  }// End-Top-If

}// signup()

/*========================= End Signup Page Script =========================*/
