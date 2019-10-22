import $ from 'jquery'
import Swal from 'sweetalert2'

$(function () {
    const modalPlaceholder = $('#modal-placeholder');
    const libId            = modalPlaceholder.data('libId');
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
        let swal = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-light',
                cancelButton:  'btn btn-light',
            },
            buttonsStyling: false
        });
        swal.fire({
            title: 'Are you sure?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: '<span class ="fas fa-trash"></span> Yes',
            cancelButtonText:  '<span class ="fas fa-times"></span> No',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.post('/library/delete', { id: libId }).done(function (data) {
                    swal.fire(
                        'Deleted!', data.message, 'success'
                    ).then(() => {
                        location.href = "/";
                    });
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swal.fire('Cancelled').then(_ => {});
            }
        });
    });
    // add new books
    $(document).on('click', '#addBooks', function () {
        // get books modal
        $.get('/library/book_list_to_add', { id: libId })
            .done(function (data) {
                modalPlaceholder.html(data);
                modalPlaceholder.find('.modal').modal('show');
            });
        // disable or enable add button
        modalPlaceholder.on('change', '#addCheckbox', function () {
            let saveBtn   = modalPlaceholder.find('[data-save="modal"]');
            let table     = modalPlaceholder.find('#booksToAdd');
            let boolCheck = false;
            table.find('.form-check-input').each(function (_, el) {
                if (el.checked) {
                    boolCheck = true
                }
            });
            if ($(this).is(':checked') || boolCheck) {
                saveBtn.removeAttr('disabled');
            } else {
                saveBtn.attr('disabled', true);
            }
        });
        modalPlaceholder.on('click', '[data-save="modal"]', function () {
            let table        = modalPlaceholder.find('#booksToAdd');
            let bookIdsToAdd = [];
            table.find('.form-check-input').each(function (_, el) {
               if (el.checked) {
                   bookIdsToAdd.push(el.dataset['bookId']);
               }
            });
            if (bookIdsToAdd.length !== 0) {
                $.post('/library/add_new_books', { id: libId, bookIds: bookIdsToAdd }).done(function (data) {
                    if (data.status === true) {
                        Swal.fire(data.message).then((result) => {
                            if (result.value) {
                                location.href = '/library/' + libId;
                            }
                        });
                    }
                });
            }
        });
    });
});
