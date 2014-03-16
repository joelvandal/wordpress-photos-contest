<style>

.share {
	padding-left: 0;
	list-style: none;
}

.share-item {
	display: inline;
	margin-right: 0.25em;
}

.share-link {
	text-decoration: none !important;
	color: #444;
	padding: .01em .5em .05em 30px;
	background-color: #f5f5f5;
	border: 1px solid #ccc;
	border-radius: 2px;
}

.share-link:hover, .share-link:active, .share-link:focus {
	color: #891434;
}

[class*="ico-"] {
	display: inline-block;
	background-size: 16px 16px;
	background-repeat: no-repeat;
	background-position: 4px center;
}

.ico-facebook {
	background-image: url("http://www.facebook.com/favicon.ico"); 
}

.ico-twitter {
	background-image: url("http://twitter.com/favicons/favicon.ico");
}

.ico-google {
	background-image: url("https://ssl.gstatic.com/s2/oz/images/faviconr2.ico"); 
}
</style>

<table width="100%" border=1>
<tr>
	<td valign="top" width="450px">
		<table>
		<tr><td>Participant Name:</td><td><?php echo sprintf("%s, %s", strtoupper($item['last_name']), $item['first_name']); ?></td></tr>
		<tr><td>Project Name:</td><td><?php echo $item['project_name']; ?></td></tr>
		<tr><td>Description:</td><td><?php echo $item['project_description']; ?></td></tr>
		</table>

		<input type="text" readonly value="<?php echo psc_shorturl($item['id']); ?>">


		<div class="share">
			<b><?php echo __("Share this picture on", PSC_PLUGIN); ?></b>
			<br />
			<li class="share-item">
				<a data-network="facebook" class="share-link ico-facebook" href="#" rel="nofollow">Facebook</a>
			</li>
			<li class="share-item">
				<a data-network="twitter" class="share-link ico-twitter" href="#" rel="nofollow">Twitter</a>
			</li>
			<li class="share-item">
				<a data-network="google" class="share-link ico-google" href="#" rel="nofollow">Google+</a>
			</li>
		</div>

<?php if (psc_is_vote_open()): ?>
<button class="btn"><?php _e('Vote', PSC_PLUGIN); ?></button>
<?php else: ?>
<?php _e('Vote is currently closed', PSC_PLUGIN); ?>
<?php endif; ?>

	</td>
	<td>
		<img width="800" src="<?php echo PSC_PATH . 'uploads/' . md5($item['email']) . '-view.png'; ?>" />
	</td>
</tr>
</table>

<script>
jQuery(".share-link").on('click', function() {
	var network = jQuery(this).data('network');
	var shorturl = '<?php echo psc_shorturl($item['id']); ?>';
	var longurl = '<?php echo psc_longurl($item['id']); ?>';
	var imgurl = '<?php echo PSC_PATH . 'uploads/' . md5($item['email']) . '-view.png'; ?>';

	switch(network) {
		case 'facebook':
			url = 'http://www.facebook.com/sharer.php?u=' + encodeURIComponent(longurl);
			break;

		case 'twitter':
			url = 'http://twitter.com/share?url='+encodeURIComponent(shorturl) + '&text=' + encodeURIComponent('<?php echo psc_get_option('twitter_text'); ?>') + '&hashtags=<?php echo preg_replace('/\s+/', ',', str_replace('#', '', psc_get_option('twitter_hash'))); ?>';
			break;

		case 'google':
			url = 'http://plus.google.com/share' + '?url='+encodeURIComponent(longurl);
			break;
	}

	window.open(url, '<?php echo __("Share", PSC_PLUGIN); ?>','scrollbars=yes,menubar=no,height=420,width=700,resizable=yes,toolbar=no,status=no');
	return false;
});
</script>
