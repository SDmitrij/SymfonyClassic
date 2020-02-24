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
            $.post('/book/edit', form.serialize()).done(function (data) {
                modalPlaceholder.find('.modal').modal('hide');
                location.href = "/book";
            });
        });
    });

    $(document).on('click', '#deleteBook', function () {
        let swal = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-light',
                cancelButton:  'btn btn-light',
            },
            buttonsStyling: false
        });
        swal.fire({
            title:             'Are you sure?',
            type:              'warning',
            showCancelButton:   true,
            confirmButtonText: '<span class ="fas fa-trash"></span> Yes',
            cancelButtonText:  '<span class ="fas fa-times"></span> No',
            reverseButtons:     true
        }).then((result) => {
            if (result.value) {
                $.post('/book/delete', { id: $(this).data('bookId') }).done(function (data) {
                    swal.fire(
                        'Deleted!', data.message, 'success'
                    ).then(() => {
                        location.href = "/book";
                    });
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swal.fire('Cancelled').then(_ => {});
            }
        });
    });
});
