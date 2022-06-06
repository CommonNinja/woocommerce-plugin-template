<?php
class CommonNinjaIntegrations
{
    public $config, $store_url;
    public $cn_base_url = "https://integrations.commoninja.com/integrations/woocommerce/";
    use CommonNinja_Plugin;
    use CommonNinja_Styles;
    use CommonNinja_Menu;
    use CommonNinja_Helpers;
    use CommonNinja_Auth;
    public function __construct()
    {
        $this->config = require(__DIR__ . '/../config.php');
        $this->store_url = $this->get_store_url();
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_menu', [$this, 'add_admin_styles']);
    }
}
