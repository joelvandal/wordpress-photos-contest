<div class="wrap">

	<h2><?php _e('Photos Contest - Participants', PSC_PLUGIN); ?></h2>

<?php

$my_table = new PSC_Participants_Table();
$my_table->filter = array('on' => __("Approved", PSC_PLUGIN),
			  'off' => __("Unapproved", PSC_PLUGIN));
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

jQuery("a.view").live('click', function(event) {

	var url = '<?php echo PSC_PATH . 'ajax.php?action=details&id='; ?>' + jQuery(this).data('id');

	jQuery.ajax({
		url : url,
		type: "GET",
		success: function(response) {
			jQuery('<div class="modal modal-wide fade"></div>').html(response).modal(); //.evalScripts();
		}
	});

});

jQuery('body').on('hidden.bs.modal', '.modal', function() {
	jQuery(this).remove();
});


</script>
