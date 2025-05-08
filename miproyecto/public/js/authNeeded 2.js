document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar todos los enlaces que requieren autenticación
    const authLinks = document.querySelectorAll('.auth-required');
    
    // Crear el elemento del tooltip
    const tooltip = document.createElement('div');
    tooltip.classList.add('tooltip');
    document.body.appendChild(tooltip);

    // Crear el diálogo de autenticación
    const dialog = document.createElement('div');
    dialog.classList.add('auth-dialog');
    dialog.innerHTML = `
        <div class="auth-dialog-content">
            <div class="auth-dialog-title">Acceso restringido</div>
            <p>Es necesario tener una cuenta para acceder a esta sección.</p>
            <div class="auth-dialog-buttons">
                <button class="auth-dialog-btn btn-login" id="login-btn">Iniciar sesión</button>
                <button class="auth-dialog-btn btn-cancel" id="cancel-btn">Cancelar</button>
            </div>
        </div>
    `;
    document.body.appendChild(dialog);

    // Agregar eventos para cada enlace
    authLinks.forEach(link => {
        // Mostrar tooltip al pasar el mouse
        link.addEventListener('mouseenter', function(e) {
            const message = this.getAttribute('data-message');
            tooltip.textContent = message;
            
            // Posicionar el tooltip
            const rect = this.getBoundingClientRect();
            tooltip.style.top = (rect.bottom + 10) + 'px';
            tooltip.style.left = (rect.left + rect.width/2 - tooltip.offsetWidth/2) + 'px';
            
            // Mostrar el tooltip
            tooltip.style.opacity = '1';
        });
        
        // Ocultar tooltip
        link.addEventListener('mouseleave', function() {
            tooltip.style.opacity = '0';
        });
        
        // Mostrar diálogo al hacer clic
        link.addEventListener('click', function() {
            dialog.classList.add('active');
            
            // Obtener la URL de inicio de sesión
            const loginUrl = this.getAttribute('data-login-url');
            
            // Configurar el botón de inicio de sesión
            document.getElementById('login-btn').onclick = function() {
                window.location.href = loginUrl;
            };
            
            // Configurar el botón de cancelar
            document.getElementById('cancel-btn').onclick = function() {
                dialog.classList.remove('active');
            };
        });
    });
    
    // Cerrar el diálogo haciendo clic fuera de él
    dialog.addEventListener('click', function(e) {
        if (e.target === dialog) {
            dialog.classList.remove('active');
        }
    });
});