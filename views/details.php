  
<div style="max-width:1200px;min-width:200px;">

<table width="100%">
<tr><td valign="top" width="450px">
<table>
<tr><td>Participant Name:</td><td><?php echo sprintf("%s, %s", strtoupper($item['last_name']), $item['first_name']); ?></td></tr>
<tr><td>Project Name:</td><td><?php echo $item['project_name']; ?></td></tr>
<tr><td>Description:</td><td><?php echo $item['project_description']; ?></td></tr>
</table>

<?php if (psc_is_vote_open()): ?>
<button class="btn">Vote</button>
<?php else: ?>
<?php _e('Vote is currently closed', PSC_PLUGIN); ?>
<?php endif; ?>

</td><td>
<img width="800" src="<?php echo PSC_PATH . 'uploads/' . md5($item['email']) . '-view.png'; ?>" />
</td>
</tr>
</table>

</div>
