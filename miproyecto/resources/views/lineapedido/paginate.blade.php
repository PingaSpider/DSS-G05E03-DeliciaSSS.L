<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lista de Líneas de Pedido</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/lineapedido/create.css') }}">
</head>
<body>
    <div class="container">
        <h1>Lista de Líneas de Pedido</h1>
        
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
            <form action="{{ route('lineaPedidos.paginate') }}" method="GET" class="search-form">
                <div class="search-group">
                    <input type="text" name="search" placeholder="Buscar por código, pedido, producto..." value="{{ request('search') }}" class="search-input">
                    <button type="submit" class="search-button">Buscar</button>
                    @if(request('search'))
                        <a href="{{ route('lineaPedidos.paginate') }}" class="search-clear">Limpiar</a>
                    @endif
                </div>
            </form>
        </div>
        
        @if(request('search'))
            <div class="search-results-info">
                Mostrando resultados para: <strong>{{ request('search') }}</strong> 
                ({{ $lineaPedidos->total() }} {{ $lineaPedidos->total() == 1 ? 'resultado' : 'resultados' }})
            </div>
        @endif
        
        <div class="action-links">
            <a href="{{ route('lineaPedidos.create') }}" class="submit-btn" style="display: inline-block; width: auto; text-decoration: none; text-align: center;">Crear Nueva Línea de Pedido</a>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Pedido</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lineaPedidos as $lineaPedido)
                    <tr>
                        <td>{{ $lineaPedido->linea }}</td>
                        <td>
                            <a href="{{ route('pedidos.show', $lineaPedido->pedido->cod) }}" class="link-pedido">
                                {{ $lineaPedido->pedido->cod }}
                            </a>
                        </td>
                        <td>{{ $lineaPedido->producto->nombre }}</td>
                        <td>{{ $lineaPedido->cantidad }}</td>
                        <td>{{ number_format($lineaPedido->precio, 2, ',', '.') }} €</td>
                        <td>{{ number_format($lineaPedido->subtotal, 2, ',', '.') }} €</td>
                        <td>
                            <a href="{{ route('lineaPedidos.show', $lineaPedido->linea) }}" class="action-btn view-btn">Ver</a>
                            <button type="button" class="action-btn edit-btn" onclick="openEditModal('{{ $lineaPedido->linea }}', '{{ $lineaPedido->pedido_id }}', '{{ $lineaPedido->producto_id }}', {{ $lineaPedido->cantidad }}, {{ $lineaPedido->precio }})">Editar</button>
                            <button type="button" class="action-btn delete-btn" onclick="deleteLineaPedido('{{ $lineaPedido->linea }}')">Eliminar</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="mt-4 flex justify-center">
            {{ $lineaPedidos->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Modal para editar línea de pedido -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
            <div class="modal-header">
                <h2>Editar Línea de Pedido</h2>
            </div>
            <form id="editLineaPedidoForm" method="POST">
                @csrf
                @method('PUT')
                
                <input type="hidden" id="edit_linea" name="linea">
                
                <div class="form-group">
                    <label for="edit_pedido_id">Pedido</label>
                    <select id="edit_pedido_id" name="pedido_id" required>
                        @foreach (\App\Models\Pedido::all() as $pedido)
                            <option value="{{ $pedido->cod }}">
                                {{ $pedido->cod }} - {{ date('d/m/Y', strtotime($pedido->fecha)) }} - {{ $pedido->usuario->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <div class="error-message" id="pedidoError"></div>
                </div>
                
                <div class="form-group">
                    <label for="edit_producto_id">Producto</label>
                    <select id="edit_producto_id" name="producto_id" required>
                        @foreach (\App\Models\Producto::all() as $producto)
                            <option value="{{ $producto->cod }}" data-precio="{{ $producto->precio }}">
                                {{ $producto->nombre }} - {{ number_format($producto->precio, 2, ',', '.') }} €
                            </option>
                        @endforeach
                    </select>
                    <div class="error-message" id="productoError"></div>
                </div>
                
                <div class="form-group">
                    <label for="edit_cantidad">Cantidad</label>
                    <input type="number" id="edit_cantidad" name="cantidad" min="1" required>
                    <div class="error-message" id="cantidadError"></div>
                </div>
                
                <div class="form-group">
                    <label for="edit_precio">Precio Unitario (€)</label>
                    <input type="number" id="edit_precio" name="precio" step="0.01" min="0" required>
                    <div class="error-message" id="precioError"></div>
                </div>
                
                <div class="form-group">
                    <label>Subtotal</label>
                    <div class="subtotal-display" id="edit_subtotal">0,00 €</div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Form oculto para eliminar línea de pedido -->
    <form id="deleteLineaPedidoForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        // Abrir modal de edición
        function openEditModal(linea, pedido_id, producto_id, cantidad, precio) {
            document.getElementById('edit_linea').value = linea;
            document.getElementById('edit_pedido_id').value = pedido_id;
            document.getElementById('edit_producto_id').value = producto_id;
            document.getElementById('edit_cantidad').value = cantidad;
            document.getElementById('edit_precio').value = precio;
            
            // Calcular subtotal inicial
            updateEditSubtotal();
            
            // Establece la acción del formulario con la ruta correcta
            document.getElementById('editLineaPedidoForm').action = `/lineas-pedido/${linea}`;
            
            // Limpia los mensajes de error
            clearErrors();
            
            // Muestra el modal
            document.getElementById('editModal').style.display = 'block';
        }
        
        // Cerrar modal de edición
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        // Función para eliminar línea de pedido
        function deleteLineaPedido(linea) {
            if (confirm('¿Estás seguro de que deseas eliminar esta línea de pedido?')) {
                const form = document.getElementById('deleteLineaPedidoForm');
                form.action = `/lineas-pedido/${linea}`;
                form.submit();
            }
        }

        // Limpiar mensajes de error
        function clearErrors() {
            document.getElementById('pedidoError').textContent = '';
            document.getElementById('productoError').textContent = '';
            document.getElementById('cantidadError').textContent = '';
            document.getElementById('precioError').textContent = '';
        }
        
        // Actualizar subtotal en el modal de edición
        function updateEditSubtotal() {
            const cantidad = parseFloat(document.getElementById('edit_cantidad').value) || 0;
            const precio = parseFloat(document.getElementById('edit_precio').value) || 0;
            const subtotal = cantidad * precio;
            document.getElementById('edit_subtotal').textContent = subtotal.toLocaleString('es-ES', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + ' €';
        }
        
        // Actualizar precio y subtotal cuando cambia el producto en el modal
        document.getElementById('edit_producto_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value !== '') {
                const precio = selectedOption.getAttribute('data-precio');
                document.getElementById('edit_precio').value = precio;
                updateEditSubtotal();
            }
        });
        
        // Actualizar subtotal cuando cambia la cantidad o precio en el modal
        document.getElementById('edit_cantidad').addEventListener('input', updateEditSubtotal);
        document.getElementById('edit_precio').addEventListener('input', updateEditSubtotal);

        // Validación del formulario de edición
        document.getElementById('editLineaPedidoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            clearErrors();
            
            const cantidad = document.getElementById('edit_cantidad').value;
            const precio = document.getElementById('edit_precio').value;
            let hasErrors = false;
            
            // Validación básica
            if (cantidad.trim() === '' || parseInt(cantidad) < 1) {
                document.getElementById('cantidadError').textContent = 'La cantidad debe ser al menos 1';
                hasErrors = true;
            }
            
            if (precio.trim() === '' || parseFloat(precio) < 0) {
                document.getElementById('precioError').textContent = 'El precio no puede ser negativo';
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