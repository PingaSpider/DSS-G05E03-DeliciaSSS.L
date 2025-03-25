document.addEventListener('DOMContentLoaded', function() {
    // Referencias a elementos del DOM
    const menuTitle = document.getElementById('menuTitle');
    const categories = document.querySelectorAll('.category');
    const menuSections = document.querySelectorAll('.menu-section');
    
    // Función para mostrar una sección específica del menú
    function showMenuSection(sectionId) {
        // Ocultar todas las secciones
        menuSections.forEach(section => {
            section.classList.remove('active');
        });
        
        // Mostrar la sección seleccionada
        const targetSection = document.getElementById(sectionId);
        if (targetSection) {
            targetSection.classList.add('active');
        }
    }
    
    // Evento para el título principal (volver al menú del día)
    menuTitle.addEventListener('click', function() {
        showMenuSection('menuDelDia');
    });
    
    // Eventos para cada categoría
    categories.forEach(category => {
        category.addEventListener('click', function() {
            const categoryId = this.getAttribute('data-category');
            showMenuSection(categoryId);
        });
    });
    
    // Función para inicializar la página
    function initPage() {
        // Mostrar el menú del día por defecto
        showMenuSection('menuDelDia');
        
        // Crear placeholders para imágenes si no están disponibles
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            img.addEventListener('error', function() {
                this.src = 'data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22286%22%20height%3D%22180%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20286%20180%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_1759e369f07%20text%20%7B%20fill%3A%23999%3Bfont-weight%3Anormal%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A14pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_1759e369f07%22%3E%3Crect%20width%3D%22286%22%20height%3D%22180%22%20fill%3D%22%23f8f8f8%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2296.828125%22%20y%3D%2296.3%22%3EImagen%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E';
            });
        });
    }
    
    // Inicializar la página
    initPage();
    
    // Para probar la navegación
    function setupTestLinks() {
        console.log("Navegación de menú inicializada");
        console.log("- Haz clic en una categoría para ver sus productos");
        console.log("- Haz clic en MENU para volver al menú del día");
    }
    
    // Animaciones para mejorar la experiencia de usuario
    function setupAnimations() {
        // Añadir efectos de hover a los productos
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
                this.style.transition = 'all 0.3s ease';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
        
        // Añadir efectos a los elementos del menú
        const menuItems = document.querySelectorAll('.category-item');
        menuItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#fffdf5';
                this.style.transition = 'background-color 0.3s ease';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.backgroundColor = 'transparent';
            });
        });
    }
    
    // Cargar contenido dinámicamente (simulación)
    function loadCategoryContent(categoryId) {
        // En una aplicación real, aquí podrías hacer una llamada AJAX para obtener datos
        // Para esta demo, simplemente mostramos y ocultamos contenido
        
        // Indicador visual de carga
        const targetSection = document.getElementById(categoryId);
        if (targetSection) {
            // Simular carga
            targetSection.style.opacity = '0.5';
            
            setTimeout(() => {
                targetSection.style.opacity = '1';
                // Aquí se actualizaría el contenido si se cargara de una API
            }, 300);
        }
    }
    
    // Funcionalidad para filtrar productos (demostración)
    function setupFilters() {
        // Esta función se implementaría si hubiera filtros en la UI
        // Por ejemplo, para filtrar por precio o tipo de producto
        
        // Para demo, podemos añadir un filtro simple con consola
        console.log("Para filtrar productos, usa filterProducts('tipo') en consola");
        
        window.filterProducts = function(type) {
            console.log(`Filtrando productos por: ${type}`);
            // En una implementación real, esto filtraría los productos mostrados
        };
    }
    
    // Ejecutar configuraciones adicionales
    setupAnimations();
    setupFilters();
    
    // Exponer algunas funciones para depuración
    window.showMenuSection = showMenuSection;
    window.loadCategoryContent = loadCategoryContent;
});