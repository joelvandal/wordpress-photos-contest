<div class="wrap">

	<h2><?php _e('Photos Contest - Participants', PSC_PLUGIN); ?></h2>

<?php
$my_table = new PSC_Participants_Table();
$my_table->prepare_items(); 
$my_table->display();
?>
</div>

