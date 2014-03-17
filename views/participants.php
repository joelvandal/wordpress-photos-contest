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

$orderby = "p.subscribe_date ASC";

$sql = "SELECT p.*,count(distinct(v.id)) AS votes FROM " . PSC_TABLE_PARTICIPANTS . " AS p LEFT JOIN " . PSC_TABLE_VOTES . " AS v ON p.id=v.participant_id WHERE p.approved=1 GROUP BY p.id ORDER BY " . $orderby;
$rows = $wpdb->get_results($sql, ARRAY_A);

$i = 1;

foreach($rows as $item) {

    $thumb = PSC_PATH . 'uploads/' . md5($item['email']) . '-thumb.png';
    
    if ($item['votes'] > 1) {
	$title = sprintf(__("%s by %s %s (%d votes)"), $item['project_name'], $item['first_name'], $item['last_name'], $item['votes']);
    } else {
	$title = sprintf(__("%s by %s %s (%d vote)"), $item['project_name'], $item['first_name'], $item['last_name'], $item['votes']);
    }
    $link =  PSC_PATH . 'ajax.php?action=details&id=' . $item['id'];
    
    echo '<div class="item' . (($i%4 == 0) ? ' last' : '') . '">';
    echo '<div class="item-image">';
    echo '<a class="fancybox.ajax" href="' . $link . '" title="' . $title . '">';
    echo '<img class="portfolio" src="' . $thumb . '">';
    echo '<span class="overlay"></span>';
    echo '</a>';
    echo '<a class="more-info" href="' . $link . '" rel="participants"></a>';
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
    jQuery(".more-info").fancybox({
	margin	    : [20, 60, 20, 60],
        fitToView   : false,
        width       : '90%',
        height      : '90%',
        autoSize    : false,
        closeClick  : false,
        openEffect  : 'none',
        closeEffect : 'none'
    });
});

</script>
