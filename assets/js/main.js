//Code for menu (bars) button
$(document).ready(function(){
    $(".button a").click(function(){
        $(".overlay").fadeToggle(200);
       $(this).toggleClass('btn-open').toggleClass('btn-close');
    });
});

$('.overlay').on('click', function(){
    $(".overlay").fadeToggle(200);
    $(".button a").toggleClass('btn-open').toggleClass('btn-close');
    open = false;
});
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
// Code for search button
$(document).ready(function() {
  $(".s-button a").click(function() {

    $(".search-overlay").fadeToggle(200);
    $(this).toggleClass('s-btn-open').toggleClass('s-btn-close');
  });

  // Code for footers Copyright info
  $(window).on("resize", function() {
    if ($(window).width() <= 736) {

      $('.copy-right').removeClass('pull-right');
      $('.copy-right').addClass('pull-left');

    }
  });
});// ready funciton
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
// Script for messages on profile page
var collapseBtn = document.getElementsByClassName("collapse-trigger");
var i;

for(i = 0; i < collapseBtn.length; i++) {

  collapseBtn[i].addEventListener("click", function() {

    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    }

  });

}// End for

//------------------------------------------------------------------------------
