<?php
/**
 * Template para renderizar el bloque de estadísticas de transfers
 * 
 * Variables disponibles:
 * - $data: Array con los datos del JSON
 * - $json_url: URL del JSON
 * - $mostrar_tabla: Boolean
 * - $mostrar_estadisticas: Boolean
 * - $numero_items: Integer
 * 
 * @package Bloque_Transfers
 */

// Verificar que no se acceda directamente
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Preparar datos
$transfers = is_array( $data ) ? array_slice( $data, 0, $numero_items ) : array();
$total_transfers = is_array( $data ) ? count( $data ) : 0;

// Calcular estadísticas
$total_pasajeros = 0;
$total_ingresos = 0;
$vehiculos = array();
$rutas = array();

foreach ( $data as $transfer ) {
    if ( isset( $transfer['pasajeros'] ) ) {
        $total_pasajeros += intval( $transfer['pasajeros'] );
    }
    if ( isset( $transfer['precio'] ) ) {
        $total_ingresos += floatval( $transfer['precio'] );
    }
    if ( isset( $transfer['vehiculo'] ) ) {
        $vehiculo = $transfer['vehiculo'];
        if ( ! isset( $vehiculos[ $vehiculo ] ) ) {
            $vehiculos[ $vehiculo ] = 0;
        }
        $vehiculos[ $vehiculo ]++;
    }
    if ( isset( $transfer['origen'] ) && isset( $transfer['destino'] ) ) {
        $ruta = $transfer['origen'] . ' → ' . $transfer['destino'];
        if ( ! isset( $rutas[ $ruta ] ) ) {
            $rutas[ $ruta ] = 0;
        }
        $rutas[ $ruta ]++;
    }
}

// Ordenar para obtener los más populares
arsort( $vehiculos );
arsort( $rutas );

$vehiculo_popular = ! empty( $vehiculos ) ? array_key_first( $vehiculos ) : 'N/A';
$ruta_popular = ! empty( $rutas ) ? array_key_first( $rutas ) : 'N/A';
$promedio_pasajeros = $total_transfers > 0 ? round( $total_pasajeros / $total_transfers, 1 ) : 0;
$promedio_ingresos = $total_transfers > 0 ? round( $total_ingresos / $total_transfers, 2 ) : 0;
?>

<div class="bloque-transfers-container">
    
    <?php if ( $mostrar_estadisticas ) : ?>
    
    <div class="bloque-transfers-estadisticas">
        <h2 class="bloque-transfers-title">📊 Estadísticas de Transfers</h2>
        
        <div class="stats-grid">
            <div class="stat-card stat-primary">
                <div class="stat-icon">🚗</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo esc_html( number_format( $total_transfers ) ); ?></div>
                    <div class="stat-label">Transfers Realizados</div>
                </div>
            </div>
            
            <div class="stat-card stat-success">
                <div class="stat-icon">👥</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo esc_html( number_format( $total_pasajeros ) ); ?></div>
                    <div class="stat-label">Pasajeros Transportados</div>
                    <div class="stat-subtitle">Promedio: <?php echo esc_html( $promedio_pasajeros ); ?> por transfer</div>
                </div>
            </div>
            
            <div class="stat-card stat-warning">
                <div class="stat-icon">💰</div>
                <div class="stat-content">
                    <div class="stat-value">€<?php echo esc_html( number_format( $total_ingresos, 2 ) ); ?></div>
                    <div class="stat-label">Ingresos Totales</div>
                    <div class="stat-subtitle">Promedio: €<?php echo esc_html( $promedio_ingresos ); ?> por transfer</div>
                </div>
            </div>
            
            <div class="stat-card stat-info">
                <div class="stat-icon">🔥</div>
                <div class="stat-content">
                    <div class="stat-value-small"><?php echo esc_html( $vehiculo_popular ); ?></div>
                    <div class="stat-label">Vehículo Más Popular</div>
                </div>
            </div>
        </div>
        
        <div class="popular-routes">
            <h3>🗺️ Ruta Más Popular</h3>
            <p class="route-highlight"><?php echo esc_html( $ruta_popular ); ?></p>
        </div>
    </div>
    
    <?php endif; ?>
    
    <?php if ( $mostrar_tabla && ! empty( $transfers ) ) : ?>
    
    <div class="bloque-transfers-tabla">
        <h3 class="table-title">📋 Últimos Transfers (<?php echo esc_html( $numero_items ); ?>)</h3>
        
        <div class="table-responsive">
            <table class="transfers-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Pasajeros</th>
                        <th>Vehículo</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $transfers as $transfer ) : ?>
                    <tr>
                        <td data-label="ID">
                            <span class="badge">#<?php echo esc_html( $transfer['id'] ?? 'N/A' ); ?></span>
                        </td>
                        <td data-label="Fecha">
                            <?php 
                            if ( isset( $transfer['fecha'] ) ) {
                                $fecha = date_create( $transfer['fecha'] );
                                echo $fecha ? esc_html( date_format( $fecha, 'd/m/Y' ) ) : esc_html( $transfer['fecha'] );
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </td>
                        <td data-label="Origen">
                            <strong><?php echo esc_html( $transfer['origen'] ?? 'N/A' ); ?></strong>
                        </td>
                        <td data-label="Destino">
                            <strong><?php echo esc_html( $transfer['destino'] ?? 'N/A' ); ?></strong>
                        </td>
                        <td data-label="Pasajeros">
                            <span class="badge-pasajeros">
                                👥 <?php echo esc_html( $transfer['pasajeros'] ?? '0' ); ?>
                            </span>
                        </td>
                        <td data-label="Vehículo">
                            <?php echo esc_html( $transfer['vehiculo'] ?? 'N/A' ); ?>
                        </td>
                        <td data-label="Precio">
                            <span class="precio">
                                €<?php echo esc_html( number_format( floatval( $transfer['precio'] ?? 0 ), 2 ) ); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if ( $total_transfers > $numero_items ) : ?>
        <p class="table-footer">
            Mostrando <?php echo esc_html( $numero_items ); ?> de <?php echo esc_html( $total_transfers ); ?> transfers totales
        </p>
        <?php endif; ?>
    </div>
    
    <?php endif; ?>
    
    <div class="bloque-transfers-footer">
        <p class="data-source">
            <small>📡 Datos en tiempo real desde: <code><?php echo esc_url( $json_url ); ?></code></small>
        </p>
        <p class="last-update">
            <small>⏱️ Última actualización: <?php echo esc_html( date_i18n( 'd/m/Y H:i:s' ) ); ?></small>
        </p>
    </div>
    
</div>
