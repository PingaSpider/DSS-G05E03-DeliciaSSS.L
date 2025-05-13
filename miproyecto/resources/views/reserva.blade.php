<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Delicia'SS - Reserva tu Mesa</title>
  <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
  <link href="{{ asset('css/reserva.css') }}" rel="stylesheet">
  <link href="{{ asset('css/sesion.css') }}" rel="stylesheet">
  <script src="{{ asset('js/reserva.js') }}"></script>
  <script src="{{ asset('js/sesionHandler.js') }}"></script>
</head>
<body style="background-color: #FEFCE0; margin: 0; padding: 20px; font-family: 'Raleway', sans-serif;">
  <!-- Reemplaza tu header actual con este -->
  <header class="header">
    <div class="logo-container">
      <a href="{{ route('home') }}">
        <img src="{{ asset('assets/images/repo/auWlPQdP6Eus31XrYaNlVMkNX77SohDB/p_OaeuUHJPLAylpvXBb80gi4TCAH9oSSZ5/delicias-logo.png') }}" alt="Delicia'SS Logo">
      </a>
    </div>
    
    <nav class="main-nav">
      <div class="nav-links">
        <a href="{{ route('home') }}">Home</a>
        |
        <a href="{{ route('menu') }}">Menus</a>
        |
        <a href="{{ route('producto.show') }}">Carta</a>
      </div>
    </nav>
    <!-- Estructura HTML para el avatar con menú desplegable -->
    <div class="avatar-container">
      <img src="{{ asset('assets/images/repo/E-commerce_Shop_Avatar_1.png') }}" id="avatar" class="avatar" alt="Avatar">
      <div class="dropdown-menu" id="avatarMenu">
          @auth
              <!-- Usuario autenticado: muestra opciones de perfil y cerrar sesión -->
              <a href="{{ route('user.profile') }}">Mi Perfil</a>
              
              @if(Auth::user()->esAdmin())
                  <a href="{{ route('paneladmin') }}">Panel Admin</a>
              @endif
              
              <!-- Formulario de logout estilizado como enlace -->
              <form action="{{ route('logout') }}" method="POST" id="logout-form" class="logout-link-form">
                  @csrf
                  <button type="submit" class="link-button">Cerrar sesión</button>
              </form>
          @else
              <!-- Usuario no autenticado: muestra opciones de login y registro -->
              <a href="{{ route('login.form') }}">Iniciar sesión</a>
              <a href="{{ route('registro.form') }}">Registrarse</a>
          @endauth
      </div>
  </header>
  
  <main class="container">
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
                <option id=cantidadPersona value="{{ $cantidad }}" {{ old('personas') == $cantidad ? 'selected' : '' }}>{{ $cantidad }}</option>
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
          @php
            // Obtener la cantidad de personas seleccionada
            $personasSeleccionadas = old('personas') ?? (isset($cantidadPersonas) && is_array($cantidadPersonas) ? reset($cantidadPersonas) : (isset($cantidadPersonas) ? $cantidadPersonas : 2));
            $mesasFiltradas = [];
            
            // Filtrar las mesas que tienen capacidad suficiente
            if(isset($mesas) && count($mesas) > 0) {
              foreach($mesas as $mesa) {
                if($mesa->cantidadMesa >= $personasSeleccionadas) {
                  $mesasFiltradas[] = $mesa;
                }
              }
            }
          @endphp
          
          @if(count($mesasFiltradas) > 0)
            $personasSeleccionadas = old('personas') ?? (isset($cantidadPersonas) && is_array($cantidadPersonas) ? reset($cantidadPersonas) : (isset($cantidadPersonas) ? $cantidadPersonas : 2)); 
            @foreach($mesasFiltradas as $mesa) 
              <option value="{{ $mesa->codMesa }}" 
                      {{ old('mesa_id') == $mesa->codMesa ? 'selected' : '' }}
                      data-capacidad="{{ $mesa->cantidadMesa }}">
                Mesa {{ $mesa->codMesa }} ({{ $mesa->cantidadMesa }} personas)
              </option>
            @endforeach
          @else
            <option value="" disabled>No hay mesas disponibles para {{ $personasSeleccionadas }} personas</option>
          @endif
        </select>
        <p style="font-size: 12px; margin-top: 8px; color: #666; font-style: italic;">
          Solo se muestran mesas con capacidad suficiente para el número de personas seleccionado.
        </p>
      </div>
      
      <div class="franja-horaria-selector">
        <p class="elige-una-franja-horaria-disponible-">Elige una franja horaria disponible:</p>
        
        <div class="franja-horaria-buttons">
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