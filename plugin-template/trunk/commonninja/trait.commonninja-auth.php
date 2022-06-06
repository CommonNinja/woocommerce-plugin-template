<?php
trait CommonNinja_Auth
{
    private function get_token()
    {
        $token = isset($_GET['token'])
            ? $_GET['token']
            : get_transient('cn_integrations_plugin_token');
        if (empty($token)) {
            delete_transient('cn_integrations_plugin_token');
            return false;
        }
        // $encoded_token = $this->encode_token($token);
        // echo $encoded_token;
        // print_r($this->decode_token($encoded_token));
        set_transient('cn_integrations_plugin_token', $token, 7 * 24 * 60);
        return $token;
    }
    // private function encode_token($token)
    // {
    //     return base64_encode("monosodium" . base64_encode($token));
    // }
    // private function decode_token($token)
    // {
    //     return explode('monosodium', base64_decode(base64_decode($token)))[1];
    // }
    // private function encrypt_salt()
    // {
    // }
    // private function decrypt_salt()
    // {
    // }
}
