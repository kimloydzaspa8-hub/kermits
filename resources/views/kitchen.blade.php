<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen - Kermit's Restaurant</title>
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
        html, body { height: 100%; overflow: hidden; }
        body { background-color: var(--bg); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
	        .btn-pos { background-color: var(--primary); color: white; border-radius: 8px; padding: 15px; font-size: 1.1rem; font-weight: bold; }
	        .btn-pos:active { background-color: var(--accent); }
        .card { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 20px; }
	        #app-shell { height: 100vh; height: 100dvh; min-height: 0; overflow: hidden; }
	        #main-content { min-width: 0; min-height: 0; }
	        .nav-btn { min-height: 48px; border-left: 4px solid transparent; border-radius: 8px; font-size: 1rem; transition: background-color 0.18s ease, color 0.18s ease, border-color 0.18s ease; }
	        .nav-btn.active { border-left-color: var(--primary); background-color: var(--secondary); color: var(--primary); }
	        .sidebar { width: 260px; height: 100vh; height: 100dvh; min-height: 0; overflow: hidden; flex-shrink: 0; }
	        .sidebar-brand { min-height: 92px; padding: 20px; gap: 12px; display: flex; align-items: center; }
	        .sidebar-brand span { display: none; }
	        .sidebar-logo { width: 52px; height: 52px; object-fit: cover; border-radius: 999px; border: 2px solid var(--primary); flex-shrink: 0; }
	        .sidebar-title { color: var(--primary); font-size: 1.25rem; line-height: 1.2; font-weight: 800; }
	        .sidebar-subtitle { color: #6b7280; font-size: 0.875rem; line-height: 1.25; }
	        .kitchen-board { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 24px; align-items: start; }
	        .kitchen-column { min-width: 0; }
	        .kitchen-card-head { gap: 12px; }
	        .kitchen-card-head h4 { min-width: 0; overflow-wrap: anywhere; }
	        .kitchen-card-head span { white-space: nowrap; flex-shrink: 0; }
	        .kitchen-order-items { overflow-wrap: anywhere; }
        @media (max-width: 767px) {
            .sidebar { width: 100%; height: auto; max-height: 72px; overflow-x: auto; overflow-y: hidden; }
            #nav-bar { min-width: max-content; }
            .nav-btn { min-width: 84px; min-height: 56px; justify-content: center; flex-direction: column; gap: 4px; padding: 7px 8px; flex: 1 0 auto; font-size: 0.75rem; line-height: 1.1; text-align: center; }
            .nav-btn i { width: auto !important; font-size: 1rem; }
            .nav-btn { border-left: 0; border-bottom: 4px solid transparent; }
            .nav-btn.active { border-left: 0; border-bottom-color: var(--primary); }
            #main-content { padding: 16px 12px 24px !important; }
            .card { padding: 16px; border-radius: 10px; }
            .btn-pos { min-height: 42px; padding: 10px 12px; font-size: 0.9rem; }
            .kitchen-board { grid-template-columns: 1fr; gap: 16px; }
            .kitchen-page-title { font-size: 1.35rem; line-height: 1.2; margin-bottom: 16px; }
            .kitchen-column > h3 { font-size: 1rem; }
        }
        @media (min-width: 768px) and (max-width: 1180px) {
            .kitchen-board { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body class="h-screen overflow-hidden">
    <script>
        if (sessionStorage.getItem('kermitsPortalAccess') !== 'kitchen') {
            window.location.replace("{{ route('home') }}");
        }
    </script>
    <div id="app-shell" class="flex flex-col md:flex-row h-full">
        <aside class="sidebar bg-white shadow z-10 flex md:flex-col">
	            <div class="sidebar-brand hidden md:flex border-b">
                <span class="text-2xl">🐸</span>
	                <img src="{{ asset('kermit.jpg') }}" alt="Kermit's Restaurant" class="sidebar-logo">
	                <div>
	                    <h2 class="sidebar-title">Kitchen</h2>
	                    <p class="sidebar-subtitle">Kermit's Restaurant</p>
	                </div>
            </div>
            <div id="nav-bar" class="flex md:flex-col w-full p-2 md:p-3 gap-2">
                <button id="nav-kitchen" class="nav-btn px-4 py-3 rounded flex items-center gap-3 text-gray-600 hover:bg-gray-100 active text-left" onclick="navigateTo('kitchen')">
                    <i class="fas fa-fire-burner w-5 text-center"></i> <span>Kitchen</span>
                </button>
                <button id="nav-inventory" class="nav-btn px-4 py-3 rounded flex items-center gap-3 text-gray-600 hover:bg-gray-100 text-left" onclick="navigateTo('inventory')">
                    <i class="fas fa-boxes-stacked w-5 text-center"></i> <span>Inventory</span>
                </button>
                <button id="nav-waste" class="nav-btn px-4 py-3 rounded flex items-center gap-3 text-gray-600 hover:bg-gray-100 text-left" onclick="navigateTo('waste')">
                    <i class="fas fa-trash w-5 text-center"></i> <span>Waste</span>
                </button>
            </div>
            <div class="hidden md:block mt-auto p-4 border-t">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-red-500 hover:text-red-700 px-4 py-3 rounded hover:bg-red-50"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </aside>

        <main id="main-content" class="flex-1 overflow-y-auto p-4 md:p-6"></main>
    </div>

    <script>
        const DB_NAME = 'KermitsCafeDB';
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
            waste: []
        };

        let currentView = 'kitchen';

        function saveDB() { localStorage.setItem(DB_NAME, JSON.stringify(db)); }
        function loadDB() {
            const saved = localStorage.getItem(DB_NAME);
            if(saved) db = JSON.parse(saved);
        }

        function renderKitchen() {
	            const activeOrders = db.orders.filter(o => o.paymentStatus === 'paid' && !['completed', 'declined'].includes(o.status));
            return `
	                <h2 class="kitchen-page-title text-2xl font-bold mb-4" style="color:var(--primary)">Kitchen Display System</h2>
	                <div class="kitchen-board">
	                    <div class="kitchen-column">
                        <h3 class="font-bold text-lg mb-3 border-b-2 pb-2 border-blue-500 text-blue-600">✅ Received</h3>
                        ${activeOrders.filter(o => o.status==='received').map(o => orderCard(o, 'preparing')).join('')}
                    </div>
	                    <div class="kitchen-column">
                        <h3 class="font-bold text-lg mb-3 border-b-2 pb-2 border-yellow-500 text-yellow-600">👨‍🍳 Preparing</h3>
                        ${activeOrders.filter(o => o.status==='preparing').map(o => orderCard(o, 'ready')).join('')}
                    </div>
	                    <div class="kitchen-column">
                        <h3 class="font-bold text-lg mb-3 border-b-2 pb-2 border-green-500 text-green-600">🧁 Ready for Pickup</h3>
                        ${activeOrders.filter(o => o.status==='ready').map(o => orderCard(o, 'completed')).join('')}
                    </div>
                </div>
            `;
        }

	        function orderCard(order, nextStatus) {
	            return `
	                <div class="card mb-4 border-l-4" style="border-left-color: var(--primary)">
		                    <div class="kitchen-card-head flex justify-between items-center mb-2">
	                        <h4 class="font-bold text-lg">${order.id}</h4>
	                        <span class="text-sm text-gray-500">${order.time}</span>
	                    </div>
		                    <ul class="kitchen-order-items mb-4 text-gray-700">
	                        ${order.items.map(i => `<li>${i.qty}x ${i.name}</li>`).join('')}
	                    </ul>
                    <button onclick="updateOrderStatus('${order.id}', '${nextStatus}')" class="w-full btn-pos text-sm py-2">
                        Move to ${nextStatus.charAt(0).toUpperCase() + nextStatus.slice(1)}
                    </button>
                </div>
	            `;
	        }

	        function updateOrderStatus(orderId, status) {
            const order = db.orders.find(o => o.id === orderId);
            if(order) order.status = status;
            saveDB();
            navigateTo(currentView);
        }

        function getReorderPoint(item) {
            return Number(item.reorder ?? item.reorderPoint ?? 0);
        }

        function getInventoryUnit(item) {
            const match = item.name.match(/\(([^)]+)\)/);
            return item.unit || (match ? match[1] : 'units');
        }

        function getStockPercent(item) {
            const reorderPoint = getReorderPoint(item);
            const target = Math.max(reorderPoint * 3, item.stock, 1);
            return Math.min(100, Math.round((item.stock / target) * 100));
        }

        function renderInventory() {
            const lowStockItems = db.inventory.filter(i => i.stock <= getReorderPoint(i));
            return `
                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3 mb-5">
                    <div>
                        <h2 class="text-2xl font-bold" style="color:var(--primary)">Ingredient Stock</h2>
                        <p class="text-gray-500">Track kitchen ingredients, low-stock alerts, and quick restocks.</p>
                    </div>
                    <div class="card py-3 px-4">
                        <p class="text-xs uppercase font-bold text-gray-500">Low Stock</p>
                        <p class="text-2xl font-bold" style="color:${lowStockItems.length ? 'var(--danger)' : 'var(--primary)'}">${lowStockItems.length}</p>
                    </div>
                </div>
                ${lowStockItems.length > 0 ? `<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert"><i class="fas fa-bell mr-2"></i> Low Stock Alert! Please reorder highlighted items.</div>` : ''}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    ${db.inventory.map((item, index) => {
                        const unit = getInventoryUnit(item);
                        const reorderPoint = getReorderPoint(item);
                        const isLow = item.stock <= reorderPoint;
                        return `
                        <div class="card ${isLow ? 'border-2 border-red-500 bg-red-50' : ''}">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="font-bold text-lg">${item.name}</h3>
                                <span class="text-sm text-gray-500">${unit}</span>
                            </div>
                            <div class="text-3xl font-bold mb-3" style="color: ${isLow ? 'var(--danger)' : 'var(--primary)'}">
                                ${item.stock} <span class="text-sm font-normal text-gray-500">${unit} left</span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded overflow-hidden mb-3">
                                <div class="${isLow ? 'bg-red-500' : 'bg-green-500'} h-full" style="width:${getStockPercent(item)}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mb-4">
                                <span>Reorder at ${reorderPoint} ${unit}</span>
                                <span>${isLow ? 'Needs restock' : 'In stock'}</span>
                            </div>
                            <div class="flex gap-2">
                                <!-- Read-only for kitchen: restocking handled by admin -->
                            </div>
                        </div>
                    `}).join('')}
                </div>
            `;
        }

        function renderWaste() {
            return `
                <h2 class="text-2xl font-bold mb-4" style="color:var(--primary)">Waste Management</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="card">
                        <h3 class="font-bold mb-4">Log Waste</h3>
                        <select id="waste-item" class="w-full border p-2 rounded mb-3">
                            ${db.menu.map(m => `<option value="${m.name}">${m.name}</option>`).join('')}
                        </select>
                        <input type="number" id="waste-qty" placeholder="Quantity" class="w-full border p-2 rounded mb-3">
                        <select id="waste-reason" class="w-full border p-2 rounded mb-3">
                            <option>Damaged</option><option>Expired</option><option>Prep Mistake</option>
                        </select>
                        <button onclick="logWaste()" class="w-full btn-pos">Log Waste</button>
                    </div>
                    <div class="card">
                        <h3 class="font-bold mb-4">Waste Log & Shrinkage</h3>
                        <div class="overflow-y-auto max-h-96">
                            ${db.waste.map(w => `
                                <div class="flex justify-between items-center border-b py-2">
                                    <div><b>${w.qty}x ${w.item}</b><br><span class="text-xs text-gray-500">${w.reason}</span></div>
                                    <div class="text-red-500 font-bold">-₱${w.loss}</div>
                                </div>
                            `).join('')}
                            ${db.waste.length === 0 ? '<p class="text-gray-400">No waste recorded.</p>' : ''}
                        </div>
                    </div>
                </div>
            `;
        }

        function logWaste() {
            const item = document.getElementById('waste-item').value;
            const qty = parseInt(document.getElementById('waste-qty').value);
            const reason = document.getElementById('waste-reason').value;
            const menuItem = db.menu.find(m => m.name === item);
            if(menuItem && qty > 0) {
                db.waste.push({ item, qty, reason, loss: menuItem.price * qty, date: new Date().toLocaleDateString() });
                saveDB();
                navigateTo('waste');
            }
        }

        function navigateTo(viewId) {
            currentView = viewId;
            document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
            const activeBtn = document.getElementById(`nav-${viewId}`);
            if(activeBtn) activeBtn.classList.add('active');

            const renderMap = {
                'kitchen': renderKitchen,
                'inventory': renderInventory,
                'waste': renderWaste
            };

            document.getElementById('main-content').innerHTML = renderMap[viewId]();
        }

        loadDB();
        navigateTo('kitchen');
    </script>
</body>
</html>
