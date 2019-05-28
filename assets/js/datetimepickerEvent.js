$('.dateTimePickerEvent').bootstrapMaterialDatePicker({
    format: 'DD-MM-YYYY HH:mm',
    lang: 'fr',
    minDate: moment($('#startDate').val()),
    maxDate: moment($('#endDate').val())
});


