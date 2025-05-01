<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Delicia'SS - Reserva tu Mesa</title>
  <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
  <link href="{{ asset('css/reserva.css') }}" rel="stylesheet">
  <style>
    .modal-hidden {
      display: none !important;
    }
    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border: 1px solid transparent;
      border-radius: 4px;
    }
    .alert-success {
      color: #3c763d;
      background-color: #dff0d8;
      border-color: #d6e9c6;
    }
    .alert-error {
      color: #a94442;
      background-color: #f2dede;
      border-color: #ebccd1;
    }
    .selected {
      background-color: #4CAF50;
      color: white;
    }
    .user-info {
      margin-bottom: 20px;
      text-align: right;
      font-style: italic;
      color: #666;
    }
    .user-info strong {
      color: #333;
    }
    .mesa-selector {
      margin-top: 20px;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      background-color: #f9f9f9;
    }
    .mesa-selector select {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      background-color: white;
      font-family: 'Raleway', sans-serif;
    }
    .mesa-selector p {
      margin-bottom: 10px;
      font-weight: bold;
    }
    .mesa-option-disabled {
      color: #999;
    }
    .mesa-title {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 15px;
      color: #333;
    }
  </style>
</head>
<body style="background-color: #FEFCE0; margin: 0; padding: 20px; font-family: 'Raleway', sans-serif;">
  <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
    <div>
      <img src="{{ asset('assets/images/repo/auWlPQdP6Eus31XrYaNlVMkNX77SohDB/p_OaeuUHJPLAylpvXBb80gi4TCAH9oSSZ5/delicias-logo.png') }}" alt="Delicia'SS Logo" style="height: 50px;">
    </div>
    
    <div style="display: flex; align-items: center;">
        <div class="search-input-1" style="position: relative; margin-right: 50px;">
            <input type="text" placeholder="Search..." style="width: 100%; height: 100%; padding-left: 10px; border: 1px solid #FEFCE0; background-color: transparent;">
          </div>
      
      <div class="link-bar-1" style="display: flex; justify-content: space-between; margin-right: 20px;">
        <a href="{{ route('reservaciones.index') }}" style="text-decoration: none; color: #000000; margin: 0 20px;">Reservas</a>
        <span style="color: #000000;">|</span>
        <a href="{{ route('menu') }}" style="text-decoration: none; color: #000000; margin: 0 20px;">Menu</a>
        <span style="color: #000000;">|</span>
      </div>
      
      <div style="display: flex; align-items: center;">
        <button class="button-1-copy-1">Pedir Online</button>
        <div class="avatar-1" style="display: flex; justify-content: center; align-items: center; margin-left: 10px;">
          <img src="{{ asset('assets/images/repo/E-commerce_Shop_Avatar_1.png') }}" alt="User avatar" style="width: 20px; height: 20px;">
        </div>
      </div>
    </div>
  </header>
  
  <main style="max-width: 800px; margin: 0 auto;">
    <h1 class="reserva-tu-mesa">Reserva tu Mesa</h1>
    
    <!-- Mostrar información del usuario -->
    @if(isset($usuario) && $usuario)
    <div class="user-info">
      <p>Reservando como: <strong>{{ $usuario->nombre ?? $usuario->email }}</strong></p>
    </div>
    @endif
    
    <p class="para-poder-ofrecerte-la-mejor-mesa-del-local-sele">
      {{ $mensaje ?? 'Para poder ofrecerte la mejor mesa del local, seleccione la cantidad de personas, fecha y lugar.' }}
    </p>
    
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

    @if ($errors->any())
      <div class="alert alert-error">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    @endif
    
    <form id="reserva-form" action="{{ route('reservaciones.store') }}" method="POST">
      @csrf
      <div style="display: flex; justify-content: space-between; margin: 30px 0;">
        <div>
          <label class="cantidad-de-personas-1" for="people">Cantidad de personas</label>
          <div style="display: flex; margin-top: 10px;">
            <select id="people" name="personas" class="select-1">
              @foreach($cantidadPersonas ?? [2, 4, 6, 8] as $cantidad)
                <option value="{{ $cantidad }}" {{ old('personas') == $cantidad ? 'selected' : '' }}>{{ $cantidad }}</option>
              @endforeach
            </select>
          </div>
        </div>
        
        <div>
          <label class="fecha-1" for="date">Fecha</label>
          <div style="display: flex; margin-top: 10px;">
            <input type="date" id="date" name="fecha" class="date-picker-1" value="{{ old('fecha', $fecha ?? date('Y-m-d')) }}" min="{{ date('Y-m-d') }}">
          </div>
        </div>
        
        <div>
          <label class="hora" for="time">Hora</label>
          <div style="display: flex; margin-top: 10px;">
            <select id="time" name="hora" class="select-2">
              @foreach($horas ?? ['15:00', '15:15', '15:30', '15:45'] as $hora)
                <option value="{{ $hora }}" {{ (old('hora') == $hora || (isset($horaSeleccionada) && $horaSeleccionada == $hora)) ? 'selected' : '' }}>{{ $hora }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      
      <hr class="horizontal-line-1">
      
      <!-- Selector de mesas -->
      <div class="mesa-selector">
        <p class="mesa-title">Selecciona una mesa:</p>
        <select id="mesa_id" name="mesa_id" required>
          <option value="">Seleccione una mesa</option>
          @foreach($mesas ?? [] as $mesa)
            <option value="{{ $mesa->codMesa }}" 
                    {{ old('mesa_id') == $mesa->codMesa ? 'selected' : '' }}
                    data-capacidad="{{ $mesa->cantidadMesa }}">
              Mesa {{ $mesa->codMesa }} ({{ $mesa->cantidadMesa }} personas)
            </option>
          @endforeach
        </select>
        <p style="font-size: 12px; margin-top: 8px; color: #666; font-style: italic;">
          Solo se muestran mesas con capacidad suficiente para el número de personas seleccionado.
        </p>
      </div>
      
      <div style="margin: 30px 0;">
        <p class="elige-una-franja-horaria-disponible-">Elige una franja horaria disponible:</p>
        
        <div style="display: flex; justify-content: space-between; margin-top: 20px; max-width: 650px;">
          @foreach($franjasHorarias ?? ['15:00', '15:15', '15:30', '15:45'] as $key => $franja)
            <button type="button" class="button-{{ $key + 1 }} {{ (old('hora') == $franja || (isset($horaSeleccionada) && $horaSeleccionada == $franja)) ? 'selected' : '' }}" data-hora="{{ $franja }}">{{ $franja }}</button>
          @endforeach
        </div>
      </div>
      
      <div style="display: flex; justify-content: center; margin: 40px 0;">
        <button type="button" class="rectangle-1" id="reservar-btn">
          <span class="reservar-ahora">Reservar Ahora</span>
        </button>
      </div>
    </form>  
    
    <div style="display: flex; justify-content: center; margin-top: 30px;">
      <img src="{{ asset('assets/images/repo/auWlPQdP6Eus31XrYaNlVMkNX77SohDB/p_OaeuUHJPLAylpvXBb80gi4TCAH9oSSZ5/delicias-logo.png') }}" alt="Restaurant logo" class="captura-de-pantalla-2025-03-12-a-las-17-20-04-png-1" style="margin-bottom: 20px;">
    </div>
    
    <!-- Información del horario del restaurante -->
    <div style="margin-top: 30px; text-align: center; color: #666;">
      <p>Horario del restaurante:</p>
      <p>{{ $horarios->semana ?? 'Lun-Vie: 12:30 - 22:00' }}</p>
      <p>{{ $horarios->finde ?? 'Sab-Dom: 13:00 - 23:30' }}</p>
    </div>
  </main>
  
  <div id="confirmModal" class="modal-hidden" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 1000;">
    <div class="rectangle-2" style="background-color: white; padding: 20px; border-radius: 5px; width: 400px; max-width: 90%;">
      <div>
        <p class="confirmar-reserva-" style="font-size: 18px; font-weight: bold; margin-bottom: 20px;">¿Confirmar Reserva?</p>
        <div id="modal-details" style="margin-bottom: 30px;">
          <!-- Los detalles de la reserva se mostrarán aquí mediante JavaScript -->
        </div>
      </div>
      
      <div style="display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" class="rectangle-5" id="cancel-btn" style="padding: 10px 20px; background-color: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer;">
          <span class="paragraph-1">CANCELAR</span>
        </button>
        <button type="submit" class="rectangle-3" id="accept-btn" form="reserva-form" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
          <span class="accept">ACEPTAR</span>
        </button>
      </div>
    </div>
  </div>

  <script src="{{ asset('js/reserva.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Referencias a elementos del DOM
      const selectPersonas = document.getElementById('people');
      const selectMesa = document.getElementById('mesa_id');
      const reservarBtn = document.getElementById('reservar-btn');
      const modalDetails = document.getElementById('modal-details');
      const selectFecha = document.getElementById('date');
      const selectHora = document.getElementById('time');
      
      // Filtrar mesas por capacidad seleccionada
      if (selectPersonas && selectMesa) {
        selectPersonas.addEventListener('change', function() {
          const personasCount = parseInt(this.value);
          
          // Recorrer todas las opciones y habilitar/deshabilitar según capacidad
          Array.from(selectMesa.options).forEach(function(option) {
            if (option.value === '') return; // Saltar la opción "Selecciona una mesa"
            
            const capacidad = parseInt(option.getAttribute('data-capacidad'));
            if (capacidad < personasCount) {
              option.disabled = true;
              option.classList.add('mesa-option-disabled');
              if (option.selected) {
                selectMesa.value = '';
              }
            } else {
              option.disabled = false;
              option.classList.remove('mesa-option-disabled');
            }
          });
        });
        
        // Ejecutar inicialmente para configurar el estado inicial
        selectPersonas.dispatchEvent(new Event('change'));
      }
      
      // Actualizar detalles en el modal de confirmación
      if (reservarBtn && modalDetails && selectPersonas && selectFecha && selectHora && selectMesa) {
        reservarBtn.addEventListener('click', function() {
          // Validar que se haya seleccionado una mesa
          if (!selectMesa.value) {
            alert('Por favor, seleccione una mesa para continuar');
            return;
          }
          
          // Formatear la fecha para mostrarla en el modal
          const fecha = new Date(selectFecha.value);
          const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
          const fechaFormateada = fecha.toLocaleDateString('es-ES', options);
          
          // Obtener el texto de la mesa seleccionada
          const mesaSeleccionada = selectMesa.options[selectMesa.selectedIndex].text;
          
          // Mostrar los detalles en el modal
          modalDetails.innerHTML = `
            <p><strong>Personas:</strong> ${selectPersonas.value}</p>
            <p><strong>Fecha:</strong> ${fechaFormateada}</p>
            <p><strong>Hora:</strong> ${selectHora.value}</p>
            <p><strong>Mesa:</strong> ${mesaSeleccionada}</p>
          `;
          
          // Mostrar el modal
          document.getElementById('confirmModal').classList.remove('modal-hidden');
        });
      }
    });
  </script>
</body>
</html>