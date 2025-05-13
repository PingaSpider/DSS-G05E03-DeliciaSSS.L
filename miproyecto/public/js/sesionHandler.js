document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar elementos del DOM
    const avatarContainer = document.querySelector('.avatar-container');
    const dropdownMenu = document.getElementById('avatarMenu');
    
    if (!avatarContainer || !dropdownMenu) {
        console.error('No se encontraron los elementos necesarios para el menú desplegable');
        return;
    }
    
    // Función para mostrar/ocultar el menú al hacer clic en el avatar
    avatarContainer.addEventListener('click', function(event) {
        dropdownMenu.classList.toggle('show');
        event.stopPropagation();
    });
    
    // Cerrar el menú si el usuario hace clic fuera de él
    window.addEventListener('click', function(event) {
        if (!avatarContainer.contains(event.target)) {
            if (dropdownMenu.classList.contains('show')) {
                dropdownMenu.classList.remove('show');
            }
        }
    });
    
    // Asegurarse de que el formulario de logout funcione correctamente
    const logoutForm = document.getElementById('logout-form');
    if (logoutForm) {
        logoutForm.addEventListener('submit', function(e) {
            // Permitir que el formulario se envíe normalmente
            // No prevenir el comportamiento por defecto
            return true;
        });
    }
});