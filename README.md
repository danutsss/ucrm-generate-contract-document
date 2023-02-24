# Generate digital contract document

Generate digital contract documents for clients in UCRM. Using this plugin, admin can select which clients he wants to generate a digital contract to.

Also, this plugin can be used as an example to show some of the possibilities of what you can do with UCRM plugins. It can be used by developers as a template for creating a new plugin.

Read more about creating your own plugin in the [Developer documentation](../../master/docs/index.md).

## Useful classes

### `App\Service\TemplateRenderer`

Very simple class to load a PHP template. When writing a PHP template be careful to use correct escaping function: `echo htmlspecialchars($string, ENT_QUOTES);`.

## UCRM Plugin SDK

The [UCRM Plugin SDK](https://github.com/Ubiquiti-App/UCRM-Plugin-SDK) is used by this plugin. It contains classes able to help you with calling UCRM API, getting plugin's configuration and much more.
