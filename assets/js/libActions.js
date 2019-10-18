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
        // get books modal
        $.get('/library/book_list_to_add', { id: libId })
            .done(function (data) {
                modalPlaceholder.html(data);
                modalPlaceholder.find('.modal').modal('show');
            });
        // disable or enable add button
        modalPlaceholder.on('change', '#addCheckbox', function () {
            let saveBtn = modalPlaceholder.find('[data-save="modal"]');
            if ($(this).is(':checked')) {
                saveBtn.removeAttr('disabled');
            } else {
                saveBtn.attr('disabled', true);
            }
        });
        modalPlaceholder.on('click', '[data-save="modal"]', function () {
            let table = modalPlaceholder.find('#booksToAdd');
            let bookIdsToAdd;
            table.find('.form-check-input').each(function (i, el) {
               if (el.checked)
               {
                   bookIdsToAdd = el.dataset['bookId'];
               }
            });
            console.log(bookIdsToAdd);
        });
    });
});
