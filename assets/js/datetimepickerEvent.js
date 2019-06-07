// The datetimepicker is used in the event part for define the begin and the end of the datetimepicker in function of event dates.
$('.dateTimePickerEvent').bootstrapMaterialDatePicker({
    format: 'DD-MM-YYYY HH:mm',
    lang: 'fr',
    minDate: moment($('#startDate').val()),
    maxDate: moment($('#endDate').val())
});


