/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.scss in this case)
require('../css/app.scss');

$ = require('jquery');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

$('#next').click(function () {
    var value = parseInt(document.getElementById('next').value);
    if (value < 5) {
        if(myFunction("step" + value)){
            $("span").hide();
            $("#step"+(value+1)).show();
            $("#step"+(value)).hide();
            document.getElementById('next').value = value+1;
            document.getElementById('previous').value = value+1;
        }
    }
});

$('#previous').click(function () {
    var value = parseInt(document.getElementById('previous').value);
    if (value > 1) {
        $("#step" + (value - 1)).show();
        $("#step" + (value)).hide();
        document.getElementById('previous').value = value - 1;
        document.getElementById('next').value = value - 1;
    }
});


$('#juge').on('change', function () {
    var val = $(this).is(':checked') ? $("#judgeDiv").show() : $("#judgeDiv").hide();
});

$('#volontaire').on('change', function () {
    var val = $(this).is(':checked') ? $("#volunteerDiv").show() : $("#volunteerDiv").hide();
});

$("input[name='selection']").on('change', function () {
    var $test = 0;

    $.each($("input[name='selection']:checked"), function () {
        var $id = $(this).val();
        $test = $test + parseInt($("#price" + $id).text());
    });
    $("#test").text($test);
});

document.getElementById('registrationType').addEventListener('change', function (event) {
    if (!event.target.validity.valid) {
        alert("Test");
    }
}, false);

function myFunction(step) {
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

    if(isValidationRadio('adherent[registrationType]')){
        $("#registrationTypeHelp").show();
        bool = false;
    }else{
        $("#registrationTypeHelp").hide();
    }

    if(isValidationInput('adherent[firstName]')){
        $("#firstNameTypeHelp").show();
        bool = false;
    }else{
        $("#firstNameTypeHelp").hide();
    }

    if(isValidationInput('adherent[lastName]')){
        $("#lastNameHelp").show();
        bool = false;
    }else{
        $("#lastNameHelp").hide();
    }

    if(isValidationRadio('adherent[sex]')){
        $("#sexHelp").show();
        bool = false;
    }else{
        $("#sexHelp").hide();
    }

    return bool;
}

function step3() {
    var bool = true;

    (isValidationInput('adherent[firstNameRep1]') ? ($("#firstNameRep1Help").show(), bool = false): $("#firstNameRep1Help").hide());
    (isValidationInput('adherent[lastNameRep1]') ? ($("#lastNameRep1Help").show(), bool = false): $("#lastNameRep1Help").hide());


    if(isValidationEmailInput('adherent[emailRep1]')){
        $("#emailRep1Help").show();
        bool = false;
    }else{
        $("#emailRep1Help").hide();
    }

    if(isValidationInput('adherent[professionRep1]')){
        $("#professionRep1Help").show();
        bool = false;
    }else{
        $("#professionRep1Help").hide();
    }

    if(isValidationInput('adherent[cityRep1]')){
        $("#cityRep1Help").show();
        bool = false;
    }else{
        $("#cityRep1Help").hide();
    }

    if(isValidationInput('adherent[addressRep1]')){
        $("#addressRep1Help").show();
        bool = false;
    }else{
        $("#addressRep1Help").hide();
    }

    if(isValidationZipCodeInput('adherent[zipCodeRep1]')){
        $("#zipCodeRep1Help").show();
        bool = false;
    }else{
        $("#zipCodeRep1Help").hide();
    }

    if(isValidationPhoneNumberInput('adherent[phoneRep1]')){
        $("#phoneRep1Help").show();
        bool = false;
    }else{
        $("#phoneRep1Help").hide();
    }

    (isValidationInput('adherent[firstNameRep2]') ? ($("#firstNameRep2Help").show(), bool = false): $("#firstNameRep2Help").hide());
    (isValidationInput('adherent[lastNameRep2]') ? ($("#lastNameRep2Help").show(), bool = false): $("#lastNameRep2Help").hide());
    (isValidationEmailInput('adherent[emailRep2]') ? ($("#emailRep2Help").show(), bool = false): $("#emailRep2Help").hide());
    (isValidationInput('adherent[professionRep2]') ? ($("#professionRep2Help").show(), bool = false): $("#professionRep2Help").hide());
    (isValidationInput('adherent[cityRep2]') ? ($("#cityRep2Help").show(), bool = false): $("#cityRep2Help").hide());
    (isValidationInput('adherent[addressRep2]') ? ($("#addressRep2Help").show(), bool = false): $("#addressRep2Help").hide());
    (isValidationZipCodeInput('adherent[zipCodeRep2]') ? ($("#zipCodeRep2Help").show(), bool = false): $("#zipCodeRep2Help").hide());
    (isValidationPhoneNumberInput('adherent[phoneRep2]') ? ($("#phoneRep2Help").show(), bool = false): $("#phoneRep2Help").hide());
    return bool;
}

function isValidationRadio(nameRadio) {
    if ($('input[name="' + nameRadio + '"]:checked').val()) {
        return false;
    } else {
        return true;
    }
}

function isValidationInput(nameInput){
    if (!$('input[name="' + nameInput + '"]').val()) {
        return true;
    } else {
        return false;
    }
}

function isValidationEmailInput(nameEmailInput){
    if (!$('input[name="' + nameEmailInput + '"]').val()) {
        return true;
    } else {
        var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
        if(pattern.test($('input[name="' + nameEmailInput + '"]').val())){
            return false;
        }else{
            return true;
        }
    }
}

function isValidationZipCodeInput(nameZipCodeInput){
    if (!$('input[name="' + nameZipCodeInput + '"]').val()) {
        return true;
    } else {
        var pattern = new RegExp(/^([0-9]{2}|(2A)|2B)[[0-9]{3}$/);
        if(pattern.test($('input[name="' + nameZipCodeInput + '"]').val())){
            return false;
        }else{
            return true;
        }
    }
}

function isValidationPhoneNumberInput(namePhoneNumberInput){
    if (!$('input[name="' + namePhoneNumberInput + '"]').val()) {
        return true;
    } else {
        var pattern = new RegExp(/^(?:(?:\+)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/);
        if(pattern.test($('input[name="' + namePhoneNumberInput + '"]').val())){
            return false;
        }else{
            return true;
        }
    }
}


