(function($) {
$(document).ready(function(){

  // SHOW/HIDE FOOTER ACTIONS
  $('#showHide').click(function(){
    if ($("#footerActions").is(":hidden")) {
      $(this).css('background-position','0 0');
      $("#footerActions").slideDown("slow");

    } else {
      $(this).css('background-position','0 -16px')
      $("#footerActions").hide();
      $("#footerActions").slideUp("slow");
    }
    return false;
  });

  // TOP SEARCH
  $('#topSearch input:text').autofill({
    value: "type your search..."
  });

  $('#topSearch input:text').focus(function() {
    $(this).animate({
      width: "215"
    }, 300 );
    $(this).val('')
  });

  $('#topSearch input:text').blur(function() {
    $(this).animate({
      width: "100"
    }, 300 );
  });

  // QUICK CONTACT

  $('#quickContact #edit-name').val('your name');
  $('#quickContact #edit-name--2').val('your name');
  $('#quickContact #edit-mail').val('your email');
  $('#quickContact #edit-subject').val('message subject');
  $('#quickContact #edit-message').val('your message');

  $('#quickContact #edit-name').focus(function() {
    $(this).val('');
  });

  $('#quickContact #edit-name--2').focus(function() {
    $(this).val('');
  });

  $('#quickContact #edit-mail').focus(function() {
    $(this).val('');
  });

  $('#quickContact #edit-subject').focus(function() {
    $(this).val('');
  });

  $('#quickContact #edit-message').focus(function() {
    $(this).val('');
  });

  //SHARE LINKS
  $('#shareLinks a.share').click(function() {
    if ($("#shareLinks #icons").is(":hidden")) {
      $('#shareLinks').animate({
        width: "625"
      }, 500 );
      $('#shareLinks #icons').fadeIn();
      $(this).text('[-] Share & Bookmark');
      return false;
    }else {
      $('#shareLinks').animate({
        width: "130"
      }, 500 );
      $('#shareLinks #icons').fadeOut();
      $(this).text('[+] Share & Bookmark');
      return false;
    }
  });

});
<!-- end document ready -->
})(jQuery);