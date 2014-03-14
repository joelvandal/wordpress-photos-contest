<style>
.fancybox-nav span {
    visibility: visible;
}

.fancybox-nav {
    width: 80px;
}

.fancybox-prev {
    left: -80px;
}

.fancybox-next {
    right: -80px;
}
</style>

<div id="gallery">
	<div id="portfolio-items" class="clearfix">

<?php

global $wpdb;

$sql = "SELECT p.*,count(distinct(v.id)) AS votes FROM " . PSC_TABLE_PARTICIPANTS . " AS p LEFT JOIN " . PSC_TABLE_VOTES . " AS v ON p.id=v.participant_id WHERE p.approved=1 GROUP BY p.id";
$rows = $wpdb->get_results($sql, ARRAY_A);

$i = 1;

foreach($rows as $item) {

    $thumb = PSC_PATH . 'uploads/' . md5($item['email']) . '-thumb.png';
    
    $title = sprintf(__("%s by %s %s (%d votes)"), $item['project_name'], $item['first_name'], $item['last_name'], $item['votes']);
    $link =  PSC_PATH . 'ajax.php?action=details&id=' . $item['id'];
    
    echo '<div class="item';
    if ($i%4 == 0) echo(' last');
    echo '">';
    
    echo'<div class="item-image">';
    
    echo '<img src="' . $thumb . '">';
    echo '<span class="overlay"></span>';
    echo '<a class="more-icon fancybox.ajax" title="' . $title . '" href="' . $link . '" rel="participants">' . esc_html('View details', PSC_PLUGIN) . '</a>';
    echo '</div> <!-- end .item-image -->';
    echo '</div> <!-- end .item -->';
    
    $i++;
}

echo '<div class="clear"></div>';

?>

	</div> <!-- end #portfolio-items -->
</div> <!-- end #gallery -->


<script>
jQuery(document).ready(function() {
    jQuery(".more-icon").fancybox({
	margin	    : [20, 60, 20, 60],
        maxWidth    : 1600,
        maxHeight   : 1200,
        fitToView   : false,
        width       : '70%',
        height      : '70%',
        autoSize    : false,
        closeClick  : false,
        openEffect  : 'none',
        closeEffect : 'none'
    });
});

</script>
