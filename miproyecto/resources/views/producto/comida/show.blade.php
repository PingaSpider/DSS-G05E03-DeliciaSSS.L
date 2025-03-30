<!DOCTYPE html>
<html lang="es">
<head>
    <title>Detalle de Comida</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pedido/create.css') }}">
</head>
<body>
    <div class="container">
        <h1>Detalle de Comida</h1>
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
        
        <div class="pedido-details">
            <div class="detail-item">
                <span class="detail-label">Código del Producto:</span>
                <span class="detail-value">{{ $producto->cod }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Nombre:</span>
                <span class="detail-value">{{ $producto->nombre }}</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Precio de Venta:</span>
                <span class="detail-value">{{ number_format($producto->pvp, 2, ',', '.') }} €</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Precio de Compra:</span>
                <span class="detail-value">{{ number_format($producto->precioCompra, 2, ',', '.') }} €</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Stock:</span>
                <span class="detail-value">{{ $producto->stock }} unidades</span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Margen:</span>
                <span class="detail-value">
                    @php
                        $margen = $producto->pvp - $producto->precioCompra;
                        $porcentaje = ($producto->precioCompra > 0) ? ($margen / $producto->precioCompra) * 100 : 0;
                    @endphp
                    {{ number_format($margen, 2, ',', '.') }} € ({{ number_format($porcentaje, 2, ',', '.') }}%)
                </span>
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Descripción:</span>
                <span class="detail-value">{{ $comida->descripcion }}</span>
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="{{ route('comida.edit', $producto->cod) }}" class="action-btn edit-btn">Editar Comida</a>
            
            <form action="{{ route('comida.destroy', $producto->cod) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn delete-btn" onclick="return confirm('¿Estás seguro de que deseas eliminar esta comida?')">Eliminar Comida</button>
            </form>
            
            <a href="{{ route('productos.paginate') }}" class="action-btn view-btn">Volver al Listado</a>
        </div>
    </div>
    
    <style>
        /* Ajustes responsivos */
        @media (max-width: 576px) {
            .action-buttons {
                flex-direction: column;
            }
            
            .action-btn {
                margin-bottom: 10px;
            }
        }
    </style>
</body>
</html>