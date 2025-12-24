<?php
/**
 * Functions and definitions
 *
 * @package Mi_Tema_Transfers
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function mi_tema_transfers_setup() {
    // Add support for block styles
    add_theme_support( 'wp-block-styles' );

    // Add support for editor styles
    add_theme_support( 'editor-styles' );

    // Enqueue editor styles
    add_editor_style( 'style.css' );

    // Add support for responsive embedded content
    add_theme_support( 'responsive-embeds' );

    // Add support for custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // Add support for post thumbnails
    add_theme_support( 'post-thumbnails' );

    // Add support for title tag
    add_theme_support( 'title-tag' );

    // Add support for HTML5 markup
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'script',
        'style',
    ) );

    // Add support for automatic feed links
    add_theme_support( 'automatic-feed-links' );
}
add_action( 'after_setup_theme', 'mi_tema_transfers_setup' );

/**
 * Enqueue theme styles and scripts
 */
function mi_tema_transfers_enqueue_scripts() {
    // Enqueue theme stylesheet
    wp_enqueue_style( 
        'mi-tema-transfers-style', 
        get_stylesheet_uri(), 
        array(), 
        wp_get_theme()->get( 'Version' ) 
    );

    // Enqueue Google Fonts
    wp_enqueue_style( 
        'mi-tema-transfers-fonts', 
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap', 
        array(), 
        null 
    );

    // Enqueue custom JavaScript
    wp_enqueue_script( 
        'mi-tema-transfers-script', 
        get_template_directory_uri() . '/assets/js/script.js', 
        array(), 
        wp_get_theme()->get( 'Version' ), 
        true 
    );
}
add_action( 'wp_enqueue_scripts', 'mi_tema_transfers_enqueue_scripts' );

/**
 * Register block patterns categories
 */
function mi_tema_transfers_register_block_pattern_categories() {
    register_block_pattern_category(
        'mi-tema-transfers',
        array( 'label' => __( 'Mi Tema Transfers', 'mi-tema-transfers' ) )
    );
}
add_action( 'init', 'mi_tema_transfers_register_block_pattern_categories' );

/**
 * Register navigation menus
 */
function mi_tema_transfers_register_menus() {
    register_nav_menus( array(
        'primary' => __( 'Menú Principal', 'mi-tema-transfers' ),
        'footer'  => __( 'Menú Pie de Página', 'mi-tema-transfers' ),
    ) );
}
add_action( 'init', 'mi_tema_transfers_register_menus' );

/**
 * Add custom image sizes
 */
function mi_tema_transfers_custom_image_sizes() {
    add_image_size( 'flota-thumbnail', 400, 300, true );
    add_image_size( 'flota-large', 1200, 800, true );
    add_image_size( 'hero', 1920, 800, true );
}
add_action( 'after_setup_theme', 'mi_tema_transfers_custom_image_sizes' );

/**
 * Customize excerpt length
 */
function mi_tema_transfers_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'mi_tema_transfers_excerpt_length' );

/**
 * Customize excerpt more string
 */
function mi_tema_transfers_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'mi_tema_transfers_excerpt_more' );

/**
 * Add CORS headers for API requests (útil para el bloque de transfers)
 */
function mi_tema_transfers_add_cors_headers() {
    // Solo en desarrollo - remover en producción
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        header( 'Access-Control-Allow-Origin: *' );
        header( 'Access-Control-Allow-Methods: GET, POST, OPTIONS' );
        header( 'Access-Control-Allow-Headers: Content-Type' );
    }
}
add_action( 'init', 'mi_tema_transfers_add_cors_headers' );

/**
 * Función auxiliar para obtener datos de transfers
 * Puede ser utilizada por el bloque personalizado
 * 
 * @param string $json_url URL del JSON de transfers
 * @return array|WP_Error Array de datos o error
 */
function mi_tema_transfers_get_transfers_data( $json_url ) {
    // Usar transient para cachear los datos durante 5 minutos
    $cache_key = 'transfers_data_' . md5( $json_url );
    $cached_data = get_transient( $cache_key );
    
    if ( false !== $cached_data ) {
        return $cached_data;
    }
    
    // Realizar petición HTTP
    $response = wp_remote_get( $json_url, array(
        'timeout' => 15,
        'headers' => array(
            'Accept' => 'application/json',
        ),
    ) );
    
    // Verificar errores
    if ( is_wp_error( $response ) ) {
        return $response;
    }
    
    // Obtener código de respuesta
    $response_code = wp_remote_retrieve_response_code( $response );
    if ( 200 !== $response_code ) {
        return new WP_Error( 
            'invalid_response', 
            sprintf( __( 'Error al obtener datos: código %d', 'mi-tema-transfers' ), $response_code ) 
        );
    }
    
    // Decodificar JSON
    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true );
    
    if ( null === $data ) {
        return new WP_Error( 
            'json_decode_error', 
            __( 'Error al decodificar JSON', 'mi-tema-transfers' ) 
        );
    }
    
    // Guardar en caché
    set_transient( $cache_key, $data, 5 * MINUTE_IN_SECONDS );
    
    return $data;
}

/**
 * Shortcode para mostrar estadísticas de transfers
 * Uso: [transfers_stats url="http://ejemplo.com/api/transfers.json"]
 */
function mi_tema_transfers_stats_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'url' => '',
    ), $atts, 'transfers_stats' );
    
    if ( empty( $atts['url'] ) ) {
        return '<p class="error">URL de JSON no especificada</p>';
    }
    
    $data = mi_tema_transfers_get_transfers_data( $atts['url'] );
    
    if ( is_wp_error( $data ) ) {
        return '<p class="error">' . esc_html( $data->get_error_message() ) . '</p>';
    }
    
    // Calcular estadísticas básicas
    $total_transfers = is_array( $data ) ? count( $data ) : 0;
    
    ob_start();
    ?>
    <div class="transfers-stats">
        <div class="stat-box">
            <h3><?php echo esc_html( $total_transfers ); ?></h3>
            <p><?php _e( 'Transfers realizados', 'mi-tema-transfers' ); ?></p>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'transfers_stats', 'mi_tema_transfers_stats_shortcode' );

/**
 * Añadir clase al body para páginas específicas
 */
function mi_tema_transfers_body_classes( $classes ) {
    if ( is_page_template( 'page-flota.html' ) ) {
        $classes[] = 'page-flota';
    }
    
    return $classes;
}
add_filter( 'body_class', 'mi_tema_transfers_body_classes' );
