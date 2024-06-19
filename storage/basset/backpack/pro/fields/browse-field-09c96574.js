// this global variable is used to remember what input to update with the file path
// because elfinder is actually loaded in an iframe by colorbox
var elfinderTarget = false;

// function to update the file selected by elfinder
function processSelectedFile(filePath, requestingField) {
	elfinderTarget.val(filePath.replace(/\\/g,"/"));
	elfinderTarget.trigger('change');
	elfinderTarget = false;
}

function bpFieldInitBrowseElement(element) {
	var triggerUrl = element.data('elfinder-trigger-url')
	var name = element.attr('name');

	element.parent('.input-group').children('button.popup_selector').click(function (event) {
	    event.preventDefault();

	    elfinderTarget = element;

	    // trigger the reveal modal with elfinder inside
	    $.colorbox({
	        href: triggerUrl + '/' + name,
	        fastIframe: false,
	        iframe: true,
	        width: '80%',
	        height: '80%'
	    });
	});

	element.bind('select', function(event) { // called on file(s) select/unselect
		element.trigger('change');
	});

	element.parent('.input-group').children('button.clear_elfinder_picker').click(function (event) {
	    event.preventDefault();
	    element.val("").trigger('change');
	});

	element.on('CrudField:disable', function(e) {
		element.parent('.input-group').children('button.popup_selector').prop('disabled','disabled');
		element.parent('.input-group').children('button.clear_elfinder_picker').prop('disabled','disabled');
	});

	element.on('CrudField:enable', function(e) {
		element.parent('.input-group').children('button.popup_selector').removeAttr('disabled');
		element.parent('.input-group').children('button.clear_elfinder_picker').removeAttr('disabled');
	});
}

		