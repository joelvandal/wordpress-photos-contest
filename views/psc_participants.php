<div class="wrap">

	<h2><?php _e_psc('Imagine - Participants'); ?></h2>

<?php

$my_table = new PSC_Participants_Table();
$my_table->filter = array('on' => __psc("Approved"),
			  'off' => __psc("Unapproved"));
$my_table->prepare_items(); 
$my_table->display();
?>
</div>


<script>
jQuery("a.delete").live('click', function(event) {
	event.stopPropagation();

	if(confirm("<?php esc_html_e_psc("Are you sure you want to delete this participant ?"); ?>")) {
		this.click;
	} else {
		return false;
	}
	event.prevendDefault();
});

jQuery("a.view").live('click', function(event) {

	var url = '<?php echo PSC_PATH . 'ajax.php?preview=true&action=details&id='; ?>' + jQuery(this).data('id');

	jQuery.ajax({
		url : url,
		type: "GET",
		success: function(response) {
			jQuery('<div class="tb-modal tb-modal-wide tb-fade"></div>').html(response).modal(); //.evalScripts();
		}
	});

});


</script>
