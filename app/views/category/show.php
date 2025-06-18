A<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h2 class="mb-0">Chi tiết danh mục</h2>
        </div>
        <div class="card-body" id="category-details">
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Đang tải...</span>
                </div>
                <p>Đang tải thông tin danh mục...</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Get category ID from URL
    const urlPath = window.location.pathname;
    const categoryId = urlPath.split('/').pop();
    
    if (!categoryId) {
        showError('ID danh mục không hợp lệ');
        return;
    }

    loadCategory(categoryId);
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
        if (category.message && category.message === 'Category not found') {
            showError('Không tìm thấy danh mục!');
            return;
        }

        displayCategory(category);
    })
    .catch(error => {
        console.error('Error loading category:', error);
        showError('Có lỗi xảy ra khi tải thông tin danh mục');
    });
}

function displayCategory(category) {
    const categoryDetails = document.getElementById('category-details');
    
    categoryDetails.innerHTML = `
        <div class="row">
            <div class="col-12">
                <h3 class="card-title text-dark font-weight-bold mb-3">
                    ${escapeHtml(category.name)}
                </h3>
                <div class="mb-4">
                    <h5 class="text-muted">Mô tả:</h5>
                    <p class="card-text">
                        ${category.description ? escapeHtml(category.description) : '<em>Chưa có mô tả</em>'}
                    </p>
                </div>
                <div class="mt-4">
                    <a href="/hoangduyminh/Category/edit/${category.id}" class="btn btn-warning px-4">
                        ✏️ Sửa danh mục
                    </a>
                    <button class="btn btn-danger px-4 ml-2" onclick="deleteCategory(${category.id}, '${escapeHtml(category.name)}')">
                        🗑️ Xóa danh mục
                    </button>
                    <a href="/hoangduyminh/Category/list" class="btn btn-secondary px-4 ml-2">
                        ← Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    `;
}

function deleteCategory(categoryId, categoryName) {
    if (!confirm(`Bạn có chắc chắn muốn xóa danh mục "${categoryName}"?`)) {
        return;
    }
    
    const token = localStorage.getItem('jwtToken');
    if (!token) {
        alert('Vui lòng đăng nhập để thực hiện thao tác này');
        location.href = '/hoangduyminh/account/login';
        return;
    }
    
    fetch(`/hoangduyminh/api/category/${categoryId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.message === 'Category deleted successfully') {
            alert('Danh mục đã được xóa thành công!');
            location.href = '/hoangduyminh/Category/list';
        } else {
            alert(data.message || 'Có lỗi xảy ra khi xóa danh mục');
        }
    })
    .catch(error => {
        console.error('Error deleting category:', error);
        alert('Có lỗi xảy ra khi xóa danh mục');
    });
}

function showError(message) {
    const categoryDetails = document.getElementById('category-details');
    categoryDetails.innerHTML = `
        <div class="alert alert-danger text-center">
            <h4>${message}</h4>
            <a href="/hoangduyminh/Category/list" class="btn btn-primary mt-3">Quay lại danh sách danh mục</a>
        </div>
    `;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>

<?php include 'app/views/shares/footer.php'; ?>