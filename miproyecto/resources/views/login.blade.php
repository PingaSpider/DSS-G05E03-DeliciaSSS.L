<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <!-- Header del formulario de login -->
        <header class="login-header">
            <h2>Iniciar Sesión</h2>
        </header>

        <!-- Formulario de login -->
        <div class="login-form">
            @if(session('status'))
                <div class="alert-success">
                    {{ session('status') }}
                </div>
            @endif
            
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}">
                    @error('email')
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
                                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Iniciar sesión</button>
                </div>
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Recordarme</label>
                </div>
                
                <div class="register-link">
                    ¿No tienes una cuenta?
                    <a href="{{ route('registro.form') }}">Regístrate ahora</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>