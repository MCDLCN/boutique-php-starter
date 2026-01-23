<!-- views/auth-modal.php -->
<style>
    .auth-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .auth-modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .auth-modal-content {
        background-color: #fff;
        padding: 2rem;
        border-radius: 8px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .auth-modal-close {
        position: absolute;
        right: 1rem;
        top: 1rem;
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #666;
    }

    .auth-modal-close:hover {
        color: #000;
    }

    .auth-modal h2 {
        margin-top: 0;
        margin-bottom: 1.5rem;
        text-align: center;
        color: #333;
    }

    .auth-form-group {
        margin-bottom: 1rem;
    }

    .auth-form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #555;
        font-weight: 500;
    }

    .auth-form-group input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
        box-sizing: border-box;
    }

    .auth-form-group input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }

    .auth-form-button {
        width: 100%;
        padding: 0.75rem;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .auth-form-button:hover {
        background-color: #0056b3;
    }

    .auth-modal-tabs {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .auth-modal-tab {
        background: none;
        border: none;
        padding: 0.75rem 0;
        cursor: pointer;
        font-size: 1rem;
        color: #666;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        transition: all 0.3s;
    }

    .auth-modal-tab.active {
        color: #007bff;
        border-bottom-color: #007bff;
    }

    .auth-form {
        display: none;
    }

    .auth-form.active {
        display: block;
    }

    .auth-toggle-text {
        text-align: center;
        margin-top: 1rem;
        color: #666;
        font-size: 0.9rem;
    }

    .auth-toggle-text button {
        background: none;
        border: none;
        color: #007bff;
        cursor: pointer;
        font-weight: 600;
        text-decoration: underline;
    }

    .auth-error {
        background-color: #f8d7da;
        color: #721c24;
        padding: 0.75rem;
        border-radius: 4px;
        margin-bottom: 1rem;
        display: none;
    }

    .auth-error.show {
        display: block;
    }

    .header__user {
        color: #333;
        font-weight: 500;
        margin-right: 0.5rem;
    }
</style>

<div id="authModal" class="auth-modal">
    <div class="auth-modal-content">
        <button class="auth-modal-close" onclick="closeAuthModal()">✕</button>
        
        <div class="auth-modal-tabs">
            <button class="auth-modal-tab active" onclick="switchTab('login')">Log In</button>
            <button class="auth-modal-tab" onclick="switchTab('register')">Register</button>
        </div>

        <div id="loginError" class="auth-error"></div>
        <div id="registerError" class="auth-error"></div>

        <!-- Login Form -->
        <form id="loginForm" class="auth-form active" onsubmit="handleLogin(event)">
            <h2>Welcome Back</h2>
            
            <div class="auth-form-group">
                <label for="loginEmail">Email</label>
                <input type="email" id="loginEmail" name="email" required>
            </div>

            <div class="auth-form-group">
                <label for="loginPassword">Password</label>
                <input type="password" id="loginPassword" name="password" required>
            </div>

            <button type="submit" class="auth-form-button">Log In</button>
        </form>

        <!-- Register Form -->
        <form id="registerForm" class="auth-form" onsubmit="handleRegister(event)">
            <h2>Create Account</h2>
            
            <div class="auth-form-group">
                <label for="registerName">Full Name</label>
                <input type="text" id="registerName" name="name" required>
            </div>

            <div class="auth-form-group">
                <label for="registerEmail">Email</label>
                <input type="email" id="registerEmail" name="email" required>
            </div>

            <div class="auth-form-group">
                <label for="registerPassword">Password</label>
                <input type="password" id="registerPassword" name="password" required>
            </div>

            <div class="auth-form-group">
                <label for="registerPasswordConfirm">Confirm Password</label>
                <input type="password" id="registerPasswordConfirm" name="password_confirm" required>
            </div>

            <button type="submit" class="auth-form-button">Register</button>
        </form>
    </div>
</div>

<script>
    function openAuthModal(tab = 'login') {
        document.getElementById('authModal').classList.add('active');
        switchTab(tab);
    }

    function closeAuthModal() {
        document.getElementById('authModal').classList.remove('active');
        document.getElementById('loginError').classList.remove('show');
        document.getElementById('registerError').classList.remove('show');
    }

    function switchTab(tab) {
        document.querySelectorAll('.auth-modal-tab').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.auth-form').forEach(form => form.classList.remove('active'));

        if (tab === 'login') {
            document.querySelector('.auth-modal-tab:first-child').classList.add('active');
            document.getElementById('loginForm').classList.add('active');
        } else {
            document.querySelector('.auth-modal-tab:last-child').classList.add('active');
            document.getElementById('registerForm').classList.add('active');
        }
    }

    function showError(formType, message) {
        const errorEl = document.getElementById(formType + 'Error');
        errorEl.textContent = message;
        errorEl.classList.add('show');
    }

    function handleLogin(e) {
        e.preventDefault();
        const form = new FormData(document.getElementById('loginForm'));
        
        fetch('/auth/login', {
            method: 'POST',
            body: form
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeAuthModal();
                setTimeout(() => {
                    location.reload();
                }, 300);
            } else {
                showError('login', data.message || 'Login failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('login', 'An error occurred');
        });
    }

    function handleRegister(e) {
        e.preventDefault();
        
        const password = document.getElementById('registerPassword').value;
        const passwordConfirm = document.getElementById('registerPasswordConfirm').value;
        
        if (password !== passwordConfirm) {
            showError('register', 'Passwords do not match');
            return;
        }

        const form = new FormData(document.getElementById('registerForm'));
        
        fetch('/auth/register', {
            method: 'POST',
            body: form
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeAuthModal();
                setTimeout(() => {
                    location.reload();
                }, 300);
            } else {
                showError('register', data.message || 'Registration failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('register', 'An error occurred');
        });
    }

    // Close modal when clicking outside
    document.getElementById('authModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeAuthModal();
        }
    });
</script>
