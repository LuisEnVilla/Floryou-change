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

/*Agregar campos al Checkout
	Para poder usar el datepicker tenemos que agregar esto en el header

	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
		$( "input[name='date_shipping']" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd' 
			});
		});
	</script>
*/
add_filter( 'woocommerce_checkout_fields' , 'add_checkout_fields' );
function add_checkout_fields( $fields ) {

	$fields['shipping']['date_shipping'] = array(
	'type'		=> 'text',
	'label'		=> __('Fecha', 'woocommerce'),
	'placeholder'   => _x('Fecha de envió...', 'placeholder', 'woocommerce'),
	'required'	=> true,
	'class'		=> array('form-row-wide'),
	'clear'		=> true
	);

	$fields['shipping']['shipping_schedule'] = array(
	'type'		=> 'select',
	'label'		=> __('Horario', 'woocommerce'),
	'required'	=> true,
	'class'		=> array('form-row-wide'),
	'clear'		=> true,
	'options'	=> array(
		'no_definido'	=> 'Selecciona el horario de envio...',
		'mañana' => 'Por la mañana',
		'tarde' => 'Por la Tarde',
		'noche' => 'Por la noche')
	);

     return $fields;
}
?>