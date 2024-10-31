<table class="widefat post" cellspacing="0">
	<thead>
	<tr>
	<th scope="col" id="title" class="manage-column column-title" style="">Name</th>
	<th scope="col" id="author" class="manage-column column-author" style="">Short Info</th>
	<th scope="col" id="categories" class="manage-column column-categories" style="">Image</th>
    <th scope="col" id="categories" class="manage-column column-categories" style="">Platform</th>
    <th scope="col" id="categories" class="manage-column column-categories" style="">Language</th>
	</tr>
	</thead>

	<tfoot>
	<tr>
	<th scope="col" id="title" class="manage-column column-title" style="">Name</th>
	<th scope="col" id="author" class="manage-column column-author" style="">Short Info</th>
	<th scope="col" id="categories" class="manage-column column-categories" style="">Image</th>
    <th scope="col" id="categories" class="manage-column column-categories" style="">Platform</th>
    <th scope="col" id="categories" class="manage-column column-categories" style="">Language</th>
    </tr>
	</tfoot>
    
    <tbody>
    <?php 
	$rows = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."pl_projects", ARRAY_A);
	if(is_array($rows)) {
		foreach($rows as $row) {
			$link = $_SERVER['REQUEST_URI'];
			$link = str_replace("pl-main.php","pl-add.php",$link);
			$link .= "?id=".$row['id'];
				echo('
				<form action="'.str_replace("pl-main.php","pl-add.php",$_SERVER['REQUEST_URI']).'" method="post" name="form_'.$row['id'].'">
				<input type="hidden" name="cmd" value="edit">
				<input type="hidden" name="id" value="'.$row['id'].'">
				<tr id="post-'.$row['id'].'" class="alternate author-self status-publish iedit" valign="top">
					<td class="post-title column-title"><strong><a class="row-title" href="#" onClick="form_'.$row['id'].'.submit(); return false;">'.$row['name'].'</a></strong>
					<div class="row-actions"><span class="edit"><a href="#" onClick="form_'.$row['id'].'.submit(); return false;">Edit</a></span></form>
					<form action="'.str_replace("pl-main.php","pl-add.php",$_SERVER['REQUEST_URI']).'" method="post" name="form_'.$row['id'].'_delete">
					<input type="hidden" name="cmd" value="delete">
					<input type="hidden" name="id" value="'.$row['id'].'">
					<span class="delete"><a class="submitdelete" title="Delete this project" href="#" onClick="form_'.$row['id'].'_delete.submit(); return false;">Delete</a></span></div>
					</form>
					</td>
					<td class="author column-author">'.$row['info_short'].'</td>
					<td class="categories column-categories"><img src="'.get_bloginfo('siteurl')."/wp-content/plugins/projectlist/images/upload/".$row['pic'].'" height="60px"></td>
					<td class="categories column-categories">'.$row['platform'].'</td>
					<td class="categories column-categories">'.$row['version'].'</td>
				</tr>
				');
		}
	} else {
		echo '<tr id="post-'.$row['id'].'" class="alternate author-self status-publish iedit" valign="top"><td class="author column-author"><h3>No Projects</h3></td></tr>';
	}
	?>
	</tbody>
</table>

