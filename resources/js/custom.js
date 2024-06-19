$(document).ready(function () {
    // Add your JavaScript code here
    // For example, show/hide category field based on role selection
    $('#role-select').change(function () {
        var selectedRole = $(this).val();
        if (selectedRole === 'admin') {
            $('.category-field-wrapper').show();
        } else {
            $('.category-field-wrapper').hide();
        }
    });
});