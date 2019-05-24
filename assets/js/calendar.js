// Print a calendar
$(function () {
    $('#calendar').fullCalendar({
        locale: 'fr',
        themeSystem: 'bootstrap4',
        businessHours: false,
        defaultView: 'agendaWeek',
        editable: true,
        eventLimit: true,
        timeFormat: 'H:mm',
        slotLabelFormat: ['H:mm'],
        allDaySlot: false,
        header: {
            left: 'title',
            right: 'agendaWeek,agendaDay',
            center: 'prev,next'
        },
        visibleRange: function () {
            return {
                start: moment($('#startDate').val()),
                end: moment($('#endDate').val()),
            };
        },
        validRange: function () {
            return {
                start: moment($('#startDate').val()),
                end: moment($('#endDate').val()),
            };
        },
        dayRender: function (date, cell) {
            console.log(date);
            let start = $('#startDate').val();
            let end = $('#endDate').val();
            if (moment(date).isBetween($('#startDate').val(), $('#endDate').val(), null, '[]') !== true) {
            }
        },
        buttonText: {
            today: "Ajourd'hui",
            month: 'Mois',
            week: 'Semaine',
            day: 'Jours'
        },
        eventSources: [
            {
                url: $('#url').val(),
                type: 'GET',
                data: {
                    id: $('#idEvent').val(),
                },
            }
        ],
        dayClick: function () {
            $('#modal-view-event-add').modal();
        },
        eventClick: function (info) {
            $('#person').text(info.person);
            $('#job').text(info.job);
            $('#place').text(info.place);
            $('#start').text(moment(info.start).format('DD-MM-YYYY HH:mm:ss'));
            $('#end').text(moment(info.end).format('DD-MM-YYYY HH:mm:ss'));
            $('#idEventManager').val(info.id);

            $('#modal-view-event-desc').modal();
        },
        eventResize: function (info) {
            alert(info.title);
            alert((info.end));
        },
        eventDrop: function (info) {
            alert(info.job);
            alert((info.start));
        },
    })
});

// Add an event on calendar
$('#addEventManager').submit(function (e) {
    e.preventDefault();
    let form_data = $(this).serialize();
    $.ajax({
        url: $(this).attr("action"),
        type: $(this).attr("method"),
        data: form_data,
        success: function (json) {
            $('#calendar').fullCalendar('refetchEvents');
            $('#modal-view-event-add').modal('hide');
            $.notification('fas fa-check', 'Succès : ', 'l\'évènement viens d\être ajouté', 'success');
        },
        error: function (response) {
            $.notification('fa fa-exclamation-circle', 'Erreur : ', response.responseJSON, 'danger');
        }
    });
    $(this).get(0).reset();
});

// Delete an event on calendar
$('#deleteEvent').click(function () {
    $.ajax({
        url: $('#deleteEvent').val(),
        type: 'POST',
        data: {
            id: $('#idEventManager').val(),
        },
        success: function (json) {
            $('#calendar').fullCalendar('refetchEvents');
            $('#modal-view-event-desc').modal('hide');
            $.notification('fas fa-check', 'Succès : ', json, 'success');
        },
        error: function (json) {
            $.notification('fa fa-exclamation-circle', 'Erreur : ', json, 'danger');
        }
    })
});

// When the user click on the update button in description modal
$('#updateEvent').click(function () {

    // Clear data in select for add a news data
    $('#selectJobUpdate').empty();
    $('#selectAccountUpdate').empty();

    // Fill the job selected in modal update
    $(".selectJob option").each(function () {
        let opt = '';
        if ($(this).text() === $('#job').text()) {
            opt = "<option value='" + $(this).val() + "' selected>" + $(this).text() + "</option>";
            $('#selectJobUpdate').append(opt);
        } else {
            opt = "<option value='" + $(this).val() + "'>" + $(this).text() + "</option>";
            $('#selectJobUpdate').append(opt);
        }
    });

    // Fill the person selected in modal update
    $(".selectAccount option").each(function () {
        if ($(this).text() === $('#person').text()) {
            opt = "<option value='" + $(this).val() + "' selected>" + $(this).text() + "</option>";
            $('#selectAccountUpdate').append(opt);
        } else {
            opt = "<option value='" + $(this).val() + "'>" + $(this).text() + "</option>";
            $('#selectAccountUpdate').append(opt);
        }
    });

    // Fill all field form with data
    $('#startUpdate').val($('#start').text());
    $('#endUpdate').val($('#end').text());
    $('#placeUpdate').val($('#place').text());
    $('#idEventManagerUpdate').val($('#idEventManager').val());
    $('#modal-view-event-update').modal();
});

// Submit the update form
$('#updateEventManagerForm').submit(function (e) {
    e.preventDefault();
    let form_data = $(this).serialize();
    $.ajax({
        url: $(this).attr("action"),
        type: $(this).attr("method"),
        data: form_data,
        success: function (json) {
            $('#calendar').fullCalendar('refetchEvents');
            $('#modal-view-event-desc').modal('hide');
            $('#modal-view-event-update').modal('hide');
            $.notification('fas fa-check', 'Succès : ', 'l\'évènement à été mis à jour', 'success');
        },
        error: function (json) {
            $.notification('fa fa-exclamation-circle', 'Erreur : ', json, 'danger');
        }
    })
});