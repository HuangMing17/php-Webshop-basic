<?php include 'app/views/shares/header.php'; ?>
<h1>Sửa sản phẩm</h1>
<div id="error-messages" class="alert alert-danger" style="display: none;">
    <ul id="error-list"></ul>
</div>
<div id="success-message" class="alert alert-success" style="display: none;"></div>

<form id="edit-product-form" enctype="multipart/form-data">
    <input type="hidden" id="product-id" name="id">
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
        <input type="number" id="SoLuong" name="SoLuong" class="form-control" min="1" required>
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
        <div id="current-image" class="mt-2"></div>
    </div>
    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
</form>
<a href="/hoangduyminh/Product/" class="btn btn-secondary mt-2">Quay lại danh sách sản phẩm</a>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const token = localStorage.getItem('jwtToken');
    if (!token) {
        alert('Vui lòng đăng nhập để sửa sản phẩm');
        location.href = '/hoangduyminh/account/login';
        return;
    }

    // Get product ID from URL
    const urlPath = window.location.pathname;
    const productId = urlPath.split('/').pop();
    
    if (!productId) {
        alert('ID sản phẩm không hợp lệ');
        location.href = '/hoangduyminh/Product/';
        return;
    }

    document.getElementById('product-id').value = productId;

    // Load categories and product data
    loadCategories();
    loadProduct(productId);

    // Handle form submission
    document.getElementById('edit-product-form').addEventListener('submit', function(event) {
        event.preventDefault();
        updateProduct();
    });
});

function loadCategories() {
    fetch('/hoangduyminh/api/category', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
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
    .then(categories => {
        console.log('Categories loaded:', categories); // Debug log
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

function loadProduct(productId) {
    fetch(`/hoangduyminh/api/product/${productId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
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
    .then(product => {
        console.log('Product loaded:', product); // Debug log
        if (product.message && product.message === 'Product not found') {
            alert('Không tìm thấy sản phẩm');
            location.href = '/hoangduyminh/Product/';
            return;
        }

        // Fill form with product data
        document.getElementById('name').value = product.name || '';
        document.getElementById('description').value = product.description || '';
        document.getElementById('price').value = product.price || '';
        document.getElementById('SoLuong').value = product.SoLuong || 1;
        document.getElementById('category_id').value = product.category_id || '';

        // Show current image if exists
        if (product.image) {
            document.getElementById('current-image').innerHTML =
                `<p>Hình ảnh hiện tại:</p><img src="/hoangduyminh/${product.image}" alt="Current Image" style="max-width: 100px;">`;
        }
    })
    .catch(error => {
        console.error('Error loading product:', error);
        showError(['Không thể tải thông tin sản phẩm']);
    });
}

async function updateProduct() {
    const token = localStorage.getItem('jwtToken');
    const productId = document.getElementById('product-id').value;
    
    let imagePath = null;
    
    // Step 1: Upload new image if selected
    const imageFile = document.getElementById('image').files[0];
    if (imageFile) {
        try {
            imagePath = await uploadImageFirst(imageFile, token);
        } catch (error) {
            showError(['Lỗi khi upload hình ảnh: ' + error.message]);
            return;
        }
    } else {
        // Keep existing image if available
        const currentImageSrc = document.querySelector('#current-image img');
        if (currentImageSrc) {
            imagePath = currentImageSrc.src.replace('/hoangduyminh/', '');
        }
    }
    
    // Step 2: Update product with JSON data
    const productData = {
        name: document.getElementById('name').value,
        description: document.getElementById('description').value,
        price: parseFloat(document.getElementById('price').value),
        SoLuong: parseInt(document.getElementById('SoLuong').value),
        category_id: parseInt(document.getElementById('category_id').value),
        existing_image: imagePath
    };

    fetch(`/hoangduyminh/api/product/${productId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify(productData)
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
        console.log('Update product response:', data); // Debug log
        if (data.message === 'Product updated successfully') {
            showSuccess('Sản phẩm đã được cập nhật thành công!');
            setTimeout(() => {
                location.href = '/hoangduyminh/Product/';
            }, 2000);
        } else {
            showError([data.message || 'Có lỗi xảy ra khi cập nhật sản phẩm']);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError(['Có lỗi xảy ra khi cập nhật sản phẩm']);
    });
}

async function uploadImageFirst(imageFile, token) {
    const formData = new FormData();
    formData.append('image', imageFile);
    
    const response = await fetch('/hoangduyminh/api/product/upload-image', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + token
        },
        body: formData
    });
    
    const text = await response.text();
    let cleanText = text;
    if (text.startsWith('FFF')) {
        cleanText = text.substring(3);
    }
    
    const data = JSON.parse(cleanText);
    
    if (!response.ok) {
        throw new Error(data.message || 'Upload failed');
    }
    
    return data.image_path;
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