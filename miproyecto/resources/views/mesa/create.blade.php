<!DOCTYPE html>
<html lang="es">
<head>
    <title>Crear Mesa</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/createmesa.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container">
        <h1>Nueva Mesa</h1>
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
        
        <form id="form_mesa" action="{{ route('mesas.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="codMesa"><b>Código de Mesa</b></label>
                <input type="text" placeholder="Introduce el código de la mesa (máx. 5 caracteres)" name="codMesa" id="codMesa" value="{{ old('codMesa') }}" maxlength="5" required>
                <div class="error-message" id="codMesaError">
                    @error('codMesa')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="cantidadMesa"><b>Capacidad de la Mesa</b></label>
                <input type="number" placeholder="Introduce la cantidad de personas" name="cantidadMesa" min="1" value="{{ old('cantidadMesa', 2) }}" required>
                @error('cantidadMesa')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group checkbox-group">
                <label><b>Estado de la Mesa</b></label>
                <div class="radio-options">
                    <div class="radio-option">
                        <input type="radio" id="ocupada_no" name="ocupada" value="0" {{ old('ocupada') == '0' ? 'checked' : '' }} checked>
                        <label for="ocupada_no">Libre</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" id="ocupada_si" name="ocupada" value="1" {{ old('ocupada') == '1' ? 'checked' : '' }}>
                        <label for="ocupada_si">Ocupada</label>
                    </div>
                </div>
                @error('ocupada')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="submit-btn">Crear Mesa</button>
            
            <div class="action-links">
                <a href="{{ route('mesas.paginate') }}" class="link-back">Volver al listado</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const codMesaInput = document.getElementById('codMesa');
            const codMesaError = document.getElementById('codMesaError');
            
            codMesaInput.addEventListener('blur', function() {
                const codigo = this.value.trim();
                
                if (codigo === '') {
                    return;
                }
                
                // Verificar si el código ya existe
                fetch(`{{ route('mesas.verificar-codigo') }}?codMesa=${encodeURIComponent(codigo)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        codMesaError.textContent = 'Este código de mesa ya está registrado';
                        codMesaInput.classList.add('error-input');
                    } else {
                        codMesaError.textContent = '';
                        codMesaInput.classList.remove('error-input');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
            
            // Validación del formulario antes de enviar
            document.getElementById('form_mesa').addEventListener('submit', function(e) {
                const codigo = codMesaInput.value.trim();
                let hasErrors = false;
                
                if (codigo === '') {
                    codMesaError.textContent = 'El código de mesa es obligatorio';
                    codMesaInput.classList.add('error-input');
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