<?php
/**
 * WooGravityCoupon-Snippet: Dynamically Generate WooCommerce Coupons from Gravity Forms Submissions
 * 
 * This snippet integrates WooCommerce and Gravity Forms to generate unique coupon codes for users based
 * on form submissions. The coupon codes are personalized, include a 10% discount, and expire in 30 days.
 * 
 * Usage:
 * - Add this snippet to your theme's `functions.php` file or use a code snippet manager plugin.
 * - Add the merge tag `{coupon_code}` in Gravity Forms notifications or confirmations.
 */

// Add a custom merge tag to Gravity Forms
add_filter('gform_custom_merge_tags', 'add_coupon_merge_tag', 10, 4);
/**
 * Adds a custom merge tag `{coupon_code}` to the list of Gravity Forms merge tags.
 *
 * @param array $merge_tags Existing merge tags.
 * @param int $form_id Current form ID.
 * @param array $fields Form fields.
 * @param string $element_id Current element ID.
 * @return array Modified merge tags.
 */
function add_coupon_merge_tag($merge_tags, $form_id, $fields, $element_id) {
    $merge_tags[] = array(
        'label' => 'Coupon Code',  // Label displayed in the merge tag dropdown.
        'tag' => '{coupon_code}'  // Tag to use in notifications/confirmations.
    );
    return $merge_tags;
}

// Replace the custom merge tag with a dynamically generated WooCommerce coupon code
add_filter('gform_replace_merge_tags', 'replace_coupon_merge_tag', 10, 7);
/**
 * Replaces the `{coupon_code}` merge tag with a dynamically generated WooCommerce coupon code.
 *
 * @param string $text The text with merge tags.
 * @param array $form Current form data.
 * @param array $entry Current form entry data.
 * @param bool $url_encode Whether to URL encode the output.
 * @param bool $esc_html Whether to escape HTML.
 * @param bool $nl2br Whether to convert newlines to <br>.
 * @param string $format Current format type.
 * @return string Updated text with the replaced coupon code.
 */
function replace_coupon_merge_tag($text, $form, $entry, $url_encode, $esc_html, $nl2br, $format) {
    $custom_merge_tag = '{coupon_code}';
    
    // Check if the text contains the custom merge tag
    if (strpos($text, $custom_merge_tag) === false) {
        return $text;
    }

    // Ensure WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return $text;
    }

    // Retrieve user details from form entry
    $user_name = rgar($entry, 27);  // Replace '27' with your Name field ID
    $user_email = rgar($entry, 2);  // Replace '2' with your Email field ID
    $user_phone = rgar($entry, 22);  // Replace '22' with your Phone field ID

    // Generate a unique coupon code
    $coupon_code = 'BONTXO_' . strtoupper(substr(md5($user_email . time()), 0, 5));

    // Set a descriptive coupon message
    $coupon_description = sprintf(
        '%s gets 10%% discount. Phone: %s, Email: %s',
        $user_name,
        $user_phone,
        $user_email
    );

    // Define WooCommerce coupon data
    $coupon = array(
        'post_title' => $coupon_code,
        'post_content' => '',
        'post_excerpt' => $coupon_description,  // Personalize the coupon description.
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'shop_coupon'
    );

    // Insert the coupon into the WooCommerce coupons post type
    $coupon_id = wp_insert_post($coupon);

    // Add metadata for the coupon
    update_post_meta($coupon_id, 'discount_type', 'percent');  // Discount type: percentage or fixed
    update_post_meta($coupon_id, 'coupon_amount', '10');  // 10% discount
    update_post_meta($coupon_id, 'individual_use', 'yes');  // Restrict to single use per order
    update_post_meta($coupon_id, 'usage_limit', '1');  // Allow coupon to be used only once
    update_post_meta($coupon_id, 'expiry_date', date('Y-m-d', strtotime('+30 days')));  // Set expiration (30 days)
    update_post_meta($coupon_id, 'apply_before_tax', 'yes');  // Apply discount before tax
    update_post_meta($coupon_id, 'free_shipping', 'no');  // Do not include free shipping

    // Replace the custom merge tag with the generated coupon code
    $text = str_replace($custom_merge_tag, $coupon_code, $text);

    return $text;
}

?>
