<?php
trait CommonNinja_Styles
{
    public function add_admin_styles()
    {
        wp_enqueue_style('admin-styles', plugin_dir_url('') . '/commonninja-plugin/_inc/admin.css?time=' . time());
        add_action('admin_enqueue_scripts', [$this, 'add_menu_item_icon']);
        if ($this->is_displaying_plugin_page()) {
            add_action('admin_enqueue_scripts', [$this, 'hide_updates_nags']);
        }
    }
    public function add_menu_item_icon()
    {
        echo "<style>.menu-top.toplevel_page_commonninja .wp-menu-image { background-image: url('{$this->get_menu_item_icon_path()}'); } </style>";
    }
    private function get_menu_item_icon_path()
    {
        // if no icon is found, Common Ninja icon will appear
        if (empty($this->config['menu'])) {
            return plugin_dir_url('') . 'commonninja-plugin/commonninja/commonninja.png';
        }
        // an icon file stored in _inc folder locally must be begin with "./"
        if (str_starts_with($this->config['menu_item_icon'], './')) {
            return plugin_dir_url('') . 'commonninja-plugin/_inc' . substr($this->config['menu_item_icon'], 1);
        }
        // otherwise, returns the path as set in the config file
        return $this->config['menu_item_icon'];
    }
    public function hide_updates_nags()
    {
        echo '<style>.update-nag, .updated, .error, .is-dismissible { display: none !important; } #wpfooter { display: none; } #wpbody-content { padding: 0 !important; } #wpcontent { padding-left: 0 !important; padding-right: 0 !important; }</style>';
    }
}
