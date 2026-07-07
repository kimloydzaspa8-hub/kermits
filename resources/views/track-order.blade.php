<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Order - Kermit's Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #5D4037;
            --surface: #fbfaf7;
            --line: #ded5cb;
            --muted: #6e625d;
        }
        body { min-height: 100vh; background: var(--surface); color: #111827; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
	        .customer-header { position: sticky; top: 0; z-index: 90; background: #fbfaf7; border-bottom: 1px solid #eee7df; box-shadow: 0 2px 10px rgba(75,58,50,0.08); }
	        .header-shell { width: min(100% - 2.5rem, 1280px); margin: 0 auto; padding: 1rem 0; display: flex; align-items: center; justify-content: space-between; gap: 1.25rem; }
	        .brand { display: inline-flex; align-items: center; gap: 14px; color: #4b3a32; font-weight: 900; }
	        .brand img { width: 48px; height: 48px; border-radius: 999px; border: 2px solid #4b3a32; object-fit: cover; flex-shrink: 0; }
	        .brand span { color: var(--primary); font-family: Georgia, 'Times New Roman', serif; font-size: clamp(1.35rem, 1.7vw, 1.75rem); line-height: 1.1; font-weight: 800; }
	        .back-link { min-height: 46px; display: inline-flex; align-items: center; justify-content: center; color: var(--primary); border: 1px solid var(--line); border-radius: 8px; padding: 10px 18px; font-weight: 700; background: white; transition: background-color 0.2s ease; }
        .back-link:hover { background: #f3eee8; }
        .page-shell { width: min(100% - 32px, 760px); margin: 0 auto; padding: 48px 0 56px; }
        .track-title { font-family: Georgia, 'Times New Roman', serif; color: #2f2f2f; font-size: clamp(1.9rem, 4vw, 2.6rem); font-weight: 800; text-align: center; margin-bottom: 10px; }
        .track-subtitle { color: var(--muted); font-size: 1.05rem; text-align: center; margin-bottom: 32px; }
        .track-card { background: white; border: 1px solid var(--line); border-radius: 16px; padding: 24px; box-shadow: 0 8px 18px rgba(75,58,50,0.08); }
        .track-form { display: grid; grid-template-columns: 1fr auto; gap: 12px; }
        .track-input { min-height: 56px; border: 1px solid var(--line); border-radius: 12px; padding: 0 18px; font-size: 1rem; outline: none; }
        .track-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(93,64,55,0.12); }
        .track-button { min-height: 56px; border-radius: 12px; background: #4b3a32; color: white; padding: 0 22px; font-size: 1rem; font-weight: 900; display: inline-flex; align-items: center; justify-content: center; gap: 10px; }
        .track-result { display: none; margin-top: 16px; text-align: left; color: #374151; font-weight: 700; }
        .track-result.show { display: block; }
        .tracking-card { overflow: hidden; border: 1px solid #e4ddd6; border-radius: 14px; background: white; box-shadow: 0 4px 10px rgba(75,58,50,0.08); }
        .tracking-card-head { background: #5b463c; color: white; padding: 16px 18px; display: flex; justify-content: space-between; gap: 14px; align-items: center; }
        .tracking-order-meta { color: #ddd3cc; font-size: 0.82rem; }
        .tracking-customer { color: white; font-size: 1rem; font-weight: 900; margin-top: 2px; }
        .tracking-status-pill { background: #fff7bf; color: #9a3f11; border-radius: 999px; padding: 6px 11px; font-size: 0.78rem; font-weight: 900; text-transform: uppercase; white-space: nowrap; }
        .tracking-card-body { padding: 18px; }
        .tracking-steps { display: grid; margin-bottom: 18px; }
        .tracking-step { position: relative; display: grid; grid-template-columns: 24px 1fr; gap: 10px; min-height: 38px; color: #6b625d; font-size: 0.95rem; font-weight: 700; }
        .tracking-step::before { content: ''; position: absolute; left: 11px; top: 18px; bottom: -18px; width: 3px; background: #e5ded8; }
        .tracking-step:last-child::before { display: none; }
        .tracking-dot { width: 11px; height: 11px; margin: 6px auto 0; border-radius: 999px; background: #ded8d2; border: 3px solid white; box-shadow: 0 0 0 1px #ded8d2; z-index: 1; }
        .tracking-step.done, .tracking-step.active { color: #111827; }
	        .tracking-step.done .tracking-dot { background: #10b981; box-shadow: 0 0 0 1px #10b981; }
	        .tracking-step.active .tracking-dot { background: #e56f51; box-shadow: 0 0 0 5px rgba(229,111,81,0.2); }
	        .tracking-step.active.waiting-pickup .tracking-dot { background: #10b981; box-shadow: 0 0 0 5px rgba(16,185,129,0.18); }
	        .tracking-step i { color: #10b981; width: 24px; text-align: center; margin-right: 8px; }
	        .tracking-step.active i { color: #e56f51; }
	        .tracking-step.active.waiting-pickup i { color: #10b981; }
        .tracking-info-box { background: #f2eee8; border-radius: 8px; padding: 12px 14px; margin-bottom: 12px; }
        .tracking-rider-main { display: flex; align-items: center; gap: 14px; }
        .tracking-rider-icon { width: 46px; height: 46px; border-radius: 999px; background: #e77758; color: white; display: inline-flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0; }
        .tracking-label { color: #6b625d; font-size: 0.8rem; font-weight: 900; text-transform: uppercase; }
        .tracking-items { border-top: 1px solid var(--line); margin-top: 14px; padding-top: 12px; }
        .tracking-item-row, .tracking-total-row { display: flex; justify-content: space-between; gap: 16px; margin-top: 6px; color: #111827; font-size: 0.95rem; }
        .tracking-total-row { border-top: 1px solid var(--line); padding-top: 10px; margin-top: 10px; font-size: 1.05rem; font-weight: 900; }
        .tracking-total-row span:last-child { color: #d66e53; }
	        @media (max-width: 640px) {
	            .header-shell { width: min(100% - 1.5rem, 1280px); }
	            .brand { gap: 12px; }
	            .brand img { width: 40px; height: 40px; }
	            .brand span { font-size: 1.05rem; }
	            .back-link { min-height: 40px; padding: 8px 12px; font-size: 0.9rem; }
	            .page-shell { width: min(100% - 1.5rem, 760px); padding: 28px 0 40px; }
	            .track-title { font-size: 1.85rem; line-height: 1.12; }
	            .track-subtitle { font-size: 0.95rem; margin-bottom: 20px; }
	            .track-card { padding: 16px; border-radius: 12px; }
	            .track-form { grid-template-columns: 1fr; }
	            .track-input, .track-button { min-height: 48px; border-radius: 10px; font-size: 0.95rem; }
	            .tracking-card-head { align-items: flex-start; flex-direction: column; }
	            .tracking-card-body { padding: 16px; }
	            .tracking-item-row, .tracking-total-row { gap: 10px; font-size: 0.9rem; }
	        }
    </style>
</head>
<body>
    <header class="customer-header">
        <nav class="header-shell">
            <a href="{{ route('home') }}" class="brand">
                <img src="{{ asset('kermit.jpg') }}" alt="Kermit's Restaurant">
                <span>Kermit's Restaurant</span>
            </a>
            <a href="{{ route('home') }}" class="back-link">Home</a>
        </nav>
    </header>

    <main class="page-shell">
        <h1 class="track-title">Track Your Order</h1>
        <p class="track-subtitle">Enter your order ID to see real-time order status</p>

        <form class="track-card" onsubmit="trackCustomerOrder(event)">
            <div class="track-form">
                <input id="track-order-id" class="track-input" type="text" placeholder="Enter Order ID (e.g. 25 or CM-20260527...)" autocomplete="off" autofocus>
                <button class="track-button" type="submit">
                    <i class="fas fa-search"></i>
                    <span>Track</span>
                </button>
            </div>
            <div id="track-order-result" class="track-result"></div>
        </form>
    </main>

    <script>
        const CASHIER_POS_DATA_URL = "{{ route('cashier.pos.data') }}";

        function escapeTrackHtml(value) {
            const div = document.createElement('div');
            div.textContent = value ?? '';
            return div.innerHTML;
        }

        function isPickupOrder(order) {
            return String(order.source || '').toLowerCase().includes('pick');
        }

        function orderTrackStatus(order) {
            const pickup = isPickupOrder(order);
            if(order.status === 'served' || order.status === 'completed') return pickup ? 'Completed' : 'Delivered';
            if(pickup && order.status === 'waiting') return 'Waiting';
            if(order.status === 'sending') return 'Delivering';
            if(order.status === 'ready') return 'Ready';
            if(order.status === 'declined') return 'Declined';
            return 'Preparing';
        }

        function trackingStepIndex(order) {
            if(order.status === 'served' || order.status === 'completed') return 3;
            if(isPickupOrder(order) && order.status === 'waiting') return 2;
            if(order.status === 'sending') return 2;
            if(order.status === 'ready') return 1;
            return 0;
        }

        function trackingStepsForOrder(order) {
            if(isPickupOrder(order)) {
                return [
                    ['fa-bell-concierge', 'Preparing your order'],
                    ['fa-box', 'Ready for pickup'],
                    ['fa-store', 'Waiting for pickup'],
                    ['fa-circle-check', 'Completed']
                ];
            }
            return [
                ['fa-bell-concierge', 'Preparing your order'],
                ['fa-box', 'Ready for pickup by rider'],
                ['fa-motorcycle', 'Rider is on the way'],
                ['fa-house', 'Delivered']
            ];
        }

	        function renderTrackingStep(index, current, icon, label, extraClass = '') {
	            const state = current > index ? 'done' : current === index ? 'active' : '';
	            return `
	                <div class="tracking-step ${state} ${extraClass}">
	                    <span class="tracking-dot"></span>
	                    <span><i class="fas ${icon}"></i>${label}</span>
	                </div>
	            `;
	        }

        function renderTrackingCard(order) {
            const currentStep = trackingStepIndex(order);
            const items = Array.isArray(order.items) ? order.items : [];
            const address = order.deliveryAddress || order.address || 'Delivery address not provided';
            const riderName = order.riderName || 'Waiting for rider';
            const pickup = isPickupOrder(order);
            const steps = trackingStepsForOrder(order);

            return `
                <div class="tracking-card">
                    <div class="tracking-card-head">
                        <div>
                            <div class="tracking-order-meta">Order #${escapeTrackHtml(order.queueNum || order.id || '-')}</div>
                            <div class="tracking-customer">${escapeTrackHtml(order.customerName || 'Customer')}</div>
                        </div>
                        <div class="tracking-status-pill">${escapeTrackHtml(orderTrackStatus(order))}</div>
                    </div>
                    <div class="tracking-card-body">
                        <div class="tracking-steps">
	                            ${steps.map((step, index) => renderTrackingStep(index, currentStep, step[0], step[1], pickup && order.status === 'waiting' && index === 2 ? 'waiting-pickup' : '')).join('')}
                        </div>

                        ${pickup ? '' : `
                            <div class="tracking-info-box">
                            <div class="tracking-rider-main">
                                <span class="tracking-rider-icon"><i class="fas fa-motorcycle"></i></span>
                                <div>
                                    <div>${escapeTrackHtml(riderName)}</div>
                                    <div class="font-normal" style="color:#6b625d;">Your delivery rider</div>
                                </div>
                            </div>
                        </div>`}

                        ${pickup ? '' : `<div class="tracking-info-box">
                            <div class="tracking-label"><i class="fas fa-location-dot" style="color:#e77758;"></i> Delivery Address</div>
                            <div class="font-normal mt-1">${escapeTrackHtml(address)}</div>
                        </div>`}

                        <div class="tracking-items">
                            <div class="tracking-label">Order Items</div>
                            ${items.map(item => `
                                <div class="tracking-item-row">
                                    <span>${Number(item.qty) || 1}x ${escapeTrackHtml(item.name)}</span>
                                    <strong>&#8369;${(Number(item.price || 0) * (Number(item.qty) || 1)).toLocaleString()}</strong>
                                </div>
                            `).join('')}
                            <div class="tracking-total-row">
                                <span>Total</span>
                                <span>&#8369;${(Number(order.total) || 0).toLocaleString()}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        async function trackCustomerOrder(event) {
            event.preventDefault();
            const input = document.getElementById('track-order-id');
            const result = document.getElementById('track-order-result');
            const query = input.value.trim().toLowerCase().replace(/^#/, '');

            if(!query) {
                result.className = 'track-result show';
                result.innerHTML = 'Please enter your order ID.';
                return;
            }

            result.className = 'track-result show';
            result.innerHTML = 'Checking order...';

            try {
                const response = await fetch(CASHIER_POS_DATA_URL, { headers: { 'Accept': 'application/json' } });
                if(!response.ok) throw new Error('Unable to load orders.');
                const data = await response.json();
                const order = (data.orders || []).find(item => {
                    return String(item.id || '').toLowerCase() === query
                        || String(item.queueNum || '').toLowerCase() === query;
                });

                result.innerHTML = order
                    ? renderTrackingCard(order)
                    : 'Order not found. Please check your order ID.';
            } catch(error) {
                result.innerHTML = 'Unable to track this order right now. Please try again.';
            }
        }
    </script>
</body>
</html>
