# WooCommerce Template for Common Ninja's Apps

With the WooCommerce template you'll be able to quickly publish your app as a WooCommerce plugin. 

This template will use Common Ninja's API to authenticate your app with WooCommerce, and will load automatically the redirect URL you defined in your Common Ninja app.

## How to use

0. Download || clone this repo.
1. Change the main folder's name (`plugin-template`) to your plugin's slug name.
  - The plugin's slug name must be unique, lower and kebab case.
2. In the `trunk` folder, rename the `plugin-template.php` file to the slug name you chose in the previous step.
3. Open the `config.php` file under the `trunk` folder and change the following details:
  - `cn_app_id` - Your public [Common Ninja's app ID](https://www.commoninja.com/developer/apps).
  - `plugin_name` - The name of the plugin as it will appear on Wordpress's menu.
  - `plugin_icon` - The icon of the plugin as it will appear on Wordpress's menu. (accepts url, base64 format, or relative path from the `_inc` folder as root).
4. In the `plugin-template` file, change the meta data of the plugin (the comments on top).
  - Note, the `Text Domain` setting must be your plugin's slug.
5. Edit and change the details on the `readme.txt` file under the `trunk` folder.

* Please note, the actual folder that you'll use for pushing your code to Wordpress's SVN repo is the main folder (the one that includes the `trunk` and `assets` folders).

## Common Ninja documentation

Please find more information in Common Ninja's [official docs](https://docs.commoninja.com).

## Need help?

* Join our Discord community: [https://discord.com/invite/cxqUTbvMNd](https://discord.com/invite/cxqUTbvMNd)
* Contact us at [mailto:contact@commoninja.com](contact@commoninja.com)