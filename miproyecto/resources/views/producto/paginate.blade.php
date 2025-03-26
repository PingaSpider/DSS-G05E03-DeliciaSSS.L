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