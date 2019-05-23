// Print a calendar
$(function () {
    $('#calendar').fullCalendar({
        locale: 'fr',
        themeSystem: 'bootstrap4',
        businessHours: false,
        defaultView: 'agendaWeek',
        editable: false,
        timeFormat: 'H:mm',
        slotLabelFormat: ['H:mm'],
        allDaySlot: false,
        header: {
            left: 'title',
            center: 'month,agendaWeek,agendaDay',
            right: 'today prev,next'
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
                    token: $('#token').val()
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
        success: function (json) {
            $('#modal-view-event-desc').modal('hide');
            $('#calendar').fullCalendar('refetchEvents');
        }
    })
});

// When the user click on the update button in description modal
$('#updateEvent').click(function () {

    if ($("#selectJobUpdate option").length === 0 || $("#selectAccountUpdate option").length == 0) {

        $(".selectJob option").each(function () {
            let opt = "<option value='" + $(this).val() + "'>" + $(this).text() + "</option>";
            $('#selectJobUpdate').append(opt);
        });

        $(".selectAccount option").each(function () {
            let opt = "<option value='" + $(this).val() + "'>" + $(this).text() + "</option>";
            $('#selectAccountUpdate').append(opt);
        });
    }

    $('#startUpdate').val($('#start').text());
    $('#endUpdate').val($('#end').text());
    $('#placeUpdate').val($('#place').text());
    $('#idEventManagerUpdate').val($('#idEventManager').val());
    $('#modal-view-event-update').modal();
});


$('#updateEventManagerForm').submit(function (e) {
    e.preventDefault();
    let form_data = $(this).serialize();
    console.log(form_data);
    $.ajax({
        url: $(this).attr("action"),
        type: $(this).attr("method"),
        data: form_data,
        success: function (json) {

        }
    })
});