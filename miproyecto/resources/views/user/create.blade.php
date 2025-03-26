<!DOCTYPE html>
<html lang="es">
<head>
    <title>Crear usuario</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">
</head>
<body>
    <div class="container">
        <h1>Nuevo Usuario</h1>
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
        
        <form id="form_user" action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <label for="nombre"><b>Nombre y Apellidos</b></label>
            <input type="text" placeholder="Introduce tu nombre y apellidos" name="nombre" value="{{ old('nombre') }}" required>
            @error('nombre')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <label for="email"><b>Email</b></label>
            <input type="email" placeholder="Introduce tu email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <label for="telefono"><b>Teléfono</b></label>
            <input type="text" placeholder="Introduce tu teléfono" name="telefono" value="{{ old('telefono') }}" required>
            @error('telefono')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <label for="password"><b>Contraseña</b></label>
            <input type="password" placeholder="Introduce tu contraseña" name="password" required>
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <button type="submit" class="registerbtn">Registrar</button>
        </form>
    </div>
</body>
</html>