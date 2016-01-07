<?php
	/*
	Plugin Name: Floryou Change
	*/

	/*Cambiar el texto del boton para agregar al carrito...*/
	add_filter( 'woocommerce_product_add_to_cart_text', 'texto_personalizado' );
	add_filter( 'woocommerce_product_single_add_to_cart_text', 'texto_personalizado' );
	function texto_personalizado() {
		return __( 'Comprar', 'woocommerce' );
	}

	/*Filtro para quitar fields del Checkout en Woocommerce WP*/
	add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
	function custom_override_checkout_fields( $fields ) {
	  unset($fields['billing']['billing_country']);
	  unset($fields['shipping']['shipping_country']);
	  return $fields;
	}

	/*Filtro para Agregar campos al Checkout
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


	/*Filtro para actualizar el Order Meta con los valores de los nuevos fields*/
	add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );
	function my_custom_checkout_field_update_order_meta( $order_id ) {
		if ( ! empty( $_POST['date_shipping'] ) ) {
			update_post_meta( $order_id, 'Fecha de envió', sanitize_text_field( $_POST['date_shipping'] ) );
		}
		if ( ! empty( $_POST['shipping_schedule'] ) ) {
			update_post_meta( $order_id, 'Horario de envió', sanitize_text_field( $_POST['shipping_schedule'] ) );
		}
	}


	/*Filtro para mostrar en la orden los nuevos fields*/
	add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );
	function my_custom_checkout_field_display_admin_order_meta($order){
		echo '<p><strong>'.__('Fecha de envió').':</strong> ' . get_post_meta( $order->id, 'Fecha de envió', true ) . '</p>';
		echo '<p><strong>'.__('Horario de envió').':</strong> ' . get_post_meta( $order->id, 'Horario de envió		', true ) . '</p>';
	}


	/*Mostrar los valores de los fields agregados a la paguina de gracias y vizta de la orden*/
	function kia_display_order_data( $order_id ){  
		$order = new WC_Order($order_id);
?>
	<h2><?php _e( 'Datos de Entrega' ); ?></h2>
	<table class="shop_table order_details additional_info">
		<tbody>
			<tr>
				<th scope="row"><?php _e( 'Nombre' ); ?>:</th>
				<td><?php echo esc_html( $order->shipping_first_name ); ?> <?php echo esc_html( $order->shipping_last_name ); ?></td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Dirección' ); ?>:</th>
				<td><?php echo esc_html( $order->shipping_address_1 ); ?>, <?php echo esc_html( $order->shipping_address_2 ); ?></td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Ciudad' ); ?>:</th>
				<td><?php echo esc_html( $order->shipping_city ); ?></td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'C.P.' ); ?></th>
				<td><?php echo esc_html( $order->shipping_postcode ); ?></td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Fecha de envió' ); ?>:</th>
				<td> <?php echo get_post_meta( $order_id, 'Fecha de envió', true ); ?></td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Horario de envió' ); ?>:</th>
				<td> <?php echo get_post_meta( $order_id, 'Horario de envió', true ); ?></td>
			</tr>
		</tbody>
	</table>
<?php 
	}
	add_action( 'woocommerce_thankyou', 'kia_display_order_data', 20 );
	add_action( 'woocommerce_view_order', 'kia_display_order_data', 20 );


	/*Funcion para acomodar la descripcion del producto */
	function woocommerce_template_product_description() {
		woocommerce_get_template( 'single-product/tabs/description.php' );
	}
	add_action( 'woocommerce_single_product_summary', 'woocommerce_template_product_description', 20 );

	/*Filtro para eliminar tabs dentro de la vista del producto*/
	add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );
	function woo_remove_product_tabs( $tabs ) {
		unset( $tabs['description'] );        // Remove the description tab
		// unset( $tabs['additional_information'] );      // Remove the additional information tab
		return $tabs;
	}
	
	/*Filtro para que el check de crear una cuenta este activo por default*/
	add_filter('woocommerce_create_account_default_checked' , function ($checked){
		return true;
	});

?>