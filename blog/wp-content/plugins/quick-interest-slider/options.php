<?php

function qis_get_stored_forex() {
	
	$modified	= get_option('qis_forex_modified');
	$exchange	= get_option('qis_forex_exchange');
	$now		= time();
	
	if (($now - $modified) >= 86400 ) { 
		/* Renew the exchange rates list */
		$renew		= strtotime((new DateTime('18:00',new DateTimeZone('GMT')))->format('r'));
		
		// collect the data from the page
		$curl		= curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://api.fixer.io/latest");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		$list		= json_decode(curl_exec($curl));

		curl_close($curl);
		
		if (isset($list->rates)) {
			// Data successfully pulled
			$rates 	= (array) $list->rates;
			$rates['EUR'] = 1;
			
			$modified = strtotime($list->date.' 18:00 GMT');
			$exchange = $rates;
			
			/* Record the data */
			update_option('qis_forex_modified',$modified);
			update_option('qis_forex_exchange',$exchange);
		}
	}
	return $exchange;	
}

function qis_get_stored_dropdown() {
	
	$settings = get_option('qis_dropdown');
	if(!is_array($settings)) $settings = array();
	
	$default = [
		'forms' => [
			'one' => 'Calculator One',
			'two' => 'Calculator Two',
			'three' => 'Calculator Three',
			'four' => 'Calculator Four',
			'five' => '',
			'six' => '',
			'seven' => '',
			'eight' => '',
			
		],
		'use'	=> ''
	];
	
	$settings = qis_splice($settings, $default);
	
	return $settings;
}

function qis_get_stored_upgrade() {
	
	$upgrade = get_option('qis_upgrade');
	
	if ($upgrade) return;
	
	$settings = get_option('qis_settingsone');
	update_option('qis_settings1',$settings);
	$settings = get_option('qis_settingstwo');
	update_option('qis_settings2',$settings);
	$settings = get_option('qis_settingsthree');
	update_option('qis_settings3',$settings);
	$settings = get_option('qis_settingsfour');
	update_option('qis_settings4',$settings);
	$settings = get_option('qis_settingsfive');
	update_option('qis_settings5',$settings);
	
	update_option('qis_upgrade','upgrade');
	
	$formnumber = get_option('qis_formnumber');
	if ($formnumber == 'one') $formnumber = 1;
	if ($formnumber == 'two') $formnumber = 2;
	if ($formnumber == 'three') $formnumber = 3;
	if ($formnumber == 'four') $formnumber = 4;
	if ($formnumber == 'five') $formnumber = 5;
	update_option('qis_formnumber',$formnumber);
}

function qis_get_stored_settings($theform) {
	
	$settings = get_option('qis_settings'.$theform);
	$rate = 0;
	if(!is_array($settings)) {
		$settings = array();
		$rate = 2;
	}
	
	$default = array(
		'formheader'		=> false,
		'buttons'			=> false,
		'currency'			=> '$',
		'iso'				=> 'USD',
		'ba'				=> 'before',
		'discount'			=> 0,
		'separator'			=> 'none',
		'interesttype'		=> 'simple',
		'loanlabel'			=> false,
		'loanmin'			=> 10000,
		'loanmax'			=> 100000,
		'loaninitial'		=> 50000,
		'loanstep'			=> 10000,
		'loanhelp'			=> false,
		'loaninfo'			=> __('This is a test to demonstrate the tooltip for the loan slider','quick-interest-slider'),
		'periodmin'			=> 1,
		'periodmax'			=> 10,
		'periodinitial'		=> 5,
		'periodstep'		=> 1,
		'period'			=> 'years',
		'periodlabel'		=> false,
		'periodhelp'		=> false,
		'periodinfo'		=> __('This is a test to demonstrate the tooltip for the term slider','quick-interest-slider'),
		'periodformat'		=> 'US',
		'dateseperator'	 => '/',
		'singleperiodlabel'	=> false,
		'interestslider'	=> false,
		'interestmin'		=> 1,
		'interestmax'		=> 10,
		'interestinitial'	=> 4,
		'intereststep'		=> 0.5,
		'interesthelp'		=> false,
		'interestinfo'		=> __('This is a test to demonstrate the tooltip for the interest slider','quick-interest-slider'),
		'multiplier'		=> 1,
		'markers'			=> false,
		'outputtotallabel'	=> __('Total you will Pay: [total]','quick-interest-slider'),
		'interestlabel'		=> __('Interest','quick-interest-slider'),
		'totallabel'		=> __('Total to Pay','quick-interest-slider'),
		'primarylabel'		=> __('2% Interest','quick-interest-slider'),
		'secondarylabel'	=> __('3% Interest','quick-interest-slider'),
		'usebubble'			=> 0,
		'outputlimits'		=> 'checked',
		'outputhelp'		=> '',
		'outputinfo'		=> __('This is a test to demonstrate the tooltip for the output','quick-interest-slider'),
		'outputinterest'	=> 'checked',
		'outputtotal'		=> 'checked',
		'outputrepayments'	=> 'checked',
		'periodslider'		=> 'checked',
		'repaymentlabel'	=> __('Your repayments are [amount] every [period] at [rate]','quick-interest-slider'),
		'adminfee'			=> '',
		'adminfeevalue'		=> 15,
		'adminfeetype'		=> 'fixed',
		'adminfeemin'		=> '50',
		'adminfeemax'		=> '200',
		'adminfeewhen'		=> 'beforeinterest',
		'usedownpayment'	=> '',
		'downpaymentfixed'  => '',
		'downpaymentpercent'=> '', 
		'usedownpaymentslider'=> '',
		'downpaymentlabel'  => __('Downpayment','quick-interest-slider'),
		'downpaymenthelp'   => '',
		'downpaymentinfo'   => __('This is a test to demonstrate the tooltip for the downpayment slider','quick-interest-slider'),
		'downpaymentmin'	=> 0,
		'downpaymentmax'	=> 1000,
		'downpaymentinitial'=> 500,
		'downpaymentstep'   => 50,
		'termfee'			=> false,
		'termfeevalue'		=> 30,
		'textinputs'		=> 'slider',
		'textinputslabel'	=> __('Loan Amount','quick-interest-slider'),
		'triggertype'		=> 'periodtrigger',
		'decimals'			=> 'none',
		'rounding'		  => 'noround',
		'onlyslidervalue'	=> false,
		'nosliderlabel'	 => false,
		'maxminlimits'		=> 'checked',
		'applynow'			=> false,
		'applynowlabel'		=> __('Apply Now','quick-interest-slider'),
		'applynowaction'	=> false,
		'applynowquery'		=> false,
		'querystructure'	=> '?amount=[amount]&term=[term]',
		'fixedaddition'		=> false,
		'outputhide'		=> false,
		'usecurrencies'		=> '',
		'currencieslabel'	=> __('Select Currency','quick-interest-slider'),
		'outputtable'	   => false,
		'percentages'	   => false,
		'currency_array'	=> array(
			array(
				'symbol'	=> '£',
				'iso'	   => 'GBP',
				'name'	  => 'Pounds'
			),
			array(
				'symbol'	=> '$',
				'iso'	   => 'USD',
				'name'	  => 'US Dollars'
			),
			array(
				'symbol'	=> '€',
				'name'	  => 'Euros',
				'iso'	   => 'EUR'
			)
		),
		'usefx'				=> '',
		'fxlabel'			=> __('Convert to','quick-interest-slider'),
		'interestselector'	=> '',
		'interestselectorlabel'=> '',
		'interestrate1'		=> '7',
		'interestname1'		=> __('Standard Rate','quick-interest-slider'),
		'interestrate2'		=> '6',
		'interestname2'		=> __('Student Rate','quick-interest-slider'),
		'interestrate3'		=> '5',
		'interestname3'		=> __('Special Rate','quick-interest-slider'),
		'interestrate4'		=> '',
		'interestname4'		=> __('','quick-interest-slider'),
		'shortmonths' => array(
			__('Jan'), 
			__('Feb'), 
			__('Mar'), 
			__('Apr'), 
			__('May'), 
			__('Jun'), 
			__('Jul'), 
			__('Aug'), 
			__('Sep'), 
			__('Oct'), 
			__('Nov'), 
			__('Dec')
		),
		'interestdropdown'  => false,
		'interestdropdownlabel'=> 'Select the interest rate',
		'interestdropdownlabelposition'=> 'paragraph',
		'interestdropdownvalues'=> '',
		'sort'			  => 'amount,currencies,term,interest,downpayment,fx,graph,repayments,total,apply',
		'usegraph'		  => false,
		'graphlabel'		=> __('Loan Breakdown','quick-interest-slider'),
		'graphdownpayment'  => __('Downpayment','quick-interest-slider'),
		'graphdiscount'	 => __('Discount','quick-interest-slider'),
		'graphprinciple'	=> __('Principle','quick-interest-slider'),
		'graphprocessing'   => __('Admin Fee','quick-interest-slider'),
		'graphinterest'	 => __('Interest','quick-interest-slider'),
		'buttonunits'		=> 'months',
		'dae'			   => '',
		'termlabel'		 => '',
		'termbutton0'		=> '',
		'termbutton1'		=> '',
		'termbutton2'		=> '',
		'termbutton3'		=> '',
		'terminterface'		=> 'slider'
			
	);

	$triggers = array(
		array('rate' => $rate, 'trigger' => '', 'amttrigger' => '','dae' => ''),
		array('rate' => '', 'trigger' => '', 'amttrigger' => '','dae' => ''),
		array('rate' => '', 'trigger' => '', 'amttrigger' => '','dae' => ''),
		array('rate' => '', 'trigger' => '', 'amttrigger' => '','dae' => ''),
		array('rate' => '', 'trigger' => '', 'amttrigger' => '','dae' => ''),
		array('rate' => '', 'trigger' => '', 'amttrigger' => '','dae' => ''),
		array('rate' => '', 'trigger' => '', 'amttrigger' => '','dae' => ''),
		array('rate' => '', 'trigger' => '', 'amttrigger' => '','dae' => '')
	);
	
	$default['triggers'] = $triggers;

	if (empty($settings['sort'])) $settings['sort'] = $default['sort'];
	
	$settings = qis_apply_defaults($default, $settings);
	
	return $settings;
}

function qis_get_stored_style() {
	$style = get_option('qis_style');
	if(!is_array($style)) $style = array();
	
	$default = array(
		'nostyles'			  => false,
		'nocustomstyles'		=> false,
		'border'				=> 'plain',
		'form-border-thickness' => 1,
		'form-border-color'	 => '#007B9A',
		'form-border-radius'	=> 0,
		'form-padding'		  => 10,
		'width'				 => 350,
		'widthtype'			 => 'percent',
		'background'			=> 'white',
		'backgroundhex'		 => '#FFF',
		'corners'			   => 'corner',
		'slider-label-size'	 => 1.2,
		'slider-label-colour'   => '#888888',
		'slider-label-margin'   => '1em 0 0 0',
		'slider-thickness'	  => 0.5,
		'slider-background'	 => '#CCC',
		'slider-revealed'	   => '#3D9BE9',
		'handle-background'	 => 'white',
		'handle-size'		   => 1.2,
		'handle-border'	 => '#007B9A',
		'handle-corners'	=> 50,
		'handle-thickness'  => 1,
		'output-size'	   => '1.2em',
		'output-colour'	 => '#465069',
		'backgroundimage'   => '',
		'toplinefont'	   => 1,
		'toplinecolour'	 => '#3D9BE9',
		'slideroutputfont'  => 1,
		'slideroutputcolour'=> '#465069',
		'interestfont'	  => 1,
		'interestcolour'	=> '#3D9BE9',
		'interestmargin'	=> '0 0 1em 0',
		'totalfont'		 => 1.5,
		'totalcolour'	   => '#465069',
		'totalmargin'	   => '0 0 1em 0',
		'slider-block'	  => false,
		'tooltipcolour'	 => '#FFFFFF',
		'tooltipbackground' => '#343848',
		'tooltipbordercolour' => '#3D9BE9',
		'tooltipborderthickness' => 3,
		'tooltipcorner'	 => 10,
		'buttoncolour'	  => '#3D9BE9',
		'floatoutput'	   => '',
		'floatpercentage'   => 60,
		'floatbreakpoint'   => 600,
		'floatcustom'	   => 'background: #cce6ff;padding:10px;',
		'graphdownpayment'  => '#DC143C',
		'graphprinciple'	=> '#008B8B',
		'graphprocessing'   => '#DAA520',
		'graphinterest'	 => '#2E8B57',
		'graphdiscount'	 => '#20B2AA'
	);
	
	$style = array_merge($default, $style);
	
	$update = false;
	
	if ($style['border'] == 'roundshadow') {
		$style['form-border-radius'] = 10;
		$style['border'] = 'shadow';
		$update = true;
	} elseif ($style['border'] == 'rounded') {
		$style['form-border-radius'] = 10;
		$style['border'] = 'plain';
		$update = true;
	}
	if ($update) update_option( 'qis_style', $style);

	return $style;
}

function qis_get_stored_register ($theform) {
	$register = get_option('qis_register'.$theform);
	if(!is_array($register)) $register = array();
	
	$default = array(
		'application'   => '',
		'alwayon'	   => '',
		'sort'		  => 'field1,field2,field3,field8,field4,field9,field10,field16,field11,field14,field15,field17,field7,field5,field6,field12,field13',
		'usename'	   => 'checked',
		'useemail'	   => 'checked',
		'useaddinfo'	=> '',
		'usecaptcha'	=> '',
		'usemessage'	=> '',
		'usetelephone'  => '',
		'useattachment' => '',
		'usecompany'	=> '',
		'useaddress'	=> '',
		'usenumber'	 => '',
		'reqname'	   => 'checked',
		'reqmail'	   => 'checked',
		'notificationsubject' => __('New registration for [name] on [date]', 'quick-interest-slider'),
		'title'		 => __('Apply for this Loan', 'quick-interest-slider'),
		'blurb'		 => __('Enter your details below. All fields are required', 'quick-interest-slider'),
		'replytitle'	=> __('Thank you for applying', 'quick-interest-slider'),
		'replyblurb'	=> __('We will be in contact soon', 'quick-interest-slider'),
		'thankyoutitle' => __('Your Loan Application has been accepted', 'quick-interest-slider'),
		'thankyoublurb' => __('<p>An application for a loan of £[amount] in the name of [name] has been received and is being processed.</p><p>A copy of the application and the contract has been sent to [email].</p><p>You will be informed by email once processing is complete. If successful the money will be transferred to your bank account.</p>', 'quick-interest-slider'),
		'loan-amount'   => '',
		'loan-period'   => '',
		'formname'	  => '',
		'sentdate'	  => '',
		'timestamp'	 => '',
		'yourname'	  => __('Your Name', 'quick-interest-slider'),
		'youremail'	 => __('Email Address', 'quick-interest-slider'),
		'yourtelephone' => __('Telephone Number', 'quick-interest-slider'),
		'yourcaptcha'   => __('Answer the sum: ', 'quick-interest-slider'),
		'yourmessage'   => __('Message', 'quick-interest-slider'),
		'yourcompany'   => __('Company Name', 'quick-interest-slider'),
		'yourchecks'	=> __('Reason', 'quick-interest-slider'),
		'yourradio'	 => __('Coose', 'quick-interest-slider'),
		'yournumber'	=> __('Registration Number', 'quick-interest-slider'),
		'youraddress'   => __('Address', 'quick-interest-slider'),
		'attachmentlabel'=> __('Attach a file. Doc, pdf, png or jpg only. Max size 1Mb', 'quick-interest-slider'),
		'linktoattachment'=> false,
		'attach_size'   => '1000000',
		'attach_type'   => 'docxpdfpngjpg',
		'attach_error_size' => __('File is too big ', 'quick-interest-slider'),
		'attach_error_type' => __('File type is not permitted', 'quick-interest-slider'),
		'yourdropdown'  => false,
		'yourdropdown2'  => false,
		'copychecked'   => false,
		'youranswer'	=> false,
		'qis-copy'	  => false,
		'addinfo'	   => __('Fill in this field', 'quick-interest-slider'),
		'useterms'	  => '',
		'termslabel'	=> __('I agree to the Terms and Conditions', 'quick-interest-slider'),
		'termsurl'	  => '',
		'termstarget'   => '',
		'notattend'	 => '',
		'errortitle'	=> __('There is an error with your Application', 'quick-interest-slider'),
		'errorblurb'	=> __('Please complete all fields', 'quick-interest-slider'),
		'errorpending'  => __('You already have an application pending', 'quick-interest-slider'),
		'qissubmit'	 => __('Apply now', 'quick-interest-slider'),
		'sendemail'	 => get_bloginfo('admin_email'),
		'sendcopy'	  => '',
		'usecopy'	   => '',
		'copychecked'   => false,
		'completed'	 => '',
		'copyblurb'	 => __('Send application details to your email address', 'quick-interest-slider'),
		'page'		  => false,
		'ipaddress'	 => false,
		'url'		   => false,
		'labeltype'	 => 'hiding',
		'borrowlabel'	=> __('Loan Amount','quick-interest-slider'),
		'forlabel'		=> __('Loan Period','quick-interest-slider'),
		'downlabel'		=> __('Downpayment','quick-interest-slider'),
		'ratelabel'		=> __('Interest Rate','quick-interest-slider'),
		'usechecks'	 => '',
		'checkboxeslabel'=> __('Reason for Loan:', 'quick-interest-slider'),
		'check1'		=> __('Buy Beer', 'quick-interest-slider'),
		'check2'		=> __('Buy Pizza', 'quick-interest-slider'),
		'check3'		=> __('Buy Tacos', 'quick-interest-slider'),
		'usedropdown'   => '',
		'dropdownlabelposition' => 'include',
		'dropdownlabel' => __('Reason for loan', 'quick-interest-slider'),
		'dropdownlist'  => __('Buy a Banjo,Ride a Donkey,Eat a Melon', 'quick-interest-slider'),
		'dropdownignorefirst'=>'',
		'usedropdown2'  => '',
		'dropdown2labelposition' => 'include',
		'dropdown2label'=> __('Choose an aminal', 'quick-interest-slider'),
		'dropdown2list' => __('Iguanas,Panda Bears,Giraffes,Kittens,Penguins', 'quick-interest-slider'),
		'dropdown2ignorefirst'=>'',
		'useradio'	  => '',
		'radiolabel'	=> __('Choose one', 'quick-interest-slider'),
		'radiolist'	 => __('Yes,No,Maybe', 'quick-interest-slider'),
		'useconsent'	=> '',
		'yourconsent'   => 'Consent',
		'consentlabel'  => __('I consent to my data being retained by the site owner after the application has been processed.', 'quick-interest-slider'),
		'storedata'	 => 'checked',
		'qis_redirect_url' => '',
		'qis_redirect_name',
		'fields'		=> '',
		'terms'		 => '',
		'radiooption'   => '',
		'repayment'	 => '',
		'totalamount'   => '',
		'offset'		=> 0,
		'loginrequired' => '',
		'loginblurb'	=> 'Please login to apply',
		'loginlink'	 => '',
		'blockduplicates' => '',
		'usecheckboxes' => false,
		'duplicate'	 => false,
		'reference'	 => 'Application Reference',
		'repaymentdata' => ''
	);
	
	$register = array_merge($default, $register);
	
	if ($register['usecheckboxes']) $register['usechecks'] = 'checked';
	
	for ($i=8;$i<=17;$i++) {
		if (strpos($register['sort'],'field'.$i)===false) {
			$register['sort'] = $register['sort'].',field'.$i;
		}
	}
	
	return $register;
}

function qis_get_stored_reference() {
	$reference = get_option('qis_reference');
	if(!is_array($reference)) $reference = array(
		'referencenumber'=> 1,
		'referenceprefix'=> get_bloginfo('name').'/',
	);
	return $reference;
}

function qis_get_stored_formname() {
	$select = get_option('qis_select_form');
	return $select;
}

function qis_get_register_style() {
	$style = get_option('qis_register_style');
	
	if(!is_array($style)) $style = array();
	
	$default = array(
		'header'			=> '',
		'header-type'	   => 'h2',
		'header-size'	   => '1.6em',
		'header-colour'	 => '#465069',
		'font-colour'	   => '#343848',
		'text-font-family'  => 'arial, sans-serif',
		'text-font-size'	=> '1em',
		'text-font-colour'  => '#465069',
		'error-font-colour' => '#D31900',
		'error-border'	  => '1px solid #D31900',
		'form-width'		=> '100%',
		'submitwidth'	   => 'submitpercent',
		'submitposition'	=> 'submitleft',
		'border'			=> 'none',
		'form-border'	   => '1px solid #415063',
		'input-border'	  => '1px solid #415063',
		'input-required'	=> '1px solid #00C618',
		'bordercolour'	  => '#415063',
		'inputborderdefault' => '1px solid #415063',
		'inputborderrequired' => '1px solid #00C618',
		'inputbackground'   => '#FFFFFF',
		'inputfocus'		=> '#FFFFCC',
		'background'		=> 'theme',
		'backgroundhex'	 => '#FFF',
		'submit-colour'	 => '#FFF',
		'submit-background' => '#343848',
		'submit-hover-background' => '#415063',
		'submit-button'	 => '',
		'submit-border'	 => '1px solid #415063',
		'submitwidth'	   => 'submitpercent',
		'submitposition'	=> 'submitleft',
		'corners'		   => 5,
		'line_margin'	   => 'margin: 2px 0 3px 0;padding: 6px;',
		'labeltype'		 => 'plain'
	);
	$style = array_merge($default, $style);
	return $style;
}

function qis_get_stored_progress() {
	$options = get_option('qis_progress');
	
	if(!is_array($options)) $options = array();
	
	$default = array(
		'enabled'	   => '',
		'loanlabel'	 => 'Application Details',
		'emaillabel'	=> 'Your email address',
		'referencelabel'=> 'Application reference number',
		'submitlabel'   => 'View application',
		'progresslabel' => 'Application status',
		'nothingfound'  => 'No applications found',
		'showdetails'   => 'checked',
		'progresssteps' => 'Received,Review,Pending,Approved,Accepted,Repaid,Closed,Rejected',
		'currentstep'   => false,
		'rejected'	  => false,
		'background'	=> '#CCCCCC',
		'highlight'	 => '#00ff00',
		'rejectedcolour'=> '#ff1a1a'
		);
	
	$options = array_merge($default, $options);
	return $options;
}

function qis_get_stored_autoresponder ($theform) {
	$auto = get_option('qis_autoresponder'.$theform);
	if(!is_array($auto)) $auto = array();
	
	$fromemail = get_bloginfo('admin_email');
	$title = get_bloginfo('name');
	$default = array(
		'enable'			=> '',
		'subject'		   => __('Loan Application', 'quick-interest-slider'),
		'message'		   => __('Thank you for your application [name], we will be in contact soon. If you have any questions please reply to this email.', 'quick-interest-slider'),
		'useregistrationdetails' => 'checked',
		'registrationdetailsblurb' => __('Your application details', 'quick-interest-slider'),
		'sendcopy'		  => 'checked',
		'fromname'		  => $title,
		'fromemail'		 => $fromemail,
		'subscribemessage'  => __('Your email address has been confirmed', 'quick-interest-slider'),
		'subscribealready'  => __('You have already confirmed your email address','quick-interest-slider'),
		'subscribelink'	 => __('', 'quick-interest-slider'),
		'subscribeanchor'   => __('Click here to confirm your email address', 'quick-interest-slider'),
		'unsubscribemessage'=> __('Your details have been removed from the database', 'quick-interest-slider'),
		'unsubscribeanchor' => __('Click here to unsubscribe', 'quick-interest-slider'),
		'notification'	  => false,
		'noconfirmation'	=> ''
	);
	
	$auto = array_merge($default, $auto);
	
	return $auto;
}

function qis_get_stored_application_messages($theform) {
	$register = get_option('qis_application'.$theform);
	if(!is_array($register)) $register = array();
	
	$default = array(
		'enable' => '',
		'part2title'	=> __('Personal Details', 'quick-interest-slider'),
		'part2blurb'	=> __('Enter your details below to complete your applicaton. Required fields are marked in green', 'quick-interest-slider'),
		'part2submit'   => __('Complete Application', 'quick-interest-slider'),
		'termslabel'	=> __('I agree to the Terms and Conditions', 'quick-interest-slider'),
		'thankyoutitle' => __('Your Loan Application has been accepted', 'quick-interest-slider'),
		'thankyoublurb' => __('<p>An application for a loan of £[amount] in the name of [name] has been received and is being processed.</p><p>A copy of the application and the contract has been sent to [email].</p><p>You will be informed by email once processing is complete. If successful the money will be transferred to your bank account.</p>', 'quick-interest-slider'),
		'borrowvalues'  => 'You are applying to borrow [amount] for [period]',
		'reference'	 => 'Your application reference number is: [reference]',
		'changedetails' => 'Change these Details',
		'errortitle'	=> __('There is an error with your Application', 'quick-interest-slider'),
		'errorblurb'	=> __('Please complete all fields', 'quick-interest-slider'),
		'notificationsubject' => __('New application for', 'quick-interest-slider'),
		'attach_size'   => '10000000',
		'attach_type'   => 'docxpdfpngjpg',
		'attach_error_size' => 'File is too big ',
		'attach_error_type' => 'File type is not permitted',
		'section1'	  => __('Loan Details', 'quick-interest-slider'),
		'section2'	  => __('Personal Details', 'quick-interest-slider'),
		'section3'	  => __('Address', 'quick-interest-slider'),
		'section4'	  => __('Outgoings', 'quick-interest-slider'),
		'section5'	  => __('Employment and Income', 'quick-interest-slider'),
		'section6'	  => __('Additional Income', 'quick-interest-slider'),
		'section7'	  => __('Bank Details', 'quick-interest-slider'),
		'section8'	  => __('Document Upload', 'quick-interest-slider'),
		'section9'	  => __('Validaton', 'quick-interest-slider'),
		'section0description' => '',
		'section1description' => '',
		'section2description' => '',
		'section3description' => '',
		'section4description' => '',
		'section5description' => '',
		'section6description' => '',
		'section7description' => '',
		'section8description' => __('You must provide proof of your identity and address. Select the document type and upload a scan of that document', 'quick-interest-slider'),
		'section9description' => '',
		'use1'		  => 'checked',
		'use2'		  => 'checked',
		'use3'		  => 'checked',
		'use4'		  => 'checked',
		'use5'		  => 'checked',
		'use6'		  => 'checked',
		'use7'		  => 'checked',
		'use8'		  => 'checked',
		'use9'		  => 'checked',
	);
	
	$register = array_merge($default, $register);
	return $register;
}

function qis_get_stored_application ($theform) {
	$application = get_option('qis_full_application'.$theform);
	if(!is_array($application)) $application = array();
	
	$default = array(
		'loanreason'	=> array(
			'label'	 => __('Purpose of loan', 'quick-interest-slider'),
			'section'   => '1',
			'use'	   => 'checked',
			'type'	  => 'multi',
			'required'  => 'checked', 
			'options'   => __('Groceries,Utility bills,Home improvements,Travelling,Holiday,Entertainment,Repay other debts', 'quick-interest-slider')),
		'repaymentmeans' => array(
			'label'	 => __('How do you intend to pay this loan?', 'quick-interest-slider'),
			'section'   => '1', 
			'use'	   => 'checked',
			'type'	  => 'dropdown', 'required' => 'checked', 
			'options'   => __('Select...,Salary,Family,Friend,Savings,Another loan,Bonus,Extra Job', 'quick-interest-slider')),
		'dateofbirth'   => array(
			'label'	 => __('Date of Birth', 'quick-interest-slider'),
			'section'   => '2', 
			'use'	   => 'checked', 
			'type'	  => 'date', 
			'required'  => 'checked'),
		'maritalstatus' => array(
			'label'	 => __('Marital Status', 'quick-interest-slider'),
			'section'   => '2', 
			'use'	   => 'checked', 
			'type'	  => 'dropdown', 
			'required'  => 'checked',
			'options'   => __('Select...,Married,Single,Divorced,Widowed,Living together,Separated,Other', 'quick-interest-slider')),
		'gender'		=> array(
			'label'	 => __('Gender', 'quick-interest-slider'),
			'section'   => '2', 
			'use'	   => 'checked', 
			'type'	  => 'dropdown',
			'required'  => '',
			'options'   => __('Select...,Male,Female', 'quick-interest-slider')),
		'dependants'	=> array(
			'label'	 => __('Number of dependants', 'quick-interest-slider'),
			'section'   => '2', 
			'use'	   => 'checked', 
			'type'	  => 'text', 
			'required'  => ''),
		'preferedtime'  => array(
			'label'	 => __('Preferred time of call (if needed)', 'quick-interest-slider'),
			'section'   => '2', 
			'use'	   => 'checked', 
			'type'	  => 'dropdown', 
			'required'  => '',
			'options'   => __('Select...,9am - 1pm,1pm - 5pm,5pm - 9pm,Any time is suitable', 'quick-interest-slider')),
		
		'homephone'	 => array(
			'label'	 => __('Alternate phone number', 'quick-interest-slider'),
			'section'   => '3', 
			'use'	   => 'checked', 
			'type'	  => 'text', 
			'required'  => ''),
		'homename'	  => array(
			'label'	 => __('Building Name', 'quick-interest-slider'),
			'section'   => '3', 
			'use'	   => 'checked', 
			'type'	  => 'text', 
			'required'  => ''),
		'homeaddress'   => array(
			'label'	 => __('House/Flat number and road', 'quick-interest-slider'),
			'section'   => '3', 
			'use'	   => 'checked', 
			'type'	  => 'text', 
			'required'  => 'checked'),
		'hometown'	  => array(
			'label'	 => __('Town', 'quick-interest-slider'),
			'section'   => '3', 
			'use'	   => 'checked', 
			'type'	  => 'text', 
			'required'  => 'checked'),
		'homepostcode'  => array(
			'label'	 => __('Postcode', 'quick-interest-slider'),
			'section'   => '3', 
			'use'	   => 'checked', 
			'type'	  => 'text', 
			'required'  => 'checked'),
		'hometype'	  => array(
			'label'	 => __('Type of Residence', 'quick-interest-slider'),
			'section'   => '3', 
			'use'	   => 'checked', 
			'type'	  => 'dropdown', 
			'required'  => 'checked',
			'options'   =>__('Select...,Private tenant,Owner,Council tenant,Living with parents,Work/school accomodation,DSS', 'quick-interest-slider')),
		'hometime'	  => array(
			'label'	 => __('Time at Residence', 'quick-interest-slider'),
			'section'   => '3', 
			'use'	   => 'checked', 
			'type'	  => 'dropdown', 
			'required'  => 'checked',
			'options'   => __('Select...,0-6 months,7-11 months,1 year,2 years,3 years,4 years, 5 years or more', 'quick-interest-slider')),
		
		'billsfood'	 => array(
			'label'	 => __('Estimated monthly cost of Food and Groceries', 'quick-interest-slider'),
			'section'   => '4', 
			'use'	   => 'checked', 
			'type'	  => 'text', 
			'required'  => 'checked'),
		'billrecreation'=> array(
			'label'	 => __('Estimated monthly cost of recreation', 'quick-interest-slider'),
			'section'   => '4', 
			'use'	   => 'checked', 
			'type'	  => 'text', 
			'required'  => 'checked'),
		'billsloans'	=> array(
			'label'	 => __('Existing loan repayments', 'quick-interest-slider'),
			'section'   => '4', 
			'use'	   => 'checked', 
			'type'	  => 'text', 
			'required'  => 'checked'),
		'billsother'	=> array(
			'label'	 => __('Other Bills', 'quick-interest-slider'),
			'section'   => '4', 
			'use'	   => 'checked', 
			'type'	  => 'dropdown', 
			'required'  => 'checked',
			'options'   => __('Select...,Yes,No', 'quick-interest-slider')),
		'billsotheramount' => array(
			'label'	 => __('If yes, enter the monthly amount', 'quick-interest-slider'),
			'section'   => '4', 
			'use'	   => 'checked', 
			'type'	  => 'text',
			'required'  => ''),
		'workcompany'   => array(
			'label'	 => __('Company Name', 'quick-interest-slider'),
			'section'   => '5', 
			'use'	   => 'checked', 
			'type'	  => 'text', 
			'required'  => ''),
		'workemployer'  => array(
			'label'	 => __('Name of Employer', 'quick-interest-slider'),
			'section'   => '5', 
			'use'	   => 'checked', 
			'type'	  => 'text', 
			'required'  => ''),
		'worktitle'	 => array(
			'label'	 => __('Job title', 'quick-interest-slider'),
			'section'   => '5', 
			'use'	   => 'checked', 
			'type'	  => 'text', 
			'required'  => 'checked'),
		'workincome'	=> array(
			'label'	 => __('Monthly Net Income', 'quick-interest-slider'),
			'section'   => '5', 
			'use'	   => 'checked', 
			'type'	  => 'text', 
			'required'  => 'checked'),
		'workduration'  => array(
			'label'	 => __('Time in this employment', 'quick-interest-slider'),
			'section'   => '5', 
			'use'	   => 'checked',
			'type'	  => 'dropdown', 
			'required'  => 'checked',
			'options'   => __('Select...,0-6 months,7-11 months,1 year,2 years,3 years,4 years, 5 years or more', 'quick-interest-slider')),
		'workbank'	  => array(
			'label'	 => __('Is your income paid directly into your bank account?', 'quick-interest-slider'),
			'section'   => '5', 
			'use'	   => 'checked',
			'type'	  => 'dropdown', 
			'required'  => 'checked',
			'options'   => __('Select...,Yes,No', 'quick-interest-slider')),
		
		'additionalincome' => array(
			'label'	 => __('Select additional income sources', 'quick-interest-slider'),
			'section'   => '6', 
			'use'	   => 'checked', 
			'type'	  => 'multi', 
			'required'  => '', 
			'options'   => __('None,Partner\'s net income,Child benefits,Working Tax Credit and Child,Family Tax Credit, Overtime,Bonus,Additional work,Pension,Incapacity Benefits', 'quick-interest-slider')),

		'bankname'	  => array(
			'label'	 => __('Bank Name', 'quick-interest-slider'),
			'section'   => '7', 
			'use'	   => 'checked', 
			'type'	  => 'text', 
			'required'  => 'checked'),
		'bankaccount'   => array(
			'label'	 => __('Account Number', 'quick-interest-slider'),
			'section'   => '7', 
			'use'	   => 'checked', 
			'type'	  => 'text', 
			'required'  => 'checked'),
		'banksort'	  => array(
			'label'	 => __('Sort Code', 'quick-interest-slider'),
			'section'   => '7', 
			'use'	   => 'checked', 
			'type'	  => 'text', 
			'required'  => 'checked'),
		'bankiban'	  => array(
			'label'	 => __('IBAN Number', 'quick-interest-slider'),
			'section'   => '7', 
			'use'	   => 'checked', 
			'type'	  => 'text', 
			'required'  => 'checked'),
		'bankswift'	 => array(
			'label'	 => __('SWIFT Number', 'quick-interest-slider'),
			'section'   => '7', 
			'use'	   => 'checked', 
			'type'	  => 'text', 
			'required'  => 'checked'),
		'bankaddress'   => array(
			'label'	 => __('Address', 'quick-interest-slider'),
			'section'   => '7', 
			'use'	   => 'checked', 
			'type'	  => 'text', 
			'required'  => 'checked'),
		'bankcountry'   => array(
			'label'	 => __('Country', 'quick-interest-slider'),
			'section'   => '7', 
			'use'	   => 'checked', 
			'type'	  => 'dropdown', 
			'required'  => 'checked',
			'options'   => __('Austria,Belgium,Canada,Denmark,United Kingdom', 'quick-interest-slider')),
		
		'identityproof' => array(
			'label'	 => __('Proof of identity document', 'quick-interest-slider'),
			'section'   => '8', 
			'use'	   => 'checked', 
			'type'	  => 'upload', 
			'required'  => 'checked',
			'options'   => __('Passport,Driving License,ID Card', 'quick-interest-slider')),
		'addressproof'  => array(
			'label'	 => __('Proof of address document', 'quick-interest-slider'),
			'section'   => '8', 
			'use'	   => 'checked', 
			'type'	  => 'upload',
			'required'  => 'checked',
			'options'   => __('Utility bill, Notarised letter, Driving license', 'quick-interest-slider')),
		
		'terms'		 => array(
			'label'	 => __('I agree to the [a]Terms and conditions[/a].', 'quick-interest-slider'),
			'section'   => '9', 
			'use'	   => 'checked', 
			'type'	  => 'link', 
			'required'  => 'checked',
			'termstarget' => false,
			'termsurl'  => ''),
		'documents'	 => array(
			'label'	 => __('I have uploaded proof of identity documents.', 'quick-interest-slider'),
			'section'   => '9', 
			'use'	   => 'checked', 
			'type'	  => 'checkbox', 
			'required'  => 'checked'),
		'accuracy'	  => array(
			'label'	 => __('Information given is accurate to the best of my knowledge.', 'quick-interest-slider'),
			'section'   => '9', 
			'use'	   => 'checked', 
			'type'	  => 'checkbox', 
			'required'  => 'checked'),
		
		'oldhomename'   => array(
			'label'	 => __('Previous Building Name', 'quick-interest-slider'),
			'section'   => '3', 
			'use'	   => 'checked', 
			'class'	 => 'sc_app_hidden', 
			'type'	  => 'text', 
			'required'  => ''),
		'oldhomeaddress'=> array(
			'label'	 => __('Previous House/Flat number and road', 'quick-interest-slider'),
			'section'   => '3', 
			'use'	   => 'checked', 
			'class'	 => 'sc_app_hidden', 
			'type'	  => 'text', 
			'required'  => ''),
		'oldhometown'   => array(
			'label'	 => __('Previous Town', 'quick-interest-slider'),
			'section'   => '3', 
			'use'	   => 'checked', 
			'class'	 => 'sc_app_hidden', 
			'type'	  => 'text', 
			'required'  => ''),
		'oldhomepostcode' => array(
			'label'	 => __('Previous Postcode', 'quick-interest-slider'),
			'section'   => '3', 
			'use'	   => 'checked', 
			'class'	 => 'sc_app_hidden', 
			'type'	  => 'text', 
			'required'  => ''),
		'oldhometype'   => array(
			'label'	 => __('Type of Residence', 'quick-interest-slider'),
			'section'   => '3', 
			'use'	   => 'checked', 
			'class'	 => 'sc_app_hidden', 
			'type'	  => 'dropdown', 
			'required'  => '',
			'options'   =>__('Select...,Private tenant,Owner,Council tenant,Living with parents,Work/school accomodation', 'quick-interest-slider')),
		'oldhometime'   => array(
			'label'	 => __('Time at Residence', 'quick-interest-slider'),
			'section'   => '3', 
			'use'	   => 'checked', 
			'class'	 => 'sc_app_hidden', 
			'type'	  => 'dropdown', 
			'required'  => '',
			'options'   => __('Select...,0-6 months,7-11 months,1 year,2 years,3 years,4 years, 5 years or more', 'quick-interest-slider')),
		);

	$application = qis_splice($application, $default);
	return $application;
}

function qis_get_stored_ouputtable() {
	$options = get_option('qis_outputtable');
	if(!is_array($options)) $options = array();
	
	$default = array(
		'sort'			  => 'principle,term,rate,interest,processing,date,downpayment,mitigated,repayment,total',
		'userepayment'	  => '',
		'userate'		   => '',
		'useinterest'	   => '',
		'usetotal'		  => '',
		'useprinciple'	  => '',
		'useterm'		   => '',
		'useprocessing'	 => '',
		'usedate'		   => '',
		'usedownpayment'	=> '',
		'usemitigated'	  => '',
		'repaymentcaption'  => 'Repayment amount',
		'ratecaption'	   => 'Interest rate',
		'interestcaption'   => 'Interest to pay',
		'totalcaption'	  => 'Total to pay',
		'principlecaption'  => 'Loan amount',
		'termcaption'	   => 'Loan Term',
		'processingcaption' => 'Processing',
		'datecaption'	   => 'Repayment Date',
		'downpaymentcaption'=> 'Downpayment',
		'mitigatedcaption'  => 'Mitigated loan amount',
		'values-padding'	 => '3',
		'values-strong'	 => '',
		'values-colour'	 => ''
	);
	$options = array_merge($default, $options);
	
	return $options;
}

function qis_apply_defaults(array $default, array $settings) {
	/*
		Just fill in blanks in the defaults
	*/
	foreach ($settings as $key => $value) {
		if (!isset($default[$key])) {
			$default[$key] = $value;
		} else {
			if (is_array($value)) {
				$default[$key] = qis_apply_defaults($default[$key],$value);
			}
		}
	}
	
	/*
		Now merge settings ontop of the defaults
	*/
	return qis_splice($settings, $default);
}

function qis_splice($a1,$a2) {
	foreach ($a2 as $a2k => $a2v) {
		if (is_array($a2v)) {
			if (!isset($a1[$a2k])) $a1[$a2k] = $a2v;
			else {
				if (is_array($a1[$a2k])) $a1[$a2k] = qis_splice($a1[$a2k],$a2v);
			}
		} else {
			if (!isset($a1[$a2k])) $a1[$a2k] = $a2v;
		}
	}
	return $a1;
}