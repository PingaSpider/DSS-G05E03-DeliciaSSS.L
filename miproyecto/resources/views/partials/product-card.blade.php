<div class="product-card" data-product-id="{{ $producto->cod }}">
    <a href="{{ route('producto.show', $producto->cod) }}" class="product-link">
        <div class="product-image">
            <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" class="product-thumbnail">
            <button class="quick-add-btn" data-product-id="{{ $producto->cod }}">
                <i class="fas fa-plus"></i>
            </button>
        </div>
        <div class="product-info">
            <div class="product-name">{{ $producto->nombre }}</div>
            <div class="product-rating">
                @for($i = 0; $i < 5; $i++)
                    <i class="fas fa-star"></i>
                @endfor
            </div>
            <div class="product-price">${{ number_format($producto->pvp, 2) }}</div>
            @if($producto->stock < 10 && $producto->stock > 0)
                <div class="stock-warning">¡Últimas unidades!</div>
            @elseif($producto->stock == 0)
                <div class="stock-warning out-of-stock">Agotado</div>
            @endif
        </div>
    </a>
</div>