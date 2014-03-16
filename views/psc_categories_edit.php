<?php
if ($item) {
    $info = $wpdb->get_row("SELECT * FROM " . PSC_TABLE_CATEGORIES . " WHERE id=" . $item, ARRAY_A);
} else {
    $info = array('category_name' => '', 'category_desc' => '');
}
?>

<div class="wrap">

	<h2><?php _e('Photos Contest - Categories', PSC_PLUGIN); ?></h2>

	<form name="category" id="category" method="post" action="<?php echo str_replace('=edit', '=save', $_SERVER['REQUEST_URI']); ?>">

		<h3>Category Informations</h3>

		<table class="form-table">

			<tr valign="top">
				<th align="left">
					<?php _e('Name', PSC_PLUGIN); ?>
				</th>
				<td>
					<input type="text" size="30" name="category_name" value="<?php echo $info['category_name']; ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e('Description', PSC_PLUGIN); ?>
				</th>
				<td>
					<textarea cols="80" rows="5" name="category_desc"><?php echo esc_textarea($info['category_desc']); ?></textarea>
				</td>
			</tr>

			<tr valign="top">
				<th align="left">
					<?php _e('Type', PSC_PLUGIN); ?>
				</th>
				<td>
					<select name="category_type">
	    
<?php global $psc_category_types;  foreach($psc_category_types as $ctype => $cname): ?>
						<option <?php if ($info['category_type'] == $ctype) { echo 'selected'; } ?> value="<?php echo $ctype; ?>"><?php echo $cname; ?></option>
<?php endforeach; ?>
					</select>
				</td>
			</tr>
		</table>

		<?php submit_button(); ?>

	</form>
</div>

