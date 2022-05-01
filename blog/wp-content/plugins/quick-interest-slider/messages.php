<?php
qis_messages();

// Builds and manages the applications table 
function qis_messages() {

	$content=$current=$all=$qis_edit=false;
	$selected = array();

	// Delete all applications
	if( isset( $_POST['qis_reset_message'])) {
		delete_option('qis_messages');
		qis_admin_notice(__('All applications have been deleted','quick-interest-slider').'.');
	}

	// Delete selected applications
	if( isset($_POST['qis_delete_selected'])) {
		$message = get_option('qis_messages');
		$count = count($message);
		for($i = 0; $i <= $count; $i++) {
			if ($_POST[$i] == 'checked') {
				unset($message[$i]);
			}
		}
		$message = array_values($message);
		update_option('qis_messages', $message );
		qis_admin_notice(__('Selected applications have been deleted','quick-interest-slider').'.');
	}

	// Approve Selected Applications
	if( isset($_POST['qis_approve_selected'])) {
		$message = get_option('qis_messages');
		foreach ($message as $key => $value ) {
			if ($_POST[$key] == 'checked') {
				$message[$key]['confirmed'] = true;
			}
		}
		update_option('qis_messages', $message );
		qis_admin_notice(__('Selected applications have been approved','quick-interest-slider').'.');
	}

	// Send applications as email
	if( isset($_POST['qis_emaillist'])) {
		$fromemail = get_bloginfo('admin_email');
		$title = get_bloginfo('name');
		$message = get_option('qis_messages');
		$content = qis_build_registration_table ($message,'report',null,null);
		$sendtoemail = $_POST['sendtoemail'];
		$headers = "From: ".title." <".$fromemail.">\r\n"."Content-Type: text/html; charset=\"utf-8\"\r\n";	
		wp_mail($sendtoemail, 'Loan Applications', $content, $headers);
		qis_admin_notice(__('Application list has been sent to','quick-interest-slider').' '.$sendtoemail.'.');
	}

	// Update edited applications
	if( isset($_POST['qis_update'])) {
		$arr = array('yourname','youremail','yourtelephone','yourmessage','yourchecks','youraddress','yourdropdown','yourdropdown2','yourradio','loan-amount','loan-period','progress');
		$message = get_option('qis_messages');
		
		// Loop through the $_POST['message'] array
		foreach ($_POST['message'] as $id => $row) {
			// Loop through the row thats contained in the message array entry
			foreach ($row as $k => $v) {
				// Do the same value assignment you make in your code
				$message[$id][$k] = $v;
			}
		}
		update_option('qis_messages',$message);
		qis_admin_notice(__('Applications have been updated','quick-interest-slider'));
	}
	
	// Edit all applications
	if( isset($_POST['qis_edit'])) {
		$qis_edit = 'all';
	}
	
	// Edit selected applications
	if( isset($_POST['qis_edit_selected']) ) {
		$qis_edit = 'selected';
		$selected = $_POST;
	}

	$message = get_option('qis_messages');
	
	$current_user = wp_get_current_user();
	if ( isset($sendtoemail) && !$sendtoemail) {
		$sendtoemail = $current_user->user_email;
	} else {
		$sendtoemail = '';
	}
	
	if(!is_array($message)) $message = array();
	$dashboard = '<div class="wrap">
	<h1>'.__('Loan Applications','quick-interest-slider').'</h1>
	<div id="qis-widget">
	<form method="post" id="qis_download_form" action="">';
	
	$content = qis_build_registration_table ($message,'',$qis_edit,$selected);
	if ($content) {
		$dashboard .= $content;
		$dashboard .='<p><input type="submit" name="qis_reset_message" class="button-secondary" value="'.__('Delete all applications','quick-interest-slider').'" onclick="return window.confirm( \'Are you sure you want to delete all the applications?\' );"/>
		<input type="submit" name="qis_delete_selected" class="button-secondary" value="'.__('Delete Selected','quick-interest-slider').'" onclick="return window.confirm( \'Are you sure you want to delete the selected applications?\' );"/> 
		<input type="submit" name="qis_approve_selected" class="button-secondary" value="'.__('Approve Selected','quick-interest-slider').'" onclick="return window.confirm( \'Are you sure you want to approve the selected applications?\' );"/> ';
		if ($qis_edit) $dashboard .= '<input type="submit" name="qis_update" class="button-primary" value="'.__('Update Applications','quick-interest-slider').'" /> ';
		else $dashboard .= '<input type="submit" name="qis_edit" class="button-secondary" value="'.__('Edit Applications','quick-interest-slider').'" /> <input type="submit" name="qis_edit_selected" class="button-secondary" value="'.__('Edit Selected','quick-interest-slider').'" /> ';
		$dashboard .= '<input type="submit" name="qis_cancel" class="button-secondary" value="'.__('Cancel','quick-interest-slider').'" /></p>
		<p>'.__('Send applications to this email address','quick-interest-slider').': <input type="text" name="sendtoemail" value="'.$sendtoemail.'">&nbsp;
		<input type="submit" name="qis_emaillist" class="button-primary" value="'.__('Email List','quick-interest-slider').'" /></p>
		</form>';
	} else {
		$dashboard .= '<p>'.__('There are no applications','quick-interest-slider').'</p>';
	}
	$dashboard .= '</div></div>';		
	echo $dashboard;
}

function qis_message_thumbs($value) {
	$content = '<td>';
	if ($value['attachment'] ) { 
		$mime = qis_attach_content_type($value['attachment']);
		$filename = $value['attachment'];
		$content .= '<a href="'.$value['attachment'].'"><img style="width:auto;height:30px;" ';
		if (strpos($value['attachment'],'.pdf')) {
			$content .= 'src="'.plugin_dir_url( __FILE__ ).'img/pdf.png"';
		} elseif (strpos($value['attachment'],'.xls')) {
			$content .= 'src="'.plugin_dir_url( __FILE__ ).'img/xls.png"';
		} elseif (strpos($value['attachment'],'.doc')) {
			$content .= 'src="'.plugin_dir_url( __FILE__ ).'img/doc.png"';
		} elseif(strstr($mime, "image/")) {
			$content .= 'src="'.$value['attachment'].'"';
		} else {
			$content .= 'src="'.plugin_dir_url( __FILE__ ).'img/files.png"';
		}
		$content .= ' alt="'.$filename.'" title="'.$filename.'" /></a>';
		} 
	$content .= '</td>';
	return $content;
}

function qis_attach_content_type($filename) {
	$mime_types = array(
		'txt' => 'text/plain',
		'htm' => 'text/html',
		'html' => 'text/html',
		'php' => 'text/html',
		'css' => 'text/css',
		'js' => 'application/javascript',
		'json' => 'application/json',
		'xml' => 'application/xml',
		'swf' => 'application/x-shockwave-flash',
		'flv' => 'video/x-flv',
		// images
		'png' => 'image/png',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'gif' => 'image/gif',
		'bmp' => 'image/bmp',
		'ico' => 'image/vnd.microsoft.icon',
		'tiff' => 'image/tiff',
		'tif' => 'image/tiff',
		'svg' => 'image/svg+xml',
		'svgz' => 'image/svg+xml',
		// archives
		'zip' => 'application/zip',
		'rar' => 'application/x-rar-compressed',
		'exe' => 'application/x-msdownload',
		'msi' => 'application/x-msdownload',
		'cab' => 'application/vnd.ms-cab-compressed',
		// audio/video
		'mp3' => 'audio/mpeg',
		'qt' => 'video/quicktime',
		'mov' => 'video/quicktime',
		// adobe
		'pdf' => 'application/pdf',
		'psd' => 'image/vnd.adobe.photoshop',
		'ai' => 'application/postscript',
		'eps' => 'application/postscript',
		'ps' => 'application/postscript',
		// ms office
		'doc' => 'application/msword',
		'rtf' => 'application/rtf',
		'xls' => 'application/vnd.ms-excel',
		'ppt' => 'application/vnd.ms-powerpoint',
		// open office
		'odt' => 'application/vnd.oasis.opendocument.text',
		'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
	);
	$ext = strtolower(array_pop(explode('.',$filename)));
	if (array_key_exists($ext, $mime_types)) {
		return $mime_types[$ext];
	}
	elseif (function_exists('finfo_open')) {
		$finfo = finfo_open(FILEINFO_MIME);
		$mimetype = finfo_file($finfo, $filename);
		finfo_close($finfo);
		return $mimetype;
	}
	else {
		return 'application/octet-stream';
	}
}