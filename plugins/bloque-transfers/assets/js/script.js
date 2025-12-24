/**
 * JavaScript para el Bloque de Estadísticas de Transfers
 * 
 * @package Bloque_Transfers
 * @version 1.0.0
 */

(function() {
    'use strict';

    // Esperar a que el DOM esté cargado
    document.addEventListener('DOMContentLoaded', function() {
        
        // Encontrar todos los bloques de transfers
        const bloques = document.querySelectorAll('.bloque-transfers-container');
        
        if (bloques.length === 0) {
            return;
        }

        console.log('Bloque Transfers cargado:', bloques.length, 'bloque(s) encontrado(s)');

        // Animar las tarjetas de estadísticas al hacer scroll
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, {
                threshold: 0.1
            });

            bloques.forEach(bloque => {
                const statCards = bloque.querySelectorAll('.stat-card');
                statCards.forEach(card => {
                    observer.observe(card);
                });
            });
        }

        // Añadir tooltips a las celdas de la tabla (opcional)
        bloques.forEach(bloque => {
            const tableCells = bloque.querySelectorAll('.transfers-table td');
            tableCells.forEach(cell => {
                const content = cell.textContent.trim();
                if (content.length > 30) {
                    cell.setAttribute('title', content);
                }
            });
        });

        // Añadir funcionalidad de búsqueda/filtrado (opcional avanzado)
        addSearchFunctionality(bloques);

    });

    /**
     * Añadir funcionalidad de búsqueda a las tablas
     * 
     * @param {NodeList} bloques Lista de bloques de transfers
     */
    function addSearchFunctionality(bloques) {
        bloques.forEach(bloque => {
            const tabla = bloque.querySelector('.transfers-table');
            if (!tabla) return;

            // Crear campo de búsqueda
            const searchContainer = document.createElement('div');
            searchContainer.className = 'transfers-search';
            searchContainer.style.marginBottom = '1rem';
            
            const searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.placeholder = '🔍 Buscar en la tabla...';
            searchInput.className = 'transfers-search-input';
            searchInput.style.cssText = `
                width: 100%;
                padding: 0.75rem 1rem;
                border: 2px solid #e5e7eb;
                border-radius: 0.5rem;
                font-size: 0.875rem;
                transition: border-color 0.2s;
            `;

            searchContainer.appendChild(searchInput);
            
            // Insertar antes de la tabla
            const tableResponsive = bloque.querySelector('.table-responsive');
            if (tableResponsive) {
                tableResponsive.parentNode.insertBefore(searchContainer, tableResponsive);
            }

            // Funcionalidad de búsqueda
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = tabla.querySelectorAll('tbody tr');

                let visibleCount = 0;
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Mostrar mensaje si no hay resultados
                updateNoResultsMessage(bloque, visibleCount, searchTerm);
            });

            // Estilo focus
            searchInput.addEventListener('focus', function() {
                this.style.borderColor = '#1e40af';
            });

            searchInput.addEventListener('blur', function() {
                this.style.borderColor = '#e5e7eb';
            });
        });
    }

    /**
     * Actualizar mensaje de "no hay resultados"
     */
    function updateNoResultsMessage(bloque, count, searchTerm) {
        let noResultsMsg = bloque.querySelector('.no-results-message');
        
        if (count === 0 && searchTerm) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('p');
                noResultsMsg.className = 'no-results-message';
                noResultsMsg.style.cssText = `
                    text-align: center;
                    padding: 2rem;
                    color: #6b7280;
                    font-style: italic;
                `;
                const tableResponsive = bloque.querySelector('.table-responsive');
                if (tableResponsive) {
                    tableResponsive.after(noResultsMsg);
                }
            }
            noResultsMsg.textContent = `No se encontraron resultados para "${searchTerm}"`;
            noResultsMsg.style.display = 'block';
        } else if (noResultsMsg) {
            noResultsMsg.style.display = 'none';
        }
    }

    /**
     * Formatear números con separadores de miles
     */
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

})();
