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

.<?php echo $prefix; ?>popover {
    width:200px;
//    height:250px;    
}

.<?php echo $prefix; ?>modal.<?php echo $prefix; ?>modal-wide .<?php echo $prefix; ?>modal-dialog {
	width: 80%;
//	height: 60%;
}

.<?php echo $prefix; ?>modal-wide .<?php echo $prefix; ?>modal-body {
//height: 60%;
	overflow-y: auto;
}

.<?php echo $prefix; ?>img-responsive {
    display: block;
    width: auto;
    height: 80%;
}
</style>

<div id="modal-window" class="<?php echo $prefix; ?>modal-dialog">
	<div class="<?php echo $prefix; ?>modal-content">
		<div class="<?php echo $prefix; ?>modal-header">
			<button type="button" class="<?php echo $prefix; ?>close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4><?php _e('Information about this participant', PSC_PLUGIN); ?></h4>
		</div>
		<div class="<?php echo $prefix; ?>modal-body <?php echo $prefix; ?>overflow-visible">

			<div class="<?php echo $prefix; ?>row">
				<div class="<?php echo $prefix; ?>col-xs-12 <?php echo $prefix; ?>col-sm-12 <?php echo $prefix; ?>col-lg-5 <?php echo $prefix; ?>col-md-5">
	
					<table class="<?php echo $prefix; ?>table <?php echo $prefix; ?>table-striped <?php echo $prefix; ?>table-hover <?php echo $prefix; ?>table-responsive <?php echo $prefix; ?>text-left">
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

			<div class="<?php echo $prefix; ?>alert <?php echo $prefix; ?>alert-warning">
				<div class="<?php echo $prefix; ?>text-center">
				<?php echo sprintf(__('You already voted using %s!', PSC_PLUGIN), $email); ?>
<br /><br />
				<button class="<?php echo $prefix; ?>btn <?php echo $prefix; ?>btn-warning" id="resetVote"><?php _e('Use a different Email', PSC_PLUGIN); ?></button>
				</div>
			</div>
</div>

<?php else: ?>

			<div class="<?php echo $prefix; ?>text-center">
				<button class="<?php echo $prefix; ?>btn <?php echo $prefix; ?>btn-primary" id="showVote"><?php _e('Vote for this participant', PSC_PLUGIN); ?></button>
			</div>


			<?php endif; ?>
		<?php else: ?>
			<div class="<?php echo $prefix; ?>alert <?php echo $prefix; ?>alert-danger">
				<div class="<?php echo $prefix; ?>text-center">
					<b><?php _e('Vote is currently closed!', PSC_PLUGIN); ?></b>
				</div>
			</div>
		<?php endif; ?>

					<div style="display: none" class="voteMessage"></div>
		
<?php if (psc_is_vote_open()): ?>
					<div id="voterDetails" style="display: none;" class="<?php echo $prefix; ?>text-left">
					<h4 class="<?php echo $prefix; ?>text-left">Vote for this participant:</h4>

						<table class="<?php echo $prefix; ?>table <?php echo $prefix; ?>table-striped <?php echo $prefix; ?>table-hover <?php echo $prefix; ?>table-responsive <?php echo $prefix; ?>text-left">
						<tbody>
							<tr>
								<th style="width: 150px; vertical-align: middle"><label for="voter_email"><?php _e('Enter your Email:', PSC_PLUGIN); ?></label></th>
								<td><div class="form-input" id="form_email"><input type="text" class="form-control" id="voter_email" placeholder="Enter email"></div></td>
							</tr>
		
							<tr>
								<th style="width: 150px; vertical-align: middle"><label for="voter_name"><?php _e('Enter your Name:', PSC_PLUGIN); ?></label></th>
								<td><div class="form-input" id="form_name"><input type="text" class="<?php echo $prefix; ?>form-control" id="voter_name" placeholder="Enter name"></div></td>
							</tr>
						</tbody>
						</table>
		
						<div class="<?php echo $prefix; ?>pull-right">
							<button class="<?php echo $prefix; ?>btn <?php echo $prefix; ?>btn-sm <?php echo $prefix; ?>btn-success" id="sendVote"><?php _e('Vote Now', PSC_PLUGIN); ?></button>
							<button class="<?php echo $prefix; ?>btn <?php echo $prefix; ?>btn-sm <?php echo $prefix; ?>btn-danger" id="cancelVote"><?php _e('Cancel', PSC_PLUGIN); ?></button>
						</div>
					</div>
<?php endif; ?>

				</div>

				<div class="<?php echo $prefix; ?>col-xs-12 <?php echo $prefix; ?>col-sm-12 <?php echo $prefix; ?>col-md-7 <?php echo $prefix; ?>col-lg-7">
					<img class="<?php echo $prefix; ?>img-responsive <?php echo $prefix; ?>img-thumbnail <?php echo $prefix; ?>text-center" style="height:auto;" src="<?php echo PSC_PATH . 'uploads/' . md5($item['email']) . '-view.png'; ?>" />
				</div>
			</div>
		</div>

		<div class="<?php echo $prefix; ?>modal-footer">
			<div class="share <?php echo $prefix; ?>text-center">
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

//  jQuery('#voter_email').popover('hide');
//  jQuery('#voter_name').popover('hide');
jQuery('#form_email').addClass('has-success');
jQuery('#form_name').addClass('has-success');

	jQuery.post(
		'<?php echo PSC_PATH . 'ajax.php'; ?>',
		params,
		function(data) {
			if (data.status == 'ok') {
				jQuery(".voteMessage").html('<div class="<?php echo $prefix; ?>alert <?php echo $prefix; ?>alert-info"><?php esc_html_e(__("Thanks! Please check your email to validate your vote!", PSC_PLUGIN)); ?></div>').show();
				jQuery('#voterDetails').hide();
				jQuery('#div-desc').show();
			} else {
				var msg = '';

				jQuery.each( data.error, function( i, val ) {
					jQuery('#form_' + i).removeClass('has-success').addClass('has-error');
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

	window.open(url, '<?php echo __("Share", PSC_PLUGIN); ?>','scrollbars=yes,menubar=no,height=420,width=700,resizable=yes,toolbar=no,status=no');
	return false;
});

function resizeModal() {
	var offset = jQuery(document).scrollTop(),
	viewportHeight = jQuery(window).height(),
	$myDialog = jQuery('#modal-window');
	$myDialog.css('top',  (offset  + (viewportHeight/2)) - ($myDialog.outerHeight()/2))
}

jQuery(".<?php echo $prefix; ?>modal-wide").on("shown.bs.modal", function() {
	resizeModal();
});
</script>
