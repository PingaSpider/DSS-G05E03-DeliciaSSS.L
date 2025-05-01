/**
 * JavaScript para la funcionalidad del perfil de usuario
 */
document.addEventListener('DOMContentLoaded', function() {
    // Activar pestañas
    initTabs();

    // Validación de formularios
    initFormValidation();
    
    // Ocultar alertas después de un tiempo
    autoHideAlerts();
});

/**
 * Inicializa el sistema de pestañas
 */
function initTabs() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    // Obtener la pestaña activa de la URL si existe
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab');
    
    if (activeTab) {
        // Desactivar todas las pestañas
        tabButtons.forEach(btn => btn.classList.remove('active'));
        tabPanes.forEach(pane => pane.classList.remove('active'));
        
        // Intentar activar la pestaña de la URL
        const tabButton = document.querySelector(`.tab-button[data-tab="${activeTab}"]`);
        if (tabButton) {
            tabButton.classList.add('active');
            document.getElementById(activeTab).classList.add('active');
        } else {
            // Si no existe, activar la primera
            tabButtons[0].classList.add('active');
            tabPanes[0].classList.add('active');
        }
    }
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Desactivar todas las pestañas
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));
            
            // Activar la pestaña seleccionada
            button.classList.add('active');
            document.getElementById(button.dataset.tab).classList.add('active');
            
            // Actualizar la URL sin recargar la página
            const url = new URL(window.location);
            url.searchParams.set('tab', button.dataset.tab);
            window.history.pushState({}, '', url);
        });
    });
}


/**
 * Reinicia el formulario de perfil
 */
function resetForm() {
    const form = document.querySelector('.user-form');
    if (form) {
        form.reset();
    }
}



/**
 * Inicializa la validación de formularios
 */
function initFormValidation() {
    // Validación de correo electrónico
    const emailInput = document.querySelector('input[type="email"]');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            if (this.value && !isValidEmail(this.value)) {
                if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('error-message')) {
                    const errorMsg = document.createElement('span');
                    errorMsg.classList.add('error-message');
                    errorMsg.textContent = 'Por favor, introduce un email válido';
                    this.parentNode.insertBefore(errorMsg, this.nextSibling);
                }
            } else if (this.nextElementSibling && this.nextElementSibling.classList.contains('error-message')) {
                this.nextElementSibling.remove();
            }
        });
    }
    
    // Validación de teléfono
    const phoneInput = document.querySelector('input[type="tel"]');
    if (phoneInput) {
        phoneInput.addEventListener('blur', function() {
            if (this.value && !isValidPhone(this.value)) {
                if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('error-message')) {
                    const errorMsg = document.createElement('span');
                    errorMsg.classList.add('error-message');
                    errorMsg.textContent = 'Por favor, introduce un número de teléfono válido';
                    this.parentNode.insertBefore(errorMsg, this.nextSibling);
                }
            } else if (this.nextElementSibling && this.nextElementSibling.classList.contains('error-message')) {
                this.nextElementSibling.remove();
            }
        });
    }
    
}

/**
 * Valida un correo electrónico
 */
function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * Valida un número de teléfono
 */
function isValidPhone(phone) {
    // Acepta formato internacional y nacional (ejemplo: +34612345678 o 612345678)
    const regex = /^(\+\d{1,3})?\d{9,}$/;
    return regex.test(phone.replace(/\s+/g, ''));
}

/**
 * Oculta automáticamente las alertas después de un tiempo
 */
function autoHideAlerts() {
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        setTimeout(function() {
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 500);
            });
        }, 5000);
    }
}

/**
 * Filtra los pedidos por fecha
 */
function filterOrders() {
    const filterForm = document.getElementById('filter-form');
    if (filterForm) {
        filterForm.submit();
    }
}

/**
 * Repetir un pedido anterior
 */
function repeatOrder(orderId) {
    const form = document.querySelector(`form[action*="${orderId}/repetir"]`);
    if (form) {
        if (confirm('¿Estás seguro de que quieres repetir este pedido?')) {
            form.submit();
        }
    }
}

/**
 * Confirma antes de cancelar un pedido
 */
function cancelOrder(orderId) {
    const form = document.querySelector(`form[action*="${orderId}/cancelar"]`);
    if (form) {
        if (confirm('¿Estás seguro de que quieres cancelar este pedido? Esta acción no se puede deshacer.')) {
            form.submit();
        }
    }
    return false;
}

/**
 * Confirma antes de eliminar una dirección
 */
function confirmDeleteAddress(addressId) {
    return confirm('¿Estás seguro de que quieres eliminar esta dirección?');
}