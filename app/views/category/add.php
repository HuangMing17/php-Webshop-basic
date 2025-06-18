<?php include 'app/views/shares/header.php'; ?>
<h1>Thêm danh mục mới</h1>

<div id="error-messages" class="alert alert-danger" style="display: none;">
    <ul id="error-list"></ul>
</div>
<div id="success-message" class="alert alert-success" style="display: none;"></div>

<form id="add-category-form">
    <div class="form-group">
        <label for="name">Tên danh mục:</label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="description">Mô tả:</label>
        <textarea id="description" name="description" class="form-control" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Thêm danh mục</button>
</form>
<a href="/hoangduyminh/Category/list" class="btn btn-secondary mt-2">Quay lại danh sách danh mục</a>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const token = localStorage.getItem('jwtToken');
    if (!token) {
        alert('Vui lòng đăng nhập để thêm danh mục');
        location.href = '/hoangduyminh/account/login';
        return;
    }

    // Handle form submission
    document.getElementById('add-category-form').addEventListener('submit', function(event) {
        event.preventDefault();
        addCategory();
    });
});

function addCategory() {
    const token = localStorage.getItem('jwtToken');
    
    const categoryData = {
        name: document.getElementById('name').value.trim(),
        description: document.getElementById('description').value.trim()
    };

    if (!categoryData.name) {
        showError(['Tên danh mục không được để trống']);
        return;
    }

    fetch('/hoangduyminh/api/category', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify(categoryData)
    })
    .then(response => {
        return response.text().then(text => {
            // Clean any FFF prefix
            let cleanText = text;
            if (text.startsWith('FFF')) {
                cleanText = text.substring(3);
            }
            try {
                return JSON.parse(cleanText);
            } catch (e) {
                console.error('Failed to parse JSON:', cleanText);
                throw new Error('Invalid JSON response');
            }
        });
    })
    .then(data => {
        console.log('Add category response:', data); // Debug log
        if (data.message === 'Category created successfully') {
            showSuccess('Danh mục đã được thêm thành công!');
            document.getElementById('add-category-form').reset();
            setTimeout(() => {
                location.href = '/hoangduyminh/Category/list';
            }, 2000);
        } else {
            showError([data.message || 'Có lỗi xảy ra khi thêm danh mục']);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError(['Có lỗi xảy ra khi thêm danh mục']);
    });
}

function showError(errors) {
    const errorDiv = document.getElementById('error-messages');
    const errorList = document.getElementById('error-list');
    const successDiv = document.getElementById('success-message');
    
    successDiv.style.display = 'none';
    errorList.innerHTML = '';
    
    errors.forEach(error => {
        const li = document.createElement('li');
        li.textContent = error;
        errorList.appendChild(li);
    });
    
    errorDiv.style.display = 'block';
}

function showSuccess(message) {
    const errorDiv = document.getElementById('error-messages');
    const successDiv = document.getElementById('success-message');
    
    errorDiv.style.display = 'none';
    successDiv.textContent = message;
    successDiv.style.display = 'block';
}
</script>

<?php include 'app/views/shares/footer.php'; ?>