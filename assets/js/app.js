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

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

/*
$('.next').click(function () {
    var value = parseInt(document.getElementById('next').value);
    if(value<4){
        $("#step"+(value+1)).show();
        $("#step"+(value)).hide();
        document.getElementById('next').value = value+1;
        document.getElementById('previous').value = value+1;
    }
});

$('.previous').click(function () {
    var value = parseInt(document.getElementById('previous').value);
    if(value>1){
        $("#step"+(value-1)).show();
        $("#step"+(value)).hide();
        document.getElementById('previous').value = value-1;
        document.getElementById('next').value = value-1;
    }
});
*/

$('#juge').on('change',function(){
    var val = $(this).is(':checked') ? $("#judgeDiv").show() : $("#judgeDiv").hide();
});

$('#volontaire').on('change',function(){
    var val = $(this).is(':checked') ? $("#volunteerDiv").show() : $("#volunteerDiv").hide();
});