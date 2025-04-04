<!DOCTYPE html>
<html lang="es">
<head>
    <title>Editar Comida</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pedido/create.css') }}">
</head>
<body>
    <div class="container">
        <h1>Editar Comida</h1>
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
        
        <form id="form_comida" action="{{ route('comida.update', $producto->cod) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="cod"><b>Código</b></label>
                <input type="text" name="cod" id="cod" value="{{ $producto->cod }}" readonly disabled class="readonly-input">
                <div class="input-info">El código no se puede modificar</div>
            </div>
            
            <div class="form-group">
                <label for="nombre"><b>Nombre</b></label>
                <input type="text" placeholder="Introduce el nombre de la comida" name="nombre" id="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
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
                <textarea name="descripcion" id="descripcion" placeholder="Describe esta comida" rows="4" required>{{ old('descripcion', $comida->descripcion) }}</textarea>
                @error('descripcion')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="submit-btn">Actualizar Comida</button>
            
            <div class="action-links">
                <a href="{{ route('productos.paginate') }}" class="link-back">Volver al listado</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validación del formulario antes de enviar
            document.getElementById('form_comida').addEventListener('submit', function(e) {
                const nombreInput = document.getElementById('nombre');
                const pvpInput = document.getElementById('pvp');
                const precioCompraInput = document.getElementById('precioCompra');
                const descripcionInput = document.getElementById('descripcion');
                let hasErrors = false;
                
                // Validar nombre
                if (nombreInput.value.trim() === '') {
                    nombreInput.nextElementSibling.textContent = 'El nombre es obligatorio';
                    nombreInput.classList.add('error-input');
                    hasErrors = true;
                }
                
                // Validar precio venta
                if (pvpInput.value <= 0) {
                    pvpInput.nextElementSibling.textContent = 'El precio de venta debe ser mayor que 0';
                    pvpInput.classList.add('error-input');
                    hasErrors = true;
                }
                
                // Validar precio compra
                if (precioCompraInput.value <= 0) {
                    precioCompraInput.nextElementSibling.textContent = 'El precio de compra debe ser mayor que 0';
                    precioCompraInput.classList.add('error-input');
                    hasErrors = true;
                }
                
                // Validar descripción
                if (descripcionInput.value.trim() === '') {
                    descripcionInput.nextElementSibling.textContent = 'La descripción es obligatoria';
                    descripcionInput.classList.add('error-input');
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


<!--Entrega2-->