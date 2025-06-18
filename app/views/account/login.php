<?php include 'app/views/shares/header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>
                        Đăng nhập TechPhone Store
                    </h3>
                </div>
                <div class="card-body p-5">
                    <!-- Error Messages -->
                    <div id="error-messages" class="alert alert-danger" style="display: none;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span id="error-text"></span>
                    </div>

                    <!-- Success Messages -->
                    <div id="success-message" class="alert alert-success" style="display: none;">
                        <i class="fas fa-check-circle me-2"></i>
                        <span id="success-text"></span>
                    </div>

                    <form id="login-form">
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-user me-2 text-primary"></i>Tên đăng nhập
                            </label>
                            <input type="text" name="username" class="form-control form-control-lg" 
                                   placeholder="Nhập tên đăng nhập" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-lock me-2 text-primary"></i>Mật khẩu
                            </label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" 
                                       class="form-control form-control-lg" 
                                       placeholder="Nhập mật khẩu" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="toggle-icon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-me">
                                <label class="form-check-label" for="remember-me">
                                    Ghi nhớ đăng nhập
                                </label>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                            </button>
                        </div>

                        <div class="text-center">
                            <a href="#" class="text-decoration-none">Quên mật khẩu?</a>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-3">Chưa có tài khoản?</p>
                        <a href="/hoangduyminh/account/register" class="btn btn-outline-primary">
                            <i class="fas fa-user-plus me-2"></i>Đăng ký ngay
                        </a>
                    </div>

                    <!-- Demo Accounts -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="fw-bold text-center mb-3">Tài khoản demo:</h6>
                        <div class="row text-center">
                            <div class="col-6">
                                <button class="btn btn-sm btn-outline-danger w-100" onclick="fillDemo('admin', 'admin123')">
                                    <i class="fas fa-crown me-1"></i>Admin
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-sm btn-outline-info w-100" onclick="fillDemo('user', 'user123')">
                                    <i class="fas fa-user me-1"></i>User
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const formData = new FormData(this);
    const jsonData = {};
    formData.forEach((value, key) => {
        jsonData[key] = value;
    });
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang đăng nhập...';
    submitBtn.disabled = true;
    
    fetch('/hoangduyminh/account/checkLogin', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(jsonData)
    })
    .then(response => {
        return response.text().then(text => {
            // Clean any FFF prefix or extra characters
            let cleanText = text;
            if (text.startsWith('FFF')) {
                cleanText = text.substring(3);
            }
            // Try to parse as JSON
            try {
                return JSON.parse(cleanText);
            } catch (e) {
                console.error('Failed to parse JSON:', cleanText);
                throw new Error('Invalid JSON response');
            }
        });
    })
    .then(data => {
        console.log('Login response:', data); // Debug log
        
        if (data.token && data.user) {
            localStorage.setItem('jwtToken', data.token);
            
            // Store user information from API response
            sessionStorage.setItem('userRole', data.user.role);
            sessionStorage.setItem('username', data.user.username);
            sessionStorage.setItem('fullname', data.user.fullname);
            sessionStorage.setItem('userId', data.user.id);
            
            showSuccess('Đăng nhập thành công! Đang chuyển hướng...');
            
            setTimeout(() => {
                location.href = '/hoangduyminh/Product/home';
            }, 1500);
        } else {
            showError(data.message || 'Tên đăng nhập hoặc mật khẩu không chính xác');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Có lỗi xảy ra khi đăng nhập. Vui lòng thử lại.');
    })
    .finally(() => {
        // Restore button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggle-icon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.className = 'fas fa-eye-slash';
    } else {
        passwordInput.type = 'password';
        toggleIcon.className = 'fas fa-eye';
    }
}

function fillDemo(username, password) {
    document.querySelector('input[name="username"]').value = username;
    document.querySelector('input[name="password"]').value = password;
}

function showError(message) {
    const errorDiv = document.getElementById('error-messages');
    const successDiv = document.getElementById('success-message');
    
    successDiv.style.display = 'none';
    document.getElementById('error-text').textContent = message;
    errorDiv.style.display = 'block';
    
    setTimeout(() => {
        errorDiv.style.display = 'none';
    }, 5000);
}

function showSuccess(message) {
    const errorDiv = document.getElementById('error-messages');
    const successDiv = document.getElementById('success-message');
    
    errorDiv.style.display = 'none';
    document.getElementById('success-text').textContent = message;
    successDiv.style.display = 'block';
}
</script>

<?php include 'app/views/shares/footer.php'; ?>