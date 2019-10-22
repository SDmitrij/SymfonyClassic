import $ from 'jquery'
import Swal from 'sweetalert2'

$(function () {
    const modalPlaceholder = $('#modal-placeholder');

    $(document).on('click', '#editBook', function () {
        $.get('/book/get_edit_modal', { id: $(this).data('bookId') }).done(function (data) {
            modalPlaceholder.html(data);
            modalPlaceholder.find('.modal').modal('show');
        });
        modalPlaceholder.on('click', '[data-save="modal"]', function () {
            let form = $(this).parents('.modal').find('form');
            $.post('/book/edit', form.serialize()).done(function () {
                modalPlaceholder.find('.modal').modal('hide');
            });
        });
    });
});
