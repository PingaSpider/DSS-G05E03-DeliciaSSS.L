<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lista de Mesas</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/paginatemesa.css') }}">
</head>
<body>
    <div class="container">
        <h1>Lista de Mesas</h1>
        
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
            <form action="{{ route('mesas.paginate') }}" method="GET" class="search-form">
                <div class="search-group">
                    <input type="text" name="search" placeholder="Buscar por código o capacidad..." value="{{ request('search') }}" class="search-input">
                    <input type="hidden" name="sort_by" value="{{ request('sort_by', 'codMesa') }}">
                    <input type="hidden" name="sort_order" value="{{ request('sort_order', 'asc') }}">
                    <button type="submit" class="search-button">Buscar</button>
                    @if(request('search'))
                        <a href="{{ route('mesas.paginate', ['sort_by' => request('sort_by'), 'sort_order' => request('sort_order')]) }}" class="search-clear">Limpiar</a>
                    @endif
                </div>
            </form>
        </div>
        
        @if(request('search'))
            <div class="search-results-info">
                Mostrando resultados para: <strong>{{ request('search') }}</strong> 
                ({{ $mesas->total() }} {{ $mesas->total() == 1 ? 'resultado' : 'resultados' }})
            </div>
        @endif
        
        <div class="action-links">
            <a href="{{ route('mesas.create') }}" class="submit-btn" style="display: inline-block; width: auto; text-decoration: none; text-align: center;">Crear Nueva Mesa</a>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>
                            @php
                                $sortBy = request('sort_by', 'codMesa');
                                $sortOrder = request('sort_order', 'asc');
                                $newSortOrder = ($sortBy === 'codMesa' && $sortOrder === 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('mesas.paginate', ['sort_by' => 'codMesa', 'sort_order' => $newSortOrder, 'search' => request('search')]) }}">
                                Código {{ $sortBy === 'codMesa' ? ($sortOrder === 'asc' ? '↑' : '↓') : '' }}
                            </a>
                        </th>
                        <th>
                            @php
                                $newSortOrder = ($sortBy === 'cantidadMesa' && $sortOrder === 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('mesas.paginate', ['sort_by' => 'cantidadMesa', 'sort_order' => $newSortOrder, 'search' => request('search')]) }}">
                                Capacidad {{ $sortBy === 'cantidadMesa' ? ($sortOrder === 'asc' ? '↑' : '↓') : '' }}
                            </a>
                        </th>
                        <th>
                            @php
                                $newSortOrder = ($sortBy === 'ocupada' && $sortOrder === 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('mesas.paginate', ['sort_by' => 'ocupada', 'sort_order' => $newSortOrder, 'search' => request('search')]) }}">
                                Estado {{ $sortBy === 'ocupada' ? ($sortOrder === 'asc' ? '↑' : '↓') : '' }}
                            </a>
                        </th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mesas as $mesa)
                    <tr>
                        <td>{{ $mesa->codMesa }}</td>
                        <td>{{ $mesa->cantidadMesa }} personas</td>
                        <td>
                            <span class="status-badge {{ $mesa->ocupada ? 'status-ocupada' : 'status-libre' }}">
                                {{ $mesa->ocupada ? 'Ocupada' : 'Libre' }}
                            </span>
                        </td>
                        <td>
                            <button type="button" class="action-btn edit-btn" onclick="openEditModal('{{ $mesa->codMesa }}', {{ $mesa->cantidadMesa }}, {{ $mesa->ocupada }})">Editar</button>
                            <button type="button" class="action-btn delete-btn" onclick="deleteMesa('{{ $mesa->codMesa }}')">Eliminar</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="mt-4 flex justify-center">
            {{ $mesas->appends(request()->query())->links() }}
        </div>
        <div>
            <a href="{{ url('/') }}" class="action-btn edit-btn">Volver al Panel Admin</a>
        </div>
    </div>

    <!-- Modal para editar mesa -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
            <div class="modal-header">
                <h2>Editar Mesa</h2>
            </div>
            <form id="editMesaForm" method="POST">
                @csrf
                @method('PUT')
                
                <input type="hidden" id="edit_codMesa" name="codMesa">
                
                <div class="form-group">
                    <label for="edit_cantidadMesa">Capacidad de la Mesa</label>
                    <input type="number" id="edit_cantidadMesa" name="cantidadMesa" min="1" required>
                    <div class="error-message" id="cantidadError"></div>
                </div>
                
                <div class="form-group checkbox-group">
                    <label>Estado de la Mesa</label>
                    <div class="radio-options">
                        <div class="radio-option">
                            <input type="radio" id="edit_ocupada_no" name="ocupada" value="0">
                            <label for="edit_ocupada_no">Libre</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="edit_ocupada_si" name="ocupada" value="1">
                            <label for="edit_ocupada_si">Ocupada</label>
                        </div>
                    </div>
                    <div class="error-message" id="ocupadaError"></div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Form oculto para eliminar mesa -->
    <form id="deleteMesaForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        // Abrir modal de edición
        function openEditModal(codMesa, cantidadMesa, ocupada) {
            document.getElementById('edit_codMesa').value = codMesa;
            document.getElementById('edit_cantidadMesa').value = cantidadMesa;
            
            // Establecer estado de ocupación
            if (ocupada == 1) {
                document.getElementById('edit_ocupada_si').checked = true;
            } else {
                document.getElementById('edit_ocupada_no').checked = true;
            }
            
            // Establece la acción del formulario con la ruta correcta
            document.getElementById('editMesaForm').action = `/mesas/${codMesa}`;
            
            // Limpia los mensajes de error
            clearErrors();
            
            // Muestra el modal
            document.getElementById('editModal').style.display = 'block';
        }
        
        // Cerrar modal de edición
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        // Función para eliminar mesa
        function deleteMesa(codMesa) {
            if (confirm('¿Estás seguro de que deseas eliminar esta mesa?')) {
                const form = document.getElementById('deleteMesaForm');
                form.action = `/mesas/${codMesa}`;
                form.submit();
            }
        }

        // Limpiar mensajes de error
        function clearErrors() {
            document.getElementById('cantidadError').textContent = '';
            document.getElementById('ocupadaError').textContent = '';
        }

        // Validación del formulario de edición
        document.getElementById('editMesaForm').addEventListener('submit', function(e) {
            e.preventDefault();
            clearErrors();
            
            const cantidadMesa = document.getElementById('edit_cantidadMesa').value;
            let hasErrors = false;
            
            // Validación básica
            if (cantidadMesa.trim() === '' || cantidadMesa < 1) {
                document.getElementById('cantidadError').textContent = 'La capacidad debe ser al menos 1';
                hasErrors = true;
            }
            
            if (hasErrors) {
                return;
            }
            
            // Si todo está correcto, enviar el formulario
            this.submit();
        });

        // Cerrar el modal si se hace clic fuera de él
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeEditModal();
            }
        }
    </script>
</body>
</html>


<!--Entrega2-->