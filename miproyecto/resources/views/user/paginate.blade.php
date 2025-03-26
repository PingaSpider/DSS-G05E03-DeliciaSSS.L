<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lista de Usuarios</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/paginate.css') }}">
</head>
<body>
    <div class="container">
        <h1>Lista de Usuarios</h1>
        
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
            <form action="{{ route('usuarios.paginate') }}" method="GET" class="search-form">
                <div class="search-group">
                    <input type="text" name="search" placeholder="Buscar por nombre, email o teléfono..." value="{{ request('search') }}" class="search-input">
                    <button type="submit" class="search-button">Buscar</button>
                    @if(request('search'))
                        <a href="{{ route('usuarios.paginate') }}" class="search-clear">Limpiar</a>
                    @endif
                </div>
            </form>
        </div>
        
        @if(request('search'))
            <div class="search-results-info">
                Mostrando resultados para: <strong>{{ request('search') }}</strong> 
                ({{ $usuarios->total() }} {{ $usuarios->total() == 1 ? 'resultado' : 'resultados' }})
            </div>
        @endif
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $user)
                    <tr>
                        <td>{{ $user->nombre }}</td>
                        <td>{{ $user->email }}</td>
                        <td>+{{ $user->telefono }}</td>
                        <td>
                            <button type="button" class="action-btn edit-btn" onclick="openEditModal({{ $user->id }}, '{{ $user->nombre }}', '{{ $user->email }}', '{{ $user->telefono }}')">Editar</button>
                            <button type="button" class="action-btn delete-btn" onclick="deleteUser({{ $user->id }})">Eliminar</button>
                        </td>
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

    <!-- Modal para editar usuario -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeEditModal()">&times;</span>
            <div class="modal-header">
                <h2>Editar Usuario</h2>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="userId" name="userId">
                
                <div class="form-group">
                    <label for="edit_nombre">Nombre y Apellidos</label>
                    <input type="text" id="edit_nombre" name="nombre" required>
                    <div class="error-message" id="nombreError"></div>
                </div>
                
                <div class="form-group">
                    <label for="edit_email">Correo Electrónico</label>
                    <input type="email" id="edit_email" name="email" required>
                    <div class="error-message" id="emailError"></div>
                </div>
                
                <div class="form-group">
                    <label for="edit_telefono">Teléfono</label>
                    <input type="text" id="edit_telefono" name="telefono" required>
                    <div class="error-message" id="telefonoError"></div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Form oculto para eliminar usuario -->
    <form id="deleteUserForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        // Abrir modal de edición
        function openEditModal(id, nombre, email, telefono) {
            document.getElementById('userId').value = id;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_telefono').value = telefono;
            
            // Establece la acción del formulario con la ruta correcta
            document.getElementById('editUserForm').action = `/usuarios/${id}`;
            
            // Limpia los mensajes de error
            clearErrors();
            
            // Muestra el modal
            document.getElementById('editModal').style.display = 'block';
        }
        
        // Cerrar modal de edición
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        // Función para eliminar usuario
        function deleteUser(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                const form = document.getElementById('deleteUserForm');
                form.action = `/usuarios/${id}`;
                form.submit();
            }
        }

        // Limpiar mensajes de error
        function clearErrors() {
            document.getElementById('nombreError').textContent = '';
            document.getElementById('emailError').textContent = '';
            document.getElementById('telefonoError').textContent = '';
        }

        // Validación del formulario de edición
        document.getElementById('editUserForm').addEventListener('submit', function(e) {
            e.preventDefault();
            clearErrors();
            
            const userId = document.getElementById('userId').value;
            const email = document.getElementById('edit_email').value;
            const nombre = document.getElementById('edit_nombre').value;
            const telefono = document.getElementById('edit_telefono').value;
            let hasErrors = false;
            
            // Validación básica
            if (nombre.trim() === '') {
                document.getElementById('nombreError').textContent = 'El nombre es obligatorio';
                hasErrors = true;
            }
            
            if (email.trim() === '') {
                document.getElementById('emailError').textContent = 'El correo es obligatorio';
                hasErrors = true;
            }
            
            if (telefono.trim() === '') {
                document.getElementById('telefonoError').textContent = 'El teléfono es obligatorio';
                hasErrors = true;
            }
            
            if (hasErrors) {
                return;
            }
            
            // Verificar si el email ya existe (excluyendo el usuario actual)
            fetch(`{{ route('usuarios.verificar-email') }}?email=${encodeURIComponent(email)}&userId=${userId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    document.getElementById('emailError').textContent = 'Este correo ya está registrado por otro usuario';
                } else {
                    // Si el email es válido, enviar el formulario
                    this.submit();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
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