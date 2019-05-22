$(function () {

    let tab = JSON.parse($('.dataEvent').text());
    let data = [];
    let tmp = {};
    for (i = 0; i <= tab.length; i++) {
        tmp = {
            title: tab[i].account.firstName + " " + tab[i].account.lastName,
            description: 'Poste : ' + tab[i].job + ', Lieu : ' + tab[i].place,
            start: tab[i].startDate,
            end: tab[i].endDate,
        };

        data.push(tmp);
        console.log(data);
    }


    $('#calendar').fullCalendar({
        locale: 'fr',
        themeSystem: 'bootstrap4',
        businessHours: false,
        defaultView: 'agendaWeek',
        editable: false,
        header: {
            left: 'title',
            center: 'month,agendaWeek,agendaDay',
            right: 'today prev,next'
        },
        slotLabelFormat: ['H:mm'],
        allDaySlot: false,
        buttonText: {
            today: "Ajourd'hui",
            month: 'Mois',
            week: 'Semaine',
            day: 'Jours'
        },
        events: data,
        dayClick: function () {
            $('#modal-view-event-add').modal();
        },

    })
});
