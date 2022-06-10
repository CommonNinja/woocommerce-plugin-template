<?php 
(function () {
    if (!function_exists('add_action')) {
        echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
        exit;
    }

    $plugin_config = require_once(plugin_dir_path(__FILE__) . '/../config.php');
    
    $plugin_page_slug = basename(plugin_dir_path(__DIR__, '/../'));
    
    $isUsingPrettyPermalinks = function () {
        return !empty(get_option('permalink_structure'));
    };

    $isWooCommerceActivated = function () {
        return is_plugin_active('woocommerce/woocommerce.php');
    };

    $getPluginToken = function () use ($plugin_page_slug) {
        $token_key = $plugin_page_slug . '_plugin_token';

        $token = isset($_GET['token']) ? $_GET['token'] : get_transient($token_key);

        if (empty($token)) {
            delete_transient($token_key);
            return false;
        }

        set_transient($token_key, $token, 7 * 24 * 60);
        return $token;
    };

    $getEncodedUrl = function ($url) {
        $revert = array('%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')');
        return strtr(rawurlencode($url), $revert);
    };

    $getRedirectUrl = function () use ($getEncodedUrl) {
        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $url = substr($url, "&", true) ?: $url;
        return $getEncodedUrl($url);
    };

    $getStoreUrl = function () use ($getEncodedUrl) {
        return $getEncodedUrl(get_site_url());
    };

    $generatePluginUrl = function ($token) use ($plugin_config, $getRedirectUrl, $getStoreUrl) {
        $base_url = 'https://integrations.commoninja.com/integrations/woocommerce/';

        $query_params = !$token ? 'redirectUrl=http://' . $getRedirectUrl() : 'token=' . $token;

        return $base_url . $plugin_config['cn_app_id'] . '/oauth/authenticate?store_url=' . $getStoreUrl() . "&" . $query_params;
    };

    $renderPlugin = function ($plugin_url) {
        echo '<div class="cn-integrations cn-integrations-plugin">
        <iframe src="' . $plugin_url . '" width="100%" height="100%" frameborder="0"></iframe>
    </div>';
    };
    
    $getMenuIcon = function () use ($plugin_config, $plugin_page_slug) {
        // If not icon is set in the config file, a Common Ninja logo will appear
        if (empty($plugin_config['plugin_icon'])) {
            return plugin_dir_url('') . $plugin_page_slug . '/_inc/commonninja.png';
        } 

        // Load an icon stored locally in _inc file by prefixing 'plugin_icon' with './'
        if (str_starts_with($plugin_config['plugin_icon'], './')) {
            return plugin_dir_url('') . $plugin_page_slug . substr($plugin_config['plugin_icon'], 1);
        }

        // returns the icon as set in the config file  - able to render a path, url or base64-image as string
        return $plugin_config['plugin_icon'];
    };

    $renderErrorPage = function ($error_message) use ($plugin_page_slug, $getMenuIcon) {
        echo '<div class="cn-integrations cn-integrations-error">
        <img src="' . $getMenuIcon() . '" alt="Common Ninja Logo" style="max-width: 100px" />
        <h4 style="font-size: 20px; margin: 0 0 10px;">' . $error_message['error'] . '</h4>
        <p style="font-size: 16px; margin: 0 0 20px;">' . $error_message['message'] . '</p>
        <a class="action" href="' . $error_message['link'] . '">' . $error_message['action'] .'</a> 
    </div>';
    };

    $renderPluginPage = function () use ($isUsingPrettyPermalinks, $isWooCommerceActivated, $renderErrorPage, $getPluginToken, $generatePluginUrl, $renderPlugin, $plugin_page_slug) {
        if (!$isUsingPrettyPermalinks()) {
            $renderErrorPage(array(
                'error' => 'It looks like you are using the <a href="https://wordpress.org/support/article/using-permalinks/#permalink-types-1" title="See more details on using permalinks at WordPress.org documentation" class="question-mark" target="_blank">Plain Permalinks</a> setting on your website.', 
                'message' => 'Please change the permalinks setting to any other value.',
                'action' => 'Change Permalink Settings', 
                'link' => '/wp-admin/options-permalink.php'
            ));
            return;
        }
        if (!$isWooCommerceActivated()) {
            $renderErrorPage(array(
                'error' => 'Please install & activate WooCommerce in order to use this plugin.', 
                'message' => 'This plugin requires WooCommerce to be installed and activated.',
                'action' => 'Go to WooCommerce', 
                'link' => '/wp-admin/plugin-install.php?s=woocommerce&tab=search&type=term'
            ));
            return;
        }

        $token = $getPluginToken($plugin_page_slug);

        $plugin_url = $generatePluginUrl($token);

        if (!$token) {
            wp_redirect($plugin_url); // if no token is found, redirects user to WooCommerce authentication page
        }

        $renderPlugin($plugin_url);
    };

    $addPluginPage = function () use ($plugin_config, $renderPluginPage, $plugin_page_slug) {
        if (isset($plugin_config['parent_menu']) && !empty($plugin_config['parent_menu'])) {
            $parent_slug = $plugin_config['parent_menu']['slug'];
            $menu_url = menu_page_url($parent_slug, false);

            // Add top menu if doesn't exists
            if (!$menu_url) {
                add_menu_page( 
                    $plugin_config['parent_menu']['name'],
                    $plugin_config['parent_menu']['name'],
                    '', 
                    $parent_slug, 
                    null, 
                    'none',
                );
            }

            // Add submenu
            add_submenu_page(
                $parent_slug,
                $plugin_config['plugin_name'],
                $plugin_config['plugin_name'],
                'manage_options',
                $plugin_page_slug,
                $renderPluginPage,
            );
        } else {
            add_menu_page(
                $plugin_config['plugin_name'],
                $plugin_config['plugin_name'],
                'manage_options',
                $plugin_page_slug,
                $renderPluginPage,
                'none',
            );
        }
    };

    $loadPluginStyle = function () use ($plugin_page_slug) {
        wp_enqueue_style('cn-integrations-admin-styles', plugin_dir_url('') . $plugin_page_slug . '/_inc/admin.css');

        if (isset($_GET['page']) && $_GET['page'] === $plugin_page_slug) {
            wp_enqueue_style('cn-integrations-hide-update-nags', plugin_dir_url('') . $plugin_page_slug . '/_inc/hide_update_nags.css');
        };
    };

    $loadMenuIcon = function () use ($getMenuIcon, $plugin_page_slug, $plugin_config) {
        $icon_path = $getMenuIcon();
        $plugin_class = isset($plugin_config['parent_menu']) && !empty($plugin_config['parent_menu']) ? $plugin_config['parent_menu']['slug'] : $plugin_page_slug;

        echo '<style>.menu-top.toplevel_page_' . $plugin_class . ' ' . '.wp-menu-image { background-size: 50%; background-repeat: no-repeat; background-position: center; background-image: url(' . $icon_path . '); } </style>';
    };

    $init = function () use ($addPluginPage, $loadPluginStyle, $loadMenuIcon) {
        add_action('admin_menu', $addPluginPage);

        add_action('admin_enqueue_scripts', $loadPluginStyle);

        add_action('admin_enqueue_scripts', $loadMenuIcon);
    };
    
    $init();
})();
