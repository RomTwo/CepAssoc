$(document).ready(function () {
    $('#events').DataTable();
});

$(document).ready(function () {
    $('#divAddJob').hide();
    $('#addJob').click(function () {
        $('#divAddJob').toggle("slide");
    })
});


$('#jobForm').submit(function (e) {
    e.preventDefault();
    let form_data = $(this).serialize();
    $.ajax({
        url: $(this).attr("action"),
        type: $(this).attr("method"),
        data: form_data,
        success: function (json) {
            let div = "<div class='form-check'>" +
                "<input type='checkbox' id='event_jobs_" + json.id + "' name='event[jobs][]' class='form-check-input' value='" + json.id + "'>" +
                "<label class='form-check-label' for='event_jobs_" + json.id + "'>" + json.name + "</label>" +
                "</div>";


            $('#jobName').val('');
            $('#divAddJob').hide();
            $('#event_jobs').append(div);
        }
    })
})