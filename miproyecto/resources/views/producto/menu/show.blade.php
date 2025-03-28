<!DOCTYPE html>
<html lang="es">
<head>
    <title>Detalle de Menú</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pedido/create.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pedido/opcional.css') }}">
</head>
<body>
    <div class="container">
        <h1>Detalle de Menú</h1>
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
                    {{ $menu->descripcion }}
                </div>
            </div>
        </div>
        
        <div class="product-card">
            <h3>Productos Incluidos en el Menú</h3>
            
            <div class="menu-productos">
                @php
                    $totalProductos = 0;
                    $valorProductos = 0;
                @endphp
                
                @if(count($menuProductos) > 0)
                    @foreach($menuProductos as $menuProducto)
                        @php
                            $subtotal = $menuProducto->pvp * $menuProducto->cantidad;
                            $valorProductos += $subtotal;
                            $totalProductos += $menuProducto->cantidad;
                            
                            // Determinar tipo de producto (comida o bebida)
                            $tipoProducto = substr($menuProducto->producto_cod, 0, 1) === 'C' ? 'comida' : 'bebida';
                        @endphp
                        <div class="menu-item">
                            <span class="menu-item-cantidad">{{ $menuProducto->cantidad }}</span>
                            <div class="menu-item-info">
                                <div>
                                    <span class="menu-item-nombre">{{ $menuProducto->nombre }}</span>
                                    <span class="menu-item-precio">{{ number_format($menuProducto->pvp, 2) }} €</span>
                                    <span class="tipo-badge tipo-{{ $tipoProducto }}">{{ $tipoProducto }}</span>
                                </div>
                                @if($menuProducto->descripcion)
                                    <div class="menu-item-descripcion">{{ $menuProducto->descripcion }}</div>
                                @endif
                            </div>
                            <div>
                                {{ number_format($subtotal, 2) }} €
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="menu-total">
                        <div>Total Items: {{ $totalProductos }}</div>
                        <div>Valor Individual: {{ number_format($valorProductos, 2) }} €</div>
                        <div>Precio Menú: {{ number_format($producto->pvp, 2) }} €</div>
                        @php
                            $ahorro = $valorProductos - $producto->pvp;
                            $porcentajeAhorro = ($valorProductos > 0) ? ($ahorro / $valorProductos) * 100 : 0;
                        @endphp
                        @if($ahorro > 0)
                            <div style="color: #4CAF50;">Ahorro: {{ number_format($ahorro, 2) }} € ({{ number_format($porcentajeAhorro, 2) }}%)</div>
                        @endif
                    </div>
                @else
                    <p>No hay productos asociados a este menú.</p>
                @endif
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('menus.edit', $producto->cod) }}" class="btn btn-primary">Editar</a>
                <a href="{{ route('productos.paginate') }}" class="btn btn-secondary">Volver al Listado</a>
                <form action="{{ route('menus.destroy', $producto->cod) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este menú?')">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>