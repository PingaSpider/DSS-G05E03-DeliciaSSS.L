<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lista de Reservas</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/reserva/list.css') }}">
</head>
<body>
    <div class="container">
        <h1>Lista de Reservas</h1>
        
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
        
        <!-- Formulario de búsqueda -->
        <div class="search-container">
            <form action="{{ route('reservas.paginate') }}" method="GET" class="search-form">
                <div class="search-group">
                    <input type="text" name="search" placeholder="Buscar por código, cliente o mesa..." value="{{ request('search') }}" class="search-input">
                    <button type="submit" class="search-button">Buscar</button>
                    @if(request('search'))
                        <a href="{{ route('reservas.paginate') }}" class="search-clear">Limpiar</a>
                    @endif
                </div>
            </form>
        </div>
        
        @if(request('search'))
            <div class="search-results-info">
                Mostrando resultados para: <strong>{{ request('search') }}</strong> 
                ({{ $reservas->total() }} {{ $reservas->total() == 1 ? 'resultado' : 'resultados' }})
            </div>
        @endif
        
        <div class="action-links">
            <a href="{{ route('reservas.create') }}" class="submit-btn">Crear Nueva Reserva</a>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Cliente</th>
                        <th>Mesa</th>
                        <th>Personas</th>
                        <th>Confirmada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservas as $reserva)
                    <tr>
                        <td>{{ $reserva->codReserva }}</td>
                        <td>{{ date('d/m/Y', strtotime($reserva->fecha)) }}</td>
                        <td>{{ $reserva->hora }}</td>
                        <td>{{ $reserva->usuario->nombre }}</td>
                        <td>{{ $reserva->mesa_id }}</td>
                        <td>{{ $reserva->cantPersona }}</td>
                        <td>{{ $reserva->reservaConfirmada ? 'Sí' : 'No' }}</td>
                        <td>
                            <a href="{{ route('reservas.show', $reserva->codReserva) }}" class="action-btn view-btn">Ver</a>
                            <button type="button" class="action-btn edit-btn" onclick="openEditModal('{{ $reserva->codReserva }}', '{{ $reserva->fecha }}', '{{ $reserva->hora }}', '{{ $reserva->cantPersona }}', '{{ $reserva->mesa_id }}', '{{ $reserva->usuario_id }}', '{{ $reserva->reservaConfirmada }}')">Editar</button>
                            <button type="button" class="action-btn delete-btn" onclick="deleteReserva('{{ $reserva->codReserva }}')">Eliminar</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="pagination">
            {{ $reservas->appends(request()->query())->links() }}
        </div>
    </div>
</body>
</html>
