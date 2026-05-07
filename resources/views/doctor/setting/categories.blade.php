@extends('layouts.admin')
@section('title', 'Categories')
@section('content')
    <div class="container">
        <h1>Categories</h1>

        <!-- Create Category Form -->
        <div class="mb-3">
            <h3>Create Category</h3>
            <form id="createCategoryForm">
                <div class="form-group">
                    <label for="categoryName">Category Name</label>
                    <input type="text" class="form-control" id="categoryName" name="name" required>
                </div>
                <div class="form-group">
                    <label for="parentCategory">Parent Category</label>
                    <select class="form-control" id="parentCategory" name="parent_id">
                        <option value="">None</option>
                        <!-- Dynamically populate categories here -->
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Create Category</button>
            </form>
        </div>

        <!-- Categories List -->
        <div class="mb-3">
            <h3>Categories List</h3>
            <ul id="categoriesList">
                <!-- Dynamically populate categories here -->
            </ul>
        </div>

        <!-- Edit Category Modal -->
        <div class="modal" id="editCategoryModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editCategoryForm">
                            <div class="form-group">
                                <label for="editCategoryName">Category Name</label>
                                <input type="text" class="form-control" id="editCategoryName" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="editParentCategory">Parent Category</label>
                                <select class="form-control" id="editParentCategory" name="parent_id">
                                    <option value="">None</option>
                                    <!-- Dynamically populate categories here -->
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

        <script>

$(document).ready(function() {
    const apiUrl = '/api/categories';

    // Request CSRF Cookie before making API requests
    $.get('/sanctum/csrf-cookie').done(function () {
        fetchCategories();  // Call the function after CSRF cookie is set
    });

    // Fetch categories
    function fetchCategories() {
        $.ajax({
            url: apiUrl,
            method: 'GET',
            xhrFields: {
                withCredentials: true  // This ensures cookies are sent with the request
            },
            success: function(response) {
                if (response.success) {
                    let categoriesHtml = '';
                    response.data.forEach(function(category) {
                        categoriesHtml += `<li>
                            ${category.name}
                            <button class="btn btn-warning btn-sm" onclick="editCategory(${category.id})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteCategory(${category.id})">Delete</button>
                        </li>`;
                    });
                    $('#categoriesList').html(categoriesHtml);
                }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    alert('Authentication failed. Please log in.');
                }
            }
        });
    }

    // Handle create category form submission
    $('#createCategoryForm').on('submit', function(event) {
        event.preventDefault();
        const formData = {
            name: $('#categoryName').val(),
            parent_id: $('#parentCategory').val()
        };

        $.ajax({
            url: apiUrl,
            method: 'POST',
            data: formData,
            xhrFields: {
                withCredentials: true  // Ensures cookies are sent with the request
            },
            success: function(response) {
                if (response.success) {
                    alert('Category created successfully');
                    fetchCategories(); // Reload categories
                    $('#categoryName').val('');
                    $('#parentCategory').val('');
                } else {
                    alert('Failed to create category');
                }
            },
            error: function() {
                alert('Authentication failed. Please log in.');
            }
        });
    });

    // Handle delete category
    window.deleteCategory = function(id) {
        $.ajax({
            url: `${apiUrl}/${id}`,
            method: 'DELETE',
            xhrFields: {
                withCredentials: true  // Ensures cookies are sent with the request
            },
            success: function(response) {
                if (response.success) {
                    alert('Category deleted successfully');
                    fetchCategories(); // Reload categories
                } else {
                    alert('Failed to delete category');
                }
            },
            error: function() {
                alert('Authentication failed. Please log in.');
            }
        });
    };

    // Handle edit category (populate form in modal)
    window.editCategory = function(id) {
        $.ajax({
            url: `${apiUrl}/${id}`,
            method: 'GET',
            xhrFields: {
                withCredentials: true  // Ensures cookies are sent with the request
            },
            success: function(response) {
                if (response.success) {
                    const category = response.data;
                    $('#editCategoryName').val(category.name);
                    $('#editParentCategory').val(category.parent_id || '');
                    $('#editCategoryForm').off('submit').on('submit', function(event) {
                        event.preventDefault();
                        const updateData = {
                            name: $('#editCategoryName').val(),
                            parent_id: $('#editParentCategory').val()
                        };
                        updateCategory(id, updateData);
                    });
                    $('#editCategoryModal').modal('show');
                } else {
                    alert('Failed to fetch category details');
                }
            },
            error: function() {
                alert('Authentication failed. Please log in.');
            }
        });
    };

    // Handle category update
    function updateCategory(id, data) {
        $.ajax({
            url: `${apiUrl}/${id}`,
            method: 'PUT',
            data: data,
            xhrFields: {
                withCredentials: true  // Ensures cookies are sent with the request
            },
            success: function(response) {
                if (response.success) {
                    alert('Category updated successfully');
                    fetchCategories(); // Reload categories
                    $('#editCategoryModal').modal('hide');
                } else {
                    alert('Failed to update category');
                }
            },
            error: function() {
                alert('Authentication failed. Please log in.');
            }
        });
    }
});


        </script>
    @endpush
@endsection
