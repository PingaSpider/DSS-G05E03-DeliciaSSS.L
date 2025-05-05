// Función para manejar el toggle de wishlist
function toggleWishlist(productoId) {
    fetch(`/producto/toggle-wishlist/${productoId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        const heartIcon = document.querySelector('.heart-icon');
        if (data.inWishlist) {
            // Producto añadido a favoritos
            heartIcon.textContent = '♥';
            heartIcon.style.color = '#ff8c38';
        } else {
            // Producto quitado de favoritos
            heartIcon.textContent = '♡';
            heartIcon.style.color = '#666';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('No se pudo actualizar favoritos');
    });
}

// Función para mostrar notificaciones
function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 2000);
}

// Función para manejar el toggle del carrito
document.addEventListener('DOMContentLoaded', function() {
    const cartButton = document.getElementById('cart');
    if (cartButton) {
        cartButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = 'carrito';
        });
    }
});





document.addEventListener('DOMContentLoaded', function() {
    // Manejar el formulario de añadir al carrito via AJAX (para la página de detalle)
    const addToCartForm = document.querySelector('.add-to-cart-form');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch('/carrito/add', {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Producto añadido al carrito');
                    // Actualizar contador de carrito si existe
                    const cartCounter = document.querySelector('.cart-counter');
                    if (cartCounter) {
                        cartCounter.textContent = data.cartItemsCount;
                    }
                } else {
                    throw new Error(data.message || 'Error al añadir al carrito');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('No se pudo añadir el producto al carrito');
            });
        });
    }

    // Prevenir que los clicks en los controles naveguen al producto
    document.querySelectorAll('.quantity-btn, .add-to-cart-btn, .quantity-input').forEach(element => {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
    });

    // Manejar cambios en la cantidad
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = this.dataset.product;
            const input = document.querySelector(`input[data-product="${productId}"]`);
            let value = parseInt(input.value);
            
            if (this.classList.contains('plus') && value < 10) {
                value++;
            } else if (this.classList.contains('minus') && value > 1) {
                value--;
            }
            
            input.value = value;
        });
    });
    
    // Manejar el input directo
    document.querySelectorAll('.quantity-input').forEach(input => {
        // Prevenir navegación al hacer click
        input.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
        
        // Manejar cambios en el valor
        input.addEventListener('change', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            let value = parseInt(this.value);
            if (isNaN(value) || value < 1) this.value = 1;
            if (value > 10) this.value = 10;
        });
        
        // Prevenir navegación al hacer focus
        input.addEventListener('focus', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
        
        // Manejar entrada por teclado
        input.addEventListener('keydown', function(e) {
            e.stopPropagation();
            
            // Permitir solo números, backspace, delete, tab, escape, enter
            if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
                // Permitir Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true) ||
                // Permitir teclas de navegación
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                return;
            }
            
            // Asegurarse de que es un número
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && 
                (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    });
    
    // Manejar añadir al carrito
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = this.dataset.product;
            const quantityInput = document.querySelector(`input[data-product="${productId}"]`);
            const quantity = quantityInput ? quantityInput.value : 1;
            
            // Deshabilitar el botón y mostrar loading
            this.disabled = true;
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            // Hacer la petición AJAX
            fetch('/carrito/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    producto_id: productId,
                    cantidad: parseInt(quantity)
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Mostrar check
                    this.innerHTML = '<i class="fas fa-check"></i>';
                    
                    // Volver al ícono del carrito después de 1 segundo
                    setTimeout(() => {
                        this.disabled = false;
                        this.innerHTML = '<i class="fas fa-shopping-cart"></i>';
                    }, 1000);
                    
                    // Actualizar contador del carrito si existe
                    const cartCounter = document.querySelector('.cart-counter');
                    if (cartCounter) {
                        cartCounter.textContent = data.cartItemsCount;
                    }
                    
                    // Mostrar notificación
                    showNotification(data.message || 'Producto añadido al carrito');
                } else {
                    throw new Error(data.message || 'Error al añadir al carrito');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Restaurar el botón
                this.disabled = false;
                this.innerHTML = originalContent;
                alert('Error: ' + error.message);
            });
        });
    });

    // Prevenir que los enlaces de productos se activen cuando se hace click en los controles
    document.querySelectorAll('.product-card').forEach(card => {
        const link = card.querySelector('a');
        if (link) {
            const controls = card.querySelectorAll('.quantity-btn, .add-to-cart-btn, .quantity-input');
            controls.forEach(control => {
                control.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });
        }
    });

       // Manejo de categorías
       document.querySelectorAll('.category-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            // Remover clase active de todos los tabs
            document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.category-section').forEach(s => s.classList.remove('active'));
            
            // Añadir clase active al tab seleccionado
            this.classList.add('active');
            const categoryId = this.dataset.category;
            document.getElementById(categoryId).classList.add('active');
        });
    });

    // Búsqueda en tiempo real
    document.getElementById('product-search').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        document.querySelectorAll('.product-card').forEach(card => {
            const productName = card.querySelector('.product-name').textContent.toLowerCase();
            if (productName.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Quick add to cart
    document.querySelectorAll('.quick-add-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = this.dataset.productId;
            // Implementar lógica de añadir al carrito
            console.log('Añadiendo producto:', productId);
        });
    });
});