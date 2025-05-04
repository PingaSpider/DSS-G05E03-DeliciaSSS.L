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

// Función para eliminar item
function removeItem(lineaId) {
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
                item.remove();
                
                // Actualizar el total
                updateCartTotal(data.total);
                
                // Si el carrito está vacío, recargar la página
                if (data.cartItemsCount === 0) {
                    window.location.reload();
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Función para actualizar el total del carrito
function updateCartTotal(total) {
    const totalElement = document.getElementById('cart-total');
    totalElement.textContent = '$' + parseFloat(total).toFixed(2);
}

// Event listeners cuando carga el documento
document.addEventListener('DOMContentLoaded', function() {
    // Manejar cambios directos en los inputs de cantidad
    document.querySelectorAll('.item-quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const lineaId = this.dataset.linea;
            const cantidad = this.value;
            
            if (cantidad < 1) this.value = 1;
            if (cantidad > 10) this.value = 10;
            
            updateQuantityOnServer(lineaId, this.value);
        });
    });
});

// Función auxiliar para actualizar cantidad en el servidor
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