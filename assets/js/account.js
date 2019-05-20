/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.scss in this case)
require('../css/account.scss');

$ = require('jquery');

$( window ).ready(function() {
    $.ajax({
        url:'https://datanova.legroupe.laposte.fr/api/records/1.0/search/',
        type: "POST",
        dataType: "json",
        data: {
            "dataset": "laposte_hexasmal",
            "refine.code_postal": $('#account_zipCode').val(),
        },
        success: function (data)
        {
            $('#account_city option').remove();
            for (var i in data["records"]) {
                commune = data["records"][i]["fields"]["nom_de_la_commune"];
                $('#account_city').append(new Option(commune, commune));
            }
        }
    })
});

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

$('#registration').on('change', function () {
    $(this).is(':checked') ? ($("#next").show(), $("#account_valid").hide()) : ($("#next").hide(), $("#account_valid").show());
});

$('#next').click(function () {
    var numberOfStep = 7;
    var value = parseInt($("#accountStep").val());
    if (value < numberOfStep) {
        if (stepChoice("accountStep" + value)) {
            if (value == numberOfStep-1) {
                $("#next").hide();
            }
            $("#previous").show();
            $("helper").hide();
            if(value == 3){
                $('#juge').is(':checked') ? ($("#accountStep" + (value + 1)).show(), $("#accountStep").val(value+1)) : $("#volontaire").is(':checked') ? ($("#accountStep" + (value + 2)).show(), $("#accountStep").val(value+2)) : ($("#accountStep" + (value + 3)).show(), $("#accountStep").val(value+3));
            }else if(value == 4){
                $('#volontaire').is(':checked') ? ($("#accountStep" + (value + 1)).show(), $("#accountStep").val(value+1)) : ($("#accountStep" + (value + 2)).show(), $("#accountStep").val(value+2));
            }else{
                $("#accountStep" + (value + 1)).show();
                $("#accountStep").val(value+1);
            }
            $("#accountStep" + (value)).hide();

        }
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
        if(value == 6){
            $('#volontaire').is(':checked') ? ($("#accountStep" + (value - 1)).show(), $("#accountStep").val(value-1)) : $("#juge").is(':checked') ? ($("#accountStep" + (value - 2)).show(), $("#accountStep").val(value-2)) : ($("#accountStep" + (value - 3)).show(), $("#accountStep").val(value-3));
        }else if(value == 5){
            $('#juge').is(':checked') ? ($("#accountStep" + (value - 1)).show(), $("#accountStep").val(value-1)) : ($("#accountStep" + (value - 2)).show(), $("#accountStep").val(value-2));
        }else{
            $("#accountStep" + (value - 1)).show();
            $("#accountStep").val(value-1);
        }
        $("#accountStep" + (value)).hide();
    } else {
        $("#previous").hide();
    }
});

$('#juge').on('change', function () {
    $(this).is(':checked') ? $("#judgeDiv").show() : $("#judgeDiv").hide();
});

$('#volontaire').on('change', function () {
    $(this).is(':checked') ? $("#volunteerDiv").show() : $("#volunteerDiv").hide();
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
        default:
            console.log('Sorry, we are out of ' + step + '.');
    }
}

function step1() {
    var bool = true;
    (isEmpty('account[firstName]') ? ($("#firstNameHelp").show(), bool = false) : $("#firstNameHelp").hide());
    (isEmpty('account[lastName]') ? ($("#lastNameHelp").show(), bool = false) : $("#lastNameHelp").hide() );
    (isEmpty('account[zipCode]') ? ($("#zipCodeHelp").show(), bool = false) : ((isValidationZipCode('account[zipCode]')) ? ($("#zipCodeHelp").show(), bool = false) : $("#zipCodeHelp").hide()));
    (isValidationList('#account_city') ? ($("#cityHelp").show(), bool = false) : $("#cityHelp").hide() );
    (isEmpty('account[address]') ? ($("#addressHelp").show(), bool = false) : $("#addressHelp").hide() );
    (isEmpty('account[email]') ? ($("#emailHelp").show(), bool = false) : ((isValidationEmail('account[email]')) ? ($("#emailHelp").show(), bool = false) : $("#emailHelp").hide()));
    (isEmpty('account[password]') ? ($("#passwordHelp").show(), bool = false ): ((isValidationPassword('account[password]')) ? ($("#passwordHelp").show(), bool = false) : $("#passwordHelp").hide()));
    return bool;
}

function step2(){
    var bool = true;
    (isEmpty('account[children][0][phoneRep1]') ? ($("#phoneRep1Help").show(), bool = false) : ((isValidationPhoneNumber('account[children][0][phoneRep1]')) ? ($("#phoneRep1Help").show(), bool = false) : $("#phoneRep1Help").hide()));
    (isEmpty('account[children][0][professionRep1]') ? ($("#professionRep1Help").show(), bool = false) : $("#professionRep1Help").hide() )
    return bool;
}

function step7(){
    var bool = true;
    (isValidationRadio('acceptRules') ? ($("#rulesHelp").show(), bool = false) : $("#rulesHelp").hide())
    (isValidationRadio('acceptRGPD') ? ($("#rgpdHelp").show(), bool = false) : $("#rgpdHelp").hide())
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

function isValidationPassword(namePassword){
    var pattern = new RegExp(/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,15})$/);
    if (pattern.test($('input[name="' + namePassword + '"]').val())) {
        return false;
    } else {
        return true;
    }
}

$('#account_zipCode').focusout( function(){
    $.ajax({
        url:'https://datanova.legroupe.laposte.fr/api/records/1.0/search/',
        type: "POST",
        dataType: "json",
        data: {
            "dataset": "laposte_hexasmal",
            "refine.code_postal": $('#account_zipCode').val(),
        },
        success: function (data)
        {
            $('#account_city option').remove();
            for (var i in data["records"]) {
                commune = data["records"][i]["fields"]["nom_de_la_commune"];
                $('#account_city').append(new Option(commune, commune));
            }
        }
    })
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

