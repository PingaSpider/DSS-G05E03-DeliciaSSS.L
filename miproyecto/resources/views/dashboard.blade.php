<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .user-info {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #fff2d6;
            border-radius: 4px;
        }
        
        .logout-form {
            display: inline;
        }
        
        .btn-logout {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .btn-logout:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Bienvenido a tu Dashboard</h1>
            
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="btn-logout">Cerrar sesión</button>
            </form>
        </div>
        
        @if(session('status'))
            <div class="alert-success" style="padding: 10px; margin-bottom: 20px; background-color: #d4edda; color: #155724; border-radius: 4px;">
                {{ session('status') }}
            </div>
        @endif
        
        <div class="user-info">
            <h2>Información del usuario</h2>
            <p><strong>Nombre:</strong> {{ Auth::user()->nombre }}</p>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            @if(Auth::user()->telefono)
                <p><strong>Teléfono:</strong> {{ Auth::user()->telefono }}</p>
            @endif
        </div>
        
        <div class="content-section">
            <h2>Tu Contenido</h2>
            <p>Aquí puedes mostrar el contenido personalizado para el usuario.</p>
        </div>
    </div>
</body>
</html>