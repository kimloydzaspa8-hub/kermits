<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cashier POS - Kermit's Restaurant</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
	    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        html, body { height: 100%; overflow: hidden; }
        body { background-color: var(--bg); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
	        .btn-pos { background-color: var(--primary); color: white; border-radius: 8px; padding: 15px; font-size: 1.1rem; font-weight: bold; transition: all 0.1s; }
	        .btn-pos:active { transform: scale(0.95); background-color: var(--accent); }
	        .card { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 20px; }
			        #app-shell { height: 100vh; height: 100dvh; min-height: 0; overflow: hidden; }
				        #main-content { min-width: 0; min-height: 0; display: flex; flex-direction: column; padding: 0 !important; box-sizing: border-box; }
	        #main-content, .pos-menu-pane, .pos-order-list, .cashier-order-lines, .overflow-y-auto, .overflow-x-auto { scrollbar-width: none; -ms-overflow-style: none; }
	        #main-content::-webkit-scrollbar, .pos-menu-pane::-webkit-scrollbar, .pos-order-list::-webkit-scrollbar, .cashier-order-lines::-webkit-scrollbar, .overflow-y-auto::-webkit-scrollbar, .overflow-x-auto::-webkit-scrollbar { display: none; }
	       	        .nav-btn { min-height: 48px; border-left: 4px solid transparent; border-radius: 8px; font-size: 1rem; transition: background-color 0.18s ease, color 0.18s ease, border-color 0.18s ease; }
		        .nav-btn.active { border-left-color: var(--primary); background-color: var(--secondary); color: var(--primary); }
		        .sidebar { width: 260px; height: 100vh; height: 100dvh; min-height: 0; overflow: hidden; flex-shrink: 0; }
		        .sidebar-logout { flex-shrink: 0; }
			        .cashier-logo { width: 52px; height: 52px; object-fit: cover; border-radius: 999px; border: 2px solid var(--primary); flex-shrink: 0; }
		        .cashier-brand { min-height: 92px; padding: 20px; display: grid !important; grid-template-columns: 52px 1fr; column-gap: 12px; align-items: center; }
	        .cashier-brand > span { display: none; }
	        .cashier-brand .cashier-logo { grid-row: 1 / span 2; }
	        .cashier-brand h2, .cashier-brand p { grid-column: 2; }
        @media (max-width: 767px) {
            .sidebar { width: 100%; height: auto; max-height: 72px; overflow-x: auto; overflow-y: hidden; }
            #nav-bar { min-width: max-content; }
            .nav-btn { min-width: 84px; min-height: 56px; justify-content: center; flex-direction: column; gap: 4px; padding: 7px 8px; flex: 1 0 auto; font-size: 0.75rem; line-height: 1.1; text-align: center; }
            .nav-btn i { width: auto !important; font-size: 1rem; }
            .nav-btn { border-left: 0; border-bottom: 4px solid transparent; }
            .nav-btn.active { border-left: 0; border-bottom-color: var(--primary); }
            .btn-pos { min-height: 42px; padding: 10px 12px; font-size: 0.92rem; }
            .pos-grid { grid-template-columns: repeat(auto-fill, minmax(142px, 1fr)); gap: 12px; padding: 16px 12px 8px; }
            .pos-category-bar { padding: 10px 12px; overflow-x: auto; }
            .pos-order-header { min-height: 58px; padding: 0 14px; }
            .pos-order-list { padding: 14px; }
            .pos-order-footer { padding: 14px; }
            .category-pill { min-width: auto; padding: 9px 14px; }
        }
        .sold-out { filter: grayscale(100%); opacity: 0.6; pointer-events: none; position: relative; }
        .sold-out::after { content: 'UNAVAILABLE'; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: red; color: white; padding: 5px 10px; border-radius: 5px; font-weight: bold; }
	        .menu-photo { height: 144px; object-fit: cover; border-radius: 8px; }
	        .menu-card { overflow: hidden; padding: 18px; border-radius: 20px; transition: transform 0.2s, box-shadow 0.2s; height: 100%; display: flex; flex-direction: column; border: 1px solid #E2DDD7; cursor: pointer; }
		        .menu-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
		        .menu-card-body { display: flex; flex-direction: column; flex: 1; padding: 14px 0 0; }
		        .menu-actions { margin-top: auto; display: grid; grid-template-columns: 1fr; gap: 10px; }
			        .category-pill { background: white; border: 1px solid var(--secondary); border-radius: 999px; color: var(--primary); min-width: 96px; padding: 10px 18px; font-weight: 700; text-align: center; }
		        .category-pill.active, .category-pill:hover { background: var(--primary); color: white; }
		        .btn-secondary { background-color: #F8BBD0; color: #5D4037; padding: 10px 16px; border-radius: 6px; font-weight: bold; }
				        .pos-layout { display: grid; grid-template-columns: minmax(0, 1fr) minmax(360px, 38%); flex: 1 1 auto; height: 100%; min-height: 0; background: var(--bg); overflow: hidden; border-radius: 0; }
		        .pos-menu-pane { min-width: 0; min-height: 0; overflow-y: auto; border-right: 1px solid #DDD6CE; }
		        .pos-category-bar { position: sticky; top: 0; z-index: 5; background: white; border-bottom: 1px solid #DDD6CE; padding: 12px 20px; }
		        .pos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(188px, 1fr)); gap: 24px; padding: 26px 20px 8px; }
				        .pos-order-panel { min-width: 0; min-height: 0; max-height: 100%; align-self: stretch; background: white; display: flex; flex-direction: column; overflow: hidden; }
			        .pos-order-header { flex-shrink: 0; min-height: 66px; padding: 0 18px; border-bottom: 1px solid #E5E0DA; display: flex; align-items: center; justify-content: space-between; gap: 16px; }
				        .pos-order-list { flex: 1 1 auto; min-height: 0; overflow-y: auto; padding: 18px; }
		        .pos-order-line { display: grid; grid-template-columns: 1fr auto; gap: 16px; align-items: center; padding-bottom: 22px; margin-bottom: 22px; border-bottom: 1px solid #E8E2DB; }
		        .pos-qty-control { display: inline-grid; grid-template-columns: 38px 38px 38px; align-items: center; min-height: 42px; overflow: hidden; border-radius: 6px; background: #F5F5F5; text-align: center; font-weight: 800; }
		        .pos-qty-control button { min-height: 42px; font-size: 1.1rem; color: #1F2937; }
		        .pos-qty-control span:not(.font-bold) { order: 2; }
		        .pos-qty-control span.font-bold { display: none; }
		        .pos-qty-control button:first-of-type { order: 1; }
		        .pos-qty-control button:last-of-type { order: 3; }
			        .pos-order-footer { flex-shrink: 0; border-top: 1px solid #E5E0DA; padding: 16px 18px 18px; background: #F7F3ED; }
		        .pos-order-footer #amount-received { display: none; }
		        .pos-payment-input { background: white; border: 1px solid #E5E7EB; border-radius: 6px; padding: 12px 16px; width: 100%; }
			        @media (max-width: 1024px) {
			            .pos-layout { grid-template-columns: 1fr; overflow-y: auto; }
			            .pos-menu-pane { overflow: visible; border-right: 0; }
				            .pos-order-panel { min-height: 0; overflow: visible; }
			        }
			        @media (max-width: 767px) {
			            .menu-card { padding: 12px; border-radius: 12px; }
			            .menu-card-body { padding-top: 10px; }
			            .menu-photo { height: 112px; border-radius: 8px; }
			            .menu-actions { gap: 8px; }
			            .pos-order-line { grid-template-columns: 1fr; gap: 10px; padding-bottom: 16px; margin-bottom: 16px; }
			            .pos-qty-control { grid-template-columns: 34px 34px 34px; min-height: 38px; justify-self: start; }
			            .pos-qty-control button { min-height: 38px; }
			        }
	        .swal-compact { font-size: 0.9rem; }
	        .swal-compact .swal2-title { font-size: 1.25rem; padding-top: 0.25rem; }
		        .swal-compact .swal2-html-container { font-size: 0.95rem; margin: 0.5rem 0 0; }
		        .swal-compact .swal2-actions { margin-top: 0.75rem; }
		        .swal-compact .swal2-confirm { padding: 0.55rem 1.25rem; }
		        .payment-popup { border-radius: 14px !important; max-height: calc(100vh - 24px); overflow: hidden; }
		        .payment-popup .swal2-title { font-family: Georgia, 'Times New Roman', serif; font-size: 1.45rem; color: #2f2f2f; padding: 0 0 0 2px; text-align: left; }
		        .payment-popup .swal2-html-container { margin: 0; max-height: calc(100vh - 210px); overflow-y: auto; }
			        .payment-box { text-align: left; color: #111827; }
			        .payment-order { text-align: left; color: #6b7280; font-size: 0.98rem; margin: 4px 0 14px; }
			        .payment-total-label { text-align: center; color: #6b7280; font-size: 0.82rem; font-weight: 700; text-transform: uppercase; }
			        .payment-total { text-align: center; color: var(--primary); font-size: 1.85rem; font-weight: 900; line-height: 1.05; margin-bottom: 18px; }
			        .payment-label { display: block; color: #6b7280; font-size: 0.78rem; font-weight: 900; text-transform: uppercase; margin: 0 0 6px; }
			        .payment-field { width: 100%; border: 1px solid #DED6D0; border-radius: 10px; padding: 10px 13px; font-size: 1rem; background: white; color: #111827; margin-bottom: 16px; }
			        .payment-divider { border-top: 1px dashed #DED6D0; margin: -1px 0 13px; }
			        .payment-change { display: flex; align-items: center; justify-content: space-between; gap: 14px; font-size: 1.1rem; font-weight: 900; margin-bottom: 16px; }
			        .payment-change span:last-child { color: #10B981; }
		        .payment-popup .swal2-actions { width: calc(100% - 40px); margin: 0 auto 0; gap: 8px; flex-direction: column; flex-shrink: 0; }
				        .payment-popup .swal2-confirm, .payment-popup .swal2-cancel { width: 100%; min-height: 42px; border-radius: 10px !important; font-size: 0.92rem !important; font-weight: 800 !important; }
						        .payment-popup .swal2-confirm { order: 1; }
				        .payment-popup .swal2-cancel { order: 2; }
								        .cashier-orders-view { margin: 24px; max-height: calc(100dvh - 48px); min-height: 0; background: white; border: 1px solid #D8CFCA; border-radius: 18px; overflow: hidden; box-shadow: 0 4px 8px rgba(75,47,37,0.05); display: flex; flex-direction: column; }
								        .cashier-orders-header { height: 104px; display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 24px; border-bottom: 1px solid #E5DDD8; flex-shrink: 0; }
									        .cashier-orders-title { color: #2f2f2f; font-family: Georgia, 'Times New Roman', serif; font-size: 1.55rem; font-weight: 900; line-height: 1; margin: 0; }
										        .cashier-orders-filter { width: 264px; height: 56px; min-width: 264px; border: 1px solid #DED6D0; border-radius: 14px; padding: 0 14px; color: #2f2f2f; background: white; font-size: 1rem; line-height: 1.2; box-sizing: border-box; }
						        .cashier-order-alert { display: none; }
						        .cashier-order-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; align-items: start; }
							        .cashier-order-card { background: white; border: 1px solid #D8CFCA; border-top: 4px solid var(--primary); border-radius: 12px; padding: 18px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); min-height: 0; display: flex; flex-direction: column; }
				        .cashier-order-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 8px; }
					        .cashier-order-id { color: #2f2f2f; font-size: 1.7rem; line-height: 1; font-weight: 900; }
				        .cashier-order-time { color: #6B7280; font-size: 0.85rem; margin-top: 4px; }
					        .cashier-order-type { color: var(--accent); font-weight: 700; margin: 8px 0 10px; }
				        .cashier-order-type.delivery { color: #E46851; font-weight: 500; }
				        .cashier-order-label { color: #6B7280; font-size: 0.72rem; font-weight: 900; text-transform: uppercase; margin-bottom: 6px; }
				        .cashier-order-items { display: grid; gap: 6px; margin-bottom: 10px; }
				        .cashier-order-item { display: flex; justify-content: space-between; gap: 12px; color: #111827; font-size: 0.92rem; }
				        .cashier-order-total { border-top: 1px solid #DED6D0; padding-top: 10px; display: flex; justify-content: space-between; gap: 14px; color: #2f2f2f; font-size: 1rem; font-weight: 900; margin-bottom: 10px; }
				        .cashier-order-address { color: var(--accent); font-weight: 700; margin-bottom: 10px; }
				        .cashier-delivery-address { background: #F3EEE8; border-radius: 8px; padding: 12px 14px; margin-bottom: 10px; color: #6B5F5A; font-weight: 500; }
					        .cashier-delivery-address-label { color: #6B5F5A; font-size: 0.78rem; font-weight: 900; text-transform: uppercase; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
				        .cashier-delivery-address-label i { color: #E46851; font-size: 0.98rem; line-height: 1; }
				        .cashier-delivery-address-title { color: #2f2f2f; font-size: 0.92rem; font-weight: 700; margin-bottom: 3px; }
				        .cashier-delivery-address-line { color: #6B5F5A; font-size: 0.88rem; line-height: 1.3; }
				        .cashier-delivery-location { color: #6B5F5A; font-size: 0.9rem; font-weight: 800; margin: -2px 0 10px; }
					        .cashier-order-actions { display: grid; grid-template-columns: minmax(48px, 0.55fr) repeat(3, minmax(0, 1fr)); gap: 8px; align-items: center; margin-top: auto; }
			        .cashier-order-actions.two { grid-template-columns: repeat(2, minmax(0, 1fr)); }
				        .cashier-order-action-muted { color: #9CA3AF; font-weight: 900; text-align: center; }
							        .cashier-order-action-light { background: white; border: 1px solid #DED6D0; color: #111827; border-radius: 8px; min-height: 48px; width: 100%; padding: 6px; font-size: clamp(0.68rem, 0.75vw, 0.82rem); line-height: 1.1; font-weight: 900; display: inline-flex; align-items: center; justify-content: center; text-align: center; overflow-wrap: anywhere; transition: background-color 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease, transform 0.12s ease; }
						        .cashier-order-action-light:hover { background: #f3eee8; border-color: #cfc3ba; box-shadow: 0 8px 18px rgba(75,58,50,0.12); transform: translateY(-1px); }
						        .cashier-order-action-light:active { box-shadow: 0 3px 8px rgba(75,58,50,0.12); transform: translateY(0) scale(0.97); }
					        .cashier-order-action-wide { grid-column: auto; }
					        .cashier-order-action-current { color: #9CA3AF; font-size: clamp(0.72rem, 0.8vw, 0.9rem); line-height: 1.1; font-weight: 900; text-align: center; min-height: 40px; padding: 6px; display: inline-flex; align-items: center; justify-content: center; overflow-wrap: anywhere; }
					        .cashier-order-action-danger { background: #DC2626; color: white; border-radius: 10px; min-height: 46px; font-weight: 900; }
						        .cashier-order-empty { background: white; padding: 32px; color: #6B7280; text-align: center; }
									        .cashier-order-lines { background: white; min-height: 0; overflow: auto; }
								        .cashier-order-table { min-width: 1080px; width: 100%; border-collapse: collapse; table-layout: auto; }
						        .cashier-order-table tr { border-bottom: 1px solid #DED6D0; }
									        .cashier-order-table th { background: #F1EEE9; color: #6B5F5A; padding: 20px 24px; text-align: left; font-size: 1rem; font-weight: 900; text-transform: uppercase; white-space: nowrap; }
									        .cashier-order-table td { padding: 17px 24px; color: #111827; font-size: 1rem; vertical-align: middle; white-space: nowrap; }
								        .cashier-order-table .order-num { font-size: 1.08rem; font-weight: 900; color: #111827; }
							        .cashier-order-table .order-muted { color: #6B7280; }
							        .cashier-order-table .order-type-cell { color: #E46851; font-weight: 900; text-transform: lowercase; }
								        .cashier-order-table .order-address-cell { white-space: normal; line-height: 1.35; overflow-wrap: anywhere; }
								        .cashier-order-table .order-total-cell { font-size: 1rem; font-weight: 900; color: #111827; }
							        .cashier-order-table .order-status-cell { text-align: center; }
							        .cashier-status-actions { display: flex; align-items: center; width: 100%; }
							        .cashier-status-button { width: 100%; min-width: 0; border-radius: 999px; padding: 10px 6px; font-size: 0.78rem; font-weight: 900; text-transform: uppercase; background: #f3f4f6; color: #6B7280; transition: transform 0.12s ease, box-shadow 0.16s ease; }
					        .cashier-status-button:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(75,58,50,0.12); }
					        .cashier-status-button.active.preparing { background: #DBEAFE; color: #1D4ED8; }
					        .cashier-status-button.active.ready, .cashier-status-button.active.sending { background: #FEF3C7; color: #92400E; }
					        .cashier-status-button.active.served { background: #CFFAE5; color: #047857; }
					        .cashier-row-actions { display: inline-flex; align-items: center; gap: 6px; }
					        .cashier-row-actions .cashier-order-action-light { width: auto; min-width: 76px; min-height: 34px; padding: 6px 12px; }
					        .cashier-row-actions .cashier-order-action-current { min-height: 34px; padding: 6px 10px; }
				        @media (max-width: 1400px) {
				            .cashier-order-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
				        }
						        @media (max-width: 640px) {
						            .cashier-orders-header { height: auto; min-height: 104px; align-items: flex-start; flex-direction: column; }
						            .cashier-orders-filter { width: 100%; min-width: 0; }
						            .cashier-order-grid { grid-template-columns: 1fr; gap: 14px; }
			            .cashier-order-card { padding: 18px; }
			            .cashier-order-id { font-size: 1.65rem; }
				            .cashier-order-actions { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; }
			            .cashier-order-actions.two { grid-template-columns: 1fr; }
			        }
		    </style>
	    <script>
	        if(sessionStorage.getItem('kermitsCashierAccess') !== 'cashier') {
	            window.location.replace("{{ route('cashier.login') }}");
	        }
	    </script>
	</head>
<body class="h-screen overflow-hidden">
    <div id="app-shell" class="flex flex-col md:flex-row h-full">
        <!-- Top Nav -->
        <aside class="sidebar bg-white shadow z-10 flex md:flex-col">
	            <div class="hidden md:block border-b cashier-brand">
                <span class="text-2xl">🐸</span>
	                <img src="{{ asset('kermit.jpg') }}" alt="Kermit's Restaurant" class="cashier-logo">
	                <h2 class="text-xl font-bold" style="color: var(--primary);">Cashier POS</h2>
                <p class="text-sm text-gray-500">Kermit's Restaurant</p>
            </div>
            <div id="nav-bar" class="flex md:flex-col w-full p-2 md:p-3 gap-2">
                <button id="nav-pos" class="nav-btn px-4 py-3 rounded flex items-center gap-3 text-gray-600 hover:bg-gray-100 active text-left" onclick="navigateTo('pos')">
                    <i class="fas fa-cash-register w-5 text-center"></i> <span>POS</span>
                </button>
                <button id="nav-orders" class="nav-btn px-4 py-3 rounded flex items-center gap-3 text-gray-600 hover:bg-gray-100 text-left" onclick="navigateTo('orders')">
                    <i class="fas fa-receipt w-5 text-center"></i> <span>Orders</span>
                </button>
            </div>
		            <div class="hidden md:block mt-auto p-4 border-t">
			                <button type="button" onclick="logoutCashier()" class="flex items-center gap-2 text-red-500 hover:text-red-700 px-4 py-3 rounded hover:bg-red-50 w-full text-left"><i class="fas fa-sign-out-alt"></i> Logout</button>
		            </div>
	        </aside>

        <!-- Content Area -->
        <main id="main-content" class="flex-1 overflow-y-auto p-4 md:p-6"></main>
    </div>

	    <script>
	        const CASHIER_LOGIN_URL = "{{ route('cashier.login') }}";
	        const CASHIER_POS_DATA_URL = "{{ route('cashier.pos.data') }}";
	        const CASHIER_POS_SAVE_URL = "{{ route('cashier.pos.save') }}";
	        const RIDERS_STORAGE_KEY = 'kermitsRiders';
	        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let db = {
            menu: [
                { id: 1, name: 'Island Brew Coffee', price: 120, type: 'coffee', img: '☕', stock: 50, ingredients: [{name: 'Coffee Beans', qty: 20}] },
                { id: 2, name: 'Bantayan Pastry', price: 85, type: 'pastry', img: '🥐', stock: 12, ingredients: [{name: 'Flour', qty: 100}, {name: 'Butter', qty: 50}] },
                { id: 3, name: 'Seafood Cake', price: 450, type: 'cake', img: '🎂', stock: 3, ingredients: [{name: 'Flour', qty: 200}, {name: 'Cream', qty: 150}] },
                { id: 4, name: 'Beef Pastry Pie', price: 150, type: 'meal', img: '🥧', stock: 0, ingredients: [{name: 'Beef', qty: 100}, {name: 'Flour', qty: 100}] }
            ],
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
	            riders: []
	        };

	        let currentView = 'pos';
	        let cart = [];
			        let amountReceived = '';
				        let cashierCategory = 'all';
				        let cashierOrdersFilter = 'all';
			        let isOnline = true;
	        async function saveDB() {
	            const riders = loadRiders();
	            const response = await fetch(CASHIER_POS_SAVE_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(db)
            });

            if(!response.ok) throw new Error('Unable to save cashier POS data.');
	            db = await response.json();
	            db.riders = riders;
	            syncCartWithMenu();
	        }

        async function loadDB() { 
            const response = await fetch(CASHIER_POS_DATA_URL, {
                headers: { 'Accept': 'application/json' }
            });

            if(!response.ok) throw new Error('Unable to load cashier POS data.');
	            db = await response.json();
	            db.riders = loadRiders();
	            syncCartWithMenu();
	        }

		        function loadRiders() {
		            try {
		                const stored = JSON.parse(localStorage.getItem(RIDERS_STORAGE_KEY) || 'null');
		                if(Array.isArray(stored)) return stored;
		            } catch(error) {}
		            return Array.isArray(db.riders) ? db.riders : [];
		        }

	        function findInventory(name) {
            return db.inventory.find(item => item.name === name || item.name.startsWith(`${name} (`));
        }

        function deductStock(items) {
            const recipeByCategory = {
                foods: [{ name: 'Flour', qty: 80 }, { name: 'Butter', qty: 20 }],
                pasta: [{ name: 'Flour', qty: 90 }, { name: 'Cream', qty: 40 }],
                drinks: [{ name: 'Coffee Beans', qty: 20 }],
                coffee: [{ name: 'Coffee Beans', qty: 20 }],
                cake: [{ name: 'Flour', qty: 160 }, { name: 'Cream', qty: 100 }],
                meal: [{ name: 'Beef', qty: 100 }, { name: 'Flour', qty: 70 }]
            };

            items.forEach(cartItem => {
                const menuItem = db.menu.find(item => String(item.id) === String(cartItem.id) || item.name === cartItem.name);
                const ingredients = menuItem?.ingredients || recipeByCategory[cartItem.category] || recipeByCategory[menuItem?.type] || [];
                ingredients.forEach(ingredient => {
                    const stockItem = findInventory(ingredient.name);
                    if(stockItem) stockItem.stock = Math.max(0, Number(stockItem.stock) - (Number(ingredient.qty) * Number(cartItem.qty || 1)));
                });
            });
        }

        async function createOrder(options) {
            db.queue = (Number(db.queue) || 0) + 1;
            const items = options.items.map(item => ({
                id: item.id,
                category: item.category,
                name: item.name,
                price: Number(item.price) || 0,
                qty: Number(item.qty) || 1
            }));
            const order = {
                id: `${options.prefix || 'ORD'}-${Date.now()}`,
                queueNum: db.queue,
                source: options.source || 'Cashier POS',
                items,
                total: Number(options.total) || items.reduce((sum, item) => sum + item.price * item.qty, 0),
                paymentStatus: options.paymentStatus || 'paid',
                status: ['unpaid', 'pending'].includes(options.paymentStatus) ? 'awaiting_payment' : 'received',
                createdAt: new Date().toISOString(),
                time: new Date().toLocaleTimeString(),
                date: new Date().toLocaleDateString()
            };

            if(order.paymentStatus === 'paid') deductStock(items);
            db.orders.push(order);
            await saveDB();
            return order;
        }

        function syncCartWithMenu() {
            if(!Array.isArray(cart) || cart.length === 0 || !Array.isArray(db.menu)) return;

            cart = cart
                .map(cartItem => {
                    const menuItem = db.menu.find(item => String(item.id) === String(cartItem.id));
	                    if(!menuItem || !isCashierItemAvailable(menuItem)) return null;

	                    const qty = Math.max(1, Number(cartItem.qty) || 1);
                    return {
                        ...cartItem,
                        category: menuItem.type || cartItem.category || 'foods',
                        name: menuItem.name,
                        price: Number(menuItem.price) || 0,
                        priceLabel: `₱${Number(menuItem.price) || 0}`,
                        qty
                    };
                })
                .filter(Boolean);
        }

		        function showAlert(icon, title, text = '') {
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
		                    heightAuto: false,
		                    confirmButtonColor: '#5D4037'
	                });
            }

            alert(text ? `${title}\n${text}` : title);
		            return Promise.resolve();
		        }

	        function logoutCashier() {
	            sessionStorage.removeItem('kermitsCashierAccess');
	            window.location.href = CASHIER_LOGIN_URL;
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

		        function showReceipt(order, received, change, paymentMethod = 'Cash') {
	            const rows = order.items.map(item => `
	                <div style="display:flex;justify-content:space-between;gap:12px;margin:4px 0;">
	                    <span>${Number(item.qty) || 1}x ${escapeHtml(item.name)}</span>
	                    <span>₱${(Number(item.price) || 0) * (Number(item.qty) || 1)}</span>
	                </div>
	            `).join('');
	            const receiptHtml = `
	                <div style="text-align:left;font-size:0.9rem;">
	                    <div style="text-align:center;margin-bottom:10px;">
	                        <strong>Kermit's Restaurant</strong><br>
	                        <span>Receipt ${escapeHtml(order.id)}</span><br>
	                        <span>Queue #${escapeHtml(order.queueNum || '-')} | ${escapeHtml(order.time || '')}</span>
	                    </div>
	                    <div style="border-top:1px dashed #ccc;border-bottom:1px dashed #ccc;padding:8px 0;margin-bottom:8px;">
	                        ${rows}
	                    </div>
		                    <div style="display:flex;justify-content:space-between;font-weight:700;"><span>Total</span><span>₱${Number(order.total) || 0}</span></div>
		                    <div style="display:flex;justify-content:space-between;"><span>Payment</span><span>${escapeHtml(paymentMethod)}</span></div>
			                    <div style="display:flex;justify-content:space-between;"><span>Received</span><span>₱${received}</span></div>
		                    <div style="display:flex;justify-content:space-between;"><span>Change</span><span>₱${change}</span></div>
	                </div>
		            `;

		            if(window.Swal) {
		                return Swal.fire({
	                    icon: 'success',
	                    title: 'Receipt',
	                    html: receiptHtml,
	                    width: 340,
	                    padding: '1rem',
		                    customClass: {
		                        popup: 'swal-compact'
		                    },
		                    showDenyButton: true,
		                    denyButtonText: 'Print',
		                    denyButtonColor: '#047857',
		                    confirmButtonText: 'Done',
		                    confirmButtonColor: '#5D4037'
		                }).then(result => {
		                    if(result.isDenied) printReceipt(receiptHtml);
		                });
		            }

			            alert(`Receipt ${order.id}\nTotal: ₱${order.total}\nPayment: ${paymentMethod}\nReceived: ₱${received}\nChange: ₱${change}`);
		            return Promise.resolve();
		        }

		        function printReceipt(receiptHtml) {
		            const printWindow = window.open('', '_blank', 'width=380,height=600');
		            if(!printWindow) {
		                alert('Please allow popups to print the receipt.');
		                return;
		            }
		            printWindow.document.write(`
		                <!DOCTYPE html>
		                <html>
		                <head>
		                    <title>Kermit's Receipt</title>
		                    <style>
		                        body { font-family: Arial, sans-serif; padding: 16px; }
		                        @media print { button { display: none; } }
		                    </style>
		                </head>
		                <body>${receiptHtml}</body>
		                </html>
		            `);
		            printWindow.document.close();
		            printWindow.focus();
		            printWindow.print();
		        }

			        function showCashierCategory(category) {
	            cashierCategory = category;
	            navigateTo('pos');
	        }

	        function getCashierCategoryItems(category) {
	            if(category === 'all') return db.menu;
	            return db.menu.filter(item => (item.type || 'foods') === category);
	        }

        function getCashierCategories() {
            const labels = {
                foods: 'Foods',
                pasta: 'Pasta',
                drinks: 'Cafe Drinks',
                cake: 'Cake',
                coffee: 'Coffee',
                pastry: 'Pastry',
                meal: 'Meals'
            };
            const preferredOrder = ['foods', 'pasta', 'drinks', 'cake', 'coffee', 'pastry', 'meal'];
            const types = [...new Set(db.menu.map(item => item.type || 'foods'))];
	            return [['all', 'All'], ...types
	                .sort((a, b) => {
	                    const aIndex = preferredOrder.indexOf(a);
	                    const bIndex = preferredOrder.indexOf(b);
	                    if(aIndex !== -1 || bIndex !== -1) return (aIndex === -1 ? 999 : aIndex) - (bIndex === -1 ? 999 : bIndex);
	                    return String(a).localeCompare(String(b));
	                })
	                .map(type => [type, labels[type] || String(type).replace(/[-_]/g, ' ').replace(/\b\w/g, char => char.toUpperCase())])];
	        }

        function renderCashierMenuImage(item) {
            const img = item.img || '🍽';
	            if(String(img).startsWith('http') || String(img).startsWith('data:image/') || String(img).startsWith('/menu-images/')) {
                return `<img class="menu-photo w-full" src="${escapeHtml(img)}" alt="${escapeHtml(item.name)}">`;
            }

            return `<div class="menu-photo w-full flex items-center justify-center bg-gray-100 text-6xl">${escapeHtml(img)}</div>`;
	        }

	        function isCashierItemAvailable(item) {
	            return Number(item?.stock ?? 1) > 0;
	        }

	        function renderCashierAvailability(item) {
	            return isCashierItemAvailable(item)
	                ? '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Available</span>'
	                : '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">Unavailable</span>';
	        }

	        function addCashierMenuItem(id) {
            const item = db.menu.find(menuItem => String(menuItem.id) === String(id));
            if(!item) return;
	            if(!isCashierItemAvailable(item)) {
	                showAlert('warning', 'Unavailable', 'This item is not available.');
                return;
            }

            const existing = cart.find(c => String(c.id) === String(item.id));
            if(existing) {
	                existing.qty++;
            } else {
                cart.push({
                    id: item.id,
                    category: item.type || 'foods',
                    name: item.name,
                    price: Number(item.price) || 0,
                    priceLabel: `₱${Number(item.price) || 0}`,
                    qty: 1
                });
            }
            renderCart();
        }

	        function removeFromCart(id) {
	            cart = cart.filter(c => String(c.id) !== String(id));
	            renderCart();
	        }

	        function changeCartQty(id, amount) {
	            const item = cart.find(c => String(c.id) === String(id));
	            if(!item) return;
	            item.qty += amount;
	            if(item.qty <= 0) {
	                cart = cart.filter(c => String(c.id) !== String(id));
	            }
	            renderCart();
	        }

	        function clearCart() {
	            cart = [];
	            amountReceived = '';
	            renderCart();
	        }

        function updateChangeMoney() {
            const input = document.getElementById('amount-received');
            if(!input) return;

            amountReceived = input.value;
            const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            const received = Number(amountReceived) || 0;
            const change = Math.max(received - total, 0);
            const changeEl = document.getElementById('change-money');
            if(changeEl) changeEl.textContent = `₱${change}`;
        }

        async function checkout() {
            if(cart.length === 0) return showAlert('warning', 'Cart Is Empty', 'Add an item before taking payment.');
            const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            const received = Number(amountReceived) || 0;

            if(received < total) {
                return showAlert('error', 'Not Enough Money', 'Amount received is less than the total.');
            }
            
            const order = await createOrder({
		                prefix: 'KC',
			                source: 'Cashier POS',
		                paymentStatus: 'paid',
	                items: cart,
	                total
	            });
		            showReceipt(order, received, received - total).then(() => {
		                cart = [];
		                amountReceived = '';
		                navigateTo('pos');
		            });
		        }

        function formatPaymentAmount(value) {
            return Number(value || 0).toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function updatePaymentModalChange(total) {
            const input = document.getElementById('payment-cash-received');
            const changeEl = document.getElementById('payment-change');
            if(!input || !changeEl) return;
            const received = Number(input.value) || 0;
	            changeEl.textContent = `\u20B1${formatPaymentAmount(Math.max(received - total, 0))}`;
        }

        async function showPaymentModal(total) {
            const nextQueue = (Number(db.queue) || 0) + 1;
            const result = await Swal.fire({
                title: 'Payment',
                html: `
                    <div class="payment-box">
                        <div class="payment-order">Order #${nextQueue}</div>
                        <div class="payment-total-label">Total Amount</div>
	                        <div class="payment-total">&#8369;${formatPaymentAmount(total)}</div>
                        <label for="payment-method" class="payment-label">Payment Method</label>
                        <select id="payment-method" class="payment-field">
                            <option value="Cash">Cash</option>
                           
                        </select>
                        <label for="payment-cash-received" class="payment-label">Cash Received</label>
                        <input id="payment-cash-received" class="payment-field" type="number" min="0" step="1" placeholder="0">
                        <div class="payment-divider"></div>
                        <div class="payment-change">
                            <span>Change</span>
	                            <span id="payment-change">&#8369;0.00</span>
                        </div>
                    </div>
                `,
			                width: 380,
			                padding: '0.45rem 1rem 1rem',
	                customClass: { popup: 'payment-popup' },
	                heightAuto: false,
	                confirmButtonText: 'Complete Order',
                confirmButtonColor: '#10B981',
                cancelButtonText: 'Cancel',
                showCancelButton: true,
                reverseButtons: true,
                focusConfirm: false,
                didOpen: () => {
                    const input = document.getElementById('payment-cash-received');
                    input?.addEventListener('input', () => updatePaymentModalChange(total));
                    input?.focus();
                },
                preConfirm: () => {
                    const method = document.getElementById('payment-method')?.value || 'Cash';
                    const received = Number(document.getElementById('payment-cash-received')?.value) || 0;
                    if(received < total) {
                        Swal.showValidationMessage('Cash received is less than the total amount.');
                        return false;
                    }
                    return { method, received, change: received - total };
                }
            });

            if(!result.isConfirmed || !result.value) return;

            const order = await createOrder({
                prefix: 'KC',
                source: 'Cashier POS',
                paymentStatus: 'paid',
                items: cart,
                total
            });

            const payment = result.value;
            await showReceipt(order, payment.received, payment.change, payment.method);
            cart = [];
            amountReceived = '';
            navigateTo('pos');
        }

        async function checkout() {
            if(cart.length === 0) return showAlert('warning', 'Cart Is Empty', 'Add an item before taking payment.');
            const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            return showPaymentModal(total);
        }

        function renderLegacyPOS() {
            const categories = [...new Set(db.menu.map(m => m.type))];
            const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

            return `
                <div class="flex flex-col md:flex-row gap-4 h-full">
                    <div class="flex-1 overflow-y-auto">
                        ${categories.map(category => `
                            <div class="mb-8">
                                <h3 class="text-xl font-bold mb-4 pb-2 border-b-2" style="color:var(--primary); border-color:var(--primary)">${category.charAt(0).toUpperCase() + category.slice(1)}</h3>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    ${db.menu.filter(item => item.type === category).map(item => `
                                        <div class="card flex flex-col items-center justify-center p-4 cursor-pointer hover:shadow-lg ${!isCashierItemAvailable(item) ? 'sold-out' : ''}" onclick="addToCart(${item.id})">
                                            <div class="text-5xl mb-2">${item.img}</div>
                                            <h3 class="font-bold text-gray-800 text-center text-sm">${item.name}</h3>
                                            <p class="text-green-700 font-bold mt-1">₱${item.price}</p>
                                            <div class="mt-2">${renderCashierAvailability(item)}</div>
                                        </div>
                                    `).join('')}
		                                </div>
		                            </div>
                        `).join('')}
                    </div>
	                    <div class="w-full md:w-96 bg-white rounded-xl shadow-lg flex flex-col self-start">
	                        <div class="p-3 border-b font-bold text-base" style="color:var(--primary); background: var(--secondary);">
	                            <i class="fas fa-shopping-cart"></i> Current Order
	                        </div>
	                        <div class="p-3 overflow-y-auto max-h-56" id="cart-items">
	                            ${cart.length === 0 ? '<p class="text-gray-400 text-center py-8">No items yet</p>' :
                            cart.map(c => `
                                <div class="flex justify-between items-center mb-3 pb-3 border-b">
                                    <div>
                                        <p class="font-bold">${c.name}</p>
                                        <p class="text-sm text-gray-500">₱${c.price} x ${c.qty}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold">₱${c.price * c.qty}</span>
                                        <button onclick="removeFromCart(${c.id})" class="text-red-500 hover:text-red-700"><i class="fas fa-times-circle"></i></button>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
		                        <div class="p-3 border-t">
                            <div class="flex justify-between font-bold text-xl mb-4">
                                <span>Total:</span>
                                <span style="color:var(--primary)">₱${total}</span>
                            </div>
		                            <div class="grid grid-cols-2 gap-2 mb-2 text-xs text-center text-gray-500">
                                <div class="border rounded p-1">GCash</div><div class="border rounded p-1">Maya</div>
                                <div class="border rounded p-1">GrabPay</div><div class="border rounded p-1">Card</div>
                            </div>
                            <button onclick="checkout()" class="w-full btn-pos py-4 text-lg mt-2">
                                <i class="fas fa-credit-card mr-2"></i> Pay Now
                            </button>
	                        </div>
		                    </div>
	                </div>
            `;
        }

        function formatPhilippineTime(date) {
            const options = { 
                timeZone: 'Asia/Manila',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };
            return new Intl.DateTimeFormat('en-US', options).format(date);
        }

        function formatPhilippineDateTime(date) {
            const options = { 
                timeZone: 'Asia/Manila',
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };
            return new Intl.DateTimeFormat('en-US', options).format(date);
        }

        function updatePhilippineTime() {
            const timeElement = document.getElementById('ph-time-display');
            if (timeElement) {
                timeElement.textContent = formatPhilippineTime(new Date());
            }
        }

        function renderPOS() {
            const categories = getCashierCategories();
            if(!categories.some(([key]) => key === cashierCategory)) {
                cashierCategory = categories[0]?.[0] || 'foods';
            }
            const selectedItems = getCashierCategoryItems(cashierCategory);
            const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            const received = Number(amountReceived) || 0;
            const change = Math.max(received - total, 0);

            return `
	                <div class="pos-layout">
	                    <section class="pos-menu-pane">
	                        <div>
	                            <div class="hidden">
                                <h2 class="text-3xl font-bold" style="color:var(--primary)">Cashier Menu</h2>
                                <p class="text-gray-600 mt-1">Select food, drinks, and cakes for this order.</p>
                            </div>
	                            <div class="pos-category-bar flex flex-wrap gap-3">
                                ${categories.map(([key, label]) => `
                                    <button type="button" onclick="showCashierCategory('${key}')" class="category-pill ${cashierCategory === key ? 'active' : ''}">${label}</button>
                                `).join('')}
                            </div>
	                            <div class="pos-grid">
                                ${selectedItems.map((item) => `
		                                    <article class="card menu-card ${!isCashierItemAvailable(item) ? 'sold-out' : ''}" onclick='addCashierMenuItem(${JSON.stringify(String(item.id))})'>
                                        ${renderCashierMenuImage(item)}
                                        <div class="menu-card-body">
                                            <h3 class="text-xl font-bold mb-2" style="color: var(--primary)">${escapeHtml(item.name)}</h3>
	                                            <p class="hidden">${escapeHtml(item.desc || 'Ready to add to this order.')}</p>
                                            <p class="font-bold text-green-700 mb-1">₱${Number(item.price) || 0}</p>
		                                            <div class="hidden">${renderCashierAvailability(item)}</div>
	                                            <div class="hidden">
	                                                <button type="button" onclick='addCashierMenuItem(${JSON.stringify(String(item.id))})' class="btn-secondary" ${!isCashierItemAvailable(item) ? 'disabled' : ''}>
                                                    <i class="fas fa-cart-plus mr-1"></i> Add to Order
                                                </button>
                                            </div>
                                        </div>
                                    </article>
                                `).join('')}
                            </div>
	                        </div>
	                    </section>
		                    <aside class="pos-order-panel">
	                        <div class="pos-order-header">
	                            <h2 class="text-2xl font-bold" style="color:var(--primary); font-family: inherit;">Current Order</h2>
	                            <button type="button" onclick="clearCart()" class="text-red-500 hover:text-red-700 font-bold">
	                                <i class="fas fa-trash mr-1"></i>Clear
	                            </button>
                        </div>
	                        <div class="pos-order-list" id="cart-items">
	                            ${cart.length === 0 ? '<p class="text-gray-400 text-center mt-10 text-xl">No items yet</p>' :
                            cart.map(c => `
	                                <div class="pos-order-line">
	                                    <div class="min-w-0">
	                                        <p class="font-bold text-xl truncate">${escapeHtml(c.name)}</p>
                                        <p class="text-sm text-gray-500">₱${c.price} x ${c.qty}</p>
                                    </div>
	                                    <div class="pos-qty-control">
	                                        <span>${c.qty}</span>
                                        <span class="font-bold">₱${c.price * c.qty}</span>
	                                        <button type="button" onclick="changeCartQty('${c.id}', -1)">-</button>
	                                        <button type="button" onclick="changeCartQty('${c.id}', 1)">+</button>
                                    </div>
                                </div>
                            `).join('')}
	                        </div>
		                        <div class="pos-order-footer">
			                            <div class="flex justify-between font-bold text-base mb-3">
			                                <span>Total:</span>
			                                <span style="color:var(--primary)">₱${total}</span>
	                            </div>
				                            <label for="amount-received" class="hidden">Money Received</label>
	                            <input id="amount-received" type="hidden" value="${amountReceived}">
			                            <div class="hidden">
                                <span>Change:</span>
                                <span id="change-money" class="text-green-700">₱${change}</span>
                            </div>
		                            <button onclick="checkout()" class="w-full btn-pos py-3 text-base mt-1">
	                                <i class="fas fa-credit-card mr-2"></i> Pay Now
	                            </button>
	                        </div>
	                    </aside>
	                </div>
            `;
        }

	        function renderCart() {
	            if(currentView !== 'pos') return;
	            document.getElementById('main-content').innerHTML = renderPOS();
	        }

		        function getPaymentStatus(order) {
	            return order.paymentStatus === 'unpaid' ? 'pending' : (order.paymentStatus || 'paid');
	        }

		        function paymentBadge(order) {
		            const status = getPaymentStatus(order);
		            const classes = {
		                paid: 'bg-green-100 text-green-700',
		                pending: 'bg-yellow-100 text-yellow-800',
		                declined: 'bg-red-100 text-red-700'
		            };
		            return `<span class="px-2 py-1 rounded-full text-xs font-bold ${classes[status] || classes.pending}">${status.toUpperCase()}</span>`;
		        }

		        function orderWorkflowBadge(order) {
		            const status = order.status || 'received';
				            const label = status === 'ready'
				                ? 'READY'
				                : status === 'sending'
				                    ? 'DELIVERING'
				                    : status === 'served' || status === 'completed'
				                        ? 'DELIVERED'
				                        : 'PREPARING';
				            const classes = label === 'DELIVERED'
				                ? 'bg-green-100 text-green-700'
				                : label === 'READY'
				                    ? 'bg-yellow-100 text-yellow-800'
				                    : label === 'DELIVERING'
				                        ? 'bg-yellow-100 text-yellow-800'
				                        : 'bg-blue-100 text-blue-700';
		            return `<span class="px-3 py-1 rounded-full text-xs font-bold ${classes}">${label}</span>`;
		        }

	        function paymentAction(order) {
	            const status = getPaymentStatus(order);
	            if(status === 'pending') {
	                return `
			                    <div class="cashier-order-actions two">
			                        <button type="button" onclick="payOrder('${order.id}')" class="btn-pos h-12 py-0 px-3 flex items-center justify-center">Mark Paid</button>
			                        <button type="button" onclick="declineOrder('${order.id}')" class="cashier-order-action-danger">Decline</button>
		                    </div>
		                `;
	            }
	            if(status === 'declined') return '<span class="text-red-500 text-sm font-bold">Declined</span>';
	            return '<span class="inline-flex items-center h-10 text-gray-400 text-sm">Done</span>';
	        }

		        function orderTypeLabel(order) {
		            const label = String(order.source || 'Cashier POS').replace('Customer Menu - ', '').replace(' - Cash', '').replace('Cash - ', '');
		            if(String(order.source || '').toLowerCase().includes('delivery')) {
		                const name = order.riderName || orderAreaRiderName(order);
		                return name ? `delivery &bull; ${escapeHtml(name)}` : 'delivery';
		            }
		            return escapeHtml(label);
		        }

	        function normalizeArea(value) {
	            return String(value || '').toLowerCase().replace(/[\s-]+/g, '');
	        }

		        function orderDeliveryLocation(order) {
		            const source = String(order.source || '').toLowerCase();
		            if(!source.includes('delivery')) {
		                return source.includes('pick') ? 'Pick-up' : 'Cashier';
		            }

		            const areas = ['Santa Fe', 'Madridejos', 'Bantayan'];
		            const addressParts = String(order.deliveryAddress || order.address || '')
		                .split(',')
		                .map(part => part.trim())
		                .filter(Boolean);

		            const exactArea = addressParts
		                .map(part => areas.find(area => normalizeArea(part) === normalizeArea(area)))
		                .find(Boolean);
		            if(exactArea) return exactArea;
	
		            const address = normalizeArea(addressParts.join(' '));
		            return areas.find(area => address.includes(normalizeArea(area))) || 'Location';
		        }

		        function orderAreaRiderName(order) {
		            const location = orderDeliveryLocation(order);
		            if(!location) return '';
					const locationKey = normalizeArea(location);
					const serverRiders = Array.isArray(db.riders) ? db.riders : [];
					const localRiders = loadRiders();
					const areaRiders = [...serverRiders, ...localRiders].filter(item => normalizeArea(item.area) === locationKey);
					const rider = areaRiders.find(item => String(item.status || '') !== 'Inactive') || areaRiders[0];
					return rider?.name || '';
		        }

	        function orderAddress(order) {
	            const type = String(order.source || '').toLowerCase();
	            if(type.includes('delivery')) {
	                const location = orderDeliveryLocation(order);
		                const rawAddress = String(order.deliveryAddress || order.address || '').trim();
		                if(rawAddress) {
		                    const parts = rawAddress.split(',').map(part => part.trim()).filter(Boolean);
		                    const landmark = parts[0] || '';
		                    const locationParts = parts.filter(part => {
		                        const lower = part.toLowerCase();
		                        return lower !== landmark.toLowerCase();
		                    });
	                    const addressLine = locationParts.length ? locationParts.join(', ') : (location ? `${location}, Bantayan Island, Cebu` : rawAddress);
	                    return `
	                        <div class="cashier-delivery-address">
		                            <div class="cashier-delivery-address-label"><i class="fas fa-map-marker-alt"></i>Delivery Address</div>
	                            ${landmark ? `<div class="cashier-delivery-address-title">${escapeHtml(landmark)}</div>` : ''}
	                            <div class="cashier-delivery-address-line">${escapeHtml(addressLine)}</div>
	                        </div>
	                    `;
		                }
		                return `<div class="cashier-delivery-location"><i class="fas fa-location-dot mr-1"></i>Delivery order: ${escapeHtml(location || 'Location')}</div>`;
	            }
	            if(type.includes('pick')) return '<i class="fas fa-store mr-1"></i>Pick-up order';
		            return '<i class="fas fa-cash-register mr-1"></i>Cashier order';
		        }

			        function orderLineAddress(order) {
			            const type = String(order.source || '').toLowerCase();
			            if(type.includes('delivery')) {
			                const rawAddress = String(order.deliveryAddress || order.address || '').trim();
			                if(rawAddress) return rawAddress.split(',').map(part => part.trim()).filter(Boolean)[0] || rawAddress;
			                return orderDeliveryLocation(order) || 'Delivery';
			            }
			            if(type.includes('pick')) return 'Pick-up order';
			            return 'Cashier order';
			        }

			        function orderTableDeliveryAddress(order) {
			            const type = String(order.source || '').toLowerCase();
			            if(type.includes('delivery')) {
			                const rawAddress = String(order.deliveryAddress || order.address || '').trim();
			                return rawAddress || orderDeliveryLocation(order) || 'Delivery';
			            }
			            if(type.includes('pick')) return 'Pick-up';
			            return 'Cashier';
			        }

		        function orderLineType(order) {
		            const source = String(order.source || '').toLowerCase();
		            if(source.includes('delivery')) return '<i class="fas fa-motorcycle mr-1"></i>delivery';
		            if(source.includes('pick')) return '<i class="fas fa-store mr-1"></i>pick-up';
		            return '<i class="fas fa-cash-register mr-1"></i>cashier';
		        }

			        function orderLineName(order) {
			            return escapeHtml(order.customerName || order.customer || order.name || order.riderName || orderAreaRiderName(order) || '-');
			        }

			        function orderLineRider(order) {
			            const source = String(order.source || '').toLowerCase();
			            if(!source.includes('delivery')) return 'N/A';
			            return escapeHtml(order.riderName || orderAreaRiderName(order) || 'Waiting for rider');
			        }

		        function kitchenActionButtons(order) {
		            const status = order.status || 'received';
		            const isDelivery = String(order.source || '').toLowerCase().includes('delivery');
					const step = status === 'ready' ? 'Ready' : status === 'sending' ? 'Sending' : status === 'served' ? 'Delivered' : 'Prep';
					if(step === 'Delivered') {
						return '<span class="inline-flex items-center h-10 text-gray-400 text-sm font-bold">Done</span>';
					}
					// Allow action buttons for delivery orders too so cashier can advance to 'sending' (assign rider)
					const stepControl = (label, nextStatus, extraClass = '') => {
						const stepOrder = { Prep: 1, Ready: 2, Sending: 3, Delivered: 4 };
						if(stepOrder[label] < stepOrder[step]) {
							return `<span class="cashier-order-action-current ${extraClass}">${label}</span>`;
						}
						return step === label
							? `<span class="cashier-order-action-current ${extraClass}">${label}</span>`
							: `<button type="button" class="cashier-order-action-light ${extraClass}" onclick="setOrderKitchenStatus('${order.id}', '${nextStatus}')">${label}</button>`;
					};
					return `
						<div class="cashier-order-actions">
							${stepControl('Prep', 'preparing')}
							${stepControl('Ready', 'ready')}
							${stepControl('Sending', 'sending')}
							${stepControl('Delivered', 'served', 'cashier-order-action-wide')}
						</div>
					`;
		        }

		        function cashierOrderActions(order) {
		            return kitchenActionButtons(order);
		        }

					        function cashierRowActions(order) {
					            const status = order.status || 'received';
					            const isDelivery = String(order.source || '').toLowerCase().includes('delivery');
					            if(status === 'served' || status === 'completed') return '<span class="text-gray-400 text-sm font-bold">Done</span>';
				            if(isDelivery && (status === 'ready' || status === 'sending')) {
				                return `<span class="cashier-order-action-current">${status === 'sending' ? 'Delivering' : 'Ready'}</span>`;
				            }
								const stepOrder = { preparing: 1, ready: 2, sending: 3, served: 4 };
								const current = status === 'ready' ? 2 : status === 'sending' ? 3 : status === 'served' ? 4 : 1;
								const control = (label, nextStatus) => {
									const nextStep = stepOrder[nextStatus] || 1;
									if(nextStep <= current) return `<span class="cashier-order-action-current">${label}</span>`;
									return `<button type="button" class="cashier-order-action-light" onclick="setOrderKitchenStatus('${order.id}', '${nextStatus}')">${label}</button>`;
								};
								return `
									<div class="cashier-row-actions">
										${control('Ready', 'ready')}
										${control('Sending', 'sending')}
										${control('Delivered', 'served')}
									</div>
								`;
						        }

							        function cashierStatusButtons(order) {
							            const isDelivery = String(order.source || '').toLowerCase().includes('delivery');
							            const current = order.status === 'served' || order.status === 'completed'
							                ? 'served'
							                : order.status === 'ready'
					                    ? 'ready'
					                    : order.status === 'sending'
				                        ? 'sending'
				                        : 'preparing';
					            const labels = {
					                preparing: 'Preparing',
					                ready: 'Ready',
					                sending: isDelivery ? 'Delivering' : 'Sending',
					                served: 'Delivered'
					            };
					            const nextStatus = {
					                preparing: 'ready',
					                ready: 'sending',
											sending: 'served',
											served: 'served'
										};
										// Always show a status button so cashier can advance deliveries to 'sending' (which assigns a rider)
											const disabled = current === 'served' || (isDelivery && (current === 'ready' || current === 'sending')) ? 'disabled' : '';
										return `
											<div class="cashier-status-actions">
												<button type="button" class="cashier-status-button ${current} active" onclick="setOrderKitchenStatus('${order.id}', '${nextStatus[current]}')" ${disabled}>${labels[current]}</button>
											</div>
										`;
				        }

				        function cashierOrdersFulfillment(order) {
				            const source = String(order.source || '').toLowerCase();
				            if(source.includes('delivery')) return 'delivery';
				            if(source.includes('pick') || source.includes('take')) return 'pickup';
				            return 'dine';
				        }

				        function cashierOrdersWorkflowStatus(order) {
				            if(order.status === 'declined') return 'declined';
				            if(order.status === 'served' || order.status === 'completed') {
				                return cashierOrdersFulfillment(order) === 'delivery' ? 'delivered' : 'completed';
				            }
				            if(order.status === 'sending') return 'delivering';
				            if(order.status === 'ready') return 'ready';
				            return 'preparing';
				        }

				        function cashierOrdersMatchesFilter(order) {
				            if(cashierOrdersFilter === 'all') return true;
				            if(cashierOrdersFilter === 'delivery' || cashierOrdersFilter === 'pickup') {
				                return cashierOrdersFulfillment(order) === cashierOrdersFilter;
				            }
				            return cashierOrdersWorkflowStatus(order) === cashierOrdersFilter;
				        }

				        function cashierOrdersFilterSelect() {
				            return `
				                <select class="cashier-orders-filter" onchange="cashierOrdersFilter = this.value; navigateTo('orders')">
				                    <option value="all" ${cashierOrdersFilter === 'all' ? 'selected' : ''}>All Orders</option>
				                    <option value="delivery" ${cashierOrdersFilter === 'delivery' ? 'selected' : ''}>Delivery</option>
				                    <option value="pickup" ${cashierOrdersFilter === 'pickup' ? 'selected' : ''}>Pickup</option>
				                    <option value="preparing" ${cashierOrdersFilter === 'preparing' ? 'selected' : ''}>Preparing</option>
				                    <option value="ready" ${cashierOrdersFilter === 'ready' ? 'selected' : ''}>Ready</option>
				                    <option value="delivering" ${cashierOrdersFilter === 'delivering' ? 'selected' : ''}>Delivering</option>
				                    <option value="delivered" ${cashierOrdersFilter === 'delivered' ? 'selected' : ''}>Delivered</option>
				                </select>
				            `;
				        }

				        function renderOrdersAsCards(orders) {
				            return `
						                <div class="cashier-orders-view">
						                    <div class="cashier-orders-header">
						                        <h2 class="cashier-orders-title">Active Orders</h2>
						                        ${cashierOrdersFilterSelect()}
						                    </div>
					                ${orders.length === 0 ? '<div class="cashier-order-empty">No orders yet.</div>' : `
					                    <div class="cashier-order-lines">
				                        <table class="cashier-order-table">
					                            <thead>
			                                <tr>
			                                    <th>ID</th>
			                                    <th>Time</th>
			                                    <th>Customer</th>
			                                    <th>Type</th>
				                                    <th>Delivery Address</th>
			                                    <th>Rider</th>
			                                    <th>Total</th>
			                                    <th>Status</th>
			                                </tr>
			                            </thead>
			                            <tbody>
			                        ${orders.map(o => `
			                            <tr>
			                                <td class="order-num">#${escapeHtml(o.queueNum || o.id || '-')}</td>
			                                <td class="order-muted">${escapeHtml(o.time || '')}</td>
			                                <td>${orderLineName(o)}</td>
			                                <td class="order-type-cell">${orderLineType(o)}</td>
					                                <td class="order-address-cell">${escapeHtml(orderTableDeliveryAddress(o) || '-')}</td>
			                                <td>${orderLineRider(o)}</td>
			                                <td class="order-total-cell">&#8369;${Number(o.total) || 0}</td>
				                                <td class="order-status-cell">${cashierStatusButtons(o)}</td>
			                            </tr>
			                        `).join('')}
		                            </tbody>
		                        </table>
			                    </div>
			                `}
			                </div>
			            `;
		        }

		        async function setOrderKitchenStatus(orderId, status) {
		            const order = db.orders.find(item => item.id === orderId);
		            if(!order) return;
		            order.status = status;
		            if(status === 'sending' && String(order.source || '').toLowerCase().includes('delivery')) {
		                order.deliveryStarted = false;
		                order.riderName = orderAreaRiderName(order) || order.riderName || '';
		            }
	            await saveDB();
	            navigateTo('orders');
	        }

	        function kitchenStatusBadge(order) {
	            const status = order.status || 'received';
	            const classes = status === 'declined'
	                ? 'bg-red-100 text-red-700'
	                : status === 'ready'
	                    ? 'bg-green-100 text-green-700'
	                    : 'bg-blue-100 text-blue-700';
	            return `<span class="px-2 py-1 rounded-full text-xs font-bold ${classes}">${status.replace('_', ' ').toUpperCase()}</span>`;
	        }

	        function renderOrders() {
	            const isDoneOrder = order => ['served', 'completed'].includes(order.status);
		            const orders = [...db.orders].filter(cashierOrdersMatchesFilter).sort((a, b) => {
	                const doneSort = Number(isDoneOrder(a)) - Number(isDoneOrder(b));
	                if(doneSort !== 0) return doneSort;
	                return (b.queueNum || 0) - (a.queueNum || 0);
	            });
	            return renderOrdersAsCards(orders);
	            return `
	                <h2 class="text-2xl font-bold mb-4" style="color:var(--primary)">Cashier Orders & Payments</h2>
	                ${orders.some(o => getPaymentStatus(o) === 'pending') ? '<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 mb-4"><i class="fas fa-cash-register mr-2"></i>Customer orders waiting for payment are listed below.</div>' : ''}
                <div class="overflow-x-auto">
	                    <table class="w-full bg-white rounded-lg shadow text-sm">
	                        <thead><tr class="bg-gray-100 text-left text-xs">
				                            <th class="px-2 py-2">Queue</th><th class="px-2 py-2">Order ID</th><th class="px-2 py-2">Type</th><th class="px-2 py-2">Items</th><th class="px-2 py-2">Total</th><th class="px-2 py-2">Payment</th><th class="px-2 py-2">Action</th>
                        </tr></thead>
                        <tbody>
                            ${orders.map(o => `
	                                <tr class="border-b h-14">
			                                    <td class="px-2 py-2 font-bold whitespace-nowrap align-middle">#${o.queueNum || '-'}</td>
			                                    <td class="px-2 py-2 font-bold leading-tight align-middle">${o.id}</td>
			                                    <td class="px-2 py-2 whitespace-nowrap align-middle">${escapeHtml(String(o.source || 'Cashier POS').replace('Customer Menu - ', '').replace(' - Cash', ''))}</td>
			                                    <td class="px-2 py-2 text-xs leading-snug align-middle">${o.items.map(i => `${i.qty}x ${i.name}`).join(', ')}</td>
		                                    <td class="px-2 py-2 whitespace-nowrap align-middle">₱${o.total}</td>
		                                    <td class="px-2 py-2 align-middle">${paymentBadge(o)}</td>
			                                    <td class="px-2 py-2 align-middle">${paymentAction(o)}</td>
                                </tr>
                            `).join('')}
				                            ${orders.length === 0 ? '<tr><td class="px-2 py-2 text-gray-400" colspan="7">No orders yet.</td></tr>' : ''}
                        </tbody>
                    </table>
                </div>
            `;
            return `
                <h2 class="text-2xl font-bold mb-4" style="color:var(--primary)">Order History</h2>
                <div class="overflow-x-auto">
                    <table class="w-full bg-white rounded-lg shadow">
                        <thead><tr class="bg-gray-100 text-left text-sm">
                            <th class="p-3">Order ID</th><th class="p-3">Items</th><th class="p-3">Total</th><th class="p-3">Status</th><th class="p-3">Time</th>
                        </tr></thead>
                        <tbody>
                            ${db.orders.map(o => `
                                <tr class="border-b">
                                    <td class="p-3 font-bold">${o.id}</td>
                                    <td class="p-3 text-sm">${o.items.map(i => i.name).join(', ')}</td>
                                    <td class="p-3">₱${o.total}</td>
                                    <td class="p-3"><span class="px-2 py-1 rounded-full text-xs font-bold ${o.status==='ready' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'}">${o.status.toUpperCase()}</span></td>
                                    <td class="p-3 text-sm">${o.time}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        function renderQueue() {
            const paidOrders = db.orders.filter(o => o.paymentStatus === 'paid');
            const serving = paidOrders.find(o => o.status === 'ready') || paidOrders[paidOrders.length-1];
            return `
                <div class="flex flex-col items-center justify-center h-full bg-white rounded-xl shadow-lg text-center">
                    <h2 class="text-3xl text-gray-500 mb-4">Now Serving</h2>
                    <div style="font-size: 8rem; font-weight: bold; color: var(--primary); line-height: 1;">${serving ? serving.id : '---'}</div>
                    <p class="text-xl text-gray-400 mt-4">Please proceed to the counter</p>
                    <div class="mt-8 text-sm text-gray-400">
                        Pending Orders: ${paidOrders.filter(o => o.status !== 'ready' && o.status !== 'completed').length}
                    </div>
                </div>
            `;
        }

			        function buildPaymentDialog(order) {
			            const total = Number(order.total) || 0;
			            const itemRows = order.items.map(item => {
			                const qty = Number(item.qty) || 1;
			                const price = Number(item.price) || 0;
			                return `
			                    <div style="display:flex;justify-content:space-between;gap:10px;padding:4px 0;border-bottom:1px solid #f3f4f6;">
			                        <span>${qty}x ${escapeHtml(item.name)}</span>
			                        <strong>₱${price * qty}</strong>
				                    </div>
			                `;
			            }).join('');

			            return `
			                <div style="text-align:left;">
			                    <div style="display:flex;justify-content:space-between;gap:12px;margin-bottom:8px;">
			                        <div>
			                            <div style="font-weight:700;color:#5D4037;">Order ${escapeHtml(order.id)}</div>
			                            <div style="font-size:0.82rem;color:#6b7280;">Queue #${escapeHtml(order.queueNum || '-')} | ${escapeHtml(String(order.source || 'Cashier POS').replace('Customer Menu - ', ''))}</div>
			                        </div>
			                        <div style="font-weight:800;color:#111827;">₱${total}</div>
			                    </div>
			                    <div style="max-height:130px;overflow:auto;margin-bottom:12px;">${itemRows}</div>
			                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Payment method</label>
			                    <select id="mark-paid-method" style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:9px 10px;margin-bottom:10px;">
			                        <option value="Cash">Cash</option>
			                        
			                    </select>
			                    <label style="display:block;font-size:0.82rem;font-weight:700;margin-bottom:4px;">Amount received</label>
			                    <input id="mark-paid-received" type="number" min="${total}" step="1" value="${total}" style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:9px 10px;margin-bottom:12px;">
			                    <div id="mark-paid-preview" style="background:#f9fafb;border:1px dashed #d1d5db;border-radius:8px;padding:10px;font-size:0.9rem;"></div>
			                </div>
			            `;
			        }

			        async function payOrder(orderId) {
				            const order = db.orders.find(item => item.id === orderId);
				            if(!order) {
				                await loadDB();
				                navigateTo('orders');
				                return;
				            }

				            const total = Number(order.total) || 0;
				            let received = total;
				            let paymentMethod = order.paymentMethod || 'Cash';
				            if(window.Swal) {
				                const result = await Swal.fire({
				                    title: 'Review Payment',
				                    html: buildPaymentDialog(order),
				                    showCancelButton: true,
				                    confirmButtonText: 'Mark Paid',
				                    confirmButtonColor: '#5D4037',
				                    cancelButtonText: 'Cancel',
				                    width: 430,
				                    didOpen: () => {
				                        const methodInput = document.getElementById('mark-paid-method');
				                        const receivedInput = document.getElementById('mark-paid-received');
				                        const preview = document.getElementById('mark-paid-preview');
				                        const updatePreview = () => {
				                            const method = methodInput.value || 'Cash';
				                            const amount = Number(receivedInput.value) || 0;
				                            const change = Math.max(amount - total, 0);
				                            preview.innerHTML = `
				                                <div style="display:flex;justify-content:space-between;font-weight:700;"><span>Receipt Total</span><span>₱${total}</span></div>
				                                <div style="display:flex;justify-content:space-between;"><span>Payment</span><span>${escapeHtml(method)}</span></div>
				                                <div style="display:flex;justify-content:space-between;"><span>Received</span><span>₱${amount}</span></div>
				                                <div style="display:flex;justify-content:space-between;"><span>Change</span><span>₱${change}</span></div>
				                            `;
				                        };
				                        methodInput.value = paymentMethod;
				                        methodInput.addEventListener('change', updatePreview);
				                        receivedInput.addEventListener('input', updatePreview);
				                        updatePreview();
				                    },
				                    preConfirm: () => {
				                        const method = document.getElementById('mark-paid-method')?.value || 'Cash';
				                        const amount = Number(document.getElementById('mark-paid-received')?.value) || 0;
				                        if(amount < total) {
				                            Swal.showValidationMessage('Amount received is less than the total.');
				                            return false;
				                        }
				                        return { method, amount };
				                    }
				                });
				                if(!result.isConfirmed) return;
				                received = Number(result.value.amount) || total;
				                paymentMethod = result.value.method || 'Cash';
				            } else {
				                paymentMethod = prompt('Payment method:', paymentMethod) || paymentMethod;
				                received = Number(prompt(`Total: ₱${total}\nAmount received:`, total)) || 0;
				                if(received < total) {
				                    alert('Amount received is less than the total.');
				                    return;
				                }
				            }

				            order.paymentStatus = 'paid';
				            order.paymentMethod = paymentMethod;
				            order.cashReceived = received;
				            order.changeDue = received - total;
				            order.status = 'received';
				            order.paidAt = new Date().toLocaleString();
				            deductStock(order.items);
				            await saveDB();
				            navigateTo('orders');
				            await showReceipt(order, received, received - total, paymentMethod);
				        }

	        function declineOrder(orderId) {
		            const runDecline = async () => {
		                const order = db.orders.find(item => item.id === orderId);
		                if(order && order.paymentStatus !== 'paid') {
		                    order.paymentStatus = 'declined';
		                    order.status = 'declined';
		                    order.declinedAt = new Date().toLocaleString();
		                    await saveDB();
		                } else {
		                    await loadDB();
		                }
		                navigateTo('orders');
		                showAlert('success', 'Payment Declined', 'Order has been declined.');
		            };

	            if(window.Swal) {
	                Swal.fire({
	                    icon: 'warning',
	                    title: 'Decline payment?',
	                    text: 'This order will not be sent to the kitchen.',
	                    width: 320,
	                    padding: '1rem',
	                    customClass: {
	                        popup: 'swal-compact'
	                    },
	                    showCancelButton: true,
	                    confirmButtonText: 'Decline',
	                    confirmButtonColor: '#dc2626',
	                    cancelButtonColor: '#5D4037'
	                }).then(result => {
	                    if(result.isConfirmed) runDecline();
	                });
	                return;
	            }

	            if(confirm('Decline this payment?')) runDecline();
	        }

		        function navigateTo(viewId) {
            currentView = viewId;
            document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
            const activeBtn = document.getElementById(`nav-${viewId}`);
            if(activeBtn) activeBtn.classList.add('active');
            
            const renderMap = {
                'pos': renderPOS,
                'orders': renderOrders
            };
            
	            document.getElementById('main-content').innerHTML = renderMap[viewId]();
	        }

        window.addEventListener('focus', () => {
            loadDB().then(() => navigateTo(currentView));
        });
	        loadDB()
	            .then(() => navigateTo(window.location.hash === '#orders' ? 'orders' : 'pos'))
	            .catch(() => {
	                showAlert('error', 'Database Error', 'Unable to load cashier POS data.');
	                navigateTo(window.location.hash === '#orders' ? 'orders' : 'pos');
	            });
    </script>
</body>
</html>
