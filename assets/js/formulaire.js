require('../css/app.scss');

$ = require('jquery');

/*This part allows you to perform actions when the page is ready to use. In our case, there is a
initialization of several items such as checkboxes that go back to "false"...*/
$(window).ready(function () {
    $("#accountStep").val(1);//
    $('#account_addAccountAdherent').is(':checked') ? ($("#next").show(), $("#account_valid").hide()) : ($("#next").hide(), $("#account_valid").show());
    $('input[name=\'selection[]\']').prop('checked', false);
    if (final()) {
        $(".buttonSubmit").removeAttr("disabled");
    } else {
        $(".buttonSubmit").attr("disabled", true);
    }
    $.ajax({
        url: 'https://datanova.legroupe.laposte.fr/api/records/1.0/search/',
        type: "POST",
        dataType: "json",
        data: {
            "dataset": "laposte_hexasmal",
            "refine.code_postal": $('#account_zipCode').val(),
        },
        success: function (data) {
            $('#account_city option').remove();
            let communeExists = [];
            for (let i in data["records"]) {
                commune = data["records"][i]["fields"]["nom_de_la_commune"];
                if ($.inArray(commune, communeExists) == -1) {
                    communeExists.push(commune);
                    $('#account_city').append(new Option(commune, commune));
                }
            }
        }
    });

    let groupColumn = 2;

    $('#activitiesSecteurLoisir').DataTable({
        "columnDefs": [
            {"visible": false, "targets": groupColumn},
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

/*This function allows to know if the user to select the option which allows to register also as "gymnast".*/
$('#account_addAccountAdherent').on('change', function () {
    $(this).is(':checked') ? ($("#next").show(), $("#account_valid").hide()) : ($("#next").hide(), $("#account_valid").show());
});

/*This function is important because it is the function that allows you to manage the steps of the form. This action is
done on the "next" button. We define the number of steps that compose our form and update the number corresponding to
the current step in the form. We do a "client" level check on the different steps to ensure that the user has all
the input ...*/
$('#next').click(function () {
    let numberOfStep = 6;
    let value = parseInt($("#accountStep").val());
    if (value < numberOfStep) {
        if (stepChoice("accountStep" + value)) {
            if (value == numberOfStep - 1) {
                $("#next").hide();
            }
            $("#previous").show();
            $("helper").hide();
            if (value == 2) {
                $('#juge').is(':checked') ? ($("#accountStep" + (value + 1)).show(), $("#accountStep").val(value + 1)) : $("#volontaire").is(':checked') ? ($("#accountStep" + (value + 2)).show(), $("#accountStep").val(value + 2)) : ($("#accountStep" + (value + 3)).show(), $("#accountStep").val(value + 3));
            } else if (value == 3) {
                $('#volontaire').is(':checked') ? ($("#accountStep" + (value + 1)).show(), $("#accountStep").val(value + 1)) : ($("#accountStep" + (value + 2)).show(), $("#accountStep").val(value + 2));
            } else {
                $("#accountStep" + (value + 1)).show();
                $("#accountStep").val(value + 1);
            }
            $("#accountStep" + (value)).hide();
            window.scrollTo(0, 0);

        }
    } else {
        $("#next").hide();
    }
});

/*When to that one, it's the same function but the "previous" button.*/
$('#previous').click(function () {
    let value = parseInt($("#accountStep").val());
    if (value > 1) {
        if (value == 2) {
            $("#previous").hide();
        }
        $("#next").show();
        if (value == 5) {
            $('#volontaire').is(':checked') ? ($("#accountStep" + (value - 1)).show(), $("#accountStep").val(value - 1)) : $("#juge").is(':checked') ? ($("#accountStep" + (value - 2)).show(), $("#accountStep").val(value - 2)) : ($("#accountStep" + (value - 3)).show(), $("#accountStep").val(value - 3));
        } else if (value == 4) {
            $('#juge').is(':checked') ? ($("#accountStep" + (value - 1)).show(), $("#accountStep").val(value - 1)) : ($("#accountStep" + (value - 2)).show(), $("#accountStep").val(value - 2));
        } else {
            $("#accountStep" + (value - 1)).show();
            $("#accountStep").val(value - 1);
        }
        $("#accountStep" + (value)).hide();
        window.scrollTo(0, 0);
    } else {
        $("#previous").hide();
    }
});

/*This part corresponds to displaying the health questionnaires.*/
$('#healthQuestionnaire').click(function () {
    $("#accountStep7").show();
    $("#accountStep6").hide();
    $("#buttonBottom").hide();
});

/*This part corresponds to displaying the health questionnaires.*/
$('#healthQuestionnaireModificate').click(function () {
    $("#accountStep7").show();
    $("#accountStep6").hide();
    $("#buttonBottom").hide();
});

/*This one does a check and a display when pressing the "cancel" button.*/
$('#cancelQuestionnaire').click(function () {
    $('div #healthQuestionnaireQuestion > div > div > input').each(function () {
        $('input[name="' + $(this).attr('name') + '"]').prop('checked', false);
    });
    $("#accountStep7").hide();
    $("#accountStep6").show();
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
        $("#accountStep7").hide();
        $("#accountStep6").show();
        $("#buttonBottom").show();
        $("#healthQuestionnaireQuestionHelp").hide();
        $(".healthQuestionnaireEmpty").hide();
        $(".healthQuestionnaireValidated").show();
    } else {
        window.scrollTo(0, 0);
        $("#healthQuestionnaireQuestionHelp").show();
    }
});

/*This function displays the "judge" step when the option has been validated.*/
$('#juge').on('change', function () {
    $(this).is(':checked') ? $("#judgeDiv").show() : $("#judgeDiv").hide();
});

/*This function displays the "volontaire" step when the option has been validated.*/
$('#volontaire').on('change', function () {
    $(this).is(':checked') ? $("#volunteerDiv").show() : $("#volunteerDiv").hide();
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
        case 'accountStep1':
            return step1();
        case 'accountStep2':
            return step2();
        case 'accountStep3':
            return true;
        case 'accountStep4':
            return true;
        case 'accountStep5':
            return step5();
        case 'accountStep6':
            return true;
        case 'accountStep7':
            return step7();
        case 'accountStep8':
            return true;
        default:
            console.log('Sorry, we are out of ' + step + '.');
    }
}

/*This function corresponds to the verification of step 1.*/
function step1() {
    let bool = true;
    (isEmpty('account[firstName]') ? ($("#firstNameHelp").show(), bool = false) : $("#firstNameHelp").hide());
    (isEmpty('account[lastName]') ? ($("#lastNameHelp").show(), bool = false) : $("#lastNameHelp").hide());
    (isEmpty('account[zipCode]') ? ($("#zipCodeHelp").show(), bool = false) : ((isValidationZipCode('account[zipCode]')) ? ($("#zipCodeHelp").show(), bool = false) : $("#zipCodeHelp").hide()));
    (isValidationList('#account_city') ? ($("#cityHelp").show(), bool = false) : $("#cityHelp").hide());
    (isEmpty('account[address]') ? ($("#addressHelp").show(), bool = false) : $("#addressHelp").hide());
    (isEmpty('account[email]') ? ($("#emailHelp").show(), bool = false) : ((isValidationEmail('account[email]')) ? ($("#emailHelp").show(), bool = false) : $("#emailHelp").hide()));
    (isEmpty('account[password]') ? ($("#passwordHelp").show(), bool = false) : ((isValidationPassword('account[password]')) ? ($("#passwordHelp").show(), bool = false) : $("#passwordHelp").hide()));
    return bool;
}

/*This function corresponds to the verification of step 2.*/
function step2() {
    let bool = true;
    (isEmpty('account[children][0][phoneRep1]') ? ($("#phoneRep1Help").show(), bool = false) : ((isValidationPhoneNumber('account[children][0][phoneRep1]')) ? ($("#phoneRep1Help").show(), bool = false) : $("#phoneRep1Help").hide()));
    (isEmpty('account[children][0][professionRep1]') ? ($("#professionRep1Help").show(), bool = false) : $("#professionRep1Help").hide())
    return bool;
}

/*This function corresponds to the verification of step 5. It allows to put the price according to the activities
 choosen by the user.*/
function step5() {
    let $idsOfTimeSlots = "";
    let $prix = 0;
    for (let [key, value] of $timeSlot) {
        $idsOfTimeSlots += key + "/";
    }
    for (let [key, value] of $activity) {
        $prix = $prix + value;
    }
    $("#idsOfTimeSlots").val($idsOfTimeSlots);//Put the value of id.
    $("#prix").text($prix);//Display the final price.
    return true;
}

/*This function corresponds to the verification of step 7.*/
function step7() {
    let bool = true;
    (isValidationList('account[children][0][paymentType]') ? ($("#paymentTypeHelp").show(), bool = false) : $("#paymentTypeHelp").hide());
    (isValidationList('account[children][0][imageRight]') ? ($("#imageRightHelp").show(), bool = false) : $("#imageRightHelp").hide())
    return bool;
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
function isValidationEmail(nameEmail) {
    let pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
    if (pattern.test($('input[name="' + nameEmail + '"]').val())) {
        return false;
    } else {
        return true;
    }
}

/*This function makes it possible to check that there is a element in list that has been selected.*/
function isValidationList(nameList) {
    let bool = true;
    $.each($(nameList + ' option:selected'), function () {
        bool = false;
    });
    return bool;
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


/*This function makes it possible to check that the input respects the regex corresponding to a password.*/
function isValidationPassword(namePassword) {
    let pattern = new RegExp(/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,15})$/);
    if (pattern.test($('input[name="' + namePassword + '"]').val())) {
        return false;
    } else {
        return true;
    }
}

/*This function allows to recover all the cities corresponds to the zip code entered by the user.*/
$('#account_zipCode').focusout(function () {
    $.ajax({
        url: 'https://datanova.legroupe.laposte.fr/api/records/1.0/search/',
        type: "POST",
        dataType: "json",
        data: {
            "dataset": "laposte_hexasmal",
            "refine.code_postal": $('#account_zipCode').val(),
        },
        success: function (data) {
            $('#account_city option').remove();
            let communeExists = [];
            for (let i in data["records"]) {
                commune = data["records"][i]["fields"]["nom_de_la_commune"];
                if ($.inArray(commune, communeExists) == -1) {
                    communeExists.push(commune);
                    $('#account_city').append(new Option(commune, commune));
                }
            }
        }
    })
});