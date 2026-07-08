<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration - Kermit's Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --orange: #ff7214;
            --orange-dark: #e85f05;
            --panel: rgba(14, 20, 24, 0.92);
            --line: rgba(255, 255, 255, 0.1);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background: #070707;
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-y: auto;
        }

        .register-page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(390px, 0.68fr);
            background:
                linear-gradient(90deg, rgba(0, 0, 0, 0.24), rgba(0, 0, 0, 0.78) 62%, #070707 100%),
                url("{{ asset('reg.jpg') }}") center / cover no-repeat;
        }

        .story-panel {
            position: relative;
            min-height: 100vh;
            padding: clamp(1rem, 2.4vw, 1.7rem) clamp(1.25rem, 5vw, 4.5rem);
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
            text-align: left;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            font-weight: 800;
            font-size: clamp(1rem, 1.4vw, 1.25rem);
            line-height: 1.1;
            padding: 0.3rem 0;
            margin-bottom: clamp(1.5rem, 3vw, 2rem);
            text-shadow: 0 3px 18px rgba(0, 0, 0, 0.45);
        }

        .brand img {
            width: 48px;
            height: 48px;
            border-radius: 999px;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.75);
            background: white;
            flex-shrink: 0;
        }

        .story-copy {
            max-width: 560px;
            margin: clamp(8rem, 34vh, 18rem) 0 0;
        }

        .story-copy h1 {
            font-size: clamp(2.8rem, 4.5vw, 4.2rem);
            line-height: 0.98;
            font-weight: 900;
            letter-spacing: 0;
            margin: 0 0 1.1rem;
            max-width: 620px;
            text-shadow: 0 5px 28px rgba(0, 0, 0, 0.45);
        }

        .story-copy h1::after {
            content: "";
            display: block;
            width: 74px;
            height: 4px;
            margin-top: 0.95rem;
            background: var(--orange);
            border-radius: 999px;
        }

        .story-copy p {
            max-width: 520px;
            color: rgba(255, 255, 255, 0.78);
            font-size: clamp(1rem, 1.25vw, 1.12rem);
            line-height: 1.55;
            margin: 0;
        }

        .benefits {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.75rem;
            width: min(100%, 420px);
            position: static;
            margin-top: clamp(2rem, 4vw, 3.5rem);
        }

        .benefit {
            min-width: 0;
            padding: 0.75rem 0;
            text-align: center;
        }

        .benefit-icon {
            display: block;
            margin: 0 auto 0.65rem;
            font-size: 1.25rem;
            color: var(--orange);
        }

        .benefit strong {
            display: block;
            font-size: 0.92rem;
            margin-bottom: 0.28rem;
            text-shadow: 0 3px 14px rgba(0, 0, 0, 0.48);
        }

        .benefit span {
            display: block;
            color: rgba(255, 255, 255, 0.66);
            font-size: 0.82rem;
            line-height: 1.35;
        }

        .form-panel {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(0.75rem, 2.2vw, 1.5rem);
        }

        .register-card {
            width: min(100%, 430px);
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: clamp(1rem, 2vw, 1.45rem);
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.42);
            backdrop-filter: blur(18px);
        }

        .register-card h2 {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.7rem;
            text-align: center;
            font-size: clamp(1.7rem, 2.4vw, 2.15rem);
            line-height: 1.1;
            font-weight: 900;
            margin: 0 0 0.35rem;
        }

        .register-card .subtitle {
            color: rgba(255, 255, 255, 0.62);
            margin-bottom: 0.9rem;
            font-size: 0.95rem;
        }

        .field-group {
            margin-bottom: 0.62rem;
        }

        .field-group label {
            display: block;
            font-size: 0.78rem;
            font-weight: 800;
            color: rgba(255, 255, 255, 0.82);
            margin-bottom: 0.3rem;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.5);
            pointer-events: none;
        }

        .input-wrap input {
            width: 100%;
            min-height: 38px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.06);
            color: white;
            outline: none;
            padding: 0 14px 0 44px;
            font-size: 0.93rem;
            transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
        }

        .input-wrap input::placeholder {
            color: rgba(255, 255, 255, 0.34);
        }

        .input-wrap input:focus {
            border-color: var(--orange);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(255, 114, 20, 0.22);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .agree-row {
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            color: rgba(255, 255, 255, 0.66);
            font-size: 0.82rem;
            line-height: 1.4;
            margin: 0.25rem 0 0.85rem;
        }

        .agree-row input {
            width: 16px;
            height: 16px;
            margin-top: 2px;
            accent-color: var(--orange);
        }

        .submit-btn {
            width: 100%;
            min-height: 42px;
            border: 0;
            border-radius: 8px;
            background: var(--orange);
            color: white;
            font-weight: 900;
            font-size: 0.98rem;
            cursor: pointer;
            transition: background 0.18s, transform 0.18s, box-shadow 0.18s;
            box-shadow: 0 16px 34px rgba(255, 114, 20, 0.26);
        }

        .submit-btn:hover {
            background: var(--orange-dark);
            transform: translateY(-1px);
        }

        .login-note {
            margin: 0.85rem 0 0;
            text-align: center;
            color: rgba(255, 255, 255, 0.64);
            font-size: 0.84rem;
        }

        .login-note a {
            color: var(--orange);
            font-weight: 900;
            cursor: pointer;
        }

        .mode-hidden {
            display: none !important;
        }

        .login-mode .form-row {
            grid-template-columns: 1fr;
        }

        .title-check {
            width: 0;
            height: 1.05em;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #4ade80;
            font-size: 0.9em;
            opacity: 0;
            overflow: hidden;
            transform: scale(0.5) rotate(-18deg);
        }

        .register-card.show-check .title-check {
            width: 1.05em;
            animation: success-pop 0.5s ease-out both;
        }

        .register-card.show-check .title-check i {
            animation: success-check 0.45s ease-out 0.12s both;
        }

        @keyframes success-pop {
            0% {
                opacity: 0;
                transform: scale(0.55);
            }
            70% {
                opacity: 1;
                transform: scale(1.08);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes success-check {
            0% {
                opacity: 0;
                transform: scale(0.4) rotate(-18deg);
            }
            100% {
                opacity: 1;
                transform: scale(1) rotate(0);
            }
        }

        @media (max-width: 980px) {
            .register-page {
                grid-template-columns: 1fr;
            }

            .story-panel {
                min-height: auto;
                align-items: flex-start;
                justify-content: flex-start;
                gap: 1.2rem;
                padding: 1rem 1rem 0;
                text-align: left;
            }

            .brand {
                margin-bottom: 1rem;
            }

            .story-copy {
                display: none;
            }

            .benefits {
                display: none;
            }

            .register-card {
                width: 100%;
                max-width: 430px;
                margin: 0;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-panel {
                min-height: auto;
                align-items: flex-start;
                padding: 1rem 1rem 1.25rem;
            }
        }

        @media (max-height: 760px) and (min-width: 981px) {
            .story-copy h1 {
                font-size: clamp(2.4rem, 4.3vw, 3.9rem);
            }

            .story-copy {
                margin-bottom: 1rem;
            }

            .benefits {
                display: none;
            }

            .form-panel {
                padding-top: 0.75rem;
                padding-bottom: 0.75rem;
            }

            .register-card {
                padding: 1rem;
            }

            .register-card h2 {
                font-size: 1.65rem;
            }

            .register-card .subtitle {
                margin-bottom: 0.65rem;
            }

            .field-group {
                margin-bottom: 0.5rem;
            }

            .input-wrap input {
                min-height: 36px;
            }

            .submit-btn {
                min-height: 40px;
            }
        }

        @media (max-width: 620px) {
            .story-panel {
                padding: 1.25rem;
                gap: 2rem;
            }

            .story-copy {
                margin-bottom: 0;
                margin-top: 1rem;
            }

            .benefits,
            .form-row {
                grid-template-columns: 1fr;
            }

            .story-copy h1 {
                font-size: 2.55rem;
            }

            .story-copy p {
                font-size: 1rem;
            }

            .form-panel {
                padding: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <main class="register-page">
        <section class="story-panel">
            <a href="{{ route('home') }}" class="brand">
                <img src="{{ asset('kermit.jpg') }}" alt="Kermit's Restaurant">
                <span>Kermit's Restaurant</span>
            </a>

            <div class="story-copy">
                <h1>Great food,<br>made for you.</h1>
                <p>Create your customer account for faster ordering, saved contact details, and easy order tracking.</p>
            </div>

            <div class="benefits" aria-label="Customer account benefits">
                <div class="benefit">
                    <span class="benefit-icon"><i class="fas fa-motorcycle"></i></span>
                    <strong>Fast Orders</strong>
                    <span>Save details for next time</span>
                </div>
                <div class="benefit">
                    <span class="benefit-icon"><i class="fas fa-shield-alt"></i></span>
                    <strong>Secure Account</strong>
                    <span>Your details stay protected</span>
                </div>
                <div class="benefit">
                    <span class="benefit-icon"><i class="fas fa-receipt"></i></span>
                    <strong>Easy Tracking</strong>
                    <span>Follow your order status</span>
                </div>
            </div>
        </section>

        <section class="form-panel" aria-label="Customer account form">
            <form id="customer-account-form" class="register-card" method="POST" action="{{ route('customer.register.submit') }}">
                @csrf
                <h2>
                    <span id="registration-check" class="title-check" aria-hidden="true"><i class="fas fa-check"></i></span>
                    <span id="form-title">Create account</span>
                </h2>
                <p id="form-subtitle" class="subtitle">Register as a Kermit's Restaurant customer</p>

                @if ($errors->any())
                    <div class="form-errors" style="margin-bottom:1rem;padding:1rem;border:1px solid #f87171;background:#fef2f2;color:#991b1b;border-radius:.5rem;">
                        <strong>There were some problems with your input.</strong>
                        <ul style="margin:.5rem 0 0;padding-left:1.25rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-row register-only">
                    <div class="field-group">
                        <label for="first_name">First Name</label>
                        <div class="input-wrap">
                            <i class="fas fa-user"></i>
                            <input id="first_name" name="first_name" type="text" placeholder="Juan" value="{{ old('first_name') }}" required>
                        </div>
                    </div>
                    <div class="field-group">
                        <label for="last_name">Last Name</label>
                        <div class="input-wrap">
                            <i class="fas fa-user"></i>
                            <input id="last_name" name="last_name" type="text" placeholder="Dela Cruz" value="{{ old('last_name') }}" required>
                        </div>
                    </div>
                </div>

                <div class="field-group">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope"></i>
                        <input id="email" name="email" type="email" placeholder="name@gmail.com" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="field-group register-only">
                    <label for="phone">Phone Number</label>
                    <div class="input-wrap">
                        <i class="fas fa-phone"></i>
                            <input id="phone" name="phone" type="tel" inputmode="numeric" maxlength="11" placeholder="09XXXXXXXXX" value="{{ old('phone') }}" required>
                    <label for="address">Delivery Address</label>
                    <div class="input-wrap">
                        <i class="fas fa-map-marker-alt"></i>
                        <input id="address" name="address" type="text" placeholder="Street, barangay, city" value="{{ old('address') }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="field-group">
                        <label for="password">Password</label>
                        <div class="input-wrap">
                            <i class="fas fa-lock"></i>
                            <input id="password" name="password" type="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="field-group">
                        <label id="password-confirmation-label" for="password_confirmation">Confirm</label>
                        <div class="input-wrap">
                            <i class="fas fa-lock"></i>
                            <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm" required>
                        </div>
                    </div>
                </div>

                <label class="agree-row register-only">
                    <input type="checkbox" name="terms" required>
                    <span>I agree to receive order updates and accept the customer account terms.</span>
                </label>

                <button id="submit-btn" type="submit" class="submit-btn">
                    Register <i class="fas fa-arrow-right ml-2"></i>
                </button>

                <p id="register-note" class="login-note">Already have an account? <a href="#" id="show-login">Sign in here</a></p>
                <p id="login-note" class="login-note mode-hidden">Need an account? <a href="#" id="show-register">Register here</a></p>
            </form>
        </section>
    </main>
    <script>
        const formTitle = document.getElementById('form-title');
        const accountForm = document.getElementById('customer-account-form');
        const formSubtitle = document.getElementById('form-subtitle');
        const submitBtn = document.getElementById('submit-btn');
        const registerNote = document.getElementById('register-note');
        const loginNote = document.getElementById('login-note');
        const registerOnlyFields = document.querySelectorAll('.register-only');
        const passwordConfirmation = document.getElementById('password_confirmation');
        const passwordConfirmationLabel = document.getElementById('password-confirmation-label');
        const loginAction = "{{ route('customer.home') }}";
        const registerAction = "{{ route('customer.register.submit') }}";

        accountForm.action = registerAction;
        let isLoginMode = false;
        const phoneInput = document.getElementById('phone');

        const setFieldRequirement = (selector, required) => {
            document.querySelectorAll(selector).forEach((field) => {
                field.required = required;
            });
        };

        phoneInput.addEventListener('input', () => {
            let digits = phoneInput.value.replace(/\D/g, '');
            if (digits.length > 11) {
                digits = digits.slice(0, 11);
            }
            if (phoneInput.value !== digits) {
                phoneInput.value = digits;
            }
        });

        const showLogin = () => {
            isLoginMode = true;
            formTitle.textContent = 'Log in';
            accountForm.classList.add('login-mode');
            accountForm.classList.remove('show-check');
            formSubtitle.textContent = 'Enter your email and password to view the home menu and orders.';
            submitBtn.innerHTML = 'Log in <i class="fas fa-arrow-right ml-2"></i>';
            registerOnlyFields.forEach((field) => field.classList.add('mode-hidden'));
            registerNote.classList.add('mode-hidden');
            loginNote.classList.remove('mode-hidden');
            passwordConfirmation.parentElement.parentElement.classList.add('mode-hidden');
            passwordConfirmationLabel.textContent = 'Confirm';
            setFieldRequirement('.register-only input', false);
            passwordConfirmation.required = false;
            accountForm.action = loginAction;
        };

        const showRegister = () => {
            isLoginMode = false;
            formTitle.textContent = 'Create account';
            accountForm.classList.remove('login-mode');
            accountForm.classList.remove('show-check');
            formSubtitle.textContent = "Register as a Kermit's Restaurant customer";
            submitBtn.innerHTML = 'Register <i class="fas fa-arrow-right ml-2"></i>';
            registerOnlyFields.forEach((field) => field.classList.remove('mode-hidden'));
            registerNote.classList.remove('mode-hidden');
            loginNote.classList.add('mode-hidden');
            passwordConfirmation.parentElement.parentElement.classList.remove('mode-hidden');
            setFieldRequirement('.register-only input', true);
            passwordConfirmation.required = true;
            accountForm.action = registerAction;
        };

        document.getElementById('show-login').addEventListener('click', (event) => {
            event.preventDefault();
            showLogin();
        });

        document.getElementById('show-register').addEventListener('click', (event) => {
            event.preventDefault();
            showRegister();
        });

        accountForm.addEventListener('submit', (event) => {
            if (isLoginMode) {
                event.preventDefault();
                window.location.href = loginAction;
                return;
            }

            accountForm.classList.add('show-check');
        });
    </script>
</body>
</html>
