<?php
/**
 * Plugin Name: Bloque Transfers
 * Plugin URI: https://www.ejemplo.com/bloque-transfers
 * Description: Bloque personalizado para mostrar estadísticas de transfers desde una fuente JSON externa. Usa Genesis Custom Blocks.
 * Version: 1.0.0
 * Author: Sergio Gómez
 * Author URI: https://www.ejemplo.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: bloque-transfers
 * Domain Path: /languages
 *
 * @package Bloque_Transfers
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Currently plugin version.
 */
define( 'BLOQUE_TRANSFERS_VERSION', '1.0.0' );
define( 'BLOQUE_TRANSFERS_PATH', plugin_dir_path( __FILE__ ) );
define( 'BLOQUE_TRANSFERS_URL', plugin_dir_url( __FILE__ ) );

/**
 * Register custom block (shortcode simple - más compatible)
 */
function bloque_transfers_register_block() {
    // Registrar como bloque simple usando shortcode
    register_block_type( 'bloque-transfers/estadisticas', array(
        'render_callback' => 'bloque_transfers_render_shortcode_block',
    ) );
}
add_action( 'init', 'bloque_transfers_register_block' );

/**
 * Render del bloque usando shortcode
 */
function bloque_transfers_render_shortcode_block( $attributes, $content ) {
    $json_url = isset( $attributes['json_url'] ) ? $attributes['json_url'] : '';
    $numero_items = isset( $attributes['numero_items'] ) ? intval( $attributes['numero_items'] ) : 10;
    
    return bloque_transfers_shortcode( array(
        'url' => $json_url,
        'items' => $numero_items
    ) );
}

/**
 * Render callback for the block
 */
function bloque_transfers_render_callback( $attributes, $content ) {
    $json_url = isset( $attributes['json_url'] ) ? $attributes['json_url'] : '';
    $mostrar_tabla = isset( $attributes['mostrar_tabla'] ) ? $attributes['mostrar_tabla'] : true;
    $mostrar_estadisticas = isset( $attributes['mostrar_estadisticas'] ) ? $attributes['mostrar_estadisticas'] : true;
    $numero_items = isset( $attributes['numero_items'] ) ? intval( $attributes['numero_items'] ) : 10;

    if ( empty( $json_url ) ) {
        return '<div class="bloque-transfers-error"><p>' . __( '⚠️ Por favor, configura la URL del JSON en la configuración del bloque.', 'bloque-transfers' ) . '</p></div>';
    }

    // Obtener datos del JSON
    $data = bloque_transfers_get_json_data( $json_url );

    if ( is_wp_error( $data ) ) {
        return '<div class="bloque-transfers-error"><p>' . esc_html( $data->get_error_message() ) . '</p></div>';
    }

    // Incluir el template de renderizado
    ob_start();
    include BLOQUE_TRANSFERS_PATH . 'templates/block-render.php';
    return ob_get_clean();
}

/**
 * Get JSON data from URL
 * 
 * @param string $url URL of JSON endpoint
 * @return array|WP_Error Array of data or error
 */
function bloque_transfers_get_json_data( $url ) {
    // Cachear datos durante 5 minutos
    $cache_key = 'bloque_transfers_' . md5( $url );
    $cached_data = get_transient( $cache_key );

    if ( false !== $cached_data ) {
        return $cached_data;
    }

    // Si es una ruta local (empieza con /), leerla directamente del sistema de archivos
    if ( strpos( $url, '/' ) === 0 ) {
        $file_path = ABSPATH . ltrim( $url, '/' );
        
        if ( ! file_exists( $file_path ) ) {
            return new WP_Error( 
                'file_not_found', 
                sprintf( __( 'Archivo no encontrado: %s', 'bloque-transfers' ), $file_path ) 
            );
        }
        
        $body = file_get_contents( $file_path );
        
        if ( false === $body ) {
            return new WP_Error( 
                'file_read_error', 
                __( 'Error al leer el archivo JSON', 'bloque-transfers' ) 
            );
        }
    } else {
        // Realizar petición HTTP para URLs externas
        $response = wp_remote_get( $url, array(
            'timeout' => 15,
            'headers' => array(
                'Accept' => 'application/json',
            ),
        ) );

        if ( is_wp_error( $response ) ) {
            return new WP_Error( 
                'http_error', 
                sprintf( __( 'Error al conectar con la API: %s', 'bloque-transfers' ), $response->get_error_message() ) 
            );
        }

        $response_code = wp_remote_retrieve_response_code( $response );
        if ( 200 !== $response_code ) {
            return new WP_Error( 
                'invalid_response', 
                sprintf( __( 'Error en la respuesta de la API (código %d)', 'bloque-transfers' ), $response_code ) 
            );
        }

        $body = wp_remote_retrieve_body( $response );
    }

    $data = json_decode( $body, true );

    if ( null === $data ) {
        return new WP_Error( 
            'json_decode_error', 
            __( 'Error al decodificar los datos JSON', 'bloque-transfers' ) 
        );
    }

    // Guardar en caché
    set_transient( $cache_key, $data, 5 * MINUTE_IN_SECONDS );

    return $data;
}

/**
 * Enqueue block styles
 */
function bloque_transfers_enqueue_styles() {
    wp_enqueue_style( 
        'bloque-transfers-style', 
        BLOQUE_TRANSFERS_URL . 'assets/css/style.css', 
        array(), 
        BLOQUE_TRANSFERS_VERSION 
    );

    wp_enqueue_script( 
        'bloque-transfers-script', 
        BLOQUE_TRANSFERS_URL . 'assets/js/script.js', 
        array(), 
        BLOQUE_TRANSFERS_VERSION, 
        true 
    );
}
add_action( 'wp_enqueue_scripts', 'bloque_transfers_enqueue_styles' );

/**
 * Admin notice if Genesis Custom Blocks is not active
 */
function bloque_transfers_gcb_notice() {
    ?>
    <div class="notice notice-error">
        <p><?php _e( 'El plugin <strong>Bloque Transfers</strong> requiere que <strong>Genesis Custom Blocks</strong> esté instalado y activado.', 'bloque-transfers' ); ?></p>
    </div>
    <?php
}

/**
 * Shortcode alternativo para usar sin el bloque
 * Uso: [transfers_stats url="http://ejemplo.com/api/transfers.json"]
 */
function bloque_transfers_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'url' => '',
        'items' => 10,
    ), $atts, 'transfers_stats' );

    if ( empty( $atts['url'] ) ) {
        return '<p class="error">' . __( 'URL de JSON no especificada', 'bloque-transfers' ) . '</p>';
    }

    $data = bloque_transfers_get_json_data( $atts['url'] );

    if ( is_wp_error( $data ) ) {
        return '<p class="error">' . esc_html( $data->get_error_message() ) . '</p>';
    }

    // Preparar variables para el template
    $json_url = $atts['url'];
    $mostrar_tabla = true;
    $mostrar_estadisticas = true;
    $numero_items = intval( $atts['items'] );

    ob_start();
    include BLOQUE_TRANSFERS_PATH . 'templates/block-render.php';
    return ob_get_clean();
}
add_shortcode( 'transfers_stats', 'bloque_transfers_shortcode' );
add_shortcode( 'bloque_transfers', 'bloque_transfers_shortcode' ); // Alias alternativo

/**
 * Add settings page
 */
function bloque_transfers_add_settings_page() {
    add_options_page(
        __( 'Configuración Bloque Transfers', 'bloque-transfers' ),
        __( 'Bloque Transfers', 'bloque-transfers' ),
        'manage_options',
        'bloque-transfers',
        'bloque_transfers_settings_page'
    );
}
add_action( 'admin_menu', 'bloque_transfers_add_settings_page' );

/**
 * Settings page content
 */
function bloque_transfers_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php _e( 'Configuración Bloque Transfers', 'bloque-transfers' ); ?></h1>
        
        <div class="card">
            <h2><?php _e( 'Cómo usar el bloque', 'bloque-transfers' ); ?></h2>
            <ol>
                <li><?php _e( 'Edita una página o entrada', 'bloque-transfers' ); ?></li>
                <li><?php _e( 'Haz clic en el botón "+" para añadir un bloque', 'bloque-transfers' ); ?></li>
                <li><?php _e( 'Busca "Estadísticas de Transfers"', 'bloque-transfers' ); ?></li>
                <li><?php _e( 'Configura la URL del JSON en las opciones del bloque', 'bloque-transfers' ); ?></li>
            </ol>
            
            <h3><?php _e( 'Uso con shortcode', 'bloque-transfers' ); ?></h3>
            <p><?php _e( 'También puedes usar el shortcode:', 'bloque-transfers' ); ?></p>
            <code>[transfers_stats url="http://ejemplo.com/api/transfers.json" items="10"]</code>
        </div>
        
        <div class="card">
            <h2><?php _e( 'Formato del JSON', 'bloque-transfers' ); ?></h2>
            <p><?php _e( 'El JSON debe tener el siguiente formato:', 'bloque-transfers' ); ?></p>
            <pre><code>[
  {
    "id": 1,
    "fecha": "2024-01-15",
    "origen": "Aeropuerto",
    "destino": "Hotel Barcelona",
    "pasajeros": 4,
    "vehiculo": "Mercedes Clase V",
    "precio": 75.00
  },
  ...
]</code></pre>
        </div>
    </div>
    <?php
}
