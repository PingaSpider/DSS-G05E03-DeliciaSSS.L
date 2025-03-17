document.addEventListener('DOMContentLoaded', function() {
    // Navegación entre pestañas del checkout
    const steps = document.querySelectorAll('.step');
    const stepContents = document.querySelectorAll('.step-content');
    const nextButtons = document.querySelectorAll('.next-step');
    const prevButtons = document.querySelectorAll('.prev-step');
    
    // Función para cambiar de pestaña
    function changeStep(currentStepId, nextStepId) {
        // Ocultar todos los contenidos de pasos
        stepContents.forEach(content => {
            content.classList.remove('active');
        });
        
        // Quitar clase activa de todos los pasos
        steps.forEach(step => {
            step.classList.remove('active');
        });
        
        // Activar el paso actual
        document.getElementById(nextStepId + '-step').classList.add('active');
        
        // Actualizar el indicador de pasos
        steps.forEach(step => {
            if (step.getAttribute('data-step') === nextStepId) {
                step.classList.add('active');
            }
        });
    }
    
    // Event listeners para botones "Next"
    nextButtons.forEach(button => {
        button.addEventListener('click', function() {
            const nextStep = this.getAttribute('data-next');
            const currentStep = this.closest('.step-content').id.replace('-step', '');
            changeStep(currentStep, nextStep);
        });
    });
    
    // Event listeners para botones "Back"
    prevButtons.forEach(button => {
        button.addEventListener('click', function() {
            const prevStep = this.getAttribute('data-prev');
            const currentStep = this.closest('.step-content').id.replace('-step', '');
            changeStep(currentStep, prevStep);
        });
    });
    
    // Event listeners para los pasos en la barra de progreso
    steps.forEach(step => {
        step.addEventListener('click', function() {
            const targetStep = this.getAttribute('data-step');
            const activeStep = document.querySelector('.step.active').getAttribute('data-step');
            
            // Solo permitir navegación a pasos anteriores o al actual
            const stepOrder = ['cart', 'details', 'payment'];
            const targetIndex = stepOrder.indexOf(targetStep);
            const activeIndex = stepOrder.indexOf(activeStep);
            
            if (targetIndex <= activeIndex) {
                changeStep(activeStep, targetStep);
            }
        });
    });
    
    // Manejo de los selectores de cantidad
    const quantityInputs = document.querySelectorAll('.quantity-selector input');
    const quantityUpButtons = document.querySelectorAll('.quantity-up');
    const quantityDownButtons = document.querySelectorAll('.quantity-down');
    
    // Aumentar cantidad
    quantityUpButtons.forEach((button, index) => {
        button.addEventListener('click', function() {
            const input = quantityInputs[index];
            let value = parseInt(input.value);
            if (value < parseInt(input.max)) {
                input.value = value + 1;
                updateTotals();
            }
        });
    });
    
    // Disminuir cantidad
    quantityDownButtons.forEach((button, index) => {
        button.addEventListener('click', function() {
            const input = quantityInputs[index];
            let value = parseInt(input.value);
            if (value > parseInt(input.min)) {
                input.value = value - 1;
                updateTotals();
            }
        });
    });
    
    // Actualizar en cambio directo del input
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            let value = parseInt(this.value);
            
            if (isNaN(value) || value < parseInt(this.min)) {
                this.value = this.min;
            } else if (value > parseInt(this.max)) {
                this.value = this.max;
            }
            
            updateTotals();
        });
    });
    
    // Función para actualizar totales
    function updateTotals() {
        // Obtener todos los productos
        const cartItems = document.querySelectorAll('.cart-item');
        let subtotal = 0;
        
        cartItems.forEach(item => {
            const priceText = item.querySelector('.item-price').textContent;
            const price = parseFloat(priceText.replace('$', ''));
            const quantity = parseInt(item.querySelector('.quantity-selector input').value);
            subtotal += price * quantity;
        });
        
        // Actualizar todos los lugares donde se muestra el total
        const totalElements = document.querySelectorAll('.total-amount');
        totalElements.forEach(element => {
            element.textContent = '$' + subtotal.toFixed(2);
        });
        
        // Actualizar resumen en paso de pago
        const summarySubtotal = document.querySelector('.payment-summary .summary-row:first-child span:last-child');
        if (summarySubtotal) {
            summarySubtotal.textContent = '$' + subtotal.toFixed(2);
        }
        
        const summaryTotal = document.querySelector('.payment-summary .total span:last-child');
        if (summaryTotal) {
            summaryTotal.textContent = '$' + subtotal.toFixed(2);
        }
    }
});