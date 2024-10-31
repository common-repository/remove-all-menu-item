<?php
/**
 * Plugin Name: Remove All Menu
 * Description: Remove All Menu, allows you to delete all menu items in a menu.
 * Version: 1.0
 * Author: XaraarTech
 * Author URI: Xaraar.com
 */
if (!function_exists('rami_remove_all_menu_items_page_callback')) {
    add_action('admin_menu', 'rami_remove_all_menu_items_page_callback');

    function rami_remove_all_menu_items_page_callback() {
        add_theme_page('Remove all menu items', 'Truncate Menus', 'edit_theme_options', 'remove-all-menu-items', 'ram_remove_all_menu_items_html');
    }

}

if (!function_exists('ram_remove_all_menu_items_html')) {

    function ram_remove_all_menu_items_html() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        if (
                isset($_POST['rami_nonce_field']) &&
                wp_verify_nonce($_POST['rami_nonce_field'], 'rami_delete_mene_items_action')
        ) {
            $deleted_count = rami_delete_menu_items();
            if ($deleted_count) {
                ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php _e('Menu truncated success! Goto menu page', 'sample-text-domain'); ?></p>
                </div>
                <?php
            }
        }

        rami_get_menus_form();
    }

}

if (!function_exists('rami_delete_menu_items')) {

    function rami_delete_menu_items() {
        $deleted_count = 0;
        if (!empty($_POST['menu_id'])) {
            $items = wp_get_nav_menu_items(filter_input(INPUT_POST, 'menu_id'));
            foreach ($items as $key => $item) {
                $rs = wp_delete_post($item->ID, true);
                $deleted_count++;
            }
        }
        return $deleted_count;
    }

}


if (!function_exists('rami_get_menus_form')) {

    function rami_get_menus_form() {
        $menus = get_terms('nav_menu');
        echo '<div class="wrap">';
        echo "<h2>" . __('Remove all menu items', 'menu-test') . "</h2>";
        echo '<p>Select menu and click Truncate button to remove all items in the menu.</p>';
        ?>
        <form name="rami_menu_form" method="post" action="" onsubmit="return confirm('Do you really want to truncate menu? This action cannot be undone.');">
            <p><?php _e("Menus:", 'menu-test'); ?> 
                <select name="menu_id">
                    <option value="">Select menu to truncate</option>
        <?php
        foreach ($menus as $key => $menu) {
            $temp_menu = (array) $menu;
            echo '<option value="' . $temp_menu['term_id'] . '">' . $temp_menu['name'] . '</option>';
        }
        ?>
                </select>
            </p>
            <hr />
            <p class="submit">
                <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Truncate Menu') ?>" />
            </p>
        <?php wp_nonce_field('rami_delete_mene_items_action', 'rami_nonce_field'); ?>

        </form>
        <?php
        echo '</div>';
    }

}

if (!function_exists('rami_js_enqueue')) {
    function rami_js_enqueue($hook) {
        wp_enqueue_script('rami_js_script', plugins_url('rami_script.js', __FILE__));
    }
    add_action('admin_enqueue_scripts', 'rami_js_enqueue');
}
