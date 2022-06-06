<?php
trait CommonNinja_Helpers
{
    private function encodeURIComponent($str)
    {
        $revert = array('%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')');
        return strtr(rawurlencode($str), $revert);
    }
    private function get_full_plugin_page_url()
    {
        return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
    private function is_displaying_plugin_page()
    {
        return !!strpos($this->get_full_plugin_page_url(), $this->config['plugin_page_slug']);
    }
    private function is_woocommerce_active_and_using_pretty_permalink()
    {
        return is_plugin_active('woocommerce/woocommerce.php') && !empty(get_option('permalink_structure'));
    }
    private function get_store_url()
    {
        return $this->encodeURIComponent(get_site_url());
    }
    private function get_redirect_url()
    {
        $url = $this->get_full_plugin_page_url();
        $url = substr($url, 0, strpos($url, "&"));
        return $this->encodeURIComponent($url);
    }
}
