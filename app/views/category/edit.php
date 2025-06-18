<?php include 'app/views/shares/header.php'; ?>
<h1>Sửa danh mục</h1>

<div id="error-messages" class="alert alert-danger" style="display: none;">
    <ul id="error-list"></ul>
</div>
<div id="success-message" class="alert alert-success" style="display: none;"></div>

<div id="loading" class="text-center">
    <div class="spinner-border" role="status">
        <span class="sr-only">Đang tải...</span>
    </div>
    <p>Đang tải thông tin danh mục...</p>
</div>

<form id="edit-category-form" style="display: none;">
    <input type="hidden" id="category-id" name="id">
    <div class="form-group">
        <label for="name">Tên danh mục:</label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="description">Mô tả:</label>
        <textarea id="description" name="description" class="form-control" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
</form>
<a href="/hoangduyminh/Category/list" class="btn btn-secondary mt-2">Quay lại danh sách danh mục</a>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const token = localStorage.getItem('jwtToken');
    if (!token) {
        alert('Vui lòng đăng nhập để sửa danh mục');
        location.href = '/hoangduyminh/account/login';
        return;
    }

    // Get category ID from URL
    const urlPath = window.location.pathname;
    const categoryId = urlPath.split('/').pop();
    
    if (!categoryId) {
        alert('ID danh mục không hợp lệ');
        location.href = '/hoangduyminh/Category/list';
        return;
    }

    document.getElementById('category-id').value = categoryId;
    loadCategory(categoryId);

    // Handle form submission
    document.getElementById('edit-category-form').addEventListener('submit', function(event) {
        event.preventDefault();
        updateCategory();
    });
});

function loadCategory(categoryId) {
    fetch(`/hoangduyminh/api/category/${categoryId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(category => {
        document.getElementById('loading').style.display = 'none';
        
        if (category.message && category.message === 'Category not found') {
            alert('Không tìm thấy danh mục');
            location.href = '/hoangduyminh/Category/list';
            return;
        }

        // Fill form with category data
        document.getElementById('name').value = category.name || '';
        document.getElementById('description').value = category.description || '';
        document.getElementById('edit-category-form').style.display = 'block';
    })
    .catch(error => {
        console.error('Error loading category:', error);
        document.getElementById('loading').style.display = 'none';
        showError(['Không thể tải thông tin danh mục']);
    });
}

function updateCategory() {
    const token = localStorage.getItem('jwtToken');
    const categoryId = document.getElementById('category-id').value;
    
    const categoryData = {
        name: document.getElementById('name').value.trim(),
        description: document.getElementById('description').value.trim()
    };

    if (!categoryData.name) {
        showError(['Tên danh mục không được để trống']);
        return;
    }

    fetch(`/hoangduyminh/api/category/${categoryId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify(categoryData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.message === 'Category updated successfully') {
            showSuccess('Danh mục đã được cập nhật thành công!');
            setTimeout(() => {
                location.href = '/hoangduyminh/Category/list';
            }, 2000);
        } else {
            showError([data.message || 'Có lỗi xảy ra khi cập nhật danh mục']);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError(['Có lỗi xảy ra khi cập nhật danh mục']);
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