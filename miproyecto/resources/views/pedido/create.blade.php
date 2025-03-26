<!DOCTYPE html>
<html lang="es">
<head>
    <title>Crear Pedido</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pedido/create.css') }}">
</head>
<body>
    <div class="container">
        <h1>Nuevo Pedido</h1>
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
        
        <form id="form_pedido" action="{{ route('pedidos.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="cod"><b>Código del Pedido</b></label>
                <input type="text" placeholder="Introduce el código (máx. 5 caracteres)" name="cod" id="cod" value="{{ old('cod') }}" maxlength="5" required>
                <div class="error-message" id="codError">
                    @error('cod')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="fecha"><b>Fecha del Pedido</b></label>
                <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
                @error('fecha')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="estado"><b>Estado del Pedido</b></label>
                <select name="estado" required>
                    <option value="Pendiente" {{ old('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="En proceso" {{ old('estado') == 'En proceso' ? 'selected' : '' }}>En proceso</option>
                    <option value="Completado" {{ old('estado') == 'Completado' ? 'selected' : '' }}>Completado</option>
                    <option value="Cancelado" {{ old('estado') == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
                @error('estado')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="usuario_id"><b>Cliente</b></label>
                <select name="usuario_id" required>
                    <option value="">Selecciona un cliente</option>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ old('usuario_id') == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->nombre }} ({{ $usuario->email }})
                        </option>
                    @endforeach
                </select>
                @error('usuario_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="submit-btn">Crear Pedido</button>
            
            <div class="action-links">
                <a href="{{ route('pedidos.paginate') }}" class="link-back">Volver al listado</a>
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
                fetch(`{{ route('pedidos.verificar-codigo') }}?cod=${encodeURIComponent(codigo)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        codError.textContent = 'Este código de pedido ya está registrado';
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
            document.getElementById('form_pedido').addEventListener('submit', function(e) {
                const codigo = codInput.value.trim();
                let hasErrors = false;
                
                if (codigo === '') {
                    codError.textContent = 'El código del pedido es obligatorio';
                    codInput.classList.add('error-input');
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