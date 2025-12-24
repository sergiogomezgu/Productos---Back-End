/**
 * Mi Tema Transfers - JavaScript principal
 * 
 * @package Mi_Tema_Transfers
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Esperar a que el DOM esté cargado
    document.addEventListener('DOMContentLoaded', function() {
        
        // Smooth scroll para enlaces internos
        const internalLinks = document.querySelectorAll('a[href^="#"]');
        internalLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Añadir clase al scroll
        let lastScroll = 0;
        const header = document.querySelector('header');
        
        if (header) {
            window.addEventListener('scroll', function() {
                const currentScroll = window.pageYOffset;
                
                if (currentScroll > 100) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
                
                lastScroll = currentScroll;
            });
        }

        console.log('Mi Tema Transfers cargado correctamente');
    });

})();
