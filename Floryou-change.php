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
?>