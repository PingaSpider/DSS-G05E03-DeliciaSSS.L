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
                <span class="detail-value">{{ $menu->descripcion }}</span>
            </div>
        </div>
        
        <div class="lineas-pedido">
            <h2>Productos Incluidos en el Menú</h2>
            
            @if(count($menuProductos) > 0)
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Cantidad</th>
                                <th>Producto</th>
                                <th>Tipo</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalProductos = 0;
                                $valorProductos = 0;
                            @endphp
                            
                            @foreach($menuProductos as $menuProducto)
                                @php
                                    $subtotal = $menuProducto->pvp * $menuProducto->cantidad;
                                    $valorProductos += $subtotal;
                                    $totalProductos += $menuProducto->cantidad;
                                    $tipoProducto = substr($menuProducto->producto_cod, 0, 1) === 'C' ? 'comida' : 'bebida';
                                @endphp
                                <tr>
                                    <td>{{ $menuProducto->cantidad }}</td>
                                    <td>
                                        {{ $menuProducto->nombre }}
                                        @if($menuProducto->descripcion)
                                            <div class="small-text">{{ $menuProducto->descripcion }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $tipoProducto }}">
                                            {{ ucfirst($tipoProducto) }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($menuProducto->pvp, 2, ',', '.') }} €</td>
                                    <td>{{ number_format($subtotal, 2, ',', '.') }} €</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="text-align: right; font-weight: bold;">Total Items:</td>
                                <td colspan="3">{{ $totalProductos }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: right; font-weight: bold;">Valor Individual:</td>
                                <td colspan="3">{{ number_format($valorProductos, 2, ',', '.') }} €</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: right; font-weight: bold;">Precio Menú:</td>
                                <td colspan="3">{{ number_format($producto->pvp, 2, ',', '.') }} €</td>
                            </tr>
                            @php
                                $ahorro = $valorProductos - $producto->pvp;
                                $porcentajeAhorro = ($valorProductos > 0) ? ($ahorro / $valorProductos) * 100 : 0;
                            @endphp
                            @if($ahorro > 0)
                                <tr>
                                    <td colspan="2" style="text-align: right; font-weight: bold;">Ahorro:</td>
                                    <td colspan="3" style="color: #2e7d32;">{{ number_format($ahorro, 2, ',', '.') }} € ({{ number_format($porcentajeAhorro, 2, ',', '.') }}%)</td>
                                </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="highlight-box">
                    <div class="content">No hay productos asociados a este menú.</div>
                </div>
            @endif
        </div>
        
        <div class="action-buttons">
            <a href="{{ route('menus.edit', $producto->cod) }}" class="action-btn edit-btn">Editar Menú</a>
            
            <form action="{{ route('menus.destroy', $producto->cod) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn delete-btn" onclick="return confirm('¿Estás seguro de que deseas eliminar este menú?')">Eliminar Menú</button>
            </form>
            
            <a href="{{ route('productos.paginate') }}" class="action-btn view-btn">Volver al Listado</a>
        </div>
    </div>
    
    <style>
        /* Estilos específicos para tipos de productos */
        .badge-comida {
            background-color: #fff2d6;
            color: #e46a20;
        }
        
        .badge-bebida {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        /* Estilos adicionales */
        .small-text {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
        }
        
        .highlight-box {
            background-color: #fff2d6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        tfoot td {
            border-top: 2px solid #ddd;
            padding-top: 15px;
        }
        
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


<!--Entrega2-->