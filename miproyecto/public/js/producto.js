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

// Manejar el formulario de añadir al carrito via AJAX
document.addEventListener('DOMContentLoaded', () => {
    const addToCartForm = document.querySelector('.add-to-cart');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch('/producto/add-to-cart', {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Producto añadido al carrito');
                    // Opcional: actualizar contador de carrito
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('No se pudo añadir el producto al carrito');
            });
        });
    }
});