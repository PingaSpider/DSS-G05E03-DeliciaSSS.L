<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mis Reservas - Delicia'SS</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500&family=Roboto&family=Source+Sans+3&display=swap" rel="stylesheet">
    <link href="{{ asset('css/reserva.css') }}" rel="stylesheet">
    <style>
        .reservas-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .reserva-item {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .reserva-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .reserva-date {
            font-weight: 500;
            color: #333;
        }
        
        .reserva-code {
            color: #666;
            font-size: 0.9rem;
        }
        
        .reserva-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .reserva-detail-item {
            display: flex;
            flex-direction: column;
        }
        
        .detail-label {
            font-size: 0.8rem;
            color: #666;
        }
        
        .detail-value {
            font-weight: 500;
        }
        
        .reserva-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .btn-cancel {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Raleway', sans-serif;
        }
        
        .btn-cancel:hover {
            background-color: #d32f2f;
        }
        
        .no-reservas {
            text-align: center;
            padding: 40px 0;
            color: #666;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 10px;
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
    
    <main class="reservas-container">
        <h1 style="text-align: center; margin-bottom: 30px;">Mis Reservas</h1>
        
        @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
        @endif
        
        @if(session('error'))
        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
        @endif
        
        @if($reservas->isEmpty())
        <div class="no-reservas">
            <p>No tienes reservas activas</p>
            <a href="{{ route('reserva') }}" style="display: inline-block; background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; margin-top: 15px;">Hacer una reserva</a>
        </div>
        @else
        <div class="reservas-list">
            @foreach($reservas as $reserva)
            <div class="reserva-item">
                <div class="reserva-header">
                    <div class="reserva-date">
                        {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }} a las {{ $reserva->hora }}
                    </div>
                    <div class="reserva-code">
                        Código: {{ $reserva->codReserva }}
                    </div>
                </div>
                
                <div class="reserva-details">
                    <div class="reserva-detail-item">
                        <span class="detail-label">Personas</span>
                        <span class="detail-value">{{ $reserva->cantPersona }}</span>
                    </div>
                    
                    <div class="reserva-detail-item">
                        <span class="detail-label">Mesa</span>
                        <span class="detail-value">{{ $reserva->mesa_id }}</span>
                    </div>
                    
                    <div class="reserva-detail-item">
                        <span class="detail-label">Estado</span>
                        <span class="detail-value">{{ $reserva->reservaConfirmada ? 'Confirmada' : 'Pendiente' }}</span>
                    </div>
                    
                    <div class="reserva-detail-item">
                        <span class="detail-label">Fecha de reserva</span>
                        <span class="detail-value">{{ \Carbon\Carbon::parse($reserva->created_at)->format('d/m/Y') }}</span>
                    </div>
                </div>
                
                <div class="reserva-actions">
                    @if(\Carbon\Carbon::parse($reserva->fecha . ' ' . $reserva->hora)->isFuture())
                    <form action="{{ route('reserva.cancelar', $reserva->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta reserva?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-cancel">Cancelar reserva</button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="pagination">
            {{ $reservas->links() }}
        </div>
        @endif
    </main>
    
    <div style="display: flex; justify-content: center; margin-top: 30px;">
        <img src="{{ asset('assets/repo/0NqEprKrYi/r1cZqXsetIdbAgA931dT9WmWgmYAWcTV.png') }}" alt="Restaurant logo" style="margin-bottom: 20px;">
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar cualquier JS necesario
        });
    </script>
</body>
</html>