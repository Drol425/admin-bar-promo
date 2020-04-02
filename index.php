<?php
/*
 * Plugin Name: Админ Бар PROMO
 * Plugin URI: https://t.me/arkaha_drol
 * Description: Админ Бар PROMO
 * Version: 1.0
 * Author: Drol
 */


//$user_id =get_current_user_id();
//if( !is_super_admin( $user_id ) ){
//echo is_user_logged_in();
  //  if ( is_user_logged_in() ) {
        function my_user_contactmethods($user_contactmethods)
        {

            $user_contactmethods['promocod'] = 'Promocod';

            return $user_contactmethods;
        }
        add_filter('user_contactmethods', 'my_user_contactmethods');
function only_admin()
{
if ( ! current_user_can( 'manage_options' ) && '/wp-admin/admin-ajax.php' != $_SERVER['PHP_SELF'] ) {
                wp_redirect( site_url() );
    }
}
add_action( 'admin_init', 'only_admin', 1 );

add_action('init', 'myFunction');

function myFunction(){

 $user_ID= get_current_user_id(); 
 if( !is_super_admin( $user_ID ) ){

    function login_redirect() {
return '/';
}
add_filter('login_redirect', 'login_redirect');

    if ( is_user_logged_in() ) {
        add_filter('show_admin_bar', '__return_true');
        show_admin_bar(true);


        add_action('admin_bar_menu', 'wp_admin_bar_my_custom_account_menu', 999);

        function wp_admin_bar_my_custom_account_menu($wp_admin_bar)
        {
            $user_id = get_current_user_id();
            $current_user = wp_get_current_user();
            $profile_url = get_edit_profile_url($user_id);

            if (0 != $user_id) {

                $promo = get_user_meta($user_id, 'promocod', true);
                /* Add the "My Account" menu */
                $avatar = get_avatar($user_id, 28);
                $howdy = sprintf(__('Привет, %1$s, твой промокод '), $current_user->display_name);
                $class = empty($avatar) ? '' : 'with-avatar';
                $linkout = wp_logout_url(home_url());
                $wp_admin_bar->add_menu(array(
                    'id' => 'my-account2',
                    'parent' => 'top-secondary',
                    'title' => 'выход',
                    'href' => $linkout,
                ));

                $wp_admin_bar->add_menu(array(
                    'id' => 'my-account1',
                    'parent' => 'top-secondary',
                    'title' => $howdy . $promo,
//'href' => $profile_url,
                ));

            }
        }




        function hide_search_toolbar()
        { ?>
            <style type="text/css">
                #wpadminbar #adminbarsearch {
                    display: none;
                }

                #wp-admin-bar-site-name {
                    display: none;
                }

                #wp-admin-bar-user-actions {
                    display: none;
                }
            </style>
            
        <?php }

        add_action('admin_head', 'hide_search_toolbar');
        add_action('wp_head', 'hide_search_toolbar');
//удаление из панели "комментариев" start
        function pss_remove_wp_logo($wp_admin_bar)
        {
            $wp_admin_bar->remove_node('wp-logo');
        }

        add_action('admin_bar_menu', 'pss_remove_wp_logo', 999);
        function clear_node_title($wp_admin_bar)
        {

            $all_toolbar_nodes = $wp_admin_bar->get_nodes();
            // Create an array of node ID's we'd like to remove
            $clear_titles = array(
                'site-name',
                'customize',
            );

            foreach ($all_toolbar_nodes as $node) {

                // Run an if check to see if a node is in the array to clear_titles
                if (in_array($node->id, $clear_titles)) {
                    // use the same node's properties
                    $args = $node;

                    // make the node title a blank string
                    $args->title = '';

                    // update the Toolbar node
                    $wp_admin_bar->add_node($args);
                }
            }
        }

        add_action('admin_bar_menu', 'clear_node_title', 999);
    }
    }  


}
