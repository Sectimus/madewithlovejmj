//setup form submit handler
$(document).ready(function() {
  $("#contactform").on("submit", function() {
    e.preventDefault();
    if ($("#submit").prop("disabled")) {
      alert("Google captcha error");
    }
    $.ajax({
      url: window.location.href,
      method: "POST",
      dataType: "json",
      data: $(this).serialize(),
      success: function(data) {
        console.log(data);
      },
      error: function(a, b, c) {
        console.log(a);
        console.log(b);
        console.log(c);
        console.log(a.responseJSON);
      },
      complete: function(jqXHR, status) {
        $(".g-recaptcha").show();
        grecaptcha.reset();
        $("#loginbtn").hide();
        $("#loginbtn").attr("disabled", false);
      }
    });
  });
  $("#submit").prop("disabled", true);
});

function captchaCompleteCallback(token) {
  var submit = $("#submit").prop("disabled", false);
  captchaDone = true;
}
function captchaExpiredCallback() {
  var submit = $("#submit").prop("disabled", true);
  grecaptcha.reset();
  captchaDone = false;
}
