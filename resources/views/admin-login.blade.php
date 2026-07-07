<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Kermit's Restaurant</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #5D4037;  
            --secondary: #D7CCC8;
            --accent: #8D6E63;
            --bg: #EFEBE9;
        }
        html, body { min-height: 100%; overflow-x: hidden; }
        body { background-color: var(--bg); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
	        .card { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 20px; }
	        .login-panel { max-width: 420px; width: 100%; }
	        .login-input { border: 1px solid #D7CCC8; border-radius: 8px; padding: 12px 14px; width: 100%; }
	        .login-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(93,64,55,0.14); outline: none; }
	        .password-field { position: relative; }
	        .password-field .login-input { padding-right: 48px; }
	        .password-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; color: #6b625d; border-radius: 8px; }
	        .password-toggle:hover { background: #f3eee8; color: var(--primary); }
	        .btn-pos { background-color: var(--primary); color: white; border-radius: 8px; padding: 15px; font-size: 1.1rem; font-weight: bold; }
	        .btn-pos:active { background-color: var(--accent); }
	        .back-link { min-height: 58px; display: flex; align-items: center; justify-content: center; width: 100%; color: #111827; border: 1px solid #D7CCC8; border-radius: 12px; font-size: 1.05rem; font-weight: 700; margin-top: 18px; }
	        .back-link:hover { background: #f3eee8; color: #4E342E; }
        .swal-compact { font-size: 0.9rem; }
        .swal-compact .swal2-title { font-size: 1.25rem; padding-top: 0.25rem; }
        .swal-compact .swal2-html-container { font-size: 0.95rem; margin: 0.5rem 0 0; }
        .swal-compact .swal2-actions { margin-top: 0.75rem; }
        .swal-compact .swal2-confirm { padding: 0.55rem 1.25rem; }
        @media (max-width: 520px) {
            main { align-items: flex-start !important; padding: 18px 12px !important; }
            .card { padding: 16px; border-radius: 10px; }
            .login-panel { max-width: none; }
            .login-panel > .mb-6 { gap: 12px; margin-bottom: 18px !important; }
            .login-panel img { width: 52px !important; height: 52px !important; }
            .login-panel h1 { font-size: 1.55rem !important; line-height: 1.15; }
            .login-panel p { font-size: 0.85rem; }
            .login-input { min-height: 46px; padding: 10px 12px; font-size: 0.95rem; }
            .btn-pos, .back-link { min-height: 46px; padding: 10px 12px; font-size: 0.95rem; border-radius: 10px; }
            .back-link { margin-top: 12px; }
        }
    </style>
</head>
<body>
    <main class="min-h-screen flex items-center justify-center p-4">
        <form class="card login-panel" onsubmit="loginAdmin(event)">
            <div class="mb-6 flex items-center gap-4">
                <img src="{{ asset('kermit.jpg') }}" alt="Kermit's Restaurant" class="w-16 h-16 object-cover rounded-full border-2 flex-shrink-0" style="border-color: var(--primary);">
                <div>
                    <h1 class="text-3xl font-bold" style="color: var(--primary);">Kermit's Admin</h1>
                    <p class="text-sm text-gray-500">Sign in to manage the restaurant.</p>
                </div>
            </div>
            <label for="admin-email" class="block text-sm font-bold text-gray-700 mb-2">Email</label>
            <input id="admin-email" type="email" class="login-input mb-4" autocomplete="username" required autofocus>
            <label for="admin-password" class="block text-sm font-bold text-gray-700 mb-2">Password</label>
            <div class="password-field mb-3">
                <input id="admin-password" type="password" class="login-input" placeholder="Password" autocomplete="current-password" required>
                <button type="button" class="password-toggle" onclick="togglePassword('admin-password', this)" aria-label="Show password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <label for="staff-login-role" class="block text-sm font-bold text-gray-700 mb-2">Role</label>
            <select id="staff-login-role" class="login-input mb-4" onchange="changeStaffLoginRole(this.value)">
		                <option value="cashier">Cashier Login</option>
		                <option value="admin" selected>Admin Login</option>
		                <option value="rider">Rider Login</option>
            </select>
            <p id="admin-login-error" class="hidden text-sm text-red-600 mb-4">Wrong admin email or password.</p>
            <button type="submit" class="btn-pos w-full">
                <i class="fas fa-lock mr-2"></i> Log In
            </button>
            <a href="{{ route('home') }}" class="back-link">
                <span>Back to Website</span>
            </a>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const ADMIN_DASHBOARD_URL = "{{ route('admin.dashboard') }}";
	        const ADMIN_LOGIN_URL = "{{ route('admin.login.submit') }}";
	        const CASHIER_LOGIN_PAGE_URL = "{{ route('cashier.login') }}";
	        const RIDER_LOGIN_PAGE_URL = "{{ route('rider.login') }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";

	        function changeStaffLoginRole(role) {
	            if(role === 'cashier') window.location.href = CASHIER_LOGIN_PAGE_URL;
	            if(role === 'rider') window.location.href = RIDER_LOGIN_PAGE_URL;
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

	        function togglePassword(inputId, button) {
	            const input = document.getElementById(inputId);
	            const icon = button.querySelector('i');
	            const showPassword = input.type === 'password';
	            input.type = showPassword ? 'text' : 'password';
	            icon.className = showPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
	            button.setAttribute('aria-label', showPassword ? 'Hide password' : 'Show password');
	        }
	
	        async function loginAdmin(event) {
	            event.preventDefault();
	            const emailInput = document.getElementById('admin-email');
	            const passwordInput = document.getElementById('admin-password');
	            const email = emailInput.value.trim().toLowerCase();
	            const password = passwordInput.value;
	            const errorMessage = document.getElementById('admin-login-error');
	
	            errorMessage.classList.add('hidden');

	            if(!emailInput.checkValidity() || !passwordInput.checkValidity()) {
	                event.target.reportValidity();
	                return;
	            }

            const response = await fetch(ADMIN_LOGIN_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({ email, password })
            });

            if(!response.ok) {
                errorMessage.classList.remove('hidden');
                document.getElementById('admin-password').focus();
                showAdminAlert('error', 'Login Failed', 'Wrong admin email or password.');
                return;
            }

            sessionStorage.setItem('kermitsAdminAccess', 'admin');
            showAdminAlert('success', 'Login Successful', 'Welcome to the admin dashboard.').then(() => {
                window.location.href = ADMIN_DASHBOARD_URL;
            });
        }

        if(sessionStorage.getItem('kermitsAdminAccess') === 'admin') {
            window.location.href = ADMIN_DASHBOARD_URL;
        }
    </script>
</body>
</html>
