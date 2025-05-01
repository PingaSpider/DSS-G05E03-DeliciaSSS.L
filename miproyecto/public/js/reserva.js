// Script para página de reservas
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando script de reserva...');
    
    // Referencias a elementos del DOM
    const franjaHorariaBotones = document.querySelectorAll('[data-hora]');
    const selectHora = document.getElementById('time');
    const selectFecha = document.getElementById('date');
    const selectPersonas = document.getElementById('people');
    const reservarBtn = document.getElementById('reservar-btn');
    const modal = document.getElementById('confirmModal');
    const cancelBtn = document.getElementById('cancel-btn');
    const acceptBtn = document.getElementById('accept-btn');
    const form = document.getElementById('reserva-form');
    
    // Verificar que todos los elementos existen
    const elementosRequeridos = {
        'selectHora': selectHora,
        'selectFecha': selectFecha,
        'selectPersonas': selectPersonas,
        'reservarBtn': reservarBtn,
        'form': form,
        'modal': modal,
        'cancelBtn': cancelBtn,
        'acceptBtn': acceptBtn
    };
    
    // Verificar si falta algún elemento crítico
    let faltanElementos = false;
    Object.entries(elementosRequeridos).forEach(([nombre, elemento]) => {
        if (!elemento) {
            console.error(`Elemento ${nombre} no encontrado en el DOM`);
            faltanElementos = true;
        }
    });
    
    if (faltanElementos) {
        console.error('No se puede inicializar el script de reserva debido a elementos faltantes');
        return;
    }
    
    console.log('Todos los elementos requeridos encontrados');
    
    // Agregar evento click a los botones de franja horaria
    if (franjaHorariaBotones.length > 0) {
        console.log(`Encontrados ${franjaHorariaBotones.length} botones de franja horaria`);
        
        franjaHorariaBotones.forEach(boton => {
            boton.addEventListener('click', function() {
                const hora = this.getAttribute('data-hora');
                console.log(`Seleccionada hora: ${hora}`);
                
                // Actualizar el select de hora
                if (selectHora.querySelector(`option[value="${hora}"]`)) {
                    selectHora.value = hora;
                } else {
                    console.warn(`La hora ${hora} no está disponible en el select`);
                }
                
                // Resaltar botón seleccionado y quitar selección de los demás
                franjaHorariaBotones.forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
    }
    
    // Evento para cuando cambia la hora seleccionada
    selectHora.addEventListener('change', function() {
        console.log(`Hora seleccionada cambiada a: ${this.value}`);
        actualizarBotonesFranjasHorariasBasadoEnHora();
    });
    
    // Evento para cuando cambia la fecha seleccionada
    selectFecha.addEventListener('change', function() {
        console.log(`Fecha seleccionada cambiada a: ${this.value}`);
        // Aquí se podría añadir una llamada AJAX para verificar disponibilidad en esta fecha
    });
    
    // Función para actualizar las franjas horarias basado en la hora seleccionada
    function actualizarBotonesFranjasHorariasBasadoEnHora() {
        const horaSeleccionada = selectHora.value;
        
        // Solo proceder si tenemos hora y botones
        if (!horaSeleccionada || franjaHorariaBotones.length === 0) {
            console.warn("No se puede actualizar franjas horarias: faltan datos");
            return;
        }
        
        try {
            // Obtener partes de la hora (horas y minutos)
            const [horas, minutos] = horaSeleccionada.split(':').map(Number);
            
            // Generar 4 franjas horarias consecutivas de 15 minutos
            const franjas = [];
            let horaActual = new Date();
            horaActual.setHours(horas, minutos, 0, 0);
            
            for (let i = 0; i < 4; i++) {
                franjas.push(
                    `${horaActual.getHours().toString().padStart(2, '0')}:${horaActual.getMinutes().toString().padStart(2, '0')}`
                );
                // Añadir 15 minutos para la siguiente franja
                horaActual.setMinutes(horaActual.getMinutes() + 15);
            }
            
            console.log("Nuevas franjas horarias generadas:", franjas);
            
            // Actualizar los botones con las nuevas franjas
            franjaHorariaBotones.forEach((boton, index) => {
                if (index < franjas.length) {
                    boton.textContent = franjas[index];
                    boton.setAttribute('data-hora', franjas[index]);
                    
                    // Verificar si este botón corresponde a la hora seleccionada
                    if (franjas[index] === horaSeleccionada) {
                        boton.classList.add('selected');
                    } else {
                        boton.classList.remove('selected');
                    }
                }
            });
        } catch (error) {
            console.error("Error al actualizar franjas horarias:", error);
        }
    }
    
    // Mostrar modal de confirmación al hacer clic en Reservar Ahora
    reservarBtn.addEventListener('click', function(event) {
        event.preventDefault(); // Prevenir envío del formulario
        
        console.log("Botón Reservar Ahora clickeado");
        
        // Asegurarse de que los valores estén actualizados en el formulario antes de mostrar el modal
        const personas = selectPersonas.value;
        const fecha = selectFecha.value;
        const hora = selectHora.value;
        
        console.log("Datos del formulario:", { personas, fecha, hora });
        
        // Verificar que tenemos los datos necesarios
        if (!personas || !fecha || !hora) {
            alert('Por favor, complete todos los campos para continuar.');
            console.warn("Formulario incompleto");
            return;
        }
        
        // Mostrar el modal de confirmación
        modal.classList.remove('modal-hidden');
        console.log("Modal de confirmación mostrado");
    });
    
    // Ocultar modal al cancelar
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            modal.classList.add('modal-hidden');
            console.log("Reserva cancelada por el usuario");
        });
    }
    
    // Enviar formulario al aceptar en el modal
    if (acceptBtn) {
        acceptBtn.addEventListener('click', function() {
            console.log("Botón aceptar clickeado, enviando formulario...");
            
            // Verificar que los datos del formulario sean correctos antes de enviar
            const personasInput = selectPersonas;
            const fechaInput = selectFecha;
            const horaInput = selectHora;
            
            if (!personasInput.value || !fechaInput.value || !horaInput.value) {
                alert('Error: Faltan datos en el formulario. No se puede procesar la reserva.');
                console.error("Error en datos del formulario al intentar enviar");
                return;
            }
            
            console.log('Enviando formulario con datos:', {
                personas: personasInput.value,
                fecha: fechaInput.value,
                hora: horaInput.value
            });
            
            // Submit del formulario
            form.submit();
        });
    }
    
    // Inicializar fecha mínima como hoy
    const today = new Date().toISOString().split('T')[0];
    if (selectFecha) {
        selectFecha.min = today;
        console.log(`Fecha mínima establecida: ${today}`);
    }
    
    // Inicializar franjas horarias basadas en la hora inicial seleccionada
    actualizarBotonesFranjasHorariasBasadoEnHora();
    
    // Logging para depuración
    console.log('Script de reserva inicializado correctamente');
});

document.addEventListener('DOMContentLoaded', function() {
    const selectPersonas = document.getElementById('people');
    const selectMesa = document.getElementById('mesa_id');
    
    if (selectPersonas && selectMesa) {
      // Filtrar mesas según la capacidad seleccionada
      selectPersonas.addEventListener('change', function() {
        const personasCount = parseInt(this.value);
        
        // Recorrer todas las opciones y habilitar/deshabilitar según capacidad
        Array.from(selectMesa.options).forEach(function(option) {
          if (option.value === '') return; // Saltar la opción "Selecciona una mesa"
          
          const capacidad = parseInt(option.getAttribute('data-capacidad'));
          if (capacidad < personasCount) {
            option.disabled = true;
            option.style.color = '#999';
            if (option.selected) {
              selectMesa.value = '';
            }
          } else {
            option.disabled = false;
            option.style.color = '';
          }
        });
      });
      
      // Ejecutar inicialmente para configurar el estado inicial
      selectPersonas.dispatchEvent(new Event('change'));
    }
  });