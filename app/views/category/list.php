<?php include 'app/views/shares/header.php'; ?>
<h1>Danh sách danh mục</h1>
<a href="/hoangduyminh/Category/add" class="btn btn-success mb-2">Thêm danh mục mới</a>

<div id="loading" class="text-center">
    <div class="spinner-border" role="status">
        <span class="sr-only">Đang tải...</span>
    </div>
    <p>Đang tải danh mục...</p>
</div>

<div id="no-categories" class="alert alert-info" style="display: none;">
    Hiện chưa có danh mục nào.
</div>

<div id="error-message" class="alert alert-danger" style="display: none;"></div>

<ul id="category-list" class="list-group" style="display: none;"></ul>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const token = localStorage.getItem('jwtToken');
    loadCategories();
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
        document.getElementById('loading').style.display = 'none';
        
        if (categories.length === 0) {
            document.getElementById('no-categories').style.display = 'block';
        } else {
            displayCategories(categories);
        }
    })
    .catch(error => {
        console.error('Error loading categories:', error);
        document.getElementById('loading').style.display = 'none';
        showError('Có lỗi xảy ra khi tải danh mục. Vui lòng thử lại sau.');
    });
}

function displayCategories(categories) {
    const categoryList = document.getElementById('category-list');
    categoryList.innerHTML = '';
    
    categories.forEach(category => {
        const listItem = document.createElement('li');
        listItem.className = 'list-group-item';
        listItem.innerHTML = `
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h5><a href="/hoangduyminh/Category/show/${category.id}" class="text-decoration-none">
                        ${escapeHtml(category.name)}
                    </a></h5>
                    <p class="mb-1">${escapeHtml(category.description || '')}</p>
                </div>
                <div>
                    <a href="/hoangduyminh/Category/edit/${category.id}" class="btn btn-warning btn-sm">Sửa</a>
                    <button class="btn btn-danger btn-sm" onclick="deleteCategory(${category.id}, '${escapeHtml(category.name)}')">
                        Xóa
                    </button>
                </div>
            </div>
        `;
        categoryList.appendChild(listItem);
    });
    
    categoryList.style.display = 'block';
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
        console.log('Delete category response:', data); // Debug log
        if (data.message === 'Category deleted successfully') {
            // Reload categories after successful deletion
            loadCategories();
            showSuccess('Danh mục đã được xóa thành công!');
        } else {
            showError(data.message || 'Có lỗi xảy ra khi xóa danh mục');
        }
    })
    .catch(error => {
        console.error('Error deleting category:', error);
        showError('Có lỗi xảy ra khi xóa danh mục');
    });
}

function showError(message) {
    const errorDiv = document.getElementById('error-message');
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
    setTimeout(() => {
        errorDiv.style.display = 'none';
    }, 5000);
}

function showSuccess(message) {
    // Create a temporary success message
    const successDiv = document.createElement('div');
    successDiv.className = 'alert alert-success';
    successDiv.textContent = message;
    document.querySelector('h1').insertAdjacentElement('afterend', successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 3000);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>

<?php include 'app/views/shares/footer.php'; ?>