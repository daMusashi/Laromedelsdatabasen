<?php if(!isLoggedIn()){ ?>}
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="Logga in" aria-hidden="true">
  <div class="modal-dialog">
    
    <div class="modal-content">
      
      <div class="modal-header">
        <button type="button" id="login-close-button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Inloggning</h4>
      </div><!-- .modal-header -->
      
      <div class="modal-body">
        
        <div class="input-group">
          <input type="text" name="user" id="login-user" class="form-control" placeholder="Inloggninsnamn" aria-describedby="sizing-addon2">
          <input type="password" name="password" id="login-pass" class="form-control" placeholder="Lösenord" aria-describedby="sizing-addon2">
        </div><!-- .modal-group -->

        <div id="login-message">
        </div>
      
      </div><!-- .modal-body -->

      <div class="modal-footer">
        <button type="button" id="login-cancel-button" class="btn btn-default" data-dismiss="modal">Stäng</button>
        <button type="button" id="login-login-button" class="btn btn-primary">Logga in</button>
      </div><!-- .modal-footer-->

    </div><!-- .modal-content -->

  </div><!-- .modal-dialog -->
</div><!-- #loginModel -->

<script>
$(document).on("ready", function() {
  $("#login-login-button" ).click(function() {
    login();
  });
  $('#loginModal').on('hidden.bs.modal', function (e) {
    $("#login-message").html("");
    $("#login-user").val("");
    $("#login-pass").val("");
  })
});

function login(){
  var user = $("#login-user").val();
  var pass = $("#login-pass").val();
  mnnDebug("login", "loggar in med u:" + user + " p: " + pass);
  
  $.post("ajax.php?<?php print CONFIG::PARAM_AJAX; ?>=login", {u: user, p: pass})
    .done(function(data) {
        //$("#ajax-debug").text(data);
      mnnDebug("login", "resultat: "+ cleanString(data));
      
      if(cleanString(data) == "TRUE"){
        mnnDebug("login", "inloggning korrekt - gör reload");
        /*$.post("ajax.php?<?php print CONFIG::PARAM_AJAX; ?>=getLogoutHTML")
          .done(function(data) {
            $("#nav-login").html(data); 
          }); */ // INAKTIVERAT - EN RELOAD GÖRS ISTÄLLET
        $('#loginModal').modal('hide');
        location.reload(true);

      } else {
        $("#login-message").html("<div class=\"alert alert-danger\" role=\"alert\">Inloggningen misslyckades</div><div class=\"alert alert-info\" role=\"alert\">Hör av dig till biblioteket om du inte kan/glömt inloggningen</div>");
      }
    })
    .always(function(data) {
        //$("#ajax-debug").text(data);
      mnnDebug("login", "always: ["+ cleanString(data) + "]");
      
      })    
    .fail(function(data) {
        mnnDebug("login", "AJAX Fail: "+ cleanString(data));
      });
}
</script>

<?php } else { ?>
  <script>
  function logout(){
    $.post("ajax.php?<?php print CONFIG::PARAM_AJAX; ?>=logout")
    .done(function(data) {
        //$("#ajax-debug").text(data);
      mnnDebug("logout", "resultat: "+ cleanString(data));
      location.reload(true);
    });
  }
  </script>
<?php }  ?>
