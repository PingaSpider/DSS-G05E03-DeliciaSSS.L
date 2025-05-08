<!DOCTYPE html>
<html lang="es">
<head>
    <title>Crear Menú</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pedido/create.css') }}">
</head>
<body>
    <div class="container">
        <h1>Nuevo Menú</h1>
        <hr>
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif
        
        <form id="form_menu" action="{{ route('menus.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="nombre"><b>Nombre del Menú</b></label>
                <input type="text" placeholder="Introduce el nombre del menú" name="nombre" id="nombre" value="{{ old('nombre') }}" required>
                @error('nombre')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="pvp"><b>Precio de Venta (€)</b></label>
                <input type="number" step="0.01" min="0" placeholder="Precio de venta" name="pvp" id="pvp" value="{{ old('pvp') }}" required>
                @error('pvp')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="stock"><b>Stock</b></label>
                <input type="number" min="0" placeholder="Cantidad disponible" name="stock" id="stock" value="{{ old('stock', 1) }}" required>
                @error('stock')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="precioCompra"><b>Precio de Compra (€)</b></label>
                <input type="number" step="0.01" min="0" placeholder="Precio de compra" name="precioCompra" id="precioCompra" value="{{ old('precioCompra') }}" required>
                @error('precioCompra')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="descripcion"><b>Descripción</b></label>
                <textarea name="descripcion" id="descripcion" placeholder="Describe este menú" rows="4" required>{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <h3>Productos del Menú</h3>
            <p>Selecciona los productos que formarán parte de este menú:</p>
            
            <div class="tabs">
                <div class="tab active" data-tab="comidas">Comidas</div>
                <div class="tab" data-tab="bebidas">Bebidas</div>
            </div>

            <div class="tab-content active" id="comidas-tab">
                <div class="productos-container">
                    <h4>Selecciona Comidas:</h4>
                    @foreach ($productos as $producto)
                        @if (substr($producto->cod, 0, 1) === 'C')
                            <div class="producto-item" data-cod="{{ $producto->cod }}">
                                <div>
                                    <input type="checkbox" class="producto-checkbox" data-cod="{{ $producto->cod }}" data-nombre="{{ $producto->nombre }}" data-precio="{{ $producto->pvp }}"
                                        data-precio-compra="{{ $producto->precioCompra }}">
                                    <strong>{{ $producto->nombre }}</strong> - {{ number_format($producto->pvp, 2) }}€
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="tab-content" id="bebidas-tab">
                <div class="productos-container">
                    <h4>Selecciona Bebidas:</h4>
                    @foreach ($productos as $producto)
                        @if (substr($producto->cod, 0, 1) === 'B')
                            <div class="producto-item" data-cod="{{ $producto->cod }}">
                                <div>
                                    <input type="checkbox" class="producto-checkbox" data-cod="{{ $producto->cod }}" data-nombre="{{ $producto->nombre }}" data-precio="{{ $producto->pvp }}"
                                        data-precio-compra="{{ $producto->precioCompra }}">
                                    <strong>{{ $producto->nombre }}</strong> - {{ number_format($producto->pvp, 2) }}€
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="productos-seleccionados">
                <h4>Productos Seleccionados:</h4>
                <div id="seleccionados-container">
                    <p id="no-productos-msg">No hay productos seleccionados.</p>
                </div>
            </div>

            <div id="productos-inputs">
                <!-- Aquí se agregarán dinámicamente los inputs ocultos para los productos seleccionados -->
            </div>

            <button type="submit" class="submit-btn">Crear Menú</button>
            
            <div class="action-links">
                <a href="{{ route('productos.paginate') }}" class="link-back">Volver al listado de productos</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Deshabilitar los campos de precio para que no se puedan editar
            const pvpInput = document.getElementById('pvp');
            const precioCompraInput = document.getElementById('precioCompra');
            
            if (pvpInput) {
                pvpInput.readOnly = true;
                pvpInput.value = '0.00';
            }
            
            if (precioCompraInput) {
                precioCompraInput.readOnly = true;
                precioCompraInput.value = '0.00';
            }
            // Configurar CSRF token para solicitudes AJAX
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Manejo de pestañas
            const tabs = document.querySelectorAll('.tab');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Actualizar pestañas activas
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Mostrar contenido de la pestaña seleccionada
                    const tabName = this.getAttribute('data-tab');
                    tabContents.forEach(content => {
                        content.classList.remove('active');
                    });
                    document.getElementById(tabName + '-tab').classList.add('active');
                });
            });
            
            // Variables para controlar productos seleccionados
            const seleccionadosContainer = document.getElementById('seleccionados-container');
            const noProductosMsg = document.getElementById('no-productos-msg');
            const productosInputs = document.getElementById('productos-inputs');
            let productosSeleccionados = [];
            
            // Agregar evento a los checkboxes de productos
            const checkboxes = document.querySelectorAll('.producto-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const cod = this.getAttribute('data-cod');
                    const nombre = this.getAttribute('data-nombre');
                    const precio = this.getAttribute('data-precio');
                    const precioCompra = this.getAttribute('data-precio-compra');
                    
                    if (this.checked) {
                        // Agregar producto a la lista de seleccionados
                        agregarProducto(cod, nombre, precio, precioCompra);
                        
                        // Marcar visualmente como seleccionado
                        this.closest('.producto-item').classList.add('producto-seleccionado');
                    } else {
                        // Quitar producto de la lista de seleccionados
                        eliminarProducto(cod);
                        
                        // Quitar marca visual
                        this.closest('.producto-item').classList.remove('producto-seleccionado');
                    }
                });
            });
            
            // Función para agregar un producto a la lista de seleccionados
            function agregarProducto(cod, nombre, precio, precioCompra) {
                // Comprobar si ya está seleccionado
                if (productosSeleccionados.find(p => p.cod === cod)) {
                    return;
                }
                
                // Agregar a la lista interna
                productosSeleccionados.push({
                    cod: cod,
                    nombre: nombre,
                    precio: precio,
                    precioCompra: precioCompra,
                    cantidad: 1
                });
                
                // Actualizar visualización
                actualizarVistaSeleccionados();
            }
            
            // Función para eliminar un producto de la lista de seleccionados
            function eliminarProducto(cod) {
                // Quitar de la lista interna
                productosSeleccionados = productosSeleccionados.filter(p => p.cod !== cod);
                
                // Actualizar visualización
                actualizarVistaSeleccionados();
                
                // Desmarcar el checkbox correspondiente
                const checkbox = document.querySelector(`.producto-checkbox[data-cod="${cod}"]`);
                if (checkbox) {
                    checkbox.checked = false;
                    checkbox.closest('.producto-item').classList.remove('producto-seleccionado');
                }
            }

            // Agregar esta función en el script existente
            function calcularPrecioTotal() {
                let precioVentaTotal = 0;
                let precioCompraTotal = 0;
                
                productosSeleccionados.forEach(producto => {
                    const cantidad = parseInt(producto.cantidad);
                    precioVentaTotal += parseFloat(producto.precio) * cantidad;
                    precioCompraTotal += parseFloat(producto.precioCompra) * cantidad;
                });
                
                // Actualizar los campos de precio
                document.getElementById('pvp').value = precioVentaTotal.toFixed(2);
                document.getElementById('precioCompra').value = precioCompraTotal.toFixed(2);
                
                return { precioVentaTotal, precioCompraTotal };
            }
            
            // Función para actualizar la vista de productos seleccionados
            function actualizarVistaSeleccionados() {
                // Limpiar contenedor
                productosInputs.innerHTML = '';
                
                if (productosSeleccionados.length === 0) {
                    // Mostrar mensaje de no hay productos
                    noProductosMsg.style.display = 'block';
                    seleccionadosContainer.innerHTML = '';
                    seleccionadosContainer.appendChild(noProductosMsg);
                    return;
                }
                
                // Ocultar mensaje de no hay productos
                noProductosMsg.style.display = 'none';
                
                // Crear HTML para los productos seleccionados
                let html = '';
                productosSeleccionados.forEach((producto, index) => {
                    html += `
                        <div class="producto-item producto-seleccionado" id="seleccionado-${producto.cod}">
                            <div class="d-flex align-items-center">
                                <strong>${producto.nombre}</strong> - ${parseFloat(producto.precio).toFixed(2)}€
                                <div class="ml-auto">
                                    <label>Cantidad: 
                                        <input type="number" min="1" class="cantidad-input" 
                                            value="${producto.cantidad}" 
                                            data-cod="${producto.cod}" 
                                            onchange="actualizarCantidad('${producto.cod}', this.value)">
                                    </label>
                                    <span class="delete-producto" onclick="eliminarProductoSeleccionado('${producto.cod}')">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </span>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Crear inputs ocultos para enviar al servidor
                    const hiddenInputProducto = document.createElement('input');
                    hiddenInputProducto.type = 'hidden';
                    hiddenInputProducto.name = `producto_ids[]`;
                    hiddenInputProducto.value = producto.cod;
                    productosInputs.appendChild(hiddenInputProducto);
                    
                    const hiddenInputCantidad = document.createElement('input');
                    hiddenInputCantidad.type = 'hidden';
                    hiddenInputCantidad.name = `cantidades[]`;
                    hiddenInputCantidad.value = producto.cantidad;
                    hiddenInputCantidad.id = `cantidad-${producto.cod}`;
                    productosInputs.appendChild(hiddenInputCantidad);
                    
                    const hiddenInputDescripcion = document.createElement('input');
                    hiddenInputDescripcion.type = 'hidden';
                    hiddenInputDescripcion.name = `descripciones[]`;
                    hiddenInputDescripcion.value = producto.descripcion || '';
                    hiddenInputDescripcion.id = `descripcion-${producto.cod}`;
                    productosInputs.appendChild(hiddenInputDescripcion);
                });
                
                seleccionadosContainer.innerHTML = html;
                
                // Actualizar precio total
                calcularPrecioTotal();
            }
            
            window.actualizarCantidad = function(cod, cantidad) {
                const producto = productosSeleccionados.find(p => p.cod === cod);
                if (producto) {
                    producto.cantidad = parseInt(cantidad);
                    
                    // Actualizar input oculto
                    const hiddenInput = document.getElementById(`cantidad-${cod}`);
                    if (hiddenInput) {
                        hiddenInput.value = cantidad;
                    }
                    
                    // Recalcular precio total
                    calcularPrecioTotal();
                }
            };
            
            // Función para actualizar la descripción de un producto
            window.actualizarDescripcion = function(cod, descripcion) {
                const producto = productosSeleccionados.find(p => p.cod === cod);
                if (producto) {
                    producto.descripcion = descripcion;
                    
                    // Actualizar input oculto
                    const hiddenInput = document.getElementById(`descripcion-${cod}`);
                    if (hiddenInput) {
                        hiddenInput.value = descripcion;
                    }
                }
            };
            
            // Función para eliminar un producto seleccionado
            window.eliminarProductoSeleccionado = function(cod) {
                eliminarProducto(cod);
            };
            
            // Validación del formulario antes de enviar
            document.getElementById('form_menu').addEventListener('submit', function(e) {
                e.preventDefault();
                
                let hasErrors = false;
                
                // Validar nombre
                const nombreInput = document.getElementById('nombre');
                if (nombreInput.value.trim() === '') {
                    if (nombreInput.nextElementSibling && nombreInput.nextElementSibling.classList.contains('error-message')) {
                        nombreInput.nextElementSibling.textContent = 'El nombre es obligatorio';
                    } else {
                        const errorDiv = document.createElement('div');
                        errorDiv.classList.add('error-message');
                        errorDiv.textContent = 'El nombre es obligatorio';
                        nombreInput.parentNode.insertBefore(errorDiv, nombreInput.nextSibling);
                    }
                    nombreInput.classList.add('error-input');
                    hasErrors = true;
                }
                
                // Validar precio venta
                const pvpInput = document.getElementById('pvp');
                if (pvpInput.value <= 0) {
                    if (pvpInput.nextElementSibling && pvpInput.nextElementSibling.classList.contains('error-message')) {
                        pvpInput.nextElementSibling.textContent = 'El precio de venta debe ser mayor que 0';
                    } else {
                        const errorDiv = document.createElement('div');
                        errorDiv.classList.add('error-message');
                        errorDiv.textContent = 'El precio de venta debe ser mayor que 0';
                        pvpInput.parentNode.insertBefore(errorDiv, pvpInput.nextSibling);
                    }
                    pvpInput.classList.add('error-input');
                    hasErrors = true;
                }
                
                // Validar productos seleccionados
                if (productosSeleccionados.length === 0) {
                    const errorDiv = document.createElement('div');
                    errorDiv.classList.add('error-message');
                    errorDiv.textContent = 'Debe seleccionar al menos un producto para el menú';
                    seleccionadosContainer.parentNode.insertBefore(errorDiv, seleccionadosContainer.nextSibling);
                    hasErrors = true;
                }
                
                if (!hasErrors) {
                    this.submit();
                }
            });
        });
    </script>
</body>
</html>


<!--Entrega2-->