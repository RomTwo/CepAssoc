// Print a notification
if ($('#text').val() !== undefined) {
    $.notify({
        // options
        icon: $('#icon').val(),
        title: $('#title').val(),
        message: $('#text').val(),
        target: '_blank'
    }, {
        // settings
        element: 'body',
        position: null,
        type: $('#type').val(),
        allow_dismiss: true,
        newest_on_top: false,
        showProgressbar: false,
        placement: {
            from: "top",
            align: "center"
        },
        offset: 20,
        spacing: 10,
        z_index: 1031,
        delay: 3500,
        timer: 1000,
        url_target: '_blank',
        mouse_over: null,
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        },
        onShow: null,
        onShown: null,
        onClose: null,
        onClosed: null,
        icon_type: 'class'
    });
}

// Initialize tooltip (bootstrap component)
$(function () {
    $('.tool').tooltip();
});

// This function print a notification. This method is used in the event part in the calendar
$.notification = function (icon, title, msg, type) {
    $.notify({
        // options
        icon: icon,
        title: title,
        message: msg,
        target: '_blank'
    }, {
        // settings
        element: 'body',
        position: null,
        type: type,
        allow_dismiss: true,
        newest_on_top: false,
        showProgressbar: false,
        placement: {
            from: "top",
            align: "center"
        },
        offset: 20,
        spacing: 10,
        z_index: 1031,
        delay: 3500,
        timer: 1000,
        url_target: '_blank',
        mouse_over: null,
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        },
        onShow: null,
        onShown: null,
        onClose: null,
        onClosed: null,
        icon_type: 'class'
    });
};
