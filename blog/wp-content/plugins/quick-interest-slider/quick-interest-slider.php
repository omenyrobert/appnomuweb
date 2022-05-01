<?php
/*
Plugin Name: Quick Interest Slider
Plugin URI: http://loanpaymentplugin.com/
Description: Interest calculator with slider and multiple display options.
Version: 2.9.2
Author: aerin
Author URI: http://quick-plugins.com/
Text Domain: quick-interest-slider
Domain Path: /languages
*/

require_once( plugin_dir_path( __FILE__ ) . '/options.php' );
require_once( plugin_dir_path( __FILE__ ) . '/register.php' );

$qis_forms = 0;

add_shortcode('qis', 'qis_loop');
add_shortcode('qis-subscribe', 'qis_subscribe');
add_shortcode('qisprogress', 'qis_show_progress');

add_action('wp_enqueue_scripts', 'qis_scripts');
add_action('init', 'qis_lang_init');
add_action('wp_head', 'qis_head_css');
add_action('template_redirect', 'qis_upgrade_ipn');

add_action('wp_ajax_qis_get_calculator', 'qis_get_calculator');
add_action('wp_ajax_nopriv_qis_get_calculator', 'qis_get_calculator');

add_action('wp_ajax_qis_get_stylesheet', 'qis_get_stylesheet');
add_action('wp_ajax_nopriv_qis_get_stylesheet', 'qis_get_stylesheet');

add_action('wp_ajax_qis_capture_application', 'qis_capture_application');
add_action('wp_ajax_nopriv_qis_capture_application', 'qis_capture_application');

add_action( 'wp_dashboard_setup', 'qis_add_dashboard_widgets' );

add_filter('plugin_action_links', 'qis_plugin_action_links', 10, 2 );

if (is_admin()) require_once( plugin_dir_path( __FILE__ ) . '/settings.php' );


function qis_add_dashboard_widgets() {
	
	$track	= get_option('qis_track');
	
	if ($track['enabletracking']) {
		wp_add_dashboard_widget(
			'qis_dashboard_widget',							// Widget slug.
			esc_html__( 'Loan Application Tracking', 'quick-interest-slider' ), // Title.
			'qis_dashboard_widget_render'					// Display function.
		);
	}
}

function qis_dashboard_widget_render() {
	
	$track	= get_option('qis_track');
	
	if ($track) {
		if (!isset($track['completed'])) $track['completed'] = 0;
		if (!isset($track['visitors'])) $track['visitors'] = 0;
		if (!isset($track['opened'])) $track['opened'] = 0;

		echo '<div style="text-align:center;width:33.3%;float:left"><div>Visitors</div>
		<div style="font-size:30px;text-align:center;">'.$track['visitors'].'</div></div>';
	
		echo '<div style="text-align:center;width:33.3%;float:left"><div>Form Opened</div>
		<div style="font-size:30px;text-align:center;">'.$track['opened'].'</div></div>';
		
		echo '<div style="text-align:center;width:33.3%;float:left"><div>Completed</div>
		<div style="font-size:30px;text-align:center;">'.$track['completed'].'</div></div>';
		
		
		echo '<div style="clear:both"></div>';
	
		if ($track['completed'] > 0) {
			$percent = ($track['completed'] / $track['visitors']) * 100;
			$percent = round($percent, 2);
			echo '<div style="text-align:center;">Percentage completed: '.$percent.'%</div>';
		}
	} else {
		echo '<p>No tracking data available</p>';
	}

}

function qis_get_calculator() {
	
	$return = ['success' => false];
	
	if (isset($_POST['attributes'])) {
		
		// Pass the shortcode attributes to the qis_loop handler
		$data = qis_loop($_POST['attributes']);
		
		$return['data']		= $data;
		$return['success']	= true;
		
	}
	
	echo json_encode($return);
	
	die();
}

function qis_get_stylesheet() {
	if (isset($_POST['form'])) {
		header('content-type: text/css');
		echo qis_generate_css();
	}
	die();
}

function qis_block_init() {
	
	if ( !function_exists( 'register_block_type' ) ) {
		return;
	}
	
	$settings	= qis_get_stored_settings(null);
	
	// Register our block editor script.
	wp_register_script(
		'block',
		plugins_url( 'block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' )
	);

	// Register our block, and explicitly define the attributes we accept.
	register_block_type(
		'quick-interest-slider/block', array(
		'attributes' => array(
					'calculator'	=> array(
						'type'=> 'string',
						'default'	=> 1
					),
				),
		'editor_script'	=> 'block', // The script name we gave in the wp_register_script() call.
		'render_callback' => 'qis_loop'
		)
	);
}

add_action( 'init', 'qis_block_init' );

function qis_loop($atts) {
	
	qis_get_stored_upgrade();
	
	// Shortcode Attributes
	
	$atts = shortcode_atts(array(
		'calculator'	=> '',
		'formheader'	=> '',
		'currency'		=> '',
		'ba'			=> '',
		'primary'		=> '',
		'secondary'		=> '',
		'loanmin'		=> '',
		'loanmax'		=> '',
		'loaninitial'	=> '',
		'loanstep'		=> '',
		'periodslider'	=> '',
		'periodmin'		=> '',
		'periodmax'		=> '',
		'periodinitial' => '',
		'periodstep'	=> '',
		'period'		=> '',
		'interestslider'=> '',
		'interestselector'=> '',
		'multiplier'	=> '',
		'triggertype'	=> '',
		'trigger'		=> '',
		'outputtotallabel'=> '',
		'interesttype'	=> '',
		'totallabel'	=> '',
		'primarylabel'	=> '',
		'secondarylabel'=> '',
		'usebubble'		=> '',
		'repaymentlabel'=> '',
		'outputtotal'	=> '',
		'outputrepayments'=> '',
		'outputhelp'	=> '',
		'application'	=> '',
		'repaymentlabel'=> '',
		'buttons'		=> '',
		'markers'		=> '',
		'processing'	=> '',
		'adminfee'		=> '',
		'adminfeevalue' => '',
		'textinputs'	=> '',
		'interesttype'	=> '',
		'decimals'		=> '',
		'discount'		=> '',
		'applynow'		=> '',
		'fixedaddition' => '',
		'application'	=> '',
		'fields'		=> '',
		'loanlabel'		 => '',
		'termlabel'		 => '',
		'interestlabel' => '',
		'parttwo'		=> '',
		'usecurrencies' => '',
		'usefx'			 => '',
		'usedownpayment'=> '',
		'float'			 => '',
		'percentages'	=> '',
		'usegraph'		=> '',
		'interestdropdown'=> '',
		'terminterface' => '',
		'use'			=> ''
	),$atts,'quick-interest-slider');
	
	$dropdown = qis_get_stored_dropdown();
	
	if ($atts['use'] == 'dropdown') $dropdown['use'] = true;
	if ($atts['calculator'] == 'one')	$atts['calculator'] = 1;
	if ($atts['calculator'] == 'two')	$atts['calculator'] = 2;
	if ($atts['calculator'] == 'three') $atts['calculator'] = 3;
	if ($atts['calculator'] == 'four')	$atts['calculator'] = 4;
	if ($atts['calculator'] == 'five')	$atts['calculator'] = 5;
	if ($atts['calculator'] == 'six')	$atts['calculator'] = 6;
	if ($atts['calculator'] == 'seven') $atts['calculator'] = 7;
	if ($atts['calculator'] == 'eight') $atts['calculator'] = 8;
	
	// Pro Version filters
	
	$qppkey = get_option('qpp_key');
	if (!$qppkey['authorised']) {
		$atts['loanlabel'] = $atts['termlabel'] = $atts['application'] = $atts['applynow'] = $atts['interestslider'] = $atts['intereselector']= $atts['usecurrencies'] = $atts['usefx'] = $atts['usedownpayment'] = $atts['terminterface'] = false;
		if ($atts['interesttype'] == 'amortization' || $atts['interesttype'] == 'amortisation') $atts['interesttype'] = 'compound';
	}
	
	global $post;
	
	// Apply Now Button
	
	if (!empty($_POST['qisapply'])) {
		$formvalues = $_POST;
		if (isset($_GET['param'])) {
			$formvalues['param'] = $_GET['param'];
		} else {
			$formvalues['param'] = false;
		}
		$settings = qis_get_stored_settings($formvalues['formname']);
		$dropdown = qis_get_stored_dropdown();
		$url = $settings['applynowaction'];
		if ($settings['applynowquery']) {
			$settings['querystructure'] = str_replace('[amount]', $_POST['loan-amount'], $settings['querystructure']);
			$settings['querystructure'] = str_replace('[term]', $_POST['loan-period'], $settings['querystructure']);
			$settings['querystructure'] = str_replace('[form]', $formvalues['formname'], $settings['querystructure']);
			$settings['querystructure'] = str_replace('[calculator]', $dropdown['forms'][$formvalues['formname']], $settings['querystructure']);
			if ($formvalues['param']) $settings['querystructure'] = str_replace('[param]', $formvalues['param'], $settings['querystructure']);
			$url = $url.$settings['querystructure'];
		}
		echo "<p>".__('Redirecting....','quick-interest-slider')."</p><meta http-equiv='refresh' content='0;url=$url' />";
		die();
		
	// Application Form
		
	} elseif (!empty($_POST['qissubmit'])) {
		$formvalues = $_POST;
		$formerrors = array();
		
		if (!qis_verify_form($formvalues, $formerrors)) {
			return qis_display($atts,$formvalues, $formerrors,null);
		} else {
			$formvalues = qis_process_form($formvalues);
			$apply = qis_get_stored_application_messages($formvalues['formname']);
			if ($apply['enable'] || $atts['parttwo']) return qis_display_application($formvalues,array(),'checked');
			else return	qis_display($atts,$formvalues, $formerrors,'registered');
		}
		
	// Part 2 Application
		
	} elseif (!empty($_POST['part2submit'])) {
		$formvalues = $_POST;
		$formerrors = array();
		if (!qis_verify_application($formvalues, $formerrors)) {
			return qis_display_application($formvalues, $formerrors,null);
		} else {
			qis_process_application($formvalues);
			return qis_display_result($formvalues);
		}

	
	} elseif (!isset($_POST['attributes']) && ($dropdown['use'])) {
		
		// Show Dropdown 
		$dd = '<select id="calculators">';
		$one = 1;
		$i = 1;
		$addition = 'selected="selected"';
		
		foreach ($dropdown['forms'] as $key => $name) {
			if ($name) {
				$dd .= '<option value="'.$i.'" '.$addition.'>'.$name.'</option>';
				if ($one++ == 1) $addition = '';
			}
			$i++;
		}
		
		$dd .= "</select>";
		
		$_POST['attributes'] = [];
		
		// Append The Default Calculator
		$dd .= '<div id="calculator-container">';
		$dd .= qis_loop($atts);
		$dd .= "</div>";
		
		return $dd;
	
	} else { // Default Display
		$formnumber = $atts['calculator'];
		$theform = (!$formnumber || $formnumber == 1) ? 1 : $formnumber;
		$settings = qis_get_stored_settings($theform);
		$track = get_option('qis_track');
		if (isset($track['enabletracking']) && $track['enabletracking']) {
			@$track['visitors']++;
			update_option('qis_track',$track);
		}
		$arr = explode(",",$settings['interestdropdownvalues']);
		//$values = qis_get_stored_register($theform);
		$values['formname'] = $theform;
		$values['interestdropdown'] = $arr[0];
		$digit1 = mt_rand(1,10);
		$digit2 = mt_rand(1,10);
		if( $digit2 >= $digit1 ) {
			$values['thesum'] = "$digit1 + $digit2";
			$values['answer'] = $digit1 + $digit2;
		} else {
			$values['thesum'] = "$digit1 - $digit2";
			$values['answer'] = $digit1 - $digit2;
		}
		return qis_display($atts,$values ,array(),null);
	}
}

function qis_capture_application() {
	
	$track	= get_option('qis_track');
	
	if ($track['enabletracking']) {
		$track['opened']++;
		update_option('qis_track', $track);
	}
	echo json_encode(['success' => true]);
	die();
}
// Display the form on the page

function qis_display($atts,$formvalues,$formerrors,$registered) {
	
	global $qis_forms;

	$formnumber = $atts['calculator'];
	$theform	= (!$formnumber || $formnumber == 1) ? 1 : $formnumber;
	$settings	= qis_get_stored_settings($theform);
	$style		= qis_get_stored_style();
	$register	= qis_get_stored_register($theform);
	$table		= qis_get_stored_ouputtable();
	$qppkey		= get_option('qpp_key');
	$floats		 = false;
	
	$qis_forms++;
	
	foreach ($atts as $item => $key) {
		if ($key) {
			$settings[$item] = $key;
		}
	}

	if ($atts['terminterface']) {
		
		$settings['terminterface'] = $atts['terminterface'];
		
		if (!in_array(strtolower($atts['terminterface']),['slider','button'])) {
			$settings['periodslider'] = false;
		} else {
			$settings['periodslider'] = 'checked';
		}
	}
		
	if ($atts['periodslider']) $settings['terminterface'] = 'slider';
	
	if ($settings['usefx'] == 'checked') 
		$fx			= qis_get_stored_forex();
	else
		$fx			= array();
	
	// Field override
	
	if ($atts['fields']) $formvalues['fields'] = $atts['fields'];
	if ($atts['application']) $register['application'] = 'checked';
	if ($atts['primary']) $settings['triggers'][0]['rate'] = $atts['primary'];
	if ($atts['secondary']) $settings['triggers'][1]['rate'] = $atts['secondary'];
	if ($atts['trigger']) $settings['triggers'][1]['trigger'] = $atts['trigger'];
	if ($atts['repaymentlabel']) $settings['outputrepayments'] = 'true';

	if ($atts['processing']) {
		if (stristr($atts['processing'],'%')) {
			$settings['adminfee'] = true;
			if (preg_match('/^(\d+?\.?\d*)%$/',$atts['processing'],$matches)) {
				$settings['adminfeevalue'] = trim($matches[1],'.');
				$settings['adminfeetype'] = 'percent';
			}
		} else {
			$settings['adminfee'] = true;
			if (is_numeric($atts['processing'])) {
				$settings['adminfeevalue'] = (float) $atts['processing'];
				$settings['adminfeetype'] = 'fixed';
			}
		}		
	}
	
	if ($settings['percentages']) {
		$settings['percentarr'] = array();
		$ratesarray = explode(',',$settings['percentages']);
		for ($i=0;$i<count($ratesarray);$i++)
			$settings['percentarr'][$i] = $ratesarray[$i];
	}
	
	$settings['repaymentlabel'] = preg_replace('/{(\w+)}/','[\1]',$settings['repaymentlabel']);

	if ($settings['ba'] == 'before') {
		$settings['cb'] = $settings['currency'];
		$settings['ca'] = ' ';
	} else {
		$settings['ca'] = $settings['currency'];
		$settings['cb'] = ' ';
	}
	
	if ($register['application']) $settings['application'] = true;
	if (!isset($formvalues['loan-amount'])) $formvalues['loan-amount'] = $settings['loaninitial'];
	if (!isset($formvalues['loan-period'])) $formvalues['loan-period'] = $settings['periodinitial'];
	if (!isset($formvalues['loan-interest'])) $formvalues['loan-interest'] = $settings['interestinitial'];
	if (!isset($formvalues['downpayment-amount'])) $formvalues['downpayment-amount'] = $settings['downpaymentinitial'];
	if ($settings['multiplier'] < 1 || $settings['multiplier'] == false) {$settings['multiplier'] = $formvalues['multiplier'] = 1;}

	$settings['singleperiod'] = $settings['singleperiodlabel'] ? $settings['singleperiodlabel'] : $settings['period'];
	$settings['periodlabel'] = $settings['periodlabel'] ? $settings['periodlabel'] : $settings['period'];
	$settings['offset'] = $register['offset'] ? $register['offset'] : 0;

	if ($style['floatoutput']) $atts['float'] = true;
	
	// Normalize values
	
	$outputA = array();
	
	foreach ($settings as $k => $v) {
		$outputA[$k] = $v; 
		
		if (!is_array($v)) {
		
			if (@strtolower($v) == 'checked') $outputA[$k] = true;
		
			if ($v == '') $outputA[$k] = false;
		
			if (@preg_match('/[0-9.]+/',$v)) $outputA[$k] = (float) $v;
		}
	}
	
	if ($settings['nosliderlabel']) {
		$amountmin = qis_separator($settings['loanmin'],$outputA['separator']);
		$amountmax = qis_separator($settings['loanmax'],$outputA['separator']);
		$periodmin = $settings['periodmin'];
		$periodmax = $settings['periodmax'];
		$interestmin = $settings['interestmin'];
		$interestmax = $settings['interestmax'];
		$downpaymentmin = qis_separator($settings['downpaymentmin'],$outputA['separator']);
		$downpaymentmax = qis_separator($settings['downpaymentmax'],$outputA['separator']);
	} else {
		$amountmin = $settings['cb'].qis_separator($settings['loanmin'],$outputA['separator']).$settings['ca'];
		$amountmax = $settings['cb'].qis_separator($settings['loanmax'],$outputA['separator']).$settings['ca'];
		$periodmin = $settings['periodmin'].' '.$settings['singleperiod'];
		$periodmax = $settings['periodmax'].' '.$settings['periodlabel'];
		$interestmin = $settings['interestmin'].'%';
		$interestmax = $settings['interestmax'].'%';
		$downpaymentmin = $settings['cb'].qis_separator($settings['downpaymentmin'],$outputA['separator']).$settings['ca'];
		$downpaymentmax = $settings['cb'].qis_separator($settings['downpaymentmax'],$outputA['separator']).$settings['ca'];
	}

	if ($settings['onlyslidervalue']) {
		$amountmin = $amountmax = $periodmin = $periodmax = '&nbsp;';
	}

	if ($settings['usefx'] && $qppkey['authorised']) {
		$outputA['fx']	= $fx;
	}
	
	// Shortcode Replacements
	
	$dpf = false;
	
	if ($settings['downpaymentfixed']) $dpf = $settings['cb'].$settings['downpaymentfixed'].$settings['ca'];
	if ($settings['downpaymentpercent'] && $dpf) $dpf = $dpf.' and '.$settings['downpaymentpercent'].'%';
	if ($settings['downpaymentpercent'] && !$dpf) $dpf = $settings['downpaymentpercent'].'%';
	
	if (strpos($settings['repaymentlabel'],'[table]') !== false) {
		
		$outputtable = '<table class="outputtable">';

		$sort = explode(",", $table['sort']);
		foreach ($sort as $name) {
			if ($table['use'.$name]) $outputtable .= '<tr><td class="output-caption">'.$table[$name.'caption'].'</td><td class="values-colour output-values">'.$strongon.'['.$name.']'.$strongoff.'</td></tr>';
		}
	
		$outputtable .= '</table>';
	
		$settings['repaymentlabel'] = str_replace('[table]', $outputtable, $settings['repaymentlabel']);
	}
	
	$arr = array('repaymentlabel','outputtotallabel');
	
	foreach ($arr as $item) {
		$settings[$item] = str_replace('[step]', $settings['periodstep'], $settings[$item]);
		$settings[$item] = str_replace('[amount]', '<span class="repayment"></span>', $settings[$item]);
		$settings[$item] = str_replace('[repayment]', '<span class="repayment"></span>', $settings[$item]);
		$settings[$item] = str_replace('[period]', $settings['singleperiod'], $settings[$item]);
		$settings[$item] = str_replace('[rate]', '<span class="interestrate"></span>', $settings[$item]);
		$settings[$item] = str_replace('[dae]', '<span class="dae"></span>', $settings[$item]);
		$settings[$item] = str_replace('[interest]', '<span class="current_interest"></span>', $settings[$item]);
		$settings[$item] = str_replace('[monthlyrate]', '<span class="monthlyrate"></span>', $settings[$item]);
		$settings[$item] = str_replace('[total]', '<span class="final_total"></span>', $settings[$item]);
		$settings[$item] = str_replace('[discount]', '<span class="discount"></span>', $settings[$item]);
		$settings[$item] = str_replace('[principle]', '<span class="principle"></span>', $settings[$item]);
		$settings[$item] = str_replace('[term]', '<span class="term"></span>', $settings[$item]);
		$settings[$item] = str_replace('[processing]', '<span class="processing"></span>', $settings[$item]);
		$settings[$item] = str_replace('[date]', '<span class="repaymentdate"></span>', $settings[$item]);
		$settings[$item] = str_replace('[percentages1]', '<span class="percentages1"></span>', $settings[$item]);
		$settings[$item] = str_replace('[percentages2]', '<span class="percentages2"></span>', $settings[$item]);
		$settings[$item] = str_replace('[percentages3]', '<span class="percentages3"></span>', $settings[$item]);
		$settings[$item] = str_replace('[percentages4]', '<span class="percentages4"></span>', $settings[$item]);
		
		$settings[$item] = str_replace('[primary]', '<span class="generic_primary"></span>', $settings[$item]);
		$settings[$item] = str_replace('[secondary]', '<span class="generic_secondary"></span>', $settings[$item]);
		
		$settings[$item] = str_replace('[weeks]', '<span class="weeks"></span>', $settings[$item]);
		$settings[$item] = str_replace('[years]', '<span class="years"></span>', $settings[$item]);
		$settings[$item] = str_replace('[weekly]', '<span class="weekly"></span>', $settings[$item]);
		$settings[$item] = str_replace('[monthly]', '<span class="monthly"></span>', $settings[$item]);
		$settings[$item] = str_replace('[annual]', '<span class="annual"></span>', $settings[$item]);
		
		if ($qppkey['authorised']) {
			$settings[$item] = str_replace('[downpayment]', '<span class="downpayment"></span>', $settings[$item]);
			$settings[$item] = str_replace('[fixeddownpayment]', $settings['cb'].$settings['downpaymentfixed'].$settings['ca'], $settings[$item]);
			$settings[$item] = str_replace('[downpaymentpercent]', $settings['downpaymentpercent'].'%', $settings[$item]);
			$settings[$item] = str_replace('[mitigated]', '<span class="mitigated"></span>', $settings[$item]);
		}
	}

	$addFloat = '';
	if ($atts['float']) {
		$addFloat = 'qis-add-float';
	}
	
	// Append the currencies to the rates object

	$outputA['currencies'] = array();
	$s_form = ((isset($_POST['submitted_form']))? $_POST['submitted_form']:'N/A');
	
	$i = 1;
	for ($A_i = 0; isset($settings['currency_array'][$A_i]); $A_i++) {
		$outputA['currencies']['c'.$A_i] = $settings['currency_array'][$A_i];
	}

	$newTriggers = [];
	foreach ($outputA['triggers'] as $k => $v) {
		if ($v['rate'] != '') $newTriggers[] = $v;
	}
	
	$outputA['triggers'] = $newTriggers;
	$outputA['graph'] = ['use' => false];
	
	$output = '<script type="text/javascript">';
	$output .= 'qis__rates["qis_'.$qis_forms.'"] = '.json_encode($outputA).';';
	$output .= 'qis_form = '.json_encode($s_form).';';
	$output .= '</script>';
	$output .= $floats;
	$output .= '<form action="" class="qis_form '.$style['border'].'" method="POST" id="qis_'.$qis_forms.'" enctype="multipart/form-data">';
	$output .= '<input type="hidden" name="submitted_form" value="qis_'.$qis_forms.'" />';
	
	if ($settings['formheader']) $output .= '<h2>'.$settings['formheader'].'</h2>';
	
	$output .= '<div class="qis-sections qis-float '.$addFloat.'"><div class="qis-inputs qis-float-columns">';
	
	$output .= '<input type="hidden" name="interesttype" value="'.$settings['interesttype'].'" />';
	
	$sort = explode(",", $settings['sort']);
	
	if ($settings['usedownpaymentslider'] != 'checked') {
		$sort = qis_unset($sort,'downpayment');		
	}

	foreach($sort as $item) {
	
		if ($item == 'amount') {
			$output .= '<div class="range qis-slider-principal">';
	
			// Principle Slider
	
			if ($settings['loanlabel'] && $qppkey['authorised']) {
				$output .= '<div class="slider-label">'.$settings['loanlabel'];
				if ($settings['loanhelp']) $output .= qis_tooltip($settings['loaninfo']);
				$output .= '</div>';
			}
	
			if ($settings['textinputs'] != 'slider') $oX = '<input type="text" class="output" value="'.$formvalues['loan-amount'].'" />';
			elseif ($settings['outputlimits']) $oX = '<output></output>';
			else $oX = null;
	
			if ($settings['textinputs'] != 'text') {
				
				if (!$settings['maxminlimits'] && $settings['buttons']) 
					$output .= qis_outputs ('&nbsp;','&nbsp;',$oX,true,true);
				elseif ($settings['maxminlimits'] && $settings['buttons']) 
					$output .= qis_outputs ($amountmin,$amountmax,$oX,true,true);
				elseif ($settings['maxminlimits'])
					$output .= qis_outputs ($amountmin,$amountmax,$oX,false,false);
				elseif ($settings['outputlimits'] && $settings['buttons'])
					$output .= qis_outputs ('&nbsp;','&nbsp;',$oX,true,true);
				else
					$output .= qis_outputs ('&nbsp;','&nbsp;',$oX,null,null);
			
				$output .= '<input type="range" name="loan-amount" min="'.$settings['loanmin'].'" max="'.$settings['loanmax'].'" value="'.$formvalues['loan-amount'].'" step="'.$settings['loanstep'].'" data-qis>';
			
				if ($settings['markers']) {
					$handle = $style['slider-thickness'] + 1;
				
					$output .= '<div class="qis_slider_markers" style="position: relative; margin-left: '.($handle/2).'em; margin-right: '.($handle/2).'em; border-left: 1px solid black; border-right: 1px solid black; height: 10px">';
					/*
						Put together a step total and output a set of step markers
					*/
					$inner_value = (float) $settings['loanmax'] - $settings['loanmin'];
					$pps = $inner_value / $settings['loanstep'];
					$ppw = 100 / $pps;
				
					for ($i = 1; $i < $pps; $i++) {
						$output .= '<div class="qis_slider_marker" style="position: absolute; height: 10px; left: '.$ppw * $i.'%; margin-left: -1px; width: 1px; background-color: black;"></div>';
					}
					$output .= '</div>';
				}
			} else {
				$output .= '<div>'.$oX.'</div>';
				$output .= '<div>'.__('Minimum Amount:','quick-interest-slider').' '.$amountmin.' '.__('Maximum Amount:','quick-interest-slider').' '.$amountmax.'</div>';
				$output .= '<div class="hidethis"><input type="range" name="loan-amount" min="'.$settings['loanmin'].'" max="'.$settings['loanmax'].'" value="'.$formvalues['loan-amount'].'" step="'.$settings['loanstep'].'" data-qis></div>';
			}
			
			$output .= '</div>';
		}
	
		if ($item == 'currencies') {
			
			// Alternate Currencies
	
			if ($settings['usecurrencies'] && $qppkey['authorised']) {
				$output .= '<div class="checkradio"><ul>';
				$output .= '	<li class="label">'.$settings['currencieslabel'].':</li>';
				$index_A = 0;
				foreach ($outputA['currencies'] as $C_k => $C_v) {
					$index_A++;
					$output .= '<li><input type="radio" name="currencyname"'.(($index_A == 1)? ' checked="checked"':'').' value="'.$C_k.'" id="name'.$index_A.'"><label for="name'.$index_A.'"><span></span>'.$C_v['name'].'</label></li>';
				}
				$output .= '</ul></div>';
				$output .= '<div style="clear:both"></div>';
				$output .= '</div>';
			}
			
		}
		 
		if ($item == 'term') {
	
			// Term Slider
	
			if ($settings['textinputs'] != 'slider') $oX = '<input type="text" class="output" value="'.$formvalues['loan-period'].'" />';
			elseif ($settings['outputlimits']) $oX = '<output></output>';
			else $oX = null;
			
			if ($settings['periodslider']) {
				
				if ($settings['termlabel'] && $qppkey['authorised']) {
					$output .= '<div class="slider-label">'.$settings['termlabel'];
					if ($settings['periodhelp']) $output .= qis_tooltip($settings['periodinfo']);
					$output .= '</div>';
				}
				
				if ($settings['terminterface'] == 'slider') {
			
					$output .= '<div class="range qis-slider-term">';
			
					if ($settings['textinputs'] != 'text') {
						if ($settings['maxminlimits'] && $settings['buttons']) 
							$output .= qis_outputs ($periodmin,$periodmax,$oX,true,true);
						elseif ($settings['maxminlimits'])
							$output .= qis_outputs ($periodmin,$periodmax,$oX,null,null);
						elseif ($settings['outputlimits'] && $settings['buttons'])
							$output .= qis_outputs ('&nbsp;','&nbsp;',$oX,true,true);
						else
							$output .= qis_outputs ('&nbsp;','&nbsp;',$oX,null,null);
				
						$output .= '<input type="range" name="loan-period" min="'.$settings['periodmin'].'" max="'.$settings['periodmax'].'" value="'.$formvalues['loan-period'].'" step="'.$settings['periodstep'].'" data-qis>';
						if ($settings['markers']) {
							$handle = $style['slider-thickness'] + 1;
							$output .= '<div class="qis_slider_markers" style="position: relative; margin-left: '.($handle/2).'em; margin-right: '.($handle/2).'em; border-left: 1px solid black; border-right: 1px solid black; height: 10px">';
							
							//Put together a step total and output a set of step markers
							$inner_value = (float) $settings['periodmax'] - $settings['periodmin'];
							$pps = $inner_value / $settings['periodstep'];
							$ppw = 100 / $pps;
							
							for ($i = 1; $i < $pps; $i++) {
								$output .= '<div class="qis_slider_marker" style="position: absolute; height: 10px; left: '.$ppw * $i.'%; margin-left: -1px; width: 1px; background-color: black;"></div>';
							}
							$output .= '</div>';
						}
					} else {
						$output .= '<div>'.$oX.'</div>';
						$output .= '<div>'.__('Minimum Period:','quick-interest-slider').' '.$settings['periodmin'].' '.$settings['period'].' '.__('Maximum Period:','quick-interest-slider').' '.$settings['periodmax'].' '.$settings['period'].'</div>';
						$output .= '<div class="hidethis"><input type="range" name="loan-period" min="'.$settings['periodmin'].'" max="'.$settings['periodmax'].'" value="'.$formvalues['loan-period'].'" step="'.$settings['periodstep'].'" data-qis></div>';
					}
					$output .= '</div>';
				} elseif ($settings['terminterface'] == 'button') {
					$output .= '<div class="range qis-slider-term"><div class="term-buttons">';
					$buttons = [];
					
					for ($i = 0; $i < 4; $i++) {
						if ($amt = trim($settings['termbutton'.$i])) {

							$unt = $settings['buttonunits'];
							$buttons[] = [$amt,$unt];
							
						}
					}

					foreach ($buttons as $k => $v) {

						$amt = $v[0];
						$unt = $v[1];
						$output .= '<div class="termbutton '.(($k == 0)? 'selected':'').'" rel="'.$amt.','.$unt.'" style="width: '.(100 / count($buttons)).'%;">'.$amt.' '.($amt == 1 ? $settings['singleperiod'] : $settings['periodlabel']).'</div>';
					}
					
					$output .= '</div></div>';
				} else {
					// what do we do here?
				}
			}
		}
		
		if ($item == 'downpayment') {
			
			
			$output .= '<div class="range qis-slider-downpayment">';
	
			// Downpayment Slider
			if ($settings['downpaymentlabel'] && $qppkey['authorised']) {
				$output .= '<div class="slider-label">'.$settings['downpaymentlabel'];
				if ($settings['downpaymenthelp']) $output .= qis_tooltip($settings['downpaymentinfo']);
				$output .= '</div>';
			}
	
			if ($settings['textinputs'] != 'slider') $oX = '<input type="text" class="output" value="'.$formvalues['downpayment-amount'].'" />';
			elseif ($settings['outputlimits']) $oX = '<output></output>';
			else $oX = null;
	
			if ($settings['textinputs'] != 'text') {
				if ($settings['maxminlimits'] && $settings['buttons']) 
					$output .= qis_outputs ($downpaymentmin,$downpaymentmax,$oX,true,true);
				elseif ($settings['maxminlimits'])
					$output .= qis_outputs ($downpaymentmin,$downpaymentmax,$oX,null,null);
				elseif ($settings['outputlimits'] && $settings['buttons'])
					$output .= qis_outputs ('&nbsp;','&nbsp;',$oX,true,true);
				else
					$output .= qis_outputs ('&nbsp;','&nbsp;',$oX,null,null);
			
				$output .= '<input type="range" name="downpayment-amount" min="'.$settings['downpaymentmin'].'" max="'.$settings['downpaymentmax'].'" value="'.$formvalues['downpayment-amount'].'" step="'.$settings['downpaymentstep'].'" data-qis>';
			
				if ($settings['markers']) {
					$handle = $style['slider-thickness'] + 1;
				
					$output .= '<div class="qis_slider_markers" style="position: relative; margin-left: '.($handle/2).'em; margin-right: '.($handle/2).'em; border-left: 1px solid black; border-right: 1px solid black; height: 10px">';
					/*
						Put together a step total and output a set of step markers
					*/
					$inner_value = (float) $settings['downpaymentmax'] - $settings['downpaymentmin'];
					$pps = $inner_value / $settings['downpaymentstep'];
					$ppw = 100 / $pps;
				
					for ($i = 1; $i < $pps; $i++) {
						$output .= '<div class="qis_slider_marker" style="position: absolute; height: 10px; left: '.$ppw * $i.'%; margin-left: -1px; width: 1px; background-color: black;"></div>';
					}
					$output .= '</div>';
				}
			} else {
				$output .= '<div>'.$oX.'</div>';
				$output .= '<div>'.__('Minimum Downpayment:','quick-interest-slider').' '.$downpaymentmin.' '.__('Maximum Downpayment:','quick-interest-slider').' '.$downpaymentmax.'</div>';
				$output .= '<div class="hidethis"><input type="range" name="downpayment-amount" min="'.$settings['downpaymentmin'].'" max="'.$settings['downpaymentmax'].'" value="'.$formvalues['downpayment-amount'].'" step="'.$settings['downpaymentstep'].'" data-qis></div>';
			}
			$output .= '</div>';
		}

		if ($item == 'interest') {
		
			// Interest Slider
	
			if ($settings['textinputs'] != 'slider') $oX = '<input type="text" class="output" value="'.$formvalues['loan-interest'].'" />';
			elseif ($settings['outputlimits']) $oX = '<output></output>';
			else $oX = null;
	
			if ($settings['interestslider'] && $qppkey['authorised'] && !$settings['interestselector'] && !$settings['interestdropdown']) {
		
				if ($settings['interestlabel']) {
					$output .= '<div class="slider-label">'.$settings['interestlabel'];
					if ($settings['interesthelp']) $output .= qis_tooltip($settings['interestinfo']);
					$output .= '</div>';
				}
		
				$output .= '<div class="range qis-slider-interest">';
		
				if ($settings['textinputs'] != 'text') {
					if ($settings['maxminlimits'] && $settings['buttons']) 
						$output .= qis_outputs ($interestmin,$interestmax,$oX,true,true);
					elseif ($settings['maxminlimits'])
						$output .= qis_outputs ($interestmin,$interestmax,$oX,null,null);
					elseif ($settings['outputlimits'] && $settings['buttons'])
						$output .= qis_outputs ('&nbsp;','&nbsp;',$oX,true,true);
					else
						$output .= qis_outputs ('&nbsp;','&nbsp;',$oX,null,null);
					
					$output .= '<input type="range" name="loan-interest" min="'.$settings['interestmin'].'" max="'.$settings['interestmax'].'" value="'.$formvalues['loan-interest'].'" step="'.$settings['intereststep'].'" data-qis>';
					
					if ($settings['markers']) {
						$handle = $style['slider-thickness'] + 1;
						$output .= '<div class="qis_slider_markers" style="position: relative; margin-left: '.($handle/2).'em; margin-right: '.($handle/2).'em; border-left: 1px solid black; border-right: 1px solid black; height: 10px">';
					
						//Put together a step total and output a set of step markers
						$inner_value = (float) $settings['interestmax'] - $settings['interestmin'];
						$pps = $inner_value / $settings['intereststep'];
						$ppw = 100 / $pps;
						
						for ($i = 1; $i < $pps; $i++) {
							$output .= '<div class="qis_slider_marker" style="position: absolute; height: 10px; left: '.$ppw * $i.'%; margin-left: -1px; width: 1px; background-color: black;"></div>';
						}
						$output .= '</div>';
					}
				} else {
					$output .= '<div>'.$oX.'</div>';
					$output .= '<div>'.__('Minimum Interest:','quick-interest-slider').' '.$settings['interestmin'].'% '.__('Maximum Interest:','quick-interest-slider').' '.$settings['interestmax'].'% </div>';
					$output .= '<div class="hidethis"><input type="range" name="loan-interest" min="'.$settings['interestmin'].'" max="'.$settings['interestmax'].'" value="'.$formvalues['loan-interest'].'" step="'.$settings['intereststep'].'" data-qis></div>';
				}
				
				$output .= '</div>';
			}
	
			// Interest Selectors
	
			if ($settings['interestselector'] && $qppkey['authorised'] && !$settings['interestslider'] && !$settings['interestdropdown']) {
				$output .= '<div class="checkradio"><ul>';
				if ($settings['interestselectorlabel']) $output .= '<li class="label">'.$settings['interestselectorlabel'].':</li>';
				for ($i = 1; $i <= 4; $i++) {
					if ($settings['interestname'.$i]) {
						$checked = $i == 1 ? 'checked' : '';
						$output .= '<li><input type="radio" name="interestselector" value="'.$i.'" '.$checked.' id="interestname'.$i.'"><label for="interestname'.$i.'"><span></span>'.$settings['interestname'.$i].'</label></li>';
					}
				}
				$output .= '</ul></div>';
				$output .= '<div style="clear:both"></div>';
			}
	
			// Interest Dropdown
	
			if ($settings['interestdropdown'] && $qppkey['authorised'] && !$settings['interestslider'] && !$settings['interestselector']) {
		
				$arr = explode(",",$settings['interestdropdownvalues']);
				$output .= '<div class="qis-register">';
				if ($settings['interestdropdownlabelposition'] == 'paragraph') 
					$output .= '<p>'.$settings['interestdropdownlabel'].'</p>';
					$output .= '<select name="interestdropdown">';
				if ($settings['interestdropdownlabelposition'] == 'include')
					$output .= '<option value="'.preg_replace("/[^0-9.]/", "", $arr[0]).'">' . $settings['interestdropdownlabel'] . '</option>'."\r\t";
				foreach ($arr as $item) {
					$value = preg_replace("/[^0-9.]/", "", $item);
					$selected = $formvalues['interestdropdown'] == $value ? ' selected="selected"' : '';
					$output .= '<option value="' .	$value . '" ' . $selected .'>' .	$item . '</option>'."\r\t";
				}
				$output .= '</select></div>';
			}
		}
	
		if ($item == 'fx') {
			
			// Alternate Currencies
	
			if ($settings['usefx'] && $qppkey['authorised']) {
				$output .= '<div class="checkradio"><ul>';
				$output .= '<li class="label">'.$settings['fxlabel'].':</li>';
				$index_A = 0;
				foreach ($outputA['currencies'] as $C_k => $C_v) {
					$index_A++;
					$output .= '<li><input type="radio" name="fxname" value="'.$C_k.'"'.(($index_A == 1)? ' checked="checked"':'').' id="fxname'.$index_A.'"><label for="fxname'.$index_A.'"><span></span>'.$C_v['name'].'</label></li>';
				}
				$output .= '</ul></div>';
				$output .= '<div style="clear:both"></div>';
			}
		}
		
		// Loan Breakdown Graph
		
		if ($item == 'graph' && !$atts['float']) {
			
			if ($settings['usegraph']) {
				
				if ($settings['graphlabel']) 
					$output .= '<div class="slider-label">'.$settings['graphlabel'].'</div>';
				
				$output .= <<<GRAPH
		<script type="text/javascript">
			qis__rates["qis_{$qis_forms}"]['graph'] = {'use':true, 'id':'qis-totalbar', 'colors':{'principal':'{$style['graphprinciple']}','interest':'{$style['graphinterest']}','fees':'{$style['graphprocessing']}','downpayment':'{$style['graphdownpayment']}','discount':'{$style['graphdiscount']}'}};
		</script>
GRAPH;

				$output .= '<div id="qis-totalbar"></div>';
				$output .= '<p class="legend">';
				if ($settings['usedownpayment'] || $settings['usedownpaymentslider']) $output .= '<span style="color:'.$style['graphdownpayment'].'">&#9632;</span> '.$settings['graphdownpayment'].' ';
				if ($settings['discount']) $output .= '<span style="color:'.$style['graphdiscount'].'">&#9632;</span> '.$settings['graphdiscount'].' ';
				$output .= '<span style="color:'.$style['graphprinciple'].'">&#9632;</span> '.$settings['graphprinciple'].' ';
				if ($settings['adminfeewhen'] == 'beforeinterest' && $settings['adminfee']) $output .= '<span style="color:'.$style['graphprocessing'].'">&#9632;</span> '.$settings['graphprocessing'].' ';
				$output .= '<span style="color:'.$style['graphinterest'].'">&#9632;</span> '.$settings['graphinterest'].' ';
				if ($settings['adminfeewhen'] == 'afterinterest' && $settings['adminfee']) $output .= '<span style="color:'.$style['graphprocessing'].'">&#9632;</span> '.$settings['graphprocessing'].' ';
				$output .= '</p>';
			}
		}
		
		if ($item == 'repayments' && !$atts['float']) {
			
			// Display output messages
			if ($settings['outputrepayments']) {
				$output .= '<div class="qis-repayments">'.$settings['repaymentlabel'];
				if ($settings['outputhelp']) $output .= qis_tooltip($settings['outputinfo']);
				$output .= '</div>';
			}
		}

		if ($item == 'total' && !$atts['float']) {
			
			if ($settings['outputtotal']) {
				$output .= '<div class="qis-total">'.$settings['outputtotallabel'].'</div>';
			}
		}
			 
		if ($item == 'apply' && !$atts['float']) {
	
		// Apply Now and Application Form
	
		if ($register['application'] && $qppkey['authorised']) $output .= qis_display_form($formvalues,$formerrors,$registered).'</div>';
		elseif ($settings['applynow'] && $qppkey['authorised']) $output .= '<div class="qis-register"><input type="submit" class="submit" name="qisapply" onclick="this.value=\'Processing\'" value="'.$settings['applynowlabel'].'" /></div>';	
		}
	
		$output .= $settings['outputtable'];
	}
	
	// $output .= '</div>';
		
	if ($atts['float']) {

		$output .= '</div><div class="qis-outputs qis-float-columns">';
	
		// Display output messages
	
		if ($settings['outputrepayments']) {
			$output .= '<div class="qis-repayments">'.$settings['repaymentlabel'];
			if ($settings['outputhelp']) $output .= qis_tooltip($settings['outputinfo']);
			$output .= '</div>';
		}
		 
		if ($settings['outputtotal']) {
			$output .= '<div class="qis-total">'.$settings['outputtotallabel'].'</div>';
		}
	
		$output .= $settings['outputtable'];
	
		// Apply Now and Application Form
	
		if ($register['application'] && $qppkey['authorised']) $output .= qis_display_form($formvalues,$formerrors,$registered).'</div>';
		elseif ($settings['applynow'] && $qppkey['authorised']) $output .= '<div class="qis-register"><input type="submit" class="submit" name="qisapply" onclick="this.value=\'Processing\'" value="'.$settings['applynowlabel'].'" /></div>';
		
		// Close .qis-float
		$output .= '</div>';
	}
	
	$output .= '</div>';
	
	$output .= '<input type="hidden" name="repayment" value="'.@$formvalues['repayment'].'" />';
	$output .= '<input type="hidden" name="totalamount" value="'.@$formvalues['totalamount'].'" />';
	$output .= '<input type="hidden" name="formname" value="'.$formvalues['formname'].'" />';
	$output .= '<input type="hidden" name="rate" value="" />';
	$output .= '<div id="filechecking"><div class="filecheckingcontent"><img src="'.plugin_dir_url( __FILE__ ).'/img/waiting.gif'.'" alt="Loading"></div></div>';

	$output .= '</div></form>';
	return $output;
}

// Display Values

function qis_outputs($min,$max,$oX,$buttonleft,$buttonright) {
	
	$nomin = (($min == '&nbsp;' && $max == '&nbsp;')? ' circle-no-option':'');

	$left = $middle = false;
	if ($buttonleft) {$left = ' style="width:10%;"';$middle = ' style="width:80%;"';}
	
	$output = '<div class="qis-slideroutput qis-loan'.$nomin.'">';
	if ($min != '&nbsp;') $output .= '<span class="qis-sliderleft qis-min">'.$min.'</span>';
	if ($buttonleft) {
		$output .= '<span class="qis-sliderbuttonleft"'.$left.'><span class="circle-down circle-control"></span></span>';
	}
	
	$output .= '<span class="qis-slidercenter"'.$middle.'>'.$oX.'</span>';
	
	if ($buttonright) {
		$output .= '<span class="qis-sliderbuttonright"'.$left.'><span class="circle-up circle-control"></span></span>';
	}
	if ($max != '&nbsp;') $output .= '<span class="qis-sliderright qis-max">'.$max.'</span>';
	
	$output .= '</div>';
	return $output;
}

function qis_new_buttons ($oX) {
	$output = '<div class="qis-slideroutput qis-loan">
			<div class="output-pad">
				<span class="circle-down circle-control"></span><span class="qis-slidercenter">'.$oX.'</span><span class="circle-up circle-control"></span>
			</div>
		</div>';
	return $output;
}

function qis_unset($array,$value) {
	
	$newarray = [];
	foreach ($array as $k => $v) {
		if ($v == $value) unset($array[$v]);
		else $newarray[] = $v;
	}
	return $newarray;
}

// Display Tooltip

function qis_tooltip($text) {
	return '<span class="qis_tooltip_toggle"><a href="javascript:void(0);"></a><div class="qis_tooltip_body"><div class="qis_tooltip_content">'.$text.'</div><div class="close"></div></div></span>';
}

// Enqueue Scripts and Styles

function qis_scripts() {
	$style = qis_get_stored_style();
	if (!$style['nostyles']) wp_enqueue_style( 'qis_style',plugins_url('slider.css', __FILE__));
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script("jquery-effects-core");
	wp_enqueue_script('qis_script',plugins_url('slider.js', __FILE__ ), array( 'jquery' ), false, true );
	wp_enqueue_style ('jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css');
	wp_localize_script('qis_script', 'qis_application', [
		'ajax_url' => admin_url( 'admin-ajax.php' )
	]);
}

// Dashboard Link

function qis_plugin_action_links($links, $file ) {
	if ( $file == plugin_basename( __FILE__ ) ) {
		$qis_links = '<a href="'.get_admin_url().'options-general.php?page=quick-interest-slider/settings.php">'.__('Settings','quick-interest-slider').'</a>';
		array_unshift( $links, $qis_links );
		}
	return $links;
}

// Build Custom CSS

function qis_generate_css() {
	$style = qis_get_stored_style();
	if ($style['nostyles'] || $style['nocustomstyles']) return;
	$border = $radius = $background = false;
	$slideradius = $style['slider-thickness'] / 2;
	$handletop = ($style['handle-size'] - $style['slider-thickness']) / 2;
	
	if ($style['border']<>'none') {
		$border = ".qis_form.".$style['border']." {border:".$style['form-border-thickness']."px solid ".$style['form-border-color']."; padding: ".$style['form-padding']."px;border-radius:".$style['form-border-radius']."px;}";
	}
	if ($style['background'] == 'white') {
		$background = "form.qis_form {background:#FFF;}";
	}
	if ($style['background'] == 'color') {
		$background = "form.qis_form {background:".$style['backgroundhex'].";}";
	}
	if ($style['backgroundimage']) {
		$background = "form.qis_form {background: url('".$style['backgroundimage']."');}";
	}
		
	$formwidth = preg_split('#(?<=\d)(?=[a-z%])#i', $style['width']);
	if (!isset($formwidth[1])) $formwidth[1] = 'px';
	if ($style['widthtype'] == 'pixel') $width = $formwidth[0].$formwidth[1];
	else $width = '100%';
	
	$data = $border.$radius.$background.'
.qis_form {width:'.$width.';max-width:100%;}
.qis, .qis__fill {height: '.$style['slider-thickness'].'em;background: '.$style['slider-background'].';border-radius: '.$slideradius.'em;}
.qis__fill {background: '.$style['slider-revealed'].';border-radius: '.$slideradius.'em 0 0 '.$slideradius.'em;}
.qis__handle {width: '.$style['handle-size'].'em;height: '.$style['handle-size'].'em;top: -'.$handletop.'em;background: '.$style['handle-background'].';border: '.$style['handle-thickness'].'px solid '.$style['handle-border'].';position: absolute;border-radius:'.$style['handle-corners'].'%;}
.total {font-weight:bold;border-top:1px solid #FFF;margin-top:6px;text-align:left;}
.qis, .qis__fill {font-size:1em;width: 100%;}
.loanoutput{text-align:left;margin-bottom:6px;}
.qis-slideroutput {color:'.$style['output-colour'].'}
.qis-slidercenter {color:'.$style['slideroutputcolour'].';font-size:'.$style['slideroutputfont'].'em;}
.qis-sliderleft, .qis-sliderright {color:'.$style['toplinecolour'].';font-size:'.$style['toplinefont'].'em;}
.slider-label {color:'.$style['slider-label-colour'].';font-size:'.$style['slider-label-size'].'em;margin:'.$style['slider-label-margin'].';}
.qis-interest, .qis-repayments {color:'.$style['interestcolour'].';font-size:'.$style['interestfont'].'em;margin:'.$style['interestmargin'].';}
.qis-total {color:'.$style['totalcolour'].';font-size:'.$style['totalfont'].'em;margin:'.$style['totalmargin'].';}
.qis_tooltip_body {border: '.$style['tooltipborderthickness'].'px solid '.$style['tooltipbordercolour'].'; background-color: '.$style['tooltipbackground'].'; border-radius: '.$style['tooltipcorner'].'px; color: '.$style['tooltipcolour'].';}
.qis_tooltip_content {overflow: hidden; width: 100%; height: 100%;}
.checkradio input[type=radio]:not(old) + label > span{border:3px solid '.$style['handle-border'].'}
.checkradio input[type=radio]:not(old):checked + label > span{background: '.$style['slider-revealed'].';border: 3px solid '.$style['handle-border'].';}
.circle-down, .circle-up {border-color: '.$style['buttoncolour'].'}
.circle-down:after, .circle-up:after, .circle-up:before {background-color:'.$style['buttoncolour'].'}
.qis-outputs {'.$style['floatcustom'].'}
';

	$table = qis_get_stored_ouputtable();
	$right = $table['values-padding'] * 2;
	$strongon = $table['values-strong'] ? '<strong>' : '';
	$strongoff = $table['values-strong'] ? '</strong>' : '';
	$data .= $table['values-colour'] ? '.outputtable td{padding: 0 '.$right.'px '.$table['values-padding'].'px 0;}.values-colour{color:'.$table['values-colour'].'}' : '';
	
$right = 98 - $style['floatpercentage'];
$data .= '.qis-add-float {display:grid;grid-template-columns:'.$style['floatpercentage'].'% '.$right.'%;grid-gap:2%;}
@media only screen and (max-width:'.$style['floatbreakpoint'].'px) {.qis-add-float{display:block;}}';
	
	if ($style['slider-block']) $data .= qis_square_css($style);
	return $data;
}

// Builds Application Form CSS

function qis_register_css () {
	$code=$header=$input=$submitwidth=$paragraph=$submitbutton=$submit='';
	$style = qis_get_register_style();
	$corners = '-webkit-border-radius:'.$style['corners'].'px;border-radius:'.$style['corners'].'px;';
	$input = '.qis-register input[type=text], .qis-register input[type=tel], .qis-register textarea, .qis-register select, .qis_checkbox label, #calculators {color:'.$style['font-colour'].';border:'.$style['input-border'].';background-color:'.$style['inputbackground'].';}.registerradio input[type=radio]:not(old) + label > span{border:'.$style['input-border'].';}';
	$required = '.qis-register input[type=text].required, .qis-register input[type=tel].required, .qis-register textarea.required, .qis-register select.required {border:'.$style['input-required'].'}'; 
	$focus = ".qis-register input:focus, .qis-register textarea:focus {background:".$style['inputfocus'].";}";
	$text = ".qis-register p {color:".$style['font-colour'].";margin: 6px 0 !important;padding: 0 !important;}";
	$error = ".qis-register .error {color:".$style['error-font-colour']." !important;border-color:".$style['error-font-colour']." !important;}";
	$button = ".toggle-qis a {color: ".$style['header-colour'].";height:auto;font-size:1em;margin:0;text-decoration:none;}";	
	$submit = "color:".$style['submit-colour'].";background:".$style['submit-background'].";border:".$style['submit-border'].";font-size: inherit;".$corners;
	$submithover = "background:".$style['submit-hover-background'].";";
	$submitbutton = ".qis-register .submit, .toggle-qis a {".$submit."}.qis-register .submit:hover {".$submithover."}";
	$code = ".qis-register {max-width:100%;overflow:hidden;}".$submitbutton.$header.$paragraph.$input.$focus.$required.$text.$error;
	$data = '<style type="text/css" media="screen">'.$code.'</style>';
	echo $data;	
}

// Builds Square Slider CSS

function qis_square_css ($style) {
	$css = '.qis, .qis__fill {height: '.$style['slider-thickness'].'em;-webkit-box-shadow: none;-moz-box-shadow: none;box-shadow: none;-webkit-border-radius: 0;-moz-border-radius: 0;-ms-border-radius: 0;-o-border-radius: none;border-radius: 0;}
.qis__handle {width: '.$style['slider-thickness'].'em;height: '.$style['slider-thickness'].'em;border:none;top: 0;-webkit-box-shadow: none;-moz-box-shadow: none;box-shadow: none;-webkit-border-radius: 0;-moz-border-radius: 0;-ms-border-radius: 0;-o-border-radius: 0;border-radius: 0;}';
	return $css;
}

// Add to Head

function qis_head_css ($atts) {
	$atts = shortcode_atts(array('calculator' => ''),$atts,'quick-interest-slider');
	$data = '<style type="text/css" media="screen">'.qis_generate_css($atts['calculator']).'</style><script type="text/javascript">qis__rates = [];</script>';
	echo $data;
	qis_register_css(); 
}

// GDPR Subscribe/Unsubsribe

function qis_subscribe() {
	$message = get_option('qis_messages');
	
	$auto = qis_get_stored_autoresponder(null);
	if ( isset ($_GET['sub']) ) {
		$ref = $_GET['sub'];
		foreach ($message as $key => $value ) {
			if ($ref == $value['timestamp'] && $value['confirmed'] != true) {
				if ($auto['notification']) qis_send_notification ($value);
				$message[$key]['confirmed'] = true;
				update_option('qis_messages',$message);
				return '<div class="emailresponse">'.$auto['subscribemessage'].'</div>';
			}
		}
		return '<div class="emailresponse">'.$auto['subscribealready'].'</div>';
	}
	if ( isset ($_GET['unsub']) ) {
		$ref = $_GET['unsub'];
		foreach ($message as $key => $value )	{
			if ($ref == $value['timestamp']) {
				unset($value);
				$message = array_values($message);
				update_option('qis_messages',$message);
				return '<div class="emailresponse">'.$auto['unsubscribemessage'].'</div>';
			}
		}
		return '<div class="emailresponse">You have already unsubscribed</div>';
	}
}

// Report of all Applications

function qis_show_progress() {
	
	$content = false;
	
	$progress = qis_get_stored_progress();
	
	if (!empty($_POST['showprogress']) && check_admin_referer("save_qis")) {
		$formvalues = $_POST;
		$formvalues['youremail'] = filter_var($formvalues['youremail'],FILTER_SANITIZE_EMAIL);
		$formvalues['reference'] = filter_var($formvalues['reference'],FILTER_SANITIZE_STRING);
		
		$message = get_option('qis_messages');
		
		foreach ($message as $key) {
			if ($formvalues['youremail'] == $key['youremail'] && $formvalues['reference'] == $key['reference']) {
				$register = qis_get_stored_register(1);
				$content	= '<div class="qis-register">';
				if ($progress['showdetails']) {
					$content .= '<h2>'.$progress['loanlabel'].'</h2>';
					$content .= qis_build_message($key,$register);
				}
				$content .= '<h2>'.$progress['progresslabel'].'</h2>';
				$content .= '<p>';
				$steps = explode(",",$progress['progresssteps']);
				$stop = false;
				if ($progress['rejected'] && end($steps) == $key['progress']) {
					$stop = $progress['currentstep'] = true;
					$progress['highlight'] = $progress['rejectedcolour'];
				}
				foreach ($steps as $item) {
					if ($progress['currentstep']) {
						$background = $item == $key['progress'] ? ' style="background-color:'.$progress['highlight'].';"' : ' style="background-color:'.$progress['background'].';"';
					} else {
						$background = $stop ? ' style="background-color:'.$progress['background'].';"' : ' style="background-color:'.$progress['highlight'].';"';
						if ($item == $key['progress']) $stop = true;	
					}	
					$content .= '<span class="step"'.$background.'>'.$item.'</span>';
				}
				$content .= '</p>';
				$content .= '</div>';
			}
		}
		if ($content) return $content;
		else return '<h2>'.$progress['nothingfound'].'</h2>';
	}
	
	$content .= '<form action="" method="POST" class="qis-register">
	<p>'.$progress['emaillabel'].'<br>
	<input type="email" name="youremail" value=""></p>
	<p>'.$progress['referencelabel'].'<br>
	<input type="text" name="reference" value=""></p>
	<p><input onClick="check();" type="submit" value="'.$progress['submitlabel'].'" class="submit" name="showprogress" /><p>
	<input type="hidden" name="anything" value="'. date('Y-m-d H:i:s').'">
	<div class="validator">Enter the word YES in the box: <input type="text" style="width:3em" name="validator" value=""></div>';
	$content .= wp_nonce_field("save_qis");
	$content .= '</form>';
	
	return $content;
}

// Report of all Applications

function qis_registration_report() {

	$message = get_option('qis_messages');
	ob_start();
	$content ='<div id="qis-widget">
	<h2>'.__('Loan Applications','quick-interest-slider').'</h2>';
	$content .= qis_build_registration_table ($message,'report',null,null);
	$content .='</div>';
	echo $content;
	$output_string=ob_get_contents();
	ob_end_clean();
	return $output_string;
}

// Build the table of registrations

function qis_build_registration_table ($message,$report,$qis_edit,$selected) {
	$register = qis_get_stored_register(1);
	$progress = qis_get_stored_progress();
	$span=$charles=$content='';
	$delete=array();$i=0;
	
	$arr = array('name','email','telephone','message','company','address','number','checks','dropdown','dropdown2','radio','consent');
	
	foreach ($arr as $item) {
		foreach($message as $row) {
			if ($row['your'.$item]) {
				$register['use'.$item] = true;
			}
		}
	}
	
	$register['yourdropdown']	= $register['dropdownlabel'];
	$register['yourdropdown2'] = $register['dropdown2label'];

	$dashboard = '<table cellspacing="0">
	<tr>
	<th>'.__('Reference', 'quick-interest-slider').'</th>';
	foreach ($arr as $item) {
		if ($register['use'.$item]) $dashboard .= '<th>'.$register['your'.$item].'</th>';
	}
	$dashboard .= '<th>'.__('Amount', 'quick-interest-slider').'</th><th>Period</th>';
	if ($register['useattachment']) $dashboard .= '<th>Attachments</th>';
	$dashboard .= '<th>'.__('Date Sent', 'quick-interest-slider').'</th>';
	if ($progress['enabled']) $dashboard .= '<th>Progress</th>';
	if (!$report) $dashboard .= '<th></th>';

	$dashboard .= '</tr>';

	foreach($message as $value) {
		$span = ($value['reference'] && !$value['confirmed']) ? ' style="font-style:italic;color:#ccc;"' : '';
		$content .= '<tr'.$span.'>
		<td>'.$value['reference'].'</td>';
		foreach ($arr as $item) {
			if ($register['use'.$item]) {
				if ($value['yourconsent']) $value['yourconsent'] = 'checked';
				$content .= '<td>';
				if ( ($qis_edit == 'selected' && $selected[$i]) || $qis_edit == 'all') $content .= '<input style="width:100%" type="text" value="'.$message[$i]['your'.$item].'" name="message['.$i.'][your'.$item.']">';
				else $content .= $value['your'.$item];
				$content .= '</td>';
			}
		}
		if ( ($qis_edit == 'selected' && $selected[$i]) || $qis_edit == 'all') {
			$content .= '<td><input style="width:100%" type="text" value="'.$message[$i]['loan-amount'].'" name="message['.$i.'][loan-amount]"></td>
			<td><input style="width:100%" type="text" value="'.$message[$i]['loan-period'].'" name="message['.$i.'][loan-period]"></td>';
		} else {
			$content .= '<td>'.$value['loan-amount'].'</td><td>'.$value['loan-period'].'</td>';
		}
		if ($value['yourname']) $charles = 'messages';
		
		/*
		if ($register['useattachment']) {
			$content .= $value['attachment'] ? '<td><a href="'.$value['attachment'].'" target="_blank">View</a></td>' : '<td></td>';
		}
		*/
		
		if ($register['useattachment']) $content .= qis_message_thumbs($value);
		$content .= '<td>'.$value['sentdate'].'</td>';
		
		if ($progress['enabled']) {
			if ( ($qis_edit == 'selected' && $selected[$i]) || $qis_edit == 'all') {
				$content .= '<td>';
				$steps = explode(",",$progress['progresssteps']);
				$content .= '<select name="message['.$i.'][progress]">';
				if ($message[$i]['progress']) $content .= '<option value="'.$message[$i]['progress'].'">'.$message[$i]['progress'].'</option>';
				foreach ($steps as $item) {
					$content .= '<option value="' .	$item . '">' .	$item . '</option>'."\r\t";
				}
				$content .= '</select></div>';
				$content .= '</td>';
			} else {
				$content .= '<td>'.$message[$i]['progress'].'</td>';
			}
		}
		
		if (!$report)	$content .= '<td><input type="checkbox" name="'.$i.'" value="checked" /></td>';
		$content .= '</tr>';
		$i++;
	}	

	$dashboard .= $content.'</table>';
	if ($charles) return $dashboard;
}

// Languages

function qis_lang_init() {
	load_plugin_textdomain( 'quick-interest-slider', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

// Upgrade IPN function

function qis_upgrade_ipn() {
	$qppkey = get_option('qpp_key');
	if (!isset($_POST['custom']) || $qppkey['authorised'])
		return;
	$raw_post_data = file_get_contents('php://input');
	$raw_post_array = explode('&', $raw_post_data);
	$myPost = array();
	foreach ($raw_post_array as $keyval) {
		$keyval = explode ('=', $keyval);
		if (count($keyval) == 2)
			$myPost[$keyval[0]] = urldecode($keyval[1]);
	}
	$req = 'cmd=_notify-validate';
	if(function_exists('get_magic_quotes_gpc')) {
		$get_magic_quotes_exists = true;
	}
	foreach ($myPost as $key => $value) {
		if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
			$value = urlencode(stripslashes($value));
		} else {
			$value = urlencode($value);
		}
		$req .= "&$key=$value";
	}

	$ch = curl_init("https://www.paypal.com/cgi-bin/webscr");
	if ($ch == FALSE) {
		return FALSE;
	}

	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

	$res = curl_exec($ch);

	$tokens = explode("\r\n\r\n", trim($res));
	$res = trim(end($tokens));

	if (strcmp ($res, "VERIFIED") == 0 && $qppkey['key'] == $_POST['custom']) {
		$qppkey['authorised'] = 'true';
		update_option('qpp_key',$qppkey);
		$qpp_setup = qp_get_stored_setup();
		$email	= bloginfo('admin_email');
		$headers = "From: Quick Plugins <mail@quick-plugins.com>\r\n"
. "Content-Type: text/html; charset=\"utf-8\"\r\n";
		$message = '<html><p>'.__('Thank you for upgrading. Your authorisation key is','quick-interest-slider').':</p><p>'.$qppkey['key'].'</p></html>';
		wp_mail($email,__('Quick Plugins Authorisation Key','quick-interest-slider'),$message,$headers);
	}
	exit();
}

// Get URL of the current page

function qis_current_page_url() {
	$pageURL = 'http';
	if (!isset($_SERVER['HTTPS'])) $_SERVER['HTTPS'] = '';
	if (!empty($_SERVER["HTTPS"])) {
		$pageURL .= "s";
	}
	$pageURL .= "://";
	if (($_SERVER["SERVER_PORT"] != "80") && ($_SERVER['SERVER_PORT'] != '443'))
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	else 
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	return $pageURL;
}

// Changes thousands seperator

function qis_separator($s,$separator) {
	if ($separator == 'none')				return $s;
	else if ($separator == 'apostrophe')	$se = "'";
	else if ($separator == 'dot')		$se = ".";
	else if ($separator == 'comma')		$se = ",";
	else $se = ' ';
	return trim(preg_replace("/(\d)(?=(\d{3})+$)/",'$1'.$se,$s));
}