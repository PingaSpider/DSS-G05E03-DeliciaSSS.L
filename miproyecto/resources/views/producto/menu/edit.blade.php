<!DOCTYPE html>
<html lang="es">
<head>
    <title>Editar Menú</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pedido/create.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pedido/edit.css') }}">
</head>
<body>
    <div class="container">
        <h1>Editar Menú</h1>
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
        
        <form id="form_menu" action="{{ route('menus.update', $producto->cod) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="cod"><b>Código</b></label>
                <input type="text" name="cod" id="cod" value="{{ $producto->cod }}" readonly disabled class="readonly-input">
                <div class="input-info">El código no se puede modificar</div>
            </div>
            
            <div class="form-group">
                <label for="nombre"><b>Nombre del Menú</b></label>
                <input type="text" placeholder="Introduce el nombre del menú" name="nombre" id="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
                @error('nombre')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="pvp"><b>Precio de Venta (€)</b></label>
                <input type="number" step="0.01" min="0" placeholder="Precio de venta" name="pvp" id="pvp" value="{{ old('pvp', $producto->pvp) }}" required>
                @error('pvp')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="stock"><b>Stock</b></label>
                <input type="number" min="0" placeholder="Cantidad disponible" name="stock" id="stock" value="{{ old('stock', $producto->stock) }}" required>
                @error('stock')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="precioCompra"><b>Precio de Compra (€)</b></label>
                <input type="number" step="0.01" min="0" placeholder="Precio de compra" name="precioCompra" id="precioCompra" value="{{ old('precioCompra', $producto->precioCompra) }}" required>
                @error('precioCompra')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="descripcion"><b>Descripción</b></label>
                <textarea name="descripcion" id="descripcion" placeholder="Describe este menú" rows="4" required>{{ old('descripcion', $menu->descripcion) }}</textarea>
                @error('descripcion')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="section-divider">
                <span class="section-title">Productos del Menú</span>
            </div>
            
            <p>Modifica los productos que forman parte de este menú:</p>
            
            <div class="tabs">
                <div class="tab active" data-tab="comidas">Comidas</div>
                <div class="tab" data-tab="bebidas">Bebidas</div>
            </div>

            <div class="tab-content active" id="comidas-tab">
                <div class="productos-container">
                    <h4>Selecciona Comidas:</h4>
                    @foreach ($productos as $productoItem)
                        @if (substr($productoItem->cod, 0, 1) === 'C')
                            @php
                                $isSelected = $menuProductos->contains('producto_cod', $productoItem->cod);
                                $menuProducto = $menuProductos->where('producto_cod', $productoItem->cod)->first();
                            @endphp
                            <div class="producto-item {{ $isSelected ? 'producto-seleccionado' : '' }}" data-cod="{{ $productoItem->cod }}">
                                <div>
                                    <input type="checkbox" class="producto-checkbox" data-cod="{{ $productoItem->cod }}" data-nombre="{{ $productoItem->nombre }}" data-precio="{{ $productoItem->pvp }}" {{ $isSelected ? 'checked' : '' }}>
                                    <strong>{{ $productoItem->nombre }}</strong> - {{ number_format($productoItem->pvp, 2, ',', '.') }}€
                                </div>
                                @if ($isSelected && $menuProducto)
                                <div class="producto-details" style="{{ $isSelected ? '' : 'display: none;' }}">
                                    <label>Cantidad: 
                                        <input type="number" min="1" class="cantidad-input" data-cod="{{ $productoItem->cod }}" value="{{ $menuProducto->cantidad }}">
                                    </label>
                                </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="tab-content" id="bebidas-tab">
                <div class="productos-container">
                    <h4>Selecciona Bebidas:</h4>
                    @foreach ($productos as $productoItem)
                        @if (substr($productoItem->cod, 0, 1) === 'B')
                            @php
                                $isSelected = $menuProductos->contains('producto_cod', $productoItem->cod);
                                $menuProducto = $menuProductos->where('producto_cod', $productoItem->cod)->first();
                            @endphp
                            <div class="producto-item {{ $isSelected ? 'producto-seleccionado' : '' }}" data-cod="{{ $productoItem->cod }}">
                                <div>
                                    <input type="checkbox" class="producto-checkbox" data-cod="{{ $productoItem->cod }}" data-nombre="{{ $productoItem->nombre }}" data-precio="{{ $productoItem->pvp }}" {{ $isSelected ? 'checked' : '' }}>
                                    <strong>{{ $productoItem->nombre }}</strong> - {{ number_format($productoItem->pvp, 2, ',', '.') }}€
                                </div>
                                @if ($isSelected && $menuProducto)
                                <div class="producto-details" style="{{ $isSelected ? '' : 'display: none;' }}">
                                    <label>Cantidad: 
                                        <input type="number" min="1" class="cantidad-input" data-cod="{{ $productoItem->cod }}" value="{{ $menuProducto->cantidad }}">
                                    </label>
                                </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="productos-seleccionados">
                <h4>Productos Seleccionados:</h4>
                <div id="seleccionados-container">
                    <p id="no-productos-msg" style="{{ count($menuProductos) > 0 ? 'display: none;' : '' }}">No hay productos seleccionados.</p>
                </div>
            </div>

            <div id="productos-inputs">
                <!-- Aquí se agregarán dinámicamente los inputs ocultos para los productos seleccionados -->
            </div>

            <button type="submit" class="submit-btn">Actualizar Menú</button>
            
            <div class="action-links">
                <a href="{{ route('productos.paginate') }}" class="link-back">Volver al listado</a>
            </div>
        </form>
    </div>

    <style>
        @media (max-width: 576px) {
            .tabs {
                flex-direction: column;
                border-bottom: none;
            }
            
            .tab {
                border: 1px solid #ddd;
                border-radius: 4px;
                margin-bottom: 5px;
            }
        }
    </style>

    <!-- Inicialización de datos para el script -->
    <script>
        // Pasamos los productos seleccionados al script
        var menuProductosInicial = [
            @foreach($menuProductos as $mp)
            {
                cod: "{{ $mp->producto_cod }}",
                nombre: "{{ $mp->nombre }}",
                precio: {{ $mp->pvp }},
                cantidad: {{ $mp->cantidad }},
                descripcion: "{{ $mp->descripcion }}"
            }{{ !$loop->last ? ',' : '' }}
            @endforeach
        ];
    </script>
    
    <!-- Carga del script externo -->
    <script src="{{ asset('js/menu-edit.js') }}"></script>
</body>
</html>