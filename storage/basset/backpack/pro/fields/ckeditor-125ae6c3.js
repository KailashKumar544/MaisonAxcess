async function bpFieldInitCKEditorElement(element) {
    let ckeditor = await ClassicEditor.create(element[0], element.data('options'));
    if(!ckeditor) return;

    element.on('CrudField:delete', function(e) {
        ckeditor.destroy();
    });

    // trigger the change event on textarea when ckeditor changes
    ckeditor.editing.view.document.on('layoutChanged', function(e) {
        element.trigger('change');
    });

    element.on('CrudField:disable', function(e) {
        ckeditor.enableReadOnlyMode('CrudField');
    });

    element.on('CrudField:enable', function(e) {
        ckeditor.disableReadOnlyMode('CrudField');
    });
}

            