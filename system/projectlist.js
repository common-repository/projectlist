/*
Javascript-functions for ProjectList plugin
by OV3RK!LL
overkill@cp-g.net
*/
function pl_typechange(value) {
	if(value=="page") {
		document.getElementById('pl_info_page_field').style.display='';
		document.getElementById('pl_info_link_field').style.display='none';	
	} else {
		document.getElementById('pl_info_page_field').style.display='none';
		document.getElementById('pl_info_link_field').style.display='';
	}
}

function pl_preview_platform(value) {
		document.getElementById('preview_platform').src=value;
}

function pl_preview_lang(value) {
		document.getElementById('preview_lang').src=value;
}

function filter(platform,state){
	if(state){
		jQuery("div."+platform).show();
	}else{
		jQuery("div."+platform).hide();	
	}
	//alert(jQuery("div."+platform).length);
}