// Función para actualizar cantidad
function updateQuantity(lineaId, change) {
    const input = document.querySelector(`input[data-linea="${lineaId}"]`);
    let newQuantity = parseInt(input.value) + change;
    
    if (newQuantity < 1) newQuantity = 1;
    if (newQuantity > 10) newQuantity = 10;
    
    input.value = newQuantity;
    
    // Enviar actualización al servidor
    fetch('/carrito/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            linea_id: lineaId,
            cantidad: newQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar el total
            updateCartTotal(data.total);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Función para eliminar item - ESTA ES LA FUNCIÓN QUE NECESITA SER LLAMADA
// Agregamos alias para que ambos nombres de función funcionen
function removeItem(lineaId) {
    removeFromCart(lineaId);
}

// Función para eliminar item del carrito - ESTA ES LA FUNCIÓN LLAMADA EN EL HTML
function removeFromCart(lineaId) {
    if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
        fetch(`/carrito/remove/${lineaId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Eliminar el elemento del DOM
                const item = document.querySelector(`[data-linea-id="${lineaId}"]`);
                if (item) {
                    item.remove();
                }
                
                // Actualizar el total
                updateCartTotal(data.total);
                
                // Si el carrito está vacío, actualizar la vista
                if (data.cartItemsCount === 0) {
                    const cartItems = document.querySelector('.cart-items');
                    if (cartItems) {
                        cartItems.innerHTML = `
                            <h2>Carrito de Compra</h2>
                            <div class="empty-cart">
                                <p>Tu carrito está vacío</p>
                                <a href="/productos" class="btn-primary">Ver productos</a>
                            </div>
                        `;
                    }
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Función para actualizar el total del carrito con animación
function updateCartTotal(total) {
    const totalElement = document.getElementById('cart-total');
    if (totalElement) {
        // Obtener el valor anterior
        const oldValue = totalElement.textContent;
        const newValue = '$' + parseFloat(total).toFixed(2);
        
        // Si el valor cambió, aplicar la animación
        if (oldValue !== newValue) {
            totalElement.textContent = newValue;
            
            // Remover la clase si ya existe
            totalElement.classList.remove('price-change');
            
            // Forzar un reflow para que la animación se reinicie
            void totalElement.offsetWidth;
            
            // Agregar la clase para iniciar la animación
            totalElement.classList.add('price-change');
            
            // Remover la clase después de que termine la animación
            setTimeout(() => {
                totalElement.classList.remove('price-change');
            }, 500);
        }
    }
    
    // Actualizar también el resumen de pago si existe
    const paymentSummaryTotal = document.querySelector('.payment-summary .summary-row.total span:last-child');
    if (paymentSummaryTotal) {
        paymentSummaryTotal.textContent = '$' + parseFloat(total).toFixed(2);
    }
}

// Función para actualizar cantidad en el servidor
function updateQuantityOnServer(lineaId, cantidad) {
    fetch('/carrito/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            linea_id: lineaId,
            cantidad: cantidad
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartTotal(data.total);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Función para manejar la navegación entre pasos
function handleStepNavigation() {
    const steps = document.querySelectorAll('.step');
    const stepContents = document.querySelectorAll('.step-content');
    
    // Manejar clics en botones "Next"
    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const nextStep = this.dataset.next;
            
            // Validar el paso actual antes de continuar
            if (validateCurrentStep()) {
                showStep(nextStep);
            }
        });
    });
    
    // Manejar clics en botones "Back"
    document.querySelectorAll('.prev-step').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const prevStep = this.dataset.prev;
            showStep(prevStep);
        });
    });
    
    // Función para mostrar un paso específico
    function showStep(stepName) {
        // Ocultar todos los pasos
        steps.forEach(step => step.classList.remove('active'));
        stepContents.forEach(content => content.classList.remove('active'));
        
        // Mostrar el paso seleccionado
        const targetStep = document.querySelector(`.step[data-step="${stepName}"]`);
        const targetContent = document.getElementById(`${stepName}-step`);
        
        if (targetStep && targetContent) {
            targetStep.classList.add('active');
            targetContent.classList.add('active');
        }
    }
    
    function validateCurrentStep() {
        const activeContent = document.querySelector('.step-content.active');
        const activeId = activeContent ? activeContent.id : '';
        
        switch(activeId) {
            case 'cart-step':
                const cartItems = document.querySelectorAll('.cart-item');
                if (cartItems.length === 0) {
                    alert('Tu carrito está vacío');
                    return false;
                }
                return true;
                
            case 'details-step':
                const form = document.querySelector('.shipping-form');
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return false;
                }
                // Guardar los datos de envío al validar
                saveShippingDetails();
                return true;
                
            case 'payment-step':
                return true;
                
            default:
                return true;
        }
    }
}

// Event listeners cuando carga el documento
document.addEventListener('DOMContentLoaded', function() {
    // Manejar cambios directos en los inputs de cantidad
    document.querySelectorAll('.item-quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const lineaId = this.dataset.linea;
            const cantidad = parseInt(this.value);
            
            if (cantidad < 1) this.value = 1;
            if (cantidad > 10) this.value = 10;
            
            updateQuantityOnServer(lineaId, this.value);
        });
    });
    
    // Inicializar navegación entre pasos
    handleStepNavigation();
    
    // Manejar el formulario de pago
    const paymentForm = document.querySelector('.payment-form');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            if (!validatePaymentForm()) {
                e.preventDefault();
            }
        });
    }
});

function saveShippingDetails() {
    const form = document.querySelector('.shipping-form');
    const formData = new FormData(form);
    
    fetch('/user/update-shipping', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Datos de envío actualizados');
        }
    })
    .catch(error => console.error('Error:', error));
}

// Función para validar el formulario de pago
function validatePaymentForm() {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
    
    if (!paymentMethod) {
        alert('Por favor selecciona un método de pago');
        return false;
    }
    
    if (paymentMethod.value === 'tarjeta') {
        const cardNumber = document.getElementById('card-number').value;
        const expiry = document.getElementById('expiry').value;
        const cvv = document.getElementById('cvv').value;
        
        if (!cardNumber || !expiry || !cvv) {
            alert('Por favor completa todos los campos de la tarjeta');
            return false;
        }
    }
    
    return true;
}