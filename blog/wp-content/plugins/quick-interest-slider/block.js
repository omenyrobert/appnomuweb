var el = wp.element.createElement,
	registerBlockType = wp.blocks.registerBlockType,
	ServerSideRender = wp.components.ServerSideRender,
	TextControl = wp.components.TextControl,
	RadioControl = wp.components.RadioControl,
	SelectControl = wp.components.SelectControl,
	TextareaControl = wp.components.TextareaControl,
	CheckboxControl = wp.components.CheckboxControl,
	InspectorControls = wp.editor.InspectorControls;

registerBlockType( 'quick-interest-slider/block', {
	title: 'Loan Calculator',
	description: 'Displays the sliders',
	icon: 'admin-settings',
	category: 'widgets',
	edit: function( props ) {		
		return [
			el( 'h2', // Tag type.
			   {
				className: props.className,
				},
				'Loan calculator ' + props.attributes.calculator
			  ),
			el( InspectorControls, {},
			   el( SelectControl, {
				'type':'number',
				'label':'Calculator Number:',
				'value':props.attributes.calculator,
				'options': [
					{'label':'1','value':1},
					{'label':'2','value':2},
					{'label':'3','value':3},
					{'label':'4','value':4},
					{'label':'5','value':5},
					{'label':'6','value':6},
					{'label':'7','value':7},
					{'label':'8','value':8},
					{'label':'9','value':9},
					{'label':'10','value':10},
				],
				onChange: ( option ) => { props.setAttributes( { calculator: option } ); }
					}
				  ),
			   ),
		];
	},

	// We're going to be rendering in PHP, so save() can just return null.
	save: function() {
		return null;
	},
} );