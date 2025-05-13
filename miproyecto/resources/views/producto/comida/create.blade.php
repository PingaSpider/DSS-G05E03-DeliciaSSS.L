<!DOCTYPE html>
<html lang="es">
<head>
    <title>Crear Comida</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pedido/create.css') }}">
</head>
<body>
    <div class="container">
        <h1>Nueva Comida</h1>
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
        
        <form id="form_comida" action="{{ route('comida.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="nombre"><b>Nombre</b></label>
                <input type="text" placeholder="Introduce el nombre de la comida" name="nombre" id="nombre" value="{{ old('nombre') }}" required>
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
            <!-- Selección de categoría y URL de imagen -->
            <div class="form-group">
                <label for="imagen_categoria"><b>Categoría de la Imagen</b></label>
                <select name="imagen_categoria" id="imagen_categoria" class="select-category">
                    <option value="">Seleccionar categoría</option>
                    <option value="hamburguesa">Hamburguesa</option>
                    <option value="pizza">Pizza</option>
                    <option value="desayuno">Desayuno</option>
                    <option value="postre">Postre</option>
                    <option value="comida">Otra comida</option>
                </select>
            </div>

            <div class="form-group">
                <label for="imagen_url"><b>Imagen del Producto</b></label>
                <select name="imagen_url" id="imagen_url" class="select-image" disabled>
                    <option value="">Primero seleccione una categoría</option>
                </select>
                <div id="image-preview" class="mt-2" style="display: none;">
                    <img src="" alt="Vista previa" style="max-width: 150px; max-height: 150px;">
                </div>
                @error('imagen_url')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="descripcion"><b>Descripción</b></label>
                <textarea name="descripcion" id="descripcion" placeholder="Describe esta comida" rows="4" required>{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="submit-btn">Crear Comida</button>
            
            <div class="action-links">
                <a href="{{ route('productos.paginate') }}" class="link-back">Volver al listado de productos</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validación del formulario antes de enviar
            document.getElementById('form_comida').addEventListener('submit', function(e) {
                const nombreInput = document.getElementById('nombre');
                const pvpInput = document.getElementById('pvp');
                const precioCompraInput = document.getElementById('precioCompra');
                const descripcionInput = document.getElementById('descripcion');
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
                
                // Validar descripción
                if (descripcionInput.value.trim() === '') {
                    descripcionInput.nextElementSibling.textContent = 'La descripción es obligatoria';
                    descripcionInput.classList.add('error-input');
                    hasErrors = true;
                }
                
                if (hasErrors) {
                    e.preventDefault();
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const categoriaSelect = document.getElementById('imagen_categoria');
            const imageSelect = document.getElementById('imagen_url');
            const imagePreview = document.getElementById('image-preview');
            const previewImg = imagePreview.querySelector('img');
            
            // Mapa de imágenes por categoría (esto será cargado mediante AJAX)
            const imagesByCategory = {};
            
            // Función para cargar imágenes de una categoría específica
            function loadImagesForCategory(category) {
                if (!category) {
                    imageSelect.innerHTML = '<option value="">Primero seleccione una categoría</option>';
                    imageSelect.disabled = true;
                    return;
                }
                
                // Si ya tenemos imágenes cargadas para esta categoría, las usamos
                if (imagesByCategory[category]) {
                    populateImageSelect(category, imagesByCategory[category]);
                    return;
                }
                
                // Sino, hacemos una solicitud AJAX para obtener las imágenes
                fetch(`/api/comida/images/${category}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            imagesByCategory[category] = data.images;
                            populateImageSelect(category, data.images);
                        } else {
                            console.error('Error al cargar imágenes:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error en la solicitud:', error);
                    });
            }
            
            // Función para llenar el selector de imágenes
            function populateImageSelect(category, images) {
                imageSelect.innerHTML = '';
                
                // Opción predeterminada
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Seleccionar imagen';
                imageSelect.appendChild(defaultOption);
                
                // Agregar opciones de imagen
                if (images && images.length > 0) {
                    images.forEach(image => {
                        const option = document.createElement('option');
                        option.value = image.name;
                        option.textContent = image.name;
                        option.setAttribute('data-img-src', image.url);
                        imageSelect.appendChild(option);
                    });
                    imageSelect.disabled = false;
                } else {
                    const noImagesOption = document.createElement('option');
                    noImagesOption.value = '';
                    noImagesOption.textContent = 'No hay imágenes disponibles para esta categoría';
                    imageSelect.appendChild(noImagesOption);
                    imageSelect.disabled = true;
                }
            }
            
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
            
            // Cargar imágenes cuando cambia la categoría
            categoriaSelect.addEventListener('change', function() {
                const category = this.value;
                loadImagesForCategory(category);
                imagePreview.style.display = 'none';
            });
        });
    </script>
</body>
</html>


<!--Entrega2-->