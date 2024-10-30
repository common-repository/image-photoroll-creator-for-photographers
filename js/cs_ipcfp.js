/**
Plugin Name: Image Photoroll Creator for Photographers
Author: Marcin Bobowski
Author URI: http://citystudio.pl
License: GPL
**/


jQuery(document).ready(function(){
	if ( jQuery('#tab-gallery a #attachments-count').length ) {
		setTimeout('media_uploader_add_buttons()',700);
	}
});

/* insert custom media buttons into media uploader gallery module iframe */
function media_uploader_add_buttons() {
	var img_btn = ' <input value="Insert All Images" id="insert_all_images" onclick="javascript:insert_all(jQuery(this));" type="button" class="button savebutton" />';
	if ( jQuery('li#tab-gallery a.current').length ) {
		jQuery('.ml-submit input[name=save]').after(img_btn);
		jQuery('#insert_all_images').css('display', 'inline');
	}
}
/* send all uploaded images to editor */
function insert_all() {
	if (jQuery('div.bar').length) { // still crunching
		jQuery('#insert_all_images').addClass('clicked').css('color', 'blue').val('waiting for crunching to complete...');
		setTimeout('insert_all()', 800);
		
	} else {
		var html = '<div class="blogimages">';
		html += collect_images(this);
		html += '</div>';
		send_to_editor( '\n'+html);
	}
}

/* gets width/height html out of WordPress button label */
function extract_image_size_html( text ) {
	text = text.substr(1,text.length); // get rid of non-standard first parens
	var parts = text.split(text.match(/[^0-9\(\)]+/));
	return 'width="'+ parts[0].replace(/[^0-9]/g,'')+'" height="'+parts[1].replace(/[^0-9]/g,'')+'"';
}


/* subroutine for sending data to WordPress editor */
function send_to_editor( string ) {
	var win = window.opener ? window.opener : window.dialogArguments;
	if (!win) win = top;
	win.send_to_editor( string );
}

/* collect data of all images in gallery */
function collect_images() {
	var result = '';
	jQuery('.media-item').each(function(){
		var src = jQuery('button.urlfile', this).attr('title');
		// here is the change for WordPress 3.4+
		if ( src == undefined ) {
			src = jQuery('button.urlfile', this).attr('data-link-url');
		}
		var size = extract_image_size_html( jQuery('.image-size-item:last label.help',this).text() );
		var title = jQuery('tr.post_title td input', this).val();
		var alt = jQuery('tr.image_alt td input', this).val();
		result += '<img '+size+' src="'+src+'" class="insert-all"  alt="'+alt+'" title="'+title+'" />';
	});
	return result;
}


