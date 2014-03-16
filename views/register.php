<style>
	.small { width: 150px; max-width: 98%; margin-bottom: 13px; }
	.register-form .clear-form { clear: both; font-size: 16px; }
	.register-form input[type='text'], .register-form input[type='email'] { width: 300px; max-width: 98%; margin-bottom: 13px; }
	.register-form select { margin-bottom: 13px; }
	.register-form textarea { height: 200px; width: 80%; float: none; margin-bottom: 13px; }
	.register-form input[type='radio'], .register-form input[type='checkbox'] { float: none; margin-bottom: 13px; }
	.register-form label { margin-bottom: 3px; float: none; font-weight: bold; display: block; }
	.register-form label.checkbox, .register-form label.radio { margin-bottom: 3px; float: none; font-weight: bold; display: inline-block; }
	.register-form label span { color: #AAA; margin-left: 4px; font-weight: normal; }
	.form-errors .form-error-message { color: red; }
	.textwidget input[type='text'], .textwidget input[type='email'], .textwidget textarea { width: 250px; max-width: 98%; }
</style>

<div id="display-form" class="register-form">

	<h4>Please enter informations About You</h4>

	<p>
	        <label for="input-first-name">Your Name <span>(required)</span></label>
	        <input type="text" id="input-first-name" placeholder="First Name" name="first_name" class="small">
		<br />
	        <input type="text" id="input-last-name" placeholder="Last Name" name="last_name" class="small">

	</p>

	<p>
		<div style="float: left; width: 170px">
			<label class="input-label">Gender <span>(required)</span></label>
			<input type="radio" name="input-sex" id="input-sex" value="m" checked> Male
			<br />
			<input type="radio" name="input-sex" id="input-sex" value="f"> Female
		</div>

		<div style="float: left; padding-left: 50px">
			 <label class="input-label">Age <span>(required)</span></label>
			 <input type="number" name="input-age" min="1" max="99" id="input-age">
		</div>

		<div style="clear: both"></div>
	</p>

	<p>
		<label for="input-email" class="input-label">Enter Your Email <span>(required)</span></label>
		<input type="email" id="input-email" placeholder="Your Email Address" name="email" class="input-large">
	</p>

	<h4>Please enter informations Your School/University</h4>

	<p>
		<label for="input-school" class="input-label">School Name</label>
		<select id="input-school" name="school" class="name">
			<option value=""><?php _e('- Select your School -'); ?></option>
	    <?php
	    $cats = psc_get_category('school');
	    foreach($cats as $cat) {
		echo sprintf('<option value="%s">%s</option>', $cat['id'], $cat['category_name']);
	    }
	    ?>	
		</select>
	</p>

	<p>
		<label for="input-classname" class="input-label">Class Name</label>
		<input type="text" id="input-classname" placeholder="Enter your Class Name" name="class">
	</p>

	<p>                                        
		<label for="input-rules" class="input-label"><input type="checkbox" id="input-rules" name="agree_rule"> I Accept contest rules</label>
		<label for="input-terms" class="input-label"><input type="checkbox" id="input-terms" name="agree_rule"> I Accept Terms</label>
	</p>

	<br />

	<!-- DIV PROJECT -->
	<div style="display: none" id="div-project">

	<h4>Talk us about your project</h4>

	<p>
		<label for="input-project-name" class="input-label">Project Name</label>
		<input type="text" maxlength="30" id="input-project-name" placeholder="Project Name" name="project">
	</p>

	<p>
		<label for="input-project-category" class="input-label">Project Category</label>
		<select id="input-project-category" name="category" class="name">
			<option value=""><?php _e('- Select your Category -'); ?></option>
	    <?php
	    $cats = psc_get_category('project');
	    foreach($cats as $cat) {
		echo sprintf('<option value="%s">%s</option>', $cat['id'], $cat['category_name']);
	    }
	    ?>
		</select>
	</p>

	<p>
		<label for="input-project-description" class="input-label">Project Description</label>
		<textarea id="input-project-description" placeholder="Project Description" name="category"></textarea>
	</p>

	<p>
		<label for="dropfile" class="input-label">Image(s)</label>

		<form action="<?php echo PSC_PATH . 'upload.php'; ?>" class="dropzone" id="dropfile">
			<input type="hidden" id="hidden-participant" name="participant" value="">
		</form>
	</p>

	<p>                                        
		<label for="input-mail-site" class="input-label"><input type="checkbox" id="input-mail-site" name="agree_mail_cb"> I Accept to receive mail from Charles</label>
		<label for="input-mail-contest" class="input-label"><input type="checkbox" id="input-mail-contest" name="agree_mail_contest"> I Accept to receive mail about countest</label>
	</p>

	</div>

	<button id="register-button" class="btn" style="display:none">Register &#187;</button>


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
			obj.after('<span style="font-size: 11px; clear: both; margin-top: 3px; display: block;" id="counter-text">Max. '+options.limit+' words</span>');

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

jQuery("textarea").textareaCounter({ limit: 1000 });

var click_terms = false;
var click_rules = false;
var click_mail_site = false;
var click_mail_contest = false;

jQuery('#input-terms').on('click', function() {
	check_project();
});

jQuery('#input-rules').on('click', function() {
	check_project();
});

jQuery('#input-mail-site').on('click', function() {
	check_project();
});

jQuery('#input-mail-contest').on('click', function() {
	check_project();
});

function check_project() {

	jQuery('#hidden-participant').val(jQuery('#input-email').val());

	click_terms = jQuery('#input-terms').is(':checked');
	click_rules = jQuery('#input-rules').is(':checked');

	click_mail_site = jQuery('#input-terms').is(':checked');
	click_mail_contest = jQuery('#input-rules').is(':checked');

	if (click_terms && click_rules) {
		jQuery('#div-project').show();
		jQuery('#register-button').show();

	} else {
		jQuery('#div-project').hide();
		jQuery('#register-button').hide();
	}
}

jQuery('#register-button').on('click', function() {

	var params = {
		action: 'register',
		email: jQuery("#input-email").val(),
		first_name: jQuery("#input-first-name").val(),
		last_name: jQuery("#input-last-name").val(),
		sex: jQuery("#input-sex").val(),
		age: jQuery("#input-age").val(),
		school: jQuery("#input-school").val(),
		class_name: jQuery("#input-classname").val(),
		project_name: jQuery("#input-project-name").val(),
		project_cat: jQuery("#input-project-category").val(),
		project_desc: jQuery("#input-project-description").val(),
		project_photo: jQuery("#input-project-photo").val(),
		mail_site: jQuery("#input-mail-site").is(':checked'),
		mail_contest: jQuery("#input-mail-contest").is(':checked')
	}; 

	jQuery.post(
		'<?php echo PSC_PATH . 'ajax.php'; ?>',
		params,
		function(data) {
			if (data.status == 'ok') {
				jQuery("#display-form").hide();
				jQuery("#display-success").show();

				msg = '';
				msg = '<p>Thanks for your subscription, we will contact you by email shortly!</p>';
				msg += '<ul>';
				jQuery.each( data.error, function( i, val ) {
					msg += '<li>' + val + '<br />';
				});
				msg += '</ul>';

				jQuery.fancybox(msg, {
					'width':350,
					'height':250,
					'maxWidth': 640,
					'maxHeight': 480,
					'minWidth': 350,
					'minHeight': 150,
				});


			} else {
				console.dir(data.status);

				msg = '';
				msg = '<p>Please fix the following error(s):' + '</p>';
				msg += '<ul>';
				jQuery.each( data.error, function( i, val ) {
					msg += '<li>' + val + '<br />';
				});
				msg += '</ul>';

				jQuery.fancybox(msg, {
					'width':350,
					'height':250,
					'maxWidth': 640,
					'maxHeight': 480,
					'minWidth': 350,
					'minHeight': 150,
				});

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
	maxFiles: 1
};

</script>
