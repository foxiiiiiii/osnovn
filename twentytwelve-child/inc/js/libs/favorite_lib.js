export class FavoriteService {
    add_to_favorite(art_id)
    {
        jQuery.ajax({
            type: "POST",
            url: window.location.origin + '/wp-admin/admin-ajax.php',
            dataType: "json",
            data: {
                action: "add_favorite",
                id: art_id
            },
            success: function (response) {
                console.log("Response from add_favorite:", response);
            },
            error: function (response) {
                console.error("Error in add_favorite:", response);
            }
        });
    }
}