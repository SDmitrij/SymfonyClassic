import $ from 'jquery'

$(function () {
    const modalPlaceholder = $('#modal-placeholder');

    $(document).on('click', '#edit', function () {
        let libId = $(this).data('libId');
        $.get('/library/edit_form/', {id: libId})
            .done(function (data) {
                modalPlaceholder.html(data);
                modalPlaceholder.find('.modal').modal('show');
            })
    });
});