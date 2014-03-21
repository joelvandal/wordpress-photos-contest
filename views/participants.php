<style>
        .post-meta { display:none; }
	.item-label {
font-size: 18px;
width: 207px;
text-align: center;
position: absolute;
top: 145px;
	}

</style>

<div id="gallery">
	<div id="portfolio-items" class="clearfix">

<?php

global $wpdb;

$orderby = "p.subscribe_date ASC";

$sql = "SELECT p.*,count(distinct(v.id)) AS votes FROM " . PSC_TABLE_PARTICIPANTS . " AS p LEFT JOIN " . PSC_TABLE_VOTES . " AS v ON p.id=v.participant_id WHERE p.approved=1 GROUP BY p.id ORDER BY " . $orderby;
$rows = $wpdb->get_results($sql, ARRAY_A);

$i = 1;

foreach($rows as $item) {

    $thumb = PSC_IMAGE . md5($item['email']) . '-thumb.png';
    
    if ($item['votes'] > 1) {
	$title = sprintf(__psc("%s by %s %s (%d votes)"), $item['project_name'], $item['first_name'], $item['last_name'], $item['votes']);
    } else {
	$title = sprintf(__psc("%s by %s %s (%d vote)"), $item['project_name'], $item['first_name'], $item['last_name'], $item['votes']);
    }
    
    echo '<div class="item' . (($i%4 == 0) ? ' last' : '') . '">';
    echo '<div class="item-image">';
    echo '<a class="more-info" data-id="' . $item['id'] . '" href="#" title="' . $title . '">';
    echo '<img width=207 height=136 class="portfolio" src="' . $thumb . '">';
    echo '<span class="overlay"></span>';
    echo '</a>';
    echo '<a class="more-info" data-id="' . $item['id'] . '" href="#" rel="participants"></a>';
    echo '</div> <!-- end .item-image -->';
    echo '<div class="item-label">' . $item['project_name'] . '</div>';
    echo '</div> <!-- end .item -->';

    $i++;
}

echo '<div class="clear"></div>';

?>

	</div> <!-- end #portfolio-items -->
</div> <!-- end #gallery -->


<script>

jQuery(document).ready(function() {

	jQuery("a.more-info").live('click', function(event) {

	var url = '<?php echo PSC_PATH . 'ajax.php?action=details&id='; ?>' + jQuery(this).data('id');

		jQuery.ajax({
			url : url,
			type: "GET",
			success: function(response) {
				jQuery('<div class="tb-modal tb-modal-wide tb-fade"></div>').html(response).removeClass('hide').modal(); //.evalScripts();
			}
		});

	});
});

jQuery('body').on('hidden.bs.modal', '.tb-modal', function() {
	jQuery(this).remove();
});

</script>
