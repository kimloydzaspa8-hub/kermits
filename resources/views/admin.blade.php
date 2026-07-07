<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ ucfirst(str_replace('-', ' ', $view ?? 'dashboard')) }} - Kermit's Restaurant</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #5D4037;
            --secondary: #D7CCC8;
            --accent: #8D6E63;
            --success: #81C784;
            --warning: #FFB74D;
            --danger: #E57373;
            --bg: #EFEBE9;
        }
	        html { scroll-behavior: smooth; height: 100%; }
	        body { min-height: 100%; }
	        body { background-color: var(--bg); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .btn-pos { background-color: var(--primary); color: white; border-radius: 8px; padding: 15px; font-size: 1.1rem; font-weight: bold; transition: background-color 0.18s ease, box-shadow 0.18s ease, opacity 0.18s ease; }
        .btn-pos:hover { box-shadow: 0 8px 18px rgba(93,64,55,0.18); }
        .btn-pos:active { background-color: var(--accent); }
				        .card { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 20px; }
				        .dashboard-stat-grid { display: grid; grid-template-columns: repeat(4, minmax(210px, 1fr)); gap: 34px; margin-bottom: 48px; }
				        .dashboard-stat-card { min-height: 164px; background: white; border: 1px solid #5D4037; border-left-width: 5px; border-radius: 22px; box-shadow: none; padding: 36px 38px; display: flex; flex-direction: column; justify-content: center; }
				        .dashboard-stat-card.revenue { border-color: #5D4037; }
				        .dashboard-stat-card.orders { border-color: #E46851; }
				        .dashboard-stat-card.active { border-color: #F59E0B; }
				        .dashboard-stat-card.delivery { border-color: #10B981; }
					        .dashboard-stat-label { color: #6B7280; font-size: 1rem; font-weight: 800; text-transform: uppercase; margin-bottom: 14px; }
					        .dashboard-stat-value { color: #111827; font-size: 2.75rem; line-height: 1; font-weight: 800; letter-spacing: 0; }
					        .recent-activity-card { background: white; border: 1px solid #E5DDD8; border-radius: 22px; box-shadow: 0 4px 8px rgba(75,47,37,0.05); padding: 42px 36px; }
					        .recent-activity-title { color: #3F2E27; font-family: Georgia, 'Times New Roman', serif; font-size: 1.5rem; font-weight: 900; margin-bottom: 26px; }
					        .recent-activity-table { width: 100%; border-collapse: collapse; }
					        .recent-activity-table th { background: #F0ECE6; color: #6B6B6B; font-size: 0.95rem; font-weight: 900; text-transform: uppercase; text-align: left; padding: 18px 24px; }
					        .recent-activity-table td { color: #111827; font-size: 1.05rem; padding: 18px 24px; border-bottom: 1px solid #E5DDD8; vertical-align: middle; }
					        .recent-activity-table tbody tr:last-child td { border-bottom: 0; }
					        .recent-activity-id, .recent-activity-amount { font-weight: 900; white-space: nowrap; }
					        .recent-activity-type { display: inline-flex; align-items: center; gap: 4px; color: #5F514B; text-transform: lowercase; }
					        .recent-activity-type.delivery { color: #E46851; }
					        .recent-activity-status { display: inline-flex; align-items: center; justify-content: center; border-radius: 999px; padding: 6px 16px; font-size: 0.85rem; font-weight: 900; text-transform: uppercase; white-space: nowrap; }
					        .recent-activity-status.completed, .recent-activity-status.delivered { background: #CFFAE5; color: #047857; }
					        .recent-activity-status.preparing { background: #DBEAFE; color: #1D4ED8; }
						        .recent-activity-status.ready, .recent-activity-status.waiting, .recent-activity-status.sending, .recent-activity-status.delivering { background: #FEF3C7; color: #92400E; }
						        .recent-activity-status.pending { background: #F3F4F6; color: #1F2937; }
						        .recent-activity-status.declined { background: #FEE2E2; color: #B91C1C; }
					        #app-shell { height: 100vh; height: 100dvh; min-height: 0; overflow: hidden; }
					        #main-content { min-width: 0; min-height: 0; scrollbar-width: none; -ms-overflow-style: none; }
					        #main-content::-webkit-scrollbar { width: 0; height: 0; }
					        .modal-scroll-body { scrollbar-width: none; -ms-overflow-style: none; }
					        .modal-scroll-body::-webkit-scrollbar { width: 0; height: 0; display: none; }
				        .nav-btn { min-height: 48px; border-left: 4px solid transparent; border-radius: 8px; font-size: 1rem; transition: background-color 0.18s ease, color 0.18s ease, border-color 0.18s ease; }
			        .nav-btn.active { border-left-color: var(--primary); background-color: var(--secondary); color: var(--primary); }
		        .inventory-action-btn { border-radius: 8px; padding: 8px 14px; font-size: 0.9rem; font-weight: 700; line-height: 1.2; }
				        .sales-chart { min-height: 260px; }
							        .sales-line-chart { width: 100%; height: 220px; display: block; overflow: visible; }
						        .sales-donut-wrap { display: grid; justify-items: center; gap: 10px; }
							        .sales-donut-chart { width: 100%; max-width: 220px; height: 150px; display: block; }
						        .sales-donut-legend { display: grid; grid-template-columns: repeat(6, minmax(48px, 1fr)); gap: 5px 8px; color: #AFC7EE; font-size: 0.75rem; width: 100%; max-width: 420px; }
						        .sales-donut-legend span:first-child { width: 26px; height: 10px; border: 2px solid #111827; display: inline-block; }
					        .sales-line-chart polyline { stroke-dasharray: 900; }
					        .sales-grid-line { animation: chartGridIn 0.55s ease both; }
					        .sales-area-fill { opacity: 0; animation: chartAreaIn 0.8s ease 0.35s forwards; }
					        .sales-trend-line { stroke-dasharray: 1; stroke-dashoffset: 1; animation: chartLineDraw 1.15s cubic-bezier(.22,.7,.2,1) 0.18s forwards; }
					        .sales-chart-point { transform-box: fill-box; transform-origin: center; animation: chartPointPop 0.42s cubic-bezier(.22,.9,.32,1.35) both; }
					        .sales-chart-bar { transform-box: fill-box; transform-origin: 50% 100%; animation: chartBarRise 0.72s cubic-bezier(.2,.75,.2,1) both; transition: opacity 0.18s ease, filter 0.18s ease; }
					        .sales-chart-bar:hover, .sales-chart-point:hover { filter: drop-shadow(0 8px 10px rgba(17, 24, 39, 0.18)); }
					        @keyframes chartGridIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
					        @keyframes chartAreaIn { from { opacity: 0; } to { opacity: 1; } }
					        @keyframes chartLineDraw { to { stroke-dashoffset: 0; } }
					        @keyframes chartPointPop { from { opacity: 0; transform: scale(0.35); } to { opacity: 1; transform: scale(1); } }
					        @keyframes chartBarRise { from { opacity: 0; transform: scaleY(0); } to { opacity: 1; transform: scaleY(1); } }
					        @media (prefers-reduced-motion: reduce) {
					            .sales-grid-line, .sales-area-fill, .sales-trend-line, .sales-chart-point, .sales-chart-bar {
					                animation: none;
					                opacity: 1;
					                transform: none;
					                stroke-dashoffset: 0;
					            }
					        }
							        .sales-line-chart rect:hover, .sales-line-chart circle:hover, .sales-donut-chart path:hover { opacity: 0.86; }
			        .chart-empty { min-height: 220px; display: flex; align-items: center; justify-content: center; color: #9CA3AF; background: #F9FAFB; border-radius: 8px; border: 1px dashed #D1D5DB; }
					        .all-orders-card { background: white; border: 1px solid #D8CFCA; border-radius: 18px; overflow: hidden; box-shadow: 0 4px 8px rgba(75,47,37,0.05); }
					        .all-orders-header { min-height: 86px; display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 24px; border-bottom: 1px solid #E5DDD8; }
					        .all-orders-header h2 { color: #2f2f2f; font-family: Georgia, 'Times New Roman', serif; font-size: 1.55rem; font-weight: 900; line-height: 1; }
					        .all-orders-filter { width: min(220px, 50vw); border: 1px solid #DED6D0; border-radius: 14px; padding: 10px 14px; color: #2f2f2f; background: white; font-size: 1rem; }
					        .all-orders-table-wrap { overflow: auto; }
					        .all-orders-table { min-width: 1080px; width: 100%; border-collapse: collapse; }
					        .all-orders-table th { background: #F1EEE9; color: #6B5F5A; padding: 20px 24px; text-align: left; font-size: 1rem; font-weight: 900; text-transform: uppercase; }
					        .all-orders-table td { border-bottom: 1px solid #DED6D0; color: #111827; padding: 17px 24px; font-size: 1rem; vertical-align: middle; }
					        .all-orders-table .order-id-cell { color: #111827; font-weight: 900; font-size: 1.08rem; white-space: nowrap; }
					        .all-orders-table .total-cell { color: #111827; font-weight: 900; white-space: nowrap; }
					        .all-orders-type { display: inline-flex; align-items: center; gap: 6px; text-transform: lowercase; font-weight: 500; white-space: nowrap; }
					        .all-orders-type.delivery { color: #E46851; }
					        .all-orders-type.pickup { color: #3F352F; }
					        .all-orders-type.dine { color: #5D4037; }
					        .order-status-pill { display: inline-flex; align-items: center; justify-content: center; min-width: 104px; padding: 7px 14px; border-radius: 999px; background: #C6F6D9; color: #047857; font-size: 0.86rem; font-weight: 900; letter-spacing: 0.03em; }
					        .order-status-pill.preparing { background: #DBEAFE; color: #1D4ED8; }
					        .order-status-pill.ready, .order-status-pill.delivering { background: #FEF3C7; color: #92400E; }
					        .order-status-pill.delivered, .order-status-pill.completed { background: #CFFAE5; color: #047857; }
				        .order-status-pill.declined { background: #FEE2E2; color: #B91C1C; }
				        .all-orders-total { display: none; }
						        .sidebar { width: 260px; flex-shrink: 0; min-height: 100vh; height: 100vh; height: 100dvh; overflow: hidden; }
	        .sidebar-brand { min-height: 92px; padding: 20px; gap: 12px; }
	        .sidebar-brand span.text-2xl { display: none; }
	        .sidebar-logo { width: 52px; height: 52px; object-fit: cover; border-radius: 999px; border: 2px solid var(--primary); flex-shrink: 0; }
	        .sidebar-title { color: var(--primary); font-size: 1.25rem; line-height: 1.75rem; font-weight: 700; }
	        .sidebar-subtitle { color: #6b7280; font-size: 0.875rem; line-height: 1.25; }
	        .login-panel { max-width: 420px; width: 100%; }
	        .login-input { border: 1px solid #D7CCC8; border-radius: 8px; padding: 12px 14px; width: 100%; }
					        .category-pill { background: white; border: 1px solid #D8CFCA; border-radius: 999px; color: #4B2F25; min-width: 96px; padding: 10px 20px; font-weight: 800; text-align: center; font-size: 0.95rem; box-shadow: 0 1px 2px rgba(75,47,37,0.04); transition: background-color 0.18s ease, color 0.18s ease, border-color 0.18s ease; }
					        .category-pill.active, .category-pill:hover { background: #5D4037; border-color: #5D4037; color: white; }
							        .menu-admin-card { min-height: 128px; padding: 18px 20px; border: 1px solid #E5DDD8; border-radius: 16px; box-shadow: 0 2px 8px rgba(75,47,37,0.06); display: grid; grid-template-columns: 96px 1fr; gap: 18px; align-items: center; }
							        .menu-admin-card > .flex:first-child { display: contents; }
							        .menu-admin-card > .flex:first-child > .flex-1 { min-width: 0; }
							        .menu-admin-card > .flex:last-child { grid-column: 2; margin-top: -26px; display: flex; gap: 12px; }
							        .menu-admin-card .inline-flex { display: none; }
					        .menu-admin-body { min-width: 0; }
					        .menu-form-photo { width: 96px; aspect-ratio: 16 / 9; height: auto; object-fit: cover; border-radius: 8px; background: #f3f4f6; }
					        .menu-inline-photo { width: 96px; height: 96px; object-fit: cover; border-radius: 10px; background: #f3f4f6; flex-shrink: 0; }
					        .menu-admin-card h4 { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1.15rem; line-height: 1.2; font-weight: 800; margin-bottom: 0.2rem; color: #5D4037 !important; }
				        .menu-admin-card p.text-sm { color: #6B5A53; font-size: 0.95rem; line-height: 1.25; margin-top: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; }
				        .menu-admin-card p.font-bold { color: #D96F55; font-size: 1.15rem; font-weight: 900; margin-top: 0.65rem; line-height: 1; }
				        .menu-admin-card > .flex:last-child button { padding: 0; border: 0; background: transparent; font-size: 0.9rem; line-height: 1; }
				        .menu-admin-card > .flex:last-child button:first-child { color: #2563EB; }
				        .menu-admin-card > .flex:last-child button:last-child { color: #EF4444; }
				        .menu-admin-card > .flex:last-child i { display: none; }
		        .swal-compact { font-size: 0.9rem; }
	        .swal-compact .swal2-title { font-size: 1.25rem; padding-top: 0.25rem; }
	        .swal-compact .swal2-html-container { font-size: 0.95rem; margin: 0.5rem 0 0; }
	        .swal-compact .swal2-actions { margin-top: 0.75rem; }
		        .swal-compact .swal2-confirm { padding: 0.55rem 1.25rem; }
		        .scroll-hidden { -ms-overflow-style: none; scrollbar-width: none; }
		        .scroll-hidden::-webkit-scrollbar { display: none; }
		        .card.overflow-x-auto > table { min-width: 720px; }
		        @media (max-width: 767px) {
					            #app-shell { height: 100vh; height: 100dvh; }
					            .sidebar { width: 100%; min-height: 0; height: auto; max-height: 72px; overflow-x: auto; overflow-y: hidden; z-index: 10; }
					            #nav-bar { min-width: max-content; }
					            .nav-btn { min-width: 78px; min-height: 56px; justify-content: center; flex-direction: column; gap: 4px; padding: 7px 8px; flex: 1 0 auto; font-size: 0.7rem; line-height: 1.1; text-align: center; }
					            .nav-btn i { width: auto !important; font-size: 1rem; }
					            .nav-btn span { max-width: 72px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
			            .nav-btn { border-left: 0; border-bottom: 4px solid transparent; }
			            .nav-btn.active { border-left: 0; border-bottom-color: var(--primary); }
			            #main-content { padding: 16px 12px 24px !important; }
			            #main-content h2 { font-size: 1.25rem !important; line-height: 1.2; }
			            #main-content h3 { line-height: 1.2; }
			            #main-content .items-center.justify-between { align-items: flex-start !important; flex-direction: column; }
			            #main-content .items-center.justify-between > button,
			            #main-content .items-center.justify-between > .btn-pos { width: 100%; justify-content: center; }
			            #main-content .flex-wrap.items-center { width: 100%; display: grid !important; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; }
			            #main-content .flex-wrap.items-center button { width: 100%; min-height: 42px; justify-content: center; }
			            .btn-pos, .inventory-action-btn { min-height: 42px; padding: 10px 12px; font-size: 0.9rem; }
			            .card { padding: 16px; border-radius: 10px; }
			            .sales-donut-legend { grid-template-columns: repeat(2, minmax(0, 1fr)); width: 100%; max-width: 240px; }
			            .dashboard-stat-grid { grid-template-columns: 1fr; gap: 16px; margin-bottom: 24px; }
			            .dashboard-stat-card { min-height: 118px; padding: 20px; border-radius: 14px; }
			            .dashboard-stat-label { font-size: 0.8rem; margin-bottom: 10px; }
			            .dashboard-stat-value { font-size: 1.9rem; }
			            .recent-activity-card { padding: 24px 16px; border-radius: 16px; overflow-x: auto; }
			            .recent-activity-table { min-width: 720px; }
			            .all-orders-header { flex-direction: column; align-items: stretch; padding: 18px 16px; }
			            .all-orders-filter { width: 100%; }
			            .all-orders-table { min-width: 760px; }
			            .menu-admin-card { grid-template-columns: 76px 1fr; gap: 12px; padding: 14px; }
		            .menu-inline-photo { width: 76px; height: 76px; }
		            .menu-admin-card h4 { font-size: 1.05rem; }
		            .menu-admin-card p.font-bold { font-size: 1rem; margin-top: 0.5rem; }
		            .menu-admin-card > .flex:last-child { margin-top: -20px; }
		        }
    </style>
</head>
<body class="h-screen overflow-hidden">
    <div id="app-shell" class="hidden flex-col md:flex-row h-full">
        <aside class="sidebar bg-white shadow z-10 flex md:flex-col">
            <div class="sidebar-brand hidden md:flex items-center border-b">
                <span class="text-2xl">??</span>
                <img src="{{ asset('kermit.jpg') }}" alt="Kermit's Restaurant" class="sidebar-logo">
                <div>
                <h2 class="sidebar-title">Dashboard</h2>
                    <p class="sidebar-subtitle">Kermit's Restaurant</p>
                </div>
            </div>
	            <div id="nav-bar" class="flex md:flex-col w-full p-2 md:p-3 gap-2">
		                <a id="nav-dashboard" href="{{ route('admin.dashboard') }}" class="nav-btn px-4 py-3 rounded flex items-center gap-3 text-gray-600 hover:bg-gray-100 text-left">
		                    <i class="fas fa-chart-pie w-5 text-center"></i> <span>Dashboard</span>
		                </a>
			                <a id="nav-menu" href="{{ route('admin.menu') }}" class="nav-btn px-4 py-3 rounded flex items-center gap-3 text-gray-600 hover:bg-gray-100 text-left">
			                    <i class="fas fa-utensils w-5 text-center"></i> <span>Menu</span>
			                </a>
			                <a id="nav-allorders" href="{{ route('admin.orders') }}" class="nav-btn px-4 py-3 rounded flex items-center gap-3 text-gray-600 hover:bg-gray-100 text-left">
			                    <i class="fas fa-list-check w-5 text-center"></i> <span>All Orders</span>
			                </a>
				                <a id="nav-inventory" href="{{ route('admin.inventory') }}" class="nav-btn px-4 py-3 rounded flex items-center gap-3 text-gray-600 hover:bg-gray-100 text-left">
				                    <i class="fas fa-boxes-stacked w-5 text-center"></i> <span>Inventory</span>
				                </a>
				                <a id="nav-staff" href="{{ route('admin.staff') }}" class="nav-btn px-4 py-3 rounded flex items-center gap-3 text-gray-600 hover:bg-gray-100 text-left">
				                    <i class="fas fa-users-gear w-5 text-center"></i> <span>Cashier/Staff</span>
				                </a>
                
	            </div>
            <div class="hidden md:block mt-auto p-4 border-t">
                <button type="button" onclick="logoutAdmin()" class="flex items-center gap-2 text-red-500 hover:text-red-700 px-4 py-3 rounded hover:bg-red-50 w-full text-left">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </div>
        </aside>

        <main id="main-content" class="flex-1 overflow-y-auto p-4 md:p-6"></main>
    </div>

		    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		    <script>
	        const ADMIN_LOGIN_URL = "{{ route('admin.login') }}";
						        const ADMIN_INITIAL_VIEW = @json($view ?? 'dashboard');
						        const POS_DATA_URL = "{{ route('cashier.pos.data') }}";
						        const POS_SAVE_URL = "{{ route('cashier.pos.save') }}";
								        const CASHIER_POS_URL = "{{ route('cashier.pos') }}";
								        const MENU_PHOTO_URL = "{{ route('cashier.pos.menu-photo') }}";
									        const STAFF_ACCOUNTS_URL = "{{ route('admin.staff.accounts.index') }}";
									        const RIDERS_STORAGE_KEY = 'kermitsRiders';
									        const MENU_CATEGORIES_STORAGE_KEY = 'kermitsMenuCategories';
							        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let db = {
	            menu: [
                { id: 1, name: 'Island Brew Coffee', price: 120, type: 'coffee', img: '?', stock: 50 },
                { id: 2, name: 'Bantayan Pastry', price: 85, type: 'pastry', img: '??', stock: 12 },
                { id: 3, name: 'Seafood Cake', price: 450, type: 'cake', img: '??', stock: 3 },
                { id: 4, name: 'Beef Pastry Pie', price: 150, type: 'meal', img: '??', stock: 0 }
	            ],
	            menuCategories: ['foods', 'pasta', 'drinks', 'cake'],
            inventory: [
                { name: 'Coffee Beans', unit: 'g', stock: 5000, reorder: 1000 },
                { name: 'Flour', unit: 'g', stock: 8000, reorder: 2000 },
                { name: 'Butter', unit: 'g', stock: 3000, reorder: 1000 },
                { name: 'Cream', unit: 'g', stock: 2000, reorder: 500 },
                { name: 'Beef', unit: 'g', stock: 500, reorder: 1000 }
            ],
            orders: [],
            customOrders: [],
            waste: [],
            loyalty: {},
            riders: [
                { id: 1, name: 'Juan Dela Cruz', phone: '0912 345 6789', area: 'Santa Fe', status: 'Available', deliveries: 12 },
                { id: 2, name: 'Marco Santos', phone: '0917 888 1234', area: 'Bantayan', status: 'On Delivery', deliveries: 8 },
                { id: 3, name: 'Lito Reyes', phone: '0999 555 4422', area: 'Madridejos', status: 'Inactive', deliveries: 3 }
            ],
            admins: [
                { id: 1, name: 'Admin User', email: 'admin@kermits.local', role: 'Owner', status: 'Active', lastActive: 'Today' },
                { id: 2, name: 'Cashier Lead', email: 'cashier@kermits.local', role: 'Cashier Manager', status: 'Active', lastActive: 'Yesterday' },
                { id: 3, name: 'Kitchen Lead', email: 'kitchen@kermits.local', role: 'Kitchen Manager', status: 'Inactive', lastActive: 'May 10, 2026' }
            ]
        };

		        let currentView = ADMIN_INITIAL_VIEW;
	        let menuSearchTerm = '';
	        let selectedMenuCategory = 'foods';

        function isAdminLoggedIn() {
            return sessionStorage.getItem('kermitsAdminAccess') === 'admin';
        }

	        function showAdminLogin() {
	            window.location.href = ADMIN_LOGIN_URL;
	        }

		        function showDashboardShell() {
		            document.getElementById('app-shell').classList.remove('hidden');
		            document.getElementById('app-shell').classList.add('flex');
		        }

	        function showAdminAlert(icon, title, text = '') {
	            if(window.Swal) {
		                return Swal.fire({
		                    icon,
		                    title,
		                    text,
		                    width: 320,
		                    padding: '1rem',
		                    customClass: {
		                        popup: 'swal-compact'
		                    },
		                    confirmButtonColor: '#5D4037'
		                });
	            }

	            alert(text ? `${title}\n${text}` : title);
	            return Promise.resolve();
	        }

	        function logoutAdmin() {
	            sessionStorage.removeItem('kermitsAdminAccess');
	            showAdminLogin();
        }

			        async function saveDB() {
			            const riders = loadRiders();
			            const menuCategories = Array.isArray(db.menuCategories) ? db.menuCategories : loadMenuCategories();
			            saveMenuCategories();
			            const response = await fetch(POS_SAVE_URL, {
	                method: 'POST',
	                headers: {
	                    'Content-Type': 'application/json',
	                    'Accept': 'application/json',
	                    'X-CSRF-TOKEN': CSRF_TOKEN
	                },
	                body: JSON.stringify(db)
	            });

	            if(!response.ok) throw new Error('Unable to save POS data.');
			            const adminAccounts = await loadStaffAccounts();
				            db = await response.json();
				            db.admins = adminAccounts;
				            db.riders = riders;
				            db.menuCategories = menuCategories;
		        }
	
		        async function loadDB() { 
			            const riders = loadRiders();
			            const menuCategories = loadMenuCategories();
		            const response = await fetch(POS_DATA_URL, {
		                headers: { 'Accept': 'application/json' }
		            });

	            if(!response.ok) throw new Error('Unable to load POS data.');
			            const adminAccounts = await loadStaffAccounts();
				            db = await response.json();
				            db.admins = adminAccounts;
				            db.riders = riders;
				            db.menuCategories = menuCategories;
				        }

			        function loadMenuCategories() {
			            try {
			                const stored = JSON.parse(localStorage.getItem(MENU_CATEGORIES_STORAGE_KEY) || 'null');
			                if(Array.isArray(stored)) return stored.filter(Boolean);
			            } catch (error) {
			                // Fall back to defaults below.
			            }
			            return ['foods', 'pasta', 'drinks', 'cake'];
			        }

			        function saveMenuCategories() {
			            localStorage.setItem(MENU_CATEGORIES_STORAGE_KEY, JSON.stringify(Array.isArray(db.menuCategories) ? db.menuCategories : []));
			        }

				        function loadRiders() {
			            try {
			                const stored = JSON.parse(localStorage.getItem(RIDERS_STORAGE_KEY) || 'null');
			                if(Array.isArray(stored)) return stored;
			            } catch(error) {}
			            return Array.isArray(db.riders) ? db.riders : [];
			        }

			        function saveRiders() {
			            localStorage.setItem(RIDERS_STORAGE_KEY, JSON.stringify(Array.isArray(db.riders) ? db.riders : []));
			        }

		        function escapeHtml(value) {
		            return String(value ?? '').replace(/[&<>"']/g, char => ({
		                '&': '&amp;',
		                '<': '&lt;',
		                '>': '&gt;',
		                '"': '&quot;',
		                "'": '&#39;'
		            }[char]));
		        }

		            @include('dashboard')
		            @include('menu')
		            @include('allorders')
			            @include('inventory')
						@include('staff')

	        function navigateTo(viewId) {
            currentView = viewId;
            document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
            const activeBtn = document.getElementById(`nav-${viewId}`);
            if(activeBtn) activeBtn.classList.add('active');
            
		            const renderMap = {
			                'dashboard': renderDashboard,
			                'menu': renderMenuCrud,
				                'allorders': renderAllOrders,
				                'inventory': renderInventory,
							'staff': renderStaffAccounts
			            };
            
	            const renderView = renderMap[viewId] || renderMap.dashboard;
	            document.getElementById('main-content').innerHTML = renderView();
        }

	        window.addEventListener('focus', () => {
		            if(!isAdminLoggedIn()) return;
		            if(typeof menuPhotoPickerOpen !== 'undefined' && menuPhotoPickerOpen) {
		                menuPhotoPickerOpen = false;
		                return;
		            }
	            loadDB().then(() => navigateTo(currentView));
	        });
				if(isAdminLoggedIn()) {
					showDashboardShell();
					loadDB()
						.then(() => navigateTo(currentView))
						.catch(() => {
							showAdminAlert('error', 'Database Error', 'Unable to load admin data.');
							navigateTo(currentView);
						});

					// Real-time refresh: reload POS data periodically and update dashboard when visible
					setInterval(async () => {
						try {
							await loadDB();
							if(currentView === 'dashboard') {
								document.getElementById('main-content').innerHTML = renderDashboard();
							}
						} catch (e) {
							// silently ignore intermittent errors
						}
					}, 7000);
				} else {
			showAdminLogin();
		}
    </script>
</body>
</html>
