
<div class="modal fade" id="help-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="help-modal-titel"><?php print $_GET["help-titel"]?></h4>
      </div>
      <div class="modal-body" id="help-modal-body">
        <?php print $_GET["help-content"]?>
      </div>
      <div class="modal-footer bg-info">
        <button type="button" class="btn btn-success" data-dismiss="modal">Stäng</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
$("#main-help").on("click", function(){
  $("#help-modal").modal();
});
</script>