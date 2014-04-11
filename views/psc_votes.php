<div class="wrap">

	<h2><?php _e_psc('Imagine - Votes'); ?></h2>

<?php
$my_table = new PSC_Votes_Table();
$my_table->prepare_items(); 
$my_table->display();
?>
</div>

<script>
jQuery("a.delete").live('click', function(event) {
	event.stopPropagation();

	if(confirm("<?php esc_html_e_psc("Are you sure you want to delete this vote ?"); ?>")) {
		this.click;
	} else {
		return false;
	}
	event.prevendDefault();
});
</script>
