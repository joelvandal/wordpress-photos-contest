<?php
$info = $wpdb->get_row("SELECT * FROM " . PSC_TABLE_PARTICIPANTS . " WHERE id=" . $item, ARRAY_A);
?>

<div class="wrap">

	<h2><?php _e_psc('Imagine - Participants'); ?></h2>

	<form name="participant" id="participant" method="post" action="<?php echo str_replace('=edit', '=save', $_SERVER['REQUEST_URI']); ?>">

		<h3>Participant Informations</h3>

		<table class="form-table">

			<tr valign="top">
				<th align="left">
					<?php _e_psc('First Name'); ?>
				</th>
				<td>
					<input type="text" size="30" name="first_name" value="<?php echo $info['first_name']; ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e_psc('Last Name'); ?>
				</th>
				<td>
					<input type="text" size="30" name="last_name" value="<?php echo $info['last_name']; ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e_psc('Artist Name'); ?>
				</th>
				<td>
					<input type="text" size="20" name="artist" value="<?php echo $info['artist']; ?>" />
				</td>
			</tr>
<!--
			<tr valign="top">
				<th align="left">
					<?php _e_psc('Use Artist Name ?'); ?>
				</th>
				<td>
					<input type="checkbox" name="artist_show" <?php if ($info['artist_show']) { echo 'checked'; } ?> />
				</td>
			</tr>
-->
			<tr valign="top">
				<th align="left">
					<?php _e_psc('Email'); ?>
				</th>
				<td>
					<input type="text" size="50" name="email" value="<?php echo $info['email']; ?>" />
				</td>
			</tr>


			<tr valign="top">
				<th align="left">
					<?php _e_psc('Age'); ?>
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
					<?php _e_psc('Level'); ?>
				</th>
				<td>
					<select name="class_level">
						<option <?php if ($info['class_level'] == 'primary') { echo 'selected'; } ?> value="primary"><?php _e_psc('Primary'); ?></option>
						<option <?php if ($info['class_level'] == 'high school') { echo 'selected'; } ?> value="high school"><?php _e_psc('High School'); ?></option>
					</select>
				</td>
			</tr>


			<tr valign="top">
				<th align="left">
					<?php _e_psc('School Name'); ?>
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
					<?php _e_psc('Teacher Name'); ?>
				</th>
				<td>
					<input type="text" size="80" name="class_name" value="<?php echo $info['class_name']; ?>" />
				</td>
			</tr>
		</table>


		<h3>Project Informations</h3>

		<table class="form-table">

			<tr valign="top">
				<th align="left">
					<?php _e_psc('Project Name'); ?>
				</th>
				<td>
					<input type="text" size="80" name="project_name" value="<?php echo $info['project_name']; ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e_psc('Project Category'); ?>
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
					<?php _e_psc('Project Description'); ?>
				</th>
				<td>
					<textarea cols="80" rows="5" name="project_description"><?php echo esc_textarea($info['project_description']); ?></textarea>
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e_psc('Project Image'); ?>
				</th>
				<td>
					<img id="thumbnail" src="<?php echo PSC_IMAGE . md5($info['email']) . '-thumb.png'; ?>" />
					<p>
						<a href="#" class="upload tb-btn tb-btn-warning"><?php _e_psc('Change Image'); ?></a>
					</p>
				</td>
			</tr>

		</table>

		<h3>Subscription</h3>

		<table class="form-table">

			<tr valign="top">
				<th align="left">
					<?php _e_psc('Subscription Date'); ?>
				</th>
				<td>
					<input type="text" class="datepicker" name="subscribe_date" value="<?php echo psc_format_datetime($info['subscribe_date']); ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e_psc('Approved'); ?>
				</th>
				<td>
					<input type="checkbox" name="approved" <?php if ($info['approved']) { echo 'checked'; } ?> />
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e_psc('Mail Site'); ?>
				</th>
				<td>
					<input type="checkbox" name="mail_site" <?php if ($info['mail_site']) { echo 'checked'; } ?> />
				</td>
			</tr>
<!--
			<tr valign="top">
				<th align="left">
					<?php _e_psc('Mail Contest'); ?>
				</th>
				<td>
					<input type="checkbox" name="mail_contest" <?php if ($info['mail_contest']) { echo 'checked'; } ?> />
				</td>
			</tr>
-->
		</table>

		<?php submit_button(); ?>

	</form>
</div>

<div class="uploadModal">
<div class="tb-modal tb-modal-wide tb-fade" id="uploadForm" style="display: none;">

<div class="tb-modal-dialog">
	<div class="tb-modal-content">
		<div class="tb-modal-header">
			<button type="button" class="tb-close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php _e_psc('Upload Image'); ?></h3>
		</div>

		<div class="tb-modal-body tb-overflow-visible">

	<form action="<?php echo PSC_PATH . 'upload.php'; ?>" class="dropzone" id="dropfile">
		<input type="hidden" id="hidden-participant" name="participant" value="<?php echo $info['email']; ?>">
	</form>

		</div>

		<div class="tb-modal-footer">
			<div class="tb-pull-left"><button type="button" class="tb-btn tb-btn-danger" data-dismiss="modal" aria-hidden="true"><?php _e_psc('Cancel'); ?></button></div>
			<div class="tb-pull-right"><button id="uploadSubmit" class="tb-btn tb-btn-primary"><?php _e_psc('Upload Image'); ?></button></div>
		</div>
	</div>
</div>
</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('.datepicker').datetimepicker({
		format : "Y-m-d H:i"
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
			jQuery("#thumbnail").attr('src', '<?php echo PSC_IMAGE . md5($info['email']) . '-thumb.png'; ?>?' + cdate);
			jQuery('#uploadForm').hide();
			jQuery('.tb-modal-backdrop').remove();
		});

        }
};



});

</script>

