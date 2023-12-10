<?php
/*
Plugin Name: REST API - For dnd 5e
Description: A API for a dnd 5e webshop
Version: 1.0
Author: Moedekjaer
*/

class API_User_Endpoints {

    public static function init() {
        add_action('rest_api_init', array(__CLASS__, 'register_endpoints'));
    }

    public static function register_endpoints() {
        // GET /users
        register_rest_route('webshop/v1', '/users', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_all_users'),
        ));

        // GET /users/{user_id}
        register_rest_route('webshop/v1', '/users/(?P<user_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_user_by_id'),
        ));

        // POST /users
        register_rest_route('webshop/v1', '/users', array(
            'methods' => 'POST',
            'callback' => array(__CLASS__, 'create_user'),
        ));

        // PUT /users/{user_id}
        register_rest_route('webshop/v1', '/users/(?P<user_id>\d+)', array(
            'methods' => 'PUT',
            'callback' => array(__CLASS__, 'update_user'),
        ));

        // DELETE /users/{user_id}
        register_rest_route('webshop/v1', '/users/(?P<user_id>\d+)', array(
            'methods' => 'DELETE',
            'callback' => array(__CLASS__, 'delete_user'),
        ));
    }

    public static function get_all_users($request) {
        global $wpdb;
        $users = $wpdb->get_results("SELECT * FROM users", ARRAY_A);
        return rest_ensure_response($users);
    }

    public static function get_user_by_id($request) {
        $user_id = $request['user_id'];
        global $wpdb;
        $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM users WHERE user_id = %d", $user_id), ARRAY_A);

        if (!$user) {
            return new WP_Error('user_not_found', 'User not found', array('status' => 404));
        }

        return rest_ensure_response($user);
    }

    public static function create_user($request) {
        $data = $request->get_params();
        global $wpdb;
        $wpdb->insert('users', $data);
        return rest_ensure_response('User created', 201);
    }

    public static function update_user($request) {
        $user_id = $request['user_id'];
        $data = $request->get_params();
        global $wpdb;
        $wpdb->update('users', $data, array('user_id' => $user_id));
        return rest_ensure_response('User updated', 200);
    }

    public static function delete_user($request) {
        $user_id = $request['user_id'];
        global $wpdb;
        $wpdb->delete('users', array('user_id' => $user_id));
        return rest_ensure_response('User deleted', 200);
    }

    public function __construct() {
        self::init();
    }
}

class API_Address_Endpoints {

    public static function init() {
        add_action('rest_api_init', array(__CLASS__, 'register_endpoints'));
    }

    public static function register_endpoints() {
        // GET /addresses
        register_rest_route('webshop/v1', '/addresses', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_all_addresses'),
        ));

        // GET /addresses?user_id={user_id}
        register_rest_route('webshop/v1', '/addresses', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_addresses_by_user_id'),
            'args' => array(
                'user_id' => array(
                    'validate_callback' => function($param, $request, $key) {
                        return is_numeric($param);
                    }
                )
            ),
        ));

        // GET /addresses/{address_id}
        register_rest_route('webshop/v1', '/addresses/(?P<address_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_address_by_id'),
        ));

        // POST /addresses
        register_rest_route('webshop/v1', '/addresses', array(
            'methods' => 'POST',
            'callback' => array(__CLASS__, 'create_address'),
        ));

        // PUT /addresses/{address_id}
        register_rest_route('webshop/v1', '/addresses/(?P<address_id>\d+)', array(
            'methods' => 'PUT',
            'callback' => array(__CLASS__, 'update_address'),
        ));

        // DELETE /addresses/{address_id}
        register_rest_route('webshop/v1', '/addresses/(?P<address_id>\d+)', array(
            'methods' => 'DELETE',
            'callback' => array(__CLASS__, 'delete_address'),
        ));
    }

    public static function get_all_addresses($request) {
        global $wpdb;
        $addresses = $wpdb->get_results("SELECT * FROM addresses", ARRAY_A);
        return rest_ensure_response($addresses);
    }

    public static function get_addresses_by_user_id($request) {
        $user_id = $request->get_param('user_id');
        global $wpdb;
        $addresses = $wpdb->get_results($wpdb->prepare("SELECT * FROM addresses WHERE user_id = %d", $user_id), ARRAY_A);
        return rest_ensure_response($addresses);
    }

    public static function get_address_by_id($request) {
        $address_id = $request['address_id'];
        global $wpdb;
        $address = $wpdb->get_row($wpdb->prepare("SELECT * FROM addresses WHERE address_id = %d", $address_id), ARRAY_A);

        if (!$address) {
            return new WP_Error('address_not_found', 'Address not found', array('status' => 404));
        }

        return rest_ensure_response($address);
    }

    public static function create_address($request) {
        $data = $request->get_params();
        global $wpdb;
        $wpdb->insert('addresses', $data);
        return rest_ensure_response('Address created', 201);
    }

    public static function update_address($request) {
        $address_id = $request['address_id'];
        $data = $request->get_params();
        global $wpdb;
        $wpdb->update('addresses', $data, array('address_id' => $address_id));
        return rest_ensure_response('Address updated', 200);
    }

    public static function delete_address($request) {
        $address_id = $request['address_id'];
        global $wpdb;
        $wpdb->delete('addresses', array('address_id' => $address_id));
        return rest_ensure_response('Address deleted', 200);
    }

    public function __construct() {
        self::init();
    }
}

class API_Item_Endpoints {

    public static function init() {
        add_action('rest_api_init', array(__CLASS__, 'register_endpoints'));
    }

    public static function register_endpoints() {
        // GET /items
        register_rest_route('webshop/v1', '/items', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_all_items'),
        ));

        // GET /items?category_id={category_id}
        register_rest_route('webshop/v1', '/items', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_items_by_category_id'),
            'args' => array(
                'category_id' => array(
                    'validate_callback' => function($param, $request, $key) {
                        return is_numeric($param);
                    }
                )
            ),
        ));

        // GET /items/{item_id}
        register_rest_route('webshop/v1', '/items/(?P<item_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_item_by_id'),
        ));

        // POST /items
        register_rest_route('webshop/v1', '/items', array(
            'methods' => 'POST',
            'callback' => array(__CLASS__, 'create_item'),
        ));

        // PUT /items/{item_id}
        register_rest_route('webshop/v1', '/items/(?P<item_id>\d+)', array(
            'methods' => 'PUT',
            'callback' => array(__CLASS__, 'update_item'),
        ));

        // DELETE /items/{item_id}
        register_rest_route('webshop/v1', '/items/(?P<item_id>\d+)', array(
            'methods' => 'DELETE',
            'callback' => array(__CLASS__, 'delete_item'),
        ));
    }

    public static function get_all_items($request) {
        global $wpdb;
        $items = $wpdb->get_results("SELECT * FROM items", ARRAY_A);
        return rest_ensure_response($items);
    }

    public static function get_items_by_category_id($request) {
        $category_id = $request->get_param('category_id');
        global $wpdb;
        $items = $wpdb->get_results($wpdb->prepare("SELECT * FROM items WHERE category_id = %d", $category_id), ARRAY_A);
        return rest_ensure_response($items);
    }

    public static function get_item_by_id($request) {
        $item_id = $request['item_id'];
        global $wpdb;
        $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM items WHERE item_id = %d", $item_id), ARRAY_A);

        if (!$item) {
            return new WP_Error('item_not_found', 'Item not found', array('status' => 404));
        }

        return rest_ensure_response($item);
    }

    public static function create_item($request) {
        $data = $request->get_params();
        global $wpdb;
        $wpdb->insert('items', $data);
        return rest_ensure_response('Item created', 201);
    }

    public static function update_item($request) {
        $item_id = $request['item_id'];
        $data = $request->get_params();
        global $wpdb;
        $wpdb->update('items', $data, array('item_id' => $item_id));
        return rest_ensure_response('Item updated', 200);
    }

    public static function delete_item($request) {
        $item_id = $request['item_id'];
        global $wpdb;
        $wpdb->delete('items', array('item_id' => $item_id));
        return rest_ensure_response('Item deleted', 200);
    }

    public function __construct() {
        self::init();
    }
}

class API_Item_Category_Endpoints {

    public static function init() {
        add_action('rest_api_init', array(__CLASS__, 'register_endpoints'));
    }

    public static function register_endpoints() {
        // GET /item-categories
        register_rest_route('webshop/v1', '/item-categories', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_all_item_categories'),
        ));

        // GET /item-categories/{category_id}
        register_rest_route('webshop/v1', '/item-categories/(?P<category_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_item_category_by_id'),
        ));

        // POST /item-categories
        register_rest_route('webshop/v1', '/item-categories', array(
            'methods' => 'POST',
            'callback' => array(__CLASS__, 'create_item_category'),
        ));

        // PUT /item-categories/{category_id}
        register_rest_route('webshop/v1', '/item-categories/(?P<category_id>\d+)', array(
            'methods' => 'PUT',
            'callback' => array(__CLASS__, 'update_item_category'),
        ));

        // DELETE /item-categories/{category_id}
        register_rest_route('webshop/v1', '/item-categories/(?P<category_id>\d+)', array(
            'methods' => 'DELETE',
            'callback' => array(__CLASS__, 'delete_item_category'),
        ));
    }

    public static function get_all_item_categories($request) {
        global $wpdb;
        $item_categories = $wpdb->get_results("SELECT * FROM items_category", ARRAY_A);
        return rest_ensure_response($item_categories);
    }

    public static function get_item_category_by_id($request) {
        $category_id = $request['category_id'];
        global $wpdb;
        $item_category = $wpdb->get_row($wpdb->prepare("SELECT * FROM items_category WHERE category_id = %d", $category_id), ARRAY_A);

        if (!$item_category) {
            return new WP_Error('category_not_found', 'Item category not found', array('status' => 404));
        }

        return rest_ensure_response($item_category);
    }

    public static function create_item_category($request) {
        $data = $request->get_params();
        global $wpdb;
        $wpdb->insert('items_category', $data);
        return rest_ensure_response('Item category created', 201);
    }

    public static function update_item_category($request) {
        $category_id = $request['category_id'];
        $data = $request->get_params();
        global $wpdb;
        $wpdb->update('items_category', $data, array('category_id' => $category_id));
        return rest_ensure_response('Item category updated', 200);
    }

    public static function delete_item_category($request) {
        $category_id = $request['category_id'];
        global $wpdb;
        $wpdb->delete('items_category', array('category_id' => $category_id));
        return rest_ensure_response('Item category deleted', 200);
    }

    public function __construct() {
        self::init();
    }
}

class API_Order_Endpoints {

    public static function init() {
        add_action('rest_api_init', array(__CLASS__, 'register_endpoints'));
    }

    public static function register_endpoints() {
        // GET /orders
        register_rest_route('webshop/v1', '/orders', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_all_orders'),
        ));

        // GET /orders?user_id={user_id}
        register_rest_route('webshop/v1', '/orders', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_orders_by_user_id'),
            'args' => array(
                'user_id' => array(
                    'validate_callback' => function($param, $request, $key) {
                        return is_numeric($param);
                    }
                ),
            ),
        ));

        // GET /orders/{order_id}
        register_rest_route('webshop/v1', '/orders/(?P<order_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_order_by_id'),
        ));

        // POST /orders
        register_rest_route('webshop/v1', '/orders', array(
            'methods' => 'POST',
            'callback' => array(__CLASS__, 'create_order'),
        ));

        // PUT /orders/{order_id}
        register_rest_route('webshop/v1', '/orders/(?P<order_id>\d+)', array(
            'methods' => 'PUT',
            'callback' => array(__CLASS__, 'update_order'),
        ));

        // DELETE /orders/{order_id}
        register_rest_route('webshop/v1', '/orders/(?P<order_id>\d+)', array(
            'methods' => 'DELETE',
            'callback' => array(__CLASS__, 'delete_order'),
        ));
    }

    public static function get_all_orders($request) {
        global $wpdb;
        $orders = $wpdb->get_results("SELECT * FROM orders", ARRAY_A);
        return rest_ensure_response($orders);
    }

    public static function get_orders_by_user_id($request) {
        $user_id = $request->get_param('user_id');
        global $wpdb;
        $orders = $wpdb->get_results($wpdb->prepare("SELECT * FROM orders WHERE user_id = %d", $user_id), ARRAY_A);
        return rest_ensure_response($orders);
    }

    public static function get_order_by_id($request) {
        $order_id = $request['order_id'];
        global $wpdb;
        $order = $wpdb->get_row($wpdb->prepare("SELECT * FROM orders WHERE order_id = %d", $order_id), ARRAY_A);

        if (!$order) {
            return new WP_Error('order_not_found', 'Order not found', array('status' => 404));
        }

        return rest_ensure_response($order);
    }

    public static function create_order($request) {
        $data = $request->get_params();
        global $wpdb;
        $wpdb->insert('orders', $data);
        return rest_ensure_response('Order created', 201);
    }

    public static function update_order($request) {
        $order_id = $request['order_id'];
        $data = $request->get_params();
        global $wpdb;
        $wpdb->update('orders', $data, array('order_id' => $order_id));
        return rest_ensure_response('Order updated', 200);
    }

    public static function delete_order($request) {
        $order_id = $request['order_id'];
        global $wpdb;
        $wpdb->delete('orders', array('order_id' => $order_id));
        return rest_ensure_response('Order deleted', 200);
    }

    public function __construct() {
        self::init();
    }
}

class API_Order_Item_Endpoints {

    public static function init() {
        add_action('rest_api_init', array(__CLASS__, 'register_endpoints'));
    }

    public static function register_endpoints() {
        // GET /order-items
        register_rest_route('webshop/v1', '/order-items', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_all_order_items'),
        ));

        // GET /order-items?order_id={order_id}
        register_rest_route('webshop/v1', '/order-items', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_order_items_by_order_id'),
            'args' => array(
                'order_id' => array(
                    'validate_callback' => function($param, $request, $key) {
                        return is_numeric($param);
                    }
                ),
            ),
        ));

        // GET /order-items/{order_item_id}
        register_rest_route('webshop/v1', '/order-items/(?P<order_item_id>\d+)', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_order_item_by_id'),
        ));

        // POST /order-items
        register_rest_route('webshop/v1', '/order-items', array(
            'methods' => 'POST',
            'callback' => array(__CLASS__, 'create_order_item'),
        ));

        // PUT /order-items/{order_item_id}
        register_rest_route('webshop/v1', '/order-items/(?P<order_item_id>\d+)', array(
            'methods' => 'PUT',
            'callback' => array(__CLASS__, 'update_order_item'),
        ));

        // DELETE /order-items/{order_item_id}
        register_rest_route('webshop/v1', '/order-items/(?P<order_item_id>\d+)', array(
            'methods' => 'DELETE',
            'callback' => array(__CLASS__, 'delete_order_item'),
        ));
    }

    public static function get_all_order_items($request) {
        global $wpdb;
        $order_items = $wpdb->get_results("SELECT * FROM order_items", ARRAY_A);
        return rest_ensure_response($order_items);
    }

    public static function get_order_items_by_order_id($request) {
        $order_id = $request->get_param('order_id');
        global $wpdb;
        $order_items = $wpdb->get_results($wpdb->prepare("SELECT * FROM order_items WHERE order_id = %d", $order_id), ARRAY_A);
        return rest_ensure_response($order_items);
    }

    public static function get_order_item_by_id($request) {
        $order_item_id = $request['order_item_id'];
        global $wpdb;
        $order_item = $wpdb->get_row($wpdb->prepare("SELECT * FROM order_items WHERE order_item_id = %d", $order_item_id), ARRAY_A);

        if (!$order_item) {
            return new WP_Error('order_item_not_found', 'Order item not found', array('status' => 404));
        }

        return rest_ensure_response($order_item);
    }

    public static function create_order_item($request) {
        $data = $request->get_params();
        global $wpdb;
        $wpdb->insert('order_items', $data);
        return rest_ensure_response('Order item created', 201);
    }

    public static function update_order_item($request) {
        $order_item_id = $request['order_item_id'];
        $data = $request->get_params();
        global $wpdb;
        $wpdb->update('order_items', $data, array('order_item_id' => $order_item_id));
        return rest_ensure_response('Order item updated', 200);
    }

    public static function delete_order_item($request) {
        $order_item_id = $request['order_item_id'];
        global $wpdb;
        $wpdb->delete('order_items', array('order_item_id' => $order_item_id));
        return rest_ensure_response('Order item deleted', 200);
    }

    public function __construct() {
        self::init();
    }
}

$api_user_ep = new API_User_Endpoints();
$api_address_ep = new API_Address_Endpoints();
$api_item_ep = new API_Item_Endpoints();
$api_item_category_ep = new API_Item_Category_Endpoints();
$api_order_ep = new API_Order_Endpoints();
$api_order_item_ep = new API_Order_Item_Endpoints();
?>