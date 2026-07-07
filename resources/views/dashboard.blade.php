	        function getDashboardPaymentStatus(order) {
	            return order.paymentStatus === 'unpaid' ? 'pending' : (order.paymentStatus || 'paid');
	        }

	        function dashboardNeedsPayment(order) {
	            const paymentStatus = getDashboardPaymentStatus(order);
	            return paymentStatus === 'pending' && !['declined', 'completed', 'served'].includes(order.status);
	        }

		        function dashboardPaymentBadge(order) {
		            const status = getDashboardPaymentStatus(order);
		            const classes = {
		                paid: 'bg-green-100 text-green-700',
		                pending: 'bg-yellow-100 text-yellow-800',
		                declined: 'bg-red-100 text-red-700'
		            };
		            return `<span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold ${classes[status] || classes.pending}">${status.toUpperCase()}</span>`;
		        }

	        function dashboardOrderType(order) {
	            return escapeHtml(String(order.source || 'Cashier POS').replace('Customer Menu - ', '').replace(' - Cash', ''));
	        }

	        function dashboardActivityFulfillment(order) {
	            const source = String(order.source || '').toLowerCase();
	            if(source.includes('delivery')) return 'delivery';
	            if(source.includes('pick') || source.includes('take')) return 'pickup';
	            return 'dine';
	        }

	        function dashboardActivityType(order) {
	            const type = dashboardActivityFulfillment(order);
	            if(type === 'delivery') return '<span class="recent-activity-type delivery"><i class="fas fa-motorcycle"></i> delivery</span>';
	            if(type === 'pickup') return '<span class="recent-activity-type pickup"><i class="fas fa-store"></i> pickup</span>';
	            return '<span class="recent-activity-type dine"><i class="fas fa-utensils"></i> dine in</span>';
	        }

	        function dashboardActivityStatus(order) {
	            if(order.status === 'declined') return 'declined';
	            if(order.status === 'served' || order.status === 'completed') {
	                return dashboardActivityFulfillment(order) === 'delivery' ? 'delivered' : 'completed';
	            }
	            if(order.status === 'sending') return 'delivering';
	            if(order.status === 'ready') return 'ready';
	            if(order.status === 'waiting') return 'waiting';
	            return 'preparing';
	        }

	        function dashboardActivityCustomer(order) {
	            return escapeHtml(order.customerName || order.customer || 'Walk-in');
	        }

	        function renderRecentActivity() {
	            const recentOrders = [...(db.orders || [])]
	                .sort((a, b) => getSalesDate(b).getTime() - getSalesDate(a).getTime())
	                .slice(0, 8);

	            return `
	                <section class="recent-activity-card">
	                    <h3 class="recent-activity-title">Recent Activity</h3>
	                    <table class="recent-activity-table">
	                        <thead>
	                            <tr>
	                                <th>ID</th>
	                                <th>Customer</th>
	                                <th>Type</th>
	                                <th>Amount</th>
	                                <th>Status</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                            ${recentOrders.map(order => {
	                                const status = dashboardActivityStatus(order);
	                                return `
	                                    <tr>
	                                        <td class="recent-activity-id">#${escapeHtml(order.queueNum || order.id || '-')}</td>
	                                        <td>${dashboardActivityCustomer(order)}</td>
	                                        <td>${dashboardActivityType(order)}</td>
	                                        <td class="recent-activity-amount">&#8369;${(Number(order.total) || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
	                                        <td><span class="recent-activity-status ${status}">${escapeHtml(status.toUpperCase())}</span></td>
	                                    </tr>
	                                `;
	                            }).join('')}
	                            ${recentOrders.length === 0 ? '<tr><td class="text-gray-400" colspan="5">No recent activity yet.</td></tr>' : ''}
	                        </tbody>
	                    </table>
	                </section>
	            `;
	        }

	        function renderDashboardSalesCharts() {
	            return `
	                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
	                    <div class="card sales-chart">
	                        <h3 class="font-bold text-lg mb-4" style="color:var(--primary)">Daily Sales</h3>
	                        ${renderDailySalesLineChart()}
	                    </div>
	                    <div class="card sales-chart">
	                        <h3 class="font-bold text-lg mb-4" style="color:var(--primary)">Monthly Sales</h3>
	                        ${renderMonthlySalesBarChart()}
	                    </div>
	                </div>
	            `;
	        }

			        function dashboardPaymentAction(order) {
		            const status = getDashboardPaymentStatus(order);
		            if(status === 'pending') return `<a href="${CASHIER_POS_URL}#orders" class="text-yellow-700 text-sm font-bold">Need Payment</a>`;
		            if(status === 'declined') return '<span class="text-red-500 text-sm font-bold">Declined</span>';
		            return '<span class="text-gray-400 text-sm">Done</span>';
		        }

	        function renderDashboard() {
	            const paidOrderList = db.orders.filter(o => getDashboardPaymentStatus(o) === 'paid' || ['delivered', 'completed', 'served'].includes(o.status));
	            const totalSales = paidOrderList.reduce((sum, o) => sum + (Number(o.total) || 0), 0);
			            const totalOrders = db.orders.length;
		            const todayKey = getLocalDateKey(new Date());
		            const todayOrders = db.orders.filter(order => {
		                const orderDate = getDashboardOrderDate(order);
		                return orderDate && getLocalDateKey(orderDate) === todayKey;
		            }).length;
			            const activeOrders = db.orders.filter(order => !['served', 'completed', 'declined'].includes(order.status)).length;
				            const deliveryOrders = db.orders.filter(order => String(order.source || '').toLowerCase().includes('delivery')).length;
		            return `
		                <h2 class="text-2xl font-bold mb-4" style="color:var(--primary)">Admin Dashboard</h2>
				                <div class="dashboard-stat-grid">
				                    <div class="dashboard-stat-card revenue"><div class="dashboard-stat-label">Total Revenue</div><div class="dashboard-stat-value">&#8369;${totalSales}</div></div>
				                    <div class="dashboard-stat-card orders"><div class="dashboard-stat-label">Total Orders</div><div class="dashboard-stat-value">${totalOrders}</div></div>
				                    <div class="dashboard-stat-card active"><div class="dashboard-stat-label">Active Orders</div><div class="dashboard-stat-value">${activeOrders}</div></div>
				                    <div class="dashboard-stat-card delivery"><div class="dashboard-stat-label">Delivery Orders</div><div class="dashboard-stat-value">${deliveryOrders}</div></div>
				                </div>
				                ${renderDashboardSalesCharts()}
				                ${renderRecentActivity()}
			            `;
            return `
                <h2 class="text-2xl font-bold mb-4" style="color:var(--primary)">Admin Dashboard</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="card text-center">
                        <div class="text-gray-500 mb-2"><i class="fas fa-coins text-3xl"></i></div>
                        <div class="text-3xl font-bold" style="color:var(--primary)">?${totalSales}</div>
                        <div class="text-gray-500">Total Revenue</div>
                    </div>
                    <div class="card text-center">
                        <div class="text-gray-500 mb-2"><i class="fas fa-receipt text-3xl"></i></div>
                        <div class="text-3xl font-bold" style="color:var(--primary)">${totalOrders}</div>
                        <div class="text-gray-500">Total Orders</div>
                    </div>
                    <div class="card text-center">
                        <div class="text-gray-500 mb-2"><i class="fas fa-trash text-3xl text-red-400"></i></div>
                        <div class="text-3xl font-bold text-red-500">?${db.waste.reduce((s,w) => s+w.loss, 0)}</div>
                        <div class="text-gray-500">Shrinkage Loss</div>
                    </div>
                </div>
                <div class="card">
                    <h3 class="font-bold mb-4">Sales Heatmap (Simulated)</h3>
                    <div class="grid grid-cols-6 gap-2 text-center text-xs">
                        ${['Mon','Tue','Wed','Thu','Fri','Sat','Sun'].map(d => `<div class="font-bold">${d}</div>`).join('').repeat(1)}
                        ${Array(42).fill(0).map(() => {
                            const intensity = Math.floor(Math.random() * 3);
                            const colors = ['bg-green-100', 'bg-green-300', 'bg-green-500'];
                            return `<div class="h-8 rounded ${colors[intensity]}"></div>`;
                        }).join('')}
                    </div>
                    <div class="flex gap-4 mt-3 text-xs text-gray-500">
                        <div class="flex items-center gap-1"><div class="w-3 h-3 rounded bg-green-100"></div> Slow</div>
                        <div class="flex items-center gap-1"><div class="w-3 h-3 rounded bg-green-300"></div> Moderate</div>
                        <div class="flex items-center gap-1"><div class="w-3 h-3 rounded bg-green-500"></div> Peak</div>
                    </div>
                </div>
            `;
	        }

		        function renderLowStockAlerts() {
		            const lowStockItems = (db.inventory || [])
		                .filter(item => Number(item.stock) <= Number(item.reorder))
		                .sort((a, b) => (Number(a.stock) - Number(a.reorder)) - (Number(b.stock) - Number(b.reorder)));

		            return `
		                <div class="card">
		                    <h3 class="font-bold text-lg mb-4" style="color:var(--primary)">Low Stock Alerts</h3>
		                    ${lowStockItems.length === 0 ? `
		                        <div class="h-28 flex items-center justify-center text-base text-gray-500">All items well stocked</div>
		                    ` : `
		                        <div class="space-y-2">
		                            ${lowStockItems.map(item => `
		                                <div class="flex items-center justify-between gap-3 rounded-lg bg-red-50 border border-red-100 px-3 py-2">
		                                    <div>
		                                        <div class="font-bold text-red-700 leading-tight">${escapeHtml(item.name)}</div>
		                                        <div class="text-xs text-gray-500">Reorder at ${Number(item.reorder) || 0} ${escapeHtml(item.unit || 'pcs')}</div>
		                                    </div>
		                                    <div class="text-right">
		                                        <div class="font-bold text-red-600 leading-tight">${Number(item.stock) || 0}</div>
		                                        <div class="text-xs text-gray-500">${escapeHtml(item.unit || 'pcs')} left</div>
		                                    </div>
		                                </div>
		                            `).join('')}
		                        </div>
		                    `}
		                </div>
		            `;
		        }

		        function renderRecentOrders() {
		            const recentOrders = [...(db.orders || [])]
		                .sort((a, b) => getSalesDate(b).getTime() - getSalesDate(a).getTime())
		                .slice(0, 3);

		            return `
		                <div class="card">
		                    <h3 class="font-bold text-lg mb-4" style="color:var(--primary)">Recent Orders</h3>
		                    ${recentOrders.length === 0 ? `
		                        <div class="h-28 flex items-center justify-center text-base text-gray-500">No recent orders</div>
		                    ` : `
		                        <div class="space-y-2">
		                            ${recentOrders.map(order => {
		                                const paymentStatus = getDashboardPaymentStatus(order);
		                                const statusClass = paymentStatus === 'paid' ? 'bg-green-100 text-green-700' : paymentStatus === 'declined' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-800';
		                                return `
		                                    <div class="flex items-center justify-between gap-3 rounded-lg bg-gray-50 border border-gray-100 px-3 py-2">
		                                        <div>
		                                            <div class="font-bold leading-tight" style="color:var(--primary)">${escapeHtml(order.id || order.order_number || 'Order')}</div>
		                                            <div class="text-xs text-gray-500">${dashboardOrderType(order)} · ${escapeHtml(getSalesDate(order).toLocaleDateString())}</div>
		                                        </div>
		                                        <div class="text-right">
		                                            <div class="font-bold text-gray-900 leading-tight">₱${Number(order.total) || 0}</div>
		                                            <span class="inline-flex mt-1 px-2 py-0.5 rounded-full text-xs font-bold ${statusClass}">${escapeHtml(paymentStatus.toUpperCase())}</span>
		                                        </div>
		                                    </div>
		                                `;
		                            }).join('')}
		                        </div>
		                    `}
		                </div>
		            `;
		        }

			        function getLocalDateKey(date) {
		            return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
		        }

		        function getDashboardOrderDate(order) {
		            const rawDate = order.createdAt || order.date;
		            if(!rawDate) return null;

		            let parsed = new Date(rawDate);
		            if(Number.isNaN(parsed.getTime()) && order.date) {
		                const parts = String(order.date).match(/^(\d{1,2})[/-](\d{1,2})[/-](\d{2,4})$/);
		                if(parts) {
		                    const first = Number(parts[1]);
		                    const second = Number(parts[2]);
		                    const year = Number(parts[3].length === 2 ? `20${parts[3]}` : parts[3]);
		                    parsed = new Date(year, first > 12 ? second - 1 : first - 1, first > 12 ? first : second);
		                }
		            }

		            return Number.isNaN(parsed.getTime()) ? null : parsed;
		        }

		        function parseDashboardDate(value) {
		            if(!value) return null;
		            let parsed = new Date(value);
		            if(!Number.isNaN(parsed.getTime())) return parsed;

		            const parts = String(value).match(/^(\d{1,2})[/-](\d{1,2})[/-](\d{2,4})(?:,\s*(.*))?$/);
		            if(parts) {
		                const first = Number(parts[1]);
		                const second = Number(parts[2]);
		                const year = Number(parts[3].length === 2 ? `20${parts[3]}` : parts[3]);
		                parsed = new Date(year, first > 12 ? second - 1 : first - 1, first > 12 ? first : second);
		                if(!Number.isNaN(parsed.getTime())) return parsed;
		            }

		            return null;
		        }

		        function getSalesDate(order) {
		            return parseDashboardDate(order.paidAt)
		                || parseDashboardDate(order.completedAt)
		                || parseDashboardDate(order.createdAt)
		                || parseDashboardDate(order.date)
		                || parseDashboardDate(order.time)
		                || new Date();
		        }

		        function isDashboardPaidOrder(order) {
		            return getDashboardPaymentStatus(order) === 'paid' && !['pending', 'declined', 'awaiting_payment'].includes(order.status);
		        }

		        function getDashboardSalesOrders() {
		            return (db.orders || []).filter(order => isDashboardPaidOrder(order) && Number(order.total) > 0);
		        }

		        function getDashboardLatestSalesDate() {
		            const salesOrders = getDashboardSalesOrders();
		            if(salesOrders.length === 0) return new Date();
		            return salesOrders
		                .map(order => getSalesDate(order))
		                .sort((a, b) => b.getTime() - a.getTime())[0];
		        }

				        function getDailySales() {
				            const endDate = getDashboardLatestSalesDate();
				            endDate.setHours(0, 0, 0, 0);
				            const startDate = new Date(endDate);
				            startDate.setDate(endDate.getDate() - 6);
				            const buckets = Array.from({ length: 7 }, (_, index) => {
				                const date = new Date(startDate);
				                date.setDate(startDate.getDate() + index);
				                return {
				                    key: getLocalDateKey(date),
				                    label: date.toLocaleDateString(undefined, { weekday: 'short' }),
				                    total: 0
				                };
				            });
				            const bucketMap = Object.fromEntries(buckets.map(item => [item.key, item]));
							getDashboardSalesOrders().forEach(order => {
								const sd = getSalesDate(order);
								if(!sd) return;
								const local = new Date(sd.getFullYear(), sd.getMonth(), sd.getDate());
								const key = getLocalDateKey(local);
								if(bucketMap[key]) bucketMap[key].total += Number(order.total) || 0;
							});
				            return buckets;
				        }

			        function getMonthlySales() {
			            return getMonthSales(11, 6);
			        }

					        function getMonthlySalesCategoryMix() {
						            const now = getDashboardLatestSalesDate();
					            const colors = ['#49C4AA', '#42B3E3', '#9A78EA', '#F6B23D', '#F35E7F', '#49C4BD'];
					            const buckets = Array.from({ length: 6 }, (_, index) => {
					                const date = new Date(now.getFullYear(), now.getMonth() - 5 + index, 1);
					                return {
					                    key: `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`,
					                    label: date.toLocaleDateString(undefined, { month: 'short' }),
					                    total: 0,
					                    color: colors[index]
					                };
					            });
					            const bucketMap = Object.fromEntries(buckets.map(item => [item.key, item]));

						            getDashboardSalesOrders().forEach(order => {
						                const date = getSalesDate(order);
						                const key = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
						                if(bucketMap[key]) bucketMap[key].total += Number(order.total) || 0;
						            });

			            return buckets;
			        }

		        function getWeekdaySales() {
		            const weekdays = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
		            const weekdayIndexes = [6, 0, 1, 2, 3, 4, 5];
		            const buckets = weekdays.map((label, index) => ({
		                key: weekdayIndexes[index],
		                label: label.substring(0, 3),
		                fullLabel: label,
		                total: 0,
		                count: 0
		            }));
			            const bucketMap = Object.fromEntries(buckets.map(item => [item.key, item]));
		            
					db.orders.forEach(order => {
						if(!isDashboardPaidOrder(order)) return;
						const date = getSalesDate(order);
						if(!date) return;
						const local = new Date(date.getFullYear(), date.getMonth(), date.getDate());
						const dayOfWeek = local.getDay();
						if(bucketMap[dayOfWeek]) {
							bucketMap[dayOfWeek].total += Number(order.total) || 0;
							bucketMap[dayOfWeek].count += 1;
						}
					});
		            
		            return buckets;
		        }

	        function getMonthSales(startMonth = 11, monthCount = 6) {
	            const now = new Date();
	            const cycleStartYear = now.getMonth() >= startMonth ? now.getFullYear() : now.getFullYear() - 1;
	            const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	            
	            const buckets = [];
	            for (let i = 0; i < monthCount; i++) {
	                const monthIndex = (startMonth + i) % 12;
	                const year = cycleStartYear + Math.floor((startMonth + i) / 12);
	                buckets.push({
	                    key: `${year}-${String(monthIndex + 1).padStart(2, '0')}`,
	                    label: months[monthIndex].substring(0, 3),
	                    fullLabel: months[monthIndex],
	                    total: 0,
	                    count: 0
	                });
	            }
	            
	            const bucketMap = Object.fromEntries(buckets.map(item => [item.key, item]));
	            db.orders.forEach(order => {
	                if(!isDashboardPaidOrder(order)) return;
	                const date = getSalesDate(order);
	                const key = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
	                if(bucketMap[key]) {
	                    bucketMap[key].total += Number(order.total) || 0;
	                    bucketMap[key].count += 1;
	                }
	            });
	            
	            return buckets;
	        }

				        function renderDailySalesLineChart() {
				            {
				            const data = getDailySales();
			            const maxValue = Math.max(...data.map(item => item.total), 0);
			            const scaleMax = Math.max(maxValue, 1);
			            const width = 700;
			            const height = 260;
			            const padding = { top: 8, right: 24, bottom: 56, left: 48 };
			            const chartWidth = width - padding.left - padding.right;
			            const chartHeight = height - padding.top - padding.bottom;
			            const points = data.map((item, index) => {
			                const x = padding.left + (chartWidth / Math.max(data.length - 1, 1)) * index;
			                const y = padding.top + chartHeight - ((item.total / scaleMax) * chartHeight);
			                return { x, y, item };
			            });
			            const path = points.map((point, index) => {
			                if(index === 0) return `M ${point.x.toFixed(1)} ${point.y.toFixed(1)}`;
			                const previous = points[index - 1];
			                const controlX = (point.x - previous.x) / 2;
			                return `C ${(previous.x + controlX).toFixed(1)} ${previous.y.toFixed(1)}, ${(point.x - controlX).toFixed(1)} ${point.y.toFixed(1)}, ${point.x.toFixed(1)} ${point.y.toFixed(1)}`;
			            }).join(' ');
			            const areaPath = `${path} L ${points[points.length - 1].x.toFixed(1)} ${(padding.top + chartHeight).toFixed(1)} L ${points[0].x.toFixed(1)} ${(padding.top + chartHeight).toFixed(1)} Z`;
			            const gridLines = Array.from({ length: 11 }, (_, index) => {
			                const value = 100 - (index * 10);
			                const y = padding.top + (chartHeight / 10) * index;
			                return `
			                    <line x1="${padding.left}" y1="${y.toFixed(1)}" x2="${padding.left + chartWidth}" y2="${y.toFixed(1)}" stroke="#C4CCD8" stroke-width="1" />
			                    <text x="${padding.left - 14}" y="${(y + 4).toFixed(1)}" text-anchor="end" font-size="13" fill="#46649A">${value}</text>
				            `;
			            }).join('');

			            return `
			                <svg class="sales-line-chart" viewBox="0 0 ${width} ${height}" role="img" aria-label="Daily sales line chart">
			                    ${gridLines}
			                    <line x1="${padding.left}" y1="${padding.top}" x2="${padding.left}" y2="${padding.top + chartHeight}" stroke="#D8DCE3" />
			                    <path d="${areaPath}" fill="rgba(18, 185, 129, 0.10)" />
			                    <path d="${path}" fill="none" stroke="#10B981" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
			                    ${points.map(point => `
			                        <circle cx="${point.x.toFixed(1)}" cy="${point.y.toFixed(1)}" r="7" fill="#49C4BD" stroke="#111827" stroke-width="4">
			                            <title>${escapeHtml(point.item.label)}: ${Number(point.item.total).toFixed(0)}</title>
			                        </circle>
			                        <text x="${point.x.toFixed(1)}" y="${height - 20}" transform="rotate(-42 ${point.x.toFixed(1)} ${height - 20})" text-anchor="end" font-size="13" fill="#46649A">${escapeHtml(point.item.label)}</text>
			                    `).join('')}
			                </svg>
				            `;
				            }
		            const data = getDailySales();
		            const maxValue = Math.max(...data.map(item => item.total), 0);
		            const scaleMax = Math.max(maxValue, 1);
		            const width = 700;
		            const height = 220;
		            const padding = { top: 18, right: 24, bottom: 42, left: 56 };
	            const chartWidth = width - padding.left - padding.right;
	            const chartHeight = height - padding.top - padding.bottom;
	            const gap = 18;
	            const barWidth = (chartWidth - gap * (data.length - 1)) / data.length;
	            const bars = data.map((item, index) => {
		                const valueHeight = (item.total / scaleMax) * chartHeight;
	                const x = padding.left + index * (barWidth + gap);
	                const y = padding.top + chartHeight - valueHeight;
	                return { x, y, height: valueHeight, item };
	            });

	            return `
	                <svg class="sales-line-chart" viewBox="0 0 ${width} ${height}" role="img" aria-label="Daily sales bar graph">
	                    <line x1="${padding.left}" y1="${padding.top}" x2="${padding.left}" y2="${padding.top + chartHeight}" stroke="#E5E7EB" />
	                    <line x1="${padding.left}" y1="${padding.top + chartHeight}" x2="${padding.left + chartWidth}" y2="${padding.top + chartHeight}" stroke="#E5E7EB" />
	                    <line x1="${padding.left}" y1="${padding.top + chartHeight / 2}" x2="${padding.left + chartWidth}" y2="${padding.top + chartHeight / 2}" stroke="#F3F4F6" stroke-dasharray="5 5" />
	                    ${bars.map(bar => `
	                        <rect x="${bar.x.toFixed(1)}" y="${bar.y.toFixed(1)}" width="${barWidth.toFixed(1)}" height="${bar.height.toFixed(1)}" rx="6" fill="#5D4037" />
		                        <text x="${(bar.x + barWidth / 2).toFixed(1)}" y="${height - 14}" text-anchor="middle" font-size="13" fill="#6B7280">${bar.item.label}</text>
	                        <text x="${(bar.x + barWidth / 2).toFixed(1)}" y="${Math.max(14, bar.y - 10).toFixed(1)}" text-anchor="middle" font-size="12" font-weight="700" fill="#5D4037">${bar.item.total ? `₱${bar.item.total}` : ''}</text>
	                    `).join('')}
	                    <text x="8" y="${padding.top + 10}" font-size="13" fill="#6B7280">₱${maxValue}</text>
	                    <text x="8" y="${padding.top + chartHeight}" font-size="13" fill="#6B7280">₱0</text>
	                </svg>
	            `;
	        }

				        function renderMonthlySalesBarChart() {
				            const data = getMonthlySalesCategoryMix();
					            const maxValue = Math.max(...data.map(item => item.total), 0);
					            const scaleMax = Math.max(maxValue, 1);
					            const width = 700;
					            const height = 260;
					            const padding = { top: 10, right: 24, bottom: 54, left: 48 };
					            const chartWidth = width - padding.left - padding.right;
					            const chartHeight = height - padding.top - padding.bottom;
					            const gap = 14;
					            const barWidth = (chartWidth - gap * (data.length - 1)) / data.length;
				            const bars = data.map((item, index) => {
				                const valueHeight = (item.total / scaleMax) * chartHeight;
				                const x = padding.left + index * (barWidth + gap);
				                const y = padding.top + chartHeight - valueHeight;
				                return { x, y, height: valueHeight, item };
				            });

					            const gridLines = Array.from({ length: 11 }, (_, index) => {
					                const value = 100 - (index * 10);
					                const y = padding.top + (chartHeight / 10) * index;
					                return `
					                    <line x1="${padding.left}" y1="${y.toFixed(1)}" x2="${padding.left + chartWidth}" y2="${y.toFixed(1)}" stroke="#C4CCD8" stroke-width="1" />
					                    <text x="${padding.left - 12}" y="${(y + 4).toFixed(1)}" text-anchor="end" font-size="13" fill="#46649A">${value}</text>
					                `;
					            }).join('');

					            return `
					                <svg class="sales-line-chart" viewBox="0 0 ${width} ${height}" role="img" aria-label="Monthly sales bar graph">
					                    ${gridLines}
					                    <line x1="${padding.left}" y1="${padding.top}" x2="${padding.left}" y2="${padding.top + chartHeight}" stroke="#D8DCE3" />
					                    ${bars.map(bar => `
					                        <rect x="${bar.x.toFixed(1)}" y="${bar.y.toFixed(1)}" width="${barWidth.toFixed(1)}" height="${bar.height.toFixed(1)}" rx="12" fill="${bar.item.color}" opacity="0.9">
					                            <title>${escapeHtml(bar.item.label)}: ₱${Number(bar.item.total).toFixed(0)}</title>
					                        </rect>
					                        <text x="${(bar.x + barWidth / 2).toFixed(1)}" y="${height - 18}" transform="rotate(-22 ${(bar.x + barWidth / 2).toFixed(1)} ${height - 18})" text-anchor="end" font-size="13" fill="#46649A">${escapeHtml(bar.item.label)}</text>
					                    `).join('')}
					                </svg>
					            `;
				        }

				        function renderMonthlySalesDonutChart() {
			            {
			            const data = getMonthlySalesCategoryMix();
				            const visibleData = data.filter(item => item.total > 0);
				            const total = visibleData.reduce((sum, item) => sum + item.total, 0);
				            if(total <= 0) return '<div class="chart-empty">No monthly sales yet.</div>';
			            const width = 420;
			            const height = 260;
			            const centerX = 178;
			            const centerY = 130;
				            const radius = 112;
			            let currentAngle = -90;
			            const polar = (radius, angle) => {
			                const radians = (angle - 90) * Math.PI / 180;
			                return {
			                    x: centerX + radius * Math.cos(radians),
			                    y: centerY + radius * Math.sin(radians)
			                };
			            };
				            const slicePath = (startAngle, endAngle) => {
				                const start = polar(radius, startAngle);
				                const end = polar(radius, endAngle);
				                const largeArc = endAngle - startAngle <= 180 ? 0 : 1;
				                return [
				                    `M ${centerX} ${centerY}`,
				                    `L ${start.x.toFixed(2)} ${start.y.toFixed(2)}`,
				                    `A ${radius} ${radius} 0 ${largeArc} 1 ${end.x.toFixed(2)} ${end.y.toFixed(2)}`,
				                    'Z'
				                ].join(' ');
				            };
				            const slices = visibleData.map(item => {
				                const startAngle = currentAngle;
				                const endAngle = currentAngle + (item.total / total) * 360;
				                currentAngle = endAngle;
					                return { ...item, d: slicePath(startAngle, endAngle) };
				            });
				            const onlySlice = visibleData.length === 1 ? visibleData[0] : null;

				            return `
				                <div class="sales-donut-wrap">
					                    <svg class="sales-donut-chart" viewBox="0 0 ${width} ${height}" role="img" aria-label="Monthly sales pie chart">
					                        ${onlySlice ? `
					                            <circle cx="${centerX}" cy="${centerY}" r="${radius}" fill="${onlySlice.color}" stroke="#111827" stroke-width="3" />
					                        ` : slices.map(slice => `
				                            <path d="${slice.d}" fill="${slice.color}" stroke="#111827" stroke-width="3">
				                                <title>${escapeHtml(slice.label)}: ${Number(slice.total).toFixed(0)}</title>
				                            </path>
				                        `).join('')}
				                    </svg>
			                    <div class="sales-donut-legend">
			                        ${data.map(item => `
			                            <div class="flex items-center gap-2">
			                                <span style="background:${item.color}"></span>
			                                <span>${escapeHtml(item.label)}</span>
			                            </div>
			                        `).join('')}
			                    </div>
			                </div>
			            `;
			            }
			            const data = getMonthlySales();
		            const maxValue = Math.max(...data.map(item => item.total), 0);
		            const scaleMax = Math.max(maxValue, 1);

		            const width = 700;
		            const height = 220;
		            const padding = { top: 18, right: 24, bottom: 42, left: 56 };
	            const chartWidth = width - padding.left - padding.right;
	            const chartHeight = height - padding.top - padding.bottom;
	            const points = data.map((item, index) => {
	                const x = padding.left + (chartWidth / Math.max(data.length - 1, 1)) * index;
		                const y = padding.top + chartHeight - ((item.total / scaleMax) * chartHeight);
	                return `${x.toFixed(1)},${y.toFixed(1)}`;
	            }).join(' ');
	            const areaPoints = `${padding.left},${padding.top + chartHeight} ${points} ${padding.left + chartWidth},${padding.top + chartHeight}`;

	            return `
	                <svg class="sales-line-chart" viewBox="0 0 ${width} ${height}" role="img" aria-label="Monthly sales line chart">
	                    <line x1="${padding.left}" y1="${padding.top}" x2="${padding.left}" y2="${padding.top + chartHeight}" stroke="#E5E7EB" />
	                    <line x1="${padding.left}" y1="${padding.top + chartHeight}" x2="${padding.left + chartWidth}" y2="${padding.top + chartHeight}" stroke="#E5E7EB" />
	                    <polygon points="${areaPoints}" fill="rgba(93,64,55,0.10)" />
	                    <polyline points="${points}" fill="none" stroke="#5D4037" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
	                    ${data.map((item, index) => {
	                        const x = padding.left + (chartWidth / Math.max(data.length - 1, 1)) * index;
		                        const y = padding.top + chartHeight - ((item.total / scaleMax) * chartHeight);
	                        return `
	                            <circle cx="${x.toFixed(1)}" cy="${y.toFixed(1)}" r="5" fill="#5D4037" />
		                            <text x="${x.toFixed(1)}" y="${height - 14}" text-anchor="middle" font-size="13" fill="#6B7280">${item.label}</text>
	                        `;
	                    }).join('')}
	                    <text x="8" y="${padding.top + 10}" font-size="13" fill="#6B7280">₱${maxValue}</text>
	                    <text x="8" y="${padding.top + chartHeight}" font-size="13" fill="#6B7280">₱0</text>
	                </svg>
	            `;
	        }

	        function renderWeekdaySalesBarChart() {
	            const data = getWeekdaySales();
	            const maxValue = Math.max(...data.map(item => item.total), 0);
	            const width = 700;
	            const height = 220;
	            const padding = { top: 18, right: 24, bottom: 42, left: 56 };
	            const chartWidth = width - padding.left - padding.right;
	            const chartHeight = height - padding.top - padding.bottom;
	            const gap = 12;
	            const barWidth = (chartWidth - gap * (data.length - 1)) / data.length;
	            const bars = data.map((item, index) => {
	                const valueHeight = maxValue > 0 ? (item.total / maxValue) * chartHeight : 0;
	                const x = padding.left + index * (barWidth + gap);
	                const y = padding.top + chartHeight - valueHeight;
	                return { x, y, height: valueHeight, item };
	            });

	            return `
	                <svg class="sales-line-chart" viewBox="0 0 ${width} ${height}" role="img" aria-label="Sales by day of week">
	                    <line x1="${padding.left}" y1="${padding.top}" x2="${padding.left}" y2="${padding.top + chartHeight}" stroke="#E5E7EB" />
	                    <line x1="${padding.left}" y1="${padding.top + chartHeight}" x2="${padding.left + chartWidth}" y2="${padding.top + chartHeight}" stroke="#E5E7EB" />
	                    <line x1="${padding.left}" y1="${padding.top + chartHeight / 2}" x2="${padding.left + chartWidth}" y2="${padding.top + chartHeight / 2}" stroke="#F3F4F6" stroke-dasharray="5 5" />
	                    ${bars.map(bar => `
	                        <rect x="${bar.x.toFixed(1)}" y="${bar.y.toFixed(1)}" width="${barWidth.toFixed(1)}" height="${bar.height.toFixed(1)}" rx="6" fill="#8D6E63" />
	                        <text x="${(bar.x + barWidth / 2).toFixed(1)}" y="${height - 14}" text-anchor="middle" font-size="12" fill="#6B7280">${bar.item.label}</text>
	                        <text x="${(bar.x + barWidth / 2).toFixed(1)}" y="${Math.max(14, bar.y - 10).toFixed(1)}" text-anchor="middle" font-size="11" font-weight="700" fill="#5D4037">${bar.item.total ? `₱${bar.item.total}` : ''}</text>
	                    `).join('')}
	                    <text x="8" y="${padding.top + 10}" font-size="13" fill="#6B7280">₱${maxValue}</text>
	                    <text x="8" y="${padding.top + chartHeight}" font-size="13" fill="#6B7280">₱0</text>
	                </svg>
	            `;
	        }

	        function renderMonthCycleSalesLineChart() {
	            const data = getMonthSales(11, 6);
	            const maxValue = Math.max(...data.map(item => item.total), 0);
	            if(maxValue <= 0) return '<div class="chart-empty">No sales data for Dec-May period.</div>';

	            const width = 700;
	            const height = 220;
	            const padding = { top: 18, right: 24, bottom: 42, left: 56 };
	            const chartWidth = width - padding.left - padding.right;
	            const chartHeight = height - padding.top - padding.bottom;
	            const points = data.map((item, index) => {
	                const x = padding.left + (chartWidth / Math.max(data.length - 1, 1)) * index;
	                const y = padding.top + chartHeight - ((item.total / maxValue) * chartHeight);
	                return `${x.toFixed(1)},${y.toFixed(1)}`;
	            }).join(' ');
	            const areaPoints = `${padding.left},${padding.top + chartHeight} ${points} ${padding.left + chartWidth},${padding.top + chartHeight}`;

	            return `
	                <svg class="sales-line-chart" viewBox="0 0 ${width} ${height}" role="img" aria-label="Dec-May sales cycle">
	                    <line x1="${padding.left}" y1="${padding.top}" x2="${padding.left}" y2="${padding.top + chartHeight}" stroke="#E5E7EB" />
	                    <line x1="${padding.left}" y1="${padding.top + chartHeight}" x2="${padding.left + chartWidth}" y2="${padding.top + chartHeight}" stroke="#E5E7EB" />
	                    <polygon points="${areaPoints}" fill="rgba(93,64,55,0.10)" />
	                    <polyline points="${points}" fill="none" stroke="#5D4037" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
	                    ${data.map((item, index) => {
	                        const x = padding.left + (chartWidth / Math.max(data.length - 1, 1)) * index;
	                        const y = padding.top + chartHeight - ((item.total / maxValue) * chartHeight);
	                        return `
	                            <circle cx="${x.toFixed(1)}" cy="${y.toFixed(1)}" r="5" fill="#5D4037" />
	                            <text x="${x.toFixed(1)}" y="${height - 14}" text-anchor="middle" font-size="13" fill="#6B7280">${item.label}</text>
	                        `;
	                    }).join('')}
	                    <text x="8" y="${padding.top + 10}" font-size="13" fill="#6B7280">₱${maxValue}</text>
	                    <text x="8" y="${padding.top + chartHeight}" font-size="13" fill="#6B7280">₱0</text>
	                </svg>
	            `;
	        }

	        function renderDailySalesLineChart() {
	            const data = getDailySales();
	            const maxValue = Math.max(...data.map(item => item.total), 0);
	            const scaleMax = Math.max(maxValue, 1);
	            const width = 700;
	            const height = 260;
		            const padding = { top: 10, right: 34, bottom: 58, left: 62 };
	            const chartWidth = width - padding.left - padding.right;
	            const chartHeight = height - padding.top - padding.bottom;
	            const points = data.map((item, index) => {
	                const x = padding.left + (chartWidth / Math.max(data.length - 1, 1)) * index;
	                const y = padding.top + chartHeight - ((item.total / scaleMax) * chartHeight);
	                return { x, y, item };
	            });
	            const path = points.map((point, index) => `${index === 0 ? 'M' : 'L'} ${point.x.toFixed(1)} ${point.y.toFixed(1)}`).join(' ');
	            const baseline = padding.top + chartHeight;
	            const areaPath = `${path} L ${points[points.length - 1].x.toFixed(1)} ${baseline.toFixed(1)} L ${points[0].x.toFixed(1)} ${baseline.toFixed(1)} Z`;
	            const gridLines = Array.from({ length: 6 }, (_, index) => {
	                const value = scaleMax - ((scaleMax / 5) * index);
	                const y = padding.top + (chartHeight / 5) * index;
	                return `
		                    <line class="sales-grid-line" style="animation-delay:${(index * 0.04).toFixed(2)}s" x1="${padding.left}" y1="${y.toFixed(1)}" x2="${padding.left + chartWidth}" y2="${y.toFixed(1)}" stroke="#CBD5E1" stroke-width="1" />
	                    <text x="${padding.left - 12}" y="${(y + 4).toFixed(1)}" text-anchor="end" font-size="12" fill="#46649A">${Math.round(value)}</text>
	                `;
	            }).join('');

	            return `
	                <svg class="sales-line-chart" viewBox="0 0 ${width} ${height}" role="img" aria-label="Daily sales line chart">
	                    ${gridLines}
	                    <line x1="${padding.left}" y1="${padding.top}" x2="${padding.left}" y2="${baseline}" stroke="#CBD5E1" />
		                    <path class="sales-area-fill" d="${areaPath}" fill="rgba(16,185,129,0.12)" />
		                    <path class="sales-trend-line" pathLength="1" d="${path}" fill="none" stroke="#10B981" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
		                    ${points.map((point, index) => `
		                        <circle class="sales-chart-point" style="animation-delay:${(0.45 + index * 0.08).toFixed(2)}s" cx="${point.x.toFixed(1)}" cy="${point.y.toFixed(1)}" r="7" fill="#49C4BD" stroke="#111827" stroke-width="4">
	                            <title>${escapeHtml(point.item.label)}: ₱${Number(point.item.total).toFixed(0)}</title>
	                        </circle>
		                        <text x="${point.x.toFixed(1)}" y="${height - 18}" transform="rotate(-22 ${point.x.toFixed(1)} ${height - 18})" text-anchor="end" font-size="12" fill="#46649A">${escapeHtml(point.item.label)}</text>
	                    `).join('')}
	                </svg>
	            `;
	        }

	        function renderMonthlySalesBarChart() {
	            const data = getMonthlySalesCategoryMix();
	            const maxValue = Math.max(...data.map(item => item.total), 0);
	            const scaleMax = Math.max(maxValue, 1);
	            const width = 700;
	            const height = 260;
	            const padding = { top: 10, right: 24, bottom: 54, left: 48 };
	            const chartWidth = width - padding.left - padding.right;
	            const chartHeight = height - padding.top - padding.bottom;
	            const gap = 16;
	            const barWidth = (chartWidth - gap * (data.length - 1)) / data.length;
	            const gridLines = Array.from({ length: 6 }, (_, index) => {
	                const value = scaleMax - ((scaleMax / 5) * index);
	                const y = padding.top + (chartHeight / 5) * index;
	                return `
		                    <line class="sales-grid-line" style="animation-delay:${(index * 0.04).toFixed(2)}s" x1="${padding.left}" y1="${y.toFixed(1)}" x2="${padding.left + chartWidth}" y2="${y.toFixed(1)}" stroke="#CBD5E1" stroke-width="1" />
	                    <text x="${padding.left - 12}" y="${(y + 4).toFixed(1)}" text-anchor="end" font-size="12" fill="#46649A">${Math.round(value)}</text>
	                `;
	            }).join('');
	            const bars = data.map((item, index) => {
	                const valueHeight = (item.total / scaleMax) * chartHeight;
	                const x = padding.left + index * (barWidth + gap);
	                const y = padding.top + chartHeight - valueHeight;
	                return { x, y, height: valueHeight, item };
	            });

	            return `
	                <svg class="sales-line-chart" viewBox="0 0 ${width} ${height}" role="img" aria-label="Monthly sales bar graph">
	                    ${gridLines}
	                    <line x1="${padding.left}" y1="${padding.top}" x2="${padding.left}" y2="${padding.top + chartHeight}" stroke="#CBD5E1" />
		                    ${bars.map((bar, index) => `
		                        <rect class="sales-chart-bar" style="animation-delay:${(0.18 + index * 0.08).toFixed(2)}s" x="${bar.x.toFixed(1)}" y="${bar.y.toFixed(1)}" width="${barWidth.toFixed(1)}" height="${bar.height.toFixed(1)}" rx="12" fill="#F35E7F" opacity="0.9">
	                            <title>${escapeHtml(bar.item.label)}: ₱${Number(bar.item.total).toFixed(0)}</title>
	                        </rect>
	                        <text x="${(bar.x + barWidth / 2).toFixed(1)}" y="${height - 18}" transform="rotate(-22 ${(bar.x + barWidth / 2).toFixed(1)} ${height - 18})" text-anchor="end" font-size="12" fill="#46649A">${escapeHtml(bar.item.label)}</text>
	                    `).join('')}
	                </svg>
	            `;
	        }
