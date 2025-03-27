// Abrir modal de edición
function openEditModal(cod, nombre, pvp, stock, precioCompra) {
    document.getElementById('edit_cod').value = cod;
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_pvp').value = pvp;
    document.getElementById('edit_stock').value = stock;
    document.getElementById('edit_precioCompra').value = precioCompra;
    
    // Determinar el tipo de producto basado en el código
    const tipo = cod.charAt(0);
    document.getElementById('edit_tipo').value = tipo;
    
    // Ocultar todos los campos específicos primero
    document.getElementById('edit_campos_comida').style.display = 'none';
    document.getElementById('edit_campos_bebida').style.display = 'none';
    document.getElementById('edit_campos_menu').style.display = 'none';
    
    // Cargar los datos específicos del producto según su tipo
    if (tipo === 'C') {
        document.getElementById('edit_campos_comida').style.display = 'block';
        cargarDatosComida(cod);
    } else if (tipo === 'B') {
        document.getElementById('edit_campos_bebida').style.display = 'block';
        cargarDatosBebida(cod);
    } else if (tipo === 'M') {
        document.getElementById('edit_campos_menu').style.display = 'block';
        cargarDatosMenu(cod);
    }
    
    // Establece la acción del formulario con la ruta correcta
    document.getElementById('editProductoForm').action = `/productos/${cod}`;
    
    // Limpia los mensajes de error
    clearErrors();
    
    // Muestra el modal
    document.getElementById('editModal').style.display = 'block';
}

// Cargar datos específicos de comida
function cargarDatosComida(cod) {
    fetch(`/productos/${cod}/datos-especificos?tipo=comida`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.comida) {
            document.getElementById('edit_descripcion').value = data.comida.descripcion || '';
        }
    })
    .catch(error => {
        console.error('Error al cargar datos de comida:', error);
    });
}

// Cargar datos específicos de bebida
function cargarDatosBebida(cod) {
    fetch(`/productos/${cod}/datos-especificos?tipo=bebida`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.bebida) {
            document.getElementById('edit_tamanio').value = data.bebida.tamanio || '';
            document.getElementById('edit_tipoBebida').value = data.bebida.tipoBebida || '';
            document.getElementById('edit_alcoholica').checked = data.bebida.alcoholica ? true : false;
        }
    })
    .catch(error => {
        console.error('Error al cargar datos de bebida:', error);
    });
}

// Cargar datos específicos de menú
function cargarDatosMenu(cod) {
    fetch(`/productos/${cod}/datos-especificos?tipo=menu`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.menu) {
            document.getElementById('edit_descripcion_menu').value = data.menu.descripcion || '';
        }
    })
    .catch(error => {
        console.error('Error al cargar datos de menú:', error);
    });
}

// Cerrar modal de edición
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Función para eliminar producto
function deleteProducto(cod) {
    if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
        const form = document.getElementById('deleteProductoForm');
        form.action = `/productos/${cod}`;
        form.submit();
    }
}

// Limpiar mensajes de error
function clearErrors() {
    document.getElementById('nombreError').textContent = '';
    document.getElementById('pvpError').textContent = '';
    document.getElementById('stockError').textContent = '';
    document.getElementById('precioCompraError').textContent = '';
    
    // Errores de campos específicos
    const errorElements = document.querySelectorAll('.error-message');
    errorElements.forEach(element => {
        element.textContent = '';
    });
}

// Validación del formulario de edición
document.getElementById('editProductoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    clearErrors();
    
    const nombre = document.getElementById('edit_nombre').value;
    const pvp = document.getElementById('edit_pvp').value;
    const stock = document.getElementById('edit_stock').value;
    const precioCompra = document.getElementById('edit_precioCompra').value;
    const tipo = document.getElementById('edit_tipo').value;
    let hasErrors = false;
    
    // Validación básica
    if (nombre.trim() === '') {
        document.getElementById('nombreError').textContent = 'El nombre es obligatorio';
        hasErrors = true;
    }
    
    if (pvp.trim() === '' || isNaN(pvp) || Number(pvp) <= 0) {
        document.getElementById('pvpError').textContent = 'El precio de venta debe ser un número positivo';
        hasErrors = true;
    }
    
    if (stock.trim() === '' || isNaN(stock) || Number(stock) < 0) {
        document.getElementById('stockError').textContent = 'El stock debe ser un número no negativo';
        hasErrors = true;
    }
    
    if (precioCompra.trim() === '' || isNaN(precioCompra) || Number(precioCompra) <= 0) {
        document.getElementById('precioCompraError').textContent = 'El precio de compra debe ser un número positivo';
        hasErrors = true;
    }
    
    // Validación específica según el tipo
    if (tipo === 'C') {
        const descripcion = document.getElementById('edit_descripcion').value;
        if (descripcion.trim() === '') {
            document.getElementById('descripcionError').textContent = 'La descripción es obligatoria';
            hasErrors = true;
        }
    } else if (tipo === 'B') {
        const tamanio = document.getElementById('edit_tamanio').value;
        const tipoBebida = document.getElementById('edit_tipoBebida').value;
        
        if (tamanio.trim() === '') {
            document.getElementById('tamanioError').textContent = 'Debe seleccionar un tamaño';
            hasErrors = true;
        }
        
        if (tipoBebida.trim() === '') {
            document.getElementById('tipoBebidaError').textContent = 'Debe seleccionar un tipo de bebida';
            hasErrors = true;
        }
    } else if (tipo === 'M') {
        const descripcionMenu = document.getElementById('edit_descripcion_menu').value;
        
        if (descripcionMenu.trim() === '') {
            document.getElementById('descripcion_menuError').textContent = 'La descripción del menú es obligatoria';
            hasErrors = true;
        }
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