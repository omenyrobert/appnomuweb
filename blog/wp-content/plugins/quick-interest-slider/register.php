<?php

define('QIS_LABEL_TYPE_NONE',0);
define('QIS_LABEL_TYPE_TINY',1);
define('QIS_LABEL_TYPE_BLUR',2);
define('QIS_LABEL_TYPE_LINE',3);

function qis_display_thankyou ($values) {
	
	$register   = qis_get_stored_register($values['formname']);
	$style	  = qis_get_stored_style();
	
	$content = '<div action="" class="qis_form '.$style['border'].'" method="POST">';
		
	if (!empty($register['replytitle'])) {
		$register['replytitle'] = '<h2>' . $register['replytitle'] . '</h2>';
		}
		if (!empty($register['replyblurb'])) {
			$register['replyblurb'] = '<p>' . $register['replyblurb'] . '</p>';
		}
		$content .= $register['replytitle'].$register['replyblurb']."</div>";
	
	$content .= '</div>';
	
	return $content;
}

function qis_display_form( $values, $errors, $registered ) {

	$content = '';
	$register = qis_get_stored_register($values['formname']);
	$style = qis_get_register_style();
	if (!$registered && count($errors) == 0) {
		if ($register['alwayson']) {
			$content = '<h2>' . $register['title'] . '</h2>';
		} else {
			$content = '<div class="toggle-qis"><a href="#">' . $register['title'] . '</a></div>
			<div class="apply" style="display: none;">';
		}
	}
	
	// Field override
	if (@$values['fields']){
		
		$usefields = array('','usename','useemail','usetelephone','usemessage','usecompany','useaddress','usenumber','usecaptcha','useaddinfo','usecopy','useterms','usechecks','usedropdown','usedropdown2');
		$fields = explode( ',',$values['fields']);
		
		foreach ($usefields as $key => $value) {
			$register[$value] = '';
		}			  
		foreach ($fields as $key => $value) {
			$register[$usefields[$value]] = 'checked';
		}
	}

	$register['reload'] = true;
	
	$content .= '<div class="qis-register">';
			
	// Displays message if application is complete
	
	if ($register['loginrequired'] && !is_user_logged_in()) {
		
		if ($register['loginlink']) {
			$redirect = qis_current_page_url();
			$content .= '<p><a href="'.wp_login_url( $redirect ).'">'.$register['loginblurb'].'</a></p>';
		} else {
			$content .= '<p>'.$register['loginblurb'].'</p>';
		}
		$content .= '</div></div>';
	
	} elseif ($registered) {
		
		// Application complete
		$content .= '<a id="qis_reload"></a>';
		
		if (!empty($register['replytitle'])) {
			$register['replytitle'] = '<h2>' . $register['replytitle'] . '</h2>';
		}
		if (!empty($register['replyblurb'])) {
			$register['replyblurb'] = '<p>' . $register['replyblurb'] . '</p>';
		}
		$content .= $register['replytitle'].$register['replyblurb'];
	
		
	} elseif (isset($errors['duplicate']) && $errors['duplicate']) {	
		
		// Pending application
		$content .= '<a id="qis_reload"></a>
		<h2 class="error">' . $register['errortitle'] . '</h2>
		<p class="qis-error-message">' . $register['errorpending'] . '</p>';
	
	} else {
		
		// Welcome and error messages
		if (!empty($register['blurb'])) {
			$register['blurb'] = '<p>' . $register['blurb'] . '</p>';
		}
		if (count($errors) > 0) {
			
			$content .= '<a id="qis_reload"></a>
			<h2 class="error">' . @$register['errortitle'] . '</h2>
			<p class="qis-error-message">' . @$register['error'] . '</p>';
 
			$arr = array('yourname','youremail','yourtelephone','yourmessage','youranswer','yourdropdown','yourdropdown2','youraddress');
			foreach ($arr as $item) if (@$errors[$item] == 'error') {
				$errors[$item] = ' class="error"';
			}
			if (isset($errors['youranswer']) && $errors['youranswer']) @$errors['youranswer'] = 'border-color:'.$style['error-font-colour'];
		} else {
			$errors = array('yourname'=>false,'youremail'=>false,'yourtelephone'=>false,'yourmessage'=>false,'yourcaptcha'=>false,'youraddress'=>false,'yourcompany'=>false,'yournumber'=>false,'yourdropdown'=>false,'yourdropdown2'=>false,'terms'=>false,'youranswer'=>false,'attach'=>false
			);
			$content .= @$register['blurb'];
		}
		
		// Learn what labels were selected
		$label = $register['labeltype'];
		switch ($label) {
			case 'none':
				$label = QIS_LABEL_TYPE_NONE;
			break;
			case 'tiny':
				$label = QIS_LABEL_TYPE_TINY;
			break;
			case 'hiding':
				$label = QIS_LABEL_TYPE_BLUR;
			break;
			case 'plain':
				$label = QIS_LABEL_TYPE_LINE;
			break;
		}
		
		// Check which fields should be displayed
		foreach (explode( ',',$register['sort']) as $name) {
			switch ( $name ) {
				case 'field1':
				if ($register['usename'])
					$content .= qis_nice_label('yourname','text',@$register['yourname'],$label,@$errors['yourname'],@$values['yourname']);
				break;
				
				case 'field2':
				if ($register['useemail']) 
					$content .= qis_nice_label('youremail','text',@$register['youremail'],$label,@$errors['youremail'],@$values['youremail']);
				break;
				
				case 'field3':
				if ($register['usetelephone']) 
					$content .= qis_nice_label('yourtelephone','tel',$register['yourtelephone'],$label,$errors['yourtelephone'],$values['yourtelephone']);
				break;
				
				case 'field4':
				if ($register['usemessage']) 
					$content .= qis_nice_label('yourmessage','textarea',$register['yourmessage'],$label,$errors['yourmessage'],$values['yourmessage']);
				break;
				
				case 'field5':
				if ($register['usecaptcha']) 
					$content .= '<span>'.$register['yourcaptcha'].' '.$values['thesum'].' = </span><input id="youranswer" name="youranswer" type="text" style="width:3em;'.$errors['youranswer'].'"  value="'.$values['youranswer'].'" onblur="if (this.value == \'\') {this.value = \''.$values['youranswer'].'\';}" onfocus="if (this.value == \''.$values['youranswer'].'\') {this.value = \'\';}" />
					<input type="hidden" name="answer" value="' . strip_tags($values['answer']) . '" />
					<input type="hidden" name="thesum" value="' . strip_tags($values['thesum']) . '" />';
				break;
				
				case 'field6':
				if ($register['usecopy']) {
					if ($register['copychecked']) $copychecked = 'checked';
					$content .= '<p><div class="qis_checkbox"><input type="checkbox" class="qis_check" name="qis-copy" id="qis-copy" value="checked" '.$values['qis-copy'].'><label for="qis-copy"></label></div> '.$register['copyblurb'].'</p>';
				}
				break;
				
				case 'field7':
				if ($register['useaddinfo'])
					$content .= '<p>'.$register['addinfo'].'</p>';
				break;
				
				case 'field8':
				if ($register['usechecks']) {
					$content .= '<div class="checklabel"><ul>';
					if ($register['checkboxeslabel']) $content .= '<li class="label">'.$register['checkboxeslabel'].'</li>';
					if (@$register['check3']) $content .= '<li class="check"><div class="qis_checkbox"><input type="checkbox" class="qis_check" name="check3" id="check3" value="checked" '.$values['check3'].'><label for="check3"></label></div>'.$register['check3'].'</li>';
					if (@$register['check2']) $content .= '<li class="check"><div class="qis_checkbox"><input type="checkbox" class="qis_check" name="check2" id="check2" value="checked" '.$values['check2'].'><label for="check2"></label></div>'.$register['check2'].'</li>';
					if (@$register['check1']) $content .= '<li class="check"><div class="qis_checkbox"><input type="checkbox" class="qis_check" name="check1" id="check1" value="checked" '.$values['check1'].'><label for="check1"></label></div>'.$register['check1'].'</li>';
					$content .= '</ul></div><div style="clear:both"></div>';
				}
				break;
				
				case 'field9':
				if ($register['useterms']) {
					$termslink = $errors['terms'] ? ' style="color:'.$style['error-font-colour'].';"' : '';
					$target = $register['termstarget'] ? ' target="_blank"' : '';
					$content .= '<p><div class="qis_checkbox"><input type="checkbox" class="qis_check" name="terms" id="terms" value="checked" '.(isset($values['terms'])? $values['terms'] : '').'><label for="terms"></label></div><a href="'.$register['termsurl'].'"'.$target.$termslink.'>'.$register['termslabel'].'</a></p>';
					}   
					break;
				
				case 'field10':
				if ($register['usedropdown']) {
					if ($register['dropdownlabelposition'] == 'paragraph') 
						$content .= '<p>'.$register['dropdownlabel'].'</p>';
					$content .= '<select name="yourdropdown"'.$errors['yourdropdown'].'>';
					if ($register['dropdownlabelposition'] == 'include')
						$content .= '<option value="">' . $register['dropdownlabel'] . '</option>'."\r\t";
					$arr = explode(",",$register['dropdownlist']);
					foreach ($arr as $item) {
						$selected = @$values['yourdropdown'] == $item ? 'selected' : '';
						$content .= '<option value="' .  $item . '" ' . $selected .'>' .  $item . '</option>'."\r\t";
					}
					$content .= '</select>';
				}
				break;
					
				case 'field11':
				if ($register['useradio']) {
					$radio = explode(',',$register['radiolist']);
					$content .= '<div class="registerradio"><ul>';
					$content .= '<li class="label">'.$register['radiolabel'].':</li>';
					$top = count($radio) - 1;
					
					for ($i=$top;$i>=0;$i--) {
						$checked = ($values['radiooption'] == $i) ? 'checked' : '';
						$content .= '<li><input type="radio" name="radiooption" value="'.$i.'" id="radio'.$i.'" '.$checked.'><label for="radio'.$i.'"><span></span>'.$radio[$i].'</label></li>';
					}
					$content .= '</ul></div><div style="clear:both"></div>';
				}
				break;
					
				case 'field12':
				if ($register['useconsent']) {
					$content .= '<p><div class="qis_checkbox"><input type="checkbox" class="qis_check" name="yourconsent" id="yourconsent" value="checked" '.$values['yourconsent'].'><label for="yourconsent"></label></div>'.$register['consentlabel'].'</p>';
				}
				break;
					
				case 'field13':
				if ($register['useattachment']) {
					$notice = $errors['attach'] ? $content .= $errors['attach'] : $register['attachmentlabel'];
					$content .= '<p>'.$notice.'<br>
					<input id="attachment" name="attachment" type="file" value="" /></p>';
				}
				break;
					
				case 'field14':
				if ($register['usecompany']) 
					$content .= qis_nice_label('yourcompany','text',$register['yourcompany'],$label,$errors['yourcompany'],@$values['yourcompany']);
				break;
					
				case 'field15':
				if ($register['usenumber']) 
					$content .= qis_nice_label('yournumber','text',$register['yournumber'],$label,$errors['yournumber'],@$values['yournumber']);
				break;
					
				case 'field16':
				if ($register['usedropdown2']) {
					if ($register['dropdown2labelposition'] == 'paragraph') 
						$content .= '<p>'.$register['dropdown2label'].'</p>';
					$content .= '<select name="yourdropdown2"'.$errors['yourdropdown2'].'>';
					if ($register['dropdown2labelposition'] == 'include')
						$content .= '<option value="">' . $register['dropdown2label'] . '</option>'."\r\t";
					$arr = explode(",",$register['dropdown2list']);
					foreach ($arr as $item) {
						$selected = $values['yourdropdown2'] == $item ? 'selected' : '';
						$content .= '<option value="' .  $item . '" ' . $selected .'>' .  $item . '</option>'."\r\t";
					}
					$content .= '</select>';
				}
				break;
				
				case 'field17':
				if ($register['useaddress']) 
					$content .= qis_nice_label('youraddress','text',$register['youraddress'],$label,$errors['youraddress'],@$values['youraddress']);
				break;
			}
		}
		
		$content .= '<input type ="hidden" name="formname" value="'.$values['formname'].'">
		<input type="hidden" name="anything" value="'. date('Y-m-d H:i:s').'">
		<div class="validator">Enter the word YES in the box: <input type="text" style="width:3em" name="validator" value=""></div>
		<input onClick="check();" type="submit" value="'.$register['qissubmit'].'" class="submit" name="qissubmit" />';
		if (!$registered && count($errors) == 0) {
			$content .= '';
		}
	}
	
	return $content;
}

// Verifies the application
function qis_verify_form(&$values, &$errors) {
	
	$register = qis_get_stored_register($values['formname']);
	
	if ($register['blockduplicates']) {
	
		$message = get_option('qis_messages');
	
		if(!empty($message)){
			foreach($message as $item) {
				if ($item['youremail'] == $values['youremail']) $errors['duplicate'] = 'error';
			}
		}
	}

	$apikey = get_option('qis_akismet');
	if ($apikey) {
		$blogurl = get_site_url();
		$akismet = new qis_akismet($blogurl ,$apikey);
		$akismet->setCommentAuthor($values['yourname']);
		$akismet->setCommentAuthorEmail($values['youremail']);
		$akismet->setCommentContent($values['yourmessage']);
		if($akismet->isCommentSpam()) die();
	}
	
	if ($register['useemail'] && !filter_var($values['youremail'], FILTER_VALIDATE_EMAIL))
		$errors['youremail'] = 'error';
	
	if ($register['usename']) {
		$values['yourname'] = filter_var($values['yourname'], FILTER_SANITIZE_STRING);
		if (empty($values['yourname']) || $values['yourname'] == $register['yourname'])
			$errors['yourname'] = 'error';
	}
	if ($register['useemail']) {
		$values['youremail'] = filter_var($values['youremail'], FILTER_SANITIZE_STRING);
		if (empty($values['youremail']) || $values['youremail'] == $register['youremail'])
			$errors['youremail'] = 'error';
	}
	if ($register['usetelephone']) {
		$values['yourtelephone'] = filter_var($values['yourtelephone'], FILTER_SANITIZE_STRING);
		if (empty($values['yourtelephone']) || $values['yourtelephone'] == $register['yourtelephone'])
			$errors['yourtelephone'] = 'error';
	}
	if ($register['useaddress']) {
		$values['youraddress'] = filter_var($values['youraddress'], FILTER_SANITIZE_STRING);
		if (empty($values['youraddress']) || $values['youraddress'] == $register['youraddress'])
			$errors['youraddress'] = 'error';
	 }
	if ($register['usemessage']) {   
		$values['yourmessage'] = filter_var($values['yourmessage'], FILTER_SANITIZE_STRING);
		if (empty($values['yourmessage']) || $values['yourmessage'] == $register['yourmessage']) 
			$errors['yourmessage'] = 'error';
	}
	if ($register['usedropdown']) {
		$values['yourdropdown'] = filter_var($values['yourdropdown'], FILTER_SANITIZE_STRING);
		if (empty($values['yourdropdown'])) 
			$errors['yourdropdown'] = 'error';
		if ($register['dropdownignorefirst']) {
			$d = explode(',',$register['dropdownlist']);
			if ($values['yourdropdown'] == $d[0]) $errors['yourdropdown'] = 'error';
		}
	}
	if ($register['usedropdown2']) {
		$values['yourdropdown2'] = filter_var($values['yourdropdown2'], FILTER_SANITIZE_STRING);
		if (empty($values['yourdropdown2'])) 
			$errors['yourdropdown2'] = 'error';
		if ($register['dropdown2ignorefirst']) {
			$d = explode(',',$register['dropdown2list']);
			if ($values['yourdropdown2'] == $d[0]) $errors['yourdropdown2'] = 'error';
		}
	}
	if ($register['usecompany']) {
		$values['yourcompany'] = filter_var($values['yourcompany'], FILTER_SANITIZE_STRING);
		if ((empty($values['yourcompany']) || $values['yourcompany'] == $register['yourcompany']))
			$errors['yourcompany'] = 'error';
	}
	if ($register['usenumber']) {
		$values['yournumber'] = filter_var($values['yournumber'], FILTER_SANITIZE_STRING);
		if (empty($values['yournumber']) || $values['yournumber'] == $register['yournumber'])
			$errors['yournumber'] = 'error';
	 }
	if ($register['useterms']) {   
		if (empty($values['terms']))
			$errors['terms'] = 'error';
	}
	if ($register['usecaptcha']) {
		if (empty($values['youranswer']) || $values['youranswer'] <> $values['answer']) 
			$errors['youranswer'] = 'error';
		$values['youranswer'] = filter_var($values['youranswer'], FILTER_SANITIZE_STRING);
	}
	if ($register['useattachment']) {
		
		$tmp_name = $_FILES['attachment']['tmp_name'];
		$name = $_FILES['attachment']['name'];
		$size = $_FILES['attachment']['size'];
		if (file_exists($tmp_name)) {
			if ($size > $register['attach_size']) $errors['attach'] = $register['attach_error_size']; 
			$ext = strtolower(substr(strrchr($name,'.'),1));
			if (strpos($register['attach_type'],$ext) === false) $errors['attach'] = $register['attach_error_type'];
		}
	}
	if ($values['validator']) die();
	if(!spawnSecure($_POST['anything'])) die();
	
	return (count($errors) == 0);	
}

// Check speed of completion
function spawnSecure($var) { 
	$spawn = trim(stripslashes($var)); $now = date('Y-m-d H:i:s'); $diff = strtotime($now) - strtotime($spawn);
	if($diff<=1.5) { return false; } else { return true; }
}

// Processes validated application
function qis_process_form($values) {
	global $post;
	$content='';
	$register = qis_get_stored_register($values['formname']);

	$formnumber = $values['formname'];
	$settings = qis_get_stored_settings($formnumber);
	
	$auto = qis_get_stored_autoresponder($formnumber);
	$qis_messages = get_option('qis_messages');
	$application = qis_get_stored_application_messages($formnumber);
	if(!is_array($qis_messages)) $qis_messages = array();
	
	$ip=$_SERVER['REMOTE_ADDR'];
	$url = $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	$page = get_the_title();
	if (empty($page)) $page = 'Unknown Page';
	
	$period = $values['loan-period'] == 1 ? $settings['singleperiodlabel'] : $settings['periodlabel'];
	if (!$period) $period = $settings['period'];
	
	$values['loan-amount'] = $settings['currency'].$values['loan-amount'];
	$values['downpayment-amount'] = $settings['currency'].$values['downpayment-amount'];
	$values['loan-period'] = $values['loan-period'].' '.$period; 
	$values['loan-interest'] = $values['loan-interest'].'%'; 
	
	$radio = explode(',',$register['radiolist']);
	
	$values['yourradio'] = $radio[$values['radiooption']];
	
	$reference = qis_get_stored_reference();
	$reference['referencenumber'] = sprintf('%06d', $reference['referencenumber']);
	$values['reference'] = $reference['referenceprefix'].$reference['referencenumber'];
	$reference['referencenumber']++;
	update_option('qis_reference',$reference);
	
	$track = get_option('qis_track');
	if ($track['enabletracking']) { 
		$track['completed']++;
		update_option('qis_track',$track);
	}
	
	for ($i=1;$i<=3;$i++) {
		if (isset($values['check'.$i])) $checks .= $register['check'.$i] . '<br>';
	}

	if ($checks) $values['yourchecks'] .= substr($checks, 0, -4);
	
	$values['sentdate'] = date_i18n('d M Y');
	$values['timestamp'] = time();
	
	if ($auto['noconfirmation']) $values['confirmed'] = true;

	$attachments = array();
	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
	}
	add_filter( 'upload_dir', 'qis_upload_dir' );
	
	$dir = (realpath(WP_CONTENT_DIR . '/uploads/qis/') ? '/uploads/qis/' : '/uploads/');
	$url = get_site_url();
	$filename = $_FILES['attachment']['tmp_name'];
	
	if (file_exists($filename)){
		$name = $values['reference'].'-'.$_FILES['attachment']['name'];
		$name = trim(preg_replace('/[^A-Za-z0-9. ]/', '', $name));
		$name = str_replace(' ','-',$name);
		$values['attachment'] = $url.'/wp-content'.$dir.$name;
		$_FILES['attachment']['name'] = $name;
		$uploadedfile = $_FILES['attachment'];
		$upload_overrides = array( 'test_form' => false );
		$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
		array_push($attachments , WP_CONTENT_DIR .$dir.$name);
		$gotlinks = true;
	}
	
	remove_filter( 'upload_dir', 'qis_upload_dir' );

		if ($register['storedata']) {
		$newmessage = array();
		$arr = array(
			'reference',
			'yourname',
			'youremail',
			'yourtelephone',
			'yourmessage',
			'yourchecks',
			'youraddress',
			'yourradio',
			'yourdropdown',
			'yourdropdown2',
			'yourconsent',
			'yourcompany',
			'youraddress',
			'yournumber',
			'loan-amount',
			'loan-period',
			'loan-interest',
			'downpayment-amount',
			'confirmed',
			'formname',
			'attachment',
			'sentdate',
			'timestamp'
			
		);
	
		foreach ($arr as $item) {
			if (isset($values[$item]) && $values[$item] != $register[$item]) $newmessage[$item] = $values[$item];
		}
	
		$qis_messages[] = $newmessage;
		update_option('qis_messages',$qis_messages);
	}
	
	if (!$auto['notification'] || $auto['noconfirmation']) {
		qis_send_notification ($values,$attachments,$register);
	}
	
	if (($auto['enable'] || $values['qis-copy']) && !$application['enable']) {
		qis_send_confirmation ($auto,$values,$content,$register);
	}
	
	if ($register['qis_redirect_url']) {
		$name = $register['qis_redirect_name'] ? '?yourname='.$values['yourname'].'&youremail='.$values['youremail'] : '';
		$location = $register['qis_redirect_url'].$name;
		echo "<meta http-equiv='refresh' content='0;url=$location' />";
		exit;
	}
	
	return $values;
}

// Send Notification
function qis_send_notification ($values,$attachments,$register) {
	
	global $post;
	$ip=$_SERVER['REMOTE_ADDR'];
	$url = $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	$page = get_the_title();
	if (empty($page)) $page = 'Unknown Page';
	
	$formnumber = $values['formname'];

	$settings = qis_get_stored_settings($formnumber);
	
	if (empty($register['sendemail'])) {
		$qis_email = get_bloginfo('admin_email');
	} else {
		$qis_email = $register['sendemail'];
	}
	
	$register['notificationsubject'] = str_replace('[name]', $values['yourname'], $register['notificationsubject']);
	$register['notificationsubject'] = str_replace('[date]', date_i18n("d M Y"), $register['notificationsubject']);
	
	if ($register['repaymentdata']) {
		
		if ($settings['ba'] == 'before') {
			$settings['cb'] = $settings['currency'];
			$settings['ca'] = ' ';
		} else {
			$settings['ca'] = $settings['currency'];
			$settings['cb'] = ' ';
		}
	
		$repayment = '<p><b>'.__('Repayment','quick-interest-slider').':</b> '.$settings['cb'].$values['repayment'].$settings['ca'].'</p>
		<p><b>'.__('Total to Pay','quick-interest-slider').':</b> '.$settings['cb'].$values['totalamount'].$settings['ca'].'</p>
		<p><b>'.__('Interest Rate','quick-interest-slider').':</b> '.$values['rate'].'%</p>';
	}
	
	$content = qis_build_message($values,$register).$repayment;
	
	if ($register['page']) $content .= "<p>".__('Message was sent from','quick-interest-slider').": <b>".$page."</b></p>";
	if ($register['ipaddress']) $content .= "<p>".__('Senders IP address','quick-interest-slider').": <b>".$ip."</b></p>";
	if ($register['url']) $content .= "<p>".__('URL','quick-interest-slider').": <b>".$url."</b></p>";
	
	if (!$register['nonotifications']) {
		$headers = "From: ".$values['yourname']." <".$values['youremail'].">\r\n"
	. "Content-Type: text/html; charset=\"utf-8\"\r\n";	
		$message = '<html>'.$content.'</html>';
		wp_mail($qis_email,$register['notificationsubject'],$message, $headers,$attachments);
	} 
}

// Sends autoresponder
function qis_send_confirmation ($auto,$values,$content,$register) {
	$date = date_i18n("d M Y");
	$subject = $auto['subject'];
	
	$formnumber = $values['formname'];
	
	$settings = qis_get_stored_settings($formnumber);
	
	if (empty($subject)) $subject = __('New Loan Application','quick-interest-slider');
	
	if (!$auto['fromemail']) $auto['fromemail'] = get_bloginfo('admin_email');
	if (!$auto['fromname']) $auto['fromname'] = get_bloginfo('name');

	if ($settings['ba'] == 'before') {
		$settings['cb'] = $settings['currency'];
		$settings['ca'] = ' ';
	} else {
		$settings['ca'] = $settings['currency'];
		$settings['cb'] = ' ';
	}
	
	$msg = $auto['message'];
	$msg = str_replace('[reference]', $values['reference'], $msg);
	$msg = str_replace('[name]', $values['yourname'], $msg);
	$msg = str_replace('[amount]', $values['loan-amount'], $msg);
	$msg = str_replace('[period]', $values['loan-period'], $msg);
	$msg = str_replace('[downpayment]', $values['downpayment-amount'], $msg);
	$msg = str_replace('[interest]', $values['loan-interest'], $msg);
	$msg = str_replace('[date]', $date, $msg);
	$msg = str_replace('[repayment]', $settings['cb'].$values['repayment'].$settings['ca'], $msg);
	$msg = str_replace('[totalamount]', $settings['cb'].$values['totalamount'].$settings['ca'], $msg);
	$msg = str_replace('[rate]', $values['rate'].'%', $msg);
	$msg = str_replace('[unsubscribe]', '<a href="'.$auto['subscribelink'].'?unsub='.$values['timestamp'].'">'.$auto['unsubscribeanchor'].'</a>', $msg);
	$msg = str_replace('[subscribe]', '<a href="'.$auto['subscribelink'].'?sub='.$values['timestamp'].'">'.$auto['subscribeanchor'].'</a>', $msg);
	
	$copy .= '<html>' . $msg;
	if ($auto['useregistrationdetails'] || $values['qis-copy']) {
		if($auto['registrationdetailsblurb']) {
			$copy .= '<h2>'.$auto['registrationdetailsblurb'].'</h2>';
		}
		$copy .= qis_build_message($values,$register);
	}
	
	$message = $copy.'</html>';
	$headers = "From: ".$auto['fromname']." <{$auto['fromemail']}>\r\n"
. "Content-Type: text/html; charset=\"utf-8\"\r\n";	
	wp_mail($values['youremail'], $subject, $message, $headers);
}

// Builds autoresponder message 
function qis_build_message($values,$register) {
	
	$formnumber = $values['formname'];
	$settings = qis_get_stored_settings($formnumber);

	$sort = explode( ',',$register['sort']);
	$checks = '';
	$content = '<p><b>'.__('Reference','quick-interest-slider').': </b>' . strip_tags($values['reference']) . '</p>
	<p><b>' . $register['borrowlabel'] . ': </b>' . strip_tags(stripslashes($values['loan-amount'])) . '</p>';
	if ($values['loan-period']) $content .= '<p><b>' . $register['forlabel'] . ': </b>' . strip_tags(stripslashes($values['loan-period'])) . '</p>';
	if ($values['downpayment-amount']) $content .= '<p><b>' . $register['downlabel'] . ': </b>' . strip_tags(stripslashes($values['downpayment-amount'])) . '</p>';
	if ($values['loan-interest']) $content .= '<p><b>' . $register['ratelabel'] . ': </b>' . strip_tags(stripslashes($values['loan-interest'])) . '</p>';

	foreach ($sort as $name) {
		switch ( $name ) {
			case 'field1':
				if ($register['usename']) $content .= '<p><b>' . $register['yourname'] . ': </b>' . strip_tags(stripslashes($values['yourname'])) . '</p>';
				break;
			case 'field2':
				if ($register['useemail']) $content .= '<p><b>' . $register['youremail'] . ': </b>' . strip_tags(stripslashes($values['youremail'])) . '</p>';
				break;
			case 'field3':
				if ($register['usetelephone']) $content .= '<p><b>' . $register['yourtelephone'] . ': </b>' . strip_tags(stripslashes($values['yourtelephone'])) . '</p>';
				break;
			case 'field4':
				if ($register['usemessage']) $content .= '<p><b>' . $register['yourmessage'] . ': </b>' . strip_tags(stripslashes($values['yourmessage'])) . '</p>';
				break;
			case 'field8':
				if ($register['usechecks']) {
					for ($i=1;$i<=3;$i++) {
						if ($values['check'.$i]) $checks .= ' '.$register['check'.$i] . ',';
					}
					if ($checks) $content .= '<p><b>' . $register['checkboxeslabel'] . '</b>'.rtrim($checks, ",").'</p>';
				}
				break;
			case 'field10':
				if ($register['usedropdown']) $content .= '<p><b>' . $register['dropdownlabel'] . ': </b>' . strip_tags(stripslashes($values['yourdropdown'])) . '</p>';
				break;
			case 'field11':
				if ($register['useradio']) $content .= '<p><b>' . $register['radiolabel'] . ': </b>' . strip_tags(stripslashes($values['yourradio'])) . '</p>';
				break;
			case 'field12':
				if ($register['useconsent'] && $values['yourconsent']) $content .= '<p>' . $register['consentlabel'] . '</p>';
				break;
			case 'field14':
				if ($register['usecompany']) $content .= '<p><b>' . $register['yourcompany'] . ': </b>' . strip_tags(stripslashes($values['yourcompany'])) . '</p>';
				break;
			case 'field15':
				if ($register['usenumber']) $content .= '<p><b>' . $register['yournumber'] . ': </b>' . strip_tags(stripslashes($values['yournumber'])) . '</p>';
				break;
			case 'field16':
				if ($register['usedropdown2']) $content .= '<p><b>' . $register['dropdown2label'] . ': </b>' . strip_tags(stripslashes($values['yourdropdown2'])) . '</p>';
				break;
			case 'field17':
				if ($register['useaddress']) $content .= '<p><b>' . $register['youraddress'] . ': </b>' . strip_tags(stripslashes($values['youraddress'])) . '</p>';
				break;
			
				
		}
	}
	return $content;
}

// Form labels
function qis_nice_label($id,$type,$label,$labelType,$error,$value) {
	
	$labelerror = $error ? ' style="color:red;"' : '';
	if ($type == 'tel') {
		switch ($labelType) {
			case 0:
				$v = (($value == $label)? '':$value);
				$returning = '<div class="profipc_nice_label profipc_label_none"'.$labelerror.'>';
				$returning .= '<input type="tel" id="'.$id.'" name="'.$id.'" value="'.$v.'"'.$error.' />';
			break;
			case 1:
				$v = (($value == $label)? '':$value);
				$returning = '<div class="profipc_nice_label profipc_label_tiny"'.$labelerror.'>';
				$returning .= '<label for="'.$id.'">'.$label.'</label>';
				$returning .= '<input type="tel" id="'.$id.'" name="'.$id.'" value="'.$v.'"'.$error.' />';
			break;
			case 2:
				$returning = '<div class="profipc_nice_label profipc_label_blur"'.$labelerror.'>';
				$returning .= '<input type="tel" id="'.$id.'" name="'.$id.'" value="'.$value.'"'.$error.' onblur="if (this.value == \'\') {this.value = \''.$value.'\';}" onfocus="if (this.value == \''.$value.'\') {this.value = \'\';}" />';
			break;
			case 3:
				$v = (($value == $label)? '':$value);
				$returning = '<div class="profipc_nice_label profipc_label_line"'.$labelerror.'>';
				$returning .= '<label for="'.$id.'">'.$label.'</label>';
				$returning .= '<input type="tel" id="'.$id.'" name="'.$id.'" value="'.$v.'"'.$error.' />';
			break;
		}
	} elseif ($type == 'email') {
		switch ($labelType) {
			case 0:
				$v = (($value == $label)? '':$value);
				$returning = '<div class="profipc_nice_label profipc_label_none"'.$labelerror.'>';
				$returning .= '<input type="email" id="'.$id.'" name="'.$id.'" value="'.$v.'"'.$error.' />';
			break;
			case 1:
				$v = (($value == $label)? '':$value);
				$returning = '<div class="profipc_nice_label profipc_label_tiny"'.$labelerror.'>';
				$returning .= '<label for="'.$id.'">'.$label.'</label>';
				$returning .= '<input type="email" id="'.$id.'" name="'.$id.'" value="'.$v.'"'.$error.' />';
			break;
			case 2:
				$returning = '<div class="profipc_nice_label profipc_label_blur"'.$labelerror.'>';
				$returning .= '<input type="email" id="'.$id.'" name="'.$id.'" value="'.$value.'"'.$error.' onblur="if (this.value == \'\') {this.value = \''.$value.'\';}" onfocus="if (this.value == \''.$value.'\') {this.value = \'\';}" />';
			break;
			case 3:
				$v = (($value == $label)? '':$value);
				$returning = '<div class="profipc_nice_label profipc_label_line"'.$labelerror.'>';
				$returning .= '<label for="'.$id.'">'.$label.'</label>';
				$returning .= '<input type="email" id="'.$id.'" name="'.$id.'" value="'.$v.'"'.$error.' />';
			break;
		}
	} elseif ($type == 'textarea') { // textarea
		switch ($labelType) {
			case 0:
				$v = (($value == $label)? '':$value);
				$returning = '<div class="profipc_nice_label profipc_label_none"'.$labelerror.'>';
				$returning .= '<textarea rows="4" label="message" id="'.$id.'" name="'.$id.'"'.$error.'>' . stripslashes($v) . '</textarea>';
			break;
			case 1:
				$v = (($value == $label)? '':$value);
				$returning = '<div class="profipc_nice_label profipc_label_tiny"'.$labelerror.'>';
				$returning .= '<label for="'.$id.'">'.$label.'</label>';
				$returning .= '<textarea rows="4" label="message" id="'.$id.'" name="'.$id.'"'.$error.'>' . stripslashes($v) . '</textarea>';
			break;
			case 2:
				$returning = '<div class="profipc_nice_label profipc_label_blur"'.$labelerror.'>';
				$returning .= '<textarea rows="4" label="message" name="'.$id.'"'.$error.' onblur="if (this.value == \'\') {this.value = \''.$value.'\';}" onfocus="if (this.value == \''.$value.'\') {this.value = \'\';}" />' . stripslashes($value) . '</textarea>';
			break;
			case 3:
				$v = (($value == $label)? '':$value);
				$returning = '<div class="profipc_nice_label profipc_label_line"'.$labelerror.'>';
				$returning .= '<label for="'.$id.'">'.$label.'</label>';
				$returning .= '<textarea rows="4" label="message" id="'.$id.'" name="'.$id.'"'.$error.'>' . stripslashes($v) . '</textarea>';
			break;
		}
	} else {
		switch ($labelType) {
			case 0:
				$v = (($value == $label)? '':$value);
				$returning = '<div class="profipc_nice_label profipc_label_none"'.$labelerror.'>';
				$returning .= '<input type="'.$type.'" id="'.$id.'" name="'.$id.'" value="'.$v.'"'.$error.' />';
			break;
			case 1:
				$v = (($value == $label)? '':$value);
				$returning = '<div class="profipc_nice_label profipc_label_tiny"'.$labelerror.'>';
				$returning .= '<label for="'.$id.'">'.$label.'</label>';
				$returning .= '<input type="'.$type.'" id="'.$id.'" name="'.$id.'" value="'.$v.'"'.$error.' />';
			break;
			case 2:
				$returning = '<div class="profipc_nice_label profipc_label_blur"'.$labelerror.'>';
				$returning .= '<input type="'.$type.'" id="'.$id.'" name="'.$id.'" value="'.$value.'"'.$error.' onblur="if (this.value == \'\') {this.value = \''.$value.'\';}" onfocus="if (this.value == \''.$value.'\') {this.value = \'\';}" />';
			break;
			case 3:
				$v = (($value == $label)? '':$value);
				$returning = '<div class="profipc_nice_label profipc_label_line"'.$labelerror.'>';
				$returning .= '<label for="'.$id.'">'.$label.'</label>';
				$returning .= '<input type="'.$type.'" id="'.$id.'" name="'.$id.'" value="'.$v.'"'.$error.' />';
			break;
		}
	}
	$returning .= '</div>';
	
	return $returning;
	
}

// Part 2 application
function qis_display_application( $values, $errors,$applied) {
	
	$application = qis_get_stored_application($values['formname']);
	$partone = qis_get_stored_register($values['formname']);
	$register = qis_get_stored_application_messages($values['formname']);
	$formnumber = $values['formname'];
	$settings = qis_get_stored_settings($formnumber);
	$arr = array_keys($application);
	
	$content = '<form action="" method="POST" enctype="multipart/form-data">
	<div class="applicationform">';
	
	if (count($errors) > 0) {
			$content .= '<h2>' . $register['errortitle'] . '</h2>';
			$content .= "<p class='qis-error-message'>" . $register['errorblurb'] . "</p>\r\t";
	} else {
		if (!empty($register['part2title'])) {
			$register['part2title'] = '<h2>' . $register['part2title'] . '</h2>';
		}
		if (!empty($register['part2blurb'])) {
			$register['part2blurb'] = '<p>' . $register['part2blurb'] . '</p>';
		}
		$content .= $register['part2title'];
	}
	if ($values['yourname']) $content .= '<p>'.$partone['yourname'].': ' . $values['yourname'] . '</p>';
	if ($values['youremail']) $content .= '<p>'.$partone['youremail'].': ' . $values['youremail'] . '</p>';
	if ($values['yourtelephone']) $content .= '<p>'.$partone['yourtelephone'].': ' . $values['yourtelephone'] . '</p>';
	
	$register['borrowvalues'] = str_replace('[amount]', $settings['currency'] . ' ' . $values['loan-amount'], $register['borrowvalues']);
	$register['borrowvalues'] = str_replace('[period]', $values['loan-period'] . ' ' . $settings['period'], $register['borrowvalues']);
	$register['reference'] = str_replace('[reference]', $values['reference'], $register['reference']);
	
	$content .= '<p>'.$register['borrowvalues'].'</p>
	<h4><a href="'.home_url().'">'.$register['changedetails'].'</a></h4>
	<p>'.$register['reference'].'</p>'
	.$register['part2blurb'];
	
	for($i = 1; $i < 10; $i++) {
		if ($register['use'.$i]) {
			$content .= '<fieldset><h2>'.$register['section'.$i].'</h2>';
			$sectionerror = $errors['documents'] ? ' style="color:red;"' : '';
			if ($register['section'.$i.'description']) $content .= '<p'.$sectionerror.'>'.$register['section'.$i.'description'].'</p>';
			foreach ($arr as $key) {
				if ($application[$key]['section'] == $i && $application[$key]['use']) { 
					$class = '';
					if (isset($application[$key]['class'])) $class = $application[$key]['class'];
				
					if ($application[$key]['type'] == 'text') {
						$required = ($application[$key]['required'] ? ' class = "required" ' : null );
						if ($errors[$key]) $required = ' style="border-color:red;"';
						$content .= '<p class="'.$class.'"><strong>'.$application[$key]['label'].'</strong><br>
						<input id="'.$key.'" name="'.$key.'" type="text" '.$required.' value="'.$values[$key].'" /></p>'."\n";
					}
					if ($application[$key]['type'] == 'date') {
						$required = ($application[$key]['required'] ? ' required ' : null );
						if ($errors[$key]) $required = '" style="border-color:red;"';
						$content .= '<p class="'.$class.'"><strong>'.$application[$key]['label'].'</strong><br>
						<input type="text" id="'.$key.'" class="qisdate'.$required.'" name="'.$key.'" value="' . $values[$key] . '" />
						<script type="text/javascript">jQuery(document).ready(function() {jQuery(\'\.qisdate\').datepicker({dateFormat : \'dd M yy\'});});</script></p>'."\r\t";
					}
					if ($application[$key]['type'] == 'dropdown') {
						$required = ($application[$key]['required'] ? ' class = "required" ' : null );
						if ($errors[$key]) $required = ' style="border-color:red;"';
						$content .= '<p class="'.$class.'"><strong>'.$application[$key]['label'].'</strong><br>';
						$content .= '<select name="'.$key.'" '.$required.'>'."\r\t";
						$d = explode(",",$application[$key]['options']);
						foreach ($d as $item) {
						   $selected = '';
						   if ($values[$key] == $item) $selected = 'selected';
						   $content .= '<option value="' .  $item . '" ' . $selected .'>' .  $item . '</option>'."\r\t";
						}
						$content .= '</select></p>'."\r\t";
					}
					if ($application[$key]['type'] == 'checkbox') {
						$required = ($application[$key]['required'] ? ' style = "color:green" ' : null );
						if ($errors[$key]) $required = ' style = "color:red;"';
						$content .= '<p'.$required.'  class="'.$class.'"><input type="checkbox" name="'.$key.'" value="checked" '.$values[$key].' /> '.$application[$key]['label'].'</p>';
					}
					if ($application[$key]['type'] == 'link') {
						$required = ($application[$key]['required'] ? ' style = "color:green" ' : null );
						if ($errors[$key]) $required = ' style = "color:red;"';
						$msg = $application[$key]['label'];
						if ($register['termstarget']) $target = ' target="blank" ';
						$msg = str_replace('[a]', '<a href= "'.$register['termsurl'].'"'.$target.'>', $msg);
						$msg = str_replace('[/a]', '</a>', $msg);
						$content .= '<p'.$required.'  class="'.$class.'"><input type="checkbox" name="'.$key.'" value="checked" '.$values[$key].' /> '.$msg.'</p>';
					}
					if ($application[$key]['type'] == 'multi') {
						$required = ($application[$key]['required'] ? ' style = "color:green" ' : null );
						if ($errors[$key]) $required = ' style = "color:red;"';
						$content .= '<p'.$required.'><strong>'.$application[$key]['label'].'</strong></p>';
						$d = explode(",",$application[$key]['options']);
						foreach ($d as $item) {
							$underscore = str_replace(' ','_',$item);
							$content .= '<p  class="'.$class.'"><input type="checkbox" name="'.$key.$underscore .'" value="checked" '.$values[$key.$underscore].' /> '.$item.'</p>';
						}
					}
					if ($application[$key]['type'] == 'upload') {
						$content .= '<p class="'.$class.'"><strong>'.$application[$key]['label'].'</strong><br>';
						$content .= '<select name="'.$key.'">'."\r\t";
						$d = explode(",",$application[$key]['options']);
						foreach ($d as $item) {
						   $selected = '';
						   if ($values[$key] == $item) $selected = 'selected';
						   $content .= '<option value="' .  $item . '" ' . $selected .'>' .  $item . '</option>'."\r\t";
						}
						$content .= '</select></p>'."\r\t";
						$content .= $errors['attach'.$key] ? '<p style="color:red;">'.$errors['attach'.$key].'</p>' : '';
						$content .= '<p class="'.$class.'"><input id="'.$key.'" name="'.$key.'" type="file" value="'.$values[$key].'" /></p>'."\n";
					}
				}
			}
		}
		$content .= '</fieldset>';
	}
	$content .= '
	<input type="hidden" name="formname" value="' . $values['formname'] . '" />
	<input type="hidden" name="sentdate" value="' . $values['sentdate'] . '" />
	<input type="hidden" name="reference" value="' . $values['reference'] . '" />
	<input type="hidden" name="yourname" value="' . $values['yourname'] . '" />
	<input type="hidden" name="youremail" value="' . $values['youremail'] . '" />
	<input type="hidden" name="yourtelephone" value="' . $values['yourtelephone'] . '" />
	<input type="hidden" name="loan-amount" value="' . $values['loan-amount'] . '" />
	<input type="hidden" name="loan-period" value="' . $values['loan-period'] . '" />
	<input type="hidden" name="rate" value="' . $values['rate'] . '" />
	<input onClick="check();" type="submit" value="'.$register['part2submit'].'" class="submit" name="part2submit" />
	</div>
	</form>';
	$content .= <<<SCRIPT
		<script type="text/javascript">
			jQuery(document).ready(function() {
				$ = jQuery;
				$('.sc_app_hidden').hide();
				$('select[name=hometime]').change(function(e) {
					if (this.selectedIndex > 4 || this.selectedIndex == 0) {
						if ($('.sc_app_hidden').is(":visible"))
							$('.sc_app_hidden').slideToggle();
					} else {
						if (!$('.sc_app_hidden').is(":visible"))
							$('.sc_app_hidden').slideToggle();
					}
				});
			});
		</script>
SCRIPT;
	return $content;
}

// Verify part 2 application
function qis_verify_application(&$values, &$errors) {
	$application = qis_get_stored_application($values['formname']);
	$register = qis_get_stored_application_messages($values['formname']);
	
	$arr = array_keys($application);
	foreach ($arr as $key => $value) {
		if ($application[$key]['type'] == 'multi') {
			$d = explode(",",$application[$key]['options']);
			foreach ($d as $item) {
				if ($values[$key.$item]) $values['loanreason'] = 'checked';
			}
		}
		if ($application[$key]['required'] == 'use' && $application[$key]['required'] == 'checked' && $register['use'.$application[$key]['section']] && (empty($values[$key]) || $values[$key] == 'Select...')) 
			$errors[$key] = 'error';
		}
	
	$filenames = array('identityproof','addressproof');
	
	foreach($filenames as $item) {
		$tmp_name = $_FILES[$item]['tmp_name'];
		$name = $_FILES[$item]['name'];
		$size = $_FILES[$item]['size'];
		if (file_exists($tmp_name)) {
			if ($size > $register['attach_size']) $errors['attach'.$item] = $register['attach_error_size']; 
			$ext = strtolower(substr(strrchr($name,'.'),1));
			if (strpos($register['attach_type'],$ext) === false) $errors['attach'.$item] = $register['attach_error_type'];
		}
	}
	return (count($errors) == 0);	
}

// Process part 2 application
function qis_process_application($values) {
	global $post;
	$content='';
	$register = qis_get_stored_register ('default');
	$applicationmessages = qis_get_stored_application_messages($values['formname']);
	$formnumber = $values['formname'];
	$settings = qis_get_stored_settings($formnumber);
	$auto = qis_get_stored_autoresponder($formnumber);
	$application = qis_get_stored_application($formnumber);
	$message = get_option('qis_messages');
	
	$arr = array_keys($application);
	
	if ($message) {
		$count = count($message);
		for($i = 0; $i <= $count; $i++) {
		if ($message[$i]['reference'] == $values['reference']) {
			$values['complete'] = 'Completed';
			$message[$i] = $values;
			update_option('qis_messages',$message);
			}
		}	
	}

	$filenames = array('identityproof','addressproof');
	
	$attachments = array();
	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
	}
	add_filter( 'upload_dir', 'qis_upload_dir' );
	
	$dir = (realpath(WP_CONTENT_DIR . '/uploads/qis/') ? '/uploads/qis/' : '/uploads/');
	foreach($filenames as $item) {
		$filename = $_FILES[$item]['tmp_name'];
		if (file_exists($filename)) {
			$name = $values['reference'].'-'.$_FILES[$item]['name'];
			$name = trim(preg_replace('/[^A-Za-z0-9. ]/', '', $name));
			$name = str_replace(' ','-',$name);
			$_FILES[$item]['name'] = $name;
			$uploadedfile = $_FILES[$item];
			$upload_overrides = array( 'test_form' => false );
			$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
			array_push($attachments , WP_CONTENT_DIR .$dir.$name);
		}
	}
	
	remove_filter( 'upload_dir', 'qis_upload_dir' );
	
	$content = qis_build_complete_message($values,$application,$arr,$register);
	
	qis_send_full_notification ($register,$values,$content,true,$attachments);
	
	qis_send_full_confirmation ($auto,$values,$content,$register);
}

function qis_upload_dir( $dir ) {
	return array(
		'path'   => $dir['basedir'] . '/qis',
		'url'	=> $dir['baseurl'] . '/qis',
		'subdir' => '/qis',
	) + $dir;
}

function qis_display_result($values) {
	$register = qis_get_stored_application_messages($values['formname']);
	$msg = $register['thankyoublurb'];
	$msg = str_replace('[name]', $values['yourname'],$msg);
	$msg = str_replace('[amount]', $values['loan-amount'], $msg);
	$msg = str_replace('[email]', $values['youremail'], $msg);
	$msg = str_replace('[reference]', $values['reference'], $msg);
	
	$content = '<div class="applicationform">
	<h2>'.$register['thankyoutitle'].'</h2>'.$msg.'</div>';
	return $content;
}

function qis_send_full_notification ($register,$values,$content,$complete,$attachments) {
	$qis_email = $register['sendemail'] ? $register['sendemail'] : get_bloginfo('admin_email');
	if ($complete) {
		$notificationsubject = 'Completed Application from '.$values['yourname'].' on '.$values['sentdate'];
	} else {
		$notificationsubject = 'New Loan Application from '.$values['yourname'].' on '.$values['sentdate'];
	}
	$headers = "From: ".$values['yourname']." <".$values['youremail'].">\r\n"
	. "Content-Type: text/html; charset=\"utf-8\"\r\n";	
	
	$message = '<html>'.$content.'</html>';
	wp_mail($qis_email, $notificationsubject, $message, $headers,$attachments);
}

function qis_send_full_confirmation ($auto,$values,$content,$register) {
	$date = date_i18n("d M Y");
	$subject = $auto['subject'] ? $auto['subject'] : 'Loan Application';
	
	if (!$auto['fromemail']) $auto['fromemail'] = get_bloginfo('admin_email');
	if (!$auto['fromname']) $auto['fromname'] = get_bloginfo('name');

	$msg = $auto['message'];
	$msg = str_replace('[name]', $values['yourname'], $msg);
	$msg = str_replace('[amount]', $values['loan-amount'], $msg);
	$msg = str_replace('[period]', $values['loan-period'], $msg);
	$msg = str_replace('[date]', $date, $msg);
	$msg = str_replace('[reference]', $values['reference'], $msg);
	
	$copy .= '<html>' . $msg;
	if ($auto['useregistrationdetails'] || $values['qis-copy']) {
		if($auto['registrationdetailsblurb']) {
			$copy .= '<h2>'.$auto['registrationdetailsblurb'].'</h2>';
		}
		$copy .= $content;
	}
	
	$message = $copy.'</html>';
	$headers = "From: ".$auto['fromname']." <{$auto['fromemail']}>\r\n"
. "Content-Type: text/html; charset=\"utf-8\"\r\n";	
	wp_mail($values['youremail'], $subject, $message, $headers);
}

function qis_build_pending_message($values,$register) {
	
	$formnumber = $values['formname'];
	$settings = qis_get_stored_settings($formnumber);
	
	$content = '<p><b>' . $register['yourname'] . ': </b>' . strip_tags(stripslashes($values['yourname'])) . '</p>
	<p><b>' . $register['youremail'] . ': </b>' . strip_tags(stripslashes($values['youremail'])) . '</p>
	<p><b>' . $register['yourtelephone'] . ': </b>' . strip_tags(stripslashes($values['yourtelephone'])) . '</p>
	<p><b>' . $register['borrowlabel'] . ':</b> ' . strip_tags(stripslashes($values['loan-amount'])) . '</p>
	<p><b>' . $register['forlabel'] . ':</b> ' . strip_tags(stripslashes($values['loan-period'])) . '</p>';
	
	return $content;
}

function qis_build_complete_message($values,$application,$arr,$register) {
	
	$content = qis_build_pending_message($values,$register);
	
	foreach ($arr as $key) {
		if ($application[$key]['type'] == 'multi') {
			$d = explode(",",$application[$key]['options']);
				$test = null;
				foreach ($d as $item) {
					$underscore = str_replace(' ','_',$item);
					if ($values[$key.$underscore]) $test .= $item.', ';
				}
				$values[$key]= substr($test, 0, -2);
		}
		if ($values[$key] && $values[$key] !="Select..." && $application[$key]['section'] != 9) $content .= '<p><b>'.$application[$key]['label'].':</b> ' . strip_tags(stripslashes($values[$key])) . '</p>';
	}
	return $content;
}

class qis_akismet {
	private $version = '0.4';
	private $wordPressAPIKey;
	private $blogURL;
	private $comment;
	private $apiPort;
	private $akismetServer;
	private $akismetVersion;
	private $ignore = array(
		'HTTP_COOKIE', 
		'HTTP_X_FORWARDED_FOR', 
		'HTTP_X_FORWARDED_HOST', 
		'HTTP_MAX_FORWARDS', 
		'HTTP_X_FORWARDED_SERVER', 
		'REDIRECT_STATUS', 
		'SERVER_PORT', 
		'PATH',
		'DOCUMENT_ROOT',
		'SERVER_ADMIN',
		'QUERY_STRING',
		'PHP_SELF'
	);
	public function __construct($blogURL, $wordPressAPIKey) {
		$this->blogURL = $blogURL;
		$this->wordPressAPIKey = $wordPressAPIKey;
		$this->apiPort = 80;
		$this->akismetServer = 'rest.akismet.com';
		$this->akismetVersion = '1.1';
		$this->comment['blog'] = $blogURL;
		$this->comment['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		if(isset($_SERVER['HTTP_REFERER'])) $this->comment['referrer'] = $_SERVER['HTTP_REFERER'];
		$this->comment['user_ip'] = $_SERVER['REMOTE_ADDR'] != getenv('SERVER_ADDR') ? $_SERVER['REMOTE_ADDR'] : getenv('HTTP_X_FORWARDED_FOR');
	}
	public function isKeyValid() {
		$response = $this->sendRequest('key=' . $this->wordPressAPIKey . '&blog=' . $this->blogURL, $this->akismetServer, '/' . $this->akismetVersion . '/verify-key');
		return $response[1] == 'valid';
	}
	
	private function sendRequest($request, $host, $path) {
		$http_request  = "POST " . $path . " HTTP/1.0\r\n";
		$http_request .= "Host: " . $host . "\r\n";
		$http_request .= "Content-Type: application/x-www-form-urlencoded; charset=utf-8\r\n";
		$http_request .= "Content-Length: " . strlen($request) . "\r\n";
		$http_request .= "User-Agent: Akismet PHP5 Class " . $this->version . " | Akismet/1.11\r\n";
		$http_request .= "\r\n";
		$http_request .= $request;
		$socketWriteRead = new qisSocketWriteRead($host, $this->apiPort, $http_request);
		$socketWriteRead->send();
		return explode("\r\n\r\n", $socketWriteRead->getResponse(), 2);
	}
	
	private function getQueryString() {
		foreach($_SERVER as $key => $value) {
			if(!in_array($key, $this->ignore)) {
				if($key == 'REMOTE_ADDR') {
					$this->comment[$key] = $this->comment['user_ip'];
				} else {
					$this->comment[$key] = $value;
				}
			}
		}
		$query_string = '';
		foreach($this->comment as $key => $data) {
			if(!is_array($data)) $query_string .= $key . '=' . urlencode(stripslashes($data)) . '&';
		}
		return $query_string;
	}
	
	public function isCommentSpam() {
		$response = $this->sendRequest($this->getQueryString(), $this->wordPressAPIKey . '.rest.akismet.com', '/' . $this->akismetVersion . '/comment-check');
		if($response[1] == 'invalid' && !$this->isKeyValid()) {
			throw new exception('The Wordpress API key passed to the Akismet constructor is invalid.  Please obtain a valid one from http://wordpress.com/api-keys/');
		}
		return ($response[1] == 'true');
	}

	public function submitSpam() {
		$this->sendRequest($this->getQueryString(), $this->wordPressAPIKey . '.' . $this->akismetServer, '/' . $this->akismetVersion . '/submit-spam');
	}
	public function submitHam() {
		$this->sendRequest($this->getQueryString(), $this->wordPressAPIKey . '.' . $this->akismetServer, '/' . $this->akismetVersion . '/submit-ham');
	}
	public function setUserIP($userip) {$this->comment['user_ip'] = $userip;}
	public function setReferrer($referrer) {$this->comment['referrer'] = $referrer;}
	public function setPermalink($permalink) {$this->comment['permalink'] = $permalink;}
	public function setCommentType($commentType) {$this->comment['comment_type'] = $commentType;}
	public function setCommentAuthor($commentAuthor) {$this->comment['comment_author'] = $commentAuthor;}
	public function setCommentAuthorEmail($authorEmail) {$this->comment['comment_author_email'] = $authorEmail;}
	public function setCommentAuthorURL($authorURL) {$this->comment['comment_author_url'] = $authorURL;}
	public function setCommentContent($commentBody) {$this->comment['comment_content'] = $commentBody;}
	public function setAPIPort($apiPort) {$this->apiPort = $apiPort;}
	public function setAkismetServer($akismetServer) {$this->akismetServer = $akismetServer;}
	public function setAkismetVersion($akismetVersion) {$this->akismetVersion = $akismetVersion;}
}

class qisSocketWriteRead {
	private $host;
	private $port;
	private $request;
	private $response;
	private $responseLength;
	private $errorNumber;
	private $errorString;
	public function __construct($host, $port, $request, $responseLength = 1160) {
		$this->host = $host;
		$this->port = $port;
		$this->request = $request;
		$this->responseLength = $responseLength;
		$this->errorNumber = 0;
		$this->errorString = '';
	}
	public function send() {
		$this->response = '';
		$fs = fsockopen($this->host, $this->port, $this->errorNumber, $this->errorString, 3);
		if($this->errorNumber != 0) {
			throw new Exception('Error connecting to host: ' . $this->host . ' Error number: ' . $this->errorNumber . ' Error message: ' . $this->errorString);
		}
		if($fs !== false) {
			@fwrite($fs, $this->request);
			while(!feof($fs)) 
				$this->response .= fgets($fs, $this->responseLength);
		}
		fclose($fs);
	}
	public function getResponse() {return $this->response;}
	public function getErrorNumner() {return $this->errorNumber;}
	public function getErrorString() {return $this->errorString;}
}