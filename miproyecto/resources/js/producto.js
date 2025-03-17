document.addEventListener('DOMContentLoaded', function() {
    // Base de datos de productos (simulada)
    const products = [
        {
            id: 0,
            name: "Hamburguesa especial",
            price: 18.00,
            description: "Carne de vacuno con tomate, lechuga, queso y salsa de bacon",
            image: "hamburguesa.jpg",
            rating: 5,
            reviews: 4
        },
        {
            id: 1,
            name: "Hamburguesa Clásica",
            price: 7.00,
            description: "Carne de ternera, lechuga, tomate y queso americano",
            image: "hamburguesa-clasica.jpg",
            rating: 3,
            reviews: 8
        },
        {
            id: 2,
            name: "Hamburguesa Deluxe",
            price: 9.00,
            description: "Doble carne, queso, cebolla caramelizada y salsa especial",
            image: "hamburguesa-deluxe.jpg",
            rating: 4,
            reviews: 6
        },
        {
            id: 3,
            name: "Hamburguesa Vegetariana",
            price: 8.00,
            description: "Hamburguesa de verduras, aguacate, tomate y rúcula",
            image: "hamburguesa-vegetariana.jpg",
            rating: 4,
            reviews: 3
        },
        {
            id: 4,
            name: "Hamburguesa de Pollo",
            price: 7.50,
            description: "Pechuga de pollo empanada, lechuga y mayonesa",
            image: "hamburguesa-pollo.jpg",
            rating: 3,
            reviews: 5
        },
        {
            id: 5,
            name: "Hamburguesa Doble",
            price: 9.00,
            description: "Doble carne, doble queso, bacon y salsa BBQ",
            image: "hamburguesa-doble.jpg",
            rating: 5,
            reviews: 7
        }
    ];
    
    // Referencias a elementos del DOM
    const mainProductImage = document.getElementById('main-product-image');
    const productTitle = document.getElementById('product-title');
    const productPrice = document.getElementById('product-price');
    const productDescription = document.getElementById('product-description');
    const productCards = document.querySelectorAll('.product-card');
    
    // Función para actualizar la información del producto principal
    function updateProductDetails(productId) {
        const product = products[productId];
        
        if (product) {
            // Actualizar imagen
            mainProductImage.src = product.image;
            mainProductImage.alt = product.name;
            
            // Actualizar título
            productTitle.textContent = product.name;
            
            // Actualizar precio
            productPrice.textContent = `$ ${product.price.toFixed(2)}`;
            
            // Actualizar descripción
            productDescription.innerHTML = `<p>${product.description}</p>`;
            
            // Actualizar estrellas de valoración (opcional)
            updateRatingStars(product.rating);
            
            // Actualizar contador de reviews (opcional)
            document.querySelector('.reviews-count').textContent = `${product.reviews} reviews`;
            
            // Actualizar URL (opcional, para permitir compartir enlaces directos)
            updateURL(product.id, product.name);
        }
    }
    
    // Función para actualizar las estrellas de valoración
    function updateRatingStars(rating) {
        const stars = document.querySelectorAll('.stars .star');
        
        // Restablecer todas las estrellas
        stars.forEach((star, index) => {
            if (index < rating) {
                star.style.color = '#ff8c38'; // Estrellas llenas
            } else {
                star.style.color = '#ddd'; // Estrellas vacías
            }
        });
    }
    
    // Función para actualizar la URL para compartir
    function updateURL(productId, productName) {
        // Crear un slug a partir del nombre del producto
        const slug = productName.toLowerCase().replace(/ /g, '-');
        
        // Actualizar la URL sin recargar la página
        if (history.pushState) {
            const newUrl = `${window.location.protocol}//${window.location.host}${window.location.pathname}?product=${productId}&name=${slug}`;
            window.history.pushState({ path: newUrl }, '', newUrl);
        }
    }
    
    // Evento para los productos similares
    productCards.forEach(card => {
        card.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            updateProductDetails(productId);
            
            // Añadir efecto visual (opcional)
            highlightSelectedProduct(this);
            
            // Hacer scroll hasta arriba (opcional)
            scrollToProductDetail();
        });
    });
    
    // Función para destacar visualmente el producto seleccionado
    function highlightSelectedProduct(selectedCard) {
        // Quitar highlight de todos los productos
        productCards.forEach(card => {
            card.style.borderColor = '#ddd';
        });
        
        // Añadir highlight al producto seleccionado
        selectedCard.style.borderColor = '#ff8c38';
        selectedCard.style.borderWidth = '2px';
    }
    
    // Función para hacer scroll hasta los detalles del producto
    function scrollToProductDetail() {
        const productDetailSection = document.querySelector('.product-detail');
        
        if (productDetailSection) {
            productDetailSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
    
    // Funcionalidad para el botón de añadir a favoritos
    const wishlistButton = document.querySelector('.btn-wishlist');
    wishlistButton.addEventListener('click', function() {
        const heartIcon = this.querySelector('.heart-icon');
        
        // Alternar entre corazón vacío y lleno
        if (heartIcon.textContent === '♡') {
            heartIcon.textContent = '♥';
            heartIcon.style.color = '#ff6b6b';
        } else {
            heartIcon.textContent = '♡';
            heartIcon.style.color = '#666';
        }
    });
    
    // Funcionalidad para añadir al carrito
    const addToCartButton = document.querySelector('.add-to-cart');
    addToCartButton.addEventListener('click', function() {
        // Obtener el ID del producto actual
        const urlParams = new URLSearchParams(window.location.search);
        const productId = urlParams.get('product') || 0;
        
        // Simulación de añadir al carrito
        console.log(`Producto ${products[productId].name} añadido al carrito`);
        
        // Feedback visual
        this.textContent = 'Added to Cart';
        setTimeout(() => {
            this.textContent = 'Add to Cart';
        }, 2000);
    });
    
    // Inicializar la página con el producto por defecto o desde la URL
    function initProductPage() {
        const urlParams = new URLSearchParams(window.location.search);
        const productId = urlParams.get('product') || 0;
        
        // Cargar el producto inicial
        updateProductDetails(productId);
        
        // Destacar el producto en la sección "Similar Products" (opcional)
        productCards.forEach(card => {
            if (card.getAttribute('data-product-id') === productId) {
                highlightSelectedProduct(card);
            }
        });
        
        // Crear placeholders para imágenes si no están disponibles
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            img.addEventListener('error', function() {
                this.src = 'data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22286%22%20height%3D%22180%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20286%20180%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_1759e369f07%20text%20%7B%20fill%3A%23999%3Bfont-weight%3Anormal%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A14pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_1759e369f07%22%3E%3Crect%20width%3D%22286%22%20height%3D%22180%22%20fill%3D%22%23f8f8f8%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2296.828125%22%20y%3D%2296.3%22%3EImagen%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E';
            });
        });
    }
    
    // Inicializar la página
    initProductPage();
});