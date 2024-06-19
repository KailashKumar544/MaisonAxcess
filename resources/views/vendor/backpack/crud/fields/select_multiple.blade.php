{{-- select multiple --}}
@php
    if (!isset($field['options'])) {
        $options = $field['model']::all();
    } else {
        $options = call_user_func($field['options'], $field['model']::query());
    }
    $field['allows_null'] = $field['allows_null'] ?? true;

    $field['value'] = old_empty_or_null($field['name'], collect()) ??  $field['value'] ?? $field['default'] ?? collect();

    if (is_a($field['value'], \Illuminate\Support\Collection::class)) {
        $field['value'] = $field['value']->pluck(app($field['model'])->getKeyName())->toArray();
    }
    
@endphp

@include('crud::fields.inc.wrapper_start')

    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')
    {{-- To make sure a value gets submitted even if the "select multiple" is empty, we need a hidden input --}}
    <input type="hidden" name="{{ $field['name'] }}" value="" @if(in_array('disabled', $field['attributes'] ?? [])) disabled @endif />
    <select
    name="{{ $field['name'] }}[]"
    @include('crud::fields.inc.attributes', ['default_class' => 'form-control form-select'])
    bp-field-main-input
    id="multiselect"
    multiple
>
    @if (count($options))
        @foreach ($options as $key =>$value)
            @if(in_array($key, $field['value']))
                <option value="{{ $key }}" selected>{{ $value }}</option>
            @else
                <option value="{{ $key }}">{{ $value }}</option>
            @endif
        @endforeach
    @endif
</select>

<div id="selectedItems">
@if (count($field['value']))
        @foreach ($field['value'] as $selectedItemId)
        @if(isset($options[$selectedItemId]))
            <div class="selected-item">
                {{ $options[$selectedItemId] }}
                <a class="remove-item" data-value="{{ $selectedItemId }}"><i class="la la-close"></i></a>
            </div>
            @endif
        @endforeach
    @endif
</div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif

@include('crud::fields.inc.wrapper_end')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        // Add change event listener to the multi-select field
        $('#multiselect').change(function () {
            // Clear the selected items div
            $('#selectedItems').empty();
            
            // Iterate over each selected option
            $(this).find('option:selected').each(function () {
                // Create a div to display the selected item
                var selectedItemDiv = $('<div class="selected-item"></div>');
                
                // Add text and a button to remove the selected item
                selectedItemDiv.text($(this).text());
                selectedItemDiv.append('<a class="remove-item" data-value="' + $(this).val() + '"><i class="la la-close"></i></a>');
                
                // Append the selected item div to the selected items container
                $('#selectedItems').append(selectedItemDiv);
            });
        });
        
        // Add click event listener to remove items
        $('#selectedItems').on('click', '.remove-item', function () {
            var valueToRemove = $(this).data('value');
            
            // Remove the corresponding option from the multi-select field
            $('#multiselect').find('option[value="' + valueToRemove + '"]').prop('selected', false);
            
            // Trigger change event to update the selected items div
            $('#multiselect').trigger('change');
        });
    });
</script>


