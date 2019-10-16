import $ from 'jquery'

$(function () {
    const modalPlaceholder = $('#modal-placeholder');

    $(document).on('click', '#edit', function () {
        let libId = $(this).data('libId');
        $.get('/library/show_edit_form/', {id: libId})
            .done(function (data) {
                modalPlaceholder.html(data);
                modalPlaceholder.find('.modal').modal('show');
            });
        modalPlaceholder.on('click', '[data-save="modal"]', function () {
            let url = '/library/edit';
            let form = $(this).parents('.modal').find('form');
            $.post(url, form.serialize()).done(function () {
                modalPlaceholder.find('.modal').modal('hide');
            });
        } )
    });
});
