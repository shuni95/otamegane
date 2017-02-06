$(function(){
    var favoriteRequest = function(id) {
        $.post(fav_url, {
            pokemon_id: id,
            _token: token
        }, function(data) {
            $('#icon-' + id).removeClass('empty');
        });
    };

    var unfavoriteRequest = function(id) {
        $.ajax({
            url: unfav_url,
            type: 'DELETE',
            data: {
                pokemon_id: id,
                _token: token
            },
            success: function(data) {
                $('#icon-' + id).addClass('empty');
            }
        });
    };

    var isFavorite = function(id){
        return !$('#icon-' +id).hasClass('empty');
    };

    var toggleFav = function(){
        var id = $(this).attr('id').slice(4);

        if (isFavorite(id)) {
            unfavoriteRequest(id);
        } else {
            favoriteRequest(id);
        }
    };

    $('.fav-pokemon').click(toggleFav);
});