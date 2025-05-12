/**
 * Script para la funcionalidad del menú
 * Maneja la selección de categorías, modal de menús semanales y carrito
 */

document.addEventListener('DOMContentLoaded', function() {
    // Configurar CSRF token para solicitudes AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Manejo de categorías de menú
    const menuCategories = document.querySelectorAll('.menu-category');
    const menuSections = document.querySelectorAll('.menu-section');
    
    menuCategories.forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            
            // Solo cambiar sección si no es el botón del modal
            if (target !== 'menuDelDia' || !this.hasAttribute('onclick')) {
                // Remover clase active de todos
                menuCategories.forEach(btn => btn.classList.remove('active'));
                menuSections.forEach(section => section.classList.remove('active'));
                
                // Añadir clase active al seleccionado
                this.classList.add('active');
                const targetSection = document.getElementById(target);
                if (targetSection) {
                    targetSection.classList.add('active');
                }
            }
        });
    });
    
    // Función para mostrar notificaciones
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 80px;
            right: 20px;
            padding: 15px 25px;
            background-color: ${type === 'success' ? '#4CAF50' : '#f44336'};
            color: white;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 10000;
            transition: all 0.3s ease;
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    // Función para añadir al carrito
    function addMenuToCart(menuId) {
        // Verificar autenticación primero
        if (!window.isAuthenticated) {
            showNotification('Debes iniciar sesión para añadir productos al carrito', 'error');
            setTimeout(() => {
                window.location.href = '/login';
            }, 2000);
            return;
        }
        
        if (!csrfToken) {
            console.error('CSRF token no encontrado');
            showNotification('Error de seguridad. Por favor, recarga la página.', 'error');
            return;
        }
        
        // Buscar el botón correcto dentro del menú activo
        const activeSection = document.querySelector('.menu-section.active');
        const addButton = activeSection?.querySelector('.add-to-cart-btn');
        const originalContent = addButton?.innerHTML;
        
        if (addButton) {
            // Deshabilitar el botón
            addButton.disabled = true;
            addButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Añadiendo...';
        }
        
        fetch('/carrito/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                producto_id: menuId,
                cantidad: 1
            })
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 401) {
                    showNotification('Tu sesión ha expirado. Por favor, inicia sesión de nuevo.', 'error');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                    return;
                }
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                showNotification(data.message || 'Menú añadido al carrito');
                
                // Actualizar contador del carrito si existe
                const cartCounter = document.querySelector('.cart-counter');
                if (cartCounter && data.cartItemsCount) {
                    cartCounter.textContent = data.cartItemsCount;
                }
                
                if (addButton) {
                    // Mostrar check
                    addButton.innerHTML = '<i class="fas fa-check"></i> Añadido';
                    
                    // Volver al estado original después de 2 segundos
                    setTimeout(() => {
                        addButton.disabled = false;
                        addButton.innerHTML = originalContent || 'Añadir al carrito';
                    }, 2000);
                }
            } else if (data) {
                throw new Error(data.message || 'Error al añadir al carrito');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification(error.message || 'Error al añadir al carrito', 'error');
            
            // Restaurar el botón
            if (addButton) {
                addButton.disabled = false;
                addButton.innerHTML = originalContent || 'Añadir al carrito';
            }
        });
    }
    
    // Función para pedir ahora
    function orderMenuNow(menuId) {
        // Verificar autenticación primero
        if (!window.isAuthenticated) {
            showNotification('Debes iniciar sesión para realizar pedidos', 'error');
            setTimeout(() => {
                window.location.href = '/login';
            }, 2000);
            return;
        }
        
        if (!csrfToken) {
            console.error('CSRF token no encontrado');
            showNotification('Error de seguridad. Por favor, recarga la página.', 'error');
            return;
        }
        
        // Buscar el botón correcto dentro del menú activo
        const activeSection = document.querySelector('.menu-section.active');
        const orderButton = activeSection?.querySelector('.order-now-btn');
        const originalContent = orderButton?.innerHTML;
        
        if (orderButton) {
            // Deshabilitar el botón
            orderButton.disabled = true;
            orderButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
        }
        
        // Primero añadir al carrito
        fetch('/carrito/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                producto_id: menuId,
                cantidad: 1
            })
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 401) {
                    showNotification('Tu sesión ha expirado. Por favor, inicia sesión de nuevo.', 'error');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                    return;
                }
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                // Redirigir al carrito
                window.location.href = '/carrito';
            } else if (data) {
                throw new Error(data.message || 'Error al procesar el pedido');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification(error.message || 'Error al procesar el pedido', 'error');
            
            // Restaurar el botón
            if (orderButton) {
                orderButton.disabled = false;
                orderButton.innerHTML = originalContent || 'Pedir ahora';
            }
        });
    }
    
    // Asignar eventos a los botones
    document.addEventListener('click', function(e) {
        // Buscar el botón de añadir al carrito
        if (e.target.closest('.add-to-cart-btn')) {
            e.preventDefault();
            // Obtener el código del menú del día actual
            const menuDelDia = window.menuDelDiaCod || 'M0001';
            addMenuToCart(menuDelDia);
        }
        
        // Buscar el botón de pedir ahora
        if (e.target.closest('.order-now-btn')) {
            e.preventDefault();
            // Obtener el código del menú del día actual
            const menuDelDia = window.menuDelDiaCod || 'M0001';
            orderMenuNow(menuDelDia);
        }
    });
});

// Funciones globales para el modal
function openMenuModal() {
    const modal = document.getElementById('menuSemanaModal');
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function closeMenuModal() {
    const modal = document.getElementById('menuSemanaModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Cerrar modal al hacer clic fuera de él
window.addEventListener('click', function(event) {
    const modal = document.getElementById('menuSemanaModal');
    if (event.target === modal) {
        closeMenuModal();
    }
});

// Cerrar modal con tecla ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeMenuModal();
    }
});