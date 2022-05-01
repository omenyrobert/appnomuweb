<?php
add_action('admin_menu', 'qis_page_init');
add_action('admin_notices', 'qis_admin_notice' );
add_action('admin_enqueue_scripts', 'qis_scripts_init');
add_action('admin_menu', 'qis_admin_pages' );

function qis_page_init() {
	add_options_page('Loan Calculator', 'Loan Calculator', 'manage_options', __FILE__, 'qis_tabbed_page');
}

function qis_admin_pages() {
	add_menu_page('Applications', 'Applications', 'manage_options','quick-interest-slider/messages.php','','dashicons-email-alt');
}

function qis_admin_tabs($current = 'settings') {
	
	$application = qis_get_stored_application_messages(1);
	
	if ($application['enable']) {
		$tabs = array(
			'settings'	  => __('Settings', 'quick-interest-slider'),
			'application'   =>  __('Application Form', 'quick-interest-slider'),
			'parttwo'	   =>  __('Full Application', 'quick-interest-slider'),
			'auto'		  => __('Auto Responder', 'quick-interest-slider'),
			'dropdown'	  => __('Selector', 'quick-interest-slider'),
			'tracking'	  => __('Tracking', 'quick-interest-slider'),
			'styles'		=> __('Styling', 'quick-interest-slider'),
			'upgrade'	   => __('Upgrade', 'quick-interest-slider'),
		);
	} else {
		$tabs = array(
			'settings'	  => __('Settings', 'quick-interest-slider'),
			'application'   =>  __('Application Form', 'quick-interest-slider'),
			'auto'		  => __('Auto Responder', 'quick-interest-slider'),
			'dropdown'	  => __('Selector', 'quick-interest-slider'),
			'tracking'	  => __('Tracking', 'quick-interest-slider'),
			'styles'		=> __('Styling', 'quick-interest-slider'),
			'upgrade'	   => __('Upgrade', 'quick-interest-slider'),
		);
	}
	
	echo '<h2 class="nav-tab-wrapper">';
	foreach( $tabs as $tab => $name ) {
		$class = ( $tab == $current ) ? ' nav-tab-active' : '';
		echo "<a class='nav-tab$class' href='?page=quick-interest-slider/settings.php&tab=$tab'>$name</a>";
		}
	echo '</h2>';
}

function qis_tabbed_page() {
	echo '<div class="wrap">';
	echo '<h1>'.__('Loan Repayment Calculator', 'quick-interest-slider').'</h1>';
	if ( isset ($_GET['tab'])) {
		qis_admin_tabs($_GET['tab']); $tab = $_GET['tab'];
	} else {
		qis_admin_tabs('settings'); $tab = 'settings';
	}
	switch ($tab) {
		case 'styles' : qis_styles(); break;
		case 'settings' : qis_settings (); break;
		case 'application' : qis_register (); break;
		case 'auto' : qis_autoresponse_page(); break;
		case 'parttwo' : qis_application(); break;
		case 'outputs' : qis_outputtable(); break;
		case 'dropdown' : qis_setdropdowns(); break;
		case 'tracking' : qis_progress(); break;   
		case 'upgrade' : qis_upgrade(); break;
		}
	echo '</div>';
}

function qis_settings(){
	$apostrophe=$none=$comma=$space=$dot=$before=$after=$days=$weeks=$months=$years=$slider=$text=$both=$beforeinterest=$afterinterest=false;
	$US=$EU=$monthnum=false;
	$float=$amounttrigger=$periodtrigger=$fixed=$simple=$compound=$amortization=$amortisation=$mambu=$percent=$always=false;
	$hideline = $advanced = $one = $two = $three = $four = $five = false;
	$tenround=$hundredround=$thousandround = false;
	$include=$paragraph=false;
	
	$formnumber = get_option('qis_formnumber');
	$theform  = (!$formnumber || $formnumber == 1) ? 1 : $formnumber;

	if( isset( $_POST['Submit'])) {
		$options = array(
			'formheader', 
			'buttons',
			'currency',
			'iso',
			'ba',
			'separator',
			'primary',
			'secondary',
			'interesttype',
			'loanlabel',
			'loanmin',
			'loanmax',
			'loaninitial',
			'loanstep',
			'loanhelp',
			'loaninfo',
			'termlabel',
			'periodslider',
			'periodmin',
			'periodmax',
			'periodinitial',
			'periodstep',
			'period',
			'singleperiodlabel',
			'periodlabel',
			'periodhelp',
			'periodinfo',
			'interestslider',
			'interestmin',
			'interestmax',
			'interestinitial',
			'intereststep',
			'interestlabel',
			'interesthelp',
			'interestinfo',
			'multiplier',
			'trigger',
			'outputrepayments',
			'repaymentlabel',
			'outputinterestlabel',
			'outputtotallabel',
			'interestlabel',
			'totallabel',
			'primarylabel',
			'secondarylabel',
			'usebubble',
			'outputlimits',
			'outputinterest',
			'outputtotal',
			'outputhelp',
			'outputinfo',
			'triggers',
			'markers',
			'adminfee',
			'adminfeevalue',
			'adminfeetype',
			'termfee',
			'termfeevalue',
			'adminfeemin',
			'adminfeemax',
			'adminfeewhen',
			'usedownpayment',
			'downpaymentfixed',
			'downpaymentpercent',
			'usedownpaymentslider',
			'downpaymentlabel',
			'downpaymenthelp',
			'downpaymentinfo',
			'downpaymentmin',
			'downpaymentmax',
			'downpaymentinitial',
			'downpaymentstep',
			'textinputs',
			'textinputslabel',
			'triggertype',
			'decimals',
			'maxminlimits',
			'nosliderlabel',
			'applynow',
			'applynowlabel',
			'applynowaction',
			'applynowquery',
			'querystructure',
			'fixedaddition',
			'outputhide',
			'nostore',
			'usecurrencies',
			'usefx',
			'currencieslabel',
			'currency_array',
			'interestselector',
			'interestselectorlabel',
			'interestrate1',
			'interestname1',
			'interestrate2',
			'interestname2',
			'interestrate3',
			'interestname3',
			'interestrate4',
			'interestname4',
			'interestdropdown',
			'interestdropdownlabel',
			'interestdropdownvalues',
			'interestdropdownlabelposition',
			'periodformat',
			'dateseperator',
			'dae',
			'sort',
			'usegraph',
			'graphlabel',
			'graphdownpayment',
			'graphdiscount',
			'graphprinciple',
			'graphprocessing',
			'graphinterest',
			'rounding',
			'terminterface',
			'termbutton0',
			'termbutton1',
			'termbutton2',
			'termbutton3',
			'buttonunits'
		);

		$newsettings = [];
		foreach ($options as $item) {
			if (isset($_POST[$item]) && is_array($_POST[$item])) {
				if ($item == 'triggers') {
					$triggers = array();
					for ($i = 0; $i < 7; $i++) {
						$x = $_POST['triggers'][$i];
						if (isset($_POST['triggers'][$i]['rate']) && !empty($_POST['triggers'][$i]['rate'])) {
							$triggers[] = array(
								'rate' => (float) trim(stripslashes($_POST['triggers'][$i]['rate'])),
								'trigger' => (int) trim(stripslashes($_POST['triggers'][$i]['trigger'])),
								'amttrigger' => (float) trim(stripslashes($_POST['triggers'][$i]['amttrigger'])),
								'dae' => @trim(stripslashes($_POST['triggers'][$i]['dae']))
							);
						}
					}
					$settings['triggers'] = $triggers;
				}
			} else {
				$settings[$item] = @stripslashes($_POST[$item]);
			}
		}
		
		if (isset($_POST['usebubble'])) {
			$settings['usebubble'] = 1;
		} else {
			$settings['usebubble'] = 0;
		}
		
		/*
			Add Legacy Support for 'periodslider'
		*/
		if (isset($_POST['terminterface']) || isset($_POST['periodslider'])) {
			$settings['periodslider'] = 'checked';
			
			// Unauthorized
			if (!isset($_POST['terminterface'])) $settings['terminterface'] = 'slider';
		} else {
			$settings['periodslider'] = '';
			$settings['terminterface'] = '';
		}
		
		/*
			Straighten out the currency array
		*/
		if (isset($_POST['currency_array'])) {
			$settings['currency_array'] = array();
			foreach ($_POST['currency_array'] as $c_K => $c_V) {
				if (strlen($c_V['symbol']) && strlen($c_V['name']) && strlen($c_V['iso'])) {
					$settings['currency_array'][] = $c_V;
				}
			}
		}

		if (isset($_POST['resetsort']) && $_POST['resetsort'] ) $settings['sort'] = false;
		
		update_option( 'qis_settings'.$theform, $settings);
		qis_admin_notice(__('The settings have been updated', 'quick-interest-slider'));
		
	}

	if( isset( $_POST['Reset'])) {
		delete_option('qis_settings'.$theform);
		qis_admin_notice(__('The settings have been reset', 'quick-interest-slider'));
	}

	
	if( isset( $_POST['changeform'])) {
		$qisform = $theform = $_POST['calculator'];
		update_option( 'qis_formnumber', $qisform);
	}
	
	if( isset( $_POST['advanced'])) {
		update_option( 'qis_advanced', true);
	}
	
	if( isset( $_POST['basic'])) {
		delete_option( 'qis_advanced');
	}
	
	$settings = qis_get_stored_settings($theform);

	${$settings['period']} = 'checked';
	${$settings['ba']} = 'checked';
	${$settings['textinputs']} = 'checked';
	${$settings['separator']} = 'checked';
	${$settings['interesttype']} = 'checked';
	${$settings['adminfeetype']} = 'checked';
	${$settings['triggertype']} = 'checked';
	${$settings['decimals']} = 'checked';
	${$settings['rounding']} = 'checked';
	${$settings['adminfeewhen']} = 'checked';
	${$settings['periodformat']} = 'checked';
	${$settings['interestdropdownlabelposition']} = 'checked';
	${$theform} = 'checked';
	
	if ($settings['ba'] == 'before') {
		$settings['cb'] = $settings['currency'];
		$settings['ca'] = ' ';
	} else {
		$settings['ca'] = $settings['currency'];
		$settings['cb'] = ' ';
	}
	
	if (!isset($settings['triggers'][3]['rate'])) $settings['triggers'][3]['rate'] = false;
	if (!isset($settings['triggers'][3]['trigger'])) $settings['triggers'][3]['trigger'] = false;
	if (!isset($settings['triggers'][3]['amttrigger'])) $settings['triggers'][3]['amttrigger'] = false;
	
	$qppkey = get_option('qpp_key');
	$register	= qis_get_stored_register($theform);
	
	$disabled = $qppkey['authorised'] ? '' : ' disabled';
	$promessage = $qppkey['authorised'] ? '' : ' (pro version only)';

	$noshow = get_option('qis_advanced') ? true : false;
	if ($noshow==true) {
		$advanced = "display:none;";
		$hideline = ' style="display:none;"';
	}
	
	$usebubble = '';	
	if ($settings['usebubble']) $usebubble = " checked='checked' ";
	
	$content ='<form method="post" action="">
	<div class="qis-options">';
	
	$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">

	<h2>'.__('Using the Plugin', 'quick-interest-slider').'</h2>';
	
	$my_theme = wp_get_theme();
	if (strtolower($my_theme->get( 'Name' )) == 'mentry') {
		$content .= '<p><span style="colour: red">WARNING</span> If you are using the Mentry theme and have any questions please contact the theme developers.</p>';
	}
	
	if ($theform == 1 || !$theform) {
		$content .='<p>'.__('Add the form to a post or page using the shortcode [qis]', 'quick-interest-slider').' '.__('or the <strong>Loan Calculator</strong> block', 'quick-interest-slider').'.</p>'; 
	} else {
		$content .='<p>'.__('Add the form to a post or page using the shortcode', 'quick-interest-slider').' [qis calculator='.$theform.'] '.__('or the <strong>Loan Calculator</strong> block', 'quick-interest-slider').'.</p>'; 
	}

	$content .='<p>'.__('Customise your form using the settings below or with shortcode attributes', 'quick-interest-slider').'. <a href="https://loanpaymentplugin.com/shortcodes/" target="_blank">'.__('See all shortcode attributes', 'quick-interest-slider').'</a>.</p>
	
	<p>'.__('If you need help with the settings', 'quick-interest-slider').' <a href="https://loanpaymentplugin.com/settings/" target="_blank">'.__('click here', 'quick-interest-slider').'</a>.</p>
	
	<p>'.__('To see what features will be included in the next version and get your hands on a beta copy', 'quick-interest-slider').' <a href="https://loanpaymentplugin.com/qis-update/" target="_blank">'.__('click here', 'quick-interest-slider').'</a>.</p>';
	
	if (!$qppkey['authorised']) $content .='<p><a href="?page=quick-interest-slider/settings.php&tab=upgrade">'.__('Upgrade to Pro', 'quick-interest-slider').'</a> '.__('for more options and styling', 'quick-interest-slider').', '.__('interest sliders and selectors', 'quick-interest-slider').', '.__('downpayments', 'quick-interest-slider').', '.__('a loan application form', 'quick-interest-slider').' '.__('and foreign exchange calculations', 'quick-interest-slider').'  </p>';

	if ($noshow) $content .= '<p>'.__('The settings here will get you started', 'quick-interest-slider').'. '.__('To see all the settings (and there are a lot of them) click the button below', 'quick-interest-slider').'.</p>
	<p><input type="submit" name="basic" class="button-secondary" value="Show all settings" /></p>';
	else $content .= '<p><input type="submit" name="advanced" class="button-secondary" value="Hide advanced settings" /></p>';
	
	$content .='</fieldset>';
	
		if (isset($qppkey['authorised']) && $qppkey['authorised']) {
		$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
		<h2>'.__('Form Selection', 'quick-interest-slider').'</h2>
		<p><select name="calculator">';
		for ($i= 1; $i<=25; $i++) {
			$selected = $theform == $i ? ' selected' : '';
			$content .='<option value="'.$i.'"'.$selected.'>Table '.$i.'</option>';
		}
		$content .='</select > <input type="submit" name="changeform" class="button-secondary" value="Change Form" /></p>
		</fieldset>';
	}
	
	$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
	<h2>'.__('Form Header', 'quick-interest-slider').'</h2>
	<p class="description">'.__('Add a title to the top of the form', 'quick-interest-slider').'.</p>
	<p><input type="text" name="formheader" . value ="' . $settings['formheader'] . '" /></p>
	
	</fieldset>';
	
	
	if ($qppkey['authorised']) {
		$sort = explode(",", $settings['sort']);
		$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
		<h2>'.__('Sorting', 'quick-interest-slider').'</h2>
		<p>'.__('Drag and drop the elements below to change the display order', 'quick-interest-slider').'. '.__('Only enabled or shortcode elements will be displayed', 'quick-interest-slider').'</p>
		<style>ul.sorting{width:90%;background:#FFF;padding: 4px;border: 1px solid #888;}
		.sorting li{outline: 1px solid #888;background:#E0E0E0;display:inline;padding: 2px;vertical-align:middle;margin-right: 5px;cursor:pointer;}
		</style>
		<script>
		jQuery(function() 
		{var qis_rsort = jQuery( "#qis_rsort" ).sortable(
		{axis: "x",cursor: "move",opacity:0.8,update:function(e,ui)
		{var order = qis_rsort.sortable("toArray").join();jQuery("#qis_order_sort").val(order);}});});
		</script>
		<ul class="sorting" id="qis_rsort">';
		
		foreach($sort as $item) {
			switch ( $item ) {
				case 'amount':
					$content .='<li id="amount">Amount</li>';
				break;
				case 'currencies':
					$currencies = $settings['usecurrencies'] ? '' : 'style="color:#CCC"';
					$content .='<li '.$currencies.' id="currencies">Currencies</li>';
				break;
				case 'term':
					$term = $settings['periodslider'] ? '' : 'style="color:#CCC"';
						$content .='<li '.$term.' id="term">Term</li>';
				break;
				case 'interest':
					$interest = $settings['interestslider'] || $settings['interestdropdown'] || $settings['interestselector'] ? '' : 'style="color:#CCC"';
						$content .='<li '.$interest.' id="interest">Interest</li>';
				break;
				case 'fx':
					$fx = $settings['usefx'] ? '' :'style="color:#CCC"';
					$content .='<li '.$fx.' id="fx">FX</li>';
				break;
				case 'graph':
					$graph = $settings['usegraph'] ? '' : 'style="color:#CCC"';
					$content .='<li '.$graph.' id="graph">Graph</li>';
				break;
				case 'repayments':
					$repayments =$settings['outputrepayments'] ? '' : 'style="color:#CCC"';
					$content .='<li '.$repayments.' id="repayments">Repayments</li>';
				break;
				case 'total':
					$total = $settings['outputtotal'] ? '' : 'style="color:#CCC"';
					$content .='<li '.$total.' id="total">Total</li>';
				break;
				case 'apply':
					$apply = $settings['applynow'] || $register['application'] ? '' : 'style="color:#CCC"';
					$content .='<li '.$apply.' id="apply">Application</li>';
				break;
				case 'downpayment':
					$downpayment = $settings['usedownpaymentslider'] ? '' : 'style="color:#CCC"';
					$content .='<li '.$downpayment.' id="downpayment">Downpayment</li>';
				break;
			}
		}
		$content .='</ul>';
		$content .='<p><input type="checkbox" name="resetsort"  value="checked" /> '.__('Reset order', 'quick-interest-slider').'
		<input type="hidden" id="qis_order_sort" name="sort" value="'.$settings['sort'].'" />
		</fieldset>';
	} else {
		$content .= '<input type="hidden" id="qis_order_sort" name="sort" value="'.$settings['sort'].'" />';
	}

	$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
	<h2>'.__('Currency', 'quick-interest-slider').'</h2>
	<p>'.__('Symbol','quick-interest-slider').': <input type="text" style="width:2em;" name="currency" . value ="' . $settings['currency'] . '" /><span'.$hideline.'> '.__('ISO code','quick-interest-slider').': <input type="text" style="width:3em;" name="iso" . value ="' . $settings['iso'] . '" /> <a href="https://www.ecb.europa.eu/stats/policy_and_exchange_rates/euro_reference_exchange_rates/html/index.en.html" target="_blank">See all supported ISO codes</a>.</span></p>
	<p><b>'.__('Currency Position', 'quick-interest-slider').':</b> <input type="radio" name="ba" value="before" ' . $before . ' />'.__('Before amount', 'quick-interest-slider').'&nbsp;<input type="radio" name="ba" value="after" ' . $after . ' />'.__('After amount', 'quick-interest-slider').'</p>
	
	</fieldset>';
	
	//Currencies
	$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	<h2>'.__('Currency Selectors and Foreign Exchange', 'quick-interest-slider').'</h2>';
	
	if ($qppkey['authorised']) {
		$CS = !$settings['usecurrencies'] ? $CS = ' style="display:none;"' : '';
		$content .= '
		
		<p><input type="checkbox" name="usecurrencies" id="chkCurrency" value="checked" ' . $settings['usecurrencies'] . '/> '.__('Add currency selector', 'quick-interest-slider').'. '.__('Lets users change the currency on the slider and output messages', 'quick-interest-slider').'. '.__('Leave unchecked to display the default currency', 'quick-interest-slider').'.</p>
		
		<p><input type="checkbox" name="usefx" id="chkFX" value="checked" ' . $settings['usefx'] . '/> '.__('Currency conversions', 'quick-interest-slider').'. '.__('Lets users select the repayment currency', 'quick-interest-slider').'. '.__('Leave unchecked to display the default currency', 'quick-interest-slider').'.</p>
		<div id="showCurrencies"'.$CS.'>
		<p>'.__('Currency selector label', 'quick-interest-slider').':<input type="text" name="currencieslabel" . value ="' . $settings['currencieslabel'] . '" /></p>
		<p>'.__('FX selector label', 'quick-interest-slider').':<input type="text" name="fxlabel" . value ="' . $settings['fxlabel'] . '" /></p>
		<table id="currencies">
		<thead>
			<tr>
				<th width="4em">'.__('Symbol', 'quick-interest-slider').'</th>
				<th width="10em">'.__('Name', 'quick-interest-slider').'</th>
				<th width="4em">'.__('ISO', 'quick-interest-slider').'</th>
				<th>Remove</th>
			</tr>
		</thead><tbody>';

		$template = <<<template
		<tr>
			<td><input class="input-symbol" type="text" style="width:2em;" name="{name1}" . value="{value1}" /></td>
			<td><input class="input-name" type="text" style="width:8em;" name="{name2}" . value="{value2}" /></td>
			<td><input class="input-iso" type="text" style="width:3em;" name="{name3}" . value="{value3}" /></td>
			<td><a href="javascript:void(0);" class="fx_remove_line">X</a></td>
		</tr>
template;

		$var	= 'c2';
		$c1		= '';
		$c2		= '';
		
		if ( $settings['currency_array'] == "" ) {
			$settings['currency_array'] = [];
		} else { 	
			$settings['currency_array'];
		}
		
		for ($i = 0; $i <= count($settings['currency_array']); $i++) {
			
			if (isset($settings['currency_array'][$i])) {
				$name	= array('currency_array['.$i.'][symbol]','currency_array['.$i.'][name]','currency_array['.$i.'][iso]');
				$value	= array($settings['currency_array'][$i]['symbol'],$settings['currency_array'][$i]['name'],$settings['currency_array'][$i]['iso']);
			} else {
				$name	= array('','','');
				$value	= array('','','');
				$var	= 'c1';
			}
			
			${$var} .= $template;
			${$var} = str_replace(array('{name1}','{name2}','{name3}'),$name,${$var});
			${$var} = str_replace(array('{value1}','{value2}','{value3}'),$value,${$var});
		}
		$content .= $c2;
		$content .='</tbody></table>';
		$content .= '<script type="text/javascript">qis_template = '.json_encode(array('raw' => $c1)).';</script>';
		$content .= '<p><a href="javascript:void(0);" class="fx_new_line">'.__('Add New Currency', 'quick-interest-slider').'</a></p>';
		$content .= '<p>'.__('Exchange rates for supported currencies are issued daily at 4pm CET by the', 'quick-interest-slider').' <a href="https://www.ecb.europa.eu/stats/policy_and_exchange_rates/euro_reference_exchange_rates/html/index.en.html" target="_blank">'.__('European Central Bank', 'quick-interest-slider').'</a>.</p>
		</div>';
	} else {
		$content .= '<p><a href="?page=quick-interest-slider/settings.php&tab=upgrade">'.__('Upgrade to Pro', 'quick-interest-slider').'</a> 
		'.__('to access this option', 'quick-interest-slider').'.</p>';
	}
	
	$content .= '</fieldset>';
	
	// Amount slider
	$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	<h2>'.__('Amount Slider Settings', 'quick-interest-slider').'</h2>';
	
	if ($qppkey['authorised']) $content .='<p'.$hideline.'>'.__('Amount Slider Label', 'quick-interest-slider').':<input type="text" name="loanlabel" . value ="' . $settings['loanlabel'] . '" /></p>
	<p'.$hideline.'><input type="checkbox" name="loanhelp"  value="checked" ' . $settings['loanhelp'] . '/> '.__('Add tooltip', 'quick-interest-slider').' ('.__('displays to the right of the label', 'quick-interest-slider').').</p>
	<p'.$hideline.'>'.__('Tooltip content', 'quick-interest-slider').':<input type="text" name="loaninfo" . value ="' . $settings['loaninfo'] . '" /></p>';
	
	$content .='<p>'.__('Minimum value', 'quick-interest-slider').': ' . $settings['currency'] . '<input type="text" style="width:5em;" name="loanmin" . value ="' . $settings['loanmin'] . '" />&nbsp;&nbsp;&nbsp;
	'.__('Maximum value', 'quick-interest-slider').': ' . $settings['currency'] . '<input type="text" style="width:5em;" name="loanmax" . value ="' . $settings['loanmax'] . '" />&nbsp;&nbsp;&nbsp;
	'.__('Initial value', 'quick-interest-slider').': ' . $settings['currency'] . '<input type="text" style="width:5em;" name="loaninitial" . value ="' . $settings['loaninitial'] . '" />&nbsp;&nbsp;&nbsp;
	'.__('Step', 'quick-interest-slider').': ' . $settings['currency'] . '<input type="text" style="width:5em;" name="loanstep" . value ="' . $settings['loanstep'] . '" /></p>
	
	</fieldset>';
	
	// Term slider
	$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
	<h2>'.__('Term Slider Settings', 'quick-interest-slider').'</h2>';
	
	$content .='<p'.$hideline.'>'.__('Term Slider Label', 'quick-interest-slider').':<input type="text" name="termlabel" . value ="' . $settings['termlabel'] . '" /></p>';
	
	if ($qppkey['authorised']) {
		$content .= '<p'.$hideline.'><input type="checkbox" name="periodhelp"  value="checked" ' . $settings['periodhelp'] . '/> '.__('Add tooltip', 'quick-interest-slider').' ('.__('displays to the right of the label', 'quick-interest-slider').').</p>';
		$content .= '<p'.$hideline.'>'.__('Tooltip content', 'quick-interest-slider').':<input type="text" name="periodinfo" . value ="' . $settings['periodinfo'] . '" /></p>';
	}
	
	$content .='<p><b>'.__('Loan Period', 'quick-interest-slider').':</b> <input type="radio" name="period" value="days" ' . $days . ' />'.__('Days', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="period" value="weeks" ' . $weeks . ' />'.__('Weeks', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="period" value="months" ' . $months . ' />'.__('Months', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="period" value="years" ' . $years . ' />'.__('Years', 'quick-interest-slider').'</p>
	<p><b>'.__('Term labels', 'quick-interest-slider').'</b> '.__('Singular', 'quick-interest-slider').': <input type="text" style="width:6em" name="singleperiodlabel" . value ="' . $settings['singleperiodlabel'] . '" />&nbsp;&nbsp;&nbsp;
	'.__('Plural', 'quick-interest-slider').': <input type="text" style="width:6em" name="periodlabel" . value ="' . $settings['periodlabel'] . '" /> <span class = "description">('.__('This will replace the word', 'quick-interest-slider').' \''.$settings['period'].'\' '.__('with your own labels', 'quick-interest-slider').')</span>.</p>';
	
	/*
		Start tabination
	*/

	if ($qppkey['authorised']) {
		$content .= '<div class="qis-tabination">';
		
		$content .= '
		<ul>
			<li '.(($settings['terminterface'] == 'slider')? 'class="selected"':'').'><input type="checkbox" class="label-radio" name="terminterface" id="usetermslider" value="slider" '.(($settings['terminterface'] == 'slider')? 'checked="checked "':'').'/> <label for="usetermslider">Slider</label><div class="blocker"></div></li>
			<li '.(($settings['terminterface'] == 'button')? 'class="selected"':'').'><input type="checkbox" class="label-radio" name="terminterface" id="usetermbuttons" value="button" '.(($settings['terminterface'] == 'button')? 'checked="checked "':'').'/> <label for="usetermbuttons">Buttons</label><div class="blocker"></div></li>
		</ul>';
		
		$content .= '<div class="qis-tabination-tab" id="tab-usetermslider">';
	} else {
		$content .= '<p><input type="checkbox" name="periodslider"  value="checked" ' . $settings['periodslider'] . '/> '.__('Use Term slider', 'quick-interest-slider').'</p>';
	}

	$content .='<p>'.__('Minimum term', 'quick-interest-slider').': <input type="text" style="width:5em;" name="periodmin" . value ="' . $settings['periodmin'] . '" /> ' . $settings['period'] . '&nbsp;&nbsp;&nbsp;
		'.__('Maximum term', 'quick-interest-slider').': <input type="text" style="width:5em;" name="periodmax" . value ="' . $settings['periodmax'] . '" /> ' . $settings['period'] . '&nbsp;&nbsp;&nbsp;
		'.__('Initial term', 'quick-interest-slider').': <input type="text" style="width:5em;" name="periodinitial" . value ="' . $settings['periodinitial'] . '" /> ' . $settings['period'] . '&nbsp;&nbsp;&nbsp;
		'.__('Step', 'quick-interest-slider').': <input type="text" style="width:5em;" name="periodstep" . value ="' . $settings['periodstep'] . '" /> ' . $settings['period'] . '</p>';
		
	if ($qppkey['authorised']) {
		$content .= '</div>';
	
		$untValue = $settings['buttonunits'];
					
		$week		= (($untValue == 'weeks')? 'selected="selected" ':'');
		$month		= (($untValue == 'months')? 'selected="selected" ':'');
		$year		= (($untValue == 'years')? 'selected="selected" ':'');
		
		$content .= '<div class="qis-tabination-tab" id="tab-usetermbuttons">';
		
		$content .= '<table>';
		$content .= '<tr><td style="width:20%;"><strong>'.__('Loan Period', 'quick-interest-slider').'</strong></td><td><select style="width:20%;" onchange="jQuery(\'.settings-button-units\').text(this.value)" name="buttonunits">
					<option value="weeks" '.$week.'>Weeks</option>
					<option value="months" '.$month.'>Months</option>
					<option value="years" '.$year.'>Years</option>
				</select></td></tr>
				<tr><td><strong>'.__('Button Values', 'quick-interest-slider').'</strong></td><td>';
				
		for ($i = 0; $i < 4; $i++) {
					
			$numValue = $settings['termbutton'.$i];
					
			$content .= <<<usetermbutton
			<input type="text" style="width:2em;" name="termbutton{$i}" value="{$settings['termbutton'.$i]}" />&nbsp;<span class="settings-button-units">{$settings['buttonunits']}</span>&nbsp;&nbsp;
usetermbutton;

		}
		$content .= '</td></tr></table>';
		$content .= '</div>';
		
		// Tabination Closure
		$content .= '</div>';
	
	}
	$content .= '</fieldset>';
	
	$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
	<h2>'.__('Input Options', 'quick-interest-slider').'</h2>
	<p><input type="radio" name="textinputs" value="slider" ' . $slider . ' />'.__('Slider Values Only', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="textinputs" value="text" ' . $text . ' />'.__('Text Input Only', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="textinputs" value="both" ' . $both . ' />&nbsp;'.__('Slider and Text Inputs', 'quick-interest-slider').'</p>
	
	<div><input type="checkbox" name="buttons" value="true" id="qis_buttons" '.(($settings['buttons'])? 'checked="checked"':'').'/> <label for="qis_buttons">'.__('Add increase/decrease buttons to slider', 'quick-interest-slider').' ('.__('does not work on text inputs', 'quick-interest-slider').').</label></div>
	
	</fieldset>';
	
	$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">';
	
	if ($qppkey['authorised']) {
		
		// Downpayments Type Selector
		$content .='<h2>'.__('Downpayments', 'quick-interest-slider').'</h2>
		<p>'.__('Subtract a downpayment amount from the principle before processing fees and interest are calculated', 'quick-interest-slider').'.</p>';
		
		// Top selector
		$dps	= (($settings['usedownpaymentslider'] == 'checked')? ['li' => 'selected','input' => 'selected="selected"']:['li' => '','input' => '']);
		$dpp	= (($settings['usedownpayment'] == 'checked')? ['li' => 'selected','input' => 'selected="selected"']:['li' => '','input' => '']);
		
		$content .= "<ul class='qisdownpayment'>
			<li class='{$dps['li']}'><input type='checkbox' class='qis-downpayment-input' name='usedownpaymentslider' value='checked' " . $settings['usedownpaymentslider'] . "/> <input class='{$dps['input']}' style='display: none;' type='radio' id='qisdownpaymentslider' name='downpaymentdisplay' value='slider' />Slider</li>
			<li class='{$dpp['li']}'><input type='checkbox' class='qis-downpayment-input' name='usedownpayment'  value='checked' " . $settings['usedownpayment'] . "/> <input class='{$dpp['input']}' style='display: none;' type='radio' id='qisdownpaymentpreset' name='downpaymentdisplay' value='preset' />Preset Value</li>
		</ul>";

		// Containing Div
		$content .= '<div id="qis-downpayment-div">';
		
		// Downpayment Slider
		$content .='<div class="qis-downpayment" id="qis-downpayment-slider">
		<p>'.__('Calculates the downpayment from a slider value', 'quick-interest-slider').'.</p>
		<p>'.__('Downpayment Slider Label', 'quick-interest-slider').':<input type="text" name="downpaymentlabel" . value ="' . $settings['downpaymentlabel'] . '" /></p>
		<p><input type="checkbox" name="downpaymenthelp"  value="checked" ' . $settings['downpaymenthelp'] . '/> '.__('Add tooltip', 'quick-interest-slider').' ('.__('displays to the right of the label', 'quick-interest-slider').').</p>
		<p>'.__('Tooltip content', 'quick-interest-slider').':<input type="text" name="downpaymentinfo" . value ="' . $settings['downpaymentinfo'] . '" /></p>
		<p>'.__('Minimum rate', 'quick-interest-slider').': ' . $settings['currency'] . '<input type="text" style="width:5em;" name="downpaymentmin" . value ="' . $settings['downpaymentmin'] . '" />&nbsp;&nbsp;&nbsp;
		'.__('Maximum rate', 'quick-interest-slider').': ' . $settings['currency'] . '<input type="text" style="width:5em;" name="downpaymentmax" . value ="' . $settings['downpaymentmax'] . '" />&nbsp;&nbsp;&nbsp;
		'.__('Initial rate', 'quick-interest-slider').': ' . $settings['currency'] . '<input type="text" style="width:5em;" name="downpaymentinitial" . value ="' . $settings['downpaymentinitial'] . '" />&nbsp;&nbsp;&nbsp;
		'.__('Step', 'quick-interest-slider').': ' . $settings['currency'] . '<input type="text" style="width:5em;" name="downpaymentstep" . value ="' . $settings['downpaymentstep'] . '" /></p></div>';
		
		// Downpayment Presets
		$content .='<div class="qis-downpayment" id="qis-downpayment-preset"><p>'.__('Calculates the downpayment from a fixed value and/or a percantege of the principle', 'quick-interest-slider').'.</p>
		<p>'.__('Fixed value', 'quick-interest-slider').': ' . $settings['currency'] . '<input type="text" style="width:4em;" name="downpaymentfixed" . value ="' . $settings['downpaymentfixed'] . '" />&nbsp;&nbsp;&nbsp;'.__('Percentage value', 'quick-interest-slider').': <input type="text" style="width:4em;" name="downpaymentpercent" . value ="' . $settings['downpaymentpercent'] . '" />%</p></div>';
		
		$content .= "</div>";

	} else {
		$content .= '<p><a href="?page=quick-interest-slider/settings.php&tab=upgrade">'.__('Upgrade to Pro', 'quick-interest-slider').'</a> 
		'.__('to access this option', 'quick-interest-slider').'.</p>';
	}
	
	$content .='</fieldset>';
	
	$content .='<fieldset id="interest" style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
	<h2>'.__('Interest Rates', 'quick-interest-slider').'</h2>
	
	<table style="width:40%;">
	
	<tr>
	<th></th>
	<th>'.__('Rate', 'quick-interest-slider').'</th>
	<th colspan="2">'.__('Trigger by:', 'quick-interest-slider').'</th>';
	if ($settings['dae']) $content .='<th>DAE</th>';
	$content .='</tr>
	
	<tr>
	<td></td>
	<td></td>
	<td><input type="radio" name="triggertype" value="periodtrigger" '.$periodtrigger.' />'.__('Period', 'quick-interest-slider').'</td>
	<td><input type="radio" name="triggertype" value="amounttrigger" '.$amounttrigger.' />'.__('Amount', 'quick-interest-slider').'</td>';
	if ($settings['dae']) $content .='<td></td>';
	$content .='</tr>

	<tr>
	<td>Primary</td>
	<td><input type="text" style="width:3em;" name="triggers[0][rate]" value ="' . $settings['triggers'][0]['rate'] . '" />%</td>
	<td><input type="hidden" name="triggers[0][trigger]" value ="0" /></td>
	<td><input type="hidden" name="triggers[0][amttrigger]" value ="0" /></td>';
	if ($settings['dae']) $content .='<td><input type="text" style="width:3em;" name="triggers[0][dae]" value ="' . $settings['triggers'][0]['dae'] . '" />%</td>';
	$content .='</tr>
	
	<tr>
	<td>Secondary</td>
	<td><input type="text" style="width:3em;" name="triggers[1][rate]" value ="' . $settings['triggers'][1]['rate'] . '" />%</td>
	<td><input type="text" style="width:3em;" name="triggers[1][trigger]" value ="' . $settings['triggers'][1]['trigger'] . '" /> ' . $settings['period'] . '</td>
	<td>'.$settings['currency'].'<input type="text" style="width:6em;" name="triggers[1][amttrigger]" value ="' . $settings['triggers'][1]['amttrigger'] . '" /></td>';
	if ($settings['dae']) $content .='<td><input type="text" style="width:3em;" name="triggers[1][dae]" value ="' . $settings['triggers'][1]['dae'] . '" />%</td>';
	$content .='</tr>
	</tr>
	
	<tr'.$hideline.'>
	<td></td>
	<td><input type="text" style="width:3em;" name="triggers[2][rate]" value ="' . $settings['triggers'][2]['rate'] . '" />%</td>
	<td><input type="text" style="width:3em;" name="triggers[2][trigger]" value ="' . $settings['triggers'][2]['trigger'] . '" /> ' . $settings['period'] . '</td>
	<td>'.$settings['currency'].'<input type="text" style="width:6em;" name="triggers[2][amttrigger]" value ="' . $settings['triggers'][2]['amttrigger'] . '" /></td>';
	if ($settings['dae']) $content .='<td><input type="text" style="width:3em;" name="triggers[2][dae]" value ="' . $settings['triggers'][2]['dae'] . '" />%</td>';
	$content .='</tr>
	</tr>

	<tr'.$hideline.'>
	<td></td>
	<td><input type="text" style="width:3em;" name="triggers[3][rate]" value ="' . $settings['triggers'][3]['rate'] . '" />%</td>
	<td><input type="text" style="width:3em;" name="triggers[3][trigger]" value ="' . $settings['triggers'][3]['trigger'] . '" /> ' . $settings['period'] . '</td>
	<td>'.$settings['currency'].'<input type="text" style="width:6em;" name="triggers[3][amttrigger]" value ="' . $settings['triggers'][3]['amttrigger'] . '" /></td>';
	if ($settings['dae']) $content .='<td><input type="text" style="width:3em;" name="triggers[3][dae]" value ="' . $settings['triggers'][3]['dae'] . '" />%</td>';
	$content .='</tr>
	</tr>
	
	<tr'.$hideline.'>
	<td></td>
	<td><input type="text" style="width:3em;" name="triggers[4][rate]" value ="' . $settings['triggers'][4]['rate'] . '" />%</td>
	<td><input type="text" style="width:3em;" name="triggers[4][trigger]" value ="' . $settings['triggers'][4]['trigger'] . '" /> ' . $settings['period'] . '</td>
	<td>'.$settings['currency'].'<input type="text" style="width:6em;" name="triggers[4][amttrigger]" value ="' . $settings['triggers'][4]['amttrigger'] . '" /></td>';
	if ($settings['dae']) $content .='<td><input type="text" style="width:3em;" name="triggers[4][dae]" value ="' . $settings['triggers'][4]['dae'] . '" />%</td>';
	$content .='</tr>
	</tr>
	
	<tr'.$hideline.'>
	<td></td>
	<td><input type="text" style="width:3em;" name="triggers[5][rate]" value ="' . $settings['triggers'][5]['rate'] . '" />%</td>
	<td><input type="text" style="width:3em;" name="triggers[5][trigger]" value ="' . $settings['triggers'][5]['trigger'] . '" /> ' . $settings['period'] . '</td>
	<td>'.$settings['currency'].'<input type="text" style="width:6em;" name="triggers[5][amttrigger]" value ="' . $settings['triggers'][5]['amttrigger'] . '" /></td>';
	if ($settings['dae']) $content .='<td><input type="text" style="width:3em;" name="triggers[5][dae]" value ="' . $settings['triggers'][5]['dae'] . '" />%</td>';
	$content .='</tr>
	</tr>
	
	<tr'.$hideline.'>
	<td></td>
	<td><input type="text" style="width:3em;" name="triggers[6][rate]" value ="' . $settings['triggers'][6]['rate'] . '" />%</td>
	<td><input type="text" style="width:3em;" name="triggers[6][trigger]" value ="' . $settings['triggers'][6]['trigger'] . '" /> ' . $settings['period'] . '</td>
	<td>'.$settings['currency'].'<input type="text" style="width:6em;" name="triggers[6][amttrigger]" value ="' . $settings['triggers'][6]['amttrigger'] . '" /></td>';
	if ($settings['dae']) $content .='<td><input type="text" style="width:3em;" name="triggers[6][dae]" value ="' . $settings['triggers'][6]['dae'] . '" />%</td>';
	$content .='</tr>
	</tr>
	
	</table>
	
	<p><b>'.__('Interest Type', 'quick-interest-slider').':</b> <input type="radio" name="interesttype" value="fixed" ' . $fixed . ' />'.__('Fixed', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="interesttype" value="simple" ' . $simple . ' />'.__('Simple', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="interesttype" value="compound" ' . $compound . ' />'.__('Compound', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="interesttype" value="amortisation" ' . $amortisation . $disabled . ' />'.__('Amortisation (Europe)', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="interesttype" value="amortization" ' . $amortization . $disabled . ' />'.__('Amortization (USA)', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="interesttype" value="mambu" ' . $mambu . $disabled . ' />'.__('Mambu', 'quick-interest-slider').$promessage.'</p>
	<p><a href="https://loanpaymentplugin.com/interest-calculations/" target="_blank">'.__('Interest type explanations', 'quick-interest-slider').'</a><p>
	
	<p>'.__('Repayment Divider', 'quick-interest-slider').': <input type="text" style="width:3em;" name="multiplier" . value ="' . $settings['multiplier'] . '" /> <span class = "description">'.__('Use this if you want to display the repayment amount in a different period to that in the interest rate', 'quick-interest-slider').'. '.__('For example, if your term slider is in years but want to show the repayment amount per month the divider would be 12', 'quick-interest-slider').'. '.__('For a slider in months with weekly repayments the divider would be 4.3', 'quick-interest-slider').'</span>.</p>
	
	<p class="description"'.$hideline.'>'.__('Interest is normally calculated as APR', 'quick-interest-slider').'. '.__('If you want to calculate interest by the period set in the Term Slider Settings uncheck this option', 'quick-interest-slider').'.</p>
	<p'.$hideline.'><input type="checkbox" name="dae"  value="checked" ' . $settings['dae'] . '/>'.__('utilizați Dobânda Anuală Efectivă', 'quick-interest-slider').' ('.__('Romania only', 'quick-interest-slider').')</p>
	
	</fieldset>';
	
	$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">';
	
	if ($qppkey['authorised']) {
		
		// Interest Rate Advanced Options
		$content .= "<h2>Advanced Interest Options</h2>";
		$content .= "<p style='padding-bottom: 10px;'><b>".__('Warning!', 'quick-interest-slider')."</b> ".__('This will disable all interest triggers', 'quick-interest-slider')." ".__('Only the selected Advanced Interest Option will be active.', 'quick-interest-slider')."</p>";
		
		// Top selector
		$iss	= (($settings['interestslider'] == 'checked')? ['li' => 'selected','input' => 'selected="selected"']:['li' => '','input' => '']);
		$ise	= (($settings['interestselector'] == 'checked')? ['li' => 'selected','input' => 'selected="selected"']:['li' => '','input' => '']);
		$isd	= (($settings['interestdropdown'] == 'checked')? ['li' => 'selected','input' => 'selected="selected"']:['li' => '','input' => '']);
		
		$content .= "<ul class='qisinterest'>
			<li class='{$iss['li']}'><input type='checkbox' class='qis-interest-input' name='interestslider'  value='checked' " . $settings['interestslider'] . "/> <input class='{$iss['input']}' style='display: none;' type='radio' id='qisinterestslider' name='interestdisplay' value='slider' />Slider</li>
			<li class='{$isd['li']}'><input type='checkbox' class='qis-interest-input' name='interestdropdown'  value='checked' " . $settings['interestdropdown'] . "/> <input class='{$isd['input']}' style='display: none;' type='radio' id='qisinterestdropdown' name='interestdisplay' value='dropdown' />Dropdown</li>
			<li class='{$ise['li']}'><input type='checkbox' class='qis-interest-input' name='interestselector'  value='checked' " . $settings['interestselector'] . "/> <input class='{$ise['input']}' style='display: none;' type='radio' id='qisinterestselector' name='interestdisplay' value='selector' />Radio Selector</li>
		</ul>
		";
		
		// Containing Div
		$content .= '<div id="qis-interest-div">';
		
		// Interest Slider
		$content .='<div class="qis-interest" id="qis-interest-slider"><h2>'.__('Interest Slider Settings', 'quick-interest-slider').'</h2>
		
		<p>'.__('Interest Slider Label', 'quick-interest-slider').':<input type="text" name="interestlabel" . value ="' . $settings['interestlabel'] . '" /></p>
		<p><input type="checkbox" name="interesthelp"  value="checked" ' . $settings['interesthelp'] . '/> '.__('Add tooltip', 'quick-interest-slider').' ('.__('displays to the right of the label', 'quick-interest-slider').').</p>
		<p>'.__('Tooltip content', 'quick-interest-slider').':<input type="text" name="interestinfo" . value ="' . $settings['interestinfo'] . '" /></p>
		<p>'.__('Minimum rate', 'quick-interest-slider').': <input type="text" style="width:5em;" name="interestmin" . value ="' . $settings['interestmin'] . '" />%&nbsp;&nbsp;&nbsp;
		'.__('Maximum rate', 'quick-interest-slider').': <input type="text" style="width:5em;" name="interestmax" . value ="' . $settings['interestmax'] . '" />%&nbsp;&nbsp;&nbsp;
		'.__('Initial rate', 'quick-interest-slider').': <input type="text" style="width:5em;" name="interestinitial" . value ="' . $settings['interestinitial'] . '" />%&nbsp;&nbsp;&nbsp;
		'.__('Step', 'quick-interest-slider').': <input type="text" style="width:5em;" name="intereststep" . value ="' . $settings['intereststep'] . '" />%</p></div>';
		
		// Interest Rate Selector
		$content .='<div class="qis-interest" id="qis-interest-selector"><h2>'.__('Interest Selector Settings', 'quick-interest-slider').'</h2>
		
		<p>'.__('Interest Selector Label', 'quick-interest-slider').':<input type="text" name="interestselectorlabel" . value ="' . $settings['interestselectorlabel'] . '" /></p>
		<table id="interestselector" style="width:40%;">
		<thead>
			<tr>
				<th>'.__('Rate', 'quick-interest-slider').'</th>
				<th>'.__('Label', 'quick-interest-slider').'</th>
			
			</tr>
		</thead><tbody>';
		
		for ($i = 1; $i < 5; $i++) {
			$content .= '<tr>
			<td><input class="input-symbol" type="text" style="width:3em;" name="interestrate'.$i.'" . value ="' . $settings['interestrate'.$i] . '" />%</td>
			<td><input class="input-name" type="text" style="width:8em;" name="interestname'.$i.'" . value ="' . $settings['interestname'.$i] . '" /></td>
		
			</tr>';
		}
		$content .='</tbody></table></div>';
		
		// Interest Rate Dropdown
		$content .='<div class="qis-interest" id="qis-interest-dropdown"><h2>'.__('Interest Dropdown Settings', 'quick-interest-slider').'</h2>
		
		<p>'.__('Interest Dropdown Label', 'quick-interest-slider').':<input type="text" name="interestdropdownlabel" . value ="' . $settings['interestdropdownlabel'] . '" /></p>
		
		<p><input type="radio" name="interestdropdownlabelposition" value="include" ' . $include . ' />&nbsp'.__('Include label in dropdown list', 'quick-interest-slider').'&nbsp&nbsp&nbsp<input type="radio" name="interestdropdownlabelposition" value="paragraph" ' . $paragraph . ' />&nbsp'.__('Show as label above dropdown', 'quick-interest-slider').'</p>
		
		<p>'.__('Interest Dropdown Values', 'quick-interest-slider').':<input type="text" name="interestdropdownvalues" . value ="' . $settings['interestdropdownvalues'] . '" /> <span class = "description">'.__('Separate values with a comma. eg: 3,4,5', 'quick-interest-slider').'</span>.</p></div>';
		
		$content .= "</div>";

	} else {
		$content .= '<h2>'.__('Interest Rate Sliders and Selectors', 'quick-interest-slider').'</h2>
		<p><a href="?page=quick-interest-slider/settings.php&tab=upgrade">'.__('Upgrade to Pro', 'quick-interest-slider').'</a> 
		'.__('to access this option', 'quick-interest-slider').'.</p>';
	}
	
	$content .= '</fieldset>';
	
	// Processing Fee

	$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
	<h2>'.__('Processing Fee', 'quick-interest-slider').'</h2>
	<p><input type="checkbox" name="adminfee" value="checked" ' . $settings['adminfee'] . '/>'.__('Add a processing fee', 'quick-interest-slider').' '.__('calculated from the amount slider value', 'quick-interest-slider').'.</p>
	<p><b>'.__('Amount Processing fee', 'quick-interest-slider').':</b> <input type="text" style="width:4em;" name="adminfeevalue" . value ="' . $settings['adminfeevalue'] . '" />&nbsp;&nbsp;&nbsp;<input type="radio" name="adminfeetype" value="fixed" ' . $fixed . ' />'.__('Fixed', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;<input type="radio" name="adminfeetype" value="percent" ' . $percent . ' />'.__('Percent', 'quick-interest-slider').'</p>
	
	<p><input type="checkbox" name="termfee" value="checked" ' . $settings['termfee'] . '/>'.__('Add a processing fee', 'quick-interest-slider').' '.__('calculated from the term slider value', 'quick-interest-slider').' ('.__('eg: an amount multiplied by the number of', 'quick-interest-slider').' '. $settings['period'] . ').</p>
	<p><b>'.__('Term Processing fee', 'quick-interest-slider').':</b> <input type="text" style="width:4em;" name="termfeevalue" . value ="' . $settings['termfeevalue'] . '" /> x '.__('number of', 'quick-interest-slider').' '. $settings['period'] . ').</p>
	</p>'.__('Add fees', 'quick-interest-slider').': <input type="radio" name="adminfeewhen" value="beforeinterest" ' . $beforeinterest . ' />'.__('before interest', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;<input type="radio" name="adminfeewhen" value="afterinterest" ' . $afterinterest . ' />'.__('after interest', 'quick-interest-slider').'
	<p>'.__('Minimum fee', 'quick-interest-slider').': <input type="text" style="width:4em;" name="adminfeemin" . value ="' . $settings['adminfeemin'] . '" />&nbsp;&nbsp;&nbsp;'.__('Maximum fee', 'quick-interest-slider').': <input type="text" style="width:4em;" name="adminfeemax" . value ="' . $settings['adminfeemax'] . '" /></p>
	
	</fieldset>';
	
	// Outputs
	
	$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
	<h2>'.__('Output Options', 'quick-interest-slider').'</h2>
	<p'.$hideline.'><input type="checkbox" name="outputlimits"  value="checked" ' . $settings['outputlimits'] . '/>'.__('Show amount/term above slider', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;<input type="checkbox" name="usebubble"  value="1" '.$usebubble.'/>&nbsp;'.__('Use moving bubble', 'quick-interest-slider').'</p>
	<p'.$hideline.'><input type="checkbox" name="maxminlimits"  value="checked" ' . $settings['maxminlimits'] . '/>'.__('Show min and max values above slider', 'quick-interest-slider').'</p>
	<p'.$hideline.'><input type="checkbox" name="nosliderlabel"  value="checked" ' . $settings['nosliderlabel'] . '/>'.__('Hide labels on slider', 'quick-interest-slider').' ('.__('eg: 100 not $100 or 7 not 7 months', 'quick-interest-slider').').</p>
	<p'.$hideline.'><input type="checkbox" name="markers"  value="checked" ' . $settings['markers'] . '/>'.__('Show step markers on slider', 'quick-interest-slider').'</p>
	<p'.$hideline.'><span class = "description">'.__('Small steps will mean lots of marker lines. Use with caution', 'quick-interest-slider').'</span></p>
	<p><input type="checkbox" name="outputrepayments"  value="checked" ' . $settings['outputrepayments'] . '/>
	'.__('Display repayment terms', 'quick-interest-slider').'</p>
	<p><textarea style="width:100%;height:100px;" name="repaymentlabel" label="repaymentlabel" rows="4">' . $settings['repaymentlabel'] . '</textarea><p>
	<p><input type="checkbox" name="outputtotal"  value="checked" ' . $settings['outputtotal'] . '/>'.__('Display total to pay repayment terms', 'quick-interest-slider').'. '.__('Renders as an H2', 'quick-interest-slider').'</p>
	<p><input type="text" name="outputtotallabel"  value ="' . $settings['outputtotallabel'] . '" /></p>
	<p class= "description">'.__('Use &lt;br&gt; or &lt;p&gt;...&lt;/p&gt; to add a new line', 'quick-interest-slider').'. '.__('HTML styling is allowed', 'quick-interest-slider').'.</p>
	<p class="description">'.__('Optional shortcode examples', 'quick-interest-slider').': [repayment], [interest], [total]. <a href="https://loanpaymentplugin.com/shortcodes/shortcodes-in-output-messages/" target="_blank">'.__('Click here to see all shortcodes', 'quick-interest-slider').'</a></p>
	<p class="description">'.__('To display the outputs in a table use the [table] shortcode', 'quick-interest-slider').'. <a href="?page=quick-interest-slider/settings.php&tab=outputs">'.__('Click here to create a table', 'quick-interest-slider').'</a>. <a href="https://loanpaymentplugin.com/features/output-tables/" target="_blank"><em>'.__('Click here for a demo', 'quick-interest-slider').'</a></em>.</p>';
	
	if ($qppkey['authorised']) $content .='<p'.$hideline.'><input type="checkbox" name="outputhelp"  value="checked" ' . $settings['outputhelp'] . '/> '.__('Add tooltip', 'quick-interest-slider').' ('.__('displays to the right of the output content', 'quick-interest-slider').').</p>
	<p'.$hideline.'>'.__('Tooltip content', 'quick-interest-slider').':<input type="text" name="outputinfo" . value ="' . $settings['outputinfo'] . '" /></p>';

	$content .= '<p'.$hideline.'><input type="checkbox" name="outputhide"  value="checked" ' . $settings['outputhide'] . '/>'.__('Hide all output messages if zero value in amount or term', 'quick-interest-slider').'</p>
	</fieldset>';
	
	$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
	<h2>'.__('Output Formats', 'quick-interest-slider').'</h2>
	
	<p><b>'.__('Thousands separator', 'quick-interest-slider').':</b> <input type="radio" name="separator" value="none" ' . $none . ' />None&nbsp;&nbsp;&nbsp;
	<input type="radio" name="separator" value="comma" ' . $comma . ' />Comma&nbsp;&nbsp;&nbsp;
	<input type="radio" name="separator" value="apostrophe" ' . $apostrophe . ' />Apostrophe&nbsp;&nbsp;&nbsp;
	<input type="radio" name="separator" value="dot" ' . $dot . ' />Period&nbsp;&nbsp;&nbsp;
	<input type="radio" name="separator" value="space" ' . $space . ' />Space</p>
	<p class="description">'.__('The period separator changes the decimal to a comma', 'quick-interest-slider').'</p>
	
	<p><b>'.__('Decimals', 'quick-interest-slider').':</b> <input type="radio" name="decimals" value="none" ' . $none . ' />'.__('None', 'quick-interest-slider').' ($1234)&nbsp;&nbsp;&nbsp;
	<input type="radio" name="decimals" value="float" ' . $float . ' />'.__('Floating', 'quick-interest-slider').' ($1234 or $1234.56)&nbsp;&nbsp;&nbsp;
	<input type="radio" name="decimals" value="always" ' . $always . ' />'.__('Always on', 'quick-interest-slider').' ($1234.00 or $1234.56) &nbsp;&nbsp;&nbsp;</p>
	
	<p><b>'.__('Rounding', 'quick-interest-slider').':</b> <input type="radio" name="rounding" value="noround" ' . @$noround . ' />'.__('None', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="rounding" value="tenround" ' . $tenround . ' />'.__('Nearest ten', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="rounding" value="hundredround" ' . $hundredround . ' />'.__('Nearest hundred', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="rounding" value="thousandround" ' . $thousandround . ' />'.__('Nearest thousand', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;<em>'.__('Use With Caution', 'quick-interest-slider').'!</em></p>
	
	<p'.$hideline.'>'.__('Repayment date format', 'quick-interest-slider').':&nbsp;<input type="radio" name="periodformat" value="US" ' . $US . ' />MM'.$settings['dateseperator'].'DD'.$settings['dateseperator'].'YYYY&nbsp;&nbsp;&nbsp;<input type="radio" name="periodformat" value="EU" ' . $EU . ' />DD'.$settings['dateseperator'].'Mmm'.$settings['dateseperator'].'YYYY&nbsp;&nbsp;&nbsp;<input type="radio" name="periodformat" value="monthnum" ' . $monthnum . ' />DD'.$settings['dateseperator'].'MM'.$settings['dateseperator'].'YYYY&nbsp;&nbsp;&nbsp;'.__('Date Seperator', 'quick-interest-slider').': <input type="text" style="width:2em;" name="dateseperator" . value ="' . $settings['dateseperator'] . '" /></p>
	
	</fieldset>';
	
	// Loan Breakdown
	
	$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
	<h2>'.__('Loan Breakdown Graph', 'quick-interest-slider').'</h2>';

	if ($qppkey['authorised']) {
	
		$content .='<p><input type="checkbox" name="usegraph" value="checked" ' . $settings['usegraph'] . '/> '.__('Display the loan breakdown as an image', 'quick-interest-slider').'.</p>
		<p>'.__('Label', 'quick-interest-slider').':<input type="text" name="graphlabel" . value ="' . $settings['graphlabel'] . '" /></p>
		<p>'.__('The image is broken down into Downpayment, Discount, Principle, Processing Fee and Interest', 'quick-interest-slider').'.</p>
		<p>'.__('Labels', 'quick-interest-slider').': 
		<input type="text" name="graphdownpayment" style="width:10em" value ="' . $settings['graphdownpayment'] . '" /> 
		<input type="text" name="graphdiscount" style="width:10em" value ="' . $settings['graphdiscount'] . '" /> 
		<input type="text" name="graphprinciple" style="width:10em" value ="' . $settings['graphprinciple'] . '" /> 
		<input type="text" name="graphprocessing" style="width:10em" value ="' . $settings['graphprocessing'] . '" /> 
		<input type="text" name="graphinterest" style="width:10em" value ="' . $settings['graphinterest'] . '" /></p>
		<p>'.__('Colours are set on the styling page', 'quick-interest-slider').'.</p>';

	} else {
		$content .= '<p><a href="?page=quick-interest-slider/settings.php&tab=upgrade">'.__('Upgrade to Pro', 'quick-interest-slider').'</a> 
		'.__('to access this option', 'quick-interest-slider').'.</p>';
	}
	
	$content .='</fieldset>';
	
	// Application Form
	
	if ($qppkey['authorised']) $content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	<h2>'.__('Apply Now Button', 'quick-interest-slider').'</h2>
	<p><input type="checkbox" name="applynow"  value="checked" ' . $settings['applynow'] . '/> '.__('Add an Apply now button to the form', 'quick-interest-slider').'<br>
	<span class="description">'.__('This does not process the form data', 'quick-interest-slider').'. '.__('All the button does is send the visitor to the URL given below', 'quick-interest-slider').'.</span></p>
	<p>'.__('Apply now label', 'quick-interest-slider').':<input type="text" name="applynowlabel" value ="' . $settings['applynowlabel'] . '" /></p>
	<p>'.__('Form action URL', 'quick-interest-slider').':<input type="text" name="applynowaction" value ="' . $settings['applynowaction'] . '" /></p>
	<p><input type="checkbox" name="applynowquery"  value="checked" ' . $settings['applynowquery'] . '/> '.__('Append query to URL', 'quick-interest-slider').'<p>
	<p>'.__('query', 'quick-interest-slider').':<input type="text" name="querystructure" value ="' . $settings['querystructure'] . '" /></p>
	</fieldset>';
	
	
	$content .= '<p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="Save Changes" /> <input type="submit" name="Reset" class="button-secondary" value="Reset" onclick="return window.confirm( \'Are you sure you want to reset the settings?\' );"/></p>
	</div>
	</form>';
	echo $content;
}

function qis_styles() {
	
	$pixel=$none=$shadow=$theme=$color=$content=false;
	$plain = $hideline = $advanced = $one = $two = $three = $four = $five = false;
	
	$formnumber = get_option('qis_formnumber');
	$theform  = (!$formnumber || $formnumber == 1) ? 1 : $formnumber;
	
	if( isset( $_POST['Submit'])) {
		$options = array(
			'nostyles',
			'nocustomstyles',
			'border',
			'form-border-thickness',
			'form-border-color',
			'form-border-radius',
			'form-padding',
			'width',
			'widthtype',
			'background',
			'backgroundhex',
			'backgroundimage',
			'slider-label-size',
			'slider-label-colour',
			'slider-label-margin',
			'slider-thickness',
			'slider-background',
			'slider-revealed',
			'handle-size',
			'handle-background',
			'handle-border',
			'handle-corners',
			'handle-thickness',
			'output-size',
			'output-colour',
			'toplinefont',
			'toplinecolour',
			'slideroutputfont',
			'slideroutputcolour',
			'interestfont',
			'interestcolour',
			'interestmargin',
			'totalfont',
			'totalcolour',
			'totalmargin',
			'slider-block',
			'tooltipcolour',
			'tooltipbackground',
			'tooltipborderthickness',
			'tooltipbordercolour',
			'tooltipcorner',
			'buttoncolour',
			'floatoutput',
			'floatpercentage',
			'floatbreakpoint',
			'floatcustom',
			'graphdownpayment',
			'graphprinciple',
			'graphprocessing',
			'graphinterest',
			'graphdiscount'
		);
		foreach ( $options as $item) {
			$style[$item] = stripslashes(@$_POST[$item]);
			$style[$item] = filter_var($style[$item],FILTER_SANITIZE_STRING);
		}
		update_option( 'qis_style', $style);
		qis_admin_notice("The slider styles have been updated.");
		}
	
	if( isset( $_POST['Reset'])) {
		delete_option('qis_style');
		qis_admin_notice("The slider styles have been reset.");
		}
	
	if( isset( $_POST['advanced'])) {
		update_option( 'qis_advanced', true);
	}
	
	if( isset( $_POST['basic'])) {
		delete_option( 'qis_advanced');
	}

	$style = qis_get_stored_style();
	
	$qppkey = get_option('qpp_key');
	
	${$style['widthtype']} = 'checked';
	${$style['border']} = 'checked';
	${$style['background']} = 'checked';
	${$theform} = 'checked';
	
	$noshow = get_option('qis_advanced') ? true : false;
	if ($noshow==true) {
		$advanced = "display:none;";
		$hideline = ' style="display:none;"';
	}

	$content .='<form method="post" action="">
	<div class="qis-options">';
	
	$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
	<h2>'.__('Form Layout', 'quick-interest-slider').'</h2>';
	
	if ($noshow) $content .= '<p>'.__('The settings below will get you started', 'quick-interest-slider').'. '.__('To see all the settings (and there are a lot of them) click the button below', 'quick-interest-slider').'.</p>
	<p><input type="submit" name="basic" class="button-secondary" value="Show all settings" /></p>';
	else $content .= '<p><input type="submit" name="advanced" class="button-secondary" value="Hide advanced settings" /></p>';
	
	$content .= '<p'.$hideline.'><input type="checkbox" name="nostyles"' . $style['nostyles'] . ' value="checked" /> '.__('Remove all styles', 'quick-interest-slider').'</p>
	<p'.$hideline.'><input type="checkbox" name="nocustomstyles"' . $style['nocustomstyles'] . ' value="checked" /> '.__('Do not use custom styles', 'quick-interest-slider').'</p>
	
	<table width="100%">
	
	<tr>
	<td colspan="2"><p><b>'.__('Form Width', 'quick-interest-slider').'</b> <input type="radio" name="widthtype" value="percent" ' . (isset($percent)? $percent : '' ) . ' /> 100% ('.__('fill the available space', 'quick-interest-slider').')&nbsp;&nbsp;&nbsp;
	<input type="radio" name="widthtype" value="pixel" ' . (isset($pixel)? $pixel : '' ) . ' /> Pixel ('.__('fixed', 'quick-interest-slider').'): <input type="text" style="width:4em" label="width" name="width" value="' . $style['width'] . '" /> '.__('use px, em or %. Default is px', 'quick-interest-slider').'.</p></td>
	</tr>
	
	<tr>
	<td style="width:20%">'.__('Form Border', 'quick-interest-slider').'</td>
	<td><input type="radio" name="border" value="none" ' . (isset($none)? $none : '' ) . ' />'.__('No border', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="border" value="plain" ' . $plain . ' />'.__('Plain Border', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="border" value="shadow" ' . $shadow . ' />'.__('Shadowed Border', 'quick-interest-slider').'</td>
	</tr>
	
	<tr>
	<td>'.__('Border Thickness', 'quick-interest-slider').'</td>
	<td><input type="text" style="width:3em" name="form-border-thickness" value="' . $style['form-border-thickness'] . '" />&nbsp;px</td>
	</tr>
	
	<tr>
	<td>'.__('Border Color', 'quick-interest-slider').'</td>
	<td><input type="text" class="qis-color" name="form-border-color" value="' . $style['form-border-color'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Border Radius', 'quick-interest-slider').'</td>
	<td><input type="text" style="width:3em" name="form-border-radius" value="' . $style['form-border-radius'] . '" />&nbsp;px</td>
	</tr>
	
	<tr>
	<td>'.__('Padding', 'quick-interest-slider').'</td>
	<td><input type="text" style="width:3em" name="form-padding" value="' . $style['form-padding'] . '" />&nbsp;px</td>
	</tr>
	
	<tr>
	<td>'.__('Background Colour', 'quick-interest-slider').'</td>
	<td><input type="radio" name="background" value="white" ' . (isset($white)? $white : '' ) . ' /> '.__('White', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="background" value="theme" ' . (isset($theme)? $theme : '' ) . ' /> '.__('Use theme colours', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="background" value="color" ' . (isset($color)? $color : '' ). ' /> '.__('Select colour', 'quick-interest-slider').'</td>
	</tr>
	
	<tr>
	<td></td>
	<td><input type="text" class="qis-color" label="background" name="backgroundhex" value="' . $style['backgroundhex'] . '" /></td>
	</tr>
	
	<tr'.$hideline.'>
	<td>'.__('Background Image', 'quick-interest-slider').'</td>
	<td>
	<input id="qis_background_image" type="text" name="backgroundimage" value="' . $style['backgroundimage'] . '" />
	<input id="qis_upload_background_image" class="button" type="button" value="Upload Image" /><br>
	<span class="description">'.__('Leave blank to use plain colours', 'quick-interest-slider').'</span></td>
	</tr>

	</table>

	</fieldset>
	
	<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
	
	<h2>'.__('Slider Styles', 'quick-interest-slider').'</h2>
	
	<table width="100%">';
	
	if ($qppkey['authorised']) $content .='<tr>
	<td colspan="2"><h3>'.__('Slider labels', 'quick-interest-slider').'</h3></td>
	</tr>
	
	<tr>
	<td style="width:20%">'.__('Font Size', 'quick-interest-slider').'</td>
	<td><input type="text" style="width:3em" name="slider-label-size" value="' . $style['slider-label-size'] . '" />&nbsp;em</td>
	</tr>
	
	<tr>
	<td>'.__('Font Colour', 'quick-interest-slider').'</td>
	<td><input type="text" class="qis-color" name="slider-label-colour" value="' . $style['slider-label-colour'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Margin', 'quick-interest-slider').'</td>
	<td><input type="text" style="width:10em" name="slider-label-margin" value="' . $style['slider-label-margin'] . '" /></td>
	</tr>';
	
	$content .='<tr>
	<td colspan="2"><h3>'.__('Slider output', 'quick-interest-slider').'</h3></td>
	</tr>
	
	<tr>
	<td style="width:20%">'.__('Font Size', 'quick-interest-slider').'</td>
	<td><input type="text" style="width:3em" name="slideroutputfont" value="' . $style['slideroutputfont'] . '" />&nbsp;em</td>
	</tr>
	
	<tr>
	<td>'.__('Font Colour', 'quick-interest-slider').'</td>
	<td><input type="text" class="qis-color" name="slideroutputcolour" value="' . $style['slideroutputcolour'] . '" /></td>
	</tr>
	
	<tr>
	<td colspan="2"><h3>'.__('Max and min values', 'quick-interest-slider').'</h3></td>
	</tr>

	<tr>
	<td>'.__('Font Size', 'quick-interest-slider').'</td>
	<td><input type="text" style="width:3em" name="toplinefont" value="' . $style['toplinefont'] . '" />&nbsp;em</td>
	</tr>
	
	<tr>
	<td>'.__('Font Colour', 'quick-interest-slider').'</td>
	<td><input type="text" class="qis-color" name="toplinecolour" value="' . $style['toplinecolour'] . '" /></td>
	</tr>
	
	<tr>
	<td colspan="2"><h3>'.__('Slider Bar', 'quick-interest-slider').'</h3></td>
	</tr>
	
	<tr>
	<td>'.__('Thickness', 'quick-interest-slider').'</td>
	<td><input type="text" style="width:3em" name="slider-thickness" value="' . $style['slider-thickness'] . '" />&nbsp;em</td>
	</tr>
	
	<tr>
	<td>'.__('Normal Background', 'quick-interest-slider').'</td>
	<td><input type="text" class="qis-color" name="slider-background" value="' . $style['slider-background'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Revealed Background', 'quick-interest-slider').'</td>
	<td><input type="text" class="qis-color" name="slider-revealed" value="' . $style['slider-revealed'] . '" /></td>
	</tr>

	<tr>
	<td>'.__('Square Slider', 'quick-interest-slider').'</td>
	<td><input type="checkbox" name="slider-block" ' . $style['slider-block'] . ' value="checked" /> (this option removes the round ends of the slider and makes the handle the same height as the slider)</td>
	</tr>
	
	<tr>
	<td colspan="2"><h3>'.__('Slider Handle', 'quick-interest-slider').'</h3></td>
	</tr>
	
	<tr>
	<td>'.__('Size', 'quick-interest-slider').'</td>
	<td><input type="text" style="width:3em" name="handle-size" value="' . $style['handle-size'] . '" /> em</td>
	</tr>
	
	<tr>
	<td>'.__('Background', 'quick-interest-slider').'</td>
	<td><input type="text" class="qis-color" name="handle-background" value="' . $style['handle-background'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Border colour', 'quick-interest-slider').'</td>
	<td><input type="text" class="qis-color" name="handle-border" value="' . $style['handle-border'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Border Thickness', 'quick-interest-slider').'</td>
	<td><input type="text" style="width:3em" name="handle-thickness" value="' . $style['handle-thickness'] . '" />&nbsp;px</td>
	</tr>
	
	<tr>
	<td>'.__('Corners', 'quick-interest-slider').'</td>
	<td><input type="text" style="width:2em" name="handle-corners" value="' . $style['handle-corners'] . '" />&nbsp;%</td>
	</tr>
	
	</table>

	</fieldset>
	
	<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
	<h2>'.__('Output Styles', 'quick-interest-slider').'</h2>
	<table width="100%">
	
	<tr>
	<td colspan="2"><h3>'.__('Interest/Repayments', 'quick-interest-slider').'</h3></td>
	</tr>
	
	<tr>
	<td width="20%">'.__('Font Size', 'quick-interest-slider').'</td>
	<td><input type="text" style="width:3em" name="interestfont" value="' . $style['interestfont'] . '" />&nbsp;em</td>
	</tr>
   
	<tr>
	<td>'.__('Font Colour', 'quick-interest-slider').'</td>
	<td><input type="text" class="qis-color" name="interestcolour" value="' . $style['interestcolour'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Margin', 'quick-interest-slider').'</td>
	<td><input type="text" style="width:10em" name="interestmargin" value="' . $style['interestmargin'] . '" /></td>
	</tr>
	
	<tr>
	<td colspan="2"><h3>'.__('Total to Pay', 'quick-interest-slider').'</h3></td>
	</tr>
	
	<tr>
	<td>'.__('Font Size', 'quick-interest-slider').'</td>
	<td><input type="text" style="width:3em" name="totalfont" value="' . $style['totalfont'] . '" />&nbsp;em</td>
	</tr>
	
	<tr>
	<td>'.__('Font Colour', 'quick-interest-slider').'</td>
	<td><input type="text" class="qis-color" name="totalcolour" value="' . $style['totalcolour'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Margin', 'quick-interest-slider').'</td>
	<td><input type="text" style="width:10em" name="totalmargin" value="' . $style['totalmargin'] . '" /></td>
	</tr>
	
	<tr>
	<td colspan="2"><h3>'.__('Visual Indicator', 'quick-interest-slider').'</h3></td>
	</tr>
	
	<tr>
	<td>'.__('Downpayment Colour', 'quick-interest-slider').'</td>
	<td><input type="text" class="qis-color" name="graphdownpayment" value="' . $style['graphdownpayment'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Loan Colour', 'quick-interest-slider').'</td>
	<td><input type="text" class="qis-color" name="graphprinciple" value="' . $style['graphprinciple'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Processing Colour', 'quick-interest-slider').'</td>
	<td><input type="text" class="qis-color" name="graphprocessing" value="' . $style['graphprocessing'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Interest Colour', 'quick-interest-slider').'</td>
	<td><input type="text" class="qis-color" name="graphinterest" value="' . $style['graphinterest'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Discount Colour', 'quick-interest-slider').'</td>
	<td><input type="text" class="qis-color" name="graphdiscount" value="' . $style['graphdiscount'] . '" /></td>
	</tr>
	
	</table>

	</fieldset>';
	
	if ($qppkey['authorised']) $content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
	<h2>'.__('Tooltips', 'quick-interest-slider').'</h2>
	<table width="100%">
	   
	<tr>
	<td width="20%">'.__('Font Colour', 'quick-interest-slider').'</td>
	<td><input type="text" class="qis-color" name="tooltipcolour" value="' . $style['tooltipcolour'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Background Colour', 'quick-interest-slider').'</td>
	<td><input type="text" class="qis-color" name="tooltipbackground" value="' . $style['tooltipbackground'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Border Colour', 'quick-interest-slider').'</td>
	<td><input type="text" class="qis-color" name="tooltipbordercolour" value="' . $style['tooltipbordercolour'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Border Thickness', 'quick-interest-slider').'</td>
	<td><input type="text" style="width:3em" name="tooltipborderthickness" value="' . $style['tooltipborderthickness'] . '" />&nbsp;em</td>
	</tr>
	
	<tr>
	<td>'.__('Corner Radius', 'quick-interest-slider').'</td>
	<td><input type="text" style="width:3em" name="tooltipcorner" value="' . $style['tooltipcorner'] . '" />&nbsp;em</td>
	</tr>
	
	</table>

	</fieldset>
	
	<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
	
	<h2>'.__('Increase/Decrease Buttons', 'quick-interest-slider').'</h2>
	
	<table width="100%">
	   
	<tr>
	<td width="20%">'.__('Font Colour', 'quick-interest-slider').'</td>
	<td><input type="text" class="qis-color" name="buttoncolour" value="' . $style['buttoncolour'] . '" /></td>
	</tr>

	</table>
	
	</fieldset>';
	
	$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
	
	<h2>'.__('Float outputs on the right', 'quick-interest-slider').'</h2>
	
	<table>
	<tr>
	<td width="20%">'.__('Use Float', 'quick-interest-slider').'</td>
	<td><input type="checkbox" name="floatoutput" ' . $style['floatoutput'] . ' value="checked" /> <span class="description">'.__('All the output messages will display to the right of the sliders', 'quick-interest-slider').'</span></td>
	</tr>
	
	<tr>
	<td>'.__('Width of sliders section', 'quick-interest-slider').'</td>
	<td><input type="text" style="width:3em" name="floatpercentage" value="' . $style['floatpercentage'] . '" />&nbsp;%</td>
	</tr>
	
	<tr>
	<td>'.__('Breakpoint', 'quick-interest-slider').'</td>
	<td><input type="text" style="width:3em" name="floatbreakpoint" value="' . $style['floatbreakpoint'] . '" />&nbsp;px <span class="description">'.__('The screen width at which the outputs move back below the sliders', 'quick-interest-slider').'</span></td>
	</tr>
	
	<tr>
	<td>'.__('Custom CSS', 'quick-interest-slider').'</td>
	<td><input type="text" name="floatcustom" value="' . $style['floatcustom'] . '" /><span class="description">'.__('These styles only apply to the output section', 'quick-interest-slider').'.</span></td>
	</tr>
	
	</table>

	</fieldset>';

	$content .='<p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="Save Changes" /> <input type="submit" name="Reset" class="button-secondary" value="Reset" onclick="return window.confirm( \'Are you sure you want to reset the styles?\' );"/></p>
	</div>
	</form>';
	echo $content;
}

function qis_register (){
 
	$alternate=$width=$paragraph=$labeltype=$processpercent=$processfixed=$qis_apikey=$corner=$square=$round = false;
	$hideline=$advanced = $one = $two = $three = $four = $five = false;
	$include = $paragraph = false;
	
	$formnumber = get_option('qis_formnumber');
	$theform  = (!$formnumber || $formnumber == 1) ? 1 : $formnumber;
	
	if( isset( $_POST['Submit']) && check_admin_referer("save_qis")) {
		$options = array(
			'application',
			'alwayson',
			'formwidth',
			'usename',
			'useemail',
			'usetelephone',
			'usemessage',
			'usecaptcha',
			'useaddinfo',
			'usecopy',
			'usecompany',
			'usenumber',
			'useaddress',
			'useattachment',
			'formborder',
			'sendemail',
			'subject',
			'subjecttitle',
			'subjectdate',
			'title',
			'blurb',
			'yourname',
			'youremail',
			'yourtelephone',
			'yourmessage',
			'yourcaptcha',
			'addinfo',
			'yourcompany',
			'youraddress',
			'yournumber',
			'qissubmit',
			'errortitle',
			'errorblurb',
			'replytitle',
			'replyblurb',
			'copyblurb',
			'sort',
			'useterms',
			'termslabel',
			'termsurl',
			'termstarget',
			'nonotifications',
			'copychecked',
			'labeltype',
			'notificationsubject',
			'page',
			'ipaddress',
			'url',
			'usechecks',
			'checkboxeslabel',
			'check1',
			'check2',
			'check3',
			'usedropdown',
			'dropdownlabelposition',
			'dropdownlabel',
			'dropdownlist',
			'dropdownignorefirst',
			'usedropdown2',
			'dropdown2labelposition',
			'dropdown2label',
			'dropdown2list',
			'dropdown2ignorefirst',
			'useradio',
			'radiolabel',
			'radiolist',
			'useconsent',
			'consentlabel',
			'storedata',
			'qis_redirect_url',
			'qis_redirect_name',
			'borrowlabel',
			'forlabel',
			'offset',
			'loginrequired',
			'loginblurb',
			'loginlink',
			'blockduplicates',
			'attachmentlabel',
			'linktoattachment',
			'attach_size',
			'attach_type',
			'attach_error_size',
			'attach_error_type',
			'repaymentdata'
		);
		foreach ($options as $item) {
			$register[$item] = stripslashes( @$_POST[$item]);
			$register[$item] = filter_var($register[$item],FILTER_SANITIZE_STRING);
		}
		update_option('qis_register'.$theform, $register);

		qis_admin_notice(__('The registration form settings have been updated', 'quick-interest-slider'));
	}
	if( isset( $_POST['Reset']) && check_admin_referer("save_qis")) {
		delete_option('qis_register'.$theform);
		qis_admin_notice(__('The registration form settings have been reset', 'quick-interest-slider'));
	}
	
	if( isset( $_POST['changeform'])) {
		$qisform = $theform = $_POST['calculator'];
		update_option( 'qis_formnumber', $qisform);
		$register = qis_get_stored_register($qisform);
	}
	
	if( isset( $_POST['advanced'])) {
		update_option( 'qis_advanced', true);
	}
	
	if( isset( $_POST['basic'])) {
		delete_option( 'qis_advanced');
	}

	if( isset( $_POST['Styles']) && check_admin_referer("save_qis")) {
		$options = array(
			'font-colour',
			'text-font-colour',
			'input-border',
			'input-required',
			'inputbackground',
			'inputfocus',
			'border',
			'form-width',
			'submit-background',
			'submit-hover-background',
			'submitwidth',
			'submitwidthset',
			'submitposition',
			'submit-border',
			'background',
			'backgroundhex',
			'corners',
			'form-border',
			'header-type',
			'header-size',
			'header-colour',
			'error-font-colour',
			'error-border',
			'line_margin',
		);
		foreach ( $options as $item) {
			$style[$item] = stripslashes(@$_POST[$item]);
			$style[$item] =filter_var($style[$item],FILTER_SANITIZE_STRING);
		}
		
		update_option( 'qis_register_style', $style);
		qis_admin_notice("The form styles have been updated.");
	}
	
	if( isset( $_POST['Savenumbering']) && check_admin_referer("save_qis")) {
		$refdata['referenceprefix'] = $_POST['referenceprefix'];
		$refdata['referencenumber'] = $_POST['referencenumber'];
		update_option( 'qis_reference', $refdata);
		qis_admin_notice("The reference number has been saved.");
	}
	
	if( isset( $_POST['Resetnumbering']) && check_admin_referer("save_qis")) {
		delete_option('qis_reference');
		qis_admin_notice("The reference number has been reset.");
	}
	
	if( isset( $_POST['Resetstyles']) && check_admin_referer("save_qis")) {
		delete_option('qis_register_style');
		qis_admin_notice("The form styles have been reset.");
	}
	
	if( isset( $_POST['Validate']) && check_admin_referer("save_qis")) {
		$apikey = $_POST['qis_apikey'];
		$blogurl = get_site_url();
		$akismet = new qis_akismet($blogurl, $apikey);
		if($akismet->isKeyValid()) {
			qis_admin_notice("Valid Akismet API Key. All messages will now be checked against the Akismet database.");
			update_option('qis_akismet', $apikey);
		}
		else qis_admin_notice("Your Akismet API Key is not Valid");
		}

	if( isset( $_POST['Delete']) && check_admin_referer("save_qis")) {
		delete_option('qis_akismet');
		qcf_admin_notice("Akismet validation is no longer active");
		}

	$qis_apikey = get_option('qis_akismet');
	
	$register = qis_get_stored_register($theform);
	$style = qis_get_register_style($theform);
	$refdata = qis_get_stored_reference();
	
	${$style['border']} = 'checked';
	${$style['background']} = 'checked';
	${$style['header-type']} = 'checked';
	${$style['labeltype']} = 'checked';
	${$register['dropdownlabelposition']} = 'checked';
	${$register['dropdown2labelposition']} = 'checked';
	${$theform} = 'checked';
	
	$noshow = get_option('qis_advanced') ? true : false;
	if ($noshow == true) {
		$advanced = "display:none;";
		$hideline = ' style="display:none;"';
	}
	
	$qppkey = get_option('qpp_key');
	
	$content ='<div class="qis-settings">';
	if (!$qppkey['authorised']) {
		$content .= '<div class="qis-options">
		<h2 style="color:#B52C00">'.__('Application Form', 'quick-interest-slider').'</h2>
		<p>'.__('Add a form to the loan calculator to allow visitors to apply for a loan', 'quick-interest-slider').'.</p>
		<p>'.__('The application form is only available in the pro version of the plugin', 'quick-interest-slider').'.</p>
		<h3><a href="?page=quick-interest-slider/settings.php&tab=upgrade">'.__('Upgrade to Pro', 'quick-interest-slider').'</a></h3></div>';
	} else {
		$content .= '<div class="qis-options">
		<form id="" method="post" action="">';
		 
	$content .='</fieldset>';
	
	if (isset($qppkey['authorised']) && $qppkey['authorised']) {
		$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
		<h2>'.__('Form Selection', 'quick-interest-slider').'</h2>
		<p><select name="calculator">';
		for ($i= 1; $i<=25; $i++) {
			$selected = $theform == $i ? ' selected' : '';
			$content .='<option value="'.$i.'"'.$selected.'>Table '.$i.'</option>';
		}
		$content .='</select > <input type="submit" name="changeform" class="button-secondary" value="Change Form" /></p>
		</fieldset>';
	}
		
	$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;margin-bottom:10px;">';
		
	$content .= '<h2>'.__('Usage', 'quick-interest-slider').'</h2>';
		
	if ($noshow) $content .= '<p>'.__('The settings below will get you started', 'quick-interest-slider').'. '.__('To see all the settings (and there are a lot of them) click the button below', 'quick-interest-slider').'.</p>
	<p><input type="submit" name="basic" class="button-secondary" value="Show all settings" /></p>';
	else $content .= '<p><input type="submit" name="advanced" class="button-secondary" value="Hide advanced settings" /></p>';
		
	$content .= '<table width="100%">
		
		<tr>
		<td style="width:5%"><input type="checkbox" name="application"' . $register['application'] . ' value="checked" /></td>
		<td  colspan="2">'.__('Enable Application Form', 'quick-interest-slider').'.</td>
		</tr>
		
		<tr>
		<td style="width:5%"><input type="checkbox" name="alwayson"' . @$register['alwayson'] . ' value="checked" /></td>
		<td  colspan="2">'.__('Always display the form', 'quick-interest-slider').' ('.__('Disables the slide in', 'quick-interest-slider').').</td>
		</tr>
		
		<tr>
		<td></td>
		<td style="width:15%">'.__('Your Email Address', 'quick-interest-slider').'</td>
		<td><input type="text" name="sendemail" value="' . $register['sendemail'] . '" /><br><span class="description">'.__('This is where application notifications will be sent', 'quick-interest-slider').'</span></td>
		</tr>
		
		<tr'.$hideline.'>
		<td style="width:5%"><input type="checkbox" name="loginrequired" ' . $register['loginrequired'] . ' value="checked" /></td>
		<td  colspan="2">'.__('Only allow applications from logged in users', 'quick-interest-slider').'.</td>
		</tr>
		
		<tr'.$hideline.'>
		<td></td>
		<td style="width:15%">'.__('Login message', 'quick-interest-slider').'</td>
		<td><input type="text" name="loginblurb" value="' . $register['loginblurb'] . '" /> <input type="checkbox" name="loginlink"' . $register['loginlink'] . ' value="checked" /> '.__('Link to login page', 'quick-interest-slider').' (<span class="description">'.__('Returns to the application page after login', 'quick-interest-slider').'</span>).</td>
		</tr>
		
		<tr'.$hideline.'>
		<td style="width:5%"><input type="checkbox" name="blockduplicates" ' . $register['blockduplicates'] . ' value="checked" /></td>
		<td  colspan="2">'.__('Prevent duplicate applications', 'quick-interest-slider').'.</td>
		</tr>
		
		<tr'.$hideline.'>
		<td></td>
		<td style="width:15%">'.__('Duplicate message', 'quick-interest-slider').'</td>
		<td><input type="text" name="errorpending" value="' . $register['errorpending'] . '" /></td>
		</tr>
		
		</table>

		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<h2>'.__('Form Headers', 'quick-interest-slider').'</h2>
		
		<table width="100%">
		<tr>
		<td style="width:20%">'.__('Form title', 'quick-interest-slider').'</td>
		<td><input type="text" style="" name="title" value="' . $register['title'] . '" /></td>
		</tr>
		<tr>
		<td>'.__('Form blurb', 'quick-interest-slider').'</td>
		<td><input type="text" style="" name="blurb" value="' . $register['blurb'] . '" /></td>
		</tr>
		<td>'.__('Submit Button', 'quick-interest-slider').'</td>
		<td><input type="text" style="" name="qissubmit" value="' . $register['qissubmit'] . '" /></td>
		</tr>
		</table>
		
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<h2>'.__('Form Fields', 'quick-interest-slider').'</h2>
		<p>'.__('Check those fields you want to use. Drag and drop to change the order', 'quick-interest-slider').'.</p>
		<style>table#sorting{width:100%;}
		#sorting tbody tr{outline: 1px solid #888;background:#E0E0E0;}
		#sorting tbody td{padding: 2px;vertical-align:middle;}
		#sorting{border-collapse:separate;border-spacing:0 5px;}</style>
		<script>
		jQuery(function() 
		{var qis_rsort = jQuery( "#qis_rsort" ).sortable(
		{axis: "y",cursor: "move",opacity:0.8,update:function(e,ui)
		{var order = qis_rsort.sortable("toArray").join();jQuery("#qis_register_sort").val(order);}});});
		</script>
		<table id="sorting">
		<thead>
		<tr>
		<th style="width:5%">'.__('Use', 'quick-interest-slider').'</th>
		<th style="width:15%">'.__('Field', 'quick-interest-slider').'</th>
		<th>'.__('Label', 'quick-interest-slider').'</th>
		</tr>
		</thead>
		<tbody id="qis_rsort">';
		$sort = explode(",", $register['sort']);
		foreach ($sort as $name) {
			switch ( $name ) {
				case 'field1':
					$use = 'usename';
					$label = __('Name', 'quick-interest-slider');
					$input = 'yourname';
					$addon = '';
					$type= 'text';
				break;
				case 'field2':
					$use = 'useemail';
					$label = __('Email', 'quick-interest-slider');
					$input = 'youremail';
					$addon = '';
					$type= 'text';
				break;
				case 'field3':
					$use = 'usetelephone';
					$label = __('Telephone', 'quick-interest-slider');
					$input = 'yourtelephone';
					$addon = '';
					$type= 'text';
				break;
				case 'field4':
					$use = 'usemessage';
					$label = __('Message', 'quick-interest-slider');
					$input = 'yourmessage';
					$addon = '';
					$type= 'textarea';
				break;
				case 'field5':
					$use = 'usecaptcha';
					$label = __('Captcha', 'quick-interest-slider');
					$input = 'yourcaptcha';
					$addon = __('Displays a simple maths captcha to confuse the spammers', 'quick-interest-slider');
					$type= 'cpatcha';
				break;
				case 'field6':
					$use = 'usecopy';
					$label = __('Copy Message', 'quick-interest-slider');
					$input = 'copyblurb';
					$addon = '<br><input type="checkbox" name="copychecked"' . $register['copychecked'] . ' value="checked" /> '.__('Set default \'Copy Message\' field to \'checked\'', 'quick-interest-slider');
					$type= 'checkbox';
				break;
				case 'field7':
					$use = 'useaddinfo';
					$label = __('Additional Info (displays as plain text)', 'quick-interest-slider');
					$input = 'addinfo';
					$addon = '';
					$type= 'paragraph';
				break;
				case 'field8':
					$use = 'usechecks';
					$label = __('Checkboxes', 'quick-interest-slider');
					$input = 'checkboxeslabel';
					$addon = '&nbsp <input type="text" style="width:20%;" name="check1" . value ="' . $register['check1'] . '" />&nbsp<input type="text" style="width:20%;" name="check2" . value ="' . $register['check2'] . '" />&nbsp<input type="text" style="width:20%;" name="check3" . value ="' . $register['check3'] . '" />';
					$type= 'text';
				break;
				case 'field9':
					$use = 'useterms';
					$label = __('Terms and Conditions checkbox', 'quick-interest-slider');
					$input = 'termslabel';
					$addon = '<br>URL: <input type="text" style="width:80%" name="termsurl" value="' . $register['termsurl'] . '" /><br><input type="checkbox"  name="termstarget" ' . $register['termstarget'] . ' value="checked" />'.__('Open link in new Tab/Window', 'quick-interest-slider');
					$type= 'checkbox';
				break;
				case 'field10':
					$use = 'usedropdown';
					$label = __('Dropdown', 'quick-interest-slider');
					$input = 'dropdownlabel';
					$addon = '<input type="radio" name="dropdownlabelposition" value="include" ' . $include . ' />&nbsp'.__('Include label in dropdown list', 'quick-interest-slider').'&nbsp&nbsp&nbsp<input type="radio" name="dropdownlabelposition" value="paragraph" ' . $paragraph . ' />&nbsp'.__('Show as label above dropdown', 'quick-interest-slider').'<br>
					<span class="description">Options (separate with a comma):</span><br><textarea  name="dropdownlist" label="Dropdown" rows="2">' . $register['dropdownlist'] . '</textarea><input type="checkbox" name="dropdownignorefirst" ' . $register['dropdownignorefirst'] . ' value="checked"> '.__('Ignore first option on validation', 'quick-interest-slider');
					$type= 'dropdown';
				break;
				case 'field11':
					$use = 'useradio';
					$label = __('Radio Buttons', 'quick-interest-slider');
					$input = 'radiolabel';
					$addon = '<br>
					<span class="description">Options (separate with a comma):</span><br><textarea  name="radiolist" label="Radio" rows="2">' . $register['radiolist'] . '</textarea>';
					$type= 'dropdown';
				break;
				case 'field12':
					$use = 'useconsent';
					$label = __('Consent Checkbox', 'quick-interest-slider');
					$input = 'consentlabel';
					$addon = '';
					$type= 'checkbox';
				break;
				case 'field13':
					$use = 'useattachment';
					$label = __('Upload Document', 'quick-interest-slider');
					$input = 'attachmentlabel';
					$addon = '<br>File Types: <input type="text" style="width:15%" name="attach_type" value="' . $register['attach_type'] . '" /> Max File size (in bytes): <input type="text" style="width:15%" name="attach_size" value="' . $register['attach_size'] . '" /> <input type="checkbox"  name="linktoattachment" ' . $register['linktoattachment'] . ' value="checked" />'.__('Add link to email instead of attachment', 'quick-interest-slider');
					$type= 'file';
				break;
				case 'field14':
					$use = 'usecompany';
					$label = __('Company Name', 'quick-interest-slider');
					$input = 'yourcompany';
					$addon = '';
					$type= 'text';
				break;
				case 'field15':
					$use = 'usenumber';
					$label = __('Number', 'quick-interest-slider');
					$input = 'yournumber';
					$addon = '';
					$type= 'text';
				break;
				case 'field16':
					$use = 'usedropdown2';
					$label = __('Dropdown 2', 'quick-interest-slider');
					$input = 'dropdown2label';
					$addon = '<input type="radio" name="dropdown2labelposition" value="include" ' . $include . ' />&nbsp'.__('Include label in dropdown list', 'quick-interest-slider').'&nbsp&nbsp&nbsp<input type="radio" name="dropdown2labelposition" value="paragraph" ' . $paragraph . ' />&nbsp'.__('Show as label above dropdown', 'quick-interest-slider').'<br>
					<span class="description">Options (separate with a comma):</span><br><textarea  name="dropdown2list" label="Dropdown" rows="2">' . $register['dropdown2list'] . '</textarea><input type="checkbox" name="dropdown2ignorefirst" ' . $register['dropdown2ignorefirst'] . ' value="checked"> '.__('Ignore first option on validation', 'quick-interest-slider');
					$type= 'dropdown';
				break;
				case 'field17':
					$use = 'useaddress';
					$label = __('Address', 'quick-interest-slider');
					$input = 'youraddress';
					$addon = '';
					$type= 'text';
				break;
			}
			$content .= '<tr id="'.$name.'">
			<td style="width:5%"><input type="checkbox" name="'.$use.'" ' . $register[$use] . ' value="checked" /></td>';
			// $width = $name =='field8' ? 'width:15%;' : '';
			$content .= '<td style="width:15%">'.$label.'</td><td>';
			$content .= '<input type="text" style="'.$width.'" name="'.$input.'" value="' . @$register[$input] . '" />'.$addon.'</td></tr>';
		}
		$content .='</tbody>
		</table>
		<input type="hidden" id="qis_register_sort" name="sort" value="'.$register['sort'].'" />
		
		</table>
		
		</fieldset>';
		
		$tiny = $none = $hiding = $plain = "";
		switch ($register['labeltype']) {
			case 'none':
				$none = " checked='checked' ";
			break;
			case 'tiny':
				$tiny = " checked='checked' ";
			break;
			case 'plain':
				$plain = " checked='checked' ";
			break;
			case 'hiding':
				$hiding = " checked='checked' ";
			break;
		}
	$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<h2>'.__('Field Label Locations', 'quick-interest-slider').'</h2>
		<p><input type="radio" name="labeltype" value="tiny" ' . $tiny . ' /> '.__('Reduce in size on focus', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;<input type="radio" name="labeltype" value="hiding" ' . $hiding . ' /> '.__('Placeholders', 'quick-interest-slider').'&nbsp;&nbsp;&nbsp;<input type="radio" name="labeltype" value="plain" ' . $plain . ' /> '.__('Above Input Fields', 'quick-interest-slider').'</p>
		
		</fieldset>

		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<h2>'.__('Error and Thank-you messages', 'quick-interest-slider').'</h2>
		
		<table width="100%">

		<tr>
		<td style="width:20%">'.__('Thank you message title', 'quick-interest-slider').'</td>
		<td><input type="text" style="" name="replytitle" value="' . $register['replytitle'] . '" /></td>
		</tr>
		
		<tr>
		<td>'.__('Thank you message blurb', 'quick-interest-slider').'</td>
		<td><textarea style="width:100%;height:100px;" name="replyblurb">' . $register['replyblurb'] . '</textarea></td>
		</tr>
		
		<tr>
		<td>'.__('Error Title', 'quick-interest-slider').'</td>
		<td><input type="text" style="" name="errortitle" value="' . $register['errortitle'] . '" /></td>
		</tr>
		
		<tr>
		<td>'.__('Error Message', 'quick-interest-slider').'</td>
		<td><input type="text" style="" name="errorblurb" value="' . $register['errorblurb'] . '" /></td>
		</tr>
		
		<tr>
		<td>'.__('Attachment too big', 'quick-interest-slider').'</td>
		<td><input type="text" style="" name="attach_error_size" value="' . $register['attach_error_size'] . '" /></td>
		</tr>
		
		<tr>
		<td>'.__('Attachment filetype', 'quick-interest-slider').'</td>
		<td><input type="text" style="" name="attach_error_type" value="' . $register['attach_error_type'] . '" /></td>
		</tr>
		
		<tr'.$hideline.'>
		<td>'.__('Offset', 'quick-interest-slider').'</td>
		<td><input type="number" style="width:4em;" name="offset" value="' . $register['offset'] . '" />'.__('The number of pixels above the top of the form on page relaod', 'quick-interest-slider').'. '.__('Used when your theme has sticky header', 'quick-interest-slider').'.</td>
		</tr>

		</table>

		</fieldset>

		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<h2>'.__('Notification Email', 'quick-interest-slider').'</h2>
		
		<table width="100%">
		
		<tr>
		<td style="width:20%">'.__('Subject', 'quick-interest-slider').'</td>
		<td><input type="text" style="" name="notificationsubject" value="' . $register['notificationsubject'] . '" /></td>
		</tr>

		<tr>
		<td style="width:20%">'.__('Amount label', 'quick-interest-slider').'</td>
		<td><input type="text" style="" name="borrowlabel" value="' . $register['borrowlabel'] . '" /></td>
		</tr>
		
		<tr>
		<td>'.__('Term label', 'quick-interest-slider').'</td>
		<td><input type="text" style="" name="forlabel" value="' . $register['forlabel'] . '" /></td>
		</tr>

		</table>
		
		<p'.$hideline.'><input type="checkbox" name="repaymentdata" ' . $register['repaymentdata'] . ' value="checked"> '.__('Show repayment data in notiification message', 'quick-interest-slider').'.</p>
		
		<p'.$hideline.'>'.__('Add tracking information to the messages you receive', 'quick-interest-slider').'.</p>
		<table'.$hideline.'>
		<tr>
		<td style="width:5%"><input type="checkbox" name="page" ' . $register['page'] . ' value="checked"></td>
		<td>'.__('Show page title', 'quick-interest-slider').'</td>
		</tr>
		<tr>
		<td><input type="checkbox" name="ipaddress" ' . $register['ipaddress'] . ' value="checked"></td>
		<td>'.__('Show IP address', 'quick-interest-slider').'</td>
		</tr>
		<tr>
		<td><input type="checkbox" name="url" ' . $register['url'] . ' value="checked"></td>
		<td>'.__('Show URL', 'quick-interest-slider').'</td>
		</tr>
		</table>

		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		 <h2>'.__('Data Storage', 'quick-interest-slider').'</h2>
		
		<table>
		<tr>
		<td style="width:5%"><input type="checkbox" name="storedata"' . $register['storedata'] . ' value="checked" /></td>
		<td  colspan="2">'.__('Store applications in database', 'quick-interest-slider').' ('.__('Disable for privacy legislation compliance', 'quick-interest-slider').').</td>
		</tr>
		</table>
		
		</fieldset>

		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">

		<h2>'.__('Redirecton', 'quick-interest-slider').'</h2>
		<p>'.__('Redirect to this URL after form submission', 'quick-interest-slider').':
		<input type="text" name="qis_redirect_url" value="' . $register['qis_redirect_url'] . '"/></p>
		<p><input type="checkbox" name="qis_redirect_name"' . @$register['qis_redirect_name'] . ' value="checked" /> '.__('Add query with applicants name and email', 'quick-interest-slider').'.</p>
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="'.__('Save Changes', 'quick-interest-slider').'" />
		<input type="submit" name="Reset" class="button-secondary" value="'.__('Reset', 'quick-interest-slider').'" onclick="return window.confirm( \''.__('Are you sure you want to reset the registration form?', 'quick-interest-slider').'\' );"/></p>
		
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<h2>'.__('Application Reference', 'quick-interest-slider').'</h2>
		
		<table width="100%">

		<tr>
		<td style="width:20%">'.__('Prefix', 'quick-interest-slider').'</td>
		<td><input type="text" style="" name="referenceprefix" value="' . $refdata['referenceprefix'] . '" /></td>
		</tr>

		<tr>
		<td style="width:20%">'.__('Initial Value', 'quick-interest-slider').'</td>
		<td><input type="text" style="" name="referencenumber" value="' . $refdata['referencenumber'] . '" /></td>
		</tr>
		
		</table>
		
		<p><input type="submit" name="Savenumbering" class="button-primary" style="color: #FFF;" value="Save Reference" /> <input type="submit" name="Resetnumbering" class="button-secondary" value="'.__('Reset Numbering', 'quick-interest-slider').'" onclick="return window.confirm( \''.__('Are you sure you want to reset the reference number?', 'quick-interest-slider').'\' );"/></p>
		
		</fieldset>';
		$content .= wp_nonce_field("save_qis");
		$content .= '</form></div>';
		
		$content .='<h1>'.__('Application Form Styling', 'quick-interest-slider').'</h1><form method="post" action="">
		
		<div class="qis-options">
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<table>
		<tr>
		<td colspan="2"><h2>'.__('Buttons', 'quick-interest-slider').'</h2></td>
		</tr>
		
		<tr>
		<td style="width:20%">'.__('Button Colour', 'quick-interest-slider').':</td>
		<td><input type="text" class="qis-color" label="header-colour" name="header-colour" value="' . $style['header-colour'] . '" /></td>
		</tr>
		
		<tr>
		<td>'.__('Button Background', 'quick-interest-slider').':</td>
		<td><input type="text" class="qis-color" label="background" name="submit-background" value="' . $style['submit-background'] . '" /></td>
		</tr>
		
		<tr>
		<td>'.__('Button Hover', 'quick-interest-slider').'</td>
		<td><input type="text" class="qis-color" label="hoverbackground" name="submit-hover-background" value="' . $style['submit-hover-background'] . '" /></td>
		</tr>
		
		<tr>
		<td>Button Border: </td><td><input type="text" label="submit-border" name="submit-border" value="' . $style['submit-border'] . '" /></td>
		</tr>
		
		</table>
		
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<table>

		<tr>
		<td colspan="2"><h2>Input Fields</h2></td>
		</tr>
		
		<tr>
		<td style="width:20%">Font Colour: </td>
		<td><input type="text" class="qis-color" label="font-colour" name="font-colour" value="' . $style['font-colour'] . '" /></td>
		</tr>
		
		<tr>
		<td>Normal Border: </td>
		<td><input type="text" label="input-border" name="input-border" value="' . $style['input-border'] . '" /></td>
		</tr>
		
		<tr>
		<td>Required Fields: </td>
		<td><input type="text" label="input-required" name="input-required" value="' . $style['input-required'] . '" /></td>
		</tr>
		
		<tr>
		<td>Background: </td>
		<td><input type="text" class="qis-color" label="inputbackground" name="inputbackground" value="' . $style['inputbackground'] . '" /></td>
		</tr>
		
		<tr>
		<td>Focus: </td>
		<td><input type="text" class="qis-color" label="inputfocus" name="inputfocus" value="' . $style['inputfocus'] . '" /></td>
		</tr>
		
		<tr>
		<td>Corners: </td>
		<td><input type="text" label="corners" style="width:3em;" name="corners" value="' . $style['corners'] . '" /> px</td>
		</tr>
		
		<tr>
		<td style="vertical-align:top;">'.__('Margins and Padding', 'quick-interest-slider').'</td>
		<td><span class="description">'.__('Set the margins and padding of each bit using CSS shortcodes', 'quick-contact-form').':</span><br><input type="text" label="line margin" name="line_margin" value="' . $style['line_margin'] . '" /></td>
		</tr>
		
		</table>
		
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<table>

		<tr>
		<td colspan="2"><h2>Other text content</h2></td>
		</tr>
		
		<tr>
		<td style="width:20%">Font Colour: </td>
		<td><input type="text" class="qis-color" label="text-font-colour" name="text-font-colour" value="' . $style['text-font-colour'] . '" /></td>
		</tr>
		
		<tr>
		<td colspan="2"><h2>Error Messages</h2></td>
		</tr>
		
		<tr>
		<td>Font/Border Colour: </td>
		<td><input type="text" class="qis-color" label="error-font-colour" name="error-font-colour" value="' . $style['error-font-colour'] . '" /></td>
		</tr>
		   
		</table>
		
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<p><input type="submit" name="Styles" class="button-primary" style="color: #FFF;" value="Save Styles" /> <input type="submit" name="Resetstyles" class="button-secondary" value="Reset Styles" onclick="return window.confirm( \'Are you sure you want to reset the styles?\' );"/></p>
		
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<h2>Use Akismet Validation</h2>
		<p>'.__('Enter your API Key to check all messages against the Akismet database', 'quick-interest-slider').':</p> 
		<p><input type="text" label="akismet" name="qis_apikey" value="'.$qis_apikey.'" /></p>
		<p><input type="submit" name="Validate" class="button-primary"  value="'.__('Activate Akismet Validation', 'quick-interest-slider').'" /> <input type="submit" name="Delete" class="button-secondary" value="Deactivate Aksimet Validation" onclick="return window.confirm( \'This will delete the Akismet Key.\nAre you sure you want to do this?\' );"/></p>
		
		</fieldset>';
		
		$content .= wp_nonce_field("save_qis");
		$content .= '</form></div>';   
	}
	echo $content;		
}

function qis_application (){
	
	$termstarget=false;
	$advanced = $one = $two = $three = $four = $five = false;
	
	$formnumber = get_option('qis_formnumber');
	$theform  = (!$formnumber || $formnumber == 1) ? 1 : $formnumber;
	
	$application = qis_get_stored_application($theform);
	
	if( isset( $_POST['Submit']) && check_admin_referer("save_qis")) {
		$option = array (
			'loanreason',
			'repaymentmeans',
			'dateofbirth',
			'maritalstatus',
			'gender',
			'dependants',
			'preferedtime',
			'homephone',
			'homename',
			'homeaddress',
			'hometown',
			'homepostcode',
			'hometype',
			'hometime',
			'billsfood',
			'billrecreation',
			'billsloans',
			'billsother',
			'billsotheramount',
			'workcompany',
			'workemployer',
			'worktitle',
			'workincome',
			'workduration',
			'workbank',
			'additionalincome',
			'bankname',
			'bankaccount',
			'banksort',
			'bankiban',
			'bankaddress',
			'bankcountry',
			'bankswift',
			'terms',
			'accuracy',
			'documents',
			'identityproof',
			'addressproof',
			'oldhomename',
			'oldhomeaddress',
			'oldhometown',
			'oldhomepostcode',
			'oldhometype',
			'oldhometime',
		);
		
		$fields = array(
			'use',
			'label',
			'required',
			'options'
		);
		$newApplication = array();
		/*
			Create Blank Application
		*/
		foreach ($option as $iA) {
			foreach ($fields as $field) {
				$newApplication[$iA][$field] = '';
			}
		}
		
		/*
			Loop through POST Variables
		*/
		foreach ($_POST['application'] as $iB => $iV) {
			if (in_array($iB,$option)) {
				$na = array();
				foreach ($iV as $field => $fV) {
					$newApplication[$iB][$field] = stripslashes($fV);
				}
			}
		}
		
		$application = qis_get_stored_application($theform);
		
		/*
			Splice the data together
		*/
		
		$app = qis_splice($newApplication,$application);
		update_option('qis_full_application'.$theform, $app);
		
		// Update the messages
		$options = array(
			'enable',
			'part2title',
			'part2blurb',
			'part2submit',
			'thankyoutitle',
			'thankyoublurb',
			'borrowvalues',
			'reference',
			'changedetails',
			'errortitle',
			'errorblurb',
			'attach_size',
			'attach_type',
			'attach_error_size',
			'attach_error_type',
			'section1',
			'section2',
			'section3',
			'section4',
			'section5',
			'section6',
			'section7',
			'section8',
			'section9',
			'section8description',
			'use1',
			'use2',
			'use3',
			'use4',
			'use5',
			'use6',
			'use7',
			'use8',
			'use9'
		);
		
		$messages = array();
			foreach ($options as $item) {
				$messages[$item] = stripslashes(@$_POST[$item]);
				$messages[$item] = filter_var($messages[$item],FILTER_SANITIZE_STRING);
			}
		update_option('qis_application'.$theform, $messages);
		
		qis_admin_notice(__('The application form settings have been updated', 'quick-interest-slider'));
	}
	
	if( isset( $_POST['changeform'])) {
		$qisform = $theform = $_POST['calculator'];
		update_option( 'qis_formnumber', $qisform);
	}
	
	// Reset the forms
	if( isset( $_POST['Reset']) && check_admin_referer("save_qis")) {
		delete_option('qis_full_application'.$theform);
		delete_option('qis_application'.$theform);
		qis_admin_notice(__('The application form settings have been reset', 'quick-interest-slider'));
	}
	
	$arr = $application = qis_get_stored_application($theform);
	$register = qis_get_stored_application_messages($theform);
	
	${$theform} = 'checked';
	
	$qppkey = get_option('qpp_key');
	
	$content ='<div class="qis-settings">';
	if (!$qppkey['authorised']) {
		$content .= '<div class="qis-options">
		<h2 style="color:#B52C00">'.__('Application Form', 'quick-interest-slider').'</h2>
		<p>'.__('Add a two part form to the loan calculator to allow visitors to apply for a loan', 'quick-interest-slider').'.</p>
		<p>'.__('The application form is only available in the pro version of the plugin', 'quick-interest-slider').'.</p>
		<h3><a href="?page=quick-interest-slider/settings.php&tab=upgrade">'.__('Upgrade to Pro', 'quick-interest-slider').'</a></h3></div>';
	} else {
		$content .= '<form id="" method="post" action="">
		<div class="qis-options">';
		
		if (isset($qppkey['authorised']) && $qppkey['authorised']) {
			$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
			<h2>'.__('Form Selection', 'quick-interest-slider').'</h2>
			<p><select name="calculator">';
			for ($i= 1; $i<=25; $i++) {
				$selected = $theform == $i ? ' selected' : '';
				$content .='<option value="'.$i.'"'.$selected.'>Table '.$i.'</option>';
			}
			$content .='</select > <input type="submit" name="changeform" class="button-secondary" value="Change Form" /></p>
			</fieldset>';
		}
		
		$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
			
			<h2>'.__('This option is no longer supported', 'quick-interest-slider').'</h2>
			<p>'.__('It just got too complicated to look after so I built a standalone form that works much better', 'quick-interest-slider').'. <a href="https://applicationform.loanpaymentplugin.com/">'.__('See the demo', 'quick-interest-slider').'.</p>
			<p>'.__('But if you still want to give it a go', 'quick-interest-slider').':</p>
			<p><input type="checkbox" name="enable" ' . $register['enable'] . ' value="checked" /> '.__('Enable Application Form', 'quick-interest-slider').'.</p>
		
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
			
			<p>'.__('Form Title', 'quick-interest-slider').'</p>
			<p><input type="text" style="" name="part2title" value="' . $register['part2title'] . '" /></p>
			<p>'.__('Form Blurb', 'quick-interest-slider').'</p>
			<p><textarea style="width:100%;height:100px;" name="part2blurb">' . $register['part2blurb'] . '</textarea></p>
			<p>'.__('Submit Button', 'quick-interest-slider').'</p>
			<p><input type="text" style="" name="part2submit" value="' . $register['part2submit'] . '" /></p>
		
		</fieldset>';
		
		for($i = 1; $i < 10; $i++) {
			$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
			
			<h2>'.__('Section', 'quick-interest-slider').' '.$i.' - '.$register['section'.$i].'</h2>
			<p><input type="checkbox" name="use'.$i.'" ' . $register['use'.$i] . ' value="checked" /> Use section '.$i.'</p>
			<p>'.__('Title', 'quick-interest-slider').':<input type="text" name="section'.$i.'"  value="' . $register['section'.$i] . '" /></p>';
			if ($register['section'.$i.'description']) $content .= '<p>'.__('Description', 'quick-interest-slider').':<input type="text" name="section'.$i.'description"  value="' . $register['section'.$i.'description'] . '" /></p>';
			
			$content .= '<p>'.__('Fields', 'quick-interest-slider').':</p>
			<table width="100%">
			<tr><th width="5%">'.__('Use', 'quick-interest-slider').'</th><th width="5%">'.__('Req', 'quick-interest-slider').'</th><th width="10%">'.__('Type', 'quick-interest-slider').'</th><th>'.__('Label/Options', 'quick-interest-slider').'</th></tr>';
			foreach ($arr as $key => $value) {
				if ($application[$key]['section'] == $i) {
					if ($application[$key]['type'] == 'text' || $application[$key]['type'] == 'date' || $application[$key]['type'] == 'checkbox') {
						$content .= '<tr>
						<td><input type="checkbox" name="application['.$key.'][use]" ' . $application[$key]['use'] . ' value="checked" /></td>
						<td><input type="checkbox" name="application['.$key.'][required]" ' . $application[$key]['required'] . ' value="checked" /></td>
						<td>'.$application[$key]['type'].'</td>
						<td><input name="application['.$key.'][label]" type="text" value="'.$application[$key]['label'].'" /></td>
						</tr>';
					}
					if ($application[$key]['type'] == 'link') {
						$content .= '<tr>
						<td><input type="checkbox" name="application['.$key.'][use]" ' . $application[$key]['use'] . ' value="checked" /></td>
						<td><input type="checkbox" name="application['.$key.'][required]" ' . $application[$key]['required'] . ' value="checked" /></td><td>'.$application[$key]['type'].'</td><td><input name="application['.$key.'][label]" type="text" value="'.$application[$key]['label'].'" /></td>
						</tr>
						<tr>
						<td></td>
						<td></td>
						<td></td>
						<td><input name="application['.$key.'][termsurl]" type="text" value="'.$application[$key]['termsurl'].'" /></td>
						</tr>
						<tr>
						<td></td>
						<td></td>
						<td></td>
						<td><input type="checkbox" name="application['.$key.'][termstarget]" ' . $application[$key]['termstarget'] . ' value="checked" /> Open in new tab</td>
						</tr>';
					}
					if ($application[$key]['type'] == 'multi' || $application[$key]['type'] == 'dropdown' || $application[$key]['type'] == 'upload') {
						$content .= '<tr>
						<td width="5%"><input type="checkbox" name="application['.$key.'][use]" ' . $application[$key]['use'] . ' value="checked" /></td>
						<td width="5%"><input type="checkbox" name="application['.$key.'][required]" ' . $application[$key]['required'] . ' value="checked" /></td>
						<td>'.$application[$key]['type'].'</td><td><input name="application['.$key.'][label]" type="text" value="'.$application[$key]['label'].'" /></td>
						</tr>
						<tr>
						<td></td>
						<td></td>
						<td></td>
						<td><input name="application['.$key.'][options]" type="text" value="'.$application[$key]['options'].'" /></td>
						</tr>';
					}
				}
			}
			$content .= '</table>
			
			</fieldset>';
		}
		$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
			<h2>'.__('Reference Messages', 'quick-interest-slider').'</h2>
			<p>'.__('Loan values', 'quick-interest-slider').'</p>
			<p><input type="text" style="" name="borrowvalues" value="' . $register['borrowvalues'] . '" /></p>
			<p>'.__('Change loan button', 'quick-interest-slider').'</p>
			<p><input type="text" style="" name="changedetails" value="' . $register['changedetails'] . '" /></p>
			<p>'.__('Loan Values', 'quick-interest-slider').'</p>
			<p><input type="text" style="" name="reference" value="' . $register['reference'] . '" /></p>
			
			<h2>'.__('Thank you and Error Messages', 'quick-interest-slider').'</h2>
			<p>'.__('Thank-you Title', 'quick-interest-slider').'</p>
			<p><input type="text" style="" name="thankyoutitle" value="' . $register['thankyoutitle'] . '" /></p>
			<p>'.__('Thank-you blurb', 'quick-interest-slider').'</p>
			<p><textarea style="width:100%;height:100px;" name="thankyoublurb">' . $register['thankyoublurb'] . '</textarea></p>
			<p>'.__('Error Title', 'quick-interest-slider').'</p>
			<p><input type="text" style="" name="errortitle" value="' . $register['errortitle'] . '" /></p>
			<p>'.__('Error Message', 'quick-interest-slider').'</p>
			<p><input type="text" style="" name="errorblurb" value="' . $register['errorblurb'] . '" /></p>
		
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
			
			<h2>'.__('Attachment Data', 'quick-interest-slider').'</h2>
			<p>'.__('Permitted filetypes', 'quick-interest-slider').'</p>
			<p><input type="text" style="" name="attach_type" value="' . $register['attach_type'] . '" /></p>
			<p>'.__('Error message', 'quick-interest-slider').'</p>
			<p><input type="text" style="" name="attach_error_type" value="' . $register['attach_error_type'] . '" /></p>
			<p>'.__('Max file size', 'quick-interest-slider').'</p>
			<p><input type="text" style="" name="attach_size" value="' . $register['attach_size'] . '" /></p>
			<p>'.__('Error message', 'quick-interest-slider').'</p>
			<p><input type="text" style="" name="attach_error_size" value="' . $register['attach_error_size'] . '" /></p>
		
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
			<p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="'.__('Save Settings', 'quick-interest-slider').'" /> <input type="submit" name="Reset" class="button-secondary" value="'.__('Reset Settings', 'quick-interest-slider').'" onclick="return window.confirm( \'Are you sure you want to reset?\' );"/></p>
		
		</fieldset>';
		$content .= wp_nonce_field("save_qis");
		$content .= '</form></div>';   
	}
	echo $content;		
}

function qis_autoresponse_page() {
	
	$advanced = $one = $two = $three = $four = $five = false;
	$formnumber = get_option('qis_formnumber');
	$theform  = (!$formnumber || $formnumber == 1) ? 1 : $formnumber;

	$auto = qis_get_stored_autoresponder($theform);
	
	if( isset( $_POST['Submit']) && check_admin_referer("save_qis")) {
		$options = array(
			'enable',
			'subject',
			'subjecttitle',
			'subjectdate',
			'useeventdetails',
			'eventdetailsblurb',
			'useregistrationdetails',
			'registrationdetailsblurb',
			'sendcopy',
			'fromname',
			'fromemail',
			'permalink',
			'subscribemessage',
			'subscribealready',
			'subscribelink',
			'subscribeanchor',
			'unsubscribemessage',
			'unsubscribelink',
			'unsubscribeanchor',
			'notification',
			'noconfirmation'
		);
		foreach ($options as $item) {
			$auto[$item] = stripslashes(@$_POST[$item]);
			$auto[$item] = filter_var($auto[$item],FILTER_SANITIZE_STRING);
		}
		
		$auto['message'] = stripslashes($_POST['message']);
		
		update_option('qis_autoresponder'.$theform, $auto );
		qis_admin_notice("The autoresponder settings have been updated.");
		$auto = qis_get_stored_autoresponder($theform);
	}
	
	if( isset( $_POST['changeform'])) {
		$qisform = $theform = $_POST['calculator'];
		update_option( 'qis_formnumber', $qisform);
		$auto = qis_get_stored_autoresponder($theform);
	}
	
	if( isset( $_POST['Reset']) && check_admin_referer("save_qis")) {
		delete_option('qis_autoresponder'.$theform);
		qis_admin_notice("The autoresponder settings have been reset.");
		$auto = qis_get_stored_autoresponder($theform);
	}
	
	${$theform} = 'checked';
	
	$qppkey = get_option('qpp_key');
	
	$message = $auto['message'];
	$content ='<div class="qis-settings"><div class="qis-options">';
	if (!$qppkey['authorised']) {
		$content .= '<h2 style="color:#B52C00">'.__('Autoresponder', 'quick-interest-slider').'</h2>
		<p>'.__('The autoresponder will send a personalised email to applicants with details of their application', 'quick-interest-slider').'.</p>
		<p>'.__('The autoresponder is only availabile in the pro version of the plugin', 'quick-interest-slider').'.</p>
		<h3><a href="?page=quick-interest-slider/settings.php&tab=upgrade">'.__('Upgrade to Pro', 'quick-interest-slider').'</a></h3>';
	} else {	
		$content .= '<form method="post" action="">';
		
		if (isset($qppkey['authorised']) && $qppkey['authorised']) {
			$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
			<h2>'.__('Form Selection', 'quick-interest-slider').'</h2>
			<p><select name="calculator">';
			for ($i= 1; $i<=25; $i++) {
				$selected = $theform == $i ? ' selected' : '';
				$content .='<option value="'.$i.'"'.$selected.'>Table '.$i.'</option>';
			}
			$content .='</select > <input type="submit" name="changeform" class="button-secondary" value="Change Form" /></p>
			</fieldset>';
		}
		
		$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<h2>'.__('Auto responder', 'quick-interest-slider').'</h2>
		<p>'.__('The Auto Responder will send an email to the Applicant if enabled or if they choose the option on the application form to recieve a copy of their details', 'quick-interest-slider').'.</p>
		<p><input type="checkbox" name="enable"' . $auto['enable'] . ' value="checked" /> '.__('Enable Auto Responder', 'quick-interest-slider').'.</p>
		
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<h2>'.__('Message Headers', 'quick-interest-slider').'</h2>
		
		<p>'.__('From Name:', 'quick-interest-slider').' (<span class="description">'.__('Defaults to your', 'quick-interest-slider').' <a href="'. get_admin_url().'options-general.php">'.__('Site Title', 'quick-interest-slider').'</a> '.__('if left blank', 'quick-interest-slider').'</span>):<br>
		<input type="text" name="fromname" value="' . $auto['fromname'] . '" /></p>
		<p>'.__('From Email:', 'quick-interest-slider').' (<span class="description">'.__('Defaults to the', 'quick-interest-slider').' <a href="'. get_admin_url().'options-general.php">'.__('Admin Email', 'quick-interest-slider').'</a> '.__('if left blank', 'quick-interest-slider').'</span>):<br>
		<input type="text" name="fromemail" value="' . $auto['fromemail'] . '" /></p>	
		<p>'.__('Subject:', 'quick-interest-slider').'<br>
		<input type="text" name="subject" value="' . $auto['subject'] . '"/></p>
		
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<h2>'.__('Autoresponder Message', 'quick-interest-slider').'</h2>';
		echo $content;
		wp_editor($message, 'message', $settings = array('textarea_rows' => '8','wpautop'=>false,'media_buttons'=>false));
		$content = '<p>'.__('You can use the following shortcodes in the message body:', 'quick-interest-slider').'</p>
		<table>
		<tr><th>Shortcode</th><th>'.__('Replacement Text', 'quick-interest-slider').'</th></tr>
		<tr><td>[name]</td><td>'.__('The registrants name from the form', 'quick-interest-slider').'</td></tr>
		<tr><td>[amount]</td><td>'.__('The loan amount', 'quick-interest-slider').'</td></tr>
		<tr><td>[period]</td><td>'.__('The replayment period', 'quick-interest-slider').'</td></tr>
		<tr><td>[date]</td><td>'.__('The date the application was made', 'quick-interest-slider').'</td></tr>
		<tr><td>[rate]</td><td>'.__('The interest rate', 'quick-interest-slider').'</td></tr>
		<tr><td>[repayment]</td><td>'.__('The repayment amount', 'quick-interest-slider').'</td></tr>
		<tr><td>[totalamount]</td><td>'.__('The total to be repaid', 'quick-interest-slider').'</td></tr>
		<tr><td>[reference]</td><td>'.__('The application reference number', 'quick-interest-slider').'</td></tr>
		<tr><td>[subscribe]</td><td>'.__('Adds a link to confirm the email address', 'quick-interest-slider').' ('.__('see below', 'quick-interest-slider').')</td></tr>
		<tr><td>[unsubscribe]</td><td>'.__('Adds a link to unsubscribe from the database', 'quick-interest-slider').' ('.__('see below', 'quick-interest-slider').')</td></tr>
		</table>';
		$content .='<p><input type="checkbox" name="useregistrationdetails"' . $auto['useregistrationdetails'] . ' value="checked" />&nbsp;'.__('Add the application details to the email', 'quick-interest-slider').'</p>
		<p>'.__('Application details blurb', 'quick-interest-slider').'<br>
		<input type="text" style="" name="registrationdetailsblurb" value="' . $auto['registrationdetailsblurb'] . '" /></p>
		
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<h2>'.__('GDPR Compliance and Unsubscribe option', 'quick-interest-slider').'</h2>
		
		<p>Visit the <a href="https://loanpaymentplugin.com/gdpr-and-privacy-settings/" target="_blank">GDPR help page</a> for guidance on how to manage privacy</p>
		
		<p><input type="checkbox" name="noconfirmation"' . $auto['noconfirmation'] . ' value="checked" /> '.__('Do not require confirmation of application', 'quick-interest-slider').'.</p>
		
		<p>'.__('Add the shortcode [qis-subscribe] to a post/page display the confirmation messages', 'quick-interest-slider').'</p>
		<p>'.__('URL of the confirmation page', 'quick-interest-slider').':<br>
		<input type="text" name="subscribelink" value="' . $auto['subscribelink'] . '" /></p>
		
		<p><input type="checkbox" name="notification"' . $auto['notification'] . ' value="checked" /> '.__('Only send notification email after confirmation', 'quick-interest-slider').'.</p>
		
		<p>'.__('Subscribe email text', 'quick-interest-slider').' <span class="description">('.__('The words that replace the [subscribe] shortcode in the autoresponder message', 'quick-interest-slider').'):<br>
		<input type="text" name="subscribeanchor" value="' . $auto['subscribeanchor'] . '" /></p>
		
		<p>'.__('Unsubscribe email text', 'quick-interest-slider').' <span class="description">('.__('The words that replace the [unsubscribe] shortcode in the autoresponder message', 'quick-interest-slider').'):<br>
		<input type="text" name="unsubscribeanchor" value="' . $auto['unsubscribeanchor'] . '" /></p>
		
		<p>'.__('Email confirmation message', 'quick-interest-slider').' <span class="description">('.__('The message that displays on the  confirmation page', 'quick-interest-slider').'):<br>
		<input type="text" name="subscribemessage" value="' . $auto['subscribemessage'] . '" /></p>
		
		<p>'.__('Already confirmed message', 'quick-interest-slider').' <span class="description">('.__('The message that displays if already confirmed', 'quick-interest-slider').'):<br>
		<input type="text" name="subscribealready" value="' . $auto['subscribealready'] . '" /></p>
		
		<p>'.__('Unubscribe confirmation message', 'quick-interest-slider').' <span class="description">('.__('The unsubscribe message that displays on the confirmation page', 'quick-interest-slider').'):<br>
		<input type="text" name="unsubscribemessage" value="' . $auto['unsubscribemessage'] . '" /></p>
		
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">

		<p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="'.__('Save Changes', 'quick-interest-slider').'" /> <input type="submit" name="'.__('Reset', 'quick-interest-slider').'" class="button-secondary" value="Reset" onclick="return window.confirm( \'Are you sure you want to reset the auto responder settings?\' );"/></p>
		
		</fieldset>';
		$content .= wp_nonce_field("save_qis");
		$content .= '</form>';
	}
	$content .='</div></div>';
	echo $content;
}
			
			
function qis_progress() {

	$process = qis_get_stored_progress();
	$track = get_option('qis_track');
	
	if( isset( $_POST['Submit']) && check_admin_referer("save_qis")) {
		$options = array(
			'enabled',
			'loanlabel',
			'emaillabel',
			'referencelabel',
			'submitlabel',
			'progresslabel',
			'nothingfound',
			'showdetails',
			'progresssteps',
			'currentstep',
			'rejected',
			'background',
			'highlight',
			'rejectedcolour'
		);
		foreach ($options as $item) {
			$process[$item] = stripslashes(@$_POST[$item]);
			$process[$item] = filter_var($process[$item],FILTER_SANITIZE_STRING);
		}
		
		update_option('qis_progress', $process );
		qis_admin_notice("The application progress settings have been updated.");
		$process = qis_get_stored_progress();
	}
	
	if( isset( $_POST['Reset']) && check_admin_referer("save_qis")) {
		delete_option('qis_progress');
		qis_admin_notice("The application progress settings have been reset.");
		$process = qis_get_stored_progress();
	}
	
	if( isset( $_POST['Savetracking']) && check_admin_referer("save_qis")) {
		delete_option('qis_track');
		delete_option('qis_track_applications');
		$track['enabletracking'] = @$_POST['enabletracking'];
		update_option('qis_track',$track);
		qis_admin_notice("Tracking has been enabled");
	}
	
	if( isset( $_POST['Resettracking']) && check_admin_referer("save_qis")) {
		delete_option('qis_track');
		delete_option('qis_track_applications');
		qis_admin_notice("Tracking array has been reset");
	}

	$qppkey = get_option('qpp_key');
	
	$content ='<div class="qis-settings"><div class="qis-options">';
	if (!$qppkey['authorised']) {
		$content .= '<h2 style="color:#B52C00">'.__('Application Tracking', 'quick-interest-slider').'</h2>
		<p>'.__('Application tracking adds dashboard widget to show you the number of completed applications', 'quick-interest-slider').'.</p>
		<p>'.__('It also lets applicants login to monitor their application progression', 'quick-interest-slider').'.</p>
		<p>'.__('Tracking is only availabile in the pro version of the plugin', 'quick-interest-slider').'.</p>
		<h3><a href="?page=quick-interest-slider/settings.php&tab=upgrade">'.__('Upgrade to Pro', 'quick-interest-slider').'</a></h3>';
	} else {	
		$content .= '<form method="post" action="">';
		
		// Application Tracking
		$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
  
		<h2>'.__('Application Tracking', 'quick-interest-slider').'</h2>
		
		<p><input type="checkbox" name="enabletracking"' . (isset($track['enabletracking'])? $track['enabletracking'] : '' ) . ' value="checked" /> '.__('Enable application form tracking', 'quick-interest-slider').'.</p>
		
		<p>'.__('Tracking data shows in a dashboard widget', 'quick-interest-slider').'.</p>
		
		<p><input type="submit" name="Savetracking" class="button-primary" style="color: #FFF;" value="Save Tracking" /> <input type="submit" name="Resettracking" class="button-secondary" value="'.__('Reset Tracking Data', 'quick-interest-slider').'" onclick="return window.confirm( \''.__('Are you sure you want to reset the tracking data?', 'quick-interest-slider').'\' );"/></p>
		
		</fieldset>';

		// Application Progress
		$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<h2>'.__('Application Report', 'quick-interest-slider').'</h2>
		<p>'.__('Add the report to your posts or pages using the shortcode', 'quick-interest-slider').' [qisprogress].</p>
		<p><input type="checkbox" name="showdetails"' . $process['showdetails'] . ' value="checked" /> '.__('Show application details', 'quick-interest-slider').'.</p>
		<p><input type="checkbox" name="enabled"' . $process['enabled'] . ' value="checked" /> '.__('Enable progress control', 'quick-interest-slider').'.</p>
		<p>Progress steps:<input type="text" name="progresssteps" value="' . $process['progresssteps'] . '" /></p>
		<p><input type="checkbox" name="currentstep"' . $process['currentstep'] . ' value="checked" /> '.__('Only highlight current status', 'quick-interest-slider').'.</p>
		<p><input type="checkbox" name="rejected"' . $process['rejected'] . ' value="checked" /> '.__('Only highlight rejected step', 'quick-interest-slider').' ('.__('if last step', 'quick-interest-slider').').</p>
		
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<h2>'.__('Labels', 'quick-interest-slider').'</h2>
		<p>Email Address<input type="text" name="emaillabel" value="' . $process['emaillabel'] . '" /></p>
		<p>Reference<input type="text" name="referencelabel" value="' . $process['referencelabel'] . '" /></p>
		<p>Submit Button<input type="text" name="submitlabel" value="' . $process['submitlabel'] . '" /></p>
		<p>Application Details<input type="text" name="loanlabel" value="' . $process['loanlabel'] . '" /></p>
		<p>Application Status<input type="text" name="progresslabel" value="' . $process['progresslabel'] . '" /></p>
		<p>No Application Found<input type="text" name="nothingfound" value="' . $process['nothingfound'] . '" /></p>
		
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<h2>'.__('Colours', 'quick-interest-slider').'</h2>
		
		<table>
		<tr>
		<td>Progress buttons:<br><input type="text" class="qis-color" label="text-font-colour" name="background" value="' . $process['background'] . '" /></td>
		<td>Completed steps:<br><input type="text" class="qis-color" label="text-font-colour" name="highlight" value="' . $process['highlight'] . '" /></td>
		<td>Rejected status:<br><input type="text" class="qis-color" label="text-font-colour" name="rejectedcolour" value="' . $process['rejectedcolour'] . '" /></td>
		</tr>
		</table>
		
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">

		<p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="'.__('Save Changes', 'quick-interest-slider').'" /> <input type="submit" name="'.__('Reset', 'quick-interest-slider').'" class="button-secondary" value="Reset" onclick="return window.confirm( \'Are you sure you want to reset the procress indicator settings?\' );"/></p>
		
		</fieldset>';
		$content .= wp_nonce_field("save_qis");
		$content .= '</form>';
	}
	$content .='</div></div>';
	echo $content;
}

function qis_setdropdowns (){
	
	if( isset( $_POST['Submit']) && check_admin_referer("save_qis")) {
		
		$dropdown['use'] = stripslashes( @$_POST['use']);

		$options = array(
			'one',
			'two',
			'three',
			'four',
			'five',
			'six',
			'seven',
			'eight'
		);
		
		foreach ($options as $item) {
			$dropdown['forms'][$item] = stripslashes( $_POST[$item]);
		}
		
		update_option('qis_dropdown', $dropdown);

		qis_admin_notice(__('The selector settings have been updated', 'quick-interest-slider'));
	}
	if( isset( $_POST['Reset']) && check_admin_referer("save_qis")) {
		delete_option('qis_dropdown');
		qis_admin_notice(__('The selector settings have been reset', 'quick-interest-slider'));
	}
	
	$dropdown = qis_get_stored_dropdown();
	
	$qppkey = get_option('qpp_key');
	
	$content ='<div class="qis-settings">';
	if (!$qppkey['authorised']) {
		$content .= '<div class="qis-options">
		<h2 style="color:#B52C00">'.__('Selector', 'quick-interest-slider').'</h2>
		<p>'.__('Add a dropdown to you form so visitors can switch between calculator', 'quick-interest-slider').'.</p>
		<p>'.__('This option is only availabile in the pro version of the plugin', 'quick-interest-slider').'.</p>
		<h3><a href="?page=quick-interest-slider/settings.php&tab=upgrade">'.__('Upgrade to Pro', 'quick-interest-slider').'</a></h3></div>';
	} else {
		$content .= '<div class="qis-options">
		<form id="" method="post" action="">';
		
		$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;margin-bottom:10px;">
		
		<h2>'.__('Usage', 'quick-interest-slider').'</h2>
		
		<p><input type="checkbox" name="use"' . $dropdown['use'] . ' value="checked" /> '.__('Use the dropdown selector', 'quick-interest-slider').'</p>
		
		</fieldset>';
		
		$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<h2>'.__('Calculators', 'quick-interest-slider').'</h2>
		<p>'.__('Add a label to those calculators you want to display in the dropdown', 'quick-interest-slider').'.</p>
		
		<table width="100%">
		<tr>
		<td style="width:10%">'.__('Form', 'quick-interest-slider').'</td>
		<td>'.__('Label', 'quick-interest-slider').'</td>
		</tr>
		<tr>
		<td style="width:10%">One</td>
		<td><input type="text" style="" name="one" value="' . $dropdown['forms']['one'] . '" /></td>
		</tr>
		<tr>
		<td style="width:10%">Two</td>
		<td><input type="text" style="" name="two" value="' . $dropdown['forms']['two'] . '" /></td>
		</tr>
		<tr>
		<td style="width:10%">Three</td>
		<td><input type="text" style="" name="three" value="' . $dropdown['forms']['three'] . '" /></td>
		</tr>
		<tr>
		<td style="width:10%">Four</td>
		<td><input type="text" style="" name="four" value="' . $dropdown['forms']['four']. '" /></td>
		</tr>
		<tr>
		<td style="width:10%">Five</td>
		<td><input type="text" style="" name="five" value="' . $dropdown['forms']['five']. '" /></td>
		</tr>
		<tr>
		<td style="width:10%">Six</td>
		<td><input type="text" style="" name="six" value="' . $dropdown['forms']['six']. '" /></td>
		</tr>
		<tr>
		<td style="width:10%">Seven</td>
		<td><input type="text" style="" name="seven" value="' . $dropdown['forms']['seven']. '" /></td>
		</tr>
		<tr>
		<td style="width:10%">Eight</td>
		<td><input type="text" style="" name="eight" value="' . $dropdown['forms']['eight']. '" /></td>
		</tr>
		
		</table>
		
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="'.__('Save Changes', 'quick-interest-slider').'" />
		<input type="submit" name="Reset" class="button-secondary" value="'.__('Reset', 'quick-interest-slider').'" onclick="return window.confirm( \''.__('Are you sure you want to reset the selectors?', 'quick-interest-slider').'\' );"/></p>
		
		</fieldset>';
		
		$content .= wp_nonce_field("save_qis");
		$content .= '</form></div>';
	}
	echo $content;		
}

function qis_outputtable (){
	
	if( isset( $_POST['Submit']) && check_admin_referer("save_qis")) {
		$output['sort'] = stripslashes( $_POST['sort']);
		$output['values-strong'] = stripslashes( $_POST['values-strong']);
		$output['values-colour'] = stripslashes( $_POST['values-colour']);
		$sort = explode(",", $output['sort']);
		foreach ($sort as $item) {
			$output['use'.$item] = stripslashes( $_POST['use'.$item]);
			$output['use'.$item] = filter_var($output['use'.$item],FILTER_SANITIZE_STRING);
			$output[$item.'caption'] = stripslashes( $_POST[$item.'caption']);
			$output[$item.'caption'] = filter_var($output[$item.'caption'],FILTER_SANITIZE_STRING);
		}
		update_option('qis_outputtable'.$select, $output);

		qis_admin_notice(__('The output table settings have been updated', 'quick-interest-slider'));
	}
	if( isset( $_POST['Reset']) && check_admin_referer("save_qis")) {
		delete_option('qis_outputtable'.$select);
		qis_admin_notice(__('The output table settings have been reset', 'quick-interest-slider'));
	}

	$output = qis_get_stored_ouputtable();
	
	$qppkey = get_option('qpp_key');
	
	$content ='<div class="qis-settings">';
	if (!$qppkey['authorised']) {
		$content .= '<div class="qis-options" style="width:90%;">
		<h2 style="color:#B52C00">'.__('Application Form', 'quick-interest-slider').'</h2>
		<p>'.__('Display the output in a table', 'quick-interest-slider').'.</p>
		<p>'.__('This option is only availabile in the pro version of the plugin', 'quick-interest-slider').'.</p>
		<h3><a href="?page=quick-interest-slider/settings.php&tab=upgrade">'.__('Upgrade to Pro', 'quick-interest-slider').'</a></h3></div>';
	} else {
		$content .= '<div class="qis-options">
		<form id="" method="post" action="">
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<h2>'.__('Output Table', 'quick-interest-slider').'</h2>
		<p>'.__('Check those outputs you want to use. Drag and drop to change the order', 'quick-interest-slider').'.</p>
		<style>table#sorting{width:100%;}
		#sorting tbody tr{outline: 1px solid #888;background:#E0E0E0;}
		#sorting tbody td{padding: 2px;vertical-align:middle;}
		#sorting{border-collapse:separate;border-spacing:0 5px;}</style>
		<script>
		jQuery(function() 
		{var qis_rsort = jQuery( "#qis_rsort" ).sortable(
		{axis: "y",cursor: "move",opacity:0.8,update:function(e,ui)
		{var order = qis_rsort.sortable("toArray").join();jQuery("#qis_register_sort").val(order);}});});
		</script>
		<table id="sorting">
		<thead>
		<tr>
		<th style="width:5%">'.__('Use', 'quick-interest-slider').'</th>
		<th style="width:15%">'.__('Output', 'quick-interest-slider').'</th>
		<th>'.__('Value', 'quick-interest-slider').'</th>
		</tr>
		</thead>
		
		<tbody id="qis_rsort">';
		$sort = explode(",", $output['sort']);
		foreach ($sort as $name) {
			switch ( $name ) {
				case 'principle':
					$label = __('Principle', 'quick-interest-slider');
					$addon = __('Includes the currency symbol', 'quick-interest-slider');
					$type= 'text';
				break;
				case 'term':
					$label = __('Term', 'quick-interest-slider');
					$addon = __('Includes the period', 'quick-interest-slider');
					$type= 'text';
				break;
				case 'rate':
					$label = __('Interest rate', 'quick-interest-slider');
					$addon = __('Includes the % symbol', 'quick-interest-slider');
					$type= 'text';
				break;
				case 'interest':
					$label = __('Interest', 'quick-interest-slider');
					$addon = __('The interest to pay', 'quick-interest-slider').' ('.__('includes the currency symbol', 'quick-interest-slider').')';
					$type= 'text';
				break;
				case 'processing':
					$label = __('Processing', 'quick-interest-slider');
					$addon = __('The total processing fee', 'quick-interest-slider').' ('.__('includes the currency symbol', 'quick-interest-slider').')';
					$type= 'text';
				break;
				case 'date':
					$label = __('Repayment Date', 'quick-interest-slider');
					$addon = __('The date the loan matures', 'quick-interest-slider');
					$type= 'text';
				break;
				case 'downpayment':
					$label = __('Downpayment', 'quick-interest-slider');
					$addon = __('The total downpayment', 'quick-interest-slider').' ('.__('includes the currency symbol', 'quick-interest-slider').')';
					$type= 'text';
				break;
				case 'mitigated':
					$label = __('Adjusted Principle', 'quick-interest-slider');
					$addon = __('The principle less the downpayment', 'quick-interest-slider');
					$type= 'text';
				break;
				case 'repayment':
					$label = __('Repayment', 'quick-interest-slider');
					$addon = __('The value of each repayment', 'quick-interest-slider').' ('.__('includes the currency symbol', 'quick-interest-slider').')';
					$type= 'text';
				break;
				case 'total':
					$label = __('Total to Pay', 'quick-interest-slider');
					$addon = __('Includes the currency symbol', 'quick-interest-slider');
					$type= 'text';
				break;
			}
			$content .= '<tr id="'.$name.'">
			<td style="width:5%"><input type="checkbox" name="use'.$name.'" ' . $output['use'.$name] . ' value="checked" /></td>
			<td style="width:15%">'.$label.'</td>
			<td><input type="text" style="padding:1px;" name="'.$name.'caption" value="' . $output[$name.'caption'] . '" /></td>
			<td>'.$addon.'</td></tr>';
		}
		$content .='</tbody>
		</table>
		<input type="hidden" id="qis_register_sort" name="sort" value="'.$output['sort'].'" />
		
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<h2>'.__('Output Values', 'quick-interest-slider').'</h2>
		
		<p><input type="checkbox" name="values-strong" value="checked" '.$output['values-strong'].'> '.__('Embolden output values', 'quick-interest-slider').'.</p>
		
		<p>'.__('Font Colour', 'quick-interest-slider').': <input type="text" class="qis-color" label="values-colour" name="values-colour" value="' . $output['values-colour'] . '" /><p>

		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
		
		<p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="'.__('Save Changes', 'quick-interest-slider').'" />
		<input type="submit" name="Reset" class="button-secondary" value="'.__('Reset', 'quick-interest-slider').'" onclick="return window.confirm( \''.__('Are you sure you want to reset the output table?', 'quick-interest-slider').'\' );"/></p>
		
		</fieldset>';
		$content .= wp_nonce_field("save_qis");
		$content .= '</form></div>';
	}
	echo $content;		
}

// Upgrade
function qis_upgrade () {
	if( isset( $_POST['Upgrade']) && check_admin_referer("save_qis")) {
		$page_url = qis_current_page_url();
		$ajaxurl = admin_url('admin-ajax.php');
		$page_url = (($ajaxurl == $page_url) ? $_SERVER['HTTP_REFERER'] : $page_url);
		$qppkey = array('key' => '2d1490348869720eb6c48469cce1d21c');
		update_option('qpp_key', $qppkey);
		$form = '<h2>'.__('Waiting for PayPal...', 'quick-interest-slider').'</h2>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="qisupgrade" id="qisupgrade">
		<input type="hidden" name="item_name" value="Loan Calculator Upgrade"/>
		<input type="hidden" name="upload" value="1">
		<input type="hidden" name="business" value="mail@quick-plugins.com">
		<input type="hidden" name="return" value="https://quick-plugins.com/quick-paypal-payments/quick-paypal-payments-authorisation-key/?key='.$qppkey['key'].'&email='.get_option('admin_email').'">
		<input type="hidden" name="cancel_return" value="'.$page_url.'">
		<input type="hidden" name="currency_code" value="USD">
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="quantity" value="1">
		<input type="hidden" name="amount" value="45.00">
		<input type="hidden" name="notify_url" value = "'.site_url('/?qis_upgrade_ipn').'">
		<input type="hidden" name="custom" value="'.$qppkey['key'].'">
		</form>
		<script language="JavaScript">document.getElementById("qisupgrade").submit();</script>';
		echo $form;
	}
	
	if( isset( $_POST['Lost']) && check_admin_referer("save_qis")) {
		$email = get_option('admin_email');
		$qppkey = get_option('qpp_key');
		$headers = "From: Quick Plugins <mail@quick-plugins.com>\r\n"
. "Content-Type: text/html; charset=\"utf-8\"\r\n";	
		$message = '<html><p>'.__('Your Authorisation key is', 'quick-interest-slider').':</p><p>'.$qppkey['key'].'</p></html>';
		wp_mail($email,'Quick Plugins Authorisation Key',$message,$headers);
		qis_admin_notice('Your auth key has been sent to '.$email);
	}

	if( isset( $_POST['Check']) && check_admin_referer("save_qis")) {
		$qppkey = get_option('qpp_key');	
		if ($_POST['key'] == $qppkey['key'] || $_POST['key'] == '2d1490348869720eb6c48469cce1d21c') {
			$qppkey['key'] = $_POST['key'];
			$qppkey['authorised'] = true;
			update_option('qpp_key', $qppkey);
			qis_admin_notice(__('Your key has been accepted', 'multipay'));
		} else {
			qis_admin_notice(__('The key is not correct, please try again', 'multipay'));
		}
	}
	
	if( isset( $_POST['Delete']) && check_admin_referer("save_qis")) {
		$qppkey = get_option('qpp_key');
		$qppkey['authorised'] = '';
		update_option('qpp_key',$qppkey);
		qis_admin_notice(__('Your key has been deleted', 'multipay'));
	}
	
	$qppkey = get_option('qpp_key');
	$content = '<form id="" method="post" action="">';
	if (!$qppkey['authorised']) {
		$content .= '<div class="qis-settings"><div class="qis-options" style="width:90%;">
		<h2 style="color:#B52C00">Upgrade</h2>
		<p>'.__('Upgrading to the Pro Version of the plugin allows you to add an application form to the loan calculator', 'quick-interest-slider').'. '.__('Visitors will be able to choose the loan they want and apply for that loan. You have a number of fields you can select and the option to send a confirmation email to the applicant', 'quick-interest-slider').'. '.__('You can review all applications and email the complete list to yourself', 'quick-interest-slider').'.</p>
		<p>'.__('The upgrade also includes', 'quick-interest-slider').':</p>
		<ul>
		<li>- '.__('Slider labels', 'quick-interest-slider').'</li>
		<li>- '.__('Help buttons', 'quick-interest-slider').'</li>
		<li>- '.__('Foriegn exchange calculations', 'quick-interest-slider').'</li>
		<li>- '.__('Multiple currencies', 'quick-interest-slider').'</li>
		<li>- '.__('Interest rate slider', 'quick-interest-slider').'</li>
		<li>- '.__('Interest rate selectors', 'quick-interest-slider').'</li>
		<li>- '.__('Downpayments', 'quick-interest-slider').'</li>
		<li>- '.__('Amortization (repayment) calculations', 'quick-interest-slider').'</li>
		<li>- '.__('Layout styles and settings', 'quick-interest-slider').'</li>
		</ul>
		<p>'.__('All for $45. Which I think is a bit of a bargain', 'quick-interest-slider').'.</p>
		<p>'.__('Activation is automatic, as soon as you have paid the plugin will upgrade allowing access to the registration form, autoresponder, and other options', 'quick-interest-slider').'.</p>
		<p><input type="submit" name="Upgrade" class="button-primary" style="color: #FFF;" value="'.__('Buy a Single Site License', 'quick-interest-slider').'" /></p>
		<p>'.__('If you need a multi-site license', 'quick-interest-slider').' <a href="mailto:mail@quick-plugins.com">'.__('Contact me', 'quick-interest-slider').'</a>.</p>
		<h2>'.__('Activate', 'quick-interest-slider').'</h2>
		<p>'.__('Enter the authorisation key below and click on the Activate button', 'quick-interest-slider').':<br>
		<input type="text" style="width:50%" name="key" value="" /><br><input type="submit" name="Check" class="button-secondary" value="'.__('Activate', 'quick-interest-slider').'" />';
	   
	} else {
		$content .= '<p>'.__('You already have the Pro version of the plugin', 'quick-interest-slider').'.</p>
		<p>'.__('Your authorisation key is', 'quick-interest-slider').': '. $qppkey['key'] .'</p>
		<p><input type="submit" name="Delete" class="button-secondary" value="'.__('Delete Key', 'quick-interest-slider').'" /></p>';
	}
	$content .= wp_nonce_field("save_qis");
	$content .= '</form>';
	$content .= '</div>
	</div>';
	echo $content;
}

function qis_admin_notice($message) {if (!empty( $message)) echo '<div class="updated"><p>'.$message.'</p></div>';}

function qis_scripts_init($hook) {
	wp_enqueue_style('qis_settings',plugins_url('settings.css', __FILE__));
	if($hook != 'settings_page_quick-interest-slider/settings') {
		return;
	}
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_media();
	wp_enqueue_script('qis-media', plugins_url('media.js', __FILE__ ), array( 'jquery','wp-color-picker' ), false, true );
}