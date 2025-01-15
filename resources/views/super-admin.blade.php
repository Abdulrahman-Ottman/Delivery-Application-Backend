<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Store Management</title>
{{--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">--}}
    <link rel="stylesheet" href="{{ url('bootstrap.min.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f5f9;
        }
        .store-card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 10px;
        }

        .store-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .store-card:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
    </style>
</head>

<body>
<div class="container mt-5">
    <div class="row justify-content-between">
        <div class="col-md-6">
            <input type="text" class="form-control" id="searchBar" placeholder="Search stores...">
        </div>
        <div class="col-md-4 text-end d-flex justify-content-end gap-3">
            <button class="btn btn-success btn-wide" data-bs-toggle="modal" data-bs-target="#addStoreModal">Add Store</button>
            <button class="btn btn-warning btn-wide" id="pendingOrdersButton" style="color:white">View Pending Orders</button>
            <button class="btn btn-danger btn-wide" id="logoutButton">Logout</button>
        </div>
    </div>

    <div class="row mt-4" id="storesGrid">
    </div>
</div>

<div class="modal fade" id="addStoreModal" tabindex="-1" aria-labelledby="addStoreModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStoreModalLabel">Add New Store</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addStoreForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="storeName" class="form-label">Store Name</label>
                        <input type="text" class="form-control" id="storeName" required>
                    </div>
                    <div class="mb-3">
                        <label for="adminId" class="form-label">Admin ID</label>
                        <input type="number" class="form-control" id="adminId" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="storeImage" class="form-label">Store Image</label>
                        <input type="file" class="form-control" id="storeImage" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <select class="form-select" id="country" required>
                            <option value="" disabled selected>Select a country</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <select class="form-select" id="city" required>
                            <option value="" disabled selected>Select a city</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Store</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{url('bootstrap.bundle.min.js')}}"></script>
<script>
    const url = 'http://192.168.218.21:8000';
    const accessToken = localStorage.getItem('access_token');
    if (!accessToken){
        localStorage.clear();
        window.location.href = url;
    }
    const role = JSON.parse(localStorage.getItem('user'))['role'];
    if (role !='superAdmin'){
        localStorage.clear();
        window.location.href = url;
    }
    const countriesData = {
        'USA': ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix'],
        'Canada': ['Toronto', 'Vancouver', 'Montreal', 'Calgary', 'Ottawa'],
        'UK': ['London', 'Manchester', 'Birmingham', 'Liverpool', 'Edinburgh'],
        'Australia': ['Sydney', 'Melbourne', 'Brisbane', 'Perth', 'Adelaide'],
        'Germany': ['Berlin', 'Munich', 'Frankfurt', 'Hamburg', 'Cologne'],
        'France': ['Paris', 'Marseille', 'Lyon', 'Toulouse', 'Nice'],
        'Italy': ['Rome', 'Milan', 'Naples', 'Turin', 'Palermo'],
        'Spain': ['Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Bilbao'],
        'Mexico': ['Mexico City', 'Guadalajara', 'Monterrey', 'Puebla', 'Cancún'],
        'Brazil': ['São Paulo', 'Rio de Janeiro', 'Salvador', 'Brasília', 'Fortaleza'],
        'Russia': ['Moscow', 'Saint Petersburg', 'Novosibirsk', 'Yekaterinburg', 'Kazan'],
        'China': ['Beijing', 'Shanghai', 'Guangzhou', 'Shenzhen', 'Chengdu'],
        'Japan': ['Tokyo', 'Osaka', 'Yokohama', 'Nagoya', 'Sapporo'],
        'India': ['New Delhi', 'Mumbai', 'Bangalore', 'Kolkata', 'Chennai'],
        'South Africa': ['Johannesburg', 'Cape Town', 'Durban', 'Pretoria', 'Port Elizabeth'],
        'Saudi Arabia': ['Riyadh', 'Jeddah', 'Mecca', 'Dammam', 'Khobar'],
        'Turkey': ['Istanbul', 'Ankara', 'Izmir', 'Bursa', 'Antalya'],
        'Sweden': ['Stockholm', 'Gothenburg', 'Malmo', 'Uppsala', 'Västerås'],
        'Norway': ['Oslo', 'Bergen', 'Stavanger', 'Drammen', 'Kristiansand'],
        'Finland': ['Helsinki', 'Espoo', 'Tampere', 'Vantaa', 'Oulu'],
        'Denmark': ['Copenhagen', 'Aarhus', 'Odense', 'Aalborg', 'Esbjerg'],
        'Netherlands': ['Amsterdam', 'Rotterdam', 'The Hague', 'Utrecht', 'Eindhoven'],
        'Belgium': ['Brussels', 'Antwerp', 'Ghent', 'Bruges', 'Liège'],
        'Switzerland': ['Zurich', 'Geneva', 'Bern', 'Basel', 'Lausanne'],
        'Austria': ['Vienna', 'Graz', 'Linz', 'Salzburg', 'Innsbruck'],
        'Ireland': ['Dublin', 'Cork', 'Limerick', 'Galway', 'Waterford'],
        'Portugal': ['Lisbon', 'Porto', 'Braga', 'Coimbra', 'Aveiro'],
        'Greece': ['Athens', 'Thessaloniki', 'Patras', 'Heraklion', 'Larissa'],
        'Czech Republic': ['Prague', 'Brno', 'Ostrava', 'Plzeň', 'Liberec'],
        'Hungary': ['Budapest', 'Debrecen', 'Szeged', 'Miskolc', 'Pécs'],
        'Romania': ['Bucharest', 'Cluj-Napoca', 'Timișoara', 'Iași', 'Constanța'],
        'Bulgaria': ['Sofia', 'Plovdiv', 'Varna', 'Burgas', 'Ruse'],
        'Ukraine': ['Kyiv', 'Lviv', 'Odessa', 'Kharkiv', 'Dnipro'],
        'Serbia': ['Belgrade', 'Novi Sad', 'Niš', 'Kragujevac', 'Subotica'],
        'Slovakia': ['Bratislava', 'Košice', 'Prešov', 'Nitra', 'Trnava'],
        'Croatia': ['Zagreb', 'Split', 'Rijeka', 'Osijek', 'Zadar'],
        'Slovenia': ['Ljubljana', 'Maribor', 'Celje', 'Kranj', 'Novo mesto'],
        'Malaysia': ['Kuala Lumpur', 'George Town', 'Johor Bahru', 'Malacca', 'Ipoh'],
        'Thailand': ['Bangkok', 'Chiang Mai', 'Phuket', 'Pattaya', 'Nakhon Ratchasima'],
        'Indonesia': ['Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Bali'],
        'Philippines': ['Manila', 'Cebu City', 'Davao City', 'Quezon City', 'Zamboanga City'],
        'Vietnam': ['Hanoi', 'Ho Chi Minh City', 'Da Nang', 'Hai Phong', 'Nha Trang'],
        'Syria': ['Damascus', 'Aleppo', 'Homes', 'Latakia', 'Tartus'],
        'Pakistan': ['Karachi', 'Lahore', 'Islamabad', 'Faisalabad', 'Rawalpindi'],
        'Bangladesh': ['Dhaka', 'Chittagong', 'Khulna', 'Rajshahi', 'Sylhet'],
        'Nepal': ['Kathmandu', 'Pokhara', 'Lalitpur', 'Bhaktapur', 'Biratnagar'],
        'Sri Lanka': ['Colombo', 'Kandy', 'Galle', 'Jaffna', 'Negombo'],
    };

    const countrySelect = document.getElementById('country');
    Object.keys(countriesData).forEach(country => {
        const option = document.createElement('option');
        option.value = country;
        option.textContent = country;
        countrySelect.appendChild(option);
    });

    countrySelect.addEventListener('change', function () {
        const citySelect = document.getElementById('city');
        citySelect.innerHTML = '<option value="" disabled selected>Select a city</option>';
        const selectedCountry = this.value;
        const cities = countriesData[selectedCountry] || [];
        cities.forEach(city => {
            const option = document.createElement('option');
            option.value = city;
            option.textContent = city;
            citySelect.appendChild(option);
        });
    });
    document.getElementById('pendingOrdersButton').addEventListener('click', async function () {
        try {
            const response = await fetch(`${url}/api/pending-orders`, {
                headers: {
                    'Authorization': `Bearer ${accessToken}`,
                    'Accept': 'application/json',
                }
            });
            if(response.status === 404)
                alert('No pending orders!');
            else if (!response.ok) {
                console.error('Error fetching pending orders:', response.statusText);
                alert('Failed to retrieve pending orders.');
                return;
            }

            const data = await response.json();
            displayPendingOrders(data.orders);

        } catch (error) {
            console.error('Error fetching pending orders:', error);
        }
    });

    function displayPendingOrders(orders) {
        const modalContent = `
    <div class="modal fade" id="pendingOrdersModal" tabindex="-1" aria-labelledby="pendingOrdersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pendingOrdersModalLabel">Pending Orders</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        ${orders.map(order => `
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>Order ID:</strong> ${order.id} <br>
                                        <strong>Status:</strong> ${order.status} <br>
                                        <strong>User name:</strong> ${order.user.first_name}
                                    </div>
                                    <div>
                                        <button class="btn btn-success btn-sm" onclick="changeOrderStatus(${order.id}, 'on_way')">Accept</button>
                                        <button class="btn btn-danger btn-sm" onclick="changeOrderStatus(${order.id}, 'rejected')">Reject</button>
                                    </div>
                                </div>
                                <hr>
                                <ul class="list-group mt-2">
                                    ${order.products.map(product => `
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>${product.name}</strong> (Quantity: ${product.pivot.ordered_quantity})
                                                <br>
                                                <small>Price: $${product.price} - Store name: ${product.store.name}</small>
                                            </div>
                                        </li>
                                    `).join('')}
                                </ul>
                            </li>
                        `).join('')}
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    `;

        if (document.getElementById('pendingOrdersModal')) {
            document.getElementById('pendingOrdersModal').remove();
        }

        document.body.insertAdjacentHTML('beforeend', modalContent);

        const modal = new bootstrap.Modal(document.getElementById('pendingOrdersModal'));
        modal.show();
    }

    document.getElementById('logoutButton').addEventListener('click', function() {
        localStorage.clear();
        window.location.href = url;
    });
    async function changeOrderStatus(orderId, status) {
        try {
            if(status=='on_way'){
                const response = await fetch(`${url}/api/accept-order`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${accessToken}`,
                    },
                    body: JSON.stringify({ order_id: orderId}),
                });
                if (response.ok) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('pendingOrdersModal'));
                    modal.hide();
                    document.getElementById('pendingOrdersButton').click();
                } else {
                    console.error('Error changing order status:', response.statusText);
                    alert('Failed to change order status.');
                }
            }
            else if(status=='rejected'){
                const response = await fetch(`${url}/api/cancel-order`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${accessToken}`,
                    },
                    body: JSON.stringify({ order_id: orderId}),
                });
                if (response.ok) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('pendingOrdersModal'));
                    modal.hide();
                    document.getElementById('pendingOrdersButton').click();
                } else {
                    console.error('Error changing order status:', response.statusText);
                    alert('Failed to change order status.');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while changing order status.');
        }
    }
    async function fetchStores() {
        let currentPage = 1;
        const storesGrid = document.getElementById('storesGrid');
        storesGrid.innerHTML = '';

        while (currentPage) {
            try {
                const response = await fetch(`${url}/api/getStores?page=${currentPage}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${accessToken}`,
                    }
                });

                if (!response.ok) {
                    console.error('Error fetching stores:', response.statusText);
                    break;
                }

                const data = await response.json();

                data.data.data.forEach(store => {
                    const storeCard = document.createElement('div');
                    storeCard.className = 'col-md-4 mb-4';
                    storeCard.innerHTML = `
                    <div class="card store-card">
                        <img src="${`${url}/${store.image}`}" alt="${store.name}">
                        <div class="card-body">
                            <h5 class="card-title">${store.name}</h5>
                            <button class="btn btn-outline-primary" onclick="window.location.href='/admin/dashboard/${store.id}'">View Store</button>
                        </div>
                    </div>
                `;
                    storesGrid.appendChild(storeCard);
                });

                currentPage = data.links && data.links.next ? currentPage + 1 : null;
            } catch (error) {
                console.error('Error fetching stores:', error);
                break;
            }
        }
    }

    document.getElementById('searchBar').addEventListener('input', function (e) {
        const searchQuery = e.target.value.toLowerCase();
        const stores = document.querySelectorAll('#storesGrid .card');

        stores.forEach(store => {
            const storeName = store.querySelector('.card-title').textContent.toLowerCase();
            if (storeName.includes(searchQuery)) {
                store.parentElement.style.display = 'block';
            } else {
                store.parentElement.style.display = 'none';
            }
        });
    });

    document.getElementById('addStoreForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const adminId = document.getElementById('adminId').value;
        const storeName = document.getElementById('storeName').value;
        const storeImageInput = document.getElementById('storeImage');
        const city = document.getElementById('city').value;
        const country = document.getElementById('country').value;

        const formData = new FormData();
        formData.append('user_id', adminId);
        formData.append('name', storeName);
        formData.append('image', storeImageInput.files[0]);
        const location = { city: city, country: country };
        formData.append('location',JSON.stringify(location));
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        try {
            const response = await fetch(`${url}/api/addStore`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${accessToken}`,
                },
                body: formData
            });

            if (response.ok) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('addStoreModal'));
                modal.hide();
                fetchStores();
            }
            else if(response.status===422||response.status===401) {
                alert('Admin id not correct or not available');
                console.error('Incorrect admin id.', 422);
            }
            else {
                console.error('Error adding store:', response.statusText);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    })

    fetchStores();
</script>
</body>
</html>
