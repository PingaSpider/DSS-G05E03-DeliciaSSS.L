<!DOCTYPE html>
<html lang="es">
<head>
    <title>Crear Producto</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pedido/create.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pedido/opcional.css') }}">
    <script src="{{ asset('js/producto.js') }}" defer></script>
</head>
<body>
    <div class="container">
        <h1>Nuevo Producto</h1>
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
        
        <form id="form_producto" action="{{ route('productos.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="tipo"><b>Tipo de Producto</b></label>
                <select name="tipo" id="tipo" required onchange="mostrarCamposEspecificos()">
                    <option value="">Seleccione un tipo</option>
                    <option value="C" {{ old('tipo') == 'C' ? 'selected' : '' }}>Comida (C)</option>
                    <option value="B" {{ old('tipo') == 'B' ? 'selected' : '' }}>Bebida (B)</option>
                    <option value="M" {{ old('tipo') == 'M' ? 'selected' : '' }}>Menú (M)</option>
                </select>
                <p class="info-text">El código se generará automáticamente según el tipo seleccionado</p>
                @error('tipo')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="nombre"><b>Nombre del Producto</b></label>
                <input type="text" placeholder="Introduce el nombre del producto" name="nombre" id="nombre" value="{{ old('nombre') }}" required>
                @error('nombre')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="pvp"><b>Precio de Venta (€)</b></label>
                <input type="number" step="0.01" placeholder="Introduce el precio de venta" name="pvp" id="pvp" value="{{ old('pvp') }}" required>
                @error('pvp')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="stock"><b>Stock</b></label>
                <input type="number" placeholder="Introduce la cantidad en stock" name="stock" id="stock" value="{{ old('stock') }}" required>
                @error('stock')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="precioCompra"><b>Precio de Compra (€)</b></label>
                <input type="number" step="0.01" placeholder="Introduce el precio de compra" name="precioCompra" id="precioCompra" value="{{ old('precioCompra') }}" required>
                @error('precioCompra')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Campos específicos para Comida -->
            <div id="campos-comida" class="campos-especificos">
                <h3>Datos específicos de la Comida</h3>
                <div class="form-group">
                    <label for="descripcion"><b>Descripción</b></label>
                    <textarea placeholder="Introduce la descripción de la comida" name="descripcion" id="descripcion" rows="4">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Campos específicos para Bebida -->
            <div id="campos-bebida" class="campos-especificos">
                <h3>Datos específicos de la Bebida</h3>
                <div class="form-group">
                    <label for="tamanio"><b>Tamaño</b></label>
                    <select name="tamanio" id="tamanio">
                        <option value="">Seleccione un tamaño</option>
                        <option value="Pequeño" {{ old('tamanio') == 'Pequeño' ? 'selected' : '' }}>Pequeño</option>
                        <option value="Mediano" {{ old('tamanio') == 'Mediano' ? 'selected' : '' }}>Mediano</option>
                        <option value="Grande" {{ old('tamanio') == 'Grande' ? 'selected' : '' }}>Grande</option>
                    </select>
                    @error('tamanio')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="tipoBebida"><b>Tipo de Bebida</b></label>
                    <select name="tipoBebida" id="tipoBebida">
                        <option value="">Seleccione un tipo</option>
                        <option value="Refresco" {{ old('tipoBebida') == 'Refresco' ? 'selected' : '' }}>Refresco</option>
                        <option value="Vino" {{ old('tipoBebida') == 'Vino' ? 'selected' : '' }}>Vino</option>
                        <option value="Cerveza" {{ old('tipoBebida') == 'Cerveza' ? 'selected' : '' }}>Cerveza</option>
                        <option value="Agua" {{ old('tipoBebida') == 'Agua' ? 'selected' : '' }}>Agua</option>
                    </select>
                    @error('tipoBebida')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="alcoholica">
                        <input type="checkbox" name="alcoholica" id="alcoholica" value="1" {{ old('alcoholica') ? 'checked' : '' }}>
                        <b>Bebida alcohólica</b>
                    </label>
                </div>
            </div>

            <!-- Campos específicos para Menú -->
            <div id="campos-menu" class="campos-especificos">
                <h3>Datos específicos del Menú</h3>
                <div class="form-group">
                    <label for="descripcion_menu"><b>Descripción del Menú</b></label>
                    <textarea placeholder="Introduce la descripción del menú" name="descripcion_menu" id="descripcion_menu" rows="4">{{ old('descripcion_menu') }}</textarea>
                    @error('descripcion_menu')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <h4>Productos incluidos en el Menú:</h4>
                
                <!-- Pestañas para elegir entre bebidas y comidas -->
                <div class="tabs-container">
                    <div class="tabs">
                        <button type="button" class="tab-btn active" onclick="cambiarTab('bebidas')">Bebidas</button>
                        <button type="button" class="tab-btn" onclick="cambiarTab('comidas')">Comidas</button>
                    </div>
                    
                    <!-- Tab de Bebidas -->
                    <div id="tab-bebidas" class="tab-content active">
                        <div class="form-group">
                            <label for="bebida-select"><b>Seleccionar Bebida</b></label>
                            <select id="bebida-select" class="producto-select">
                                <option value="">Seleccione una bebida</option>
                                <!-- Las opciones se cargarán dinámicamente -->
                            </select>
                            <button type="button" onclick="agregarProductoSeleccionado('B')" class="btn-agregar-seleccionado">Agregar Bebida</button>
                        </div>
                    </div>
                    
                    <!-- Tab de Comidas -->
                    <div id="tab-comidas" class="tab-content">
                        <div class="form-group">
                            <label for="comida-select"><b>Seleccionar Comida</b></label>
                            <select id="comida-select" class="producto-select">
                                <option value="">Seleccione una comida</option>
                                <!-- Las opciones se cargarán dinámicamente -->
                            </select>
                            <button type="button" onclick="agregarProductoSeleccionado('C')" class="btn-agregar-seleccionado">Agregar Comida</button>
                        </div>
                    </div>
                </div>
                
                <!-- Lista de productos seleccionados -->
                <div class="productos-seleccionados-container">
                    <h4>Productos seleccionados:</h4>
                    <div id="productos-seleccionados" class="productos-seleccionados">
                        <p class="empty-message"><i>No hay productos seleccionados</i></p>
                    </div>
                </div>
            </div>

            <button type="submit" class="submit-btn">Crear Producto</button>
            
            <div class="action-links">
                <a href="{{ route('productos.paginate') }}" class="link-back">Volver al listado</a>
            </div>
        </form>
    </div>
</body>
</html>


<!--Entrega2-->