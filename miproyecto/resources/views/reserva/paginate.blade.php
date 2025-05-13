<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lista de Reservas</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/lineapedido/create.css') }}">
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
                    <input type="hidden" name="sort_by" value="{{ request('sort_by', 'fecha') }}">
                    <input type="hidden" name="sort_order" value="{{ request('sort_order', 'asc') }}">
                    <button type="submit" class="search-button">Buscar</button>
                    @if(request('search'))
                        <a href="{{ route('reservas.paginate', ['sort_by' => request('sort_by'), 'sort_order' => request('sort_order')]) }}" class="search-clear">Limpiar</a>
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
                        <th>
                            @php
                                $sortBy = request('sort_by', 'fecha');
                                $sortOrder = request('sort_order', 'asc');
                                $newSortOrder = ($sortBy === 'codReserva' && $sortOrder === 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('reservas.paginate', ['sort_by' => 'codReserva', 'sort_order' => $newSortOrder, 'search' => request('search')]) }}">
                                Código {{ $sortBy === 'codReserva' ? ($sortOrder === 'asc' ? '↑' : '↓') : '' }}
                            </a>
                        </th>
                        <th>
                            @php
                                $newSortOrder = ($sortBy === 'fecha' && $sortOrder === 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('reservas.paginate', ['sort_by' => 'fecha', 'sort_order' => $newSortOrder, 'search' => request('search')]) }}">
                                Fecha {{ $sortBy === 'fecha' ? ($sortOrder === 'asc' ? '↑' : '↓') : '' }}
                            </a>
                        </th>
                        <th>
                            @php
                                $newSortOrder = ($sortBy === 'hora' && $sortOrder === 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('reservas.paginate', ['sort_by' => 'hora', 'sort_order' => $newSortOrder, 'search' => request('search')]) }}">
                                Hora {{ $sortBy === 'hora' ? ($sortOrder === 'asc' ? '↑' : '↓') : '' }}
                            </a>
                        </th>
                        <th>
                           Cliente
                        </th>
                        <th>
                            @php
                                $newSortOrder = ($sortBy === 'mesa_id' && $sortOrder === 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('reservas.paginate', ['sort_by' => 'mesa_id', 'sort_order' => $newSortOrder, 'search' => request('search')]) }}">
                                Mesa {{ $sortBy === 'mesa_id' ? ($sortOrder === 'asc' ? '↑' : '↓') : '' }}
                            </a>
                        </th>
                        <th>
                            @php
                                $newSortOrder = ($sortBy === 'cantPersona' && $sortOrder === 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('reservas.paginate', ['sort_by' => 'cantPersona', 'sort_order' => $newSortOrder, 'search' => request('search')]) }}">
                                Personas {{ $sortBy === 'cantPersona' ? ($sortOrder === 'asc' ? '↑' : '↓') : '' }}
                            </a>
                        </th>
                        <th>
                            @php
                                $newSortOrder = ($sortBy === 'reservaConfirmada' && $sortOrder === 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('reservas.paginate', ['sort_by' => 'reservaConfirmada', 'sort_order' => $newSortOrder, 'search' => request('search')]) }}">
                                Confirmada {{ $sortBy === 'reservaConfirmada' ? ($sortOrder === 'asc' ? '↑' : '↓') : '' }}
                            </a>
                        </th>
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
                        <td>
                            @if(isset($reserva->mesa))
                                Mesa {{ $reserva->mesa->codMesa }}
                            @else
                                {{ $reserva->mesa_id }}
                            @endif
                        </td>
                        <td>{{ $reserva->cantPersona }}</td>
                        <td>
                            <span class="{{ $reserva->reservaConfirmada ? 'confirmada' : 'no-confirmada' }}">
                                {{ $reserva->reservaConfirmada ? 'Sí' : 'No' }}
                            </span>
                            
                            <!-- Nota: Estos botones tendrán que agregarse a web.php como rutas adicionales -->
                            @if(!$reserva->reservaConfirmada)
                                <a href="{{ url('/reservas/'.$reserva->codReserva.'/confirmar') }}" class="accion-rapida confirmar-btn">Confirmar</a>
                            @else
                                <a href="{{ url('/reservas/'.$reserva->codReserva.'/cancelar') }}" class="accion-rapida pendiente-btn">Pendiente</a>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('reservas.show', $reserva->id) }}" class="action-btn view-btn">Ver</a>
                            <a href="{{ route('reservas.edit', $reserva->id) }}" class="action-btn edit-btn">Editar</a>
                            <button type="button" class="action-btn delete-btn" onclick="deleteReserva('{{ $reserva->id }}')">Eliminar</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="mt-4 flex justify-center">
            {{ $reservas->appends(request()->query())->links() }}
        </div>
        
        <div class="action-links mt-4">
            <a href="{{ url('/admin/') }}" class="action-btn edit-btn">Volver al Panel Admin</a>
        </div>
    </div>

    <!-- Form oculto para eliminar reserva -->
    <form id="deleteReservaForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        // Función para eliminar reserva
        function deleteReserva(codReserva) {
            if (confirm('¿Estás seguro de que deseas eliminar esta reserva?')) {
                const form = document.getElementById('deleteReservaForm');
                form.action = `/admin/reservas/${codReserva}`;
                form.submit();
            }
        }
    </script>
</body>
</html>


<!--Entrega2-->