<?php
// WP plugin header
/*
Plugin Name: Image Photoroll Creator for Photographers
Description: Plugin adds aditional buttons to media upload module: 1. Add alt text to all uploaded photos, 2. Insert all photos into post at cursor position with clear markup.
Version: 1.5
Author: Marcin Bobowski
Author URI: http://citystudio.pl
License: GPL
*/

function cs_ipcf_top_addon() {
	$errors = array();
	if ( !empty($_POST) ) {
		$return = media_upload_form_handler();
		if ( is_string($return) )
			return $return;
		if ( is_array($return) )
			$errors = $return;
	}
	return wp_iframe( 'cs_ipcf_form_addon', $errors );
}
function cs_ipcf_form_addon($errors) {
?>
<script type="text/javascript">
<!--
function set_alt_text(){
	jQuery('.ml-set-alt-img-text #set-alt-img-text').bind('click', function(){	
		var $alt_text = jQuery('.ml-set-alt-img-text #alt-img-text').attr('value');
		jQuery('#media-items .media-item').each(function(){
			var $this = jQuery(this);
			var $image_alt = jQuery($this).find('.image_alt input');
			jQuery($image_alt).attr('value', $alt_text);
			
		});
		jQuery('#gallery-form').submit();
		return false;
	})
}
function set_order(){
	jQuery('.ml-set-alt-img-text #set-order-of-img').bind('click', function(){	
		var i = jQuery('input.menu_order_input').size();
		jQuery('input.menu_order_input').each(function(){
			jQuery(this).attr('value', i);
			i--;
		})
		jQuery('#gallery-form').submit();
		return false;
	})
}

jQuery(document).ready(function() {
	set_alt_text();
	set_order();
})
-->
</script>

<form>
<p class="ml-set-alt-img-text">
<input type="text" class="text" id="alt-img-text" name="alt-img-text" calue="" />
<input type="submit" class="button savebutton" name="save" id="set-alt-img-text" value="Add alternative text" />
<input type="submit" class="button savebutton" name="set-order-of-img" id="set-order-of-img" value="Reverse the order" />
</p>
</form>

<?php
}

add_filter('media_upload_gallery', 'cs_ipcf_top_addon');

function cs_ipcf_addall_init() {
	global $pagenow;
	if ( $pagenow == 'media-upload.php' ) {
		wp_enqueue_script( 'cs_ipcfp', plugins_url( '/js/cs_ipcfp.js', __FILE__ ), array('jquery'), '1.2', false );	   
	}
}

add_action( 'init', 'cs_ipcf_addall_init' );

function wpVersion() {
	$version = str_pad( intval( str_replace( '.', '', $GLOBALS['wp_version'] ) ), 3, '0' );
	return ( $version == '000' ) ? 999 : intval( $version );
}

function cs_ipcf_patcher() {
	if ( is_admin() && $GLOBALS['pagenow'] == 'media-upload.php' && isset( $_GET['tab'] ) && $_GET['tab'] == 'gallery' ) {
		add_action( 'admin_head', create_function( '', 'echo "<script>
			var removeBars = function(){ jQuery(document).ready(function(\$){\$(\'div.bar\').remove();}); };
			removeBars();
			setTimeout( \'removeBars\', 500 );
			setTimeout( \'removeBars\', 1500 );
		</script>";' ) );
	}
}

if ( wpVersion() >= 330 ) {
	add_action( 'after_setup_theme', 'cs_ipcf_patcher' );
}

?>