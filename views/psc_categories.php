<div class="wrap">

	<h2><?php _e('Photos Contest - Categories', PSC_PLUGIN); ?></h2>

<?php
global $psc_category_types;
$my_table = new PSC_Categories_Table();
$my_table->filter = $psc_category_types;
$my_table->prepare_items(); 
$my_table->display();
?>
</div>

<br />
<a href="?page=psc_categories&action=edit" class="button button-primary"><?php _e('Add a new item', PSC_PLUGIN); ?></a>
