<div class="wrap">

	<h2><?php _e('Photos Contest - Categories', PSC_PLUGIN); ?></h2>

	<p>
		<form id="psc_category_filter">
			<input type="hidden" name="page" value="psc_categories" />
                <?php _e( 'Show Type', PSC_PLUGIN ) ?>
                <select name="type">
			<option value="" <?php if (!$_GET['type']) { echo 'selected'; } ?>><?php _e('All', PSC_PLUGIN); ?></option>
<?php global $psc_category_types;  foreach($psc_category_types as $ctype => $cname): ?>
			<option <?php if ($_GET['type'] == $ctype) { echo 'selected'; } ?> value="<?php echo $ctype; ?>"><?php echo $cname; ?></option>
<?php endforeach; ?>
                </select>
 <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Apply', PSC_PLUGIN ) ?>"/>
        </form>
        </p>


<?php
$my_table = new PSC_Categories_Table();
$my_table->prepare_items(); 
$my_table->display();
?>
</div>

<br />
<a href="?page=psc_categories&action=edit" class="button button-primary"><?php _e('Add a new item', PSC_PLUGIN); ?></a>
