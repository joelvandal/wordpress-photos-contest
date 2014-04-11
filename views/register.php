<style>
        .post-meta { display:none; }
	.entry h1.title { display:none; }
	.register-form .clear-form { clear: both; font-size: 16px; }
	.register-form input[type='text'], .register-form input[type='email'] { width: 300px; margin-bottom: 13px; }
	.register-form select { margin-bottom: 13px; width: 300px }
	.register-form .small { margin-bottom: 13px; width: 75px }
	.register-form textarea { height: 150px; width: 450px; float: none; margin-bottom: 13px; }
	.register-form input[type='radio'], .register-form input[type='checkbox'] { float: none; margin-bottom: 13px; }
	.register-form label { margin-bottom: 3px; float: none; font-weight: bold; display: block; }
	.register-form label span { color: #AAA; margin-left: 4px; font-weight: normal; }
	.form-errors .form-error-message { color: red; }
</style>

<div id="display-form" class="register-form">

	<h3><?php _e_psc('Submitting your Idea'); ?></h3>

	<h4><?php _e_psc('Please fill out this form and upload your image and idea description to register.'); ?></h4>
	<br />
	<p>
	        <label for="input-first-name"><?php _e_psc('Name'); ?> <span><?php _e('(required)'); ?></span></label>
	        <input type="text" id="input-first-name" placeholder="<?php _e_psc('First Name'); ?>" name="first_name" style="width: 150px">
	        <input type="text" id="input-last-name" placeholder="<?php _e_psc('Last Name'); ?>" name="last_name"  style="width: 150px">
	</p>

	<p>
	        <label for="input-artist"><?php _e_psc('Artist Name'); ?> <span><?php _e('(required)'); ?></span></label>
	        <input size=20 type="text" id="input-artist" placeholder="<?php _e_psc('Artist Name'); ?>" name="artist" class="small">
		<label for="input-artist-show" class="input-label"> <?php _e_psc('Your artist name will be displayed next to your idea instead of your name'); ?></label>
	</p>

	<p>
		<div style="float: left; width: 150px">
			 <label class="input-label"><?php _e_psc('Age'); ?> <span><?php _e('(required)'); ?></span></label>
			<select id="input-age" class="small" name="age">
	    <?php
	    $cats = psc_get_category('school');
	    for($i = 6; $i <= 99; $i++) {
		echo sprintf('<option value="%s">%s</option>', $i, $i);
	    }
	    ?>	
			</select>
		</div>

		<div style="float: left; padding-left: 50px">
			<label class="input-label"><?php _e_psc('Level'); ?> <span><?php _e('(required)'); ?></span></label>
			<input type="radio" name="input-level" id="input-level" value="primary" checked> <?php _e_psc('Primary'); ?>
			<input type="radio" name="input-level" id="input-level" value="high school"> <?php _e_psc('High School'); ?>

		</div>

		<div style="clear: both"></div>
	</p>

	<p>
		<label for="input-email" class="input-label"><?php _e_psc('Enter Your Email'); ?> <span><?php _e('(required)'); ?></span></label>
		<input type="email" id="input-email" placeholder="<?php _e_psc('Your Email Address'); ?>" name="email" class="input-large">
	</p>

<!--
	<h4><?php _e_psc('Please enter informations Your School/University'); ?></h4>
-->

	<p>
		<label for="input-school" class="input-label"><?php _e_psc('School'); ?></label>
		<select id="input-school" name="school" class="name">
			<option value=""><?php _e_psc('- Select your School -'); ?></option>
			<option value="NA"><?php _e_psc('Not applicable'); ?></option>
	    <?php
	    $cats = psc_get_category('school');
	    foreach($cats as $cat) {
		echo sprintf('<option value="%s">%s</option>', $cat['id'], $cat['category_name']);
	    }
	    ?>	
		</select>
	</p>
	<p>
		<label for="input-classname" class="input-label"><?php _e_psc('Teacher Name'); ?></label>
		<input type="text" maxlength="30" id="input-classname" placeholder="<?php _e_psc("Type your teacher's name here"); ?>" name="classname">
	</p>

	<br />

	<p>
	<label for="input-project-name" class="input-label"><?php _e_psc('Project Title'); ?> <span><?php _e('(required)'); ?></span></label>
		<input type="text" maxlength="30" id="input-project-name" placeholder="<?php _e_psc('Type your concept title here'); ?>" name="project">
	</p>

	<p>
		<label for="input-project-category" class="input-label"><?php _e_psc('Project Category'); ?></label>
		<select id="input-project-category" name="category" class="name">
			<option value=""><?php _e_psc('- Select your Category -'); ?></option>
	    <?php
	    $cats = psc_get_category('project');
	    foreach($cats as $cat) {
		echo sprintf('<option value="%s">%s</option>', $cat['id'], $cat['category_name']);
	    }
	    ?>
		</select>
	</p>

	<p>

		<div id="show-upload" style="display:none">
			<label for="dropfile" class="input-label"><?php _e_psc('Upload your image'); ?></label>
			<form action="<?php echo PSC_PATH . 'upload.php'; ?>" class="dropzone" id="dropfile" style="width: 400px">
				<input type="hidden" id="hidden-participant" name="participant" value="">
			</form>
		</div>
	</p>

	<p>
		<label for="input-project-description" class="input-label"><?php _e_psc('Project Description'); ?> <span><?php _e('(required)'); ?></span></label>
		<textarea id="input-project-description" placeholder="<?php _e_psc('Project Description'); ?>" name="category"></textarea>
	</p>

	</div>

	<p>                                        
<!--	
		<label for="input-rules" class="input-label"><input type="checkbox" id="input-rules" name="agree_rule"> <?php _e_psc('I Accept contest rules'); ?></label>
-->
		<label for="input-terms" class="input-label"><input type="checkbox" id="input-terms" name="agree_rule"> <?php echo sprintf(__psc('I accept the <a target="_new" href="/terms-conditions?lang=%s">terms and conditions</a> in regards to my entry.'), (isset($_GET['lang']) ? $_GET['lang'] : 'en')); ?></label>
<br />
		<label for="input-mail-site" class="input-label"><input checked type="checkbox" id="input-mail-site" name="agree_mail_cb"> <?php echo __psc('I would like to receive the <a target="_new" href="http://charlesbombardier.com">CharlesBombardier.com</a> monthly newsletter.'); ?></label>
<!--
<br />
		<label for="input-mail-contest" class="input-label"><input type="checkbox" id="input-mail-contest" name="agree_mail_contest"> <?php _e_psc('I Accept to be notified by e-mail regarding my concept idea'); ?></label>
-->
	</p>

	<button id="register-button" class="readmore"><?php _e_psc('Submit'); ?></button>

</div>

<div class="tb-modal tb-modal-wide tb-fade" id="registerModal" style="display: none;" data-keyboard="false" data-backdrop="static">

<div class="tb-modal-dialog">
	<div class="tb-modal-content">
		<div class="tb-modal-header">
			<button type="button" class="tb-close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<div class="tb-h3" id="modal-title"></div>
		</div>

		<div class="tb-modal-body tb-overflow-visible" id="modal-body">

		</div>

		<div class="tb-modal-footer">
			<div class="tb-pull-right"><button type="button" id="btnClose" class="tb-btn tb-btn-danger" data-dismiss="modal" aria-hidden="true"><?php _e_psc('Close'); ?></button></div>
		</div>
	</div>
</div>
</div>

<script type="text/javascript">
jQuery.noConflict();

(function($){
	$.fn.textareaCounter = function(options) {
		var defaults = {
			limit: 100
		};	
		var options = $.extend(defaults, options);
 
		return this.each(function() {
			var obj, text, wordcount, limited;
			
			obj = $(this);
			obj.after('<div style="width: 450px; text-align: right"><span style="font-size: 11px; clear: both; margin-top: 3px; display: block;" id="counter-text">Max. '+options.limit+' words</span></div>');

			obj.keyup(function() {
				text = obj.val();
				if(text === "") {
					wordcount = 0;
				} else {
					wordcount = $.trim(text).split(" ").length;
				}
				if(wordcount > options.limit) {
					$("#counter-text").html('<span style="color: #DD0000;">0 words left</span>');
					limited = $.trim(text).split(" ", options.limit);
					limited = limited.join(" ");
					$(this).val(limited);
				} else {
			        	$("#counter-text").html((options.limit - wordcount)+' words left');
				}
			});
		});
	};
})(jQuery);

jQuery("textarea").textareaCounter({ limit: 350 });

var upload_image = false;
var click_terms = false;
var valid_email = false;

jQuery('#input-email').on('change', function() {

	jQuery('#hidden-participant').val(jQuery('#input-email').val());

	var params = {
		action: 'check_email',
		email: jQuery("#input-email").val(),
		_ajax_nonce: '<?php echo $nonce ?>'
	}

	jQuery.post(
		'<?php echo PSC_PATH . 'ajax.php'; ?>',
		params,
		function(data) {
			if (data.status == 1) {
				msg = '<?php _e_psc('The Email address is already registered!'); ?>';
				jQuery("#registerModal").modal('show');
				jQuery("#modal-title").html('<?php _e_psc('Please fix the following error(s):'); ?>');
				jQuery("#modal-body").html(msg);
				valid_email = false;
				jQuery("#show-upload").hide();
				return false;
			} else {
				jQuery("#show-upload").show();
				valid_email = true;
			}
		},
		'json'
	);

});

jQuery('#input-artist-show').on('click', function() {

	if (jQuery("#input-artist-show").is(':checked')) {
		jQuery('#show-artist').show();
	} else {
		jQuery('#show-artist').hide();
	}

});

jQuery('#register-button').on('click', function() {

	click_terms = jQuery('#input-terms').is(':checked');

	if (!valid_email) {
		msg = '<?php _e_psc('You must enter a valid Email address in order to continue!'); ?>';
		jQuery("#registerModal").modal('show');
		jQuery("#modal-title").html('<?php _e_psc('Please fix the following error(s):'); ?>');
		jQuery("#modal-body").html(msg);
		return false;
	}

	if (!click_terms) {
		msg = '<?php _e_psc('You must agree Terms and Conditions in order to continue!'); ?>';
		jQuery("#registerModal").modal('show');
		jQuery("#modal-title").html('<?php _e_psc('Please fix the following error(s):'); ?>');
		jQuery("#modal-body").html(msg);
		return false;
	}

	if (!upload_image) {
		msg = '<?php _e_psc('You must upload an image in order to continue!'); ?>';
		jQuery("#registerModal").modal('show');
		jQuery("#modal-title").html('<?php _e_psc('Please fix the following error(s):'); ?>');
		jQuery("#modal-body").html(msg);
		return false;
	}

	var params = {
		action: 'register',
		email: jQuery("#input-email").val(),
		first_name: jQuery("#input-first-name").val(),
		last_name: jQuery("#input-last-name").val(),
		artist: jQuery("#input-artist").val(),
		last_name: jQuery("#input-last-name").val(),
		age: jQuery("#input-age").val(),
		school: jQuery("#input-school").val(),
		class_level: jQuery("#input-level").val(),
		class_name: jQuery("#input-classname").val(),
		project_name: jQuery("#input-project-name").val(),
		project_cat: jQuery("#input-project-category").val(),
		project_desc: jQuery("#input-project-description").val(),
		project_photo: jQuery("#input-project-photo").val(),
		mail_site: jQuery("#input-mail-site").is(':checked'),
		mail_contest: jQuery("#input-mail-contest").is(':checked'),
		_ajax_nonce: '<?php echo $nonce ?>'
	}; 

	jQuery.post(
		'<?php echo PSC_PATH . 'ajax.php'; ?>',
		params,
		function(data) {
			if (data.status == 'ok') {

				jQuery(".tb-close").remove();
				msg = '<?php _e_psc('Thanks for your subscription, we will contact you by email shortly!'); ?>';

				jQuery("#btnClose").on("click", function() {
					window.location.href="/gallery?lang=<?php echo isset($_GET['lang']) ? $_GET['lang'] : 'en'; ?>";
				});


				jQuery("#registerModal").modal('show');
				jQuery("#modal-title").html('<?php _e_psc('Registration'); ?>');
				jQuery("#modal-body").html(msg);


			} else {

				msg = '';
				msg += '<ul>';
				jQuery.each( data.error, function( i, val ) {
					msg += '<li>' + val + '<br />';
				});
				msg += '</ul>';

				jQuery("#registerModal").modal('show');
				jQuery("#modal-title").html('<?php _e_psc('Please fix the following error(s):'); ?>');
				jQuery("#modal-body").html(msg);

			}
		},
		'json'
	);

	return false;

});


Dropzone.options.dropfile = {
	paramName: "file",
	maxFilesize: 3,
	addRemoveLinks: true,
	acceptedFiles: 'image/*',
	maxFiles: 1,
	init: function () {
		var myDropzone = this;

		myDropzone.on("complete", function (file) {
			upload_image = true;
		});

        }

};

</script>
