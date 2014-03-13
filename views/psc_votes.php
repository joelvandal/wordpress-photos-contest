<div class="wrap">

	<h2><?php _e('Photos Contest - Votes', PSC_PLUGIN); ?></h2>

<?php
$my_table = new PSC_Votes_Table();
$my_table->prepare_items(); 
$my_table->display();
?>
</div>

