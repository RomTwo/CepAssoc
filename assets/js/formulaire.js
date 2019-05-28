require('../css/app.scss');

$ = require('jquery');

$(window).ready(function () {
    $("#accountStep").val(1);
    $('#account_addAccountAdherent').is(':checked') ? ($("#next").show(), $("#account_valid").hide()) : ($("#next").hide(), $("#account_valid").show());
    $('input[name=\'selection\']').prop('checked', false);
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
            var communeExists = [];
            for (var i in data["records"]) {
                commune = data["records"][i]["fields"]["nom_de_la_commune"];
                if ($.inArray(commune, communeExists) == -1) {
                    communeExists.push(commune);
                    $('#account_city').append(new Option(commune, commune));
                }
            }
        }
    })

    var groupColumn = 2;

    $('#activitiesSecteurLoisir').DataTable({
        "columnDefs": [
            {"visible": false, "targets": groupColumn},
        ],

        "displayLength": 10,
        "drawCallback": function () {
            var api = this.api();
            var rows = api.rows({page: 'current'}).nodes();
            var last = null;

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

$('#acceptRules, #acceptRGPD').on('change', function () {
    if (final()) {
        $(".buttonSubmit").removeAttr("disabled");
    } else {
        $(".buttonSubmit").attr("disabled", true);
    }
});

$('#account_addAccountAdherent').on('change', function () {
    $(this).is(':checked') ? ($("#next").show(), $("#account_valid").hide()) : ($("#next").hide(), $("#account_valid").show());
});

$('#next').click(function () {
    var numberOfStep = 6;
    var value = parseInt($("#accountStep").val());
    if (value < numberOfStep) {
        //if (stepChoice("accountStep" + value)) {
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

        //}
    } else {
        $("#next").hide();
    }
});

$('#previous').click(function () {
    var value = parseInt($("#accountStep").val());
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

$('#healthQuestionnaire').click(function () {
    $("#accountStep7").show();
    $("#accountStep6").hide();
    $("#buttonBottom").hide();
});

$('#healthQuestionnaireModificate').click(function () {
    $("#accountStep7").show();
    $("#accountStep6").hide();
    $("#buttonBottom").hide();
});

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

$('#validQuestionnaire').click(function () {
        var bool = true;
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


$('#juge').on('change', function () {
    $(this).is(':checked') ? $("#judgeDiv").show() : $("#judgeDiv").hide();
});

$('#volontaire').on('change', function () {
    $(this).is(':checked') ? $("#volunteerDiv").show() : $("#volunteerDiv").hide();
});

var $prix = 0;

$("input[name='selection']").on('change', function () {

    var $id = $(this).val();
    if($(this).is(':checked')){
        $prix = $prix + parseInt($("#price" + $id).text());
    }else{
        $prix = $prix - parseInt($("#price" + $id).text());
    }
    $("#prix").text($prix);
});

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
            return true;
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

function step1() {
    var bool = true;
    (isEmpty('account[firstName]') ? ($("#firstNameHelp").show(), bool = false) : $("#firstNameHelp").hide());
    (isEmpty('account[lastName]') ? ($("#lastNameHelp").show(), bool = false) : $("#lastNameHelp").hide());
    (isEmpty('account[zipCode]') ? ($("#zipCodeHelp").show(), bool = false) : ((isValidationZipCode('account[zipCode]')) ? ($("#zipCodeHelp").show(), bool = false) : $("#zipCodeHelp").hide()));
    (isValidationList('#account_city') ? ($("#cityHelp").show(), bool = false) : $("#cityHelp").hide());
    (isEmpty('account[address]') ? ($("#addressHelp").show(), bool = false) : $("#addressHelp").hide());
    (isEmpty('account[email]') ? ($("#emailHelp").show(), bool = false) : ((isValidationEmail('account[email]')) ? ($("#emailHelp").show(), bool = false) : $("#emailHelp").hide()));
    (isEmpty('account[password]') ? ($("#passwordHelp").show(), bool = false) : ((isValidationPassword('account[password]')) ? ($("#passwordHelp").show(), bool = false) : $("#passwordHelp").hide()));
    return bool;
}

function step2() {
    var bool = true;
    (isEmpty('account[children][0][phoneRep1]') ? ($("#phoneRep1Help").show(), bool = false) : ((isValidationPhoneNumber('account[children][0][phoneRep1]')) ? ($("#phoneRep1Help").show(), bool = false) : $("#phoneRep1Help").hide()));
    (isEmpty('account[children][0][professionRep1]') ? ($("#professionRep1Help").show(), bool = false) : $("#professionRep1Help").hide())
    //(isValidationList('account[children][0][nationality]') ? ($("#nationalityHelp").show(), bool = false) : $("#nationalityHelp").hide())
    return bool;
}

function step7() {
    var bool = true;
    (isValidationList('account[children][0][paymentType]') ? ($("#paymentTypeHelp").show(), bool = false) : $("#paymentTypeHelp").hide());
    (isValidationList('account[children][0][imageRight]') ? ($("#imageRightHelp").show(), bool = false) : $("#imageRightHelp").hide())
    return bool;
}

function final() {
    var bool = true;
    if (isValidationRadio('acceptRules')) {
        bool = false;
    }

    if (isValidationRadio('acceptRGPD')) {
        bool = false;
    }

    return bool;
}

function isEmpty(nameInput) {
    return !$('input[name="' + nameInput + '"]').val();
}

function isValidationRadio(nameRadio) {
    return !$('input[name="' + nameRadio + '"]:checked').val();
}

function isValidationEmail(nameEmail) {
    var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
    if (pattern.test($('input[name="' + nameEmail + '"]').val())) {
        return false;
    } else {
        return true;
    }
}

function isValidationList(nameList) {
    var bool = true;
    $.each($(nameList + ' option:selected'), function () {
        bool = false;
    });
    return bool;
}

function isValidationZipCode(nameZipCode) {
    var pattern = new RegExp(/^([0-9]{2}|(2A)|2B)[[0-9]{3}$/);
    if (pattern.test($('input[name="' + nameZipCode + '"]').val())) {
        return false;
    } else {
        return true;
    }
}

function isValidationPhoneNumber(nameZipCode) {
    var pattern = new RegExp(/^(?:(?:\+)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/);
    if (pattern.test($('input[name="' + nameZipCode + '"]').val())) {
        return false;
    } else {
        return true;
    }
}

function isValidationPassword(namePassword) {
    var pattern = new RegExp(/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,15})$/);
    if (pattern.test($('input[name="' + namePassword + '"]').val())) {
        return false;
    } else {
        return true;
    }
}

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
            var communeExists = [];
            for (var i in data["records"]) {
                commune = data["records"][i]["fields"]["nom_de_la_commune"];
                if ($.inArray(commune, communeExists) == -1) {
                    communeExists.push(commune);
                    $('#account_city').append(new Option(commune, commune));
                }
            }
        }
    })
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});