
# WooCommerce Product Page Shipping Calculator

Display a shipping calculator on the product page, allowing customers to calculate shipping costs before adding products to their cart.


**Version:** `1.0`

### About

WooCommerce has a shipping calculator that appears on the cart and/or checkout pages. This plugin will help to display the shipping costs in product pages. Admin will have the privilege to display the shipping costs in any position of the product page that he wants to. 


### Installation
Clone this repository
```sh
$ git clone https://github.com/ohid/woocommerce-product-page-shipping-calculator
```

Now cd into woocommerce-product-page-shipping-calculator
```sh
$ cd woocommerce-product-page-shipping-calculator
```
Install composer
```sh
$ composer install
```

### Usage

The usage of the plugin is very simple. After activating the plugin, navigate to `WooCommerce -> Settings -> Shipping -> Shipping Options` page. 

Enable the option `Enable the shipping calculator on the product page` and save changes. This will allow to display the shipping calculator on the product page.

Now to change the location of the shipping calculator on the product page, please go to the `Shipping Calculator` page under the `Shipping` tab.

You can choose your desired location from `Shipping Calculator Position` option.

If you want to display the shipping calculator in a tab using WooCommerce Tab Manager, please first select `WooCommerce tab manager content` option from the shipping calculator position. 

Then use this shortcode `[wppsc_shipping_calculator]` inside the tab content area and the shipping calculator will be displayed in your desired tab. 

### Available hooks and filters

**Hooks** 

There are two hooks available in the plugin that can be used to display any content before and after the shipping calculator. The hooks are
1. `wppsc_before_shipping_calculator`
2. `wppsc_after_shipping_calculator`


**Filters** 

The available filters are listed below with it's functions

1. `wppsc_shipping_method_name` This hook can be used to alter the shipping method name that displays in the shipping calculator cost. 

2. `wppsc_calculator_position` This hook can be used to change the shipping calculator position in the product page. It's advised to use only available filters that is available on the product page. 

3. `wppsc_shipping_calculator_enable_country` Use this hook to turn on/off the country field in shipping calculator. 

3. `wppsc_shipping_calculator_enable_state` Use this hook to turn on/off the state field in shipping calculator. 

3. `wppsc_shipping_calculator_enable_city` Use this hook to turn on/off the city field in shipping calculator. 

3. `wppsc_shipping_calculator_enable_postcode` Use this hook to turn on/off the postcode field in shipping calculator. 

