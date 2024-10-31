<?php
/*
Plugin Name: ProjectList
Plugin URI: http://overkill.cp-g.net
Description: List your projects. Let your users see what you are working on.
Version: 0.3.0
Author: OV3RK!LL
Author URI: http://overkill.cp-g.net/landing.php?ProjectList

TODO:
-fix css bugs
-change changelog to version & add to preview
*/
### Global Information
$PL_VERSION = "0.1";
### Init Downloads Manager Admin Menu
function ProjectList_Menu() {
  add_menu_page(__('Project List', 'projectlist'), __('Project List', 'projectlist'),6,dirname(__FILE__).'/pages/pl-main.php',false,get_bloginfo('siteurl').'/wp-content/plugins/projectlist/images/icon.png');
  if (function_exists('add_submenu_page')) {
    add_submenu_page(dirname(__FILE__).'/pages/pl-main.php', __('Add Project', 'projectlist'), __('Add Project', 'projectlist'), 6, dirname(__FILE__).'/pages/pl-add.php');
    add_submenu_page(dirname(__FILE__).'/pages/pl-main.php', __('Settings', 'projectlist'), __('Settings', 'projectlist'), 6, dirname(__FILE__).'/pages/pl-config.php');
  }
}

function ProjectList_Parse($content) {
	$content = preg_replace_callback("/<!--projectlist(.*?)?-->/", "ProjectList_Render", $content);
	return $content;
}

function ProjectList_Install() {
	global $wpdb;
	global $PL_VERSION;
	if(get_option('pl_db_version')=="") {
		$sql = "DROP TABLE IF EXISTS `".$wpdb->prefix."pl_projects`; CREATE TABLE `".$wpdb->prefix."pl_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `info_short` text NOT NULL,
  `info_long` text NOT NULL,
  `info_type` tinytext NOT NULL,
  `pic` text NOT NULL,
  `platform` text NOT NULL,
  `lang` text NOT NULL,
  `version` text NOT NULL,
  `link` text NOT NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT=1;";
		add_option("pl_db_version", $PL_VERSION);
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	add_option("pl_style", "black");
	add_option("pl_lightbox",false);
}

function ProjectList_Render() {
	global $wpdb;
	$platforms = array();
	$output = "";
	$imgpath = get_bloginfo('siteurl')."/wp-content/plugins/projectlist/images/";
	$rows = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."pl_projects", ARRAY_A);
	if(is_array($rows)) {
		foreach($rows as $row) {		
		if(array_search($row['platform'],$platforms)===false){
			$platforms[]=$row['platform'];
		}
			$output .= '
			<div class="project '.strtolower($row['platform']).'">
				<div class="title"><a href="'.$row['info_long'].'">'.$row['name'].'</a></div><p>';
			if(get_option("pl_lightbox"))
				$output.='<a href="'.$imgpath."upload/".$row['pic'].'" rel="lightbox"><img class="image" src="'.$imgpath."upload/".$row['pic'].'" alt=""></a>';
			else
				$output.='<img class="image" src="'.$imgpath."upload/".$row['pic'].'" alt="">';
			$output.='</p><div class="info">Platform: '.$row['platform'].' | Status: '.$row['version'].'</div><p>'.$row['info_short'].'</p>';
			$output .= '<div class="link"><a href="'.$row['info_long'].'">more information</a></div></div>';
		}
	}
	$head = "Platform: ";
	for($i=0;$i<count($platforms);$i++){
		$head .= '<input type="checkbox" checked="checked" value="'.strtolower($platforms[$i]).'" onchange="filter(this.value,this.checked);"/>'.$platforms[$i].'&nbsp;&nbsp;';
	}
	
	return $head.$output;
} 

function ProjectList_Init() {
	//
}

function ProjectList_Head() {
	echo '<link href="' . get_bloginfo('siteurl') . '/wp-content/plugins/projectlist/system/projectlist.css" rel="stylesheet" type="text/css" />' . "\n";	
	echo '<script type="text/javascript" language="javascript" src="' . get_bloginfo('siteurl') . '/wp-includes/js/jquery/jquery.js"></script>' . "\n";
	echo '<script type="text/javascript" language="javascript" src="' . get_bloginfo('siteurl') . '/wp-includes/js/jquery/interface.js"></script>' . "\n";
	echo '<script type="text/javascript" language="javascript" src="' . get_bloginfo('siteurl') . '/wp-content/plugins/projectlist/system/projectlist.js"></script>' . "\n";

}

function ProjectList_Adminhead() {
	echo '<script type="text/javascript" language="javascript" src="' . get_bloginfo('siteurl') . '/wp-content/plugins/projectlist/system/projectlist.js"></script>';
	echo '<script type="text/javascript" src="'.get_bloginfo('siteurl').'/wp-includes/js/tinymce/tiny_mce.js"></script>';
	echo '<script type="text/javascript">
			<!--
			tinyMCE.init({
			theme : "advanced",
			mode : "exact",
			elements : "editorContent",
			width : "565",
			height : "200"
			});
			-->
			</script>';
}

add_filter('the_content', 'ProjectList_Parse');

add_action('activate_projectlist', 'ProjectList_Install');
add_action('admin_menu', 'ProjectList_Menu');
add_action('init', 'ProjectList_Init');
add_action('wp_head', 'ProjectList_Head');
add_action('admin_head','ProjectList_Adminhead');


register_activation_hook(__FILE__,'ProjectList_Install');

?>