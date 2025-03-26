<!DOCTYPE html>
<html lang="es">
<head>
    <title>Crear Línea de Pedido</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/lineaPedido.css') }}">
</head>
<body>
    <div class="container">
        <h1>Nueva Línea de Pedido</h1>
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
        
        <form id="form_lineaPedido" action="{{ route('lineaPedidos.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="linea"><b>Código de Línea</b></label>
                <input type="text" placeholder="Introduce el código de línea" name="linea" id="linea" value="{{ old('linea') }}" required>
                <div class="error-message" id="lineaError">
                    @error('linea')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="pedido_id"><b>Pedido</b></label>
                <select name="pedido_id" required>
                    <option value="">Selecciona un pedido</option>
                    @foreach ($pedidos as $pedido)
                        <option value="{{ $pedido->cod }}" {{ old('pedido_id') == $pedido->cod ? 'selected' : '' }}>
                            {{ $pedido->cod }} - {{ date('d/m/Y', strtotime($pedido->fecha)) }} - {{ $pedido->usuario->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('pedido_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="producto_id"><b>Producto</b></label>
                <select name="producto_id" id="producto_id" required>
                    <option value="">Selecciona un producto</option>
                    @foreach ($productos as $producto)
                        <option value="{{ $producto->cod }}" data-precio="{{ $producto->precio }}" {{ old('producto_id') == $producto->cod ? 'selected' : '' }}>
                            {{ $producto->nombre }} - {{ number_format($producto->precio, 2, ',', '.') }} €
                        </option>
                    @endforeach
                </select>
                @error('producto_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="cantidad"><b>Cantidad</b></label>
                <input type="number" name="cantidad" id="cantidad" min="1" value="{{ old('cantidad', 1) }}" required>
                @error('cantidad')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="precio"><b>Precio Unitario (€)</b></label>
                <input type="number" name="precio" id="precio" step="0.01" min="0" value="{{ old('precio') }}" required>
                @error('precio')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label><b>Subtotal</b></label>
                <div class="subtotal-display" id="subtotal">0,00 €</div>
            </div>

            <button type="submit" class="submit-btn">Crear Línea de Pedido</button>
            
            <div class="action-links">
                <a href="{{ route('lineaPedidos.paginate') }}" class="link-back">Volver al listado</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lineaInput = document.getElementById('linea');
            const lineaError = document.getElementById('lineaError');
            const productoSelect = document.getElementById('producto_id');
            const cantidadInput = document.getElementById('cantidad');
            const precioInput = document.getElementById('precio');
            const subtotalDisplay = document.getElementById('subtotal');
            
            // Generar un código de línea aleatorio por defecto
            if (lineaInput.value === '') {
                lineaInput.value = 'LIN' + Math.floor(Math.random() * 10000).toString().padStart(5, '0');
            }
            
            // Verificar el código cuando pierda el foco
            lineaInput.addEventListener('blur', function() {
                const linea = this.value.trim();
                
                if (linea === '') {
                    return;
                }
                
                // Verificar si el código ya existe
                fetch(`{{ route('lineaPedidos.verificar-codigo') }}?linea=${encodeURIComponent(linea)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        lineaError.textContent = 'Este código de línea ya está registrado';
                        lineaInput.classList.add('error-input');
                    } else {
                        lineaError.textContent = '';
                        lineaInput.classList.remove('error-input');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
            
            // Actualizar precio cuando se selecciona un producto
            productoSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value !== '') {
                    const precio = selectedOption.getAttribute('data-precio');
                    precioInput.value = precio;
                    updateSubtotal();
                }
            });
            
            // Actualizar subtotal cuando cambia la cantidad o precio
            cantidadInput.addEventListener('input', updateSubtotal);
            precioInput.addEventListener('input', updateSubtotal);
            
            function updateSubtotal() {
                const cantidad = parseFloat(cantidadInput.value) || 0;
                const precio = parseFloat(precioInput.value) || 0;
                const subtotal = cantidad * precio;
                subtotalDisplay.textContent = subtotal.toLocaleString('es-ES', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' €';
            }
            
            // Actualizar subtotal inicial
            updateSubtotal();
            
            // Validación del formulario antes de enviar
            document.getElementById('form_lineaPedido').addEventListener('submit', function(e) {
                const linea = lineaInput.value.trim();
                let hasErrors = false;
                
                if (linea === '') {
                    lineaError.textContent = 'El código de línea es obligatorio';
                    lineaInput.classList.add('error-input');
                    hasErrors = true;
                }
                
                if (hasErrors) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>