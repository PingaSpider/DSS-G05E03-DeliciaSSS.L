/**
 * Script para la edición de menús
 * Este archivo controla la selección de productos y la gestión de la interfaz
 * para la página de edición de menús
 */

document.addEventListener('DOMContentLoaded', function() {
    // Configurar CSRF token para solicitudes AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Manejo de pestañas
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Actualizar pestañas activas
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Mostrar contenido de la pestaña seleccionada
            const tabName = this.getAttribute('data-tab');
            tabContents.forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(tabName + '-tab').classList.add('active');
        });
    });
    
    // Variables para controlar productos seleccionados
    const seleccionadosContainer = document.getElementById('seleccionados-container');
    const noProductosMsg = document.getElementById('no-productos-msg');
    const productosInputs = document.getElementById('productos-inputs');
    let productosSeleccionados = [];
    
    // Inicializar productos seleccionados desde los productos existentes
    if (typeof menuProductosInicial !== 'undefined' && menuProductosInicial.length > 0) {
        productosSeleccionados = menuProductosInicial;
    }
    
    // Actualizar vista inicial
    actualizarVistaSeleccionados();
    
    // Agregar evento a los checkboxes de productos
    const checkboxes = document.querySelectorAll('.producto-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const cod = this.getAttribute('data-cod');
            const nombre = this.getAttribute('data-nombre');
            const precio = this.getAttribute('data-precio');
            
            if (this.checked) {
                // Agregar producto a la lista de seleccionados
                agregarProducto(cod, nombre, precio);
                
                // Mostrar campos de detalles
                const detallesDiv = document.createElement('div');
                detallesDiv.className = 'producto-details';
                detallesDiv.innerHTML = `
                    <label>Cantidad: 
                        <input type="number" min="1" class="cantidad-input" data-cod="${cod}" value="1" onchange="actualizarCantidad('${cod}', this.value)">
                    </label>
                `;
                
                const productoItem = this.closest('.producto-item');
                productoItem.appendChild(detallesDiv);
                
                // Marcar visualmente como seleccionado
                productoItem.classList.add('producto-seleccionado');
            } else {
                // Quitar producto de la lista de seleccionados
                eliminarProducto(cod);
                
                // Ocultar campos de detalles
                const productoItem = this.closest('.producto-item');
                const detallesDiv = productoItem.querySelector('.producto-details');
                if (detallesDiv) {
                    productoItem.removeChild(detallesDiv);
                }
                
                // Quitar marca visual
                productoItem.classList.remove('producto-seleccionado');
            }
        });
    });
    
    // Eventos para los inputs de cantidad y descripción existentes
    document.querySelectorAll('.cantidad-input').forEach(input => {
        input.addEventListener('change', function() {
            const cod = this.getAttribute('data-cod');
            actualizarCantidad(cod, this.value);
        });
    });
    
    document.querySelectorAll('.descripcion-input').forEach(input => {
        input.addEventListener('change', function() {
            const cod = this.getAttribute('data-cod');
            actualizarDescripcion(cod, this.value);
        });
    });
    
    // Función para agregar un producto a la lista de seleccionados
    function agregarProducto(cod, nombre, precio) {
        // Comprobar si ya está seleccionado
        if (productosSeleccionados.find(p => p.cod === cod)) {
            return;
        }
        
        // Agregar a la lista interna
        productosSeleccionados.push({
            cod: cod,
            nombre: nombre,
            precio: precio,
            cantidad: 1,
            descripcion: ''
        });
        
        // Actualizar visualización
        actualizarVistaSeleccionados();
    }
    
    // Función para eliminar un producto de la lista de seleccionados
    function eliminarProducto(cod) {
        // Quitar de la lista interna
        productosSeleccionados = productosSeleccionados.filter(p => p.cod !== cod);
        
        // Actualizar visualización
        actualizarVistaSeleccionados();
        
        // Desmarcar el checkbox correspondiente
        const checkbox = document.querySelector(`.producto-checkbox[data-cod="${cod}"]`);
        if (checkbox) {
            checkbox.checked = false;
            checkbox.closest('.producto-item').classList.remove('producto-seleccionado');
        }
    }
    
    // Función para actualizar la vista de productos seleccionados
    function actualizarVistaSeleccionados() {
        // Limpiar contenedor
        productosInputs.innerHTML = '';
        
        if (productosSeleccionados.length === 0) {
            // Mostrar mensaje de no hay productos
            noProductosMsg.style.display = 'block';
            seleccionadosContainer.innerHTML = '';
            seleccionadosContainer.appendChild(noProductosMsg);
            return;
        }
        
        // Ocultar mensaje de no hay productos
        noProductosMsg.style.display = 'none';
        
        // Crear HTML para los productos seleccionados
        let html = '';
        productosSeleccionados.forEach((producto, index) => {
            html += `
                <div class="producto-item producto-seleccionado" id="seleccionado-${producto.cod}">
                    <div class="d-flex align-items-center">
                        <strong>${producto.nombre}</strong> - ${parseFloat(producto.precio).toFixed(2)}€
                        <div class="ml-auto">
                            <label>Cantidad: 
                                <input type="number" min="1" class="cantidad-input" 
                                    value="${producto.cantidad}" 
                                    data-cod="${producto.cod}" 
                                    onchange="actualizarCantidad('${producto.cod}', this.value)">
                            </label>
                            <span class="delete-producto" onclick="eliminarProductoSeleccionado('${producto.cod}')">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </span>
                        </div>
                    </div>
                </div>
            `;
            
            // Crear inputs ocultos para enviar al servidor
            const hiddenInputProducto = document.createElement('input');
            hiddenInputProducto.type = 'hidden';
            hiddenInputProducto.name = `producto_ids[]`;
            hiddenInputProducto.value = producto.cod;
            productosInputs.appendChild(hiddenInputProducto);
            
            const hiddenInputCantidad = document.createElement('input');
            hiddenInputCantidad.type = 'hidden';
            hiddenInputCantidad.name = `cantidades[]`;
            hiddenInputCantidad.value = producto.cantidad;
            hiddenInputCantidad.id = `cantidad-${producto.cod}`;
            productosInputs.appendChild(hiddenInputCantidad);
            
            const hiddenInputDescripcion = document.createElement('input');
            hiddenInputDescripcion.type = 'hidden';
            hiddenInputDescripcion.name = `descripciones[]`;
            hiddenInputDescripcion.value = producto.descripcion || '';
            hiddenInputDescripcion.id = `descripcion-${producto.cod}`;
            productosInputs.appendChild(hiddenInputDescripcion);
        });
        
        seleccionadosContainer.innerHTML = html;
    }
    
    // Exponemos funciones al ámbito global para usarlas en eventos inline
    window.actualizarCantidad = function(cod, cantidad) {
        const producto = productosSeleccionados.find(p => p.cod === cod);
        if (producto) {
            producto.cantidad = parseInt(cantidad);
            
            // Actualizar input oculto
            const hiddenInput = document.getElementById(`cantidad-${cod}`);
            if (hiddenInput) {
                hiddenInput.value = cantidad;
            }
        }
    };
    
    window.actualizarDescripcion = function(cod, descripcion) {
        const producto = productosSeleccionados.find(p => p.cod === cod);
        if (producto) {
            producto.descripcion = descripcion;
            
            // Actualizar input oculto
            const hiddenInput = document.getElementById(`descripcion-${cod}`);
            if (hiddenInput) {
                hiddenInput.value = descripcion;
            }
        }
    };
    
    window.eliminarProductoSeleccionado = function(cod) {
        eliminarProducto(cod);
    };
    
    // Validación del formulario antes de enviar
    document.getElementById('form_menu').addEventListener('submit', function(e) {
        let hasErrors = false;
        
        // Validar nombre
        const nombreInput = document.getElementById('nombre');
        if (nombreInput.value.trim() === '') {
            if (nombreInput.nextElementSibling && nombreInput.nextElementSibling.classList.contains('error-message')) {
                nombreInput.nextElementSibling.textContent = 'El nombre es obligatorio';
            } else {
                const errorDiv = document.createElement('div');
                errorDiv.classList.add('error-message');
                errorDiv.textContent = 'El nombre es obligatorio';
                nombreInput.parentNode.insertBefore(errorDiv, nombreInput.nextSibling);
            }
            nombreInput.classList.add('error-input');
            hasErrors = true;
        }
        
        // Validar precio venta
        const pvpInput = document.getElementById('pvp');
        if (pvpInput.value <= 0) {
            if (pvpInput.nextElementSibling && pvpInput.nextElementSibling.classList.contains('error-message')) {
                pvpInput.nextElementSibling.textContent = 'El precio de venta debe ser mayor que 0';
            } else {
                const errorDiv = document.createElement('div');
                errorDiv.classList.add('error-message');
                errorDiv.textContent = 'El precio de venta debe ser mayor que 0';
                pvpInput.parentNode.insertBefore(errorDiv, pvpInput.nextSibling);
            }
            pvpInput.classList.add('error-input');
            hasErrors = true;
        }
        
        // Validar productos seleccionados
        if (productosSeleccionados.length === 0) {
            const errorDiv = document.createElement('div');
            errorDiv.classList.add('error-message');
            errorDiv.textContent = 'Debe seleccionar al menos un producto para el menú';
            seleccionadosContainer.parentNode.insertBefore(errorDiv, seleccionadosContainer.nextSibling);
            hasErrors = true;
        }
        
        if (hasErrors) {
            e.preventDefault();
        }
    });
});