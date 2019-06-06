// Initialize the datatable
$(document).ready(function () {
    $('#events').DataTable();
});


// This function permit to add job in event and add the checkbox field in form of event.
$('#jobForm').submit(function (e) {
    e.preventDefault();
    let form_data = $(this).serialize();
    $.ajax({
        url: $(this).attr("action"),
        type: $(this).attr("method"),
        data: form_data,
        success: function (json) {
            $.notification('fas fa-check', 'Succès : ', "Le poste vient d'être ajouté", 'success');

            let div = "<div class='form-check'>" +
                "<input type='checkbox' id='event_jobs_" + json.id + "' name='event[jobs][]' class='form-check-input' value='" + json.id + "'>" +
                "<label class='form-check-label' for='event_jobs_" + json.id + "'>" + json.name + "</label>" +
                "</div>";

            $('#jobName').val('');
            $('#modal-add-job').modal('hide');
            $('#event_jobs').append(div);
        },
        error: function (error) {
            $('#errorAddJob').append($.createErrorMsg(error.responseJSON));
            $('#jobName').val('');
        }
    })
});

// Permit to delete a document in event update part
$(function () {
    $('.delDoc').click(function (e) {
        e.preventDefault();
        let del = ".fileContainer[data-id='" + $(this).attr("data-id") + "']";

        $.ajax({
            url: $(this).attr("data-url"),
            type: "post",
            data: {
                id: $(this).attr("data-id")
            },
            success: function (response) {
                $.notification('fas fa-check', 'Succès : ', response, 'success');
                $(del).empty();
            },
            error: function (error) {
                $.notification('fa fa-exclamation-circle', 'Erreur : ', error.responseJSON, 'danger');
            }
        })
    });
});

// Create an error message for the form (same format of bootstrap error message)
$.createErrorMsg = function (msg) {
    return "<div class='row ml-1'>" +
        "<span class='invalid-feedback d-block'>" +
        "<span class='d-block'>" +
        "<span class='form-error-icon badge badge-danger text-uppercase'>Erreur</span>" +
        "<span class='form-error-message'>" + msg + "</span>" +
        "</span>" +
        "</span>" +
        "</div>";
};