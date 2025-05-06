document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar elementos del DOM
    const searchBar = document.querySelector('.search-bar input');
    if (!searchBar) return; // Si no existe el elemento, salir
    
    const searchBarContainer = document.querySelector('.search-bar');

    // Crear el contenedor de resultados de búsqueda si no existe
    let searchResults = searchBarContainer.querySelector('.search-results');
    if (!searchResults) {
        searchResults = document.createElement('div');
        searchResults.className = 'search-results';
        searchBarContainer.appendChild(searchResults);
    }
    
    // Añadir estilos al contenedor de resultados
    searchResults.style.display = 'none';
    searchResults.style.position = 'absolute';
    searchResults.style.top = '100%';
    searchResults.style.left = '0';
    searchResults.style.width = '100%';
    searchResults.style.maxHeight = '300px';
    searchResults.style.overflowY = 'auto';
    searchResults.style.backgroundColor = '#fff';
    searchResults.style.border = '1px solid #ddd';
    searchResults.style.borderRadius = '0 0 4px 4px';
    searchResults.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
    searchResults.style.zIndex = '1000';
    
    // Añadir estilos CSS para los resultados de búsqueda
    const style = document.createElement('style');
    style.textContent = `
        .search-bar {
            position: relative;
        }
        .search-results {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 0 0 4px 4px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .search-product {
            display: flex;
            padding: 10px;
            border-bottom: 1px solid #eee;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        .search-product:last-child {
            border-bottom: none;
        }
        .search-product:hover {
            background-color: #f9f9f9;
        }
        .search-product-image {
            width: 50px;
            height: 50px;
            margin-right: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .search-product-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .search-product-image img:hover {
            transform: scale(1.05);
        }
        .search-product-info {
            flex: 1;
        }
        .search-product-info h4 {
            margin: 0 0 5px 0;
            font-size: 0.9rem;
            cursor: pointer;
        }
        .search-product-info h4:hover {
            color: #F39221;
        }
        .search-product-info p {
            margin: 0 0 5px 0;
            font-size: 0.8rem;
            color: #666;
        }
        .search-product-price {
            font-weight: bold;
            color: #F39221;
        }
        .btn-add-to-cart {
            background-color: #F39221;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px;
            cursor: pointer;
            margin-left: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            font-size: 16px;
            transition: background-color 0.2s ease;
        }
        .btn-add-to-cart:hover {
            background-color: #e67d2e;
        }
        .no-results, .search-loading {
            padding: 15px;
            text-align: center;
            color: #666;
        }
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            border-radius: 4px;
            z-index: 9999;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: opacity 0.3s ease;
        }
        .notification.success {
            background-color: #4CAF50;
            color: white;
        }
        .notification.error {
            background-color: #f44336;
            color: white;
        }
    `;
    document.head.appendChild(style);
    
    // Evento de búsqueda en tiempo real con debounce para evitar demasiadas peticiones
    let debounceTimer;
    searchBar.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        clearTimeout(debounceTimer);
        
        if (searchTerm.length < 2) {
            searchResults.style.display = 'none';
            return;
        }
        
        // Mostrar mensaje de carga
        searchResults.innerHTML = '<div class="search-loading">Buscando productos...</div>';
        searchResults.style.display = 'block';
        
        // Debounce para evitar muchas peticiones
        debounceTimer = setTimeout(() => {
            // Hacer una petición AJAX para buscar productos
            fetch(`/api/productos/buscar?q=${encodeURIComponent(searchTerm)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error en la petición: ${response.status} ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    // Limpiar resultados anteriores
                    searchResults.innerHTML = '';
                    
                    console.log('Productos encontrados:', data); // Log para depuración
                    
                    if (!data || data.length === 0 || (data.error && data.error === true)) {
                        searchResults.innerHTML = '<div class="no-results">No se encontraron productos</div>';
                        return;
                    }
                    
                    // Mostrar resultados de búsqueda
                    data.forEach(producto => {
                        const productoElement = document.createElement('div');
                        productoElement.className = 'search-product';
                        
                        // Verificar si tenemos una URL de imagen
                        const imagenUrl = producto.imagen_url || '/assets/images/comida/no-encontrado.png';
                        const precio = parseFloat(producto.precio || producto.pvp).toFixed(2);
                        
                        productoElement.innerHTML = `
                            <div class="search-product-image">
                                <img src="${imagenUrl}" alt="${producto.nombre}" onerror="this.src='/assets/images/comida/no-encontrado.png'">
                            </div>
                            <div class="search-product-info">
                                <h4>${producto.nombre}</h4>
                                <p>${producto.descripcion || ''}</p>
                                <span class="search-product-price">$${precio}</span>
                            </div>
                            <button class="btn-add-to-cart" data-producto-id="${producto.cod}">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        `;
                        
                        searchResults.appendChild(productoElement);
                        
                        // Añadir evento para hacer clic en la imagen o título y llevarte a la página del producto
                        const productImage = productoElement.querySelector('.search-product-image');
                        const productTitle = productoElement.querySelector('.search-product-info h4');
                        
                        const navigateToProduct = function() {
                            window.location.href = `/producto/${producto.cod}`;
                        };
                        
                        productImage.addEventListener('click', navigateToProduct);
                        productTitle.addEventListener('click', navigateToProduct);
                        
                        // Añadir evento para agregar al carrito
                        const addButton = productoElement.querySelector('.btn-add-to-cart');
                        addButton.addEventListener('click', function(e) {
                            e.stopPropagation(); // Evitar que se cierre el menú
                            const productoId = this.getAttribute('data-producto-id');
                            addProductToCart(productoId);
                        });
                    });
                    
                    // Mostrar el contenedor de resultados
                    searchResults.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error al buscar productos:', error);
                    searchResults.innerHTML = `<div class="no-results">Error al buscar productos: ${error.message}</div>`;
                });
        }, 300); // Esperar 300ms después de que el usuario termine de escribir
    });
    
    // Función para agregar producto al carrito
    function addProductToCart(productoId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('/carrito/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                producto_id: productoId,
                cantidad: 1
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error en la petición: ${response.status} ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Mostrar notificación de éxito
                showNotification('Producto añadido al carrito', 'success');
                
                // Si estamos en la página de carrito, recargar para ver el nuevo producto
                if (window.location.pathname.includes('/carrito')) {
                    window.location.reload();
                }
            } else {
                showNotification(data.message || 'Error al añadir el producto', 'error');
            }
        })
        .catch(error => {
            console.error('Error al añadir producto:', error);
            showNotification('Error al añadir el producto al carrito', 'error');
        });
    }
    
    // Función para mostrar notificaciones
    function showNotification(message, type) {
        // Eliminar notificaciones anteriores
        const previousNotifications = document.querySelectorAll('.notification');
        previousNotifications.forEach(notification => {
            document.body.removeChild(notification);
        });
        
        // Crear elemento de notificación
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        
        // Añadir al DOM
        document.body.appendChild(notification);
        
        // Aparecer con animación
        setTimeout(() => {
            notification.style.opacity = '1';
        }, 10);
        
        // Desaparecer después de 3 segundos
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
    
    // Cerrar resultados al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!searchBarContainer.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
    
    // Evitar que se cierre al hacer clic dentro del contenedor de resultados
    searchResults.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});