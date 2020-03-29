/**
* Light weight educational "ajax-framework" created by Adam Khoury
* @TODO Change this code to something better - this is just for starting up
* @TODO !! IMPORTANT !! it has to be changed
*/

function ajaxObj(method, url) {

  var xhttp = new XMLHttpRequest();   // x (:= ajax)
  xhttp.open(method, url, true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  return xhttp;

}// ajaxObj()

function ajaxReturn(xhttp) {

  if (xhttp.readyState === 4 && xhttp.status === 200) {
    return true;
  }

}// ajaxReturn()
