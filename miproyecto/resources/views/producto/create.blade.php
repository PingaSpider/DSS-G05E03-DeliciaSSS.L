<!DOCTYPE html>
<html lang="es">
<head>
    <title>Crear Producto</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pedido/create.css') }}">
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
                <label for="cod"><b>Código del Producto</b></label>
                <input type="text" placeholder="Introduce el código (máx. 5 caracteres)" name="cod" id="cod" value="{{ old('cod') }}" maxlength="5" required>
                <div class="error-message" id="codError">
                    @error('cod')
                        {{ $message }}
                    @enderror
                </div>
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

            <button type="submit" class="submit-btn">Crear Producto</button>
            
            <div class="action-links">
                <a href="{{ route('productos.paginate') }}" class="link-back">Volver al listado</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const codInput = document.getElementById('cod');
            const codError = document.getElementById('codError');
            
            // Verificar el código cuando pierda el foco
            codInput.addEventListener('blur', function() {
                const codigo = this.value.trim();
                
                if (codigo === '') {
                    return;
                }
                
                // Verificar si el código ya existe
                fetch(`{{ route('productos.verificarCodigo') }}?cod=${encodeURIComponent(codigo)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        codError.textContent = 'Este código de producto ya está registrado';
                        codInput.classList.add('error-input');
                    } else {
                        codError.textContent = '';
                        codInput.classList.remove('error-input');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
            
            // Validación del formulario antes de enviar
            document.getElementById('form_producto').addEventListener('submit', function(e) {
                const codigo = codInput.value.trim();
                const nombre = document.getElementById('nombre').value.trim();
                const pvp = document.getElementById('pvp').value.trim();
                const stock = document.getElementById('stock').value.trim();
                const precioCompra = document.getElementById('precioCompra').value.trim();
                let hasErrors = false;
                
                if (codigo === '') {
                    codError.textContent = 'El código del producto es obligatorio';
                    codInput.classList.add('error-input');
                    hasErrors = true;
                }
                
                if (nombre === '') {
                    document.querySelector('#nombre + .error-message').textContent = 'El nombre del producto es obligatorio';
                    document.getElementById('nombre').classList.add('error-input');
                    hasErrors = true;
                }
                
                if (pvp === '' || isNaN(pvp) || Number(pvp) <= 0) {
                    document.querySelector('#pvp + .error-message').textContent = 'El precio de venta debe ser un número positivo';
                    document.getElementById('pvp').classList.add('error-input');
                    hasErrors = true;
                }
                
                if (stock === '' || isNaN(stock) || Number(stock) < 0) {
                    document.querySelector('#stock + .error-message').textContent = 'El stock debe ser un número no negativo';
                    document.getElementById('stock').classList.add('error-input');
                    hasErrors = true;
                }
                
                if (precioCompra === '' || isNaN(precioCompra) || Number(precioCompra) <= 0) {
                    document.querySelector('#precioCompra + .error-message').textContent = 'El precio de compra debe ser un número positivo';
                    document.getElementById('precioCompra').classList.add('error-input');
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