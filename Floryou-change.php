<?php
/*
Plugin Name: Floryou Change
*/

/*Cambiar el texto del boton para agregar al carrito...*/
add_filter( 'add_to_cart_text', 'texto_personalizado' );
add_filter( 'woocommerce_product_add_to_cart_text', 'texto_personalizado' );
function texto_personalizado() {
	return __( 'Mi Texto ', 'woocommerce' );
}

/*Filtro para quitar fields del Checkout en WooComerce WP*/
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
  unset($fields['billing']['billing_country']);
  unset($fields['shipping']['shipping_country']);
  return $fields;
}


?>