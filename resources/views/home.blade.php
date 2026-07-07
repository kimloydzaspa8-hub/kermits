<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kermit's Restaurant - POS & Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #5D4037; /* Cafe Brown */
            --secondary: #D7CCC8; /* Light Cream */
            --accent: #F8BBD0; /* Pastry Pink */
            --success: #81C784;
            --warning: #FFB74D;
            --danger: #E57373;
            --bg: #EFEBE9;
        }
		        html, body { min-height: 100%; overflow-x: hidden; overscroll-behavior: none; }
        body { background-color: var(--bg); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .nav-active { background-color: var(--primary); color: white; }
        .card { background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .btn-primary { background-color: var(--primary); color: white; padding: 10px 20px; border-radius: 6px; font-weight: bold; }
        .btn-primary:hover { background-color: #4E342E; }
        .btn-secondary { background-color: #F8BBD0; color: #5D4037; padding: 10px 16px; border-radius: 6px; font-weight: bold; }
        .btn-secondary:hover { background-color: #F48FB1; }
        .sold-out { filter: grayscale(100%); opacity: 0.6; pointer-events: none; }
        .status-bar { height: 8px; border-radius: 4px; background: #e0e0e0; }
        .status-fill { height: 100%; border-radius: 4px; background: var(--primary); transition: width 0.3s; }
        .offline-banner { background: var(--danger); color: white; text-align: center; padding: 5px; font-size: 14px; display: none; }
        .touch-btn { min-height: 60px; font-size: 1.2rem; } /* Large buttons for POS touch screens */
		        #home-screen {
		            min-height: 100vh;
		            background: #101010;
		        }
				        .home-hero {
				            min-height: 100vh;
				            min-height: 100svh;
				            min-height: 100dvh;
				            overflow-x: hidden;
				            overflow-y: auto;
			            background:
			                linear-gradient(90deg, rgba(10, 10, 10, 0.88) 0%, rgba(10, 10, 10, 0.68) 44%, rgba(10, 10, 10, 0.28) 100%),
			                linear-gradient(180deg, rgba(10, 10, 10, 0.08) 0%, rgba(10, 10, 10, 0.54) 100%),
			                url("{{ asset('homepage.jpg') }}") center / cover no-repeat;
			            color: white;
			            display: flex;
			            flex-direction: column;
			        }
			        .home-hero-shell {
			            width: min(100% - 2rem, 1280px);
			            margin: 0 auto;
			        }
					        .home-nav {
					            background: rgba(255, 255, 255, 0.96);
					            border-bottom: 1px solid rgba(255, 255, 255, 0.7);
					            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.08);
					        }
				        .home-nav-row {
					            min-height: 70px;
				            display: flex;
				            align-items: center;
				            justify-content: space-between;
				            gap: 1rem;
				            padding-top: 0.75rem;
				            padding-bottom: 0.75rem;
				        }
				        .home-brand {
				            display: flex;
				            align-items: center;
				            gap: 14px;
				        }
					        .home-brand-logo {
					            width: 48px;
					            height: 48px;
				            border-radius: 999px;
				            display: block;
				            object-fit: cover;
					            border: 2px solid #4b3a32;
				            flex-shrink: 0;
				        }
						        .home-brand-title {
							            color: #2f211c;
							            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
							            font-size: 1.1rem;
						            line-height: 1.1;
						            font-weight: 800;
						        }
					        .home-nav-links {
					            display: flex;
					            align-items: center;
					            gap: 2rem;
					            color: #292321;
					            font-weight: 800;
					        }
					        .home-nav-links a {
					            transition: color 0.2s;
					        }
						        .home-nav-links a:hover,
						        .home-nav-links a:first-child {
						            color: #16a065;
						        }
					        .home-hero-content {
				            flex: 1;
				            display: flex;
				            align-items: center;
				            justify-content: flex-start;
				            padding-top: 3rem;
				            padding-bottom: 3.5rem;
				        }
			        .home-hero-copy {
			            max-width: 720px;
			            text-align: left;
			            margin: 0;
		        }
		        .home-eyebrow {
	            display: inline-flex;
	            align-items: center;
	            gap: 8px;
	            background: rgba(255, 255, 255, 0.16);
	            border: 1px solid rgba(255, 255, 255, 0.2);
	            border-radius: 999px;
	            color: #fff7ec;
	            padding: 9px 16px;
	            backdrop-filter: blur(8px);
	            font-weight: 800;
	            letter-spacing: 0;
	            text-transform: none;
	        }
				        .home-hero-title {
				            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
					            font-size: clamp(3rem, 5.2vw, 5rem);
					            line-height: 1;
				            font-weight: 900;
				            letter-spacing: 0;
				            text-shadow: 0 5px 24px rgba(0, 0, 0, 0.3);
				        }
				        .home-hero-title .accent-word {
				            color: #ff7a18;
				            display: inline-block;
				        }
			        .home-hero-subtitle {
			            max-width: 610px;
		            line-height: 1.5;
	            color: rgba(255, 255, 255, 0.84);
			            margin-left: 0;
			            margin-right: 0;
			            text-shadow: 0 3px 16px rgba(0, 0, 0, 0.36);
			        }
        .menu-photo { height: 180px; object-fit: cover; border-radius: 8px 8px 0 0; }
        .category-pill {
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.45);
            border-radius: 999px;
            color: white;
            min-width: 96px;
            padding: 10px 18px;
            text-align: center;
            transition: background 0.2s, transform 0.2s;
        }
        .category-pill:hover,
        .category-pill.active {
            background: rgba(255,255,255,0.34);
            transform: translateY(-1px);
        }
        .pos-category-pill { background: white; border: 1px solid var(--secondary); border-radius: 999px; color: var(--primary); min-width: 96px; padding: 10px 18px; font-weight: 700; text-align: center; }
        .pos-category-pill.active, .pos-category-pill:hover { background: var(--primary); color: white; }
        .home-menu-card { transition: transform 0.2s, box-shadow 0.2s; }
        .home-menu-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .home-card-body { min-height: 230px; display: flex; flex-direction: column; }
        .home-card-actions { margin-top: auto; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .home-cart-dropdown {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            width: min(92vw, 920px);
            z-index: 50;
            color: #1f2937;
        }
        .cake-card-photo { height: 230px; object-fit: cover; border-radius: 8px 8px 0 0; }
        .cake-card-tag { color: var(--primary); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; }
			        .admin-login-link { min-height: 46px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: #ff6b0a; border: 1px solid #ff6b0a; color: white; padding: 10px 18px; border-radius: 999px; font-weight: 800; transition: background 0.2s, transform 0.2s, box-shadow 0.2s; white-space: nowrap; }
	        .admin-login-link:hover { background: #f05e00; transform: translateY(-1px); box-shadow: 0 14px 30px rgba(255, 107, 10, 0.25); }
		        .staff-login-link { width: 42px; min-height: 42px; padding: 0; background: transparent; color: #292321; border-color: transparent; border-radius: 999px; }
		        .staff-login-link:hover { background: #f3eee8; color: #292321; box-shadow: none; }
		        .hero-actions { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; justify-content: flex-end; }
					        .hero-order-link { margin-top: 1.5rem; padding: 15px 34px; font-size: 1rem; box-shadow: 0 16px 34px rgba(255, 107, 10, 0.28); }
			        .track-link { background: transparent; color: #292321; border-color: transparent; }
			        .track-link:hover { background: #f3eee8; color: #292321; box-shadow: none; }
				        @@media (max-width: 1180px) {
					            .home-brand-title { font-size: 1rem; }
					            .home-hero-title { font-size: 3.5rem; }
					            .home-hero-copy { max-width: 650px; }
					            .hero-order-link { margin-top: 2.25rem; }
				        }
				        @@media (max-width: 920px) {
				            .home-nav-row { align-items: flex-start; flex-direction: column; }
				            .hero-actions { justify-content: flex-start; }
				            .home-nav-links { gap: 1rem; }
				            .home-hero-content { align-items: flex-start; padding-top: 3rem; }
				            .home-hero-copy { text-align: left; margin: 0; max-width: 760px; }
				            .home-hero-subtitle { margin-left: 0; margin-right: 0; }
			            .home-hero-title { font-size: 3rem; }
			        }
				        @@media (max-height: 650px) {
				            .home-nav-row { min-height: 68px; padding-top: 0.5rem; padding-bottom: 0.5rem; }
				            .home-hero-content { padding-top: 1rem; padding-bottom: 1rem; }
				            .home-eyebrow { margin-bottom: 0.6rem !important; }
				            .home-hero-title { font-size: 2.6rem; margin-bottom: 0.75rem !important; }
				            .home-hero-subtitle { line-height: 1.35; }
				            .hero-order-link { margin-top: 1.25rem; min-height: 44px; padding-top: 10px; padding-bottom: 10px; }
				        }
				        @@media (max-width: 768px) {
				            #home-screen { background: #101010; }
				            .home-hero { min-height: 100dvh; overflow-y: auto; }
				            .home-hero-shell { width: min(100% - 1.25rem, 1280px); }
				            .home-nav { align-items: center; }
				            .home-nav-row { min-height: 0; padding-top: 0.85rem; padding-bottom: 0.85rem; }
				            .home-brand { gap: 12px; }
				            .home-brand-logo { width: 40px; height: 40px; font-size: 1.35rem; }
				            .home-brand-title { font-size: 1.05rem; }
				            .home-hero-content { padding-top: 1.45rem; padding-bottom: 2rem; }
				            .home-hero-title { font-size: 2.25rem; line-height: 1.08; }
			            .home-hero-subtitle { font-size: 1rem; line-height: 1.55; }
			            .home-eyebrow { font-size: 0.9rem; margin-bottom: 1rem !important; }
				            .home-nav-links { display: none; }
				            .admin-login-link { min-height: 42px; padding: 9px 12px; font-size: 0.9rem; }
				            .admin-login-link i { font-size: 0.95rem; }
				            .staff-login-link { width: 40px; padding: 0; }
			            .hero-order-link { margin-top: 1.75rem; min-height: 48px; padding: 12px 26px; width: auto; }
				            .hero-actions { justify-content: flex-start; }
			        }
				        @@media (max-width: 520px) {
				            .home-nav-row { gap: 0.8rem; }
					            .hero-actions { width: 100%; display: grid; grid-template-columns: 1fr auto; gap: 8px; }
					            .admin-login-link { width: 100%; min-height: 40px; padding: 8px 9px; font-size: 0.78rem; gap: 6px; }
					            .staff-login-link { width: 40px; padding: 0; }
				            .home-hero-title { font-size: clamp(1.85rem, 9vw, 2.05rem); line-height: 1.08; margin-bottom: 1rem !important; }
				            .home-hero-subtitle { font-size: 0.95rem; line-height: 1.55; }
				            .hero-order-link { width: auto; min-width: 150px; font-size: 0.95rem; }
				        }
			        .app-nav { flex-shrink: 0; }
			        .app-main { min-width: 0; min-height: 0; }
			        @@media (max-width: 767px) {
			            #app-screen { height: 100vh; height: 100dvh; min-height: 0; overflow: hidden; }
			            .app-nav { max-height: 76px; overflow-x: auto; overflow-y: hidden; }
			            #nav-container { min-width: max-content; }
			            #nav-container > * { flex: 1 0 auto; min-width: 84px; min-height: 52px; justify-content: center; white-space: nowrap; font-size: 0.78rem; padding: 8px 10px; }
			            .app-main { height: auto !important; flex: 1 1 auto; padding: 16px 12px 24px; }
			        }
	        .login-card { border-top: 6px solid var(--primary); }
        .login-icon { width: 54px; height: 54px; border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; background: var(--secondary); color: var(--primary); font-size: 1.4rem; margin-bottom: 14px; }
        
        /* Print styles for receipts */
        @@media print {
            body * { visibility: hidden; }
            #printable-receipt, #printable-receipt * { visibility: visible; }
            #printable-receipt { position: absolute; left: 0; top: 0; width: 100%; }
        }
    </style>
</head>
<body class="text-gray-800">

    <!-- Offline Mode Banner -->
    <div id="offline-banner" class="offline-banner">
        <i class="fas fa-wifi-slash"></i> You are offline. Data is saving locally and will sync when connection returns.
    </div>

	    <!-- Public Home Page -->
			    <div id="home-screen">
		        <header class="home-hero">
		            <nav class="home-nav">
					                <div class="home-hero-shell home-nav-row">
			                <div class="home-brand">
				                    <img src="{{ asset('kermit.jpg') }}" alt="Kermit's Restaurant" class="home-brand-logo">
					                    <h1 class="home-brand-title">Kermit's Restaurant</h1>
			                </div>
			                <div class="home-nav-links" aria-label="Primary navigation">
				                    <a href="{{ route('home') }}">Home</a>
					                    <a href="{{ route('customer.menu') }}">Menu</a>
				                    <a href="{{ route('track.order') }}">Orders</a>
			                </div>
			                <div class="hero-actions">
				                    <a href="{{ route('cashier.login') }}" class="admin-login-link staff-login-link">
			                        <i class="fas fa-user-lock"></i>
			                        <span class="sr-only">Staff Login</span>
		                    </a>
	                </div>
	                </div>
	            </nav>
	            <section class="home-hero-content home-hero-shell">
	                <div class="home-hero-copy">
				                    <p class="home-eyebrow mb-6"><i class="fas fa-utensils"></i> Freshly Prepared for Every Celebration</p>
				                    <h2 class="home-hero-title mb-5">Fresh bites,<br>smooth drinks,<br><span class="accent-word">sweet celebrations.</span></h2>
				                    <p class="home-hero-subtitle text-base md:text-lg">Enjoy comfort meals, handcrafted coffee, chilled cafe favorites, and cakes made for everyday treats or special occasions.</p>

            </section>
		        
		        </header>

		    </div>

    <!-- Main App Screen -->
    <div id="app-screen" class="hidden min-h-screen flex flex-col md:flex-row">
        
        <!-- Sidebar Navigation -->
	        <nav class="app-nav w-full md:w-64 bg-white shadow-md flex md:flex-col overflow-x-auto md:overflow-x-visible">
            <div class="p-4 hidden md:block">
                <h2 class="text-xl font-bold" style="color: var(--primary)">Kermit's POS</h2>
            </div>
            <div id="nav-container" class="flex md:flex-col w-full">
                <!-- Nav items injected by JS based on role -->
            </div>
            <button onclick="logout()" class="mt-auto p-4 text-red-500 hidden md:block"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </nav>

        <!-- Main Content Area -->
	        <main class="app-main flex-1 p-4 md:p-8 overflow-y-auto h-screen">
            <div id="dynamic-content">
                <!-- Content injected by JS -->
            </div>
        </main>
    </div>

    <!-- Hidden printable receipt -->
    <div id="printable-receipt" class="p-4 hidden">
        <h1 class="text-2xl font-bold text-center">Kermit's Restaurant</h1>
        <p class="text-center text-sm">Bantayan Island, Cebu</p>
        <hr class="my-2 border-dashed">
        <div id="receipt-items"></div>
        <hr class="my-2 border-dashed">
        <div id="receipt-total" class="text-right font-bold text-xl"></div>
        <p class="text-center text-sm mt-4">Thank you for visiting!</p>
    </div>

		    <script>
	        // --- MOCK DATABASE (Local Storage) ---
        const DB_NAME = 'KermitsCafeDB';
        let db = localStorage.getItem(DB_NAME) ? JSON.parse(localStorage.getItem(DB_NAME)) : {
            inventory: [
                { id: 'ing1', name: 'Flour (kg)', stock: 50, reorderPoint: 10 },
                { id: 'ing2', name: 'Sugar (kg)', stock: 20, reorderPoint: 5 },
                { id: 'ing3', name: 'Eggs (pcs)', stock: 100, reorderPoint: 20 },
                { id: 'ing4', name: 'Coffee Beans (kg)', stock: 5, reorderPoint: 2 }
            ],
            menu: [
                { id: 'm1', name: 'Classic Croissant', price: 80, img: 'https://images.unsplash.com/photo-1555507036-ab1f4038024a?auto=format&fit=crop&w=200&q=80', ingredients: [{id: 'ing1', qty: 0.2}, {id: 'ing2', qty: 0.05}] },
                { id: 'm2', name: 'Iced Spanish Latte', price: 120, img: 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=200&q=80', ingredients: [{id: 'ing4', qty: 0.05}, {id: 'ing2', qty: 0.02}] },
                { id: 'm3', name: 'Chocolate Cake Slice', price: 150, img: 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=200&q=80', ingredients: [{id: 'ing1', qty: 0.3}, {id: 'ing2', qty: 0.2}, {id: 'ing3', qty: 2}] }
            ],
            orders: [],
            waste: [],
            loyalty: {},
            queue: 0
        };

        const saveDb = () => localStorage.setItem(DB_NAME, JSON.stringify(db));
        let currentRole = null;
        let currentCart = [];
        let currentPosCategory = 'foods';
        let homeCart = [];
        let homeRenderedItems = [];
        const homeMenu = {
            foods: [
                { name: 'Chicken Alfredo Pasta', price: 'PHP 185', img: 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?auto=format&fit=crop&w=700&q=80', desc: 'Creamy pasta with tender chicken and herbs.' },
                { name: 'Clubhouse Sandwich', price: 'PHP 145', img: 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=700&q=80', desc: 'Layered toast, egg, vegetables, cheese, and savory filling.' },
                { name: 'Fresh Garden Salad', price: 'PHP 110', img: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=700&q=80', desc: 'Crisp greens with bright dressing and fresh toppings.' }
            ],
            beef: [
                { section: 'Beef', name: 'Stroganoff', price: 'PHP 300', img: 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=700&q=80', desc: 'Creamy beef stroganoff plate.' },
                { section: 'Beef', name: 'Burger Steak w/ Bacon', price: 'PHP 280', img: 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=700&q=80', desc: 'Burger steak topped with bacon.' },
                { section: 'Beef', name: 'Regular Burger Steak', price: 'PHP 250', img: 'https://images.unsplash.com/photo-1551782450-a2132b4ba21d?auto=format&fit=crop&w=700&q=80', desc: 'Classic burger steak meal.' },
                { section: 'Beef', name: 'Beef w/ Onions', price: 'PHP 220', img: 'https://images.unsplash.com/photo-1600891964092-4316c288032e?auto=format&fit=crop&w=700&q=80', desc: 'Savory beef with onions.' }
            ],
            pork: [
                { section: 'Pork', name: 'BBQ Ribs in Java Rice', price: 'PHP 370', img: 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=700&q=80', desc: 'BBQ ribs served with Java rice.' },
                { section: 'Pork', name: 'Grilled Hawaiian Belly', price: 'PHP 240', img: 'https://images.unsplash.com/photo-1432139555190-58524dae6a55?auto=format&fit=crop&w=700&q=80', desc: 'Grilled pork belly with Hawaiian flavor.' },
                { section: 'Pork', name: 'Lechon Kawali in Bagoong Rice', price: 'PHP 240', img: 'https://images.unsplash.com/photo-1625944525533-473f1a3d54e7?auto=format&fit=crop&w=700&q=80', desc: 'Crispy lechon kawali with bagoong rice.' },
                { section: 'Pork', name: 'Sisig', price: 'PHP 290', img: 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&w=700&q=80', desc: 'Sizzling pork sisig.' },
                { section: 'Pork', name: 'Pork Snitzel', price: 'PHP 220', img: 'https://images.unsplash.com/photo-1559847844-5315695dadae?auto=format&fit=crop&w=700&q=80', desc: 'Crispy pork snitzel plate.' }
            ],
            chicken: [
                { section: 'Chicken', name: 'Chicken Potato Casserole', price: 'PHP 320', img: 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&w=700&q=80', desc: 'Chicken and potato casserole.' },
                { section: 'Chicken', name: 'Stroganoff', price: 'PHP 280', img: 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?auto=format&fit=crop&w=700&q=80', desc: 'Creamy chicken stroganoff.' },
                { section: 'Chicken', name: 'Fun Bites', price: 'PHP 210', img: 'https://images.unsplash.com/photo-1562967914-608f82629710?auto=format&fit=crop&w=700&q=80', desc: 'Crispy chicken bites.' },
                { section: 'Chicken', name: 'Fillet in Creamy Sauce', price: 'PHP 200', img: 'https://images.unsplash.com/photo-1532550907401-a500c9a57435?auto=format&fit=crop&w=700&q=80', desc: 'Chicken fillet with creamy sauce.' }
            ],
            pasta: [
                { section: 'Pasta', name: 'Aligue Pasta', price: 'PHP 280', img: 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?auto=format&fit=crop&w=700&q=80', desc: 'Rich crab fat pasta.' },
                { section: 'Pasta', name: 'Baked Spaghetti', price: 'PHP 260', img: 'https://images.unsplash.com/photo-1622973536968-3ead9e780960?auto=format&fit=crop&w=700&q=80', desc: 'Baked spaghetti with savory sauce.' },
                { section: 'Pasta', name: 'Spaghetti', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1551892374-ecf8754cf8b0?auto=format&fit=crop&w=700&q=80', desc: 'Classic spaghetti pasta.' },
                { section: 'Pasta', name: 'Aglio Olio', price: 'PHP 240', img: 'https://images.unsplash.com/photo-1473093295043-cdd812d0e601?auto=format&fit=crop&w=700&q=80', desc: 'Garlic and olive oil pasta.' },
                { section: 'Pasta', name: 'Truffled Carbonara', price: 'PHP 240', img: 'https://images.unsplash.com/photo-1588013273468-315fd88ea34c?auto=format&fit=crop&w=700&q=80', desc: 'Creamy carbonara with truffle flavor.' }
            ],
            burrito: [
                { section: 'Burrito', name: 'Chunky Chicken', price: 'PHP 200', img: 'https://images.unsplash.com/photo-1626700051175-6818013e1d4f?auto=format&fit=crop&w=700&q=80', desc: 'Burrito filled with chunky chicken.' },
                { section: 'Burrito', name: 'Ham & Beef Combo', price: 'PHP 165', img: 'https://images.unsplash.com/photo-1626700051175-6818013e1d4f?auto=format&fit=crop&w=700&q=80', desc: 'Ham and beef combo burrito.' },
                { section: 'Burrito', name: 'Beef', price: 'PHP 150', img: 'https://images.unsplash.com/photo-1566740933430-b5e70b06d2d5?auto=format&fit=crop&w=700&q=80', desc: 'Classic beef burrito.' },
                { section: 'Burrito', name: 'Chicken', price: 'PHP 150', img: 'https://images.unsplash.com/photo-1626700051175-6818013e1d4f?auto=format&fit=crop&w=700&q=80', desc: 'Classic chicken burrito.' },
                { section: 'Burrito', name: 'Veggies', price: 'PHP 140', img: 'https://images.unsplash.com/photo-1626700051175-6818013e1d4f?auto=format&fit=crop&w=700&q=80', desc: 'Vegetable burrito.' }
            ],
            salad: [
                { section: 'Salad', name: "Kermit's Salad", price: 'PHP 220', img: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=700&q=80', desc: "Kermit's house salad." },
                { section: 'Salad', name: 'Caesar Salad', price: 'PHP 210', img: 'https://images.unsplash.com/photo-1550304943-4f24f54ddde9?auto=format&fit=crop&w=700&q=80', desc: 'Classic Caesar salad.' },
                { section: 'Salad', name: 'Creamy Fusili Salad', price: 'PHP 210', img: 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?auto=format&fit=crop&w=700&q=80', desc: 'Creamy fusili pasta salad.' }
            ],
            burgers: [
                { section: 'Burgers', name: 'Kermits Special Burger', price: 'PHP 300', img: 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=700&q=80', desc: 'Signature Kermits special burger.' },
                { section: 'Burgers', name: 'Three Cheese Burger', price: 'PHP 275', img: 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=700&q=80', desc: 'Burger loaded with three cheeses.' },
                { section: 'Burgers', name: 'Quarter Pounder', price: 'PHP 275', img: 'https://images.unsplash.com/photo-1551782450-a2132b4ba21d?auto=format&fit=crop&w=700&q=80', desc: 'Classic quarter pounder burger.' },
                { section: 'Burgers', name: 'Cheeseburger w/ Bacon', price: 'PHP 240', img: 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=700&q=80', desc: 'Cheeseburger with bacon.' },
                { section: 'Burgers', name: 'Cheeseburger', price: 'PHP 200', img: 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=700&q=80', desc: 'Classic cheeseburger.' },
                { section: 'Burgers', name: 'Regular Burgers', price: 'PHP 150', img: 'https://images.unsplash.com/photo-1551782450-a2132b4ba21d?auto=format&fit=crop&w=700&q=80', desc: 'Regular burger.' },
                { section: 'Add Ons', name: 'Egg', price: 'PHP 30', img: 'https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=700&q=80', desc: 'Add egg to your burger.' },
                { section: 'Add Ons', name: 'Ham', price: 'PHP 40', img: 'https://images.unsplash.com/photo-1524438418049-ab2acb7aa48f?auto=format&fit=crop&w=700&q=80', desc: 'Add ham to your burger.' },
                { section: 'Add Ons', name: 'Bacon', price: 'PHP 50', img: 'https://images.unsplash.com/photo-1528607929212-2636ec44253e?auto=format&fit=crop&w=700&q=80', desc: 'Add bacon to your burger.' }
            ],
            restaurant: [
                { name: 'All-Day Breakfast Plate', price: 'PHP 165', img: 'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?auto=format&fit=crop&w=700&q=80', desc: 'Eggs, toast, sausage, and a warm cafe side.' },
                { name: 'Grilled Chicken Rice Bowl', price: 'PHP 190', img: 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&w=700&q=80', desc: 'Grilled chicken served with rice and vegetables.' },
                { name: 'Chef Special Platter', price: 'Daily special', img: 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=700&q=80', desc: 'A rotating dine-in favorite from the kitchen.' }
            ],
            drinks: [
                { name: 'Iced Spanish Latte', price: 'PHP 120', img: 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=700&q=80', desc: 'Chilled espresso with sweet, creamy milk.' },
                { name: 'Caramel Frappe', price: 'PHP 135', img: 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?auto=format&fit=crop&w=700&q=80', desc: 'Blended coffee with caramel and whipped cream.' },
                { name: 'Fruit Iced Tea', price: 'PHP 95', img: 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=700&q=80', desc: 'Refreshing tea with fruity notes and ice.' }
            ],
            coffee: [
                { section: 'Coffee', name: 'Espresso', price: 'PHP 80', img: 'https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?auto=format&fit=crop&w=700&q=80', desc: 'Strong and classic espresso shot.' },
                { section: 'Coffee', name: 'Americano', price: '8oz PHP 60 | 12oz PHP 90', img: 'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=700&q=80', desc: 'Smooth black coffee served hot.' },
                { section: 'Coffee', name: 'Cappuccino', price: '8oz PHP 80 | 12oz PHP 110', img: 'https://images.unsplash.com/photo-1534778101976-62847782c213?auto=format&fit=crop&w=700&q=80', desc: 'Espresso with steamed milk and foam.' },
                { section: 'Coffee', name: 'Cafe Mocha', price: '8oz PHP 90 | 12oz PHP 120', img: 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&w=700&q=80', desc: 'Coffee and chocolate in a warm cup.' },
                { section: 'Coffee', name: 'Latte', price: '8oz PHP 80 | 12oz PHP 120', img: 'https://images.unsplash.com/photo-1570968915860-54d5c301fa9f?auto=format&fit=crop&w=700&q=80', desc: 'Creamy espresso latte.' },
                { section: 'Coffee', name: 'Flavored Latte', price: '8oz PHP 85 | 12oz PHP 130', img: 'https://images.unsplash.com/photo-1561882468-9110e03e0f78?auto=format&fit=crop&w=700&q=80', desc: 'Choose salted caramel, butterscotch, or hazelnut.' }
            ],
            icedCoffee: [
                { section: 'Iced Coffee', name: 'Caramel Macchiato', price: 'PHP 120', img: 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=700&q=80', desc: 'Iced coffee layered with caramel.' },
                { section: 'Iced Coffee', name: 'Mocha', price: 'PHP 120', img: 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=700&q=80', desc: 'Cold chocolate coffee drink.' },
                { section: 'Iced Coffee', name: 'White Mocha', price: 'PHP 120', img: 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?auto=format&fit=crop&w=700&q=80', desc: 'Creamy white mocha over ice.' },
                { section: 'Iced Coffee', name: 'Americano', price: 'PHP 100', img: 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&w=700&q=80', desc: 'Black coffee served chilled over ice.' },
                { section: 'Iced Coffee', name: 'Latte', price: 'PHP 110', img: 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?auto=format&fit=crop&w=700&q=80', desc: 'Iced espresso with milk.' },
                { section: 'Iced Coffee', name: 'Flavored Latte', price: 'PHP 120', img: 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?auto=format&fit=crop&w=700&q=80', desc: 'Choose salted caramel, butterscotch, or hazelnut.' },
                { section: 'Iced Coffee', name: 'Affogato Twist', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=700&q=80', desc: 'Coffee dessert drink with a sweet twist.' }
            ],
            tea: [
                { section: 'Tea', name: 'Hot Tea', price: 'PHP 50', img: 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?auto=format&fit=crop&w=700&q=80', desc: 'Warm brewed tea.' },
                { section: 'Tea', name: 'Iced Tea', price: 'PHP 45', img: 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=700&q=80', desc: 'Classic cold iced tea.' },
                { section: 'Tea', name: 'Passion Fruit Iced Tea', price: 'PHP 70', img: 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=700&q=80', desc: 'Refreshing passion fruit tea.' },
                { section: 'Tea', name: 'Peach Iced Tea', price: 'PHP 70', img: 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=700&q=80', desc: 'Sweet peach iced tea.' }
            ],
            slushie: [
                { section: 'Slushie', name: 'Halo-Halo Special', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=700&q=80', desc: 'Special shaved ice dessert drink.' },
                { section: 'Slushie', name: 'Halo-Halo Regular', price: 'PHP 100', img: 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=700&q=80', desc: 'Classic halo-halo slushie.' },
                { section: 'Slushie', name: 'Buko Lychee', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1525385133512-2f3bdd039054?auto=format&fit=crop&w=700&q=80', desc: 'Coconut and lychee cooler.' },
                { section: 'Slushie', name: 'Peach Mango', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1621263764928-df1444c5e859?auto=format&fit=crop&w=700&q=80', desc: 'Peach and mango blended cooler.' },
                { section: 'Slushie', name: 'Mais con Hielo', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=700&q=80', desc: 'Sweet corn shaved ice drink.' },
                { section: 'Slushie', name: 'Special Mango con Hielo', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?auto=format&fit=crop&w=700&q=80', desc: 'Special mango shaved ice cooler.' },
                { section: 'Slushie', name: 'Banana', price: 'PHP 100', img: 'https://images.unsplash.com/photo-1525385133512-2f3bdd039054?auto=format&fit=crop&w=700&q=80', desc: 'Sweet banana slushie.' },
                { section: 'Slushie', name: 'Buko', price: 'PHP 100', img: 'https://images.unsplash.com/photo-1525385133512-2f3bdd039054?auto=format&fit=crop&w=700&q=80', desc: 'Refreshing coconut slushie.' },
                { section: 'Slushie', name: 'Mango', price: 'PHP 100', img: 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?auto=format&fit=crop&w=700&q=80', desc: 'Mango blended cooler.' },
                { section: 'Slushie', name: 'Choco Boast', price: 'PHP 80', img: 'https://images.unsplash.com/photo-1577805947697-89e18249d767?auto=format&fit=crop&w=700&q=80', desc: 'Chocolate slushie cooler.' },
                { section: 'Slushie', name: 'Vanilla Dream', price: 'PHP 80', img: 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?auto=format&fit=crop&w=700&q=80', desc: 'Creamy vanilla slushie.' }
            ],
            smoothies: [
                { section: 'Smoothies / Milkshakes', name: 'Mixed Berries', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?auto=format&fit=crop&w=700&q=80', desc: 'Blended mixed berry smoothie.' },
                { section: 'Smoothies / Milkshakes', name: 'Raspberry', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1505252585461-04db1eb84625?auto=format&fit=crop&w=700&q=80', desc: 'Sweet raspberry smoothie.' },
                { section: 'Smoothies / Milkshakes', name: 'Peach', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1621263764928-df1444c5e859?auto=format&fit=crop&w=700&q=80', desc: 'Creamy peach smoothie.' },
                { section: 'Smoothies / Milkshakes', name: 'Cookies N Cream MS', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?auto=format&fit=crop&w=700&q=80', desc: 'Cookies and cream milkshake.' },
                { section: 'Smoothies / Milkshakes', name: 'Choco Mallow MS', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1577805947697-89e18249d767?auto=format&fit=crop&w=700&q=80', desc: 'Chocolate mallow milkshake.' },
                { section: 'Smoothies / Milkshakes', name: 'Mango Float MS', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?auto=format&fit=crop&w=700&q=80', desc: 'Mango float milkshake.' },
                { section: 'Smoothies / Milkshakes', name: 'Strawberry Swing MS', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?auto=format&fit=crop&w=700&q=80', desc: 'Strawberry milkshake.' },
                { section: 'Smoothies / Milkshakes', name: 'Ube Taro', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?auto=format&fit=crop&w=700&q=80', desc: 'Creamy ube taro drink.' }
            ],
            frappes: [
                { section: 'Frappes', name: 'Butterscotch / Cookies N Cream', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?auto=format&fit=crop&w=700&q=80', desc: 'Creamy blended frappe with dessert flavors.' },
                { section: 'Frappes', name: 'Caramel', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?auto=format&fit=crop&w=700&q=80', desc: 'Sweet caramel blended frappe.' },
                { section: 'Frappes', name: 'Salted Caramel', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1577805947697-89e18249d767?auto=format&fit=crop&w=700&q=80', desc: 'Balanced sweet and salty caramel frappe.' },
                { section: 'Frappes', name: 'Hazelnut', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?auto=format&fit=crop&w=700&q=80', desc: 'Nutty hazelnut blended frappe.' },
                { section: 'Frappes', name: 'Matcha', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1515823662972-da6a2e4d3002?auto=format&fit=crop&w=700&q=80', desc: 'Creamy green tea matcha frappe.' },
                { section: 'Frappes', name: 'Mocha', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=700&q=80', desc: 'Chocolate coffee blended frappe.' },
                { section: 'Frappes', name: 'Horchata', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1525385133512-2f3bdd039054?auto=format&fit=crop&w=700&q=80', desc: 'Cool cinnamon-style blended drink.' },
                { section: 'Frappes', name: 'Choco Fudge', price: 'PHP 130', img: 'https://images.unsplash.com/photo-1577805947697-89e18249d767?auto=format&fit=crop&w=700&q=80', desc: 'Rich chocolate fudge frappe.' }
            ],
            lemonade: [
                { section: 'Lemonade', name: 'Green Apple', price: 'PHP 70', img: 'https://images.unsplash.com/photo-1621263764928-df1444c5e859?auto=format&fit=crop&w=700&q=80', desc: 'Refreshing green apple lemonade.' },
                { section: 'Lemonade', name: 'Apple', price: 'PHP 70', img: 'https://images.unsplash.com/photo-1621263764928-df1444c5e859?auto=format&fit=crop&w=700&q=80', desc: 'Bright apple lemonade.' },
                { section: 'Lemonade', name: 'Blue Curacao', price: 'PHP 70', img: 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?auto=format&fit=crop&w=700&q=80', desc: 'Blue curacao lemonade.' },
                { section: 'Lemonade', name: 'Grape', price: 'PHP 70', img: 'https://images.unsplash.com/photo-1621263764928-df1444c5e859?auto=format&fit=crop&w=700&q=80', desc: 'Sweet grape lemonade.' },
                { section: 'Lemonade', name: 'Passion Fruit', price: 'PHP 70', img: 'https://images.unsplash.com/photo-1621263764928-df1444c5e859?auto=format&fit=crop&w=700&q=80', desc: 'Tropical passion fruit lemonade.' },
                { section: 'Lemonade', name: 'Raspberry', price: 'PHP 70', img: 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?auto=format&fit=crop&w=700&q=80', desc: 'Berry lemonade with a crisp finish.' }
            ],
            cake: [
                { section: 'Cake', items: [
                    { name: 'Almond Gateaux', whole: 900, slice: 60 },
                    { name: 'Choco Overload', whole: 900, slice: 60 },
                    { name: 'Hazelnut Gateaux', whole: 900, slice: 60 },
                    { name: 'Honey Crunch Gateaux', whole: 900, slice: 60 },
                    { name: 'Lemon Blueberry', whole: 900, slice: 60 },
                    { name: 'Mango Graham', whole: 850, slice: 55 },
                    { name: 'Mango Overload', whole: 800, slice: 50 },
                    { name: 'Mango Regular', whole: 800, slice: 50 },
                    { name: 'Black Forest', whole: 800, slice: 50 },
                    { name: 'Choco Cream', whole: 800, slice: 50 },
                    { name: 'Cookies N Cream', whole: 800, slice: 50 },
                    { name: 'Midnight Chocolate', whole: 800, slice: 50 },
                    { name: 'Red Velvet', whole: 800, slice: 50 },
                    { name: 'Rosemarie', whole: 800, slice: 50 },
                    { name: 'Ube Custard', whole: 800, slice: 50 },
                    { name: 'Ube Regular', whole: 700, slice: 45 },
                    { name: 'White Forest', whole: 800, slice: 50 }
                ]},
                { section: 'Cheesecake', items: [
                    { name: 'Blueberry', whole: 900, slice: 75 },
                    { name: 'Mango', whole: 900, slice: 75 }
                ]},
                { section: 'Pie', items: [
                    { name: 'Apple', whole: 500, slice: 65 },
                    { name: 'Buko', whole: 500, slice: 65 }
                ]},
                { section: 'Roll', items: [
                    { name: 'Berry Roulade', whole: 550, slice: '' },
                    { name: 'Choco Mucho', whole: 550, slice: 50 },
                    { name: 'Mango Roulade', whole: 550, slice: 50 },
                    { name: 'White Choco Roulade', whole: 550, slice: 50 },
                    { name: 'Brazo de Mercedes Classic', whole: 400, slice: 50 },
                    { name: 'Brazo de Mercedes Ube', whole: 400, slice: 30 },
                    { name: 'Choco Roll', whole: 400, slice: 35 }
                ]}
            ]
        };

        const getCakeImage = (name) => {
            const lowerName = name.toLowerCase();
            const cakeImages = {
                mango: 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?auto=format&fit=crop&w=160&q=80',
                choco: 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=160&q=80',
                chocolate: 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?auto=format&fit=crop&w=160&q=80',
                blueberry: 'https://images.unsplash.com/photo-1622621746668-59fb299bc4d7?auto=format&fit=crop&w=160&q=80',
                strawberry: 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?auto=format&fit=crop&w=160&q=80',
                lemon: 'https://images.unsplash.com/photo-1519915028121-7d3463d20b13?auto=format&fit=crop&w=160&q=80',
                ube: 'https://images.unsplash.com/photo-1616690710400-a16d146927c5?auto=format&fit=crop&w=160&q=80',
                red: 'https://images.unsplash.com/photo-1586788680434-30d324b2d46f?auto=format&fit=crop&w=160&q=80',
                apple: 'https://images.unsplash.com/photo-1568571780765-9276ac8b75a2?auto=format&fit=crop&w=160&q=80',
                buko: 'https://images.unsplash.com/photo-1627308595229-7830a5c91f9f?auto=format&fit=crop&w=160&q=80',
                roll: 'https://images.unsplash.com/photo-1621303837174-89787a7d4729?auto=format&fit=crop&w=160&q=80',
                brazo: 'https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=160&q=80'
            };

            if(lowerName.includes('mango')) return cakeImages.mango;
            if(lowerName.includes('blueberry')) return cakeImages.blueberry;
            if(lowerName.includes('lemon')) return cakeImages.lemon;
            if(lowerName.includes('ube')) return cakeImages.ube;
            if(lowerName.includes('red velvet')) return cakeImages.red;
            if(lowerName.includes('apple')) return cakeImages.apple;
            if(lowerName.includes('buko')) return cakeImages.buko;
            if(lowerName.includes('roll') || lowerName.includes('roulade')) return cakeImages.roll;
            if(lowerName.includes('brazo')) return cakeImages.brazo;
            if(lowerName.includes('choco') || lowerName.includes('forest') || lowerName.includes('midnight')) return cakeImages.choco;
            return 'https://images.unsplash.com/photo-1535141192574-5d4897c12636?auto=format&fit=crop&w=160&q=80';
        };

        const getHomePriceValue = (item) => {
            if(item.whole) return Number(item.whole);
            const match = String(item.price || '').match(/\d+/);
            return match ? Number(match[0]) : 0;
        };

        const getHomePriceLabel = (item) => {
            if(item.whole) return `PHP ${item.whole}`;
            return item.price || 'Ask staff';
        };

        const registerHomeItem = (item) => {
            const cartItem = {
                name: item.name,
                price: getHomePriceValue(item),
                priceLabel: getHomePriceLabel(item)
            };
            homeRenderedItems.push(cartItem);
            return homeRenderedItems.length - 1;
        };

        const homeActionButtons = (index) => `
            <div class="home-card-actions">
                <button type="button" onclick="addHomeItemToCart(${index})" class="btn-secondary">
                    <i class="fas fa-cart-plus mr-1"></i> Add to Cart
                </button>
                <button type="button" onclick="buyHomeItem(${index})" class="btn-primary">
                    <i class="fas fa-bag-shopping mr-1"></i> Buy Now
                </button>
            </div>
        `;

        const renderHomeCart = () => {
            const count = homeCart.reduce((sum, item) => sum + item.qty, 0);
            const total = homeCart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            const countEl = document.getElementById('home-cart-count');
            const itemsEl = document.getElementById('home-cart-items');
            const totalEl = document.getElementById('home-cart-total');
            const panel = document.getElementById('home-cart-panel');

            if(countEl) countEl.innerText = count;
            if(totalEl) totalEl.innerText = `PHP ${total}`;
            if(panel && homeCart.length === 0) panel.classList.add('hidden');
            if(!itemsEl) return;

            itemsEl.innerHTML = homeCart.length ? homeCart.map((item, index) => `
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b pb-3">
                    <div>
                        <p class="font-bold">${item.name}</p>
                        <p class="text-sm text-gray-500">${item.priceLabel} x ${item.qty}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="updateHomeCartQty(${index}, -1)" class="px-3 py-1 bg-gray-200 rounded">-</button>
                        <span class="px-2 font-bold">${item.qty}</span>
                        <button onclick="updateHomeCartQty(${index}, 1)" class="px-3 py-1 bg-gray-200 rounded">+</button>
                        <button onclick="removeHomeCartItem(${index})" class="ml-2 text-red-500"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            `).join('') : '<p class="text-gray-500">Your cart is empty.</p>';
        };

        const toggleHomeCart = () => {
            const panel = document.getElementById('home-cart-panel');
            if(!panel) return;
            if(homeCart.length === 0) {
                panel.classList.add('hidden');
                return;
            }
            panel.classList.remove('hidden');
        };

        const addHomeItemToCart = (index, shouldOpenCart = false) => {
            const item = homeRenderedItems[index];
            if(!item) return;
            const key = `${item.name}-${item.priceLabel}`;
            const existing = homeCart.find(cartItem => cartItem.key === key);
            if(existing) existing.qty++;
            else homeCart.push({ ...item, key, qty: 1 });
            renderHomeCart();
            if(shouldOpenCart) {
                document.getElementById('home-cart-panel')?.classList.remove('hidden');
            }
        };

        const buyHomeItem = (index) => {
            const item = homeRenderedItems[index];
            if(!item) return;
            homeCart = [{ ...item, key: `${item.name}-${item.priceLabel}`, qty: 1 }];
            renderHomeCart();
            document.getElementById('home-cart-panel')?.classList.remove('hidden');
        };

        const updateHomeCartQty = (index, change) => {
            homeCart[index].qty += change;
            if(homeCart[index].qty <= 0) homeCart.splice(index, 1);
            renderHomeCart();
        };

        const removeHomeCartItem = (index) => {
            homeCart.splice(index, 1);
            renderHomeCart();
        };

        const clearHomeCart = () => {
            homeCart = [];
            renderHomeCart();
        };

        const checkoutHomeCart = () => {
            if(homeCart.length === 0) return;
            const total = homeCart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            db.queue++;
            db.orders.push({
                id: 'WEB-' + Date.now(),
                queueNum: db.queue,
                items: homeCart.map(item => ({ name: item.name, price: item.price, qty: item.qty })),
                total,
                status: 'received',
                time: new Date().toISOString()
            });
            saveDb();
            homeCart = [];
            renderHomeCart();
            alert(`Order placed!\nQueue: #${db.queue}\nTotal: PHP ${total}`);
        };

        const showHomeCategory = (category, shouldScroll = false) => {
            const grid = document.getElementById('home-menu-grid');
            if(!grid) return;

            const selectedItems = homeMenu[category] || homeMenu.foods;
            homeRenderedItems = [];
            document.querySelectorAll('[data-home-category]').forEach(button => {
                button.classList.toggle('active', button.dataset.homeCategory === category);
            });

            if(category === 'cake') {
                const cakeCards = selectedItems.flatMap(group =>
                    group.items.map(item => ({ ...item, section: group.section }))
                );

                grid.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6';
                grid.innerHTML = cakeCards.map(item => {
                    const itemIndex = registerHomeItem(item);
                    return `
                    <article class="card home-menu-card overflow-hidden">
                        <img class="cake-card-photo w-full" src="${getCakeImage(item.name)}" alt="${item.name}">
                        <div class="p-5 home-card-body">
                            <p class="cake-card-tag mb-2">${item.section}</p>
                            <h3 class="text-2xl font-bold mb-3" style="color: var(--primary)">${item.name}</h3>
                            <p class="text-gray-600 mb-4">Available as whole cake${item.slice ? ' or per slice' : ''}.</p>
                            <div class="flex flex-wrap gap-x-6 gap-y-2 font-bold text-green-700 text-lg">
                                <span>Whole: PHP ${item.whole}</span>
                                ${item.slice ? `<span>Slice: PHP ${item.slice}</span>` : ''}
                            </div>
                            ${homeActionButtons(itemIndex)}
                        </div>
                    </article>
                `;
                }).join('');
            } else if(category === 'beef' || category === 'pork' || category === 'chicken' || category === 'pasta' || category === 'burrito' || category === 'salad' || category === 'burgers' || category === 'coffee' || category === 'icedCoffee' || category === 'tea' || category === 'slushie' || category === 'smoothies' || category === 'frappes' || category === 'lemonade') {
                grid.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6';
                grid.innerHTML = selectedItems.map(item => {
                    const itemIndex = registerHomeItem(item);
                    return `
                    <article class="card home-menu-card overflow-hidden">
                        <img class="menu-photo w-full" src="${item.img}" alt="${item.name}">
                        <div class="p-5 home-card-body">
                            <p class="cake-card-tag mb-2">${item.section}</p>
                            <h3 class="text-2xl font-bold mb-3" style="color: var(--primary)">${item.name}</h3>
                            <p class="text-gray-600 mb-4">${item.desc}</p>
                            <p class="font-bold text-green-700 text-lg mb-4">${item.price}</p>
                            ${homeActionButtons(itemIndex)}
                        </div>
                    </article>
                `;
                }).join('');
            } else {
                grid.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5';
                grid.innerHTML = selectedItems.map(item => {
                    const itemIndex = registerHomeItem(item);
                    return `
                    <article class="card home-menu-card overflow-hidden">
                        <img class="menu-photo w-full" src="${item.img}" alt="${item.name}">
                        <div class="p-4 home-card-body">
                            <h3 class="text-xl font-bold mb-2" style="color: var(--primary)">${item.name}</h3>
                            <p class="text-gray-600 mb-3">${item.desc}</p>
                            <p class="font-bold text-green-700 mb-4">${item.price}</p>
                            ${homeActionButtons(itemIndex)}
                        </div>
                    </article>
                `;
                }).join('');
            }

            if(shouldScroll) {
                document.getElementById('home-menu')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        };

        // --- OFFLINE & SYNC LOGIC ---
        window.addEventListener('offline', () => document.getElementById('offline-banner').style.display = 'block');
        window.addEventListener('online', () => {
            document.getElementById('offline-banner').style.display = 'none';
            // Trigger sync to cloud database here (API call)
            alert("Back online! Local data synced to cloud.");
        });

        // --- AUTH & NAVIGATION ---
        const showHome = () => {
            currentRole = null;
            document.getElementById('home-screen').classList.remove('hidden');
            document.getElementById('app-screen').classList.add('hidden');
        };

        const logout = () => {
            showHome();
        };

        const renderNav = () => {
            const nav = document.getElementById('nav-container');
            let items = [
                { id: 'pos', icon: 'fa-cash-register', text: 'POS', roles: ['cashier', 'admin'] },
                { id: 'orders', icon: 'fa-list-check', text: 'Orders & Queue', roles: ['kitchen', 'admin'] },
                { id: 'inventory', icon: 'fa-boxes-stacked', text: 'Inventory & Waste', roles: ['kitchen', 'admin'] },
                { id: 'custom', icon: 'fa-cake-candles', text: 'Custom Orders', roles: ['kitchen', 'admin'] },
                { id: 'dashboard', icon: 'fa-chart-pie', text: 'Dashboard', roles: ['admin'] }
            ];

            nav.innerHTML = items.filter(item => item.roles.includes(currentRole))
                .map(item => `<button onclick="navigateTo('${item.id}')" id="nav-${item.id}" class="flex items-center p-4 hover:bg-gray-200 w-full text-left md:border-b">
                    <i class="fas ${item.icon} mr-3"></i> <span class="hidden md:inline">${item.text}</span>
                </button>`).join('');
        };

        const navigateTo = (section) => {
            document.querySelectorAll('[id^="nav-"]').forEach(el => el.classList.remove('nav-active'));
            document.getElementById(`nav-${section}`)?.classList.add('nav-active');
            const content = document.getElementById('dynamic-content');
            
            switch(section) {
                case 'pos': renderPOS(content); break;
                case 'orders': renderOrders(content); break;
                case 'inventory': renderInventory(content); break;
                case 'custom': renderCustomOrders(content); break;
                case 'dashboard': renderDashboard(content); break;
            }
        };

        // --- 1. POS SYSTEM (Customer Order & Cashier) ---
        let renderPOS = (el) => {
            const isSoldOut = (menuItem) => {
                return menuItem.ingredients.some(ing => {
                    const stockItem = db.inventory.find(i => i.id === ing.id);
                    return stockItem.stock < ing.qty;
                });
            };

            el.innerHTML = `
                <div class="flex flex-col lg:flex-row gap-6 h-full">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold mb-4" style="color: var(--primary)">Menu</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            ${db.menu.map(item => `
                                <div class="card p-4 cursor-pointer ${isSoldOut(item) ? 'sold-out' : ''}" onclick="${isSoldOut(item) ? '' : `addToCart('${item.id}')`}">
                                    <img src="${item.img}" class="w-full h-24 object-cover rounded mb-2">
                                    <h3 class="font-bold">${item.name}</h3>
                                    <p class="text-green-700 font-bold">₱${item.price}</p>
                                    ${isSoldOut(item) ? '<span class="text-red-500 font-bold text-sm">SOLD OUT</span>' : ''}
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    <div class="w-full lg:w-96 bg-white p-4 rounded shadow flex flex-col">
                        <h2 class="text-xl font-bold mb-4 border-b pb-2">Current Order</h2>
                        <div id="cart-items" class="flex-1 overflow-y-auto">
                            ${currentCart.length === 0 ? '<p class="text-gray-500 text-center mt-10">No items yet</p>' : renderCartItems()}
                        </div>
                        <div class="mt-4 pt-4 border-t">
                            <div class="flex justify-between text-xl font-bold mb-4">
                                <span>Total:</span> <span id="cart-total">₱${calculateTotal()}</span>
                            </div>
                            <button onclick="checkout()" class="btn-primary w-full touch-btn ${currentCart.length === 0 ? 'opacity-50 cursor-not-allowed' : ''}" ${currentCart.length === 0 ? 'disabled' : ''}>
                                <i class="fas fa-credit-card mr-2"></i> Pay & Checkout
                            </button>
                        </div>
                    </div>
                </div>
            `;
        };

        const renderCartItems = () => {
            return currentCart.map((item, index) => `
                <div class="flex justify-between items-center mb-2 pb-2 border-b">
                    <div>
                        <p class="font-bold">${item.name}</p>
                        <p class="text-sm text-gray-500">₱${item.price} x ${item.qty}</p>
                    </div>
                    <div class="flex items-center">
                        <button onclick="updateQty(${index}, -1)" class="px-2 bg-gray-200 rounded">-</button>
                        <span class="px-3">${item.qty}</span>
                        <button onclick="updateQty(${index}, 1)" class="px-2 bg-gray-200 rounded">+</button>
                        <button onclick="removeFromCart(${index})" class="ml-3 text-red-500"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            `).join('');
        };

        const getPOSMenuItems = (category) => {
            if(category === 'drinks') return homeMenu.coffee.slice(0, 3);
            if(category === 'cake') return homeMenu.cake[0].items.slice(0, 3).map(item => ({
                name: item.name,
                price: `PHP ${item.whole}`,
                img: getCakeImage(item.name),
                desc: 'Available as whole cake or per slice.'
            }));
            return homeMenu[category] || homeMenu.foods;
        };

        const showPOSCategory = (category) => {
            currentPosCategory = category;
            navigateTo('pos');
        };

        const addPOSMenuItem = (category, index) => {
            const item = getPOSMenuItems(category)[index];
            if(!item) return;
            const id = `${category}-${item.name}-${item.price}`;
            const existing = currentCart.find(c => c.id === id);
            if(existing) existing.qty++;
            else currentCart.push({
                id,
                category,
                name: item.name,
                price: getHomePriceValue(item),
                qty: 1
            });
            navigateTo('pos');
        };

        renderPOS = (el) => {
            const categories = [
                ['foods', 'Foods'],
                ['pasta', 'Pasta'],
                ['drinks', 'Cafe Drinks'],
                ['cake', 'Cake']
            ];
            const selectedItems = getPOSMenuItems(currentPosCategory);

            el.innerHTML = `
                <div class="flex flex-col lg:flex-row gap-6 h-full">
                    <div class="flex-1 overflow-y-auto">
                        <div class="mb-6">
                            <h2 class="text-3xl font-bold" style="color: var(--primary)">Cashier Menu</h2>
                            <p class="text-gray-600 mt-1">Select food, drinks, and cakes for this order.</p>
                        </div>
                        <div class="flex flex-wrap gap-3 mb-6">
                            ${categories.map(([key, label]) => `
                                <button type="button" onclick="showPOSCategory('${key}')" class="pos-category-pill ${currentPosCategory === key ? 'active' : ''}">${label}</button>
                            `).join('')}
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                            ${selectedItems.map((item, index) => `
                                <article class="card home-menu-card overflow-hidden">
                                    <img class="menu-photo w-full" src="${item.img}" alt="${item.name}">
                                    <div class="p-5 home-card-body">
                                        <h3 class="text-xl font-bold mb-2" style="color: var(--primary)">${item.name}</h3>
                                        <p class="text-gray-600 mb-3">${item.desc}</p>
                                        <p class="font-bold text-green-700 mb-4">${item.price}</p>
                                        <button type="button" onclick="addPOSMenuItem('${currentPosCategory}', ${index})" class="btn-secondary">
                                            <i class="fas fa-cart-plus mr-1"></i> Add to Order
                                        </button>
                                    </div>
                                </article>
                            `).join('')}
                        </div>
                    </div>
                    <div class="w-full lg:w-96 bg-white p-4 rounded shadow flex flex-col">
                        <h2 class="text-xl font-bold mb-4 border-b pb-2">Current Order</h2>
                        <div id="cart-items" class="flex-1 overflow-y-auto">
                            ${currentCart.length === 0 ? '<p class="text-gray-500 text-center mt-10">No items yet</p>' : renderCartItems()}
                        </div>
                        <div class="mt-4 pt-4 border-t">
                            <div class="flex justify-between text-xl font-bold mb-4">
                                <span>Total:</span> <span id="cart-total">PHP ${calculateTotal()}</span>
                            </div>
                            <button onclick="checkout()" class="btn-primary w-full touch-btn ${currentCart.length === 0 ? 'opacity-50 cursor-not-allowed' : ''}" ${currentCart.length === 0 ? 'disabled' : ''}>
                                <i class="fas fa-credit-card mr-2"></i> Pay & Checkout
                            </button>
                        </div>
                    </div>
                </div>
            `;
        };

        const addToCart = (id) => {
            const item = db.menu.find(m => m.id === id);
            const existing = currentCart.find(c => c.id === id);
            if(existing) existing.qty++;
            else currentCart.push({ id: item.id, name: item.name, price: item.price, qty: 1 });
            navigateTo('pos');
        };

        const updateQty = (index, change) => {
            currentCart[index].qty += change;
            if(currentCart[index].qty <= 0) currentCart.splice(index, 1);
            navigateTo('pos');
        };

        const removeFromCart = (index) => {
            currentCart.splice(index, 1);
            navigateTo('pos');
        };

        const calculateTotal = () => currentCart.reduce((sum, item) => sum + (item.price * item.qty), 0);

        const checkout = () => {
            if(currentCart.length === 0) return;
            
            // 1. Deduct Inventory
            currentCart.forEach(cartItem => {
                const menuItem = db.menu.find(m => m.id === cartItem.id);
                if(!menuItem || !menuItem.ingredients) return;
                menuItem.ingredients.forEach(ing => {
                    const stockItem = db.inventory.find(i => i.id === ing.id);
                    if(!stockItem) return;
                    stockItem.stock -= (ing.qty * cartItem.qty);
                });
            });

            // 2. Create Order
            db.queue++;
            const newOrder = {
                id: 'ORD-' + Date.now(),
                queueNum: db.queue,
                items: currentCart,
                total: calculateTotal(),
                status: 'received', // received, preparing, ready
                time: new Date().toISOString()
            };
            db.orders.push(newOrder);
            
            saveDb();
            currentCart = [];
            navigateTo('pos');
            
            // 3. Simulate Payment & Print
            alert(`Payment successful via GCash/Maya!\nOrder Queue: #${newOrder.queueNum}`);
            printReceipt(newOrder);
        };

        const printReceipt = (order) => {
            const receiptItems = document.getElementById('receipt-items');
            receiptItems.innerHTML = order.items.map(i => `<p>${i.name} x${i.qty} ..... ₱${i.price * i.qty}</p>`).join('');
            document.getElementById('receipt-total').innerText = `Total: ₱${order.total}`;
            window.print();
        };

        // --- 2. KITCHEN & ORDERS ---
        const renderOrders = (el) => {
            const activeOrders = db.orders.filter(o => o.status !== 'completed');
            el.innerHTML = `
                <div class="card p-6 mb-6 text-center" style="background: var(--primary); color: white;">
                    <h2 class="text-3xl font-bold">Now Serving: #${db.queue > 0 ? db.queue - activeOrders.filter(o=>o.status==='ready').length : '0'}</h2>
                    <div class="flex justify-center gap-4 mt-4">
                        ${activeOrders.filter(o=>o.status==='ready').map(o => `<div class="bg-white text-yellow-900 font-bold p-2 rounded">Queue #${o.queueNum}</div>`).join('')}
                    </div>
                </div>
                <h2 class="text-2xl font-bold mb-4" style="color: var(--primary)">Active Orders</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    ${['received', 'preparing', 'ready'].map(status => `
                        <div>
                            <h3 class="font-bold text-lg mb-2 capitalize">${status.replace('received', '👨‍🍳 Received')}</h3>
                            <div class="space-y-2">
                                ${activeOrders.filter(o => o.status === status).map(o => `
                                    <div class="card p-3 border-l-4 ${status === 'received' ? 'border-blue-500' : status === 'preparing' ? 'border-yellow-500' : 'border-green-500'}">
                                        <div class="flex justify-between">
                                            <span class="font-bold">Order #${o.queueNum || o.id}</span>
                                            <span class="text-sm text-gray-500">${o.id.split('-')[1]}</span>
                                        </div>
                                        <ul class="text-sm my-2">${o.items.map(i => `<li>${i.qty}x ${i.name}</li>`).join('')}</ul>
                                        <button onclick="updateOrderStatus('${o.id}')" class="text-xs btn-primary py-1 px-2 w-full">
                                            ${status === 'received' ? 'Start Preparing' : status === 'preparing' ? 'Mark Ready' : 'Complete & Serve'}
                                        </button>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
        };

        const updateOrderStatus = (orderId) => {
            const order = db.orders.find(o => o.id === orderId);
            if(order.status === 'received') order.status = 'preparing';
            else if(order.status === 'preparing') order.status = 'ready';
            else order.status = 'completed';
            saveDb();
            renderOrders(document.getElementById('dynamic-content'));
        };

        // --- 3. INVENTORY & WASTE ---
        const renderInventory = (el) => {
            el.innerHTML = `
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold" style="color: var(--primary)">Inventory & Waste</h2>
                    <button onclick="logWaste()" class="bg-red-500 text-white px-4 py-2 rounded"><i class="fas fa-trash-alt mr-2"></i>Log Waste</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="card p-4">
                        <h3 class="font-bold text-lg mb-4">Ingredient Stock</h3>
                        <div class="space-y-4">
                            ${db.inventory.map(ing => {
                                const isLow = ing.stock <= ing.reorderPoint;
                                return `
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="font-bold">${ing.name} ${isLow ? '<span class="text-red-500 text-xs">(LOW STOCK!)</span>' : ''}</span>
                                        <span>${ing.stock} units</span>
                                    </div>
                                    <div class="status-bar">
                                        <div class="status-fill ${isLow ? 'bg-red-500' : ''}" style="width: ${(ing.stock/100)*100}%"></div>
                                    </div>
                                </div>`;
                            }).join('')}
                        </div>
                    </div>
                    <div class="card p-4">
                        <h3 class="font-bold text-lg mb-4">Waste Log</h3>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            ${db.waste.length === 0 ? '<p class="text-gray-500">No waste recorded.</p>' : 
                            db.waste.map(w => `
                                <div class="p-2 bg-red-50 rounded border border-red-100">
                                    <p class="font-bold text-sm">${w.item} - ${w.reason}</p>
                                    <p class="text-xs text-gray-500">${new Date(w.date).toLocaleString()}</p>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
        };

        const logWaste = () => {
            const reason = prompt("Enter waste details (e.g., 2x Croissant - Burned):");
            if(reason) {
                db.waste.push({ item: reason, reason: reason, date: new Date().toISOString() });
                saveDb();
                renderInventory(document.getElementById('dynamic-content'));
            }
        };

        // --- 4. CUSTOM CAKE ORDERS ---
        const renderCustomOrders = (el) => {
            el.innerHTML = `
                <h2 class="text-2xl font-bold mb-4" style="color: var(--primary)">Custom Cake Pre-Orders</h2>
                <div class="card p-4 mb-6">
                    <h3 class="font-bold mb-2">New Custom Order</h3>
                    <form onsubmit="submitCustomOrder(event)" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" placeholder="Customer Name" class="p-2 border rounded" required>
                        <input type="tel" placeholder="Phone Number" class="p-2 border rounded" required>
                        <select class="p-2 border rounded" required>
                            <option value="">Select Cake Size</option>
                            <option>6 Inch (2-4 pax)</option>
                            <option>8 Inch (6-8 pax)</option>
                            <option>10 Inch (10-12 pax)</option>
                        </select>
                        <input type="datetime-local" class="p-2 border rounded" required>
                        <textarea class="p-2 border rounded md:col-span-2" placeholder="Design Description & Notes..."></textarea>
                        <button type="submit" class="btn-primary">Submit for Approval</button>
                    </form>
                </div>
            `;
        };

        const submitCustomOrder = (e) => {
            e.preventDefault();
            alert("Custom Cake Order submitted! Kitchen staff will review.");
        };

        // --- 5. ADMIN DASHBOARD ---
        const renderDashboard = (el) => {
            const totalSales = db.orders.reduce((sum, o) => sum + o.total, 0);
            
            el.innerHTML = `
                <h2 class="text-2xl font-bold mb-6" style="color: var(--primary)">Admin Dashboard</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="card p-4 border-l-4 border-green-500">
                        <h3 class="text-gray-500">Total Sales</h3>
                        <p class="text-3xl font-bold">₱${totalSales}</p>
                    </div>
                    <div class="card p-4 border-l-4 border-blue-500">
                        <h3 class="text-gray-500">Total Orders</h3>
                        <p class="text-3xl font-bold">${db.orders.length}</p>
                    </div>
                    <div class="card p-4 border-l-4 border-red-500">
                        <h3 class="text-gray-500">Waste Incidents</h3>
                        <p class="text-3xl font-bold">${db.waste.length}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="card p-4">
                        <h3 class="font-bold mb-4">Best Selling Items</h3>
                        <div class="space-y-2">
                            ${getBestSellers().map(item => `
                                <div class="flex justify-between items-center">
                                    <span>${item.name}</span>
                                    <span class="font-bold">${item.qty} sold</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    <div class="card p-4">
                        <h3 class="font-bold mb-4">Customer Loyalty</h3>
                        <p class="text-gray-500 text-center py-8">Feature active. Customers earn digital stamps via phone number at checkout.</p>
                    </div>
                </div>
            `;
        };

        const getBestSellers = () => {
            let counts = {};
            db.orders.forEach(o => {
                o.items.forEach(i => {
                    counts[i.name] = (counts[i.name] || 0) + i.qty;
                });
            });
            return Object.entries(counts).map(([name, qty]) => ({name, qty})).sort((a,b) => b.qty - a.qty).slice(0, 3);
        };

        // Initial check for offline status
        showHomeCategory('foods');
        if(!navigator.onLine) document.getElementById('offline-banner').style.display = 'block';

    </script>
</body>
</html>
