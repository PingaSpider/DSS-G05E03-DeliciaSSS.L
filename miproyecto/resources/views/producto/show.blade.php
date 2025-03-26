<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalles del Producto</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pedido/create.css') }}">
</head>
<body>
    <div class="container">
        <h1>Detalles del Producto</h1>
        
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
        
        <div class="product-details">
            <div class="detail-row">
                <div class="detail-label">Código:</div>
                <div class="detail-value">{{ $producto->cod }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Nombre:</div>
                <div class="detail-value">{{ $producto->nombre }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Precio de Venta:</div>
                <div class="detail-value">{{ number_format($producto->pvp, 2, ',', '.') }} €</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Stock:</div>
                <div class="detail-value">{{ $producto->stock }}</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Precio de Compra:</div>
                <div class="detail-value">{{ number_format($producto->precioCompra, 2, ',', '.') }} €</div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Margen de Beneficio:</div>
                <div class="detail-value">{{ number_format($producto->pvp - $producto->precioCompra, 2, ',', '.') }} € 
                    ({{ number_format(($producto->pvp - $producto->precioCompra) / $producto->pvp * 100, 2, ',', '.') }}%)</div>
            </div>
            
            @if(method_exists($producto, 'getType'))
                <div class="detail-row">
                    <div class="detail-label">Tipo de Producto:</div>
                    <div class="detail-value">{{ $producto->getType() }}</div>
                </div>
            @endif
        </div>
        
        <div class="action-buttons">
            <a href="{{ route('productos.edit', $producto->cod) }}" class="action-btn edit-btn">Editar Producto</a>
            <button type="button" class="action-btn delete-btn" onclick="deleteProducto('{{ $producto->cod }}')">Eliminar Producto</button>
        </div>
        
        <div class="action-links">
            <a href="{{ route('productos.paginate') }}" class="link-back">Volver al listado</a>
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
    
    <style>
        .product-details {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .detail-label {
            font-weight: bold;
            width: 200px;
        }
        
        .detail-value {
            flex: 1;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
    </style>
</body>
</html>