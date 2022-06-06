<?php
trait CommonNinja_Plugin
{
    public function handle_plugin_page()
    {
        if (!$this->is_woocommerce_active_and_using_pretty_permalink()) {
            $this->render_unable_notice();
            return;
        }
        $dynamic_url = $this->get_plugin_url();
        if (!$this->get_token()) {
            wp_redirect($dynamic_url); // redirects to authorize page if no token is set
            return;
        }
        $this->render_plugin_iframe($dynamic_url);
    }
    private function get_plugin_url()
    {
        $token = $this->get_token();
        $query_params = $token ? "token={$token}" : "redirectUrl={$this->get_redirect_url()}";
        return "{$this->cn_base_url}{$this->config['cn_app_id']}/oauth/authenticate?store_url={$this->store_url}&{$query_params}";
    }
    private function render_plugin_iframe($plugin_url)
    {
?>
        <div id="poststuff" class="cn-integrations">
            <div class="postbox hide-if-js" id="postexcerpt" style="display: block; height: calc(100vh - 32px); overflow: hidden; margin: 0; border: none;">
                <iframe src="<?php echo $plugin_url ?>" width="100%" height="100%" frameborder="0"></iframe>
            </div>
        </div>
    <?php
    }
    private function render_unable_notice()
    {
    ?>
        <div class="cn-integrations cn-integrations-error">
            <h4>Warning</h4>
            <p>Unable to activate the plugin. Please make sure that:</p> 
            <ol>
                <li><a href="/wp-admin/plugin-install.php?s=woocommerce&tab=search&type=term">WooCommerce</a> is installed & activated.</li>
                <li>The <a href="/wp-admin/options-permalink.php">permalink setting</a> must <b>NOT</b> be set to "Plain" (also called the <a href="https://wordpress.org/support/article/using-permalinks/" target="_blank">Ugly Permalink</a>).</li>
            </ol>
        </div>
<?php
    }
}
