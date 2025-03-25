<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delicia'SS - Reserva tu Mesa</title>
  <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
  <link href="{{ asset('css/reserva.css') }}" rel="stylesheet">
  <style>
    .modal-hidden {
      display: none !important;
    }
  </style>
</head>
<body style="background-color: #FEFCE0; margin: 0; padding: 20px; font-family: 'Raleway', sans-serif;">
  <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
    <div>
      <img src="{{ asset('assets/repo/0NqEprKrYi/r1cZqXsetIdbAgA931dT9WmWgmYAWcTV.png') }}" alt="Delicia'SS Logo" style="height: 50px;">
    </div>
    
    <div style="display: flex; align-items: center;">
        <div class="search-input-1" style="position: relative; margin-right: 50px;">
            <input type="text" placeholder="Search..." style="width: 100%; height: 100%; padding-left: 10px; border: 1px solid #FEFCE0; background-color: transparent;">
          </div>
      
      <div class="link-bar-1" style="display: flex; justify-content: space-between; margin-right: 20px;">
        <a href="{{ route('reserva') }}" style="text-decoration: none; color: #000000; margin: 0 20px;">Reservas</a>
        <span style="color: #000000;">|</span>
        <a href="{{ route('menu') }}" style="text-decoration: none; color: #000000; margin: 0 20px;">Menu</a>
        <span style="color: #000000;">|</span>
        <a href="{{ route('contacto') }}" style="text-decoration: none; color: #000000; margin: 0 20px;">Contacto</a>
      </div>
      
      <div style="display: flex; align-items: center;">
        <button class="button-1-copy-1">Pedir Online</button>
        <div class="avatar-1" style="display: flex; justify-content: center; align-items: center; margin-left: 10px;">
          <img src="{{ asset('assets/repo/E-commerce_Shop_Avatar_1.png') }}" alt="User avatar" style="width: 20px; height: 20px;">
        </div>
      </div>
    </div>
  </header>
  
  <main style="max-width: 800px; margin: 0 auto;">
    <h1 class="reserva-tu-mesa">Reserva tu Mesa</h1>
    
    <p class="para-poder-ofrecerte-la-mejor-mesa-del-local-sele">
      {{ $mensaje ?? 'Para poder ofrecerte la mejor mesa del local, seleccione la cantidad de personas, fecha y lugar.' }}
    </p>
    
    <form action="{{ route('reservas.store') }}" method="POST">
      @csrf
      <div style="display: flex; justify-content: space-between; margin: 30px 0;">
        <div>
          <label class="cantidad-de-personas-1" for="people">Cantidad de personas</label>
          <div style="display: flex; margin-top: 10px;">
            <select id="people" name="personas" class="select-1">
              @foreach($cantidadPersonas ?? [2, 4, 6, 8] as $cantidad)
                <option value="{{ $cantidad }}">{{ $cantidad }}</option>
              @endforeach
            </select>
          </div>
        </div>
        
        <div>
          <label class="fecha-1" for="date">Fecha</label>
          <div style="display: flex; margin-top: 10px;">
            <input type="date" id="date" name="fecha" class="date-picker-1" value="{{ date('Y-m-d') }}">
          </div>
        </div>
        
        <div>
          <label class="hora" for="time">Hora</label>
          <div style="display: flex; margin-top: 10px;">
            <select id="time" name="hora" class="select-2">
              @foreach($horas ?? ['15:00', '15:15', '15:30', '15:45'] as $hora)
                <option value="{{ $hora }}">{{ $hora }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      
      <hr class="horizontal-line-1">
      
      <div style="margin: 30px 0;">
        <p class="elige-una-franja-horaria-disponible-">Elige una franja horaria disponible:</p>
        
        <div style="display: flex; justify-content: space-between; margin-top: 20px; max-width: 650px;">
          @foreach($franjasHorarias ?? ['15:00', '15:15', '15:30', '15:45'] as $key => $franja)
            <button type="button" class="button-{{ $key + 1 }}" data-hora="{{ $franja }}">{{ $franja }}</button>
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
      <img src="{{ asset('assets/repo/0NqEprKrYi/r1cZqXsetIdbAgA931dT9WmWgmYAWcTV.png') }}" alt="Restaurant logo" class="captura-de-pantalla-2025-03-12-a-las-17-20-04-png-1" style="margin-bottom: 20px;">
    </div>
  </main>
  
  <div id="confirmModal" class="modal-hidden" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 100;">
    <div class="rectangle-2" style="display: flex; flex-direction: column; justify-content: space-between;">
      <div>
        <p class="confirmar-reserva-">Confirmar Reserva?</p>
      </div>
      
      <div style="display: flex; justify-content: flex-end; gap: 10px;">
        <button class="rectangle-5" id="cancel-btn">
          <span class="paragraph-1">CANCEL</span>
        </button>
        <button class="rectangle-3" id="accept-btn" onclick="document.querySelector('form').submit();">
          <span class="accept">ACCEPT</span>
        </button>
      </div>
    </div>
  </div>

  <script src="{{ asset('js/reserva.js') }}"></script>
</body>
</html>