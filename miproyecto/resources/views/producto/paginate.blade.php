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
                    <input type="hidden" name="sort_by" value="{{ request('sort_by', 'cod') }}">
                    <input type="hidden" name="sort_order" value="{{ request('sort_order', 'asc') }}">
                    <button type="submit" class="search-button">Buscar</button>
                    @if(request('search'))
                        <a href="{{ route('productos.paginate', ['sort_by' => request('sort_by', 'cod'), 'sort_order' => request('sort_order', 'asc')]) }}" class="search-clear">Limpiar</a>
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
            <a href="{{ route('comida.create') }}" class="submit-btn">Crear Nueva Comida</a>
            <a href="{{ route('bebidas.create') }}" class="submit-btn">Crear Nueva Bebida</a>
            <a href="{{ route('menus.create') }}" class="submit-btn">Crear Nuevo Menú</a>    
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>
                            @php
                                $sortBy = request('sort_by', 'cod');
                                $sortOrder = request('sort_order', 'asc');
                                $newSortOrder = ($sortBy === 'cod' && $sortOrder === 'asc') ? 'desc' : 'asc';
                                $isActive = $sortBy === 'cod';
                            @endphp
                            <a href="{{ route('productos.paginate', ['sort_by' => 'cod', 'sort_order' => $newSortOrder, 'search' => request('search')]) }}" class="sort-link {{ $isActive ? 'sort-active' : '' }}">
                                Código
                                @if($isActive)
                                    <span class="sort-icon">{{ $sortOrder === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th>
                            @php
                                $newSortOrder = ($sortBy === 'nombre' && $sortOrder === 'asc') ? 'desc' : 'asc';
                                $isActive = $sortBy === 'nombre';
                            @endphp
                            <a href="{{ route('productos.paginate', ['sort_by' => 'nombre', 'sort_order' => $newSortOrder, 'search' => request('search')]) }}" class="sort-link {{ $isActive ? 'sort-active' : '' }}">
                                Nombre
                                @if($isActive)
                                    <span class="sort-icon">{{ $sortOrder === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th>
                            @php
                                $newSortOrder = ($sortBy === 'pvp' && $sortOrder === 'asc') ? 'desc' : 'asc';
                                $isActive = $sortBy === 'pvp';
                            @endphp
                            <a href="{{ route('productos.paginate', ['sort_by' => 'pvp', 'sort_order' => $newSortOrder, 'search' => request('search')]) }}" class="sort-link {{ $isActive ? 'sort-active' : '' }}">
                                Precio Venta (€)
                                @if($isActive)
                                    <span class="sort-icon">{{ $sortOrder === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th>
                            @php
                                $newSortOrder = ($sortBy === 'stock' && $sortOrder === 'asc') ? 'desc' : 'asc';
                                $isActive = $sortBy === 'stock';
                            @endphp
                            <a href="{{ route('productos.paginate', ['sort_by' => 'stock', 'sort_order' => $newSortOrder, 'search' => request('search')]) }}" class="sort-link {{ $isActive ? 'sort-active' : '' }}">
                                Stock
                                @if($isActive)
                                    <span class="sort-icon">{{ $sortOrder === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th>
                            @php
                                $newSortOrder = ($sortBy === 'precioCompra' && $sortOrder === 'asc') ? 'desc' : 'asc';
                                $isActive = $sortBy === 'precioCompra';
                            @endphp
                            <a href="{{ route('productos.paginate', ['sort_by' => 'precioCompra', 'sort_order' => $newSortOrder, 'search' => request('search')]) }}" class="sort-link {{ $isActive ? 'sort-active' : '' }}">
                                Precio Compra (€)
                                @if($isActive)
                                    <span class="sort-icon">{{ $sortOrder === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
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
                            @php
                                $tipoProducto = substr($producto->cod, 0, 1);
                                $rutaShow = 'productos.show';
                                
                                if ($tipoProducto === 'C') {
                                    $rutaShow = 'comida.show';
                                } elseif ($tipoProducto === 'B') {
                                    $rutaShow = 'bebidas.show';
                                } elseif ($tipoProducto === 'M') {
                                    $rutaShow = 'menus.show';
                                }
                            @endphp
                            <a href="{{ route($rutaShow, $producto->cod) }}" class="action-btn view-btn">Ver</a>
                            @php
                                $tipoProducto = substr($producto->cod, 0, 1);
                                $rutaEdit = 'productos.edit';

                                if ($tipoProducto === 'C') {
                                    $rutaEdit = 'comida.edit';
                                } elseif ($tipoProducto === 'B') {
                                    $rutaEdit = 'bebidas.edit';
                                } elseif ($tipoProducto === 'M') {
                                    $rutaEdit = 'menus.edit';
                                }
                            @endphp
                            <a href="{{ route($rutaEdit, $producto->cod) }}" class="action-btn edit-btn">Editar</a>
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

        <div>
            <a href="{{ url('/') }}" class="action-btn edit-btn">Volver al Panel Admin</a>
        </div>
    </div>
    <!-- Form oculto para eliminar producto -->
    <form id="deleteProductoForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script> 
        // Función para eliminar producto
        function deleteProducto(cod) {
            if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                const form = document.getElementById('deleteProductoForm');
                form.action = `/productos/${cod}`;
                form.submit();
            }
        }
    </script>
</body>
</html>