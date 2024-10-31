<?php
	$viewform = true;
	if($_REQUEST['cmd']=="save") {
		global $wpdb;
		
		$viewform = false;
		$pl_data = array();
		$pl_data['name'] = $wpdb->escape($_POST['pl_name']);
		$pl_data['info_short'] = $wpdb->escape($_POST['pl_info_short']);
		$pl_data['info_type'] = $wpdb->escape($_POST['pl_info_type']);		
		switch($pl_data['info_type'])
		{
			case "page":
				$pl_data['info_long'] = $wpdb->escape($_POST['pl_info_page']);
				break;
			case "text":
				$pl_data['info_long'] = $wpdb->escape($_POST['pl_info_text']);
				break;
			case "link":
				$tmplink = $wpdb->escape($_POST['pl_info_link']);
				if(stristr($tmplink,"http://")==true)
					$pl_data['info_long'] = $tmplink;
				else
					$pl_data['info_long'] = "http://".$tmplink;
				break;
		}
		if(isset($_FILES["pl_image"]) && is_uploaded_file($_FILES["pl_image"]["tmp_name"])) {
			$img_name_split = explode(".",$_FILES["pl_image"]["name"]);
			$img_ext = $img_name_split[count($img_name_split)-1];
			$img_name = substr(md5(rand(11111111,99999999)*time()),0,20).'.'.$img_ext;
			$pl_data['pic'] = $img_name;
			$img_move_path = "../wp-content/plugins/projectlist/images/upload/".$img_name;
			move_uploaded_file($_FILES["pl_image"]["tmp_name"],$img_move_path);
			echo '<div style="background-color: rgb(255, 251, 204);" id="message" class="updated fade below-h2"><p>Image has been uploaded</p></div>';
		}
		$pl_data['platform'] = $wpdb->escape($_POST['pl_platform']);		
		$pl_data['lang'] = $wpdb->escape($_POST['pl_lang']);		
		$pl_data['version'] = $wpdb->escape($_POST['pl_version']);			
		if(""==$_POST['id']) //add new data
			$query = $wpdb->insert($wpdb->prefix."pl_projects",$pl_data);
		else //update exsiting data
			$query = $wpdb->update($wpdb->prefix."pl_projects",$pl_data,array("id"=>$_POST['id']));

		if($query)	
			echo '<div style="background-color: rgb(255, 251, 204);" id="message" class="updated fade below-h2"><p>Project has been modified</p></div>';
		else
			echo '<div style="background-color: rgb(255, 0, 0);" id="message" class="updated fade below-h2"><p>Project hasn\'t been modified</p></div>';
		
	}
	if($_REQUEST['cmd']=="edit") {
		global $wpdb;
		$wpdb->show_errors();
		$form = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."pl_projects WHERE `id` = ".$_POST['id'], ARRAY_A);
		$viewform = true;
	}
	if($_REQUEST['cmd']=="delete") {
		global $wpdb;
		$row = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."pl_projects WHERE `id` = ".$_POST['id'], ARRAY_A);
		$name = $row['name'];
		unlink("../wp-content/plugins/projectlist/images/upload/".$row['pic']);
		$query = $wpdb->query("DELETE FROM ".$wpdb->prefix."pl_projects WHERE `id` = ".$_POST['id']);
		if($query)	
			echo '<div style="background-color: rgb(255, 251, 204);" id="message" class="updated fade below-h2"><p>Project "'.$name.'" has been deleted</p></div>';
		else
			echo '<div style="background-color: rgb(255, 0, 0);" id="error" class="updated fade below-h2"><p>Project "'.$name.'" hasn\'t been deleted</p></div>';
		$viewform=false;
	}
	if($viewform==true)	{
?>

<div class="wrap">
	<h2 id="donwload_name"><?php if($form['id']) echo 'Edit'; else echo 'Add'; ?> a Project</h2>
	<form name="dm" method="POST" action="" enctype="multipart/form-data">
    <input type="hidden" name="cmd" value="save" />
    <input type="hidden" name="id" value="<?php echo $form['id']; ?>" />
	<table align="center" border="0" cellpadding="3" width="100%">
		<tbody>
    		<tr>
      			<th scope="row" align="right" width="100">Project Name</th>
      			<td width="500"><input name="pl_name" id="pl_name" size="40" type="text" value="<?php echo $form['name']; ?>"></td>
        	</tr>
            <tr>
      			<th scope="row" align="right" width="100">Short Info</th>
      			<td width="500"><textarea name="pl_info_short" cols="40" rows="6" id="pl_info_short"><?php echo $form['info_short']; ?></textarea></td>
        	</tr>
            <tr>
      			<th scope="row" align="right" width="100">Long Info Type</th>
      			<td width="500"><select name="pl_info_type" onchange="pl_typechange(this.value)">
                					<option value="page" <? if("page"==$form['info_type']) echo 'selected="selected"'; ?>>Page</option>
                                    <option value="link" <? if("link"==$form['info_type']) echo 'selected="selected"'; ?>>Link</option></select></td>
        	</tr>
            <tr id="pl_info_link_field" <? if("link"!=$form['info_type']) echo 'style="display:none"'; ?>>
      			<th scope="row" align="right" width="100">Info Link</th>
      			<td width="500"><input name="pl_info_link" id="pl_info_link" size="40" type="text" value="<? if("link"==$form['info_type']) echo $form['info_long']; ?>"></td>
        	</tr>
            <tr id="pl_info_page_field" <? if("page"!=$form['info_type'] && isset($_REQUEST['cmd'])) echo 'style="display:none"'; ?>>
      			<th scope="row" align="right" width="100">Info Page</th>
      			<td width="500"><select name="pl_info_page">
                                 <?php 
                                  $pages = get_pages(); 
                                  foreach ($pages as $pagg) {
                                    $option = '<option';
									if(get_page_link($pagg->ID)==$form['info_long'])
										$option.=' selected="selected"';
									$option .= ' value="'.get_page_link($pagg->ID).'"';
									$option .= '>';
                                    $option .= $pagg->post_title;
                                    $option .= '</option>';
                                    echo $option;
                                  }
                                 ?>
                                </select></td>
        	</tr>
            <tr>
      			<th scope="row" align="right" width="100">Image</th>
      			<td width="500"><input type="file" name="pl_image" /></td>
        	</tr>
            <tr>
      			<th scope="row" align="right" width="100">Platform</th>
      			<td width="500"><input name="pl_platform" id="pl_platform" size="40" type="text" value="<?php echo $form['platform']; ?>"></td>
        	</tr>
            <tr>
      			<th scope="row" align="right" width="100">Version</th>
      			<td width="500"><input name="pl_version" id="pl_version" size="40" type="text" value="<?php echo $form['version']; ?>"></td>
        	</tr>
            <tr>
      			<th scope="row" align="right" width="100"></th>
      			<td width="500"><input type="submit" /></td>
        	</tr>
    	</tbody>
	</table>
    </form>
</div>
<?php
}
?>

