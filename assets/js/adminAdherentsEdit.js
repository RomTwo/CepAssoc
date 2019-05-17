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

$('#admin_adherent_zipCodeRep1').focusout( function(){
    $.ajax({
        url:'https://datanova.legroupe.laposte.fr/api/records/1.0/search/',
        type: "POST",
        dataType: "json",
        data: {
            "dataset": "laposte_hexasmal",
            "refine.code_postal": $('#admin_adherent_zipCodeRep1').val(),
        },
        success: function (data)
        {
            $('#admin_adherent_cityRep1 option').remove();
            for (var i in data["records"]) {
                commune = data["records"][i]["fields"]["nom_de_la_commune"];
                $('#admin_adherent_cityRep1').append(new Option(commune, commune));
            }
        }
    })
});

$('#admin_adherent_zipCodeRep2').focusout( function(){
    $.ajax({
        url:'https://datanova.legroupe.laposte.fr/api/records/1.0/search/',
        type: "POST",
        dataType: "json",
        data: {
            "dataset": "laposte_hexasmal",
            "refine.code_postal": $('#admin_adherent_zipCodeRep2').val(),
        },
        success: function (data)
        {
            $('#admin_adherent_cityRep2 option').remove();
            for (var i in data["records"]) {
                commune = data["records"][i]["fields"]["nom_de_la_commune"];
                $('#admin_adherent_cityRep2').append(new Option(commune, commune));
            }
        }
    })
});
