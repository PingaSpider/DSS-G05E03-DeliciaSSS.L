<div class="product-card" data-product-id="{{ $producto->cod }}">
    <a href="{{ route('producto.show', $producto->cod) }}" class="product-link">
        <div class="product-image">
            <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" class="product-thumbnail">
        </div>
        <div class="product-info">
            <div class="product-name">{{ $producto->nombre }}</div>
            <div class="product-rating">
                @for($i = 0; $i < 5; $i++)
                    <i class="fas fa-star"></i>
                @endfor
            </div>
            <div class="product-price">${{ number_format($producto->pvp, 2) }}</div>
            @auth
                <div class="product-actions">
                    <div class="quantity-selector">
                        <button class="quantity-btn minus" data-product="{{ $producto->cod }}">-</button>
                        <input type="number" class="quantity-input" value="1" min="1" max="10" data-product="{{ $producto->cod }}">
                        <button class="quantity-btn plus" data-product="{{ $producto->cod }}">+</button>
                    </div>
                    <button class="add-to-cart-btn" data-product="{{ $producto->cod }}">
                        <i class="fas fa-cart-plus"></i>
                    </button>
                </div>
            @endauth
            @if($producto->stock < 10 && $producto->stock > 0)
                <div class="stock-warning">¡Últimas unidades!</div>
            @elseif($producto->stock == 0)
                <div class="stock-warning out-of-stock">Agotado</div>
            @endif
        </div>
    </a>
</div>