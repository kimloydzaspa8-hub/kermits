	        function renderOrders() {
            const orders = [...db.orders].sort((a, b) => (b.queueNum || 0) - (a.queueNum || 0));
            const activeOrders = orders.filter(order => order.paymentStatus === 'paid' && !['completed', 'served', 'declined'].includes(order.status));
            const columns = [
                { key: 'received', title: 'Received', icon: 'fa-square-check', color: '#2563eb', nextStatus: 'preparing', button: 'Move to Preparing' },
                { key: 'preparing', title: 'Preparing', icon: 'fa-hourglass-half', color: '#d97706', nextStatus: 'ready', button: 'Move to Ready' },
                { key: 'ready', title: 'Ready for Pickup', icon: 'fa-cake-candles', color: '#059669', nextStatus: 'completed', button: 'Complete Order' }
            ];
            return `
	                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3 mb-4">
	                    <div>
	                        <h2 class="text-xl font-bold" style="color:var(--primary)">Order Board</h2>
	                        <p class="text-sm text-gray-500">Move orders from received to preparing, then ready for pickup.</p>
	                    </div>
	                    <div class="card h-9 py-1 px-3 flex items-center gap-2">
	                        <span class="text-xs uppercase font-bold text-gray-500">Active</span>
	                        <span class="text-base font-bold leading-none" style="color:var(--primary)">${activeOrders.length}</span>
	                    </div>
	                </div>
	                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    ${columns.map(column => {
                        const columnOrders = activeOrders.filter(order => getOrderBoardStatus(order) === column.key);
                        return `
	                            <section>
	                                <div class="flex items-center gap-2 pb-3 mb-3 border-b-2" style="border-color:${column.color}; color:${column.color}">
	                                    <i class="fas ${column.icon} text-base"></i>
	                                    <h3 class="text-xl font-bold">${column.title}</h3>
	                                </div>
	                                <div class="space-y-3">
                                    ${columnOrders.map(order => renderOrderBoardCard(order, column)).join('')}
                                    ${columnOrders.length === 0 ? '<div class="text-gray-400 text-center py-10">No orders here.</div>' : ''}
                                </div>
                            </section>
                        `;
                    }).join('')}
                </div>
	            `;
	        }

	        function getOrderBoardStatus(order) {
            if(order.status === 'ready_for_pickup') return 'ready';
            if(order.status === 'awaiting_payment' || order.status === 'declined') return 'received';
            return ['received', 'preparing', 'ready'].includes(order.status) ? order.status : 'received';
        }

        function renderOrderBoardCard(order, column) {
            const orderTime = order.time || order.createdAt || '';
            const items = Array.isArray(order.items) ? order.items : [];
            return `
	                <article class="bg-white rounded-lg shadow border-l-4 p-4" style="border-left-color:var(--primary)">
	                    <div class="flex items-start justify-between gap-3 mb-4">
	                        <h4 class="text-xl font-bold text-black break-all">${escapeHtml(order.id || `Order ${order.queueNum || ''}`)}</h4>
	                        <span class="text-sm text-gray-500 whitespace-nowrap">${escapeHtml(orderTime)}</span>
	                    </div>
	                    <div class="text-base text-gray-800 leading-relaxed mb-4">
	                        ${items.map(item => `<div>${Number(item.qty) || 1}x ${escapeHtml(item.name)}</div>`).join('')}
	                        ${items.length === 0 ? '<div class="text-gray-400">No items listed.</div>' : ''}
	                    </div>
	                    <button onclick="moveOrderStatus('${escapeHtml(order.id)}', '${column.nextStatus}')" class="w-full rounded-lg py-3 text-base font-bold text-white" style="background:var(--primary)">
	                        ${column.button}
	                    </button>
                </article>
            `;
        }

        async function moveOrderStatus(orderId, nextStatus) {
            const order = db.orders.find(item => String(item.id) === String(orderId));
            if(!order) return;
            order.status = nextStatus;
            await saveDB();
            navigateTo('orders');
        }
