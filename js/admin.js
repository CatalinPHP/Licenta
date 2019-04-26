(function ($) {
    function loadEntries(params, successCallback, failCallback) {
        $.ajax({
            url: "/services/search-books",
            method: "POST",
            data: params,
            dataType: "json",
            success: function (data, statusText, request) {
                successCallback(data);
            },
            error: function (error, textStatus, ErrorObj) {
                failCallback(error, textStatus, ErrorObj);
            }

        })
    }

    function adminSearchSuccessCallback(data) {
        var div = $('form div#errors');
        var tableBody = $('table#results tbody');
        if (data['errors'].length > 0) {
            tableBody.empty();
            div.empty();
            var length = data['errors'].length;
            for (var i = 0; i < length; i++) {
                div.append($('<p>', {text: data['errors'][i]}))
            }
        } else {
            div.empty();
            var tr;
            tableBody.empty();
            var books = data['books'];
            var authors;
            var categories;
            if (!books.length) {
                tr = $('<tr>');
                tr.append($('<td>', {colspan: 8, text: 'No entries found.'}));
                tableBody.append(tr);
            }
            for (var i = 0, length = books.length; i < length; i++) {
                authors = books[i]['authorNames'] ? books[i]['authorNames'].join(', ') : '';
                categories = books[i]['categoryNames'] ? books[i]['categoryNames'].join(', ') : '';
                tr = $('<tr>');
                var image = books[i]['image'] ? $('<img>', {
                    class: "imgSearch",
                    src: books[i]['image']
                }) : $('<img>', {class: "imgSearch", src: '/images/book_default.png'});
                var deepLink = books[i]['buy_link'] ? '<a href="' + books[i]['buy_link'] + '" target="_blank" >Buy link</a>' : 'N/A';
                var rating = books[i]['rating'] ? books[i]['rating'] : 'N/A';
                var language = books[i]['language'] ? books[i]['language'] : 'N/A';
                var options = '<button type="button"><a href="/admin/books/delete/' + books[i]['id'] + '"target="_blank">Delete</a></button> | <button type="button"><a href="/admin/edit/' + books[i]['id'] + '"target="_blank">Edit</a></button>';

                tr
                    .append($('<td>').append(image))
                    .append($('<td>', {text: books[i]['title']}))
                    .append($('<td>', {text: authors}))
                    .append($('<td>', {text: categories}))
                    .append($('<td>').append(deepLink))
                    .append($('<td>').append(rating))
                    .append($('<td>').append(language))
                    .append($('<td>').append(options));
                tableBody.append(
                    tr
                )
            }
        }
    }


    function adminSearchErrorCallback(error, textStatus, ErrorObj) {
        console.log('Fetch book call failed...');
        console.log(error, textStatus, ErrorObj);
    }

    $(document).ready(function (e) {
            $('#searchEntries').click(function (e) {
                e.preventDefault();
                var params = {
                    title: $('input[name="search-title"]').val(),
                    author: $('select[name="search-author"]').val(),
                    category: $('select[name="search-category"]').val(),
                    priceFrom: $('input[name="search-priceFrom"]').val(),
                    priceTo: $('input[name="search-priceTo"]').val(),
                };
                loadEntries(params, adminSearchSuccessCallback, adminSearchErrorCallback);
            });

            $('.clickable').click(function (e) {
                e.preventDefault();
                $("#shortDescr").hide();
                $("#longDescr").show();
                $(this).hide();
            });
            $('#')

        }
    );

    $(".chosen-select").chosen({
        width: "100%"
    });

})(jQuery);

$(document).ready(function () {
    var $users = $('#databaseUsers tbody')

    $.ajax({
        type: 'GET',
        url: '/services/take-users',
        dataType: 'json',
        success: function (users) {

            $.each(users, function (i, user) {
                $users.append('<tr><th>' + user.username + '</th>' +
                    '<th>' + user.image + '</th>' +
                    '<th> edit...</th></tr>'
                );

            })

        }
    })
});
(jQuery);
