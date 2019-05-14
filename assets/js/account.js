/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.scss in this case)
require('../css/account.scss');

$ = require('jquery');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

$('#registration').on('change', function () {
    $(this).is(':checked') ? ($("#next").show(), $("#accountStep1-1").show(), $("#account_valid").hide()) : ($("#next").hide(), $("#accountStep1-1").hide(), $("#account_valid").show());
});

$('#next').click(function () {
    var value = parseInt(document.getElementById('step').value);
    if (value < 5) {
        //if (stepChoice("step" + value)) {
            if (value == 5) {
                $("#next").hide();
            }
            $("#previous").show();
            $("helper").hide();
            $("#accountStep" + (value + 1)).show();
            $("#accountStep" + (value)).hide();
            document.getElementById('step').value = value + 1;
        //}
    } else {
        $("#next").hide();
    }
});

$('#previous').click(function () {
    var value = parseInt(document.getElementById('step').value);
    if (value > 1) {
        if (value == 2) {
            $("#previous").hide();
        }
        $("#next").show();
        $("#accountStep" + (value - 1)).show();
        $("#accountStep" + (value)).hide();
        document.getElementById('step').value = value - 1;
    } else {
        $("#previous").hide();
    }
});

$('#juge').on('change', function () {
    var val = $(this).is(':checked') ? $("#judgeDiv").show() : $("#judgeDiv").hide();
});

$('#volontaire').on('change', function () {
    var val = $(this).is(':checked') ? $("#volunteerDiv").show() : $("#volunteerDiv").hide();
});

$("input[name='selection']").on('change', function () {
    var $prix = 0;

    $.each($("input[name='selection']:checked"), function () {
        var $id = $(this).val();
        $prix = $prix + parseInt($("#price" + $id).text());
    });
    $("#prix").text($prix);
});

function stepChoice(step) {
    switch (step) {
        case 'step1':
            return step1();
        case 'step2':
            return true;
        case 'step3':
            return step3();
        case 'step4':
            return true;
        default:
            console.log('Sorry, we are out of ' + step + '.');
    }
}

function step1() {
    var bool = true;

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

    if (isValidationRadio('adherent[sex]')) {
        $("#sexTypeHelp").show();
        bool = false;
    } else {
        $("#sexTypeHelp").hide();
    }

    return bool;
}

function step3() {
    var bool = true;

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

    if (isEmpty('adherent[cityRep1]')) {
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

$('#account_zipCode').change( function(){
    $.ajax({
        url:'https://datanova.legroupe.laposte.fr/api/records/1.0/search/',
        type: "POST",
        dataType: "json",
        data: {
            "dataset": "laposte_hexasmal",
            "refine.code_postal": $('#account_zipCode').val(),
        },
        async: true,
        success: function (data)
        {
            $('#account_city option').remove();
            for (var i in data["records"]) {
                commune = data["records"][i]["fields"]["nom_de_la_commune"];
                $('#account_city').append('<option value=' + commune + '>' + commune + '</option>');
            }
        }
    })
});


