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

.modal.modal-wide .modal-dialog {
	width: 80%;
	height: 80%;
}

.modal-wide .modal-body {
	overflow-y: auto;
}

</style>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php _e('Information about this participant', PSC_PLUGIN); ?></h3>
		</div>

		<div class="modal-body overflow-visible">

			<div class="row">
				<div class="col-xs-12 col-sm-12 col-lg-5 col-md-5">
	
					<table class="table table-striped table-hover table-responsive text-left">
					<tbody>
						<tr><th style="width: 150px">Project Name:</th><td><?php echo $item['project_name']; ?></td></tr>
						<tr><th>Project Type:</th><td><?php echo psc_get_project($item['project_category']); ?></td></tr>
						<tr><th>Name:</th><td><?php echo ucwords(strtolower(sprintf("%s %s", $item['first_name'], $item['last_name']))); ?></td></tr>
						<tr><th>Age:</th><td><?php echo $item['age']; ?></td></tr>
						<tr><th>School Name:</th><td><?php echo psc_get_school($item['school']); ?></td></tr>
						<tr><th>Class Name:</th><td><?php echo psc_get_class($item['class_name']); ?></td></tr>
						<tr id="div-desc"><th>Description:</th><td><?php echo $item['project_description']; ?></td></tr>
					</tbody>
					</table>


		<?php if (psc_is_vote_open()): ?>
			<?php if ($email = psc_get_vote_email()): ?>
						<div id="alreadyVoted">
				<?php echo sprintf(__('You already voted using %s!', PSC_PLUGIN), $email); ?>
							<br />
							<button class="btn btn-primary" id="resetVote"><?php _e('Use a different Email', PSC_PLUGIN); ?></button>
						</div>
			<?php endif; ?>
						<button class="btn btn-primary text-center" id="showVote"><?php _e('Vote for this participant', PSC_PLUGIN); ?></button>
		<?php else: ?>
			<?php _e('Vote is currently closed', PSC_PLUGIN); ?>
		<?php endif; ?>

					<div style="display: none" class="voteMessage"></div>
		
<?php if (psc_is_vote_open()): ?>
					<div id="voterDetails" style="display: none;" class="text-left">
					<h4 class="text-left">Vote for this participant:</h4>

						<table class="table table-striped table-hover table-responsive text-left">
						<tbody>
							<tr>
								<th style="width: 150px; vertical-align: middle"><label for="voter_email"><?php _e('Enter your Email:', PSC_PLUGIN); ?></label></th>
								<td><input type="text" class="form-control" id="voter_email" placeholder="Enter email"></td>
							</tr>
		
							<tr>
								<th style="width: 150px; vertical-align: middle"><label for="voter_name"><?php _e('Enter your Name:', PSC_PLUGIN); ?></label></th>
								<td><input type="text" class="form-control" id="voter_name" placeholder="Enter name"></td>
							</tr>
						</tbody>
						</table>
		
						<div class="pull-right">
							<button class="btn btn-sm btn-success" id="sendVote"><?php _e('Vote Now', PSC_PLUGIN); ?></button>
							<button class="btn btn-sm btn-danger" id="cancelVote"><?php _e('Cancel', PSC_PLUGIN); ?></button>
						</div>
					</div>
<?php endif; ?>

				</div>

				<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
					<img class="img-responsive img-thumbnail text-center" style="max-height: 90%" src="<?php echo PSC_PATH . 'uploads/' . md5($item['email']) . '-view.png'; ?>" />
				</div>
			</div>
		</div>

		<div class="modal-footer">
			<div class="share text-center">
				<b><?php echo __("Share this picture on", PSC_PLUGIN); ?></b>
				<li class="share-item">
					<a data-network="facebook" class="share-link ico-facebook" href="#" rel="nofollow">Facebook</a>
				</li>
				<li class="share-item">
					<a data-network="twitter" class="share-link ico-twitter" href="#" rel="nofollow">Twitter</a>
				</li>
				<li class="share-item">
					<a data-network="google" class="share-link ico-google" href="#" rel="nofollow">Google+</a>
				</li>
				<li class="share-item">
					<input type="text" readonly value="<?php echo psc_shorturl($item['id']); ?>">
				</li>
			</div>
		</div>
	</div>
</div>

<script>

<?php if (psc_is_vote_open()): ?>

<?php if ($email = psc_get_vote_email()): ?>
jQuery('#showVote').hide();
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
				jQuery('#voterDetails').show();
			}
		},
		'json'
	);
});

jQuery('#showVote').on('click', function() {
	jQuery('#div-desc').hide();
	jQuery('#showVote').hide();
	jQuery('#voterDetails').show();
	jQuery(".voteMessage").hide();
});

jQuery('#cancelVote').on('click', function() {
	jQuery('#div-desc').show();
	jQuery('#showVote').show();
	jQuery('#voterDetails').hide();
	jQuery(".voteMessage").hide();
});

jQuery('#sendVote').on('click', function() {

	var params = {
		action: 'vote',
		email: jQuery("#voter_email").val(),
		name: jQuery("#voter_name").val(),
		participant_id: '<?php echo $item['id']; ?>'
	};

	jQuery.post(
		'<?php echo PSC_PATH . 'ajax.php'; ?>',
		params,
		function(data) {
			console.dir(data);
			if (data.status == 'ok') {
				jQuery(".voteMessage").html('<?php esc_html_e(__("Thanks! Please check your email to validate your vote!", PSC_PLUGIN)); ?>').show();
				jQuery('#voterDetails').hide();
			} else {
				jQuery(".voteMessage").html(data.error).show();
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

	window.open(url, '<?php echo __("Share", PSC_PLUGIN); ?>','scrollbars=yes,menubar=no,height=420,width=700,resizable=yes,toolbar=no,status=no');
	return false;
});
</script>
