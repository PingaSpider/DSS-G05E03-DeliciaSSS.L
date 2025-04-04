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
                <div class="detail-label">Tipo de Producto:</div>
                <div class="detail-value">
                    @if(substr($producto->cod, 0, 1) == 'C')
                        Comida
                    @elseif(substr($producto->cod, 0, 1) == 'B')
                        Bebida
                    @elseif(substr($producto->cod, 0, 1) == 'M')
                        Menú
                    @else
                        Otro
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Detalles específicos según el tipo de producto -->
        <div class="specific-details">
            @if(substr($producto->cod, 0, 1) == 'C' && isset($comida))
                <h3>Datos específicos de la Comida</h3>
                <div class="detail-row">
                    <div class="detail-label">Descripción:</div>
                    <div class="detail-value">{{ $comida->descripcion }}</div>
                </div>
            @elseif(substr($producto->cod, 0, 1) == 'B' && isset($bebida))
                <h3>Datos específicos de la Bebida</h3>
                <div class="detail-row">
                    <div class="detail-label">Tamaño:</div>
                    <div class="detail-value">{{ $bebida->tamanio }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Tipo de Bebida:</div>
                    <div class="detail-value">{{ $bebida->tipoBebida }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Alcohólica:</div>
                    <div class="detail-value">{{ $bebida->alcoholica ? 'Sí' : 'No' }}</div>
                </div>
            @elseif(substr($producto->cod, 0, 1) == 'M' && isset($menu))
                <h3>Datos específicos del Menú</h3>
                <div class="detail-row">
                    <div class="detail-label">Descripción:</div>
                    <div class="detail-value">{{ $menu->descripcion }}</div>
                </div>
                
                @if(isset($menuProductos) && count($menuProductos) > 0)
                    <h4>Productos incluidos en el menú:</h4>
                    <div class="menu-productos">
                        <!-- Agrupar por tipo (bebidas y comidas) -->
                        @php
                            $bebidas = $menuProductos->filter(function($item) {
                                return substr($item->producto_cod, 0, 1) == 'B';
                            });
                            
                            $comidas = $menuProductos->filter(function($item) {
                                return substr($item->producto_cod, 0, 1) == 'C';
                            });
                        @endphp
                        
                        <!-- Mostrar bebidas -->
                        @if(count($bebidas) > 0)
                            <div class="menu-categoria">Bebidas</div>
                            @foreach($bebidas as $item)
                                <div class="menu-producto-item">
                                    <div class="producto-info">
                                        <strong>{{ $item->nombre }}</strong> 
                                        <span>({{ $item->producto_cod }})</span>
                                        <div>{{ $item->pvp }} €</div>
                                    </div>
                                    <div class="cantidad-info">
                                        Cantidad: {{ $item->cantidad }}
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        
                        <!-- Mostrar comidas -->
                        @if(count($comidas) > 0)
                            <div class="menu-categoria">Comidas</div>
                            @foreach($comidas as $item)
                                <div class="menu-producto-item">
                                    <div class="producto-info">
                                        <strong>{{ $item->nombre }}</strong> 
                                        <span>({{ $item->producto_cod }})</span>
                                        <div>{{ $item->pvp }} €</div>
                                    </div>
                                    <div class="cantidad-info">
                                        Cantidad: {{ $item->cantidad }}
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @else
                    <p>Este menú no tiene productos asociados.</p>
                @endif
            @endif
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
</body>
</html>


<!--Entrega2-->