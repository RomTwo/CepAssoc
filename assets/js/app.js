/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.scss in this case)
require('../css/app.scss');

$ = require('jquery');
require('bootstrap');
require('datatables.net-bs4');
require('bootstrap-notify');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

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