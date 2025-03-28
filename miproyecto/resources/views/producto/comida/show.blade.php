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
        
        <div class="product-card">
            <div class="product-header">
                <h2 class="product-title">{{ $producto->nombre }}</h2>
                <span class="product-code">Código: {{ $producto->cod }}</span>
            </div>
            
            <div class="product-data">
                <h3>Información General</h3>
                <div class="data-row">
                    <span class="data-label">Precio de Venta:</span>
                    <span class="data-value">{{ number_format($producto->pvp, 2) }} €</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Precio de Compra:</span>
                    <span class="data-value">{{ number_format($producto->precioCompra, 2) }} €</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Stock:</span>
                    <span class="data-value">{{ $producto->stock }} unidades</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Margen:</span>
                    <span class="data-value">
                        @php
                            $margen = $producto->pvp - $producto->precioCompra;
                            $porcentaje = ($producto->precioCompra > 0) ? ($margen / $producto->precioCompra) * 100 : 0;
                        @endphp
                        {{ number_format($margen, 2) }} € ({{ number_format($porcentaje, 2) }}%)
                    </span>
                </div>
            </div>
            
            <div class="product-data">
                <h3>Descripción</h3>
                <div class="description-box">
                    {{ $comida->descripcion }}
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('comida.edit', $producto->cod) }}" class="btn btn-primary">Editar</a>
                <a href="{{ route('productos.paginate') }}" class="btn btn-secondary">Volver al Listado</a>
                <form action="{{ route('comida.destroy', $producto->cod) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta comida?')">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>