$(document).ready(function () {

    let autocompleteUrl = $(this).data('autocomplete-url');

    $('.js-user-autocomplete').autocomplete({hint: false}, [
        {
            source: function (query, cb) {
                $.ajax({
                    url: autocompleteUrl + '?query=' + query
                }).then(function (data) {
                    cb(data.users);
                })
            },
            displayKey: 'email',
            debounce: 500
        }
    ])
})
