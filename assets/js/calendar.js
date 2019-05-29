// Print a calendar
$(function () {
    $('#calendar').fullCalendar({
        locale: 'fr',
        themeSystem: 'bootstrap4',
        defaultView: 'agendaWeek',
        timeFormat: 'H:mm',
        slotLabelFormat: ['H:mm'],
        businessHours: false,
        editable: true,
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
        dayRender: function (dayRenderInfo) {
            console.log(dayRenderInfo);
            if (moment(dayRenderInfo.date).isBetween($('#startDate').val(), $('#endDate').val(), null, '[]') !== true) {
                dayRenderInfo.el.css('background-color', 'red');
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
            $('#description').text(info.description);
            $('#start').text(moment(info.start).format('DD-MM-YYYY HH:mm:ss'));
            $('#end').text(moment(info.end).format('DD-MM-YYYY HH:mm:ss'));
            $('#idEventManager').val(info.id);

            $('#modal-view-event-desc').modal();
        },
        eventResize: function (info) {
            $.updateDatetime(info);
        },
        eventDrop: function (info) {
            $.updateDatetime(info);
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
        success: function (response) {
            $('#calendar').fullCalendar('refetchEvents');
            $('#modal-view-event-add').modal('hide');
            $.notification('fas fa-check', 'Succès : ', response, 'success');
            $('#addEventManager').get(0).reset();
        },
        error: function (error) {
            $('#errorAddEventManager').append($.createErrorMsg(error.responseJSON));
        }
    });

});

// Submit the update form
$('#updateEventManagerForm').submit(function (e) {
    e.preventDefault();
    let form_data = $(this).serialize();
    $.ajax({
        url: $(this).attr("action"),
        type: $(this).attr("method"),
        data: form_data,
        success: function (response) {
            $('#calendar').fullCalendar('refetchEvents');
            $('#modal-view-event-desc').modal('hide');
            $('#modal-view-event-update').modal('hide');
            $.notification('fas fa-check', 'Succès : ', response, 'success');
        },
        error: function (error) {
            $('#errorUpdateEventManager').append($.createErrorMsg(error.responseJSON));
        }
    })
});

// Delete an event on calendar
$('#deleteEvent').click(function () {
    $.ajax({
        url: $('#deleteEvent').val(),
        type: 'POST',
        data: {
            id: $('#idEventManager').val(),
        },
        success: function (response) {
            $('#calendar').fullCalendar('refetchEvents');
            $('#modal-view-event-desc').modal('hide');
            $.notification('fas fa-check', 'Succès : ', response, 'success');
        },
        error: function (error) {
            $.notification('fa fa-exclamation-circle', 'Erreur : ', error.responseJSON, 'danger');
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
    $('#descriptionUpdate').val($('#description').text());
    $('#idEventManagerUpdate').val($('#idEventManager').val());
    $('#modal-view-event-update').modal();
});

// Update the datetime of an event when the user deplace an event or resize an event by the bottom
// This method are call in eventResize and eventDrop functions
$.updateDatetime = function (object) {
    $.ajax({
        url: $('#urlUpdateDate').val(),
        type: 'post',
        data: {
            id: object.id,
            start: moment(object.start).format('DD-MM-YYYY HH:mm:ss'),
            end: moment(object.end).format('DD-MM-YYYY HH:mm:ss')
        },
        success: function (response) {
            $(this).fullCalendar('refetchEvents');
            $.notification('fas fa-check', 'Succès : ', response, 'success');
        },
        error: function (response) {
            $.notification('fa fa-exclamation-circle', 'Erreur : ', error.responseJSON, 'danger');
        }
    })
};

$.createErrorMsg = function (msg) {
    return "<div class='row ml-1'>" +
        "<span class='invalid-feedback d-block'>" +
        "<span class='d-block'>" +
        "<span class='form-error-icon badge badge-danger text-uppercase'>Erreur</span>" +
        "<span class='form-error-message'>" + msg + "</span>" +
        "</span>" +
        "</span>" +
        "</div>";
};
