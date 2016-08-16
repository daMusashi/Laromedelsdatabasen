<?php require_once("page_functions_navs.php"); ?>

<div class="modal fade" id="change-termin-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="change-termin-modal-titel">Ändra kursens start- och sluttermin</h4>
      </div>
      <div class="modal-body" id="change-termin-modal-body">
        <p>Kursens start och slut bestämmer även längden på de bokningar som görs för kursen</p>
        <div><strong>Start</strong>-termin: <?php print getChangeTerminInputHTML("change-termin-start");?></div>
        <p></p>
        <div><strong>Slut</strong>-termin: <?php print getChangeTerminInputHTML("change-termin-slut");?></div>
        <p></p>
        <p><strong>OBSERVERA</strong> att du behöver ladda om kurslistan för att se ändringen</p>
        <input type="hidden" id="change-termin-terminid" value="-1">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Avbryt</button>
        <button type="button" class="btn btn-success" id="change-termin-modal-confirm">Spara</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
  function setChangeTerminModal(kursId, initStartTerminId, initSlutTerminId){
    $("#change-termin-terminid").val(kursId);
    $("#change-termin-start").val(initStartTerminId);
    $("#change-termin-slut").val(initSlutTerminId);
  }
  //function getAjaxCAll(){
  $("#change-termin-modal-confirm").click(function(){
    kursid = $("#change-termin-terminid").val();
    start = $("#change-termin-start").val();
    slut = $("#change-termin-slut").val();
    query = '<?php print Config::PARAM_AJAX; ?>=update-termin-time&<?php print Config::PARAM_ID; ?>='+kursId+'&start='+start+'&slut='+slut;
    console.log(query);
    //return 'ajax.php?'+query;
  });
  //}
</script>