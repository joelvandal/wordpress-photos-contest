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

.mask{
   position: relative;
   overflow: hidden;
   margin: 0px auto;
   width: 100%;
   height: 100%;
   background-color: #f4f4f4
}
.header{
text-align: center;
   position: absolute;
   top: 0px;
   height: 35px;
 line-height: 35px;
   width: 100%;
   background-color: #bbb;
}
.colleft{
   position: absolute;
   top: 45px;
   bottom: 30px;
   left: 5px;
   right: 5px;
   background-color: #f4f4f4
}
.col1{
text-align: left;
   position: absolute;
   overflow: hidden;
   right: 5px;
   width: 58%;
   background-color: #e6e6e6
}
.col2{
text-align: left;
   position: absolute;
   overflow: hidden;
//   float: left;
   left: 5px;
    width: 40%;
   background-color: #e6e6e6
}
.footer{
text-align: center;
position: absolute;
bottom: 0px;
   width: 100%;
height: 35px;
   background-color: #ccc;
}

#wrap {
   width:95%;
   margin:0 auto;
}

#wrap-desc {
text-align: justify;
   width:95%;
   margin:0 auto;
}
#left_col {
   float:left;
   width:30%;
}
#right_col {
   float:right;
   width:70%;
}

#left_col2 {
   float:left;
   width:70%;
}
#right_col2 {
   float:right;
   width:30%;
}

</style>


<div class="mask">
	<div class="header">
		<b><?php _e('Information about this participant', PSC_PLUGIN); ?></b>
	</div>

	<div class="colleft">
		<div class="col1">
			<img width="100%" src="<?php echo PSC_PATH . 'uploads/' . md5($item['email']) . '-view.png'; ?>" />
		</div>

		<div class="col2">

&nbsp;<br />

<div id="wrap">
	<div id="left_col"><b>Project Name:</b></div>
	<div id="right_col"><?php echo $item['project_name']; ?></div>
</div>

<div id="wrap">
	<div id="left_col"><b>Project Type:</b></div>
	<div id="right_col"><?php echo psc_get_project($item['project_category']); ?></div>
</div>

&nbsp;<br />
<div id="wrap">
	<div id="left_col"><b>Name:</b></div>
	<div id="right_col"><?php echo ucwords(strtolower(sprintf("%s %s", $item['first_name'], $item['last_name']))); ?></div>
</div>

<div id="wrap">
	<div id="left_col"><b>Age:</b></div>
	<div id="right_col"><?php echo $item['age']; ?></div>
</div>

&nbsp;<br />
<div id="wrap">
	<div id="left_col"><b>School Name:</b></div>
	<div id="right_col"><?php echo psc_get_school($item['school']); ?></div>
</div>

<div id="wrap">
	<div id="left_col"><b>Class Name:</b></div>
	<div id="right_col"><?php echo psc_get_class($item['class_name']); ?></div>
</div>

&nbsp;<br />
<div id="wrap">
<b>Description:</b>
</div>
<div id="wrap-desc">
<?php echo $item['project_description']; ?>
</div>
</p>
<br />

<div id="wrap">
	<div id="left_col2"><b>Vote for this participant:</b></div>
	<div id="right_col2">

<?php if (psc_is_vote_open()): ?>
<button><?php _e('Vote', PSC_PLUGIN); ?></button>
<?php else: ?>
<?php _e('Vote is currently closed', PSC_PLUGIN); ?>
<?php endif; ?>
	</div>
</div>

<!--
<div id="wrap">
	<div id="left_col2"><b>Click here to sponsor this concept:</b></div>
	<div id="right_col2"><button><?php _e('Sponsor', PSC_PLUGIN); ?></button></div>
</div>
-->

&nbsp;<br />
&nbsp;<br />



        </div>


    </div> 
    <div class="footer">
		<div class="share">
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


<script>
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
