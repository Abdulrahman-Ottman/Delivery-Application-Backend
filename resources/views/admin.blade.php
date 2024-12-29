<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Product Management</title>
{{--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">--}}
    <link rel="stylesheet" href="{{ url('bootstrap.min.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f5f9;
        }

        .product-card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 10px;
        }

        .product-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .product-card:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
    </style>
</head>

<body>

<div class="container mt-5">
    <div class="row justify-content-between">
        <div class="col-md-6">
            <input type="text" class="form-control" id="searchBar" placeholder="Search products...">
        </div>
        <div class="col-md-3 text-end d-flex justify-content-end gap-3" id="buttonContainer">
            <script>
                const role = JSON.parse(localStorage.getItem('user'))['role'];
                if (role === 'admin') {
                    // Create the Add Product button
                    const addProductButton = document.createElement('button');
                    addProductButton.className = 'btn btn-success';
                    addProductButton.setAttribute('data-bs-toggle', 'modal');
                    addProductButton.setAttribute('data-bs-target', '#addProductModal');
                    addProductButton.textContent = 'Add Product';

                    // Append the button to the buttonContainer
                    document.getElementById('buttonContainer').appendChild(addProductButton);
                }
            </script>
            <button class="btn btn-danger" id="logoutButton">Logout</button>
        </div>
    </div>

    <div class="row mt-4" id="productsGrid">
    </div>
</div>

<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addProductForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="productName" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="productName" required>
                    </div>
                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Price ($)</label>
                        <input type="number" class="form-control" id="productPrice" min="0" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="productQuantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="productQuantity" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="productDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="productDescription" rows="3" required></textarea>
                    </div>
                    <div class="mb-3" id="detailsContainer">
                        <label class="form-label">Details (Key-Value Pairs)</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" placeholder="Key" id="detailKey">
                            <input type="text" class="form-control" placeholder="Value" id="detailValue">
                            <button class="btn btn-secondary" type="button" id="addDetailButton">Add</button>
                        </div>
                        <ul class="list-group" id="detailsList"></ul>
                    </div>
                    <div class="mb-3">
                        <label for="productImage" class="form-label">Main Image (optional)</label>
                        <input type="file" class="form-control" id="productImage" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="additionalImages" class="form-label">Additional Images (optional)</label>
                        <input type="file" class="form-control" id="additionalImages" accept="image/*" multiple>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>
    </div>
</div>
{{--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>--}}
<script src="{{url('bootstrap.bundle.min.js')}}"></script>
<script>
    const url = 'http://192.168.9.54:8000';
    const details = [];
    const details2 = [];
    const accessToken = localStorage.getItem('access_token');
    if (!accessToken){
        localStorage.clear();
        window.location.href = url;
    }
    if (role !='superAdmin' && role !='admin'){
        localStorage.clear();
        window.location.href = url;
    }
    document.getElementById('logoutButton').addEventListener('click', function() {
        localStorage.clear();
        window.location.href = url;
    });

    async function fetchProducts() {
        const storeId = @json($id);
        const productsGrid = document.getElementById('productsGrid');
        productsGrid.innerHTML = '';

        try {
            const response = await fetch(`${url}/api/store/${storeId}`, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${accessToken}`
                }
            });

            if (!response.ok) {
                console.error('Error fetching products:', response.statusText);
                return;
            }

            const data = await response.json();
            const products = data.products;

            products.forEach(product => {
                const productCard = document.createElement('div');
                productCard.className = 'col-md-4 mb-4';
                productCard.innerHTML = `
                <div class="card product-card">
                    <img src="${url}/${product.image}" alt="${product.name}">
                    <div class="card-body">
                        <h5 class="card-title">${product.name}</h5>
                        <h5 class="card-title">
                            <div class="d-flex justify-content-between align-items-center">
                                <span style="color: green">${product.price}$</span>
                                <span>Quantity: ${product.quantity}</span>
                            </div>
                        </h5>
                    </div>
                </div>
            `;
                productsGrid.appendChild(productCard);
            });

        } catch (error) {
            console.error('Error fetching products:', error);
        }
    }

    document.getElementById('searchBar').addEventListener('input', function (e) {
        const searchQuery = e.target.value.toLowerCase();
        const products = document.querySelectorAll('#productsGrid .card');

        products.forEach(product => {
            const productName = product.querySelector('.card-title').textContent.toLowerCase();
            if (productName.includes(searchQuery)) {
                product.parentElement.style.display = 'block';
            } else {
                product.parentElement.style.display = 'none';
            }
        });
    });

    document.getElementById('addDetailButton').addEventListener('click', function () {
        const key = document.getElementById('detailKey').value.trim();
        const value = document.getElementById('detailValue').value.trim();

        if (key && value) {
            details.push({key,value});
            details2[key] = value;
            updateDetailsList();
            document.getElementById('detailKey').value = '';
            document.getElementById('detailValue').value = '';
        } else {
            alert('Please enter both key and value.');
        }
    });

    function updateDetailsList() {
        const detailsList = document.getElementById('detailsList');
        detailsList.innerHTML = '';

        details.forEach((detail, index) => {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center';
            li.textContent = `${detail.key}: ${detail.value}`;
            const removeButton = document.createElement('button');
            removeButton.className = 'btn btn-danger btn-sm';
            removeButton.textContent = 'Remove';
            removeButton.onclick = () => {
                details.splice(index, 1);
                updateDetailsList();
            };
            li.appendChild(removeButton);
            detailsList.appendChild(li);
        });
    }

    document.getElementById('addProductForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const storeId = @json($id);
        const productName = document.getElementById('productName').value;
        const productPrice = document.getElementById('productPrice').value;
        const productQuantity = document.getElementById('productQuantity').value;
        const productDescription = document.getElementById('productDescription').value;
        const productImageInput = document.getElementById('productImage');
        const additionalImagesInput = document.getElementById('additionalImages');

        if (details.length === 0) {
            alert('Please add at least one detail before submitting the product.');
            return;
        }

        const form = e.target;
        const formData = new FormData(form);
        formData.append('store_id', storeId);
        formData.append('name', productName);
        formData.append('price', productPrice);
        formData.append('quantity', productQuantity);
        formData.append('description', productDescription);
        details.forEach((detail, index) => {
            formData.append(`details[${index}][key]`, detail.key);
            formData.append(`details[${index}][value]`, detail.value);
        });

        if (productImageInput.files[0]) {
            formData.append('main_image', productImageInput.files[0]);
        }

        if (additionalImagesInput.files.length > 0) {
            Array.from(additionalImagesInput.files).forEach(file => {
                formData.append('additional_images[]', file);
            });
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        try {
            const response = await fetch(`${url}/api/addProduct`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${accessToken}`,
                },
                body: formData,
            });

            if (response.ok) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
                modal.hide();
                fetchProducts();
                details.length = 0;
                updateDetailsList();
            } else if (response.status === 440){
                alert(`Access denied`);
                localStorage.clear();
                window.location.href = url;
            }
            else {
                const errorData = await response.json();
                console.error('Error adding product:', errorData);
                alert(`Error: ${errorData.message}`);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    fetchProducts();
</script>
</body>
</html>
