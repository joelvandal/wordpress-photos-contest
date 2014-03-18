<?php
$info = $wpdb->get_row("SELECT * FROM " . PSC_TABLE_PARTICIPANTS . " WHERE id=" . $item, ARRAY_A);
?>

<div class="wrap">

	<h2><?php _e('Photos Contest - Participants', PSC_PLUGIN); ?></h2>

	<form name="participant" id="participant" method="post" action="<?php echo str_replace('=edit', '=save', $_SERVER['REQUEST_URI']); ?>">

		<h3>Participant Informations</h3>

		<table class="form-table">

			<tr valign="top">
				<th align="left">
					<?php _e('First Name', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="text" size="30" name="first_name" value="<?php echo $info['first_name']; ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e('Last Name', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="text" size="30" name="last_name" value="<?php echo $info['last_name']; ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e('Email', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="text" size="50" name="email" value="<?php echo $info['email']; ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e('Sex', PSC_PLUGIN); ?>
				</th>
				<td>
					<select name="sex">
						<option <?php if ($info['sex'] == 'm') { echo 'selected'; } ?> value="m"><?php _e('Male', PSC_PLUGIN); ?></option>
						<option <?php if ($info['sex'] == 'f') { echo 'selected'; } ?> value="f"><?php _e('Female', PSC_PLUGIN); ?></option>
					</select>
				</td>
			</tr>


			<tr valign="top">
				<th align="left">
					<?php _e('Age', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="number" name="age" min="1" max="99" value="<?php echo $info['age']; ?>">
				</td>
			</tr>

		</table>

		<h3>School Informations</h3>

		<table class="form-table">

			<tr valign="top">
				<th align="left">
					<?php _e('School Name', PSC_PLUGIN); ?>
				</th>
				<td>

		<select id="input-school-name" name="school" class="name">
	    <?php
	    $cats = psc_get_category('school');
	    foreach($cats as $cat) {
		$selected = ($cat['id'] == $info['school']) ? 'selected' : '';
		echo sprintf('<option %s value="%s">%s</option>', $selected, $cat['id'], $cat['category_name']);
	    }
	    ?>
		</select>

				</td>
			</tr>


			<tr valign="top">
				<th align="left">
					<?php _e('Class Name', PSC_PLUGIN); ?>
				</th>
				<td>
		<select id="input-class-name" name="class_name" class="name">
	    <?php
	    $cats = psc_get_category('class_name');
	    foreach($cats as $cat) {
		$selected = ($cat['id'] == $info['class_name']) ? 'selected' : '';
		echo sprintf('<option %s value="%s">%s</option>', $selected, $cat['id'], $cat['category_name']);
	    }
	    ?>
		</select>
				</td>
			</tr>

		</table>


		<h3>Project Informations</h3>

		<table class="form-table">

			<tr valign="top">
				<th align="left">
					<?php _e('Project Name', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="text" size="80" name="project_name" value="<?php echo $info['project_name']; ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e('Project Category', PSC_PLUGIN); ?>
				</th>
				<td>

		<select id="input-project-category" name="project_category" class="name">
	    <?php
	    $cats = psc_get_category('project');
	    foreach($cats as $cat) {
		$selected = ($cat['id'] == $info['project_category']) ? 'selected' : '';
		echo sprintf('<option %s value="%s">%s</option>', $selected, $cat['id'], $cat['category_name']);
	    }
	    ?>
		</select>

				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e('Project Description', PSC_PLUGIN); ?>
				</th>
				<td>
					<textarea cols="80" rows="5" name="project_description"><?php echo esc_textarea($info['project_description']); ?></textarea>
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e('Project Image', PSC_PLUGIN); ?>
				</th>
				<td>
					<img id="thumbnail" src="<?php echo PSC_PATH . '/uploads/' . md5($info['email']) . '-thumb.png'; ?>" />
					<br />
					<a href="#" class="upload"><?php _e('Click here to upload a new image'); ?></a>
				</td>
			</tr>

		</table>

		<h3>Subscription</h3>

		<table class="form-table">

			<tr valign="top">
				<th align="left">
					<?php _e('Subscription Date', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="text" class="datepicker" name="subscribe_date" value="<?php echo psc_format_datetime($info['subscribe_date']); ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e('Approved', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="checkbox" name="approved" <?php if ($info['approved']) { echo 'checked'; } ?> />
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e('Mail Site', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="checkbox" name="mail_site" <?php if ($info['mail_site']) { echo 'checked'; } ?> />
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e('Mail Contest', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="checkbox" name="mail_contest" <?php if ($info['mail_contest']) { echo 'checked'; } ?> />
				</td>
			</tr>

		</table>

		<?php submit_button(); ?>

	</form>
</div>

<div id="uploadForm" style="display: none; position: absolute">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header header-color-blue">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php _e('Upload Image', PSC_PLUGIN); ?></h3>
		</div>

		<div class="modal-body overflow-visible">

	<form action="<?php echo PSC_PATH . 'upload.php'; ?>" class="dropzone" id="dropfile">
		<input type="hidden" id="hidden-participant" name="participant" value="<?php echo $info['email']; ?>">
	</form>

		</div>

		<div class="modal-footer">
		<button type="button" class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?php _e('Cancel', PSC_PLUGIN); ?></button>
			<div class="pull-right"><button id="uploadSubmit" class="btn btn-primary"><?php _e('Upload Image', PSC_PLUGIN); ?></button></div>
		</div>
	</div>
</div>

</div>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('.datepicker').datetimepicker({
		format : "Y-m-d H:i"
	});

	jQuery("#uploadClose").on('click', function() {
//		jQuery.fancybox.close();
	});

	jQuery("a.upload").live('click', function(event) {
		jQuery('#uploadForm').modal('show');
	});

Dropzone.options.dropfile = {
	paramName: "file",
	maxFilesize: 3,
	addRemoveLinks: true,
	acceptedFiles: 'image/*',
	maxFiles: 1,
        autoProcessQueue: false,

	init: function () {
		var myDropzone = this;

		jQuery("#uploadSubmit").on('click', function() {
			myDropzone.processQueue();
		});

		myDropzone.on("complete", function (file) {
			var cdate = new Date().getTime();
			jQuery("#thumbnail").attr('src', '<?php echo PSC_PATH . '/uploads/' . md5($info['email']) . '-thumb.png'; ?>?' + cdate);
//			jQuery.fancybox.close();
		});

        }
};



});
</script>

