<?php
/*
Plugin Name:    BG Woo Shipping
Plugin URI:     
Description:    The plugin is compatible with WooCommerce. It contains all the offices of the company EKONT in Bulgaria.
Version:        1.1
Author:         V.Stefanova
*/
define('WBSLURL', WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ) );
define('WBSLPATH', WP_PLUGIN_DIR."/".dirname( plugin_basename( __FILE__ ) ) );


//
// Disable default fields
add_filter( 'woocommerce_checkout_fields' , 'wbsl_custom_override_checkout_fields' );
function wbsl_custom_override_checkout_fields( $fields ) {

     unset($fields['billing']['billing_first_name']);
     unset($fields['billing']['billing_last_name']);
     unset($fields['billing']['billing_company']);
     unset($fields['billing']['billing_address_1']);
     unset($fields['billing']['billing_address_2']);
     unset($fields['billing']['billing_city']);
     unset($fields['billing']['billing_postcode']);
     unset($fields['billing']['billing_country']);
     unset($fields['billing']['billing_state']);
     unset($fields['billing']['billing_phone']);

     return $fields;

}


//
// Add custum fields
add_action( 'woocommerce_after_checkout_billing_form', 'wbsl_custom_checkout_field' );
 
function wbsl_custom_checkout_field( $checkout ) {

      woocommerce_form_field( 'name', array(
          'type'          => 'text',
          'class'         => array('my-field-class form-row-wide'),
          'label'         => __('Име и фамилия'),
          'required'      => required,
          'placeholder'   => __(''),
          ), $checkout->get_value( 'name' ));

    woocommerce_form_field( 'phone', array(
          'type'          => 'text',
          'class'         => array('my-field-class form-row-wide'),
          'label'         => __('Телефон'),
          'required'      => required,
          'placeholder'   => __(''),
          ), $checkout->get_value( 'phone' ));
 
    echo '<div id="to_address">';

      woocommerce_form_field( 'custum_grad_address', array(
          'type'          => 'text',
          'class'         => array('hide_when_office'),
          'label'         => __('Град'),
          'required'      => required,
          'placeholder'   => __(''),
      ), $checkout->get_value( 'custum_grad_address' ));
   
      woocommerce_form_field( 'custum_address', array(
          'type'          => 'text',
          'class'         => array('hide_when_office'),
          'label'         => __('Адрес'),
          'required'      => required,
          'placeholder'   => __(''),
          ), $checkout->get_value( 'custum_address' ));

    echo '</div>';

    echo '<div id="to_office">';

      woocommerce_form_field( 'custum_town', array(
          'type'          => 'text',
          'class'         => array('my-field-class form-row-wide'),
          'label'         => __('Град'),
          'required'      => required,
          'placeholder'   => __(''),
      ), $checkout->get_value( 'custum_town' ));

      woocommerce_form_field( 'custum_office', array(
          'type'          => 'select',
          'class'         => array('my-field-class form-row-wide'),
          'label'         => __('Офис'),
          'required'      => required,
          'placeholder'   => __(''),
          'options' => array(''),
      ), $checkout->get_value( 'custum_office' ));

    echo '</div>';
 
}


//
// Custum checkfield radio buttons /Shipping Mode/
add_action('woocommerce_before_checkout_billing_form', 'my_custom_checkout_field_process',1);

function my_custom_checkout_field_process() {
?>

<style>
.radio_fields { width: calc(50% - 5px); float: left; background-color: #f7f6f7; margin-bottom: 10px; padding: 10px 5px; }
.radio_fields + .radio_fields { margin-left: 10px; }
.radio-label { width: 100%; margin-bottom: 0; }
#custum_office,
#custum_office option { background-color: #f7f6f7; }
#to_office,
#to_address{ display: none; }
#custum_town li,
.ui-autocomplete li,
.ui-autocomplete option,
.ui-autocomplete ul { background-color: #dbf2e0; max-width: 280px; list-style-type: none; line-height: 2em; }
.ui-autocomplete li { position: relative; padding-left: 25px; transition: .3s ease-in; }
.ui-autocomplete li:hover { cursor: pointer; background-color: #cde6d2; }
.ui-autocomplete li:before { position: absolute; top: 50%; transform: translateY(-50%); left: 5px; content: '\27A4'; }
.ui-helper-hidden-accessible { display: none; }
ul.ui-autocomplete { margin-left: 0; }

/* Base for label styling */
.radio-field:not(:checked),
.radio-field:checked { position: absolute; left: -9999px; }
.radio-field:not(:checked) + label,
.radio-field:checked + label { position: relative; padding-left: 25px; cursor: pointer; }
/* radio aspect */
.radio-field:not(:checked) + label:before,
.radio-field:checked + label:before { position: absolute; top: 50%; transform: translateY(-50%); left: 0; width: 1.25em; height: 1.25em; border: 2px  solid #ccc; background: #fff; border-radius: 4px; box-shadow: inset 0 1px 3px rgba(0,0,0,.1); content: ''; }
/* checked mark aspect */
.radio-field:not(:checked) + label:after,
.radio-field:checked + label:after { position: absolute; top: 5px; left: 3px; font-size: 1.3em; line-height: 0.8; color: #019067;; transition: all .2s;  content: '\2713\0020'; }
/* checked mark aspect changes */
.radio-field:not(:checked) + label:after { opacity: 0; transform: scale(0); }
.radio-field:checked + label:after { opacity: 1; transform: scale(1); }
/* disabled radio */
.radio-field:disabled:not(:checked) + label:before,
.radio-field:disabled:checked + label:before { box-shadow: none; border-color: #bbb; background-color: #ddd; }
.radio-field:disabled:checked + label:after { color: #999; }
.radio-field:disabled + label { color: #aaa; }
/* hover style */
.radio-label:hover:before { border: 2px solid #999898 !important; }

/* select field */
.select { display: block; font-size: 16px; font-family: sans-serif; font-weight: 700; color: #444; line-height: 1; padding: .6em 1.4em .5em .8em; width: 100%; max-width: 100%; box-sizing: border-box; margin: 0; border: 1px solid #bbb; box-shadow: 0 1px 0 1px rgba(0,0,0,.04); border-radius: 3px; -moz-appearance: none; -webkit-appearance: none; appearance: none; background-color: #fff; background: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007CB2%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'), #f7f6f7; background-repeat: no-repeat, repeat; background-position: right .7em top 50%, 0 0; background-size: .65em auto, 100%; }
.select::-ms-expand { display: none; }
.select:focus { box-shadow: none; outline: none; }
.select option { font-weight: normal; }
/* Support for rtl text, explicit support for Hebrew */
*[dir="rtl"] .select, :root:lang(ar) .select, :root:lang(iw) .select { background-position: left .7em top 50%, 0 0; padding: .6em .8em .5em 1.4em; }
.select:disabled, .select[aria-disabled=true] { color: graytext; background: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22graytext%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'), #f7f6f7;
}
.select:disabled:hover, .select[aria-disabled=true] { border-color: #aaa; }

</style>

<span>Доставка до:</span>
<br />
<div class="radio_fields">
  <input type="radio" class="radio-field" name="shipping_mode" value="office" id="radio_office" />

  <label for="radio_office" class="radio-label">Офис на Еконт</label>
</div>

<div class="radio_fields">
  <input type="radio" class="radio-field" name="shipping_mode" value="address" id="radio_address" />

  <label for="radio_address" class="radio-label">Личен адрес</label>
</div>
<br />
<?php
}

//
// Update the order meta with field value
add_action( 'woocommerce_checkout_update_order_meta', 'wbsl_custom_checkout_field_update_order_meta' );
 
function wbsl_custom_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['name'] ) ) {
        update_post_meta( $order_id, 'name', sanitize_text_field( $_POST['name'] ) );
    }
    if ( ! empty( $_POST['phone'] ) ) {
        update_post_meta( $order_id, 'phone', sanitize_text_field( $_POST['phone'] ) );
    }
    if ( ! empty( $_POST['custum_address'] ) ) {
        update_post_meta( $order_id, 'custum_address', sanitize_text_field( $_POST['custum_address'] ) );
    }
    if ( ! empty( $_POST['custum_town'] ) ) {
        update_post_meta( $order_id, 'custum_town', sanitize_text_field( $_POST['custum_town'] ) );
    }
    if ( ! empty( $_POST['custum_office'] ) ) {
        update_post_meta( $order_id, 'custum_office', sanitize_text_field( $_POST['custum_office'] ) );
    }
    if ( ! empty( $_POST['custum_grad_address'] ) ) {
        update_post_meta( $order_id, 'custum_grad_address', sanitize_text_field( $_POST['custum_grad_address'] ) );
    }
    if( $_POST['shipping_mode'] != ''){
        update_post_meta( $order_id, 'shipping_mode',sanitize_text_field( $_POST['shipping_mode']) );
    }
}


//
// Add custum validation with errors
add_action( 'woocommerce_checkout_process', 'wbsl_woocommerce_add_error' );

function wbsl_woocommerce_add_error(  ) {
  if(   empty($_POST['shipping_mode'])) {
    wc_add_notice( __('Изберете начин на доставка','woocommerce'), 'error' );
  }
    if(   $_POST['shipping_mode'] == 'office') {
      if( empty( $_POST['name']) ){
            wc_add_notice( __('Попълнете име и фамилия','woocommerce'), 'error' );
        }
        if( empty( $_POST['custum_town']) ){
            wc_add_notice( __('Попълнете град','woocommerce'), 'error' );
        }
        if( empty($_POST['custum_office'])){
            wc_add_notice( __('Изберете Офис','woocommerce'),  'error' );
        }
        if( empty($_POST['phone'])){
            wc_add_notice( __('Попълнете телефон','woocommerce'),  'error' );
        }
    }
    if(   $_POST['shipping_mode'] == 'address') {
        if( empty( $_POST['phone']) ){
            wc_add_notice( __('Попълнете телефон','woocommerce'), 'error' );
        }
        if( empty($_POST['custum_address']) ){
            wc_add_notice( __('Попълнете адрес','woocommerce'),  'error' );
        }
        if( empty($_POST['custum_grad_address']) ){
            wc_add_notice( __('Попълнете град','woocommerce'),  'error' );
        }
    }
}


//
// Display order details on Admin page
add_action( 'woocommerce_admin_order_data_after_billing_address', 'wbsl_custom_checkout_field_display_admin_order_meta', 10, 1 );
add_action( 'woocommerce_order_details_after_customer_details', 'wbsl_custom_checkout_field_display_admin_order_meta', 10, 1 );

function wbsl_custom_checkout_field_display_admin_order_meta($order){
    if(get_post_meta( $order->id ,'shipping_mode',true) == 'office'){
      echo '<p><strong>'.__('Доставка: Офис на ЕКОНТ').'</strong><p>';
      echo '<p><strong>'.__('Име: ').':</strong> ' . get_post_meta( $order->id, 'name', true ) . '</p>';
      echo '<p><strong>'.__('Телефон: ').':</strong> ' . get_post_meta( $order->id, 'phone', true ) . '</p>';
      echo '<p><strong>'.__('Град: ').':</strong> ' . get_post_meta( $order->id, 'custum_town', true ) . '</p>';
      echo '<p><strong>'.__('Офис: ').':</strong> ' . get_post_meta( $order->id, 'custum_office', true ) . '</p>';
      echo '<p><strong>'.__('Бележка: ').':</strong> ' . $order->customer_note . '</p>';
    }
    if(get_post_meta( $order->id ,'shipping_mode',true) == 'address'){
      echo '<p><strong>'.__('Доставка: личен адрес').'</strong><p>';
      echo '<p><strong>'.__('Име: ').':</strong> ' . get_post_meta( $order->id, 'name', true ) . '</p>';
      echo '<p><strong>'.__('Телефон: ').':</strong> ' . get_post_meta( $order->id, 'phone', true ) . '</p>';
      echo '<p><strong>'.__('Град: ').':</strong> ' . get_post_meta( $order->id, 'custum_grad_address', true ) . '</p>';
      echo '<p><strong>'.__('Адрес: ').':</strong> ' . get_post_meta( $order->id, 'custum_address', true ) . '</p>';
      echo '<p><strong>'.__('Бележка: ').':</strong> ' . $order->customer_note .  '</p>';
    }
}


//
// Add jQuery-Autocomplete library
add_action('wp_enqueue_scripts', 'wbsl_scripts_method');

function my_library_method() {
   wp_register_script('library_script','https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js');
   wp_enqueue_script('library_script');
}
add_action('wp_enqueue_scripts', 'my_library_method');

function wbsl_scripts_method() {
  wp_register_script('custom_script', WBSLURL.'/js/js-auto.js', array( 'jquery' ), false, true );
  wp_enqueue_script('custom_script');
}


//
// Thank you page custumisation
remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );

add_action('woocommerce_thankyou','wbsl_custum_thank_you_fields',10);
$cutum_notes = $order->customer_note;
function wbsl_custum_thank_you_fields($order){
  if(get_post_meta( $order ,'shipping_mode',true) == 'office'){
      echo '<p><strong>'.__('Доставка: Офис на ЕКОНТ').'</strong><p>';
      echo '<p><strong>'.__('Име: ').':</strong> ' . get_post_meta( $order, 'name', true ) . '</p>';
      echo '<p><strong>'.__('Телефон: ').':</strong> ' . get_post_meta( $order, 'phone', true ) . '</p>';
      echo '<p><strong>'.__('Град: ').':</strong> ' . get_post_meta( $order, 'custum_town', true ) . '</p>';
      echo '<p><strong>'.__('Офис: ').':</strong> ' . get_post_meta( $order, 'custum_office', true ) . '</p>';
    }
    if(get_post_meta( $order ,'shipping_mode',true) == 'address'){
      echo '<p><strong>'.__('Доставка: личен адрес').'</strong><p>';
      echo '<p><strong>'.__('Име: ').':</strong> ' . get_post_meta( $order, 'name', true ) . '</p>';
      echo '<p><strong>'.__('Телефон: ').':</strong> ' . get_post_meta( $order, 'phone', true ) . '</p>';
      echo '<p><strong>'.__('Град: ').':</strong> ' . get_post_meta( $order, 'custum_grad_address', true ) . '</p>';
      echo '<p><strong>'.__('Адрес: ').':</strong> ' . $customer_notes . '</p>';
    }
}


//
// Email custumization
add_action('woocommerce_email_customer_details','wbsl_custum_email');

function wbsl_custum_email($order){
    if(get_post_meta( $order->id ,'shipping_mode',true) == 'office'){
      echo '<p><strong>'.__('Доставка: Офис на ЕКОНТ').'</strong><p>';
      echo '<p><strong>'.__('Име: ').':</strong> ' . get_post_meta( $order->id, 'name', true ) . '</p>';
      echo '<p><strong>'.__('Телефон: ').':</strong> ' . get_post_meta( $order->id, 'phone', true ) . '</p>';
      echo '<p><strong>'.__('Град: ').':</strong> ' . get_post_meta( $order->id, 'custum_town', true ) . '</p>';
      echo '<p><strong>'.__('Офис: ').':</strong> ' . get_post_meta( $order->id, 'custum_office', true ) . '</p>';
      echo '<p><strong>'.__('Бележка: ').':</strong> ' . $order->customer_note . '</p>';
    }
    if(get_post_meta( $order->id ,'shipping_mode',true) == 'address'){
      echo '<p><strong>'.__('Доставка: личен адрес').'</strong><p>';
      echo '<p><strong>'.__('Име: ').':</strong> ' . get_post_meta( $order->id, 'name', true ) . '</p>';
      echo '<p><strong>'.__('Телефон: ').':</strong> ' . get_post_meta( $order->id, 'phone', true ) . '</p>';
      echo '<p><strong>'.__('Град: ').':</strong> ' . get_post_meta( $order->id, 'custum_grad_address', true ) . '</p>';
      echo '<p><strong>'.__('Адрес: ').':</strong> ' . get_post_meta( $order->id, 'custum_address', true ) . '</p>';
      echo '<p><strong>'.__('Бележка: ').':</strong> ' . $order->customer_note  . '</p>';
    }
}
?>
