<div class="wrap">
<?php
	if($_POST['cmd']=="save") {
		if("yes"==$_POST['pl_lightbox'])
			update_option("pl_lightbox",true);
		else
			update_option("pl_lightbox",false);
			
		update_option("pl_style",$_POST['pl_style']);
		echo '<div style="background-color: rgb(255, 251, 204);" id="message" class="updated fade below-h2"><p>Setting saved.</p></div>';
	}
	elseif($_POST['cmd']=="drop" && $_POST['delete']=="yes") {
		global $wpdb;
		$sql = "DROP TABLE ".$wpdb->prefix."pl_projects";
		$wpdb->query($sql);
		echo '<div style="background-color: rgb(255, 251, 204);" id="message" class="updated fade below-h2"><p>Table has been deleted.</p></div>';
		delete_option('pl_db_version');
	}
?>
	<h2 id="donwload_name">Config</h2>
<table align="center" border="0" cellpadding="3" width="100%">
<form action="" method="post">
<input type="hidden" name="cmd" value="save" />
		<tbody>
    		<tr>
      			<th scope="row" align="right" width="100">Use Lightbox for preview image?</th>
      			<td width="500"><select name="pl_lightbox"><option <?php if(get_option("pl_lightbox")) echo 'selected="selected"'; ?> value="yes">yes</option><option <?php if(!get_option("pl_lightbox")) echo 'selected="selected"'; ?> value="no">no</option></select></td>
        	</tr>
            <tr>
      			<th scope="row" align="right" width="100">Style</th>
      			<td width="500"><select name="pl_style"><option value="black">black</option></select></td>
        	</tr>
            <tr>
      			<th scope="row" align="right" width="100"></th>
      			<td width="500"><input type="submit" /></td>
        	</tr>
		</tbody>
</form>
</table>
<h2 id="donwload_name">Deinstall</h2>
<table align="center" border="0" cellpadding="3" width="100%">
<form action="" method="post">
<input type="hidden" name="cmd" value="drop" />
		<tbody>
    		<tr>
      			<th scope="row" align="right" width="100">Delete table</th>
      			<td width="500"><select name="delete"><option value="no">no</option><option value="yes">yes</option></select></td>
        	</tr>
            <tr>
      			<th scope="row" align="right" width="100"></th>
      			<td width="500"><input type="submit" /></td>
        	</tr>
		</tbody>
</form>
</table>
</div>