<?php
trait CommonNinja_Menu
{
    public function add_admin_menu()
    {
        add_menu_page(
            $this->config['plugin_page_title'],
            $this->config['menu_item_name'],
            'manage_options',
            $this->config['plugin_page_slug'],
            [$this, 'handle_plugin_page'],
            'none',
        );
    }
}
