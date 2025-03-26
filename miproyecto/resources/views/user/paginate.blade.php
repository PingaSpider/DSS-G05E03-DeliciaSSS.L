<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de Usuarios</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/paginate.css') }}">
</head>
<body>
    <div class="container">
        <h1>Lista de Usuarios</h1>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $user)
                    <tr>
                        <td>{{ $user->nombre }}</td>
                        <td>{{ $user->email }}</td>
                        <td>+{{ $user->telefono }}</td>
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="mt-4 flex justify-center">
            {{ $usuarios->links() }}
        </div>
    </div>
</body>
</html>