document.addEventListener('DOMContentLoaded', function() {
    // Verificar si el usuario está autenticado
    const isAuthenticated = window.isAuthenticated || false;
    
    // Solo continuar si hay enlaces que requieren autenticación
    const authLinks = document.querySelectorAll('.auth-required');
    
    if (authLinks.length === 0) {
        return; // No hacer nada si no hay enlaces que requieran autenticación
    }
    
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
        // Deshabilitar el enlace si el usuario no está autenticado
        link.addEventListener('click', function(e) {
            if (!isAuthenticated) {
                e.preventDefault();
                dialog.classList.add('active');
                
                // Obtener la URL de inicio de sesión
                const loginUrl = this.getAttribute('data-login-url') || '/login';
                
                // Configurar el botón de inicio de sesión
                document.getElementById('login-btn').onclick = function() {
                    window.location.href = loginUrl;
                };
                
                // Configurar el botón de cancelar
                document.getElementById('cancel-btn').onclick = function() {
                    dialog.classList.remove('active');
                };
            }
        });
        
        // Mostrar tooltip al pasar el mouse
        link.addEventListener('mouseenter', function(e) {
            if (!isAuthenticated) {
                const message = this.getAttribute('data-message') || 'Requiere autenticación';
                tooltip.textContent = message;
                
                // Posicionar el tooltip
                const rect = this.getBoundingClientRect();
                tooltip.style.top = (rect.bottom + 10) + 'px';
                tooltip.style.left = (rect.left + rect.width/2 - tooltip.offsetWidth/2) + 'px';
                
                // Mostrar el tooltip
                tooltip.style.opacity = '1';
            }
        });
        
        // Ocultar tooltip
        link.addEventListener('mouseleave', function() {
            tooltip.style.opacity = '0';
        });
    });
    
    // Cerrar el diálogo haciendo clic fuera de él
    dialog.addEventListener('click', function(e) {
        if (e.target === dialog) {
            dialog.classList.remove('active');
        }
    });
});