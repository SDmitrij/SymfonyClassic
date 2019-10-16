import $ from 'jquery'

$(function () {
    const modalPlaceholder = $('#modal-placeholder');
    const libId = modalPlaceholder.data('libId');
    // edit library
    $(document).on('click', '#edit', function () {
        $.get('/library/show_edit_modal/', {id: libId})
            .done(function (data) {
                modalPlaceholder.html(data);
                modalPlaceholder.find('.modal').modal('show');
            });
        modalPlaceholder.on('click', '[data-save="modal"]', function () {
            let form = $(this).parents('.modal').find('form');
            $.post('/library/edit', form.serialize()).done(function () {
                modalPlaceholder.find('.modal').modal('hide');
            });
        });
    });
    // delete library
    $(document).on('click', '#delete', function () {
        $.get('/library/show_delete_modal')
            .done(function (data) {
                modalPlaceholder.html(data);
                modalPlaceholder.find('.modal').modal('show');
            });
        modalPlaceholder.on('click', '[data-delete="modal"]', function () {
            $.post('/library/delete', { id: libId }).done(function () {
               location.href = '/';
            });
        });
    });
    // add new books
    $(document).on('click', '#addBooks', function () {

    });
});
