/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function ($) {
    $('.publishing-action').prepend('<a href="' + getHomeUrl() + '/wp-admin/themes.php?page=remove-all-menu-items"><input type="button" name="truncate_menu" id="" class="button button-primary menu-save" value="Remove all items"></a>');
});

function getHomeUrl() {
    var href = window.location.href;
    var index = href.indexOf('/wp-admin');
    var homeUrl = href.substring(0, index);
    return homeUrl;
}