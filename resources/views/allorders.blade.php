		        let allOrdersFilter = 'all';

		        function allOrdersFulfillment(order) {
		            const source = String(order.source || '').toLowerCase();
		            if(source.includes('delivery')) return 'delivery';
		            if(source.includes('pick')) return 'pickup';
		            if(source.includes('take')) return 'pickup';
		            return 'dine';
		        }

		        function allOrdersType(order) {
		            const type = allOrdersFulfillment(order);
		            if(type === 'delivery') return '<span class="all-orders-type delivery"><i class="fas fa-motorcycle"></i> delivery</span>';
		            if(type === 'pickup') return '<span class="all-orders-type pickup"><i class="fas fa-store"></i> pickup</span>';
		            return '<span class="all-orders-type dine"><i class="fas fa-utensils"></i> dine in</span>';
		        }

		        function allOrdersWorkflowStatus(order) {
		            if(order.status === 'declined') return 'declined';
		            if(order.status === 'served' || order.status === 'completed') return allOrdersFulfillment(order) === 'delivery' ? 'delivered' : 'completed';
		            if(order.status === 'sending') return 'delivering';
		            if(order.status === 'ready') return 'ready';
		            return 'preparing';
		        }

		        function allOrdersStatusClass(order) {
		            return allOrdersWorkflowStatus(order);
		        }

		        function allOrdersStatusLabel(order) {
		            return allOrdersWorkflowStatus(order).replace('_', ' ').toUpperCase();
		        }

			        function allOrdersRider(order) {
			            if(allOrdersFulfillment(order) !== 'delivery') return 'N/A';
			            if(order.riderName) return escapeHtml(order.riderName);
			            if(order.status === 'sending') return 'Rider assigned';
			            return 'Waiting for rider';
			        }

			        function allOrdersArea(order) {
			            if(allOrdersFulfillment(order) !== 'delivery') return 'N/A';
			            const address = String(order.deliveryAddress || order.address || '');
			            const areas = ['Santa Fe', 'Madridejos', 'Bantayan'];
			            const area = areas.find(item => address.toLowerCase().includes(item.toLowerCase()));
			            return escapeHtml(area || 'Location');
			        }

		        function allOrdersCustomer(order) {
		            return escapeHtml(order.customerName || order.customer || 'Walk-in');
		        }

		        function allOrdersItems(order) {
		            const items = Array.isArray(order.items) ? order.items : [];
		            if(!items.length) return 'No items';
		            return items.map(item => `${Number(item.qty) || 1}x ${escapeHtml(item.name)}`).join(', ');
		        }

		        function renderAllOrders() {
		            const orders = [...db.orders].filter(order => {
		                if(allOrdersFilter === 'all') return true;
		                if(allOrdersFilter === 'delivery' || allOrdersFilter === 'pickup') return allOrdersFulfillment(order) === allOrdersFilter;
		                return allOrdersWorkflowStatus(order) === allOrdersFilter;
		            }).sort((a, b) => {
		                const queueDiff = (Number(b.queueNum) || 0) - (Number(a.queueNum) || 0);
		                return queueDiff || String(b.id || '').localeCompare(String(a.id || ''));
		            });
		            const totalOrders = Array.isArray(db.orders) ? db.orders.length : 0;

		            return `
		                <div class="all-orders-card">
		                    <div class="all-orders-header">
		                        <h2>All Orders</h2>
		                        <select class="all-orders-filter" onchange="allOrdersFilter = this.value; navigateTo('allorders')">
		                            <option value="all" ${allOrdersFilter === 'all' ? 'selected' : ''}>All Orders</option>
		                            <option value="delivery" ${allOrdersFilter === 'delivery' ? 'selected' : ''}>Delivery</option>
		                            <option value="pickup" ${allOrdersFilter === 'pickup' ? 'selected' : ''}>Pickup</option>
		                            <option value="preparing" ${allOrdersFilter === 'preparing' ? 'selected' : ''}>Preparing</option>
		                            <option value="ready" ${allOrdersFilter === 'ready' ? 'selected' : ''}>Ready</option>
		                            <option value="delivering" ${allOrdersFilter === 'delivering' ? 'selected' : ''}>Delivering</option>
		                            <option value="delivered" ${allOrdersFilter === 'delivered' ? 'selected' : ''}>Delivered</option>
		                        </select>
		                    </div>
		                    <div class="all-orders-table-wrap">
		                        <table class="all-orders-table">
		                            <thead>
		                                <tr>
		                                    <th>ID</th>
		                                    <th>Time</th>
			                                    <th>Customer</th>
			                                    <th>Type</th>
			                                    <th>Area</th>
			                                    <th>Rider</th>
			                                    <th>Total</th>
		                                    <th>Status</th>
		                                </tr>
		                            </thead>
		                            <tbody>
		                                ${orders.map(order => `
		                                    <tr>
		                                        <td class="order-id-cell">#${escapeHtml(order.queueNum || order.id || '-')}</td>
		                                        <td>${escapeHtml(order.time || '')}</td>
			                                        <td>${allOrdersCustomer(order)}</td>
			                                        <td>${allOrdersType(order)}</td>
			                                        <td>${allOrdersArea(order)}</td>
			                                        <td>${allOrdersRider(order)}</td>
		                                        <td class="total-cell">&#8369;${(Number(order.total) || 0).toLocaleString()}</td>
		                                        <td><span class="order-status-pill ${allOrdersStatusClass(order)}">${allOrdersStatusLabel(order)}</span></td>
		                                    </tr>
		                                `).join('')}
			                                ${orders.length === 0 ? '<tr><td class="text-gray-400" colspan="8">No orders found.</td></tr>' : ''}
		                            </tbody>
		                        </table>
		                    </div>
		                    <div class="all-orders-total">Total ${totalOrders}</div>
		                </div>
		            `;
		        }
