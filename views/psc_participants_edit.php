<?php
$info = $wpdb->get_row("SELECT * FROM " . PSC_TABLE_PARTICIPANTS . " WHERE id=" . $item, ARRAY_A);
?>

<div class="wrap">

	<h2><?php _e('Photos Contest - Participants', PSC_PLUGIN); ?></h2>

	<form name="participant" id="participant" method="post" action="<?php echo str_replace('=edit', '=save', $_SERVER['REQUEST_URI']); ?>">
		<table class="form-table">

			<tr valign="top">
				<th align="left">
					<?php _e('First Name', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="text" name="first_name" value="<?php echo $info['first_name']; ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e('Last Name', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="text" name="last_name" value="<?php echo $info['last_name']; ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e('Email', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="text" name="email" value="<?php echo $info['email']; ?>" />
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

		<table class="form-table">

			<tr valign="top">
				<th align="left">
					<?php _e('Project Name', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="text" name="project_name" value="<?php echo $info['project_name']; ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e('Project Category', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="text" name="project_category" value="<?php echo $info['project_category']; ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e('Project Description', PSC_PLUGIN); ?>
				</th>
				<td>
					<textarea name="project_description"><?php echo $info['project_description']; ?></textarea>
				</td>
			</tr>

		</table>

		<table class="form-table">

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

<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.datepicker').datepicker({
			showOn: "button",
			buttonImage: "<?php echo PSC_PATH . '/images/vcalendar.png';?>",
			buttonImageOnly: true,
			dateFormat : "yy-mm-dd"
		});
	});
</script>

