<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Krusty Krab</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/hex.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <nav class="navbar">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" class="logoImg">
            <h2>KRUSTY KRAB</h2>
        </div>
        </div>

        <ul id="nav-menu">
            <li><a href="#home" class="nav-link">Home</a></li>
            <li><a href="#about" class="nav-link">About</a></li>
            <li><a href="#contact" class="nav-link">Contact</a></li>
            <li><a href="#login" class="logIn-btn">Login</a></li>
        </ul>

        <div class="menu-toggle" id="menu-toggle">☰</div>
    </nav>

    <!-- Home Section -->

    <section id="home" class="section home" style="position: relative; overflow: hidden;">
        <!-- Bubbles container for background -->
        <div class="bubbles-container"></div>

        <style>
            /* Bubble Animation */
            .bubbles-container {
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 1;
                pointer-events: none;
                overflow: hidden;
            }

            .bubble {
                position: absolute;
                bottom: -20px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
                box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.5);
                animation: rise linear infinite;
            }

            @keyframes rise {
                0% {
                    transform: translateY(0) scale(1);
                    opacity: 0;
                }

                10% {
                    opacity: 1;
                }

                90% {
                    opacity: 1;
                }

                100% {
                    transform: translateY(-1000px) scale(1.5);
                    opacity: 0;
                }
            }

            .hero-layout {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 60px;
                width: 100%;
                position: relative;
                z-index: 2;
            }

            /* Hover interactions & Floating */
            .hero-character {
                max-width: 250px;
                text-align: center;
                animation: float 4s ease-in-out infinite;
                display: none;
                margin-top: 100px;
                cursor: pointer;
                transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            }

            .left-character:hover {
                transform: scale(1.1) rotate(-5deg);
                animation-play-state: paused;
            }

            .right-character:hover {
                transform: scale(1.1) rotate(5deg);
                animation-play-state: paused;
            }

            .left-character {
                animation-delay: 0s;
            }

            .right-character {
                animation-delay: 1.5s;
            }

            .hero-character img {
                width: 100%;
                filter: drop-shadow(0 15px 25px rgba(0, 0, 0, 0.6));
                pointer-events: none;
            }

            .speech-bubble {
                background: #fff;
                color: #50061e;
                padding: 18px 22px;
                border-radius: 20px;
                font-weight: 700;
                font-size: 1.1rem;
                position: relative;
                margin-bottom: 25px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
                text-shadow: none;
                font-family: 'Poppins', sans-serif;
                pointer-events: none;
            }

            .left-character .speech-bubble::after {
                content: '';
                position: absolute;
                bottom: -15px;
                right: 50px;
                border-width: 15px 15px 0 0;
                border-style: solid;
                border-color: #fff transparent transparent transparent;
            }

            .right-character .speech-bubble::after {
                content: '';
                position: absolute;
                bottom: -15px;
                left: 50px;
                border-width: 15px 0 0 15px;
                border-style: solid;
                border-color: #fff transparent transparent transparent;
            }

            @keyframes float {
                0% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-20px);
                }

                100% {
                    transform: translateY(0);
                }
            }

            @media(min-width: 1024px) {
                .hero-character {
                    display: block;
                }
            }

            /* Pulsing Button */
            .pulse-btn {
                animation: pulseGlow 2s infinite;
                display: inline-block;
            }

            @keyframes pulseGlow {
                0% {
                    box-shadow: 0 0 0 0 rgba(252, 226, 6, 0.7);
                }

                70% {
                    box-shadow: 0 0 0 15px rgba(252, 226, 6, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(252, 226, 6, 0);
                }
            }

            /* Subtitle */
            .subtitle {
                font-size: 1.3rem;
                color: #fff;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.8);
                margin: -25px 0 20px 0;
                font-family: 'Poppins', sans-serif;
                font-style: italic;
            }
        </style>

        <div class="hero-layout">
            <!-- Left Character -->
            <div class="hero-character left-character" onclick="playAudio('spongeAudio')">
                <div class="speech-bubble">“I’m ready! Come try a Krabby Patty!”</div>
                <img src="{{ asset('images/sponge.png') }}" alt="SpongeBob cooking">
                <audio id="spongeAudio" src="{{ asset('sounds/spongebob.mp3') }}"></audio>
            </div>

            <!-- Center Logo/Title -->
            <div class="text">
                <div class="title-container">
                    <img src="{{ asset('images/logo.png') }}" class="logoImg">
                    <h1>WELCOME TO <span>KRUSTY KRAB!</span></h1>
                    <p class="subtitle" style="margin-top: 5px;">Home of the world-famous Krabby Patty!</p>
                    <div style="margin-top: 30px;">
                        <a href="#login" class="logIn-btn pulse-btn"
                            style="font-size: 1.2rem; padding: 12px 35px; text-decoration: none;">Order Now</a>
                    </div>
                </div>
            </div>

            <!-- Right Character -->
            <div class="hero-character right-character" onclick="playAudio('krabsAudio')">
                <div class="speech-bubble">“Money well spent!”</div>
                <img src="{{ asset('images/krabs.png') }}" alt="Mr Krabs">
                <audio id="krabsAudio" src="{{ asset('sounds/mrkrabs.mp3') }}"></audio>
            </div>
        </div>

        <script>
            // Audio player function
            function playAudio(id) {
                const audio = document.getElementById(id);
                if (audio) {
                    audio.currentTime = 0;
                    audio.play().catch(e => console.log('Add the related .mp3 to public/sounds/ to hear this!', e));
                }
            }

            // Bubble generator
            document.addEventListener('DOMContentLoaded', function () {
                const container = document.querySelector('.bubbles-container');
                if (!container) return;
                const bubbleCount = 15;
                for (let i = 0; i < bubbleCount; i++) {
                    let bubble = document.createElement('div');
                    bubble.className = 'bubble';

                    let size = Math.random() * 40 + 10;
                    bubble.style.width = size + 'px';
                    bubble.style.height = size + 'px';
                    bubble.style.left = Math.random() * 100 + '%';
                    bubble.style.animationDuration = (Math.random() * 5 + 4) + 's';
                    bubble.style.animationDelay = (Math.random() * 5) + 's';

                    container.appendChild(bubble);
                }
            });
        </script>
    </section>


    <!-- About Section -->
    <section id="about" class="about">
        <h2 class="about-title">About the <span>KRUSTY KRAB</span></h2>
        <div class="about-cards">
            <div class="about-card">
                <p>
                    Welcome to <span>The Krusty Krab</span>, the most famous fast-food restaurant under the sea!
                    Known for its legendary <span>Krabby Patty</span>, we serve delicious meals and unforgettable
                    experiences.<br><br>
                    Managed by <span>Mr. Krabs</span>, with help from <span>SpongeBob SquarePants</span> and
                    <span>Squidward Tentacles</span>, every order is prepared with care.<br><br>
                    Whether you're craving a juicy Krabby Patty or crispy sea fries, The Krusty Krab is the perfect
                    place to enjoy food with friends and family.<br><br>
                    <span>"The Krusty Krab — Home of the Krabby Patty!"</span>
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section contact">
        <div class="main-wrapper">
            <div class="contact-info">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
                <h2>Contact Us</h2>
                <hr>
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <a href="tel:+639129298869">+63 912 929 8869</a>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:krustykrab@gmail.com">krustykrab@gmail.com</a>
                </div>
            </div>
            <div class="message">
                <h3>Send us a message!</h3>
                <form action="#" method="POST">
                    @csrf
                    <input type="text" name="name" placeholder="Your Name" required>
                    <input type="email" name="email" placeholder="Your Email" required>
                    <textarea name="message" placeholder="Your Message"></textarea>
                    <button type="submit">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Login/Register/Forgot Password Section -->
    <section id="login" class="login-section">
        <div class="login-card">

            <!-- Notification Modal -->
            <style>
                .custom-modal-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.6);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 10000;
                    opacity: 0;
                    pointer-events: none;
                    transition: opacity 0.3s ease;
                }

                .custom-modal-overlay.show {
                    opacity: 1;
                    pointer-events: auto;
                }

                .custom-modal-box {
                    background: #fff;
                    border-radius: 12px;
                    width: 90%;
                    max-width: 380px;
                    padding: 30px 24px;
                    text-align: center;
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                    transform: translateY(-20px);
                    transition: transform 0.3s ease;
                }

                .custom-modal-overlay.show .custom-modal-box {
                    transform: translateY(0);
                }

                .custom-modal-icon {
                    font-size: 50px;
                    margin-bottom: 15px;
                }

                .custom-modal-icon.error {
                    color: #d93025;
                }

                .custom-modal-icon.success {
                    color: #188038;
                }

                .custom-modal-title {
                    font-size: 1.4rem;
                    font-weight: 700;
                    margin-bottom: 10px;
                    color: #333;
                }

                .custom-modal-text {
                    font-size: 1rem;
                    color: #555;
                    margin-bottom: 25px;
                    line-height: 1.5;
                }

                .custom-modal-btn {
                    background: #fce206;
                    color: #611909;
                    border: none;
                    padding: 12px 30px;
                    border-radius: 6px;
                    cursor: pointer;
                    font-size: 1.05rem;
                    font-weight: 600;
                    transition: background 0.2s;
                    width: 100%;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }

                .custom-modal-btn:hover {
                    background: #e5cc05;
                }
            </style>

            <div class="custom-modal-overlay" id="notificationModal">
                <div class="custom-modal-box">
                    <div class="custom-modal-icon" id="modalIcon">⚠️</div>
                    <div class="custom-modal-title" id="modalTitle">Notification</div>
                    <div class="custom-modal-text" id="modalText">Message</div>
                    <button class="custom-modal-btn"
                        onclick="document.getElementById('notificationModal').classList.remove('show')">Okay</button>
                </div>
            </div>

            <script>
                function showModal(type, title, message) {
                    const modal = document.getElementById('notificationModal');
                    const icon = document.getElementById('modalIcon');
                    const titleEl = document.getElementById('modalTitle');
                    const textEl = document.getElementById('modalText');

                    if (type === 'error') {
                        icon.innerHTML = '❌';
                        icon.className = 'custom-modal-icon error';
                    } else {
                        icon.innerHTML = '✅';
                        icon.className = 'custom-modal-icon success';
                    }
                    titleEl.textContent = title;
                    textEl.innerHTML = message;
                    modal.classList.add('show');
                }

                document.addEventListener('DOMContentLoaded', () => {
                    @if(session('success'))
                        showModal('success', 'Success!', "{!! addslashes(session('success')) !!}");
                    @elseif(session('error'))
                        showModal('error', 'Access Denied', "{!! addslashes(session('error')) !!}");
                    @elseif($errors->any())
                        let errorMsgs = "";
                        @foreach ($errors->all() as $error)
                            errorMsgs += "{!! addslashes($error) !!}<br>";
                        @endforeach
                        showModal('error', 'Oops!', errorMsgs);
                    @endif
            });
            </script>

            <!-- LOGIN FORM -->
            <div id="loginForm">
                <div class="login-header">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="login-logo">
                    <h2>Login</h2>
                </div>
                <form action="{{ route('login.post') }}" method="POST" class="login-form">
                    @csrf
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <a href="#" class="forgot-pass" id="forgotPassLink">Forgot password?</a>
                    <button type="submit" class="btn-logIn">Login</button>
                    <button type="button" class="btn-register" id="showRegister">
                        Don't have an account? Create one here.
                    </button>
                </form>
            </div>

            <!-- REGISTER FORM -->
            <div id="registerForm" style="display: none;">
                <div class="login-header">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="login-logo">
                    <h2>Register</h2>
                </div>
                <form action="{{ route('register.post') }}" method="POST" class="login-form">
                    @csrf
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" placeholder="Enter your full name" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" placeholder="Confirm password" required>
                    </div>
                    <button type="submit" class="btn-logIn">Sign Up</button>
                    <a href="#" id="showLogin" class="forgot-pass">Already have an account? Login here</a>
                </form>
            </div>

            <!-- FORGOT PASSWORD FORM -->
            <div id="forgotPass" style="display: none;">
                <div class="login-header">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="login-logo">
                    <h2>Account Recovery</h2>
                </div>
                <form action="{{ route('forgotpass.post') }}" method="POST" class="login-form">
                    @csrf
                    <p class="form-note">Please enter the email used for your account.</p>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <button type="submit" class="btn-logIn">Send Reset Link</button>
                    <button type="button" id="backToLogin" class="btn-register">Back to Login</button>
                </form>
            </div>

        </div>
    </section>

    <!-- JS -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const loginForm = document.getElementById("loginForm");
            const registerForm = document.getElementById("registerForm");
            const forgotForm = document.getElementById("forgotPass");

            const activeFormTab = "{{ session('active_form', 'loginForm') }}";

            if (activeFormTab === 'registerForm') {
                loginForm.style.display = "none";
                registerForm.style.display = "block";
                forgotForm.style.display = "none";
            } else if (activeFormTab === 'forgotPass') {
                loginForm.style.display = "none";
                registerForm.style.display = "none";
                forgotForm.style.display = "block";
            } else {
                loginForm.style.display = "block";
                registerForm.style.display = "none";
                forgotForm.style.display = "none";
            }

            document.getElementById("showRegister").addEventListener("click", () => {
                loginForm.style.display = "none";
                registerForm.style.display = "block";
                forgotForm.style.display = "none";
            });

            document.getElementById("showLogin").addEventListener("click", (e) => {
                e.preventDefault();
                loginForm.style.display = "block";
                registerForm.style.display = "none";
                forgotForm.style.display = "none";
            });

            document.getElementById("forgotPassLink").addEventListener("click", (e) => {
                e.preventDefault();
                loginForm.style.display = "none";
                registerForm.style.display = "none";
                forgotForm.style.display = "block";
            });

            document.getElementById("backToLogin").addEventListener("click", () => {
                loginForm.style.display = "block";
                registerForm.style.display = "none";
                forgotForm.style.display = "none";
            });
        });
        const toggle = document.getElementById("menu-toggle");
        const menu = document.getElementById("nav-menu");

        toggle.onclick = () => {
            menu.classList.toggle("active");
        };
    </script>
    <script src="{{ asset('js/trynga.js') }}"></script>

</body>

</html>