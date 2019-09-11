<?php
if ( ! function_exists( 'subway_sub_fs' ) ) {
    // Create a helper function for easy SDK access.
    function subway_sub_fs() {
        global $sub_fs;

        if ( ! isset( $sub_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $sub_fs = fs_dynamic_init( array(
                'id'                  => '4568',
                'slug'                => 'subway',
                'type'                => 'plugin',
                'public_key'          => 'pk_3a6a0341c13d6fcd2cd323268ba6f',
                'is_premium'          => true,
                // If your plugin is a serviceware, set this option to false.
                'has_premium_version' => true,
                'has_addons'          => false,
                'has_paid_plans'      => true,
                'menu'                => array(
                    'slug'           => 'subway',
                    'contact'        => false,
                ),
                // Set the SDK to work in a sandbox mode (for development & testing).
                // IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
                'secret_key'          => 'sk_V]5t$aCT^xV^7I9i.}!Tk7DDcZ{$s',
            ) );
        }

        return $sub_fs;
    }

    // Init Freemius.
    subway_sub_fs();
    // Signal that SDK was initiated.
    do_action( 'sub_fs_loaded' );
}