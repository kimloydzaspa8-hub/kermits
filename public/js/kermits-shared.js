window.KermitsStore = (() => {
    const DB_NAME = 'KermitsCafeDB';

    const defaults = {
        menu: [
            { id: 'foods-chicken-alfredo-pasta', name: 'Chicken Alfredo Pasta', price: 185, type: 'foods', img: 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?auto=format&fit=crop&w=700&q=80', stock: 50, desc: 'Creamy pasta with tender chicken and herbs.' },
            { id: 'foods-clubhouse-sandwich', name: 'Clubhouse Sandwich', price: 145, type: 'foods', img: 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=700&q=80', stock: 50, desc: 'Layered toast, egg, vegetables, cheese, and savory filling.' },
            { id: 'foods-fresh-garden-salad', name: 'Fresh Garden Salad', price: 110, type: 'foods', img: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=700&q=80', stock: 50, desc: 'Crisp greens with bright dressing and fresh toppings.' },
            { id: 'pasta-aligue-pasta', name: 'Aligue Pasta', price: 280, type: 'pasta', img: 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?auto=format&fit=crop&w=700&q=80', stock: 50, desc: 'Rich crab fat pasta.' },
            { id: 'pasta-baked-spaghetti', name: 'Baked Spaghetti', price: 260, type: 'pasta', img: 'https://images.unsplash.com/photo-1622973536968-3ead9e780960?auto=format&fit=crop&w=700&q=80', stock: 50, desc: 'Baked spaghetti with savory sauce.' },
            { id: 'pasta-spaghetti', name: 'Spaghetti', price: 130, type: 'pasta', img: 'https://images.unsplash.com/photo-1551892374-ecf8754cf8b0?auto=format&fit=crop&w=700&q=80', stock: 50, desc: 'Classic spaghetti pasta.' },
            { id: 'drinks-iced-spanish-latte', name: 'Iced Spanish Latte', price: 120, type: 'drinks', img: 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=700&q=80', stock: 50, desc: 'Chilled espresso with sweet, creamy milk.' },
            { id: 'drinks-caramel-frappe', name: 'Caramel Frappe', price: 135, type: 'drinks', img: 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?auto=format&fit=crop&w=700&q=80', stock: 50, desc: 'Blended coffee with caramel and whipped cream.' },
            { id: 'drinks-fruit-iced-tea', name: 'Fruit Iced Tea', price: 95, type: 'drinks', img: 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=700&q=80', stock: 50, desc: 'Refreshing tea with fruity notes and ice.' },
            { id: 'cake-almond-gateaux', name: 'Almond Gateaux', price: 900, type: 'cake', img: 'https://images.unsplash.com/photo-1535141192574-5d4897c12636?auto=format&fit=crop&w=700&q=80', stock: 10, desc: 'Available as whole cake or per slice.' },
            { id: 'cake-choco-overload', name: 'Choco Overload', price: 900, type: 'cake', img: 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=700&q=80', stock: 10, desc: 'Rich chocolate cake for celebrations.' },
            { id: 'cake-mango-graham', name: 'Mango Graham', price: 850, type: 'cake', img: 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?auto=format&fit=crop&w=700&q=80', stock: 10, desc: 'Sweet mango graham cake.' }
        ],
        inventory: [
            { name: 'Coffee Beans', unit: 'g', stock: 5000, reorder: 1000 },
            { name: 'Flour', unit: 'g', stock: 8000, reorder: 2000 },
            { name: 'Butter', unit: 'g', stock: 3000, reorder: 1000 },
            { name: 'Cream', unit: 'g', stock: 2000, reorder: 500 },
            { name: 'Beef', unit: 'g', stock: 1500, reorder: 1000 }
        ],
        orders: [],
        customOrders: [],
        waste: [],
        loyalty: {},
        queue: 0
    };

    const recipeByCategory = {
        foods: [{ name: 'Flour', qty: 80 }, { name: 'Butter', qty: 20 }],
        pasta: [{ name: 'Flour', qty: 90 }, { name: 'Cream', qty: 40 }],
        drinks: [{ name: 'Coffee Beans', qty: 20 }],
        coffee: [{ name: 'Coffee Beans', qty: 20 }],
        cake: [{ name: 'Flour', qty: 160 }, { name: 'Cream', qty: 100 }],
        meal: [{ name: 'Beef', qty: 100 }, { name: 'Flour', qty: 70 }]
    };

    function clone(value) {
        return JSON.parse(JSON.stringify(value));
    }

    function hasMenuItem(menu, item) {
        return menu.some((existing) => String(existing.id) === String(item.id)
            || (existing.name === item.name && (existing.type || 'foods') === (item.type || 'foods')));
    }

    function normalize(db) {
        const next = Object.assign(clone(defaults), db || {});
        next.orders = Array.isArray(next.orders) ? next.orders : [];
        next.customOrders = Array.isArray(next.customOrders) ? next.customOrders : [];
        next.waste = Array.isArray(next.waste) ? next.waste : [];
        next.loyalty = next.loyalty && typeof next.loyalty === 'object' ? next.loyalty : {};
        next.menu = Array.isArray(next.menu) && next.menu.length ? next.menu : clone(defaults.menu);
        const legacyDefaultNames = ['Island Brew Coffee', 'Bantayan Pastry', 'Seafood Cake', 'Beef Pastry Pie'];
        const savedMenuNames = next.menu.map((item) => item.name);
        const isLegacyDefaultMenu = next.menu.length === legacyDefaultNames.length
            && legacyDefaultNames.every((name) => savedMenuNames.includes(name));
        if (isLegacyDefaultMenu) {
            next.menu = clone(defaults.menu);
        }
        if (next.menuSeedVersion !== 'customer-menu-v1') {
            defaults.menu.forEach((item) => {
                if (!hasMenuItem(next.menu, item)) {
                    next.menu.push(clone(item));
                }
            });
            next.menuSeedVersion = 'customer-menu-v1';
        }
        next.inventory = Array.isArray(next.inventory) && next.inventory.length ? next.inventory : clone(defaults.inventory);

        next.inventory = next.inventory.map((item) => ({
            name: item.name || item.id || 'Ingredient',
            unit: item.unit || 'units',
            stock: Number(item.stock) || 0,
            reorder: Number(item.reorder ?? item.reorderPoint) || 0,
            reorderPoint: Number(item.reorderPoint ?? item.reorder) || 0
        }));

        defaults.inventory.forEach((item) => {
            if (!next.inventory.some((existing) => existing.name === item.name)) {
                next.inventory.push(clone(item));
            }
        });

        next.orders = next.orders.map((order) => ({
            ...order,
            items: Array.isArray(order.items) ? order.items : [],
            total: Number(order.total) || 0,
            queueNum: Number(order.queueNum) || null,
            paymentStatus: order.paymentStatus || 'paid',
            status: order.status || 'received',
            source: order.source || 'Cashier POS',
            time: order.time || new Date().toLocaleTimeString(),
            date: order.date || new Date().toLocaleDateString()
        }));

        next.queue = Math.max(
            Number(next.queue) || 0,
            next.orders.reduce((max, order) => Math.max(max, Number(order.queueNum) || 0), 0)
        );

        return next;
    }

    function load() {
        const saved = localStorage.getItem(DB_NAME);
        return normalize(saved ? JSON.parse(saved) : {});
    }

    function save(db) {
        localStorage.setItem(DB_NAME, JSON.stringify(normalize(db)));
    }

    function findInventory(db, name) {
        return db.inventory.find((item) => item.name === name || item.name.startsWith(`${name} (`));
    }

    function deductStock(db, items) {
        items.forEach((cartItem) => {
            const menuItem = db.menu.find((item) => String(item.id) === String(cartItem.id) || item.name === cartItem.name);
            const ingredients = menuItem?.ingredients || recipeByCategory[cartItem.category] || recipeByCategory[menuItem?.type] || [];
            ingredients.forEach((ingredient) => {
                const stockItem = findInventory(db, ingredient.name);
                if (stockItem) {
                    stockItem.stock = Math.max(0, Number(stockItem.stock) - (Number(ingredient.qty) * Number(cartItem.qty || 1)));
                }
            });
            if (menuItem && Number.isFinite(Number(menuItem.stock))) {
                menuItem.stock = Math.max(0, Number(menuItem.stock) - Number(cartItem.qty || 1));
            }
        });
    }

    function createOrder(db, options) {
        db.queue = (Number(db.queue) || 0) + 1;
        const items = options.items.map((item) => ({
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

        if (order.paymentStatus === 'paid') {
            deductStock(db, items);
        }

        db.orders.push(order);
        save(db);
        return order;
    }

    function markPaid(db, orderId) {
        const order = db.orders.find((item) => item.id === orderId);
        if (!order || order.paymentStatus === 'paid') return order;
        order.paymentStatus = 'paid';
        order.status = 'received';
        order.paidAt = new Date().toLocaleString();
        deductStock(db, order.items);
        save(db);
        return order;
    }

    function declineOrder(db, orderId) {
        const order = db.orders.find((item) => item.id === orderId);
        if (!order || order.paymentStatus === 'paid') return order;
        order.paymentStatus = 'declined';
        order.status = 'declined';
        order.declinedAt = new Date().toLocaleString();
        save(db);
        return order;
    }

    return { DB_NAME, normalize, load, save, createOrder, markPaid, declineOrder, deductStock };
})();
