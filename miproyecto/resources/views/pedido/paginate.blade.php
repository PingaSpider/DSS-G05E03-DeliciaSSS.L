<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lista de Pedidos</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pedido/create.css') }}">
</head>
<body>
    <div class="container">
        <h1>Lista de Pedidos</h1>
        
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
            <form action="{{ route('pedidos.paginate') }}" method="GET" class="search-form">
                <div class="search-group">
                    <input type="text" name="search" placeholder="Buscar por código, o cliente..." value="{{ request('search') }}" class="search-input">
                    <input type="hidden" name="sort_by" value="{{ request('sort_by', 'cod') }}">
                    <input type="hidden" name="sort_order" value="{{ request('sort_order', 'asc') }}">
                    <button type="submit" class="search-button">Buscar</button>
                    @if(request('search'))
                        <a href="{{ route('pedidos.paginate', ['sort_by' => request('sort_by'), 'sort_order' => request('sort_order')]) }}" class="search-clear">Limpiar</a>
                    @endif
                </div>
            </form>
        </div>
        
        @if(request('search'))
            <div class="search-results-info">
                Mostrando resultados para: <strong>{{ request('search') }}</strong> 
                ({{ $pedidos->total() }} {{ $pedidos->total() == 1 ? 'resultado' : 'resultados' }})
            </div>
        @endif
        
        <div class="action-links">
            <a href="{{ route('pedidos.create') }}" class="submit-btn" style="display: inline-block; width: auto; text-decoration: none; text-align: center;">Crear Nuevo Pedido</a>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>
                            @php
                                $sortBy = request('sort_by', 'cod');
                                $sortOrder = request('sort_order', 'asc');
                                $newSortOrder = ($sortBy === 'cod' && $sortOrder === 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('pedidos.paginate', ['sort_by' => 'cod', 'sort_order' => $newSortOrder, 'search' => request('search')]) }}">
                                Código {{ $sortBy === 'cod' ? ($sortOrder === 'asc' ? '↑' : '↓') : '' }}
                            </a>
                        </th>
                        <th>
                            @php
                                $newSortOrder = ($sortBy === 'fecha' && $sortOrder === 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('pedidos.paginate', ['sort_by' => 'fecha', 'sort_order' => $newSortOrder, 'search' => request('search')]) }}">
                                Fecha {{ $sortBy === 'fecha' ? ($sortOrder === 'asc' ? '↑' : '↓') : '' }}
                            </a>
                        </th>
                        <th>
                            @php
                                $newSortOrder = ($sortBy === 'usuario_id' && $sortOrder === 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('pedidos.paginate', ['sort_by' => 'usuario_id', 'sort_order' => $newSortOrder, 'search' => request('search')]) }}">
                                Cliente {{ $sortBy === 'usuario_id' ? ($sortOrder === 'asc' ? '↑' : '↓') : '' }}
                            </a>
                        </th>
                        <th>
                            @php
                                $newSortOrder = ($sortBy === 'estado' && $sortOrder === 'asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('pedidos.paginate', ['sort_by' => 'estado', 'sort_order' => $newSortOrder, 'search' => request('search')]) }}">
                                Estado {{ $sortBy === 'estado' ? ($sortOrder === 'asc' ? '↑' : '↓') : '' }}
                            </a>
                        </th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pedidos as $pedido)
                    <tr>
                        <td>{{ $pedido->cod }}</td>
                        <td>{{ date('d/m/Y', strtotime($pedido->fecha)) }}</td>
                        <td>{{ $pedido->usuario->nombre }}</td>
                        <td>
                            <span class="badge 
                                @if($pedido->estado == 'Pendiente') badge-pendiente 
                                @elseif($pedido->estado == 'En proceso') badge-proceso 
                                @elseif($pedido->estado == 'Completado') badge-completado 
                                @elseif($pedido->estado == 'Cancelado') badge-cancelado 
                                @endif">
                                {{ $pedido->estado }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('pedidos.show', $pedido->cod) }}" class="action-btn view-btn">Ver</a>
                            <button type="button" class="action-btn edit-btn" onclick="openEditModal('{{ $pedido->cod }}', '{{ $pedido->fecha }}', '{{ $pedido->estado }}', {{ $pedido->usuario_id }})">Editar</button>
                            <button type="button" class="action-btn delete-btn" onclick="deletePedido('{{ $pedido->cod }}')">Eliminar</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="mt-4 flex justify-center">
            {{ $pedidos->appends(request()->query())->links() }}
        </div>
        <div>
            <a href="{{ url('/') }}" class="action-btn edit-btn">Volver al Panel Admin</a>
        </div>
    </div>

    <!-- Modal para editar pedido -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
            <div class="modal-header">
                <h2>Editar Pedido</h2>
            </div>
            <form id="editPedidoForm" method="POST">
                @csrf
                @method('PUT')
                
                <input type="hidden" id="edit_cod" name="cod">
                
                <div class="form-group">
                    <label for="edit_fecha">Fecha del Pedido</label>
                    <input type="date" id="edit_fecha" name="fecha" required>
                    <div class="error-message" id="fechaError"></div>
                </div>
                
                <div class="form-group">
                    <label for="edit_estado">Estado del Pedido</label>
                    <select id="edit_estado" name="estado" required>
                        <option value="Pendiente">Pendiente</option>
                        <option value="En proceso">En proceso</option>
                        <option value="Completado">Completado</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                    <div class="error-message" id="estadoError"></div>
                </div>
                
                <div class="form-group">
                    <label for="edit_usuario_id">Cliente</label>
                    <select id="edit_usuario_id" name="usuario_id" required>
                        @foreach (\App\Models\Usuario::all() as $usuario)
                            <option value="{{ $usuario->id }}">
                                {{ $usuario->nombre }} ({{ $usuario->email }})
                            </option>
                        @endforeach
                    </select>
                    <div class="error-message" id="usuarioError"></div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Form oculto para eliminar pedido -->
    <form id="deletePedidoForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        // Abrir modal de edición
        function openEditModal(cod, fecha, estado, usuario_id) {
            document.getElementById('edit_cod').value = cod;
            document.getElementById('edit_fecha').value = fecha;
            document.getElementById('edit_estado').value = estado;
            document.getElementById('edit_usuario_id').value = usuario_id;
            
            // Establece la acción del formulario con la ruta correcta
            document.getElementById('editPedidoForm').action = `/pedidos/${cod}`;
            
            // Limpia los mensajes de error
            clearErrors();
            
            // Muestra el modal
            document.getElementById('editModal').style.display = 'block';
        }
        
        // Cerrar modal de edición
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        // Función para eliminar pedido
        function deletePedido(cod) {
            if (confirm('¿Estás seguro de que deseas eliminar este pedido?')) {
                const form = document.getElementById('deletePedidoForm');
                form.action = `/pedidos/${cod}`;
                form.submit();
            }
        }

        // Limpiar mensajes de error
        function clearErrors() {
            document.getElementById('fechaError').textContent = '';
            document.getElementById('estadoError').textContent = '';
            document.getElementById('usuarioError').textContent = '';
        }

        // Validación del formulario de edición
        document.getElementById('editPedidoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            clearErrors();
            
            const fecha = document.getElementById('edit_fecha').value;
            const estado = document.getElementById('edit_estado').value;
            const usuario_id = document.getElementById('edit_usuario_id').value;
            let hasErrors = false;
            
            // Validación básica
            if (fecha.trim() === '') {
                document.getElementById('fechaError').textContent = 'La fecha es obligatoria';
                hasErrors = true;
            }
            
            if (estado.trim() === '') {
                document.getElementById('estadoError').textContent = 'El estado es obligatorio';
                hasErrors = true;
            }
            
            if (usuario_id.trim() === '') {
                document.getElementById('usuarioError').textContent = 'El cliente es obligatorio';
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