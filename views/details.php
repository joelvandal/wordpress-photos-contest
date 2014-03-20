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

.tb-popover {
    width:200px;
//    height:250px;    
}

.tb-modal.tb-modal-wide .tb-modal-dialog {
	width: 80%;
//	height: 60%;
}

.tb-modal-wide .tb-modal-body {
//height: 60%;
	overflow-y: auto;
}

.tb-img-responsive {
    display: block;
    width: auto;
    height: 80%;
}
</style>

<?php
if ($item['artist_show'] && !empty($item['artist'])) {
    $item['full_name'] = $item['artist'];
} else {
    $item['full_name'] = ucwords(strtolower(sprintf("%s %s", $item['first_name'], $item['last_name'])));
}
?>

<?php if (!wp_is_mobile()): ?>
<div id="modal-window" class="tb-modal-dialog">
	<div class="tb-modal-content">
		<div class="tb-modal-header">
			<button type="button" class="tb-close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<div class="tb-h3"><?php echo sprintf(__psc('%s created by %s'), $item['project_name'], $item['full_name']) ; ?></div>
		</div>
		<div class="tb-modal-body tb-overflow-visible">
<?php endif; ?>
			<div class="tb-row">
				<div class="tb-col-xs-12 tb-col-sm-12 tb-col-lg-5 tb-col-md-5">
	
					<table class="tb-table tb-table-striped tb-table-hover tb-table-responsive tb-text-left">
					<tbody>
						<tr><th>Name:</th><td><?php echo $item['full_name']; ?></td></tr>
						<tr><th>Age:</th><td><?php echo $item['age']; ?></td></tr>
						<tr><th>School Name:</th><td><?php echo psc_get_school($item['school']); ?></td></tr>
<!--
						<tr><th>Class Name:</th><td><?php echo psc_get_class($item['class_name']); ?></td></tr>
-->

						<tr><th style="width: 150px">Project Name:</th><td><?php echo $item['project_name']; ?></td></tr>
						<tr><th>Category:</th><td><?php echo psc_get_project($item['project_category']); ?></td></tr>
						<tr id="div-desc"><th colspan=2>Description:</th></tr>
						<tr id="div-desc2"><td colspan=2><?php echo $item['project_description']; ?></td></tr>
					</tbody>
					</table>


		<?php if (psc_is_vote_open()): ?>
			<?php if ($email = psc_get_vote_email()): ?>
						<div id="alreadyVoted">

			<div class="tb-alert tb-alert-warning">
				<div class="tb-text-center">
				<?php echo sprintf(__psc('You already voted using %s!'), $email); ?>
<br /><br />
				<button class="tb-btn tb-btn-warning" id="resetVote"><?php _e_psc('Use a different Email'); ?></button>
				</div>
			</div>
</div>

<?php else: ?>

			<div class="tb-row">
<div class="tb-col-lg-3"></div>
				<div class="tb-col-lg-6 tb-text-center">
				<a class="readmore" id="closeVote" class="tb-close" data-dismiss="modal" aria-hidden="true"><span><?php _e_psc('Close'); ?></span></a>
				<a class="readmore" id="showVote"><span><?php _e_psc('Vote for this participant'); ?></span></a>
				</div>
 			</div>
			<?php endif; ?>
		<?php else: ?>
			<div class="tb-alert tb-alert-danger">
				<div class="tb-text-center">
					<b><?php _e_psc('Vote is currently closed!'); ?></b>
				</div>
			</div>
		<?php endif; ?>
			<br />

					<div style="display: none" class="voteMessage"></div>
		
<?php if (psc_is_vote_open()): ?>
					<div id="voterDetails" style="display: none;" class="tb-text-left">
					<h4 class="tb-text-left">Vote for this participant:</h4>
						<table class="tb-table tb-table-striped tb-table-hover tb-table-responsive tb-text-left">
						<tbody>
							<tr>
								<th><label for="voter_email"><?php _e_psc('Enter your Email:'); ?></label></th>
<?php if (wp_is_mobile()): ?>
							</tr>
							<tr>
<?php endif; ?>		
								<td><div class="tb-form-input" id="form_email"><input type="text" class="tb-form-control" id="voter_email" placeholder="Enter email"></div></td>
							</tr>
		
							<tr>
								<th style="width: 150px; vertical-align: middle"><label for="voter_name"><?php _e_psc('Enter your Name:'); ?></label></th>
<?php if (wp_is_mobile()): ?>
							</tr>
							<tr>
<?php endif; ?>		
								<td><div class="tb-form-input" id="form_name"><input type="text" class="tb-form-control" id="voter_name" placeholder="Enter name"></div></td>
							</tr>
						</tbody>
						</table>
						<div class="tb-pull-right">
							<button class="tb-btn tb-btn-sm tb-btn-success" id="sendVote"><?php _e_psc('Vote Now'); ?></button>
							<button class="tb-btn tb-btn-sm tb-btn-danger" id="cancelVote"><?php _e_psc('Cancel'); ?></button>
						</div>
<p><br /></p>
						<div style="margin-top: 10px"><br /></div>
					</div>
<?php endif; ?>

				</div>

				<div class="tb-col-xs-12 tb-col-sm-12 tb-col-md-7 tb-col-lg-7">
					<img class="tb-img-responsive tb-img-thumbnail tb-text-center" style="height:auto;" src="<?php echo PSC_PATH . 'uploads/' . md5($item['email']) . '-view.png'; ?>" />
				</div>
			</div>
		</div>

<?php if (!wp_is_mobile()): ?>
		<div class="tb-modal-footer">
<?php endif; ?>
			<div class="share tb-text-center">
				<b><?php echo __psc("Share this picture on"); ?></b>
<?php if (wp_is_mobile()): ?><br /><?php endif; ?>
				<li class="share-item">
					<a data-network="facebook" class="share-link ico-facebook" href="#" rel="nofollow">Facebook</a>
				</li>
				<li class="share-item">
					<a data-network="twitter" class="share-link ico-twitter" href="#" rel="nofollow">Twitter</a>
				</li>
				<li class="share-item">
					<a data-network="google" class="share-link ico-google" href="#" rel="nofollow">Google+</a>
				</li>
<?php if (wp_is_mobile()): ?><br /><br /><?php endif; ?>
				<li class="share-item">
					<input type="text" readonly value="<?php echo psc_shorturl($item['id']); ?>">
				</li>
			</div>
		</div>
<?php if (!wp_is_mobile()): ?>
	</div>
</div>
<?php endif; ?>
<script>

<?php if (psc_is_vote_open()): ?>

<?php if ($email = psc_get_vote_email()): ?>
jQuery('#showVote').hide();
jQuery('#closeVote').hide();
<?php endif; ?>

jQuery('#resetVote').on('click', function() {

	var params = {
		action: 'reset_vote',
	};

	jQuery.post(
		'<?php echo PSC_PATH . 'ajax.php'; ?>',
		params,
		function(data) {
			if (data.status == 'ok') {
				jQuery("#voter_email").val('<?php echo $email; ?>'),
				jQuery('#alreadyVoted').hide();
				jQuery('#showVote').hide();
				jQuery('#closeVote').hide();
				jQuery('#voterDetails').show();
			}
		},
		'json'
	);
});

jQuery('#showVote').on('click', function() {
	jQuery('#div-desc').hide();
	jQuery('#div-desc2').hide();
	jQuery('#showVote').hide();
	jQuery('#closeVote').hide();
	jQuery('#voterDetails').show();
	jQuery(".voteMessage").hide();
});

jQuery('#cancelVote').on('click', function() {
	jQuery('#div-desc').show();
	jQuery('#div-desc2').show();
	jQuery('#showVote').show();
	jQuery('#closeVote').show();
	jQuery('#voterDetails').hide();
	jQuery(".voteMessage").hide();
});

jQuery('#closeVote').on('click', function() {
//	jQuery('#modal-window').modal('hide');
});

jQuery('#sendVote').on('click', function() {

	var params = {
		action: 'vote',
		email: jQuery("#voter_email").val(),
		name: jQuery("#voter_name").val(),
		participant_id: '<?php echo $item['id']; ?>'
	};

	jQuery('#form_email').addClass('tb-has-success');
	jQuery('#form_name').addClass('tb-has-success');

	jQuery.post(
		'<?php echo PSC_PATH . 'ajax.php'; ?>',
		params,
		function(data) {
			if (data.status == 'ok') {
				jQuery(".voteMessage").html('<div class="tb-alert tb-alert-info"><?php esc_html_e_psc(__psc("Thanks! Please check your email to validate your vote!")); ?></div>').show();
				jQuery('#voterDetails').hide();
				jQuery('#div-desc').show();
				jQuery('#div-desc2').show();
			} else {
				var msg = '';

				jQuery.each( data.error, function( i, val ) {
					jQuery('#form_' + i).removeClass('tb-has-success').addClass('tb-has-error');
					jQuery('#voter_' + i).popover({ content: val }).popover('show');

				});

				resizeModal();

			}
		},
		'json'
	);

});

<?php endif; ?>

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

	window.open(url, '<?php echo __psc("Share"); ?>','scrollbars=yes,menubar=no,height=420,width=700,resizable=yes,toolbar=no,status=no');
	return false;
});

function resizeModal() {
	var offset = jQuery(document).scrollTop(),
	viewportHeight = jQuery(window).height(),
	$myDialog = jQuery('#modal-window');
	$myDialog.css('top',  (offset  + (viewportHeight/2)) - ($myDialog.outerHeight()/2))
}

jQuery(".tb-modal-wide").on("shown.bs.modal", function() {
	resizeModal();
});
</script>
