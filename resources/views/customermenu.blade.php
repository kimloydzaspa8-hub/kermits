<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order Now - Kermit's Restaurant</title>
	    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
	    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
	    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
	    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
	        :root {
	            --primary: #4b3a32;
	            --secondary: #ded5cb;
	            --accent: #db7358;
	            --bg: #f4f1ec;
	            --muted: #6e625d;
	        }
		        html { scrollbar-width: none; }
		        html::-webkit-scrollbar { display: none; }
			        body {
				            background: #ffffff;
			            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			            color: #2f2f2f;
			            -ms-overflow-style: none;
			        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes softPop {
            from { opacity: 0; transform: scale(0.94); }
            to { opacity: 1; transform: scale(1); }
        }
		
	        .card { background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(75,58,50,0.08); }
	        .btn-secondary { background-color: #F8BBD0; color: #5D4037; padding: 8px 12px; border-radius: 6px; font-weight: 800; text-align: center; font-size: 0.88rem; transition: background-color 0.2s ease, transform 0.2s ease; }
        .btn-secondary:hover { background-color: #F48FB1; transform: translateY(-1px); }
        .btn-secondary:disabled { opacity: 0.65; cursor: not-allowed; }
        .btn-add-icon { width: 51px; height: 43px; padding: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 1rem; justify-self: start; }
        .btn-add-icon:disabled { opacity: 0.65; cursor: not-allowed; }
				        .customer-header { position: sticky; top: 0; z-index: 90; background: transparent; border-bottom: none; box-shadow: none; }
		        .customer-header nav { width: min(100% - 2.5rem, 1280px); margin: 0 auto; padding: 1rem 0; display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; }
		        .customer-header nav > a:first-child { gap: 14px; }
				        .brand-mark { width: 48px; height: 48px; border-radius: 999px; display: block; object-fit: cover; border: 2px solid var(--primary); flex-shrink: 0; }
					        .brand-name { color: var(--primary); font-family: Georgia, 'Times New Roman', serif; font-size: clamp(1.35rem, 1.7vw, 1.75rem); line-height: 1.1; font-weight: 800; }
				        .home-link { min-height: 46px; display: inline-flex; align-items: center; justify-content: center; color: var(--primary); border: 1px solid var(--secondary); border-radius: 8px; padding: 10px 18px; font-weight: 700; transition: background-color 0.2s ease; }
	        .home-link:hover { background: #f3eee8; }
			        .menu-heading { font-family: Georgia, 'Times New Roman', serif; color: var(--primary); font-size: clamp(1.9rem, 2.7vw, 2.7rem); line-height: 1; }
		        .menu-heading-rule { width: 72px; height: 4px; border-radius: 999px; background: var(--accent); margin: 14px auto 0; }
			        .menu-surface { background: transparent; border: 0; border-radius: 0; padding: clamp(1rem, 2vw, 1.5rem); box-shadow: none; backdrop-filter: none; }
	        .menu-search-wrap { position: relative; z-index: 20; max-width: 720px; margin: 0 auto 28px; }
	        .menu-search-bar { display: grid; grid-template-columns: minmax(145px, 190px) 1fr auto; align-items: stretch; min-height: 52px; background: white; border: 1px solid var(--secondary); border-radius: 999px; box-shadow: 0 10px 24px rgba(75,58,50,0.08); overflow: visible; }
	        .menu-category-toggle { display: flex; align-items: center; justify-content: space-between; gap: 12px; min-width: 0; height: 100%; padding: 0 18px 0 22px; border-radius: 999px; background: var(--primary); color: white; font-weight: 800; text-align: left; }
	        .menu-category-toggle span { min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
	        .menu-category-toggle i { font-size: 0.78rem; transition: transform 0.2s ease; }
	        .menu-search-wrap.open .menu-category-toggle i { transform: rotate(180deg); }
	        .menu-category-menu { position: absolute; top: calc(100% + 10px); left: 0; width: min(220px, 58vw); display: none; overflow: hidden; border: 1px solid #eee5dc; border-radius: 8px; background: white; box-shadow: 0 18px 40px rgba(75,58,50,0.16); }
	        .menu-search-wrap.open .menu-category-menu { display: block; }
	        .menu-category-option { width: 100%; min-height: 44px; display: flex; align-items: center; padding: 0 22px; color: #2f2f2f; font-weight: 700; text-align: left; transition: background-color 0.18s ease, color 0.18s ease; }
	        .menu-category-option:hover, .menu-category-option.active { background: #f3eee8; color: var(--primary); }
	        .menu-search-field { min-width: 0; width: 100%; height: 100%; padding: 0 14px 0 20px; color: #2f2f2f; font-weight: 700; outline: none; background: transparent; }
	        .menu-search-field::placeholder { color: #9a8e86; font-weight: 600; }
	        .menu-search-icon { width: 52px; height: 52px; display: inline-flex; align-items: center; justify-content: center; color: var(--accent); font-size: 1.05rem; }
	        .menu-card { position: relative; overflow: hidden; padding: 0; border-radius: 12px; border: 1px solid #e8e0d8; transition: transform 0.22s ease, box-shadow 0.22s ease; height: 100%; display: flex; flex-direction: column; animation: fadeUp 0.42s ease both; animation-delay: calc(var(--card-index, 0) * 55ms); }
	        .menu-card:hover { transform: translateY(-4px); box-shadow: 0 16px 30px rgba(75,58,50,0.13); }
	        .menu-card:hover .menu-photo { transform: scale(1.04); }
	        .menu-card-body { min-height: 156px; display: flex; flex-direction: column; flex: 1; padding: 16px; }
	        .menu-card-title-row { display: flex; align-items: start; justify-content: space-between; gap: 12px; margin-bottom: 9px; }
	        .menu-actions { margin-top: auto; display: grid; grid-template-columns: 1fr; gap: 8px; align-items: center; }
	        .menu-actions.single-action { grid-template-columns: 1fr; }
	        .menu-photo { width: 100%; height: 155px; object-fit: cover; border-radius: 12px 12px 0 0; background: #f3f4f6; transition: transform 0.35s ease; }
	        .menu-photo-button { display: block; width: 100%; cursor: zoom-in; border: 0; padding: 0; background: transparent; text-align: inherit; }
	        .menu-name { color: var(--primary); font-family: Georgia, 'Times New Roman', serif; font-size: 1.08rem; line-height: 1.2; font-weight: 800; }
	        .menu-description { color: var(--muted); font-size: 0.86rem; line-height: 1.4; margin-bottom: 14px; }
	        .menu-price { color: var(--accent); font-size: 0.98rem; line-height: 1; font-weight: 900; white-space: nowrap; }
	        .menu-badge { position: absolute; top: 10px; left: 10px; z-index: 3; display: inline-flex; align-items: center; gap: 5px; background: var(--accent); color: white; padding: 6px 10px; border-radius: 999px; font-size: 0.66rem; font-weight: 900; text-transform: uppercase; }
        .menu-Tty-control { display: inline-flex; align-items: center; gap: 16px; margin: 0 0 22px; padding: 5px; border: 1px solid #e5e7eb; border-radius: 999px; background: white; width: max-content; }
        .menu-qty-button { width: 34px; height: 34px; border-radius: 999px; background: #111827; color: white; font-weight: 900; line-height: 1; display: inline-flex; align-items: center; justify-content: center; }
        .menu-qty-button:disabled { opacity: 0.45; cursor: not-allowed; }
        .menu-qty-value { min-width: 22px; text-align: center; font-weight: 900; color: #111827; }
        .item-preview { position: fixed; inset: 0; z-index: 100; display: none; align-items: center; justify-content: center; background: rgba(0,0,0,0.72); padding: 24px; }
        .item-preview.show { display: flex; }
        .item-preview.show .item-preview-card { animation: softPop 0.24s ease both; }
        .item-preview-card { width: min(92vw, 390px); max-height: 92vh; overflow-y: auto; }
        .item-preview-photo { width: 100%; height: 220px; object-fit: cover; border-radius: 8px 8px 0 0; background: #f3f4f6; }
        .item-preview-close { position: fixed; top: 18px; right: 22px; width: 44px; height: 44px; border-radius: 999px; background: white; color: var(--primary); font-size: 1.4rem; display: inline-flex; align-items: center; justify-content: center; }
	        .cart-button { position: fixed; right: 24px; bottom: 28px; z-index: 80; width: 58px; height: 58px; display: inline-flex; align-items: center; justify-content: center; gap: 0; color: white; background: var(--primary); font-weight: 800; border: 0; border-radius: 999px; box-shadow: 0 14px 28px rgba(75,58,50,0.22); }
	        .cart-button i { font-size: 1.08rem; }
	        .cart-button-label { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; }
		        .cart-count { position: absolute; top: 3px; right: 3px; min-width: 20px; height: 20px; border-radius: 999px; background: var(--accent); color: white; font-size: 0.68rem; display: none; align-items: center; justify-content: center; padding: 0 5px; }
	        .cart-count.show { display: inline-flex; }
	        .cart-panel { position: fixed; inset: 0; z-index: 110; display: none; align-items: center; justify-content: center; background: rgba(0,0,0,0.58); padding: 18px; }
        .cart-panel.show { display: flex; }
			        .cart-drawer { width: min(94vw, 620px); max-height: 88vh; background: white; display: flex; flex-direction: column; border-radius: 18px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.28); }
		        .cart-stepper { display: flex; align-items: center; justify-content: center; gap: 0; padding: 20px 0 8px; }
		        .cart-step { width: 40px; height: 40px; border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; background: #f8f6f2; border: 3px solid #e1dcd4; color: #7a716b; font-weight: 900; position: relative; z-index: 1; }
		        .cart-step.active { background: var(--primary); border-color: var(--primary); color: white; }
		        .cart-step.done { background: #12b886; border-color: #12b886; color: white; }
		        .cart-step-line { width: 54px; height: 3px; background: #e1dcd4; }
		        .cart-step-line.done { background: #12b886; }
			        .cart-line { display: grid; grid-template-columns: 1fr auto auto; gap: 12px; align-items: center; padding: 18px; margin-bottom: 10px; border-bottom: 0; border-radius: 10px; background: #f3eee8; }
	        .cart-line:hover { background: #eee7df; }
	        .cart-item-info { display: flex; align-items: center; gap: 12px; min-width: 0; }
	        .cart-item-photo { width: 56px; height: 56px; border-radius: 6px; object-fit: cover; background: #e5e7eb; flex-shrink: 0; }
	        .cart-item-name { color: var(--primary); font-family: Georgia, 'Times New Roman', serif; font-size: 1rem; font-weight: 800; line-height: 1.2; }
	        .cart-item-price { color: var(--muted); font-weight: 600; font-size: 0.92rem; }
	        .qty-button { width: 30px; height: 30px; border-radius: 999px; border: 1px solid var(--secondary); color: var(--primary); font-weight: 800; transition: background-color 0.2s ease, color 0.2s ease, transform 0.16s ease, box-shadow 0.2s ease; }
	        .qty-button:hover { background: var(--primary); color: white; box-shadow: 0 6px 14px rgba(93,64,55,0.16); transform: translateY(-1px); }
	        .qty-button:active { transform: scale(0.9); }
	        .cart-qty-control { display: inline-flex; align-items: center; border: 1px solid #ded5cb; border-radius: 6px; overflow: hidden; background: white; }
	        .cart-qty-control .qty-button { border: 0; border-radius: 0; background: white; color: #2f2f2f; }
	        .cart-remove-button { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; color: #ef6464; font-size: 1.2rem; font-weight: 900; line-height: 1; border-radius: 999px; transition: background-color 0.2s ease, color 0.2s ease, transform 0.16s ease; }
	        .cart-remove-button:hover { background: #fee2e2; color: #dc2626; transform: translateY(-1px); }
		        .cart-total-row { border-top: 1px solid #ded5cb; padding-top: 20px; }
		        .cart-footer-button { display: flex; align-items: center; justify-content: center; width: 100% !important; max-width: none; margin-left: auto; margin-right: auto; min-height: 52px; font-size: 1rem; border-radius: 10px; }
	        .btn-buy { background-color: var(--primary); color: white; padding: 10px 14px; border-radius: 8px; font-weight: 800; text-align: center; transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease; font-size: 0.92rem; }
	        .btn-buy:hover { background-color: #382b25; transform: translateY(-1px); box-shadow: 0 8px 18px rgba(75,58,50,0.22); }
        .btn-add-icon { background-color: var(--primary); }
        .btn-dine-in { background-color: var(--primary); }
        .btn-dine-in:hover { background-color: #4a3029; box-shadow: 0 8px 18px rgba(93,64,55,0.2); }
	        .btn-take-out { background-color: var(--primary); }
	        .btn-take-out:hover { background-color: #4a3029; box-shadow: 0 8px 18px rgba(93,64,55,0.2); }
	        .checkout-option { min-height: 58px; border: 2px solid #ded5cb; border-radius: 14px; color: #111827; background: white; font-size: 1.05rem; font-weight: 900; display: flex; align-items: center; justify-content: center; gap: 10px; }
	        .checkout-option.active { background: var(--primary); border-color: var(--primary); color: white; }
		        .checkout-field { width: 100%; min-height: 56px; border: 1px solid #ded5cb; border-radius: 12px; padding: 0 18px; font-size: 1.05rem; color: #111827; background: white; }
		        .checkout-field::placeholder { color: #9ca3af; }
		        .checkout-field.invalid { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,0.12); }
		        .checkout-error { display: none; color: #dc2626; font-size: 0.82rem; font-weight: 800; margin-top: 6px; }
		        .checkout-error.show { display: block; }
	        .checkout-label { display: block; color: #6e625d; font-weight: 900; text-transform: uppercase; margin-bottom: 6px; font-size: 0.86rem; }
	        .checkout-card { background: #f3eee8; border-radius: 10px; padding: 14px; }
	        .checkout-map { height: 230px; border-radius: 12px; overflow: hidden; background: #e5e7eb; position: relative; border: 1px solid #d9e1dc; }
	        .checkout-map .leaflet-control-attribution { font-size: 0.68rem; }
	        .summary-row { display: flex; align-items: center; justify-content: space-between; gap: 16px; font-size: 1.08rem; color: #6e625d; margin-bottom: 10px; }
	        .summary-total { font-size: 1.3rem; font-weight: 900; color: #2f2f2f; }
	        .sold-out { filter: grayscale(100%); opacity: 0.6; pointer-events: none; position: relative; }
        .sold-out::after { content: 'UNAVAILABLE'; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: red; color: white; padding: 5px 10px; border-radius: 5px; font-weight: bold; }
					    @media (max-width: 640px) {
					            .customer-header nav { width: min(100% - 1.5rem, 1280px); }
				            .customer-header nav > a:first-child { gap: 12px; }
				            .brand-mark { width: 40px; height: 40px; font-size: 1.2rem; }
				            .brand-name { font-size: 1.05rem; }
				            .home-link { padding: 10px 14px; }
		            .menu-search-wrap { margin-bottom: 22px; }
		            .menu-search-bar { grid-template-columns: minmax(116px, 38%) 1fr 44px; min-height: 48px; }
		            .menu-category-toggle { padding: 0 13px 0 16px; font-size: 0.9rem; }
		            .menu-search-field { padding-left: 12px; font-size: 0.94rem; }
		            .menu-search-icon { width: 44px; height: 48px; }
		            .menu-category-menu { width: min(198px, 88vw); }
	            .menu-photo { height: 135px; }
		            .menu-card-body { min-height: 150px; padding: 14px; }
		            .menu-name { font-size: 1rem; }
		            .menu-description { font-size: 0.82rem; }
		            .cart-button { width: 52px; height: 52px; right: 14px; bottom: 16px; }
		            .cart-panel { padding: 12px; }
			            .cart-drawer { width: min(96vw, 420px); border-radius: 14px; }
			            .cart-drawer .btn-pos, .checkout-option { min-height: 46px; padding: 10px 12px; font-size: 0.95rem; }
			            .cart-stepper { padding-top: 18px; }
		            .cart-step { width: 42px; height: 42px; }
		            .cart-step-line { width: 36px; }
		            .cart-line { grid-template-columns: 1fr auto; gap: 10px; }
	            .cart-remove-button { grid-column: 2; }
	            .cart-item-photo { width: 48px; height: 48px; }
	            .cart-item-name { font-size: 0.95rem; }
		            .cart-qty-control .qty-button { width: 28px; height: 28px; }
			            .checkout-option { min-height: 48px; font-size: 0.95rem; }
			            .checkout-field { min-height: 48px; padding: 0 14px; font-size: 1rem; }
		            .checkout-map { height: 210px; }
	            .menu-actions { grid-template-columns: 1fr; }
	            .menu-actions.single-action { grid-template-columns: 1fr; }
	        }
    </style>
</head>
<body>
		    <header class="customer-header">
				        <nav class="max-w-7xl mx-auto px-4 py-5 flex items-center justify-between gap-5">
			            <a href="{{ route('home') }}" class="flex items-center gap-5 font-bold">
		                <img src="{{ asset('kermit.jpg') }}" alt="Kermit's Restaurant" class="brand-mark">
		                <span class="brand-name">Kermit's Restaurant</span>
		            </a>
		    </header>
	    <button type="button" class="cart-button" onclick="openCart()" aria-label="Open cart">
	        <i class="fas fa-bag-shopping"></i>
	        <span class="cart-button-label">Cart</span>
	        <span id="cart-count" class="cart-count">0</span>
	    </button>

		    <main class="max-w-5xl mx-auto px-4 py-7 md:py-9">
			        <section class="menu-surface">
		        <div class="mb-7 text-center">
	            <h1 class="menu-heading font-bold">Our Menu</h1>
	            <div class="menu-heading-rule"></div>
	        </div>

			        <div id="menu-search-wrap" class="menu-search-wrap">
			            <div class="menu-search-bar">
			                <button id="category-toggle" type="button" class="menu-category-toggle" onclick="toggleCategoryMenu(event)" aria-haspopup="listbox" aria-expanded="false">
			                    <span id="category-toggle-label">Foods</span>
			                    <i class="fas fa-chevron-down" aria-hidden="true"></i>
			                </button>
			                <input id="menu-search-input" class="menu-search-field" type="search" placeholder="Search menu..." oninput="setMenuSearch(this.value)" autocomplete="off" aria-label="Search menu">
			                <span class="menu-search-icon" aria-hidden="true"><i class="fas fa-search"></i></span>
			            </div>
			            <div id="category-list" class="menu-category-menu" role="listbox" aria-label="Menu categories"></div>
			        </div>
			        <div id="menu-grid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
			            <p class="text-gray-500">Loading menu...</p>
			        </div>
	        <p id="menu-error" class="hidden text-red-600 font-bold">Unable to load menu.</p>
	        </section>
	    </main>

    <div id="item-preview" class="item-preview" onclick="closeItemPreview(event)">
        <button type="button" class="item-preview-close" onclick="closeItemPreview(event)" aria-label="Close item preview">
            <i class="fas fa-times"></i>
        </button>
        <article id="item-preview-card" class="card menu-card item-preview-card border border-gray-100" onclick="event.stopPropagation()"></article>
    </div>

	    <div id="cart-panel" class="cart-panel" onclick="closeCart(event)">
		        <aside class="cart-drawer" onclick="event.stopPropagation()">
		            <div id="cart-stepper" class="cart-stepper"></div>
		            <div class="px-6 pt-2 pb-3 flex items-center justify-between gap-3" style="color: var(--primary);">
		                <h2 id="cart-title" class="text-2xl font-bold" style="font-family: Georgia, 'Times New Roman', serif;">Your Cart</h2>
		                <button type="button" class="text-2xl text-gray-500 hover:text-gray-900" onclick="closeCart()" aria-label="Close cart">
		                    <i class="fas fa-times"></i>
		                </button>
		            </div>
		            <div id="cart-items" class="flex-1 overflow-y-auto px-6 py-2"></div>
		            <div id="cart-footer" class="px-6 pb-5 pt-2"></div>
	        </aside>
	    </div>

	    <script>
        const MENU_DATA_URL = "{{ route('cashier.pos.data') }}";
        const CUSTOMER_ORDER_URL = "{{ route('customer.menu.order') }}";
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
	        const categoryLabels = {
	            all: 'All',
	            foods: 'Foods',
	            pasta: 'Pasta',
	            drinks: 'Cafe Drinks',
            coffee: 'Coffee',
            cake: 'Cake',
            pastry: 'Pastry',
            meal: 'Meals'
	        };
        const defaultMenuItems = [
            { id: 'default-food-1', name: 'Chicken Alfredo Pasta', desc: 'Creamy pasta with tender chicken and herbs.', price: 185, type: 'foods', img: 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?auto=format&fit=crop&w=700&q=80', stock: 50 },
            { id: 'default-food-2', name: 'Clubhouse Sandwich', desc: 'Layered toast, egg, vegetables, cheese, and savory filling.', price: 145, type: 'foods', img: 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=700&q=80', stock: 50 },
            { id: 'default-food-3', name: 'Fresh Garden Salad', desc: 'Crisp greens with bright dressing and fresh toppings.', price: 110, type: 'foods', img: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=700&q=80', stock: 50 },
            { id: 'default-pasta-1', name: 'Baked Spaghetti', desc: 'Baked spaghetti with savory sauce.', price: 260, type: 'pasta', img: 'https://images.unsplash.com/photo-1622973536968-3ead9e780960?auto=format&fit=crop&w=700&q=80', stock: 50 },
            { id: 'default-drink-1', name: 'Iced Spanish Latte', desc: 'Chilled espresso with sweet, creamy milk.', price: 120, type: 'drinks', img: 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=700&q=80', stock: 50 },
            { id: 'default-cake-1', name: 'Choco Overload', desc: 'Rich chocolate cake for celebrations.', price: 900, type: 'cake', img: 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=700&q=80', stock: 10 }
        ];
					        let menuItems = [];
				        let cart = [];
					        let activeCategory = 'foods';
				        let menuSearchQuery = '';
		        let isSubmittingOrder = false;
	        let checkoutStep = 1;
	        let checkoutState = {
	            fulfillment: 'Delivery',
	            paymentMethod: 'cash-on-delivery',
	            fullName: '',
	            phone: '',
	            municipality: '',
	            barangay: '',
	            street: '',
	            latitude: 11.1564,
	            longitude: 123.8051
	        };
		        let checkoutMap = null;
		        let checkoutMapMarker = null;
		        const DELIVERY_FEE = 20;
			        const DELIVERY_AREAS = {
			            'Santa Fe': ['Hagdan', 'Hilantagaan', 'Kinatarkan', 'Langub', 'Maricaban', 'Okoy', 'Poblacion', 'Balidbid', 'Pooc', 'Talisay'],
			            'Madridejos': ['Bunakan', 'Kangwayan', 'Kaongkod', 'Kodia', 'Maalat', 'Malbago', 'Mancilang', 'Pili', 'Poblacion', 'San Agustin', 'Tabagak', 'Talangnan', 'Tarong', 'Tugas'],
			            'Bantayan': ['Atop-atop', 'Baigad', 'Baod', 'Binaobao', 'Botigues', 'Kabac', 'Doong', 'Hilotongan', 'Guiwanon', 'Kabangbang', 'Kampingganon', 'Kangkaibe', 'Lipayran', 'Luyongbaybay', 'Mojon', 'Obo-ob', 'Patao', 'Putian', 'Sillon', 'Sungko', 'Suba', 'Sulangan', 'Tamiao', 'Bantigue', 'Ticad']
			        };
			        const DELIVERY_MAP_CENTERS = {
			            'Santa Fe': [11.1564, 123.8051],
			            'Bantayan': [11.1689, 123.7228],
			            'Madridejos': [11.2664, 123.7339]
			        };
			        const DELIVERY_MAP_BOUNDS = [
			            [11.06, 123.62],
			            [11.34, 123.88]
			        ];

        function escapeHtml(value) {
            const div = document.createElement('div');
            div.textContent = value ?? '';
            return div.innerHTML;
        }

        function showCustomerAlert(icon, title, text = '') {
            if(window.Swal) {
                return Swal.fire({
                    icon,
                    title,
                    text,
                    confirmButtonColor: '#5D4037',
                    width: 360
                });
            }
            alert(text ? `${title}\n${text}` : title);
        }

        function formatPrice(value) {
            return `₱${Number(value || 0).toLocaleString('en-PH', { minimumFractionDigits: 0, maximumFractionDigits: 2 })}`;
        }

        function menuCategoryName(category) {
            return categoryLabels[category] || String(category || 'foods').replace(/[-_]/g, ' ').replace(/\b\w/g, char => char.toUpperCase());
        }

	        function isBestSeller(item) {
	            return Boolean(item?.bestSeller || item?.isBestSeller || item?.featured);
	        }

	        function getCategories() {
	            const preferredOrder = ['foods', 'pasta', 'drinks', 'cake', 'coffee', 'pastry', 'meal'];
	            const types = [...new Set(menuItems.map(item => item.type || 'foods'))];
	            const sortedTypes = types.sort((a, b) => {
	                const aIndex = preferredOrder.indexOf(a);
	                const bIndex = preferredOrder.indexOf(b);
	                if(aIndex !== -1 || bIndex !== -1) return (aIndex === -1 ? 999 : aIndex) - (bIndex === -1 ? 999 : bIndex);
	                return String(a).localeCompare(String(b));
	            });
	            return ['all', ...sortedTypes];
	        }

        function renderMenuImage(item) {
            const img = item.img || '🍽';
            if(String(img).startsWith('http') || String(img).startsWith('data:image/') || String(img).startsWith('/menu-images/')) {
                return `<button type="button" class="menu-photo-button" onclick='openItemPreview(${JSON.stringify(String(item.id))})' aria-label="View ${escapeHtml(item.name)}">
                    <img class="menu-photo w-full" src="${escapeHtml(img)}" alt="${escapeHtml(item.name)}">
                </button>`;
            }

            return `<div class="menu-photo w-full flex items-center justify-center bg-gray-100 text-6xl">${escapeHtml(img)}</div>`;
        }

        function renderBestSellerBadge(item, index = 0) {
            return isBestSeller(item, index)
                ? '<span class="menu-badge"><i class="fas fa-fire"></i> Best Seller</span>'
                : '';
        }

        function openItemPreview(id, mode = 'cart') {
            const item = menuItems.find(menuItem => String(menuItem.id) === String(id));
            if(!item) return;
            const preview = document.getElementById('item-preview');
            const card = document.getElementById('item-preview-card');
            card.className = `card menu-card item-preview-card border border-gray-100 ${!isItemAvailable(item) ? 'sold-out' : ''}`;
            card.innerHTML = renderSoloItemCard(item, mode);
            preview.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeItemPreview(event) {
            if(event && event.target && event.currentTarget !== event.target && !event.target.closest('.item-preview-close')) return;
            const preview = document.getElementById('item-preview');
            const card = document.getElementById('item-preview-card');
            preview.classList.remove('show');
            card.innerHTML = '';
            document.body.style.overflow = '';
        }

	        document.addEventListener('keydown', (event) => {
	            if(event.key === 'Escape') closeItemPreview();
	        });

	        function isItemAvailable(item) {
            return Number(item?.stock ?? 1) > 0;
        }

        function renderPreviewItemBadge(item) {
            if(isBestSeller(item)) {
                return '<div class="mb-5"><span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold text-white" style="background: var(--accent);"><i class="fas fa-fire mr-1"></i> Best Seller</span></div>';
            }

            return isItemAvailable(item)
                ? ''
                : '<div class="mb-5"><span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">Unavailable</span></div>';
        }

        function getSelectedItemQty(id) {
            const qtyElement = document.querySelector(`[data-preview-qty="${CSS.escape(String(id))}"]`);
            return Math.max(1, Number(qtyElement?.textContent || 1));
        }

        function changeSelectedItemQty(id, amount, event) {
            if(event) event.stopPropagation();
            const key = String(id);
            const nextQty = Math.max(1, getSelectedItemQty(key) + amount);
            document.querySelectorAll(`[data-preview-qty="${CSS.escape(key)}"]`).forEach(element => {
                element.textContent = nextQty;
            });
        }

        function renderPreviewQtyControl(item) {
            const id = String(item.id);
            const disabled = !isItemAvailable(item) ? 'disabled' : '';
            return `
                <div class="menu-qty-control" onclick="event.stopPropagation()" aria-label="Quantity">
                    <button type="button" class="menu-qty-button" onclick='changeSelectedItemQty(${JSON.stringify(id)}, -1, event)' ${disabled}>-</button>
                    <span class="menu-qty-value" data-preview-qty="${escapeHtml(id)}">1</span>
                    <button type="button" class="menu-qty-button" onclick='changeSelectedItemQty(${JSON.stringify(id)}, 1, event)' ${disabled}>+</button>
                </div>
            `;
        }

        function getCartCount() {
            return cart.reduce((sum, item) => sum + item.qty, 0);
        }

		        function getCartTotal() {
		            return cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
		        }

	        function getCheckoutTotal() {
	            return getCartTotal() + (checkoutState.fulfillment === 'Delivery' ? DELIVERY_FEE : 0);
	        }

	        function setCartTitle(title) {
	            document.getElementById('cart-title').textContent = title;
	        }

	        function renderCartStepper(step = checkoutStep) {
	            const stepper = document.getElementById('cart-stepper');
	            stepper.innerHTML = `
	                <span class="cart-step ${step === 1 ? 'active' : 'done'}">1</span>
	                <span class="cart-step-line ${step > 1 ? 'done' : ''}"></span>
	                <span class="cart-step ${step === 2 ? 'active' : step > 2 ? 'done' : ''}">2</span>
	                <span class="cart-step-line ${step > 2 ? 'done' : ''}"></span>
	                <span class="cart-step ${step === 3 ? 'active' : ''}">3</span>
	            `;
	        }

			        function captureCheckoutFields() {
			            const fullName = document.getElementById('checkout-full-name');
				            const phone = document.getElementById('checkout-phone');
				            const municipality = document.getElementById('checkout-municipality');
				            const barangay = document.getElementById('checkout-barangay');
				            const street = document.getElementById('checkout-street');
				            if(fullName) checkoutState.fullName = fullName.value.trim();
				            if(phone) checkoutState.phone = phone.value.trim();
				            if(municipality) checkoutState.municipality = municipality.value;
				            if(barangay) checkoutState.barangay = barangay.value;
				            if(street) checkoutState.street = street.value.trim();
				        }

			        function clearCheckoutValidation() {
			            document.querySelectorAll('.checkout-field.invalid').forEach(field => field.classList.remove('invalid'));
			            document.querySelectorAll('.checkout-error').forEach(error => {
			                error.textContent = '';
			                error.classList.remove('show');
			            });
			        }

			        function showCheckoutFieldError(id, message) {
			            const field = document.getElementById(id);
			            const error = document.getElementById(`${id}-error`);
			            if(field) field.classList.add('invalid');
			            if(error) {
			                error.textContent = message;
			                error.classList.add('show');
			            }
			            return field;
			        }

			        function validateCheckoutDetails() {
			            clearCheckoutValidation();
			            const invalidFields = [];
			            const phoneValue = checkoutState.phone.replace(/[\s-]/g, '');

			            if(!checkoutState.fullName) {
			                invalidFields.push(showCheckoutFieldError('checkout-full-name', 'Full name is required.'));
			            }

			            if(!phoneValue) {
			                invalidFields.push(showCheckoutFieldError('checkout-phone', 'Phone number is required.'));
			            } else if(!/^09\d{9}$/.test(phoneValue)) {
			                invalidFields.push(showCheckoutFieldError('checkout-phone', 'Use an 11-digit mobile number, like 09123456789.'));
			            }

			            if(checkoutState.fulfillment === 'Delivery') {
			                if(!checkoutState.municipality) {
			                    invalidFields.push(showCheckoutFieldError('checkout-municipality', 'Please select a municipality.'));
			                }
			                if(!checkoutState.barangay) {
			                    invalidFields.push(showCheckoutFieldError('checkout-barangay', 'Please select a barangay.'));
			                }
			                if(!checkoutState.street) {
			                    invalidFields.push(showCheckoutFieldError('checkout-street', 'Street or landmark is required.'));
			                }
			            }

			            const firstInvalid = invalidFields.find(Boolean);
			            if(firstInvalid) {
			                firstInvalid.focus();
			                return false;
			            }

			            checkoutState.phone = phoneValue;
			            return true;
			        }

			        function handleCheckoutPhoneInput(input) {
			            const digits = input.value.replace(/\D/g, '');
			            if(digits.length > 11) {
			                input.value = digits.slice(0, 11);
			                showCustomerAlert('warning', 'Phone Number Limit', 'Phone number must be 11 digits only.');
			                return;
			            }
			            input.value = digits;
			        }

			        function renderSelectOptions(items, selectedValue, placeholder) {
			            return [
			                `<option value="">${escapeHtml(placeholder)}</option>`,
			                ...items.map(item => `<option value="${escapeHtml(item)}" ${selectedValue === item ? 'selected' : ''}>${escapeHtml(item)}</option>`)
			            ].join('');
			        }

					        function setCheckoutMunicipality(value) {
					            captureCheckoutFields();
					            checkoutState.municipality = value;
					            checkoutState.barangay = '';
					            const center = DELIVERY_MAP_CENTERS[value];
					            if(center) {
					                setDeliveryPin(center[0], center[1]);
					            }
					            buyOrder();
					        }

				        function setCheckoutBarangay(value) {
				            captureCheckoutFields();
				            checkoutState.barangay = value;
				            buyOrder();
				        }

		        function setDeliveryPin(lat, lng) {
		            checkoutState.latitude = Number(lat);
		            checkoutState.longitude = Number(lng);
		            if(checkoutMapMarker) {
		                checkoutMapMarker.setLatLng([checkoutState.latitude, checkoutState.longitude]);
		            }
		        }

		        function initCheckoutMap() {
		            const mapElement = document.getElementById('checkout-map');
		            if(!mapElement || !window.L) return;
		            const center = [checkoutState.latitude, checkoutState.longitude];

		            if(checkoutMap) {
		                checkoutMap.remove();
		                checkoutMap = null;
		                checkoutMapMarker = null;
		            }

			            checkoutMap = L.map(mapElement, {
			                center,
			                zoom: 14,
			                scrollWheelZoom: false,
			                maxBounds: DELIVERY_MAP_BOUNDS,
			                maxBoundsViscosity: 0.85
			            });
			            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			                maxZoom: 19,
			                minZoom: 11,
			                attribution: '&copy; OpenStreetMap contributors'
			            }).addTo(checkoutMap);
			            checkoutMap.fitBounds(DELIVERY_MAP_BOUNDS);
			            checkoutMap.setView(center, 14);
		            checkoutMapMarker = L.marker(center, { draggable: true }).addTo(checkoutMap);
		            checkoutMapMarker.on('dragend', event => {
		                const position = event.target.getLatLng();
		                setDeliveryPin(position.lat, position.lng);
		            });
		            checkoutMap.on('click', event => setDeliveryPin(event.latlng.lat, event.latlng.lng));
		            setTimeout(() => checkoutMap?.invalidateSize(), 80);
		        }
	
	        function getDeliveryAddress() {
	            const addressParts = [
	                checkoutState.street,
	                checkoutState.barangay,
	                checkoutState.municipality,
	                'Bantayan Island, Cebu'
	            ].filter(Boolean);
	            if(checkoutState.latitude && checkoutState.longitude) {
	                addressParts.push(`Map pin: ${Number(checkoutState.latitude).toFixed(6)}, ${Number(checkoutState.longitude).toFixed(6)}`);
	            }
	            return addressParts.join(', ');
	        }

	        function renderCartItemPhoto(item) {
	            const menuItem = menuItems.find(menuEntry => String(menuEntry.id) === String(item.id));
	            const img = item.img || menuItem?.img || '🍽';
	            if(String(img).startsWith('http') || String(img).startsWith('data:image/') || String(img).startsWith('/menu-images/')) {
	                return `<img class="cart-item-photo" src="${escapeHtml(img)}" alt="${escapeHtml(item.name)}">`;
	            }
	            return `<div class="cart-item-photo flex items-center justify-center text-3xl">${escapeHtml(img)}</div>`;
	        }

	        function updateCartCount() {
	            const count = getCartCount();
	            const badge = document.getElementById('cart-count');
	            badge.textContent = count;
	            badge.classList.toggle('show', count > 0);
	        }

		        function renderCart() {
		            const cartItems = document.getElementById('cart-items');
		            const cartFooter = document.getElementById('cart-footer');
		            checkoutStep = 1;
		            renderCartStepper(1);
		            setCartTitle('Your Cart');
		            updateCartCount();
	
		            if(cart.length === 0) {
		                cartItems.innerHTML = '<p class="text-gray-500 text-center py-12 text-lg">Your cart is empty.</p>';
		                cartFooter.innerHTML = '';
		                return;
		            }
	
		            cartItems.innerHTML = cart.map((item, index) => `
		                <div class="cart-line" style="--cart-line-index: ${index}">
		                    <div class="cart-item-info">
		                        ${renderCartItemPhoto(item)}
		                        <div class="min-w-0">
		                            <h3 class="cart-item-name">${escapeHtml(item.name)}</h3>
		                            <p class="cart-item-price">${formatPrice(item.price)}</p>
		                        </div>
		                    </div>
		                    <div class="cart-qty-control">
		                        <button type="button" class="qty-button" onclick='changeCartQty(${JSON.stringify(String(item.id))}, -1)'>-</button>
		                        <span class="font-bold w-10 text-center">${item.qty}</span>
		                        <button type="button" class="qty-button" onclick='changeCartQty(${JSON.stringify(String(item.id))}, 1)'>+</button>
		                    </div>
		                    <button type="button" class="cart-remove-button" onclick='removeCartItem(${JSON.stringify(String(item.id))})' aria-label="Clear ${escapeHtml(item.name)}">x</button>
		                </div>
		            `).join('');
	
		            cartFooter.innerHTML = `
		                <div class="cart-total-row flex items-center justify-between text-2xl font-bold mb-6" style="color: #2f2f2f;">
		                    <span>Subtotal</span>
		                    <span>${formatPrice(getCartTotal())}</span>
		                </div>
		                <button type="button" class="btn-buy cart-footer-button w-full" onclick="buyOrder()">
		                    Proceed to Checkout
		                </button>
		            `;
		        }
	
	        function setFulfillment(type) {
	            captureCheckoutFields();
	            checkoutState.fulfillment = type;
	            buyOrder();
	        }

	        function setPaymentMethod(method) {
	            checkoutState.paymentMethod = method;
	        }

	        function buyOrder() {
	            if(cart.length === 0) {
	                showCustomerAlert('warning', 'Cart Is Empty', 'Please add an item first.');
	                return;
	            }
	
	            checkoutStep = 2;
	            renderCartStepper(2);
	            setCartTitle('Delivery Details');
	            const cartItems = document.getElementById('cart-items');
	            const cartFooter = document.getElementById('cart-footer');
	            const isDelivery = checkoutState.fulfillment === 'Delivery';
	
	            cartItems.innerHTML = `
		                <div class="grid grid-cols-2 gap-3 mb-5">
	                    <button type="button" class="checkout-option ${isDelivery ? 'active' : ''}" onclick="setFulfillment('Delivery')">
	                        <i class="fas fa-motorcycle"></i> Delivery
	                    </button>
	                    <button type="button" class="checkout-option ${!isDelivery ? 'active' : ''}" onclick="setFulfillment('Pick-up')">
	                        <i class="fas fa-store"></i> Pick-up
	                    </button>
	                </div>
		                <div class="space-y-5">
		                    <div>
		                        <label class="checkout-label" for="checkout-full-name">Full Name</label>
		                        <input id="checkout-full-name" class="checkout-field" type="text" placeholder="Juan Dela Cruz" value="${escapeHtml(checkoutState.fullName)}" required>
		                        <p id="checkout-full-name-error" class="checkout-error"></p>
		                    </div>
		                    <div>
		                        <label class="checkout-label" for="checkout-phone">Phone Number</label>
			                        <input id="checkout-phone" class="checkout-field" type="tel" inputmode="numeric" maxlength="11" placeholder="0912 345 6789" value="${escapeHtml(checkoutState.phone)}" oninput="handleCheckoutPhoneInput(this)" required>
		                        <p id="checkout-phone-error" class="checkout-error"></p>
		                    </div>
	                    ${isDelivery ? `
		                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
	                            <div>
	                                <label class="checkout-label" for="checkout-municipality">Municipality</label>
			                                <select id="checkout-municipality" class="checkout-field" onchange="setCheckoutMunicipality(this.value)">
			                                    ${renderSelectOptions(Object.keys(DELIVERY_AREAS), checkoutState.municipality, 'Select Municipality')}
			                                </select>
			                                <p id="checkout-municipality-error" class="checkout-error"></p>
			                            </div>
			                            <div>
			                                <label class="checkout-label" for="checkout-barangay">Barangay</label>
			                                <select id="checkout-barangay" class="checkout-field" onchange="setCheckoutBarangay(this.value)">
			                                    ${renderSelectOptions(DELIVERY_AREAS[checkoutState.municipality] || [], checkoutState.barangay, checkoutState.municipality ? 'Select Barangay' : 'Select Municipality First')}
			                                </select>
			                                <p id="checkout-barangay-error" class="checkout-error"></p>
			                            </div>
			                        </div>
		                        <div>
		                            <label class="checkout-label" for="checkout-street">Street / Landmark</label>
		                            <input id="checkout-street" class="checkout-field" type="text" placeholder="Near the chapel, Street name..." value="${escapeHtml(checkoutState.street)}" required>
		                            <p id="checkout-street-error" class="checkout-error"></p>
		                        </div>
	                        <div>
	                            <label class="checkout-label">Delivery Location</label>
	                            <div id="checkout-map" class="checkout-map"></div>
	                            <p class="text-sm mt-2" style="color: var(--muted);"><i class="fas fa-circle-info"></i> Click the map or drag the pin to set the exact location.</p>
	                        </div>
	                    ` : `
	                        <div class="checkout-card flex items-start gap-4">
	                            <i class="fas fa-store mt-1" style="color: var(--accent);"></i>
	                            <div>
	                                <h3 class="font-bold text-lg">Kermit's Cafe & Pastry</h3>
	                                <p style="color: var(--muted);">Bantayan, Bantayan Island, Cebu<br>Open daily: 7:00 AM - 9:00 PM</p>
	                            </div>
	                        </div>
	                    `}
	                </div>
	            `;
	
	            cartFooter.innerHTML = `
	                <div class="grid grid-cols-2 gap-3">
	                    <button type="button" class="cart-footer-button border font-bold" style="border-color: #ded5cb;" onclick="renderCart()">Back</button>
	                    <button type="button" class="btn-buy cart-footer-button" onclick="reviewOrder()">Review Order</button>
	                </div>
	            `;
	            if(isDelivery) {
	                setTimeout(initCheckoutMap, 0);
	            }
				document.getElementById('cart-panel').classList.add('show');
				document.body.style.overflow = 'hidden';
			}
	
	        function reviewOrder() {
	            if(cart.length === 0) {
	                showCustomerAlert('warning', 'Cart Is Empty', 'Please add an item first.');
	                return;
		            }
			            captureCheckoutFields();
			            if(!validateCheckoutDetails()) {
			                return;
			            }
			            checkoutStep = 3;
	            renderCartStepper(3);
	            setCartTitle('Order Summary');
	
	            const cartItems = document.getElementById('cart-items');
	            const cartFooter = document.getElementById('cart-footer');
	            const isDelivery = checkoutState.fulfillment === 'Delivery';
	            const address = isDelivery ? getDeliveryAddress() : "Kermit's Cafe & Pastry, Bantayan, Bantayan Island, Cebu";
	
	            cartItems.innerHTML = `
	                <div class="space-y-3">
	                    ${cart.map(item => `
	                        <div class="flex items-center justify-between gap-4 text-base">
	                            <span>${item.qty}&times; ${escapeHtml(item.name)}</span>
	                            <strong>${formatPrice(item.price * item.qty)}</strong>
	                        </div>
	                    `).join('')}
	                    <div class="checkout-card">
	                        <div class="font-bold uppercase mb-2" style="color: var(--muted);">
	                            <i class="fas ${isDelivery ? 'fa-location-dot' : 'fa-store'} mr-1" style="color: var(--accent);"></i>
	                            ${isDelivery ? 'Delivery Address' : 'Pick-up Location'}
	                        </div>
	                        <p class="text-lg">${escapeHtml(checkoutState.street || checkoutState.fullName || "Kermit's Cafe & Pastry")}</p>
	                        <p style="color: var(--muted);">${escapeHtml(address)}</p>
	                    </div>
	                    ${isDelivery ? `<div class="checkout-card">
                        <div class="font-bold uppercase mb-3" style="color: var(--muted);">
                            <i class="fas fa-credit-card mr-1" style="color: var(--accent);"></i>
                            Payment Method
                        </div>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="radio" name="payment-method" value="cash-on-delivery" checked onchange="setPaymentMethod(this.value)" class="w-4 h-4">
                                <span class="text-base">Cash on Delivery</span>
                            </label>
                        </div>
                    </div>` : ''}
	                    <div class="border-t pt-4 mt-4" style="border-color: #ded5cb;">
	                        <div class="summary-row"><span>Subtotal</span><span>${formatPrice(getCartTotal())}</span></div>
	                        ${isDelivery ? `<div class="summary-row"><span>Delivery Fee</span><span>${formatPrice(DELIVERY_FEE)}</span></div>` : ''}
	                        <div class="summary-row summary-total border-t pt-4" style="border-color: #ded5cb;"><span>Total</span><span style="color: var(--accent);">${formatPrice(getCheckoutTotal())}</span></div>
	                    </div>
	                </div>
	            `;
	            cartFooter.innerHTML = `
	                <div class="grid grid-cols-2 gap-3">
	                    <button type="button" class="cart-footer-button border font-bold" style="border-color: #ded5cb;" onclick="buyOrder()">Back</button>
	                    <button type="button" class="btn-buy cart-footer-button" style="background: var(--accent);" onclick="submitCustomerOrder(event)">
	                        <i class="fas fa-check mr-2"></i> Place Order
	                    </button>
	                </div>
	            `;
	            document.getElementById('cart-panel').classList.add('show');
	            document.body.style.overflow = 'hidden';
	        }

	        async function submitCustomerOrder(event = null) {
	            if(cart.length === 0) {
	                showCustomerAlert('warning', 'Cart Is Empty', 'Please add an item first.');
	                return;
	            }
	            captureCheckoutFields();
	
	            if(isSubmittingOrder) {
	                return;
	            }
	
		            const submitButton = event?.currentTarget;
		            const orderType = checkoutState.fulfillment === 'Pick-up' ? 'Take Out' : 'Take Out';
		            const paymentMethod = checkoutState.paymentMethod === 'cash-on-delivery' ? 
		                (checkoutState.fulfillment === 'Delivery' ? 'Cash - Delivery' : 'Cash - Pick-up') : 
		                'Online Payment';
		            const deliveryAddress = checkoutState.fulfillment === 'Delivery' ? getDeliveryAddress() : "Kermit's Cafe & Pastry, Bantayan, Bantayan Island, Cebu";
			            if(!checkoutState.fullName || !/^09\d{9}$/.test(checkoutState.phone.replace(/[\s-]/g, ''))) {
			                showCustomerAlert('warning', 'Customer Details Needed', 'Please enter a full name and valid 11-digit phone number.');
			                return;
			            }
			            if(checkoutState.fulfillment === 'Delivery' && (!checkoutState.street || !checkoutState.municipality || !checkoutState.barangay || !deliveryAddress)) {
			                showCustomerAlert('warning', 'Delivery Address Needed', 'Please enter your delivery address.');
			                return;
			            }
		            isSubmittingOrder = true;
	            if(submitButton) {
	                submitButton.disabled = true;
	                submitButton.classList.add('opacity-60', 'cursor-not-allowed');
	            }

            try {
                const response = await fetch(CUSTOMER_ORDER_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
			                    body: JSON.stringify({
			                        order_type: orderType,
			                        payment_method: paymentMethod,
			                        customer_name: checkoutState.fullName,
			                        delivery_address: deliveryAddress,
			                        delivery_fee: checkoutState.fulfillment === 'Delivery' ? DELIVERY_FEE : 0,
		                        items: cart.map(item => ({
	                            id: item.id,
                            qty: Number(item.qty) || 1
                        }))
                    })
                });

                const data = await response.json().catch(() => ({}));
                if(!response.ok) {
                    showCustomerAlert('error', 'Order Not Sent', data.message || 'Unable to send order. Please try again.');
                    return;
                }

	                const order = data.order || {};
	                const total = Number(order.total ?? getCartTotal());
		                const trackingNumber = order.queueNum || order.id || '-';
		                showCustomerAlert('success', 'Order Placed', `Thank you. Tracking Order #${trackingNumber} | ${checkoutState.fulfillment} | Total: ${formatPrice(total)}`);
	                cart = [];
	                renderCart();
	                closeCart();
            } catch (error) {
                showCustomerAlert('error', 'Order Not Sent', 'Unable to send order. Please try again.');
            } finally {
                isSubmittingOrder = false;
                if(submitButton) {
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-60', 'cursor-not-allowed');
                }
            }
        }

        function addToCart(id, event) {
            if(event) event.stopPropagation();
	            const item = menuItems.find(menuItem => String(menuItem.id) === String(id));
	            if(!item || !isItemAvailable(item)) return;
	            const qty = getSelectedItemQty(id);
	
	            const existing = cart.find(cartItem => String(cartItem.id) === String(id));
            if(existing) {
                existing.qty += qty;
            } else {
	                cart.push({
	                    id: item.id,
	                    category: item.type || 'foods',
	                    name: item.name,
	                    price: Number(item.price) || 0,
	                    img: item.img || '',
	                    qty
	                });
            }

            renderCart();
            closeItemPreview();
        }

        function buyMenuItem(id, event) {
            if(event) event.stopPropagation();
            const item = menuItems.find(menuItem => String(menuItem.id) === String(id));
            if(!item || !isItemAvailable(item)) return;

            cart = [{
                id: item.id,
	                category: item.type || 'foods',
	                name: item.name,
	                price: Number(item.price) || 0,
	                img: item.img || '',
	                qty: getSelectedItemQty(id)
	            }];
            renderCart();
            closeItemPreview();
            buyOrder();
        }

        function changeCartQty(id, amount) {
            const item = cart.find(cartItem => String(cartItem.id) === String(id));
            if(!item) return;
            item.qty += amount;
            if(item.qty <= 0) cart = cart.filter(cartItem => String(cartItem.id) !== String(id));
            renderCart();
        }

        function removeCartItem(id) {
            cart = cart.filter(item => String(item.id) !== String(id));
            renderCart();
        }

        function openCart() {
            renderCart();
            document.getElementById('cart-panel').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeCart(event) {
            if(event && event.target && event.currentTarget !== event.target) return;
            document.getElementById('cart-panel').classList.remove('show');
            if(!document.getElementById('item-preview').classList.contains('show')) {
                document.body.style.overflow = '';
            }
        }

	        function setCategory(category) {
	            activeCategory = category;
	            closeCategoryMenu();
	            renderMenu();
	        }

	        function setMenuSearch(value) {
	            menuSearchQuery = String(value || '').trim().toLowerCase();
	            renderMenu();
	        }

	        function toggleCategoryMenu(event) {
	            if(event) event.stopPropagation();
	            const wrap = document.getElementById('menu-search-wrap');
	            const toggle = document.getElementById('category-toggle');
	            const isOpen = wrap.classList.toggle('open');
	            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
	        }

	        function closeCategoryMenu() {
	            const wrap = document.getElementById('menu-search-wrap');
	            const toggle = document.getElementById('category-toggle');
	            if(wrap) wrap.classList.remove('open');
	            if(toggle) toggle.setAttribute('aria-expanded', 'false');
	        }

        function renderSoloItemCard(item, mode = 'cart') {
            const img = item.img || '🍽';
            const isImage = String(img).startsWith('http') || String(img).startsWith('data:image/') || String(img).startsWith('/menu-images/');
            const media = isImage
                ? `<img class="item-preview-photo" src="${escapeHtml(img)}" alt="${escapeHtml(item.name)}">`
                : `<div class="item-preview-photo flex items-center justify-center text-6xl">${escapeHtml(img)}</div>`;
            const isBuyMode = mode === 'buy';

            return `
                ${media}
                <div class="menu-card-body">
                    <h3 class="menu-name">${escapeHtml(item.name)}</h3>
                    <p class="menu-description mb-4 flex-1">${escapeHtml(item.desc || 'Ready to add to this order.')}</p>
                    <p class="menu-price mb-2">${formatPrice(item.price)}</p>
                    ${renderPreviewItemBadge(item)}
                    ${renderPreviewQtyControl(item)}
                    <div class="menu-actions single-action">
                        <button type="button" class="btn-buy" onclick='${isBuyMode ? `buyMenuItem(${JSON.stringify(String(item.id))}, event)` : `addToCart(${JSON.stringify(String(item.id))}, event)`}' ${!isItemAvailable(item) ? 'disabled' : ''}>
                            <i class="fas ${isBuyMode ? 'fa-bag-shopping' : 'fa-cart-plus'} mr-1"></i> ${isBuyMode ? 'Buy' : 'Add to Cart'}
                        </button>
                    </div>
                </div>
            `;
        }

		        function renderCategories() {
		            const categories = getCategories();
		            if(!categories.includes(activeCategory)) activeCategory = 'all';
		            document.getElementById('category-toggle-label').textContent = menuCategoryName(activeCategory);
		            document.getElementById('category-list').innerHTML = categories.map(category => `
                <button type="button" class="menu-category-option ${activeCategory === category ? 'active' : ''}" onclick='setCategory(${JSON.stringify(String(category))})' role="option" aria-selected="${activeCategory === category ? 'true' : 'false'}">
                    ${escapeHtml(menuCategoryName(category))}
                </button>
            `).join('');
	        }

		        function renderMenu() {
		            renderCategories();
		            const categoryItems = activeCategory === 'all'
		                ? menuItems
		                : menuItems.filter(item => (item.type || 'foods') === activeCategory);
		            const visibleItems = menuSearchQuery
		                ? categoryItems.filter(item => {
		                    const searchable = [
		                        item.name,
		                        item.desc,
		                        item.type,
		                        menuCategoryName(item.type || 'foods')
		                    ].join(' ').toLowerCase();
		                    return searchable.includes(menuSearchQuery);
		                })
		                : categoryItems;

		            document.getElementById('menu-grid').innerHTML = visibleItems.map((item, index) => `
	                <article class="card menu-card ${!isItemAvailable(item) ? 'sold-out' : ''}" style="--card-index: ${index}">
	                    ${renderBestSellerBadge(item, index)}
	                    ${renderMenuImage(item)}
	                    <div class="menu-card-body cursor-pointer" onclick='openItemPreview(${JSON.stringify(String(item.id))})'>
	                        <div class="menu-card-title-row">
	                            <h3 class="menu-name">${escapeHtml(item.name)}</h3>
	                            <p class="menu-price">${formatPrice(item.price)}</p>
	                        </div>
	                        <p class="menu-description flex-1">${escapeHtml(item.desc || 'Ready to add to this order.')}</p>
	                        <div class="menu-actions">
	                            <button type="button" class="btn-buy" onclick='addToCart(${JSON.stringify(String(item.id))}, event)' ${!isItemAvailable(item) ? 'disabled' : ''}>
	                                <i class="fas fa-plus mr-2"></i> Add to Cart
	                            </button>
	                        </div>
	                    </div>
	                </article>
	            `).join('') || '<p class="text-gray-500">No menu items match your search.</p>';
	        }

	        document.addEventListener('click', closeCategoryMenu);

	        fetch(MENU_DATA_URL)
	            .then(response => {
	                if(!response.ok) throw new Error('Menu request failed');
	                return response.json();
	            })
	            .then(data => {
	                const loadedMenu = Array.isArray(data.menu) ? data.menu : [];
	                menuItems = loadedMenu.length ? loadedMenu : defaultMenuItems;
	                renderMenu();
	            })
	            .catch(() => {
	                menuItems = defaultMenuItems;
	                renderMenu();
	            });
    </script>
</body>
</html>



