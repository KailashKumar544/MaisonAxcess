document.styleSheets[0].addRule('.select2-selection__clear::after','content:  "Clear";');

// this is the function responsible for querying the ajax endpoint with our query string, emulating the select2
// ajax search mechanism.
var performAjaxSearch = function (element, $searchString) {
    var $includeAllFormFields = element.attr('data-include-all-form-fields')=='false' ? false : true;
    var $refreshUrl = element.attr('data-data-source');
    var $method = element.attr('data-method');
    var form = element.closest('form')

    return new Promise(function (resolve, reject) {
        $.ajax({
            url: $refreshUrl,
            data: (function() {
                if ($includeAllFormFields) {
                            return {
                                q: $searchString, // search term
                                form: form.serializeArray() // all other form inputs
                            };
                        } else {
                            return {
                                q: $searchString, // search term
                            };
                        }
            })(),
            type: $method,
            success: function (result) {

                resolve(result);
            },
            error: function (result) {

                reject(result);
            }
        });
    });
};

//this setup the "+Add" button in page with corresponding click handler.
//when clicked, fetches the html for the modal to show

function setupInlineCreateButtons(element) {
    var $fieldEntity = element.attr('data-field-related-name');
    var $inlineCreateButtonElement = $(element).parent().find('.inline-create-button');
    var $inlineModalRoute = element.attr('data-inline-modal-route');
    var $inlineModalClass = element.attr('data-inline-modal-class');
    var $parentLoadedFields = element.attr('data-parent-loaded-fields');
    var $includeMainFormFields = element.attr('data-include-main-form-fields') == 'false' ? false : (element.attr('data-include-main-form-fields') == 'true' ? true : element.attr('data-include-main-form-fields'));
    var $form = element.closest('form');

    $inlineCreateButtonElement.on('click', function () {

        //we change button state so users know something is happening.
        var loadingText = '<span class="la la-spinner la-spin" style="font-size:18px;"></span>';
        if ($inlineCreateButtonElement.html() !== loadingText) {
            $inlineCreateButtonElement.data('original-text', $inlineCreateButtonElement.html());
            $inlineCreateButtonElement.html(loadingText);
        }

        //prepare main form fields to be submitted in case there are some.
        if (typeof $includeMainFormFields === "boolean" && $includeMainFormFields === true) {
            var $toPass = $form.serializeArray();
        } else {
            if (typeof $includeMainFormFields !== "boolean") {
                var $fields = JSON.parse($includeMainFormFields);
                var $serializedForm = $form.serializeArray();
                var $toPass = [];

                $fields.forEach(function(value, index) {
                    $valueFromForm = $serializedForm.filter(function(field) {
                        return field.name === value
                    });
                    $toPass.push($valueFromForm[0]);

                });
            }
        }
        $.ajax({
            url: $inlineModalRoute,
            data: (function() {
                if (typeof $includeMainFormFields === 'array' || $includeMainFormFields) {
                    return {
                        'entity': $fieldEntity,
                        'modal_class' : $inlineModalClass,
                        'parent_loaded_assets' : $parentLoadedFields,
                        'main_form_fields' : $toPass
                    };
                } else {
                    return {
                        'entity': $fieldEntity,
                        'modal_class' : $inlineModalClass,
                        'parent_loaded_assets' : $parentLoadedFields
                    };
                }
            })(),
            type: 'POST',
            success: function (result) {
                $('body').append(result);
                triggerModal(element);

            },
            error: function (result) {
                if (!element.data('debug')) {
                new Noty({
                        type: "error",
                        text: "<strong>Error</strong><br>Error loading page. Please refresh the page."
                    }).show();
                }
                $inlineCreateButtonElement.html($inlineCreateButtonElement.data('original-text'));
            }
        });
    });
}

// when an entity is created we query the ajax endpoint to check if the created option is returned.
function ajaxSearch(element, created) {
    var $relatedAttribute = element.attr('data-field-attribute');
    var $relatedKeyName = element.attr('data-connected-entity-key-name');
    var $searchString = created[$relatedAttribute];

    //we run the promise with ajax call to search endpoint to check if we got the created entity back
    //in case we do, we add it to the selected options.
    performAjaxSearch(element, $searchString).then(function(result) {
        var inCreated = $.map(result.data, function (item) {
            var $itemText = processItemText(item, $relatedAttribute);
            var $createdText = processItemText(created, $relatedAttribute);
            if ($itemText == $createdText) {
                    return {
                        text: $itemText,
                        id: item[$relatedKeyName]
                    }
                }
        });

        if (inCreated.length) {
            selectOption(element, created);
        }
    });
}

/**
 * This is the function called when button to add is pressed,
 * It triggers the modal on page and initialize the fields
 *
 * @param element {HTMLElement}
 */
function triggerModal(element) {
    const $modalInstance = new bootstrap.Modal(document.getElementById('inline-create-dialog'));
    const $modalElement = $('#inline-create-dialog');
    const $fieldName = element.attr('data-field-related-name');
    const $modalSaveButton = $modalElement.find('#saveButton');
    const $modalCancelButton = $modalElement.find('#cancelButton');
    const $inlineCreateRoute = element.attr('data-inline-create-route');
    const $force_select = element.attr('data-force-select') == 'true';

    initializeFieldsWithJavascript($(document.getElementById($fieldName+"-inline-create-form")));

    $modalCancelButton.on('click', function () {
        $modalInstance.hide();
    });

    // When you hit save on modal save button.
    $modalSaveButton.on('click', function () {

        $form = document.getElementById($fieldName+"-inline-create-form");

        // This is needed otherwise fields like ckeditor don't post their value.
        $($form).trigger('form-pre-serialize');

        var $formData = new FormData($form);

        // We change button state so users know something is happening.
        // We also disable it to prevent double form submition
        var loadingText = '<i class="la la-spinner la-spin"></i>Saving...';
        if ($modalSaveButton.html() !== loadingText) {
            $modalSaveButton.data('original-text', $(this).html());
            $modalSaveButton.html(loadingText);
            $modalSaveButton.prop('disabled', true);
        }

        $.ajax({
            url: $inlineCreateRoute,
            data: $formData,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (result) {

                $createdEntity = result.data;

                if (!$force_select) {
                    // If developer did not force the created entity to be selected we first try to
                    // Check if created is still available upon model re-search.
                    ajaxSearch(element, result.data);

                } else {
                    selectOption(element, result.data);
                }

                $modalInstance.hide();

                new Noty({
                    type: "info",
                    text: 'Related entry has been created and selected.',
                }).show();
            },
            error: function (result) {

                const $errors = result.responseJSON.errors;

                let message = '';
                for (let i in $errors) {
                    message += $errors[i] + ' \n';
                }

                new Noty({
                    type: "error",
                    text: '<strong>Could not create related entry.</strong><br> '+message,
                }).show();

                // Revert save button back to normal
                $modalSaveButton.prop('disabled', false);
                $modalSaveButton.html($modalSaveButton.data('original-text'));
            }
        });
    });

    $modalElement.on('hidden.bs.modal', function (e) {
        // When modal is closed (canceled or success submitted) we revert the "+ Add" loading state back to normal.
        const $inlineCreateButtonElement = $(element).parent().find('.inline-create-button');
        $inlineCreateButtonElement.html($inlineCreateButtonElement.data('original-text'));

        $modalElement.remove();
    });


    $modalElement.on('shown.bs.modal', function () {
        $modalElement.on('keyup',  function (e) {
            if ($modalElement.hasClass('show')) {
                if (e.key === 'Enter' && e.target.nodeName === 'INPUT') {
                    if($(e.target).hasClass('select2-search__field')) {
                        return false;
                    }

                    $modalSaveButton.click();
                }
            }
            return false;
        });
    });

    // All is ready, let's show the modal!
    $modalInstance.show();
}

//function responsible for adding an option to the select
//it parses any previous options in case of select multiple.
function selectOption(element, option) {
    var $relatedAttribute = element.attr('data-field-attribute');
    var $relatedKeyName = element.attr('data-connected-entity-key-name');
    var $multiple = element.prop('multiple');

    var $optionText = processItemText(option, $relatedAttribute);

    var $option = new Option($optionText, option[$relatedKeyName]);

        $(element).append($option);

        if ($multiple) {
            //we get any options previously selected
            var selectedOptions = $(element).val();

            //we add the option to the already selected array.
            selectedOptions.push(option[$relatedKeyName]);
            $(element).val(selectedOptions);

        } else {
            $(element).val(option[$relatedKeyName]);
        }

        $(element).trigger('change');

}



function bpFieldInitFetchOrCreateElement(element) {
    var form = element.closest('form');
    var $isFieldInline = element.data('field-is-inline');
    var $ajax = element.attr('data-field-ajax') == 'true' ? true : false;
    var $placeholder = element.attr('data-placeholder');
    var $minimumInputLength = element.attr('data-minimum-input-length');
    var $dataSource = element.attr('data-data-source');
    var $method = element.attr('data-method');
    var $fieldAttribute = element.attr('data-field-attribute');
    var $connectedEntityKeyName = element.attr('data-connected-entity-key-name');
    var $includeAllFormFields = element.attr('data-include-all-form-fields')=='false' ? false : true;
    var $dependencies = JSON.parse(element.attr('data-dependencies'));
    var $modelKey = element.attr('data-model-local-key');
    var $allows_null = (element.attr('data-allows-null') == 'true') ? true : false;
    var $multiple = element.prop('multiple');
    var $ajaxDelay = element.attr('data-ajax-delay');
    var $isPivotSelect = element.data('is-pivot-select');
    var $fieldCleanName = element.attr('data-repeatable-input-name') ?? element.attr('name');

    var FetchOrCreateAjaxFetchSelectedEntry = function (element) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: $dataSource,
                data: {
                    'keys': $selectedOptions
                },
                type: $method,
                success: function (result) {

                    resolve(result);
                },
                error: function (result) {
                    reject(result);
                }
            });
        });
    };

    //Checks if field is not beeing inserted in one inline create modal and setup buttons
    if(!$isFieldInline) {
        setupInlineCreateButtons(element);
    }

    if (!element.hasClass("select2-hidden-accessible")) {

        element.select2({
            theme: "bootstrap",
            placeholder: $placeholder,
            minimumInputLength: $minimumInputLength,
            allowClear: $allows_null,
            ajax: {
            url: $dataSource,
            dropdownParent: $isFieldInline ? $('#inline-create-dialog .modal-content') : $(document.body),
            type: $method,
            dataType: 'json',
            delay: $ajaxDelay,
            data: function (params) {
                if ($includeAllFormFields) {
                    return {
                        q: params.term, // search term
                        page: params.page, // pagination
                        form: form.serializeArray(), // all other form inputs
                        triggeredBy:
                        {
                            'rowNumber': element.attr('data-row-number') !== 'undefined' ? element.attr('data-row-number')-1 : false,
                            'fieldName': $fieldCleanName
                        }
                    };
                } else {
                    return {
                        q: params.term, // search term
                        page: params.page, // pagination
                    };
                }
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                // if field is a pivot select we are gona get other pivot values so we can disable them from selection.
                if ($isPivotSelect) {
                    let containerName = element.data('repeatable-input-name');

                    if(containerName.indexOf('[') > -1) {
                        containerName = containerName.substring(0, containerName.indexOf('['));
                    }

                    let pivotsContainer = element.closest('div[data-repeatable-holder='+containerName+']');
                    var selectedValues = [];

                   pivotsContainer.children().each(function(i,container) {
                        $(container).find('select').each(function(i, el) {
                            if(typeof $(el).attr('data-is-pivot-select') !== 'undefined' && $(el).attr('data-is-pivot-select') != "false" && $(el).val()) {
                                selectedValues.push($(el).val());
                            }
                        });
                    });
                }
                //if we have data.data here it means we returned a paginated instance from controller.
                //otherwise we returned one or more entries unpaginated.
                let paginate = false;

                if (data.data) {
                    paginate = data.next_page_url !== null;
                    data = data.data;
                }

                return {
                    results: $.map(data, function (item) {
                        var $itemText = processItemText(item, $fieldAttribute);
                        let disabled = false;

                        if(selectedValues && selectedValues.some(e => e == item[$connectedEntityKeyName])) {
                            disabled = true;
                        }

                        return {
                            text: $itemText,
                            id: item[$connectedEntityKeyName],
                            disabled: disabled
                        }
                    }),
                    pagination: {
                            more: paginate
                    }
                };
            },
            cache: true
        },
    });

        // if any dependencies have been declared
        // when one of those dependencies changes value
        // reset the select2 value
        for (var i=0; i < $dependencies.length; i++) {
            var $dependency = $dependencies[i];
            //if element does not have a custom-selector attribute we use the name attribute
            if(typeof element.attr('data-custom-selector') === 'undefined') {
                form.find('[name="'+$dependency+'"], [name="'+$dependency+'[]"]').change(function(el) {
                        $(element.find('option:not([value=""])')).remove();
                        element.val(null).trigger("change");
                });
            }else{
                // we get the row number and custom selector from where element is called
                let rowNumber = element.attr('data-row-number');
                let selector = element.attr('data-custom-selector');

                // replace in the custom selector string the corresponding row and dependency name to match
                    selector = selector
                        .replaceAll('%DEPENDENCY%', $dependency)
                        .replaceAll('%ROW%', rowNumber);

                $(selector).change(function (el) {
                    $(element.find('option:not([value=""])')).remove();
                    element.val(null).trigger("change");
                });
            }
        }
    }
}


if (typeof processItemText !== 'function') {
    function processItemText(item, $fieldAttribute) {
        var $appLang = 'en';
        var $appLangFallback = 'en';
        var $emptyTranslation = '(empty)';
        var $itemField = item[$fieldAttribute];

        // try to retreive the item in app language; then fallback language; then first entry; if nothing found empty translation string
        return typeof $itemField === 'object' && $itemField !== null
        ? $itemField[$appLang] ? $itemField[$appLang] : $itemField[$appLangFallback] ? $itemField[$appLangFallback] : Object.values($itemField)[0] ? Object.values($itemField)[0] : $emptyTranslation
            : $itemField;
    }
}

    