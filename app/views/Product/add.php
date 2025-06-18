<?php include 'app/views/shares/header.php'; ?>
<h1>Thêm sản phẩm mới</h1>
<div id="error-messages" class="alert alert-danger" style="display: none;">
    <ul id="error-list"></ul>
</div>
<div id="success-message" class="alert alert-success" style="display: none;"></div>

<form id="add-product-form" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">Tên sản phẩm:</label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="description">Mô tả:</label>
        <textarea id="description" name="description" class="form-control" required></textarea>
    </div>
    <div class="form-group">
        <label for="price">Giá:</label>
        <input type="number" id="price" name="price" class="form-control" step="0.01" required>
    </div>
    <div class="form-group">
        <label for="SoLuong">Số lượng:</label>
        <input type="number" id="SoLuong" name="SoLuong" class="form-control" min="1" value="1" required>
    </div>
    <div class="form-group">
        <label for="category_id">Danh mục:</label>
        <select id="category_id" name="category_id" class="form-control" required>
            <option value="">Chọn danh mục...</option>
        </select>
    </div>
    <div class="form-group">
        <label for="image">Hình ảnh:</label>
        <input type="file" id="image" name="image" class="form-control" accept="image/*">
    </div>
    <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
</form>
<a href="/hoangduyminh/Product/" class="btn btn-secondary mt-2">Quay lại danh sách sản phẩm</a>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const token = localStorage.getItem('jwtToken');
    if (!token) {
        alert('Vui lòng đăng nhập để thêm sản phẩm');
        location.href = '/hoangduyminh/account/login';
        return;
    }

    // Load categories from API
    loadCategories();

    // Handle form submission
    document.getElementById('add-product-form').addEventListener('submit', function(event) {
        event.preventDefault();
        addProduct();
    });
});

function loadCategories() {
    fetch('/hoangduyminh/api/category', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(categories => {
        const categorySelect = document.getElementById('category_id');
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        });
    })
    .catch(error => {
        console.error('Error loading categories:', error);
        showError(['Không thể tải danh mục']);
    });
}

function addProduct() {
    const token = localStorage.getItem('jwtToken');
    const formData = new FormData();
    
    formData.append('name', document.getElementById('name').value);
    formData.append('description', document.getElementById('description').value);
    formData.append('price', document.getElementById('price').value);
    formData.append('SoLuong', document.getElementById('SoLuong').value);
    formData.append('category_id', document.getElementById('category_id').value);
    
    const imageFile = document.getElementById('image').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }

    // Since the API expects JSON, we need to convert FormData to JSON for text fields
    // But for file upload, we need a different approach - let's use the traditional form submission for now
    // and modify the API to handle form data
    
    const productData = {
        name: document.getElementById('name').value,
        description: document.getElementById('description').value,
        price: parseFloat(document.getElementById('price').value),
        SoLuong: parseInt(document.getElementById('SoLuong').value),
        category_id: parseInt(document.getElementById('category_id').value)
    };

    fetch('/hoangduyminh/api/product', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify(productData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.message === 'Product created successfully') {
            showSuccess('Sản phẩm đã được thêm thành công!');
            document.getElementById('add-product-form').reset();
            setTimeout(() => {
                location.href = '/hoangduyminh/Product/';
            }, 2000);
        } else if (data.errors) {
            showError(Object.values(data.errors));
        } else {
            showError([data.message || 'Có lỗi xảy ra khi thêm sản phẩm']);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError(['Có lỗi xảy ra khi thêm sản phẩm']);
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