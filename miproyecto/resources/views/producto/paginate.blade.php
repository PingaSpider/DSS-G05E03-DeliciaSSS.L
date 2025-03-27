<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lista de Productos</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/lineapedido/create.css') }}">
</head>
<body>
    <div class="container">
        <h1>Lista de Productos</h1>
        
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
        
        <!-- Formulario de búsqueda -->
        <div class="search-container">
            <form action="{{ route('productos.paginate') }}" method="GET" class="search-form">
                <div class="search-group">
                    <input type="text" name="search" placeholder="Buscar por código o nombre..." value="{{ request('search') }}" class="search-input">
                    <button type="submit" class="search-button">Buscar</button>
                    @if(request('search'))
                        <a href="{{ route('productos.paginate') }}" class="search-clear">Limpiar</a>
                    @endif
                </div>
            </form>
        </div>
        
        @if(request('search'))
            <div class="search-results-info">
                Mostrando resultados para: <strong>{{ request('search') }}</strong> 
                ({{ $productos->total() }} {{ $productos->total() == 1 ? 'resultado' : 'resultados' }})
            </div>
        @endif
        
        <div class="action-links">
            <a href="{{ route('productos.create') }}" class="submit-btn" style="display: inline-block; width: auto; text-decoration: none; text-align: center;">Crear Nuevo Producto</a>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Precio Venta (€)</th>
                        <th>Stock</th>
                        <th>Precio Compra (€)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $producto)
                    <tr>
                        <td>{{ $producto->cod }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ number_format($producto->pvp, 2, ',', '.') }} €</td>
                        <td>{{ $producto->stock }}</td>
                        <td>{{ number_format($producto->precioCompra, 2, ',', '.') }} €</td>
                        <td>
                            <a href="{{ route('productos.show', $producto->cod) }}" class="action-btn view-btn">Ver</a>
                            <button type="button" class="action-btn edit-btn" onclick="openEditModal('{{ $producto->cod }}', '{{ $producto->nombre }}', {{ $producto->pvp }}, {{ $producto->stock }}, {{ $producto->precioCompra }})">Editar</button>
                            <button type="button" class="action-btn delete-btn" onclick="deleteProducto('{{ $producto->cod }}')">Eliminar</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="mt-4 flex justify-center">
            {{ $productos->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Modal para editar producto -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
            <div class="modal-header">
                <h2>Editar Producto</h2>
            </div>
            <form id="editProductoForm" method="POST">
                @csrf
                @method('PUT')
                
                <input type="hidden" id="edit_cod" name="cod">
                <input type="hidden" id="edit_tipo" name="tipo">
                
                <div class="form-group">
                    <label for="edit_nombre">Nombre del Producto</label>
                    <input type="text" id="edit_nombre" name="nombre" required>
                    <div class="error-message" id="nombreError"></div>
                </div>
                
                <div class="form-group">
                    <label for="edit_pvp">Precio de Venta (€)</label>
                    <input type="number" step="0.01" id="edit_pvp" name="pvp" required>
                    <div class="error-message" id="pvpError"></div>
                </div>
                
                <div class="form-group">
                    <label for="edit_stock">Stock</label>
                    <input type="number" id="edit_stock" name="stock" required>
                    <div class="error-message" id="stockError"></div>
                </div>
                
                <div class="form-group">
                    <label for="edit_precioCompra">Precio de Compra (€)</label>
                    <input type="number" step="0.01" id="edit_precioCompra" name="precioCompra" required>
                    <div class="error-message" id="precioCompraError"></div>
                </div>
                
                <!-- Campos específicos para comida -->
                <div id="edit_campos_comida" class="specific-fields" style="display: none;">
                    <h3>Datos específicos de Comida</h3>
                    <div class="form-group">
                        <label for="edit_descripcion">Descripción</label>
                        <textarea id="edit_descripcion" name="descripcion" rows="4"></textarea>
                        <div class="error-message" id="descripcionError"></div>
                    </div>
                </div>
                
                <!-- Campos específicos para bebida -->
                <div id="edit_campos_bebida" class="specific-fields" style="display: none;">
                    <h3>Datos específicos de Bebida</h3>
                    <div class="form-group">
                        <label for="edit_tamanio">Tamaño</label>
                        <select id="edit_tamanio" name="tamanio">
                            <option value="">Seleccione un tamaño</option>
                            <option value="Pequeño">Pequeño</option>
                            <option value="Mediano">Mediano</option>
                            <option value="Grande">Grande</option>
                        </select>
                        <div class="error-message" id="tamanioError"></div>
                    </div>
                    <div class="form-group">
                        <label for="edit_tipoBebida">Tipo de Bebida</label>
                        <select id="edit_tipoBebida" name="tipoBebida">
                            <option value="">Seleccione un tipo</option>
                            <option value="Refresco">Refresco</option>
                            <option value="Vino">Vino</option>
                            <option value="Cerveza">Cerveza</option>
                            <option value="Agua">Agua</option>
                        </select>
                        <div class="error-message" id="tipoBebidaError"></div>
                    </div>
                    <div class="form-group">
                        <label for="edit_alcoholica" class="checkbox-label">
                            <input type="checkbox" id="edit_alcoholica" name="alcoholica" value="1">
                            Bebida alcohólica
                        </label>
                    </div>
                </div>
                
                <!-- Campos específicos para menú -->
                <div id="edit_campos_menu" class="specific-fields" style="display: none;">
                    <h3>Datos específicos de Menú</h3>
                    <div class="form-group">
                        <label for="edit_descripcion_menu">Descripción del Menú</label>
                        <textarea id="edit_descripcion_menu" name="descripcion_menu" rows="4"></textarea>
                        <div class="error-message" id="descripcion_menuError"></div>
                    </div>
                    
                    <div class="menu-productos-container">
                        <h4>Productos incluidos en el menú</h4>
                        <p>Para gestionar los productos del menú, edite el producto desde la vista detallada.</p>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Form oculto para eliminar producto -->
    <form id="deleteProductoForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        // Abrir modal de edición
        function openEditModal(cod, nombre, pvp, stock, precioCompra) {
            document.getElementById('edit_cod').value = cod;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_pvp').value = pvp;
            document.getElementById('edit_stock').value = stock;
            document.getElementById('edit_precioCompra').value = precioCompra;
            
            // Establece la acción del formulario con la ruta correcta
            document.getElementById('editProductoForm').action = `/productos/${cod}`;
            
            // Limpia los mensajes de error
            clearErrors();
            
            // Muestra el modal
            document.getElementById('editModal').style.display = 'block';
        }
        
        // Cerrar modal de edición
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        // Función para eliminar producto
        function deleteProducto(cod) {
            if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                const form = document.getElementById('deleteProductoForm');
                form.action = `/productos/${cod}`;
                form.submit();
            }
        }

        // Limpiar mensajes de error
        function clearErrors() {
            document.getElementById('nombreError').textContent = '';
            document.getElementById('pvpError').textContent = '';
            document.getElementById('stockError').textContent = '';
            document.getElementById('precioCompraError').textContent = '';
        }

        // Validación del formulario de edición
        document.getElementById('editProductoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            clearErrors();
            
            const nombre = document.getElementById('edit_nombre').value;
            const pvp = document.getElementById('edit_pvp').value;
            const stock = document.getElementById('edit_stock').value;
            const precioCompra = document.getElementById('edit_precioCompra').value;
            let hasErrors = false;
            
            // Validación básica
            if (nombre.trim() === '') {
                document.getElementById('nombreError').textContent = 'El nombre es obligatorio';
                hasErrors = true;
            }
            
            if (pvp.trim() === '' || isNaN(pvp) || Number(pvp) <= 0) {
                document.getElementById('pvpError').textContent = 'El precio de venta debe ser un número positivo';
                hasErrors = true;
            }
            
            if (stock.trim() === '' || isNaN(stock) || Number(stock) < 0) {
                document.getElementById('stockError').textContent = 'El stock debe ser un número no negativo';
                hasErrors = true;
            }
            
            if (precioCompra.trim() === '' || isNaN(precioCompra) || Number(precioCompra) <= 0) {
                document.getElementById('precioCompraError').textContent = 'El precio de compra debe ser un número positivo';
                hasErrors = true;
            }
            
            if (hasErrors) {
                return;
            }
            
            // Si todo está correcto, enviar el formulario
            this.submit();
        });
        
        // Cerrar el modal si se hace clic fuera de él
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeEditModal();
            }
        }
    </script>
</body>
</html>