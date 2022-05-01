$ = jQuery;

var fx = {'lines':$('#currencies tbody tr').length,'addLine':function(t) {
	this.lines++;
	
	var newLine = $(qis_template.raw);
	newLine.find('.input-symbol').attr({'name':'currency_array['+this.lines+'][symbol]','value':''});
	newLine.find('.input-iso').attr({'name':'currency_array['+this.lines+'][iso]','value':''});
	newLine.find('.input-name').attr({'name':'currency_array['+this.lines+'][name]','value':''});

	$('#currencies tbody').append(newLine);
	newLine.find('.fx_remove_line').click(qis_remove_line);
	
},removeLine:function(t) {
	$(t).closest('tr').remove();
}};

jQuery(document).ready(function($){
	var custom_uploader;
	$('#qis_upload_background_image').click(function(e) {
		e.preventDefault();
		if (custom_uploader) {custom_uploader.open();return;}
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: 'Background Image',button: {text: 'Insert Image'},multiple: false});
		custom_uploader.on('select', function() {
			attachment = custom_uploader.state().get('selection').first().toJSON();
			$('#qis_background_image').val(attachment.url);
		});
		custom_uploader.open();
	});
	
	$('.qis-color').wpColorPicker();
	
	$selector = $('#chkCurrency,#chkFX');
	$selector.change(function() {
		if ($selector.is(':checked')) $("#showCurrencies").show("slow");
		else {
			$("#showCurrencies").hide("slow");
		}
	});
	
	$('.fx_remove_line').click(qis_remove_line);
	
	$('.fx_new_line').click(function() {
		fx.addLine(this);
	});
	
	/*
		Some Javascript for the interest checkboxes
	*/
	$('input[name=interestdisplay]').change(function() { });
	$('.qis-interest-input').click(function(e) { 
		var t = $(this);
		window.setTimeout(function() {
			t.parent('li').click();
		},10);
		e.stopPropagation();
		e.preventDefault();
		return false; 
	});
	$('.qisinterest li').click(function() {
		
		var t = $(this), r = t.find('input[type=checkbox]'), v = t.find('input[type=radio]').prop('value');
		if (r.is(':checked')) {
			// Uncheck everything
			t.find('input').prop('checked',false);
			$('#qis-interest-div').hide();
			$('.qis-interest').hide();
			t.removeClass('selected');
			return false;
		}
		
		qis_do_standard(t,r,v);
	});
	
	// Add default behavior for selected checkbox
	if ($('.qis-interest-input:checked').size()) {
		var r = $('.qis-interest-input:checked');
		var t = r.parent('li');
		var v = t.find('input[type=radio]').prop('value');
		qis_do_standard(t,r,v);
	}
	
		/*
		Some Javascript for the downpayment checkboxes
	*/
	$('input[name=downpaymentdisplay]').change(function() { });
	$('.qis-downpayment-input').click(function(e) { 
		var t = $(this);
		window.setTimeout(function() {
			t.parent('li').click();
		},10);
		e.stopPropagation();
		e.preventDefault();
		return false; 
	});
	$('.qisdownpayment li').click(function() {
		
		var t = $(this), r = t.find('input[type=checkbox]'), v = t.find('input[type=radio]').prop('value');
		if (r.is(':checked')) {
			// Uncheck everything
			t.find('input').prop('checked',false);
			$('#qis-downpayment-div').hide();
			$('.qis-downpayment').hide();
			t.removeClass('selected');
			return false;
		}
		
		qis_do_downpayment(t,r,v);
	});
	
	// Add default behavior for selected checkbox
	if ($('.qis-downpayment-input:checked').size()) {
		var r = $('.qis-downpayment-input:checked');
		var t = r.parent('li');
		var v = t.find('input[type=radio]').prop('value');
		qis_do_downpayment(t,r,v);
	}
 
	// Add default behavior for selected term slider type
	$('.qis-tabination ul li input[type=checkbox]').on('inputchange',function() {
		var main = $(this).closest('.qis-tabination');
		main.find('.qis-tabination-tab').hide().filter('#tab-'+$(this).prop('id')).show();
	});
	
	$('.qis-tabination li').click(function() {
		
		var main = $(this).closest('ul');
		
		var deselect = false;
		if ($(this).is('.selected')) deselect = true;
		
		main.find('li').removeClass('selected');
		main.find('li input[type=checkbox]').prop('checked',false);
		
		if (!deselect) {
			$(this).find('input[type=checkbox]').prop('checked',true).trigger('inputchange');;
			$(this).addClass('selected');
		} else {
			main.closest('.qis-tabination').find('.qis-tabination-tab').hide();
		}
		
	});
	
	$('.qis-tabination ul li input[type=checkbox]:checked').trigger('inputchange');
	
});

function qis_do_standard(t,r,v) {
	$('#qis-interest-div').show();
	$('.qis-interest').hide();
	$('#qis-interest-'+v).show();
	$('.qis-interest-input').prop('checked',false);
	r.prop('checked',true);
	
	t.parent().find('li').removeClass('selected');
	t.addClass('selected');
}

function qis_do_downpayment(t,r,v) {
	$('#qis-downpayment-div').show();
	$('.qis-downpayment').hide();
	$('#qis-downpayment-'+v).show();
	$('.qis-downpayment-input').prop('checked',false);
	r.prop('checked',true);
	
	t.parent().find('li').removeClass('selected');
	t.addClass('selected');
}

function qis_remove_line() {
	fx.removeLine(this);
}