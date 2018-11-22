// Couldn't get this to work
//wp.blocks.unregisterBlockType( 'core/cover-image' );

var el = wp.element.createElement;
var registerBlockType = wp.blocks.registerBlockType;

registerBlockType('j3/video', {
    title: 'Embeded Video',
    icon: 'video',
    category: 'embed',
    keywords: ['video', 'vimeo', 'youtube'],
    attributes: {
        embedcode: {
            type: 'string',
            selector: 'p',
        }
    },
    edit: function( props ) {
	var className = props.className;
	var embedcode = props.attributes.embedcode;
	function updateMessage( event ) {
	    props.setAttributes( { embedcode: event.target.value } );
	}

	return el(
	    'p', 
	    {},
	    el(
		'textarea',
		{ value: embedcode, onChange: updateMessage }
	    ) 
	);
    },
    save: function( props ) {
	var embedcode = props.attributes.embedcode;

        // XXX this gets escaped
        return ( '[video]' + embedcode + '[/video]');
    },
});
