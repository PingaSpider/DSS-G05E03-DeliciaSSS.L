
document.addEventListener('DOMContentLoaded', function() {

    // Seleccionar elementos del DOM
    const avatarContainer = document.querySelector('.avatar-container');
    const dropdownMenu = document.getElementById('avatarMenu');
    
    // Función para mostrar/ocultar el menú al hacer clic en el avatar
    avatarContainer.addEventListener('click', function() {
        dropdownMenu.classList.toggle('show');
    });
    
    // Cerrar el menú si el usuario hace clic fuera de él
    window.addEventListener('click', function(event) {
        if (!avatarContainer.contains(event.target)) {
            if (dropdownMenu.classList.contains('show')) {
                dropdownMenu.classList.remove('show');
            }
        }
    });
   
});