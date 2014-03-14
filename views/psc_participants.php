<div class="wrap">

	<h2><?php _e('Photos Contest - Participants', PSC_PLUGIN); ?></h2>

<?php

global $wpdb;
$item = intval($_GET['item']);

switch($_GET['action']) {
 case 'approve':
    $wpdb->query("UPDATE " . PSC_TABLE_PARTICIPANTS . " SET approved=1 WHERE id=" . $item);
    break;
    
 case 'delete':
    $wpdb->query("DELETE FROM " . PSC_TABLE_PARTICIPANTS . " WHERE id=" . $item);
    break;
    
 case 'unapprove':
    $wpdb->query("UPDATE " . PSC_TABLE_PARTICIPANTS . " SET approved=0 WHERE id=" . $item);
    break;
}


$my_table = new PSC_Participants_Table();
$my_table->prepare_items(); 
$my_table->display();
?>
</div>


<script>
jQuery("a.delete").live('click', function(event) {
	event.stopPropagation();
	if(confirm("<?php _e("Are you sure you want to delete this participant ?"); ?>")) {
		this.click;
	}
	event.prevendDefault();
});
</script>
