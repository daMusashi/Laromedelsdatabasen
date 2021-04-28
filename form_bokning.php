<?php

?>
<div id="form-bokning" class="form-box">
<form method="post" action="">
<?php print getSelectBokHTML($selectedBokId); ?>
<?php print getSelectKursHTML($selectedKursId); ?>
<?php print getSelectInUtTillfalleHTML("Välj utlämningstillfälle...", "select-ut-tillfalle", $selectedUtTillfalleId); ?>
<?php print getSelectInUtTillfalleHTML("Välj inlämningstillfälle...", "select-in-tillfalle", $selectedInTillfalleId); ?>
<?php print getSelectLarareHTML("Välj bokningslärare...", "select-bokningslarare", $selectedBokningsLarareId); ?>
<textarea name="comment"></textarea>
<select läsår><option>Läsår</option></select>
<input type="submit" value="boka" />
</form>
</div>