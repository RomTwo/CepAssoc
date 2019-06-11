/*This part allows you to perform actions when the page is ready to use. In our case, there is a
initialization of several items such as checkboxes that go back to "false"...*/
$(window).ready(function () {

    if (final()) {
        $(".buttonSubmit").removeAttr("disabled");
    } else {
        $(".buttonSubmit").attr("disabled", true);

    }

    if ($('#adherent_cityRep1').val() == null) {
        $.ajax({
            url: 'https://datanova.legroupe.laposte.fr/api/records/1.0/search/',
            type: "POST",
            dataType: "json",
            data: {
                "dataset": "laposte_hexasmal",
                "refine.code_postal": $('#adherent_zipCodeRep1').val(),
            },
            success: function (data) {
                $('#adherent_cityRep1 option').remove();
                for (let i in data["records"]) {
                    commune = data["records"][i]["fields"]["nom_de_la_commune"];
                    $('#adherent_cityRep1').append(new Option(commune, commune));
                }
            }
        });
    }

    if ($('#adherent_cityRep2').val() == null) {
        $.ajax({
            url: 'https://datanova.legroupe.laposte.fr/api/records/1.0/search/',
            type: "POST",
            dataType: "json",
            data: {
                "dataset": "laposte_hexasmal",
                "refine.code_postal": $('#adherent_zipCodeRep2').val(),
            },
            success: function (data) {
                $('#adherent_cityRep2 option').remove();
                for (let i in data["records"]) {
                    commune = data["records"][i]["fields"]["nom_de_la_commune"];
                    $('#adherent_cityRep2').append(new Option(commune, commune));
                }
            }
        });
    }

    $("#step").val(1);
    $('input[name=\'selection\']').prop('checked', false);
    if (final()) {
        $("#buttonSubmit").removeAttr("disabled");
    } else {
        $("#buttonSubmit").attr("disabled", true);

    }

    let groupColumn = 2;

    $('#activitiesSecteurLoisir').DataTable({
        "columnDefs": [
            {"visible": false, "targets": groupColumn}
        ],
        "pageLength": 10,
        "drawCallback": function () {
            let api = this.api();
            let rows = api.rows({page: 'current'}).nodes();
            let last = null;

            api.column(groupColumn, {page: 'current'}).data().each(function (group, i) {
                if (last !== group) {
                    $(rows).eq(i).before(
                        '<tr class="group"><td colspan="5">' + group + '</td></tr>'
                    );

                    last = group;
                }
            });
        },
        orderFixed: [2, 'asc'],
        "bInfo": false,
        "lengthChange": false,
        "language": {
            "sProcessing": "Traitement en cours...",
            "sSearch": "Rechercher&nbsp;:",
            "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
            "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
            "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            "sInfoPostFix": "",
            "sLoadingRecords": "Chargement en cours...",
            "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
            "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
            "oPaginate": {
                "sFirst": "Premier",
                "sPrevious": "Pr&eacute;c&eacute;dent",
                "sNext": "Suivant",
                "sLast": "Dernier"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
            },
            "select": {
                "rows": {
                    _: "%d lignes séléctionnées",
                    0: "Aucune ligne séléctionnée",
                    1: "1 ligne séléctionnée"
                }
            }
        }
    });

    $('#activitiesSecteurCompetitif').DataTable({
        "lengthChange": false,
        "language": {
            "sProcessing": "Traitement en cours...",
            "sSearch": "Rechercher&nbsp;:",
            "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
            "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
            "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            "sInfoPostFix": "",
            "sLoadingRecords": "Chargement en cours...",
            "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
            "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
            "oPaginate": {
                "sFirst": "Premier",
                "sPrevious": "Pr&eacute;c&eacute;dent",
                "sNext": "Suivant",
                "sLast": "Dernier"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
            },
            "select": {
                "rows": {
                    _: "%d lignes séléctionnées",
                    0: "Aucune ligne séléctionnée",
                    1: "1 ligne séléctionnée"
                }
            }
        }
    });
});

/*This function makes it possible to control the various regulations to accept to validate the registration.*/
$('#acceptRules, #acceptRGPD').on('change', function () {
    if (final()) {
        $(".buttonSubmit").removeAttr("disabled");
    } else {
        $(".buttonSubmit").attr("disabled", true);
    }
});

/*This function is important because it is the function that allows you to manage the steps of the form. This action is
done on the "next" button. We define the number of steps that compose our form and update the number corresponding to
the current step in the form. We do a "client" level check on the different steps to ensure that the user has all
the input ...*/
$('#nextRegistration').click(function () {
    let numberOfStep = 5;
    let value = parseInt(document.getElementById('step').value);
    if (value < numberOfStep) {
        if (stepChoice("step" + value)) {
            if (value == numberOfStep - 1) {
                $("#nextRegistration").hide();
            }
            $("#previousRegistration").show();
            $("helper").hide();
            $("#registrationStep" + (value + 1)).show();
            $("#registrationStep" + (value)).hide();
            document.getElementById('step').value = value + 1;
        }
    } else {
        $("#nextRegistration").hide();
    }

    $('#example').DataTable();
});

/*When to that one, it's the same function but the "previous" button.*/
$('#previousRegistration').click(function () {
    let value = parseInt(document.getElementById('step').value);
    if (value > 1) {
        if (value == 2) {
            $("#previousRegistration").hide();
        }
        $("#nextRegistration").show();
        $("#registrationStep" + (value - 1)).show();
        $("#registrationStep" + (value)).hide();
        document.getElementById('step').value = value - 1;
    } else {
        $("#previousRegistration").hide();
    }
});

/*This function displays the "judge" step when the option has been validated.*/
$('#juge').on('change', function () {
    let val = $(this).is(':checked') ? $("#judgeDiv").show() : $("#judgeDiv").hide();
});

/*This function displays the "volontaire" step when the option has been validated.*/
$('#volontaire').on('change', function () {
    let val = $(this).is(':checked') ? $("#volunteerDiv").show() : $("#volunteerDiv").hide();
});

let $activity = new Map();//Definition of a table to store activity ids and their prices.
let $timeSlot = new Map();//Definition of a table to store timeSlot ids and their id's activity.

/*This function makes it possible to manage the different activities selected with the datatables and makes it possible
to display the price according to the clipped boxes.*/
$("input[name='selection[]']").on('change', function () {
    if ($(this).is(':checked')) {
        $activity.set($(this).parent().parent().attr('value'), parseInt($("#price" + $(this).parent().parent().attr('value')).attr('value')));//Put the id of activity and their price.
        $timeSlot.set($(this).parent().parent().attr('id'), $(this).parent().parent().attr('value'));//Put the id of timeSlot and their id's activity.
    } else {
        $activity.delete($(this).parent().parent().attr('value'));//Remove the id of activity and their price.
        $timeSlot.delete($(this).parent().parent().attr('id'));//Remove the id of timeSlot and their id's activity.
    }
});

/*This function is based on the switch-case because it allows to call the different functions that performs
the verification of steps directly.*/
function stepChoice(step) {
    switch (step) {
        case 'step1':
            return step1();
        case 'step2':
            return true;
        case 'step3':
            return step3();
        case 'step4':
            return step4();
        default:
            console.log('Sorry, we are out of ' + step + '.');
    }
}

/*This function corresponds to the verification of step 1.*/
function step1() {
    let bool = true;

    if (isValidationRadio('adherent[registrationType]')) {
        $("#registrationTypeHelp").show();
        bool = false;
    } else {
        $("#registrationTypeHelp").hide();
    }

    if (isEmpty('adherent[firstName]')) {
        $("#firstNameTypeHelp").show();
        bool = false;
    } else {
        $("#firstNameTypeHelp").hide();
    }

    if (isEmpty('adherent[lastName]')) {
        $("#lastNameTypeHelp").show();
        bool = false;
    } else {
        $("#lastNameTypeHelp").hide();
    }

    return bool;
}

/*This function corresponds to the verification of step 3.*/
function step3() {
    let bool = true;

    (isEmpty('adherent[firstNameRep1]') ? ($("#firstNameRep1Help").show(), bool = false) : $("#firstNameRep1Help").hide());
    (isEmpty('adherent[lastNameRep1]') ? ($("#lastNameRep1Help").show(), bool = false) : $("#lastNameRep1Help").hide());


    if (isEmpty('adherent[emailRep1]') || isValidationEmail('adherent[emailRep1]')) {
        $("#emailRep1Help").show();
        bool = false;
    } else {
        $("#emailRep1Help").hide();
    }

    if (isEmpty('adherent[professionRep1]')) {
        $("#professionRep1Help").show();
        bool = false;
    } else {
        $("#professionRep1Help").hide();
    }

    if (isValidationList('#adherent_cityRep1')) {
        $("#cityRep1Help").show();
        bool = false;
    } else {
        $("#cityRep1Help").hide();
    }

    if (isEmpty('adherent[addressRep1]')) {
        $("#addressRep1Help").show();
        bool = false;
    } else {
        $("#addressRep1Help").hide();
    }

    if (isEmpty('adherent[zipCodeRep1]') || isValidationZipCode('adherent[zipCodeRep1]')) {
        $("#zipCodeRep1Help").show();
        bool = false;
    } else {
        $("#zipCodeRep1Help").hide();
    }

    if (isEmpty('adherent[phoneRep1]') || isValidationPhoneNumber('adherent[phoneRep1]')) {
        $("#phoneRep1Help").show();
        bool = false;
    } else {
        $("#phoneRep1Help").hide();
    }

    (isEmpty('adherent[emailRep2]') ? $("#emailRep2Help").hide() : ((isValidationEmail('adherent[emailRep2]')) ? ($("#emailRep2Help").show(), bool = false) : $("#emailRep2Help").hide()));
    (isEmpty('adherent[zipCodeRep2]') ? $("#zipCodeRep2Help").hide() : ((isValidationZipCode('adherent[zipCodeRep2]')) ? ($("#zipCodeRep2Help").show(), bool = false) : $("#zipCodeRep2Help").hide()));
    (isEmpty('adherent[phoneRep2]') ? $("#phoneRep2Help").hide() : ((isValidationPhoneNumber('adherent[phoneRep2]')) ? ($("#phoneRep2Help").show(), bool = false) : $("#phoneRep2Help").hide()));
    return bool;
}

/*This function corresponds to the verification of step 4. It allows to put the price according to the activities
 choosen by the user.*/
function step4() {
    let $idsOfTimeSlots = "";
    let $prix = 0;
    for (let [key, value] of $timeSlot) {
        $idsOfTimeSlots += key + "/";
    }
    for (let [key, value] of $activity) {
        $prix = $prix + value;
    }
    $("#idsOfTimeSlots").val($idsOfTimeSlots);
    $("#prix").text($prix);
    return true;
}

/*This function corresponds to the verification of final step with the verrification of checked rules.*/
function final() {
    let bool = true;
    if (isValidationRadio('acceptRules')) {
        bool = false;
    }

    if (isValidationRadio('acceptRGPD')) {
        bool = false;
    }

    return bool;
}

/*This function makes it possible to check that the input is not empty.*/
function isEmpty(nameInput) {
    return !$('input[name="' + nameInput + '"]').val();
}

/*This function makes it possible to check that there is a radio button that has been selected.*/
function isValidationRadio(nameRadio) {
    return !$('input[name="' + nameRadio + '"]:checked').val();
}

/*This function makes it possible to check that the input respects the regex corresponding to an email address.*/
function isValidationList(nameList) {
    let bool = true;
    $.each($(nameList + ' option:selected'), function () {
        bool = false;
    });
    return bool;
}

/*This function makes it possible to check that the input respects the regex corresponding to an email address.*/
function isValidationEmail(nameEmail) {
    let pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
    if (pattern.test($('input[name="' + nameEmail + '"]').val())) {
        return false;
    } else {
        return true;
    }
}

/*This function makes it possible to check that the input respects the regex corresponding to a ZipCode.*/
function isValidationZipCode(nameZipCode) {
    let pattern = new RegExp(/^([0-9]{2}|(2A)|2B)[[0-9]{3}$/);
    if (pattern.test($('input[name="' + nameZipCode + '"]').val())) {
        return false;
    } else {
        return true;
    }
}

/*This function makes it possible to check that the input respects the regex corresponding to a phone number.*/
function isValidationPhoneNumber(nameZipCode) {
    let pattern = new RegExp(/^(?:(?:\+)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/);
    if (pattern.test($('input[name="' + nameZipCode + '"]').val())) {
        return false;
    } else {
        return true;
    }
}

/*This function allows to recover all the cities corresponds to the zip code entered by the user.*/
$('#adherent_zipCodeRep1').focusout(function () {
    $.ajax({
        url: 'https://datanova.legroupe.laposte.fr/api/records/1.0/search/',
        type: "POST",
        dataType: "json",
        data: {
            "dataset": "laposte_hexasmal",
            "refine.code_postal": $('#adherent_zipCodeRep1').val(),
        },
        success: function (data) {
            $('#adherent_cityRep1 option').remove();
            for (let i in data["records"]) {
                commune = data["records"][i]["fields"]["nom_de_la_commune"];
                $('#adherent_cityRep1').append(new Option(commune, commune));
            }
        }
    })
});

/*This function allows to recover all the cities corresponds to the zip code entered by the user.*/
$('#adherent_zipCodeRep2').focusout(function () {
    $.ajax({
        url: 'https://datanova.legroupe.laposte.fr/api/records/1.0/search/',
        type: "POST",
        dataType: "json",
        data: {
            "dataset": "laposte_hexasmal",
            "refine.code_postal": $('#adherent_zipCodeRep2').val(),
        },
        success: function (data) {
            $('#adherent_cityRep2 option').remove();
            for (let i in data["records"]) {
                commune = data["records"][i]["fields"]["nom_de_la_commune"];
                $('#adherent_cityRep2').append(new Option(commune, commune));
            }
        }
    })
});

$(".custom-file-input").on("change", function () {
    let fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

/*This part corresponds to displaying the health questionnaires.*/
$('#healthQuestionnaire').click(function () {
    $("#registrationStep6").show();
    $("#registrationStep5").hide();
    $("#buttonBottom").hide();
});

/*This part corresponds to displaying the health questionnaires.*/
$('#healthQuestionnaireModificate').click(function () {
    $("#registrationStep6").show();
    $("#registrationStep5").hide();
    $("#buttonBottom").hide();
});

/*This one does a check and a display when pressing the "cancel" button.*/
$('#cancelQuestionnaire').click(function () {
    $('div #healthQuestionnaireQuestion > div > div > input').each(function () {
        $('input[name="' + $(this).attr('name') + '"]').prop('checked', false);
    });
    $("#registrationStep6").hide();
    $("#registrationStep5").show();
    $("#buttonBottom").show();
    $("#healthQuestionnaireQuestionHelp").hide();
    $(".healthQuestionnaireEmpty").show();
    $(".healthQuestionnaireValidated").hide();
});

/*This one does a check and a display when pressing the "valid" button.*/
$('#validQuestionnaire').click(function () {
    let bool = true;
    $('div #healthQuestionnaireQuestion > div > div > input').each(function () {
        if (isValidationRadio($(this).attr('name'))) {
            bool = false;
        }
        ;
    });
    if (bool) {
        $("#registrationStep6").hide();
        $("#registrationStep5").show();
        $("#buttonBottom").show();
        $("#healthQuestionnaireQuestionHelp").hide();
        $(".healthQuestionnaireEmpty").hide();
        $(".healthQuestionnaireValidated").show();
    } else {
        window.scrollTo(0, 0);
        $("#healthQuestionnaireQuestionHelp").show();
    }
});