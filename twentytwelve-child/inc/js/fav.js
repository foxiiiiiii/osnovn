import {FavoriteService} from "./libs/favorite_lib.js";

window.removeArticle = (element) => {
    let id = element.getAttribute('data-id');
    if (id !== null)
    {
        let favoriteService = new FavoriteService();
        favoriteService.add_to_favorite(id);

        element.parentNode.remove();
    }
    return true;
}