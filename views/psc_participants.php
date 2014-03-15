<div class="wrap">

	<h2><?php _e('Photos Contest - Participants', PSC_PLUGIN); ?></h2>

<?php

$my_table = new PSC_Participants_Table();
$my_table->prepare_items(); 
$my_table->display();
?>
</div>


<script>
jQuery("a.delete").live('click', function(event) {
	event.stopPropagation();

	if(confirm("<?php esc_html_e("Are you sure you want to delete this participant ?"); ?>")) {
		this.click;
	} else {
		return false;
	}
	event.prevendDefault();
});
</script>
