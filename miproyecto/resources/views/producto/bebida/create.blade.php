<!DOCTYPE html>
<html lang="es">
<head>
    <title>Crear Bebida</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pedido/create.css') }}">
</head>
<body>
    <div class="container">
        <h1>Nueva Bebida</h1>
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
        
        <form id="form_bebida" action="{{ route('bebidas.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="nombre"><b>Nombre</b></label>
                <input type="text" placeholder="Introduce el nombre de la bebida" name="nombre" id="nombre" value="{{ old('nombre') }}" required>
                @error('nombre')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="pvp"><b>Precio de Venta (€)</b></label>
                <input type="number" step="0.01" min="0" placeholder="Precio de venta" name="pvp" id="pvp" value="{{ old('pvp') }}" required>
                @error('pvp')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="stock"><b>Stock</b></label>
                <input type="number" min="0" placeholder="Cantidad disponible" name="stock" id="stock" value="{{ old('stock', 0) }}" required>
                @error('stock')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="precioCompra"><b>Precio de Compra (€)</b></label>
                <input type="number" step="0.01" min="0" placeholder="Precio de compra" name="precioCompra" id="precioCompra" value="{{ old('precioCompra') }}" required>
                @error('precioCompra')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="tamanio"><b>Tamaño</b></label>
                <select name="tamanio" id="tamanio" required>
                    <option value="">Seleccione un tamaño</option>
                    <option value="Pequeño" {{ old('tamanio') == 'Pequeño' ? 'selected' : '' }}>Pequeño</option>
                    <option value="Mediano" {{ old('tamanio') == 'Mediano' ? 'selected' : '' }}>Mediano</option>
                    <option value="Grande" {{ old('tamanio') == 'Grande' ? 'selected' : '' }}>Grande</option>
                    <option value="Extra Grande" {{ old('tamanio') == 'Extra Grande' ? 'selected' : '' }}>Extra Grande</option>
                </select>
                @error('tamanio')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="tipo"><b>Tipo de Bebida</b></label>
                <select name="tipo" id="tipo" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="agua" {{ old('tipo') == 'agua' ? 'selected' : '' }}>Agua</option>
                    <option value="refresco" {{ old('tipo') == 'refresco' ? 'selected' : '' }}>Refresco</option>
                    <option value="vino" {{ old('tipo') == 'vino' ? 'selected' : '' }}>Vino</option>
                    <option value="cerveza" {{ old('tipo') == 'cerveza' ? 'selected' : '' }}>Cerveza</option>
                </select>
                @error('tipo')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

             <!-- Elegir imagen -->
             <div class="form-group">
                <label for="imagen_url"><b>Imagen del Producto</b></label>
                <select name="imagen_url" id="imagen_url" class="select-image">
                    <option value="">Seleccionar imagen predeterminada</option>
                    @php
                        $imagePath = public_path('assets/images/comida/bebida');
                        $images = [];
                        if (file_exists($imagePath)) {
                            $files = scandir($imagePath);
                            foreach($files as $file) {
                                $ext = pathinfo($file, PATHINFO_EXTENSION);
                                if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']) && $file != '.' && $file != '..') {
                                    $imageName = pathinfo($file, PATHINFO_FILENAME);
                                    $images[$imageName] = asset('assets/images/comida/bebida/' . $file);
                                }
                            }
                        }
                    @endphp
                    
                    @foreach($images as $name => $url)
                        <option value="{{ $name }}" data-img-src="{{ $url }}">{{ $name }}</option>
                    @endforeach
                </select>
                <div id="image-preview" class="mt-2" style="display: none;">
                    <img src="" alt="Vista previa" style="max-width: 150px; max-height: 150px;">
                </div>
                @error('imagen_url')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="submit-btn">Crear Bebida</button>
            
            <div class="action-links">
                <a href="{{ route('productos.paginate') }}" class="link-back">Volver al listado de productos</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validación del formulario antes de enviar
            document.getElementById('form_bebida').addEventListener('submit', function(e) {
                const nombreInput = document.getElementById('nombre');
                const pvpInput = document.getElementById('pvp');
                const precioCompraInput = document.getElementById('precioCompra');
                const tamanioInput = document.getElementById('tamanio');
                const tipoInput = document.getElementById('tipo');
                const imagenInput = document.getElementById('imagen_url');
                let hasErrors = false;
                
                // Validar nombre
                if (nombreInput.value.trim() === '') {
                    nombreInput.nextElementSibling.textContent = 'El nombre es obligatorio';
                    nombreInput.classList.add('error-input');
                    hasErrors = true;
                }
                
                // Validar precio venta
                if (pvpInput.value <= 0) {
                    pvpInput.nextElementSibling.textContent = 'El precio de venta debe ser mayor que 0';
                    pvpInput.classList.add('error-input');
                    hasErrors = true;
                }
                
                // Validar precio compra
                if (precioCompraInput.value <= 0) {
                    precioCompraInput.nextElementSibling.textContent = 'El precio de compra debe ser mayor que 0';
                    precioCompraInput.classList.add('error-input');
                    hasErrors = true;
                }
                
                // Validar tamaño
                if (tamanioInput.value === '') {
                    tamanioInput.nextElementSibling.textContent = 'Debe seleccionar un tamaño';
                    tamanioInput.classList.add('error-input');
                    hasErrors = true;
                }
                
                // Validar tipo de bebida
                if (tipoInput.value === '') {
                    tipoInput.nextElementSibling.textContent = 'Debe seleccionar un tipo de bebida';
                    tipoInput.classList.add('error-input');
                    hasErrors = true;
                }

                // Validar imagen
                if (imagenInput.value === '') {
                    imagenInput.nextElementSibling.textContent = 'Debe seleccionar una imagen';
                    imagenInput.classList.add('error-input');
                    hasErrors = true;
                }
                
                if (hasErrors) {
                    e.preventDefault();
                }
            });
        });

        // Al cargar el documento, inicializa la vista previa de imagen
        document.addEventListener('DOMContentLoaded', function() {
            const imageSelect = document.getElementById('imagen_url');
            const imagePreview = document.getElementById('image-preview');
            const previewImg = imagePreview.querySelector('img');

            // Mostrar vista previa cuando se selecciona una imagen
            imageSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.hasAttribute('data-img-src')) {
                    previewImg.src = selectedOption.getAttribute('data-img-src');
                    imagePreview.style.display = 'block';
                } else {
                    imagePreview.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>


<!--Entrega2-->