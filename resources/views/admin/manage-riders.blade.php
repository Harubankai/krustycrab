<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Riders</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
<nav class="sidebar">
    <div class="brand">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logoImg">
        <span class="brand-title">ADMIN</span>
    </div>
    <ul class="menu">
        <li><a href="{{ route('admin.dashboard') }}"><i class='bx bx-trending-up'></i> Dashboard </a></li>
        <li><a href="{{ route('admin.manage-riders') }}" class="active"><i class='bx bx-group'></i> Manage Riders </a></li>
        <li><a href="{{ route('admin.modify-menu') }}"><i class="bx bx-edit"></i> Modify Menu </a></li>
    </ul>
    <div class="logout">
        <a href="{{ route('logout') }}" class="logout-btn" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
    </div>
</nav>

<main class="main-content">
    <header class="header">
        <div class="header-row">
            <div class="header-text">
                <h1>Manage Riders</h1>
                <p>Quick view of active riders and their current availability.</p>
            </div>
            <button class="btn btn-primary" id="addRiderBtn">Add Rider</button>
        </div>
    </header>

    <section class="recent-orders" style="margin-top: 10px;">
        <h2>Search and Filter</h2>
        <div class="filters-row">
            <div class="form-row">
                <label for="riderSearch">Search Rider</label>
                <input type="text" id="riderSearch" placeholder="Search by name, ID, or phone">
            </div>
            <div class="form-row">
                <label for="statusFilter">Status Filter</label>
                <select id="statusFilter">
                    <option value="All">All</option>
                    <option value="Available">Available</option>
                    <option value="On Delivery">On Delivery</option>
                    <option value="Offline">Offline</option>
                </select>
            </div>
        </div>
    </section>

    <section class="recent-orders" style="margin-top: 30px;">
        <h2>Rider List</h2>
        <table>
            <thead>
            <tr>
                <th>Rider ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody id="ridersTableBody">
                <!-- Riders dynamically loaded via JS -->
            </tbody>
        </table>
    </section>
</main>

<!-- Details Modal -->
<div class="modal-overlay" id="detailsModal">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="detailsTitle">
        <h3 id="detailsTitle">Rider Details</h3>
        <div class="modal-body">
            <p><strong>Rider ID:</strong> <span id="detailId">-</span></p>
            <p><strong>Name:</strong> <span id="detailName">-</span></p>
            <p><strong>Email:</strong> <span id="detailEmail">-</span></p>
            <p><strong>Temp Password:</strong> <span id="detailTempPassword">-</span></p>
            <p><strong>Phone:</strong> <span id="detailPhone">-</span></p>
            <p><strong>Status:</strong> <span id="detailStatus">-</span></p>
            <p><strong>Vehicle:</strong> <span id="detailVehicle">-</span></p>
            <p><strong>Last Delivery:</strong> <span id="detailLast">-</span></p>
        </div>
        <div class="modal-actions">
            <button class="btn btn-secondary" data-close="detailsModal">Close</button>
            <button class="btn btn-danger" id="removeRiderBtn">Remove As a Rider</button>
        </div>
    </div>
</div>

<!-- Add Rider Modal -->
<div class="modal-overlay" id="addRiderModal">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle">
        <h3 id="addTitle">Add Rider</h3>
        <form id="addRiderForm">
            <div class="modal-body">
                <div class="form-row">
                    <label for="riderName">Name</label>
                    <input type="text" id="riderName" name="riderName" required>
                </div>
                <div class="form-row">
                    <label for="riderEmail">Email</label>
                    <input type="email" id="riderEmail" name="riderEmail" autocomplete="email" required>
                </div>
                <div class="form-row">
                    <label for="riderTempPassword">Temporary Password</label>
                    <input type="text" id="riderTempPassword" name="riderTempPassword" autocomplete="new-password" required>
                </div>
                <div class="form-row">
                    <label for="riderPhone">Phone</label>
                    <input type="text" id="riderPhone" name="riderPhone" required>
                </div>
                <div class="form-row">
                    <label for="riderStatus">Status</label>
                    <select id="riderStatus" name="riderStatus" required>
                        <option value="Available">Available</option>
                        <option value="On Delivery">On Delivery</option>
                        <option value="Offline">Offline</option>
                    </select>
                </div>
                <div class="form-row">
                    <label for="riderVehicle">Vehicle</label>
                    <input type="text" id="riderVehicle" name="riderVehicle" required>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" data-close="addRiderModal">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Rider</button>
            </div>
        </form>
    </div>
</div>

<script>
    const detailsModal = document.getElementById('detailsModal');
    const addRiderModal = document.getElementById('addRiderModal');
    const addRiderBtn = document.getElementById('addRiderBtn');
    const ridersTableBody = document.getElementById('ridersTableBody');
    const removeRiderBtn = document.getElementById('removeRiderBtn');
    const addRiderForm = document.getElementById('addRiderForm');
    const riderSearch = document.getElementById('riderSearch');
    const statusFilter = document.getElementById('statusFilter');
    let currentRow = null;

    const openModal = modal => modal.classList.add('active');
    const closeModal = modal => modal.classList.remove('active');
    const getStatusClass = status => status === 'Available' ? 'status-available' : status === 'On Delivery' ? 'status-delivery' : 'status-offline';

    [detailsModal, addRiderModal].forEach(modal => modal.addEventListener('click', e => {
    if(e.target===modal) closeModal(modal);
}));

// NEW: Close buttons
document.querySelectorAll('[data-close]').forEach(btn => {
    btn.addEventListener('click', () => {
        const modal = document.getElementById(btn.getAttribute('data-close'));
        if(modal) closeModal(modal);
    });
});
    // Fetch all riders from backend
 const fetchRiders = async () => {
    try {
        const res = await fetch("{{ route('admin.riders.list') }}");
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        const data = await res.json();
        ridersTableBody.innerHTML = '';

        if (data.length === 0) {
            ridersTableBody.innerHTML = '<tr><td colspan="5">No riders found</td></tr>';
            return;
        }

        data.forEach(rider => {
            const tr = document.createElement('tr');
            // Add data-* attributes for filters & modal
            tr.dataset.id = rider.id;
            tr.dataset.name = rider.name;
            tr.dataset.email = rider.email;
            tr.dataset.phone = rider.phone || '';
            tr.dataset.status = rider.status;
            tr.dataset.tempPassword = rider.tempPassword || '';
            tr.dataset.vehicle = rider.vehicle || '';
            tr.dataset.last = rider.last || '';

            tr.innerHTML = `
                <td>${rider.id}</td>
                <td>${rider.name}</td>
                <td class="${getStatusClass(rider.status)}">${rider.status}</td>
                <td>${rider.phone || '-'}</td>
                <td><a href="#" class="view-details">View</a></td>
            `;
            ridersTableBody.appendChild(tr);
        });

        applyFilters(); // reapply filters after fetching
    } catch (err) {
        console.error('Failed to fetch riders:', err);
        ridersTableBody.innerHTML = '<tr><td colspan="5">Unable to load riders</td></tr>';
    }
};

    // Filters
    const applyFilters = () => {
        const query = riderSearch.value.trim().toLowerCase();
        const status = statusFilter.value;
        Array.from(ridersTableBody.querySelectorAll('tr')).forEach(row => {
            const haystack = [row.dataset.id,row.dataset.name,row.dataset.email,row.dataset.phone,row.dataset.status].join(' ').toLowerCase();
            row.style.display = ((!query || haystack.includes(query)) && (status === 'All' || row.dataset.status === status)) ? '' : 'none';
        });
    };

    addRiderBtn.addEventListener('click', () => openModal(addRiderModal));
    riderSearch.addEventListener('input', applyFilters);
    statusFilter.addEventListener('change', applyFilters);

    // Open details modal
    ridersTableBody.addEventListener('click', e => {
        const link = e.target.closest('.view-details');
        if (!link) return;
        e.preventDefault();
        currentRow = link.closest('tr');
        ['Id','Name','Email','TempPassword','Phone','Status','Vehicle','Last'].forEach(k => document.getElementById(`detail${k}`).textContent = currentRow.dataset[k.toLowerCase()] || '-');
        openModal(detailsModal);
    });

    removeRiderBtn.addEventListener('click', () => {
        if(currentRow) currentRow.remove();
        currentRow=null; closeModal(detailsModal);
    });

    addRiderForm.addEventListener('submit', e => {
        e.preventDefault();
        const payload = {
            name: document.getElementById('riderName').value.trim(),
            email: document.getElementById('riderEmail').value.trim(),
            password: document.getElementById('riderTempPassword').value.trim(),
            phone: document.getElementById('riderPhone').value.trim(),
        };
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch("{{ route('admin.riders.store') }}", {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},
            body: JSON.stringify(payload)
        }).then(res => res.json()).then(data => {
            if(data.ok){
                alert(`Rider ${payload.name} added successfully.\nEmail: ${payload.email}\nTemp Password: ${payload.password}`);
                fetchRiders();
                addRiderForm.reset();
                closeModal(addRiderModal);
            } else alert(data.message || 'Unable to add rider');
        });
    });

    [detailsModal, addRiderModal].forEach(modal => modal.addEventListener('click', e => {if(e.target===modal) closeModal(modal);}));

    // Initial load
    fetchRiders();
    
</script>
</body>
</html>