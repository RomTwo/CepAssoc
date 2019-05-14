if ($('#text').val() !== undefined) {
    $.notify({
        // options
        icon: 'fa fa-exclamation-circle',
        title: $('#title').val(),
        message: $('#text').val(),
        url: 'https://github.com/mouse0270/bootstrap-notify',
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
        delay: 3000,
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