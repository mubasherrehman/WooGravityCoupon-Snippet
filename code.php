<?php

add_filter('gform_custom_merge_tags', 'add_coupon_merge_tag', 10, 4);
add_filter('gform_replace_merge_tags', 'replace_coupon_merge_tag', 10, 7);

function add_coupon_merge_tag($merge_tags, $form_id, $fields, $element_id) {
    $merge_tags[] = array(
        'label' => 'Coupon Code',
        'tag' => '{coupon_code}'
    );
    return $merge_tags;
}

function replace_coupon_merge_tag($text, $form, $entry, $url_encode, $esc_html, $nl2br, $format) {
    $custom_merge_tag = '{coupon_code}';
    
    if (strpos($text, $custom_merge_tag) === false) {
        return $text;
    }

    // Make sure WooCommerce is active
    if (!class_exists('WooCommerce')) return $text;

    // Get user information from form submission
    $user_name = rgar($entry, 27);  // Replace 'name' with your actual name field ID
    $user_email = rgar($entry, 2);  // Replace 'email' with your actual email field ID
    $user_phone = rgar($entry, 22);  // Replace 'phone' with your actual phone field ID

    // Generate unique coupon code
    $coupon_code = 'BONTXO_' . strtoupper(substr(md5($user_email . time()), 0, 5));

    // Set coupon description
    $coupon_description = sprintf(
        '%s gets 10%% discount. Having phone number: %s and Email: %s',
        $user_name,
        $user_phone,
        $user_email
    );

    // Set coupon data
    $coupon = array(
        'post_title' => $coupon_code,
        'post_content' => '',
        'post_excerpt' => $coupon_description,  // Set description here
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'shop_coupon'
    );

    // Insert the coupon post
    $coupon_id = wp_insert_post($coupon);

    // Set coupon meta data
    update_post_meta($coupon_id, 'discount_type', 'percent');
    update_post_meta($coupon_id, 'coupon_amount', '10');  // 10% discount
    update_post_meta($coupon_id, 'individual_use', 'yes');
    update_post_meta($coupon_id, 'usage_limit', '1');
    update_post_meta($coupon_id, 'expiry_date', date('Y-m-d', strtotime('+30 days')));
    update_post_meta($coupon_id, 'apply_before_tax', 'yes');
    update_post_meta($coupon_id, 'free_shipping', 'no');

    // Replace the merge tag with the coupon code
    $text = str_replace($custom_merge_tag, $coupon_code, $text);

    return $text;
}

?>
