# WooGravityCoupon
Generating Woocommerce Coupons from Gravity Forms Submissions

A WordPress code snippet that integrates WooCommerce with Gravity Forms to dynamically generate unique WooCommerce coupons based on user input from form submissions. Ideal for creating personalized discounts directly from Gravity Forms entries. Simply copy and paste this snippet into your theme's functions.php file or use it as a custom code snippet.

## Key Features:
1. **Custom Merge Tag for Gravity Forms:** Adds  *{Coupon_code}*  as a custom merge tag, enabling you to include dynamic coupon codes in notifications or confirmations.
2. **WooCommerce Integration:** Automatically generates WooCommerce coupons based on form submission data, ensuring smooth e-commerce integration.
3. **Dynamic and Personalized Coupons:** Coupons are created with:
-- A 10% discount.
-- Personalized descriptions that include the user's name, phone, and email.
-- A one-time usage limit.
-- A 30-day expiration.
4. **No Plugin Overhead:** A lightweight solution that can be added to your theme's functions.php file or used with a snippet manager.

## Usage:
1. **Add to Your Theme:** Copy and paste the code into your WordPress theme's functions.php file or use a code snippet plugin (like Code Snippets).
2. **Customize Field IDs:** Replace the field IDs (27, 2, 22) in the code with the correct field IDs from your Gravity Form for name, email, and phone.
3. **Use in Notifications:** Add *{coupon_code}* in your Gravity Form notifications or confirmations where you want the coupon code to appear.
4. **Ensure WooCommerce Is Active:** This snippet requires WooCommerce to be installed and active.
