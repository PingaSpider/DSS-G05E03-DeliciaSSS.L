<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link rel="stylesheet" href="{{ asset('css/registro.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
</head>
<body>
    <div class="registro-container">
        <!-- Header del formulario de registro -->
        <header class="registro-header">
            <h2>Crear cuenta</h2>
        </header>

        <!-- Formulario de registro -->
        <div class="registro-form">
            <h3 class="form-title">Información personal</h3>
            
            <form class=registro-formulario  >
                @csrf
                
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}">
                    @error('nombre')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}">
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono (opcional)</label>
                    <input type="tel" id="telefono" name="telefono" value="{{ old('telefono') }}">
                    @error('telefono')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password">
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Confirmar contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Crear cuenta</button>
                </div>
                
                <div class="login-link">
                    ¿Ya tienes una cuenta? <a href="{{ route('login') }}">Iniciar sesión</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>