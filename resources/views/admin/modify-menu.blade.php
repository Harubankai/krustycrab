<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Menu</title>
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
            <li><a href="{{ route('admin.dashboard') }}"><i class='bx bx-trending-up'></i>   Dashboard </a></li>
            <li><a href="{{ route('admin.manage-riders') }}"><i class='bx bx-group'></i>   Manage Riders </a></li>
            <li><a href="{{ route('admin.modify-menu') }}" class="active"><i class="bx bx-edit"></i>   Modify Menu </a></li>
        </ul>

        <div class="logout">
            <a href="{{ route('logout') }}" class="logout-btn" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
        </div>
    </nav>

    <main class="main-content">
        <header class="header">
            <div class="header-row">
                <div class="header-text">
                    <h1>Modify Menu</h1>
                    <p>Update menu items, pricing, and availability in one place.</p>
                </div>
                <div class="toolbar-actions">
                    <button class="btn btn-primary" id="addMenuBtn">+ Add Menu Item</button>
                </div>
            </div>
        </header>

        <section class="cards">
            <div class="card">
                <h3>Total Items</h3>
                <p id="totalCount">0</p>
            </div>
            <div class="card">
                <h3>Meals</h3>
                <p id="mealCount">0</p>
            </div>
            <div class="card">
                <h3>Drinks</h3>
                <p id="drinkCount">0</p>
            </div>
        </section>

        <section class="toolbar">
            <div class="filters-row" style="width: 100%;">
                <div class="form-row">
                    <label for="adminSearch">Search Menu</label>
                    <input type="text" id="adminSearch" placeholder="Search by food name">
                </div>
                <div class="form-row">
                    <label for="adminCategory">Category</label>
                    <select id="adminCategory">
                        <option value="all">All</option>
                        <option value="meal">Meal</option>
                        <option value="drinks">Drinks</option>
                    </select>
                </div>
            </div>
        </section>

        <p class="empty-state" id="emptyState" hidden>No menu items yet. Click \"+ Add Menu Item\" to start.</p>
        <section class="menu-grid" id="adminMenuGrid"></section>
    </main>

    <div class="modal-overlay" id="addMenuModal" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addMenuTitle">
            <h3 id="addMenuTitle">Add Menu Item</h3>
            <form id="addMenuForm">
                <div class="form-row">
                    <label for="addName">Food Name</label>
                    <input id="addName" type="text" required>
                </div>
                <div class="form-row">
                    <label for="addCategory">Category</label>
                    <select id="addCategory" required>
                        <option value="meal">Meal</option>
                        <option value="drinks">Drinks</option>
                    </select>
                </div>
                <div class="form-row">
                    <label for="addDescription">Description</label>
                    <input id="addDescription" type="text" required>
                </div>
                <div class="form-row">
                    <label for="addPrice">Price</label>
                    <input id="addPrice" class="no-spinner" type="number" step="0.01" min="0" required>
                </div>
                <div class="form-row">
                    <label for="addImageFile">Upload Photo</label>
                    <input id="addImageFile" type="file" accept="image/*">
                    <small class="muted">Optional. PNG/JPG recommended.</small>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" data-close>Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Item</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="editMenuModal" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="editMenuTitle">
            <h3 id="editMenuTitle">Edit Menu Item</h3>
            <form id="editMenuForm">
                <input type="hidden" id="editId">
                <div class="form-row">
                    <label for="editName">Food Name</label>
                    <input id="editName" type="text" required>
                </div>
                <div class="form-row">
                    <label for="editCategory">Category</label>
                    <select id="editCategory" required>
                        <option value="meal">Meal</option>
                        <option value="drinks">Drinks</option>
                    </select>
                </div>
                <div class="form-row">
                    <label for="editDescription">Description</label>
                    <input id="editDescription" type="text" required>
                </div>
                <div class="form-row">
                    <label for="editPrice">Price</label>
                    <input id="editPrice" class="no-spinner" type="number" step="0.01" min="0" required>
                </div>
                <div class="form-row">
                    <label>Recent Photo</label>
                    <img id="editImagePreview" class="image-preview" alt="Menu item photo preview">
                </div>
                <div class="form-row">
                    <label for="editImageFile">Upload New Photo</label>
                    <input id="editImageFile" type="file" accept="image/*">
                    <small class="muted">Leave empty to keep the current photo.</small>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" data-close>Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="deleteMenuModal" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="deleteMenuTitle">
            <h3 id="deleteMenuTitle">Delete Menu Item</h3>
            <div class="modal-body">
                <p id="deleteMenuText">Are you sure you want to delete this menu item?</p>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" data-close>Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>

    <script>
        const MENU_STORAGE_KEY = 'kk_menu_items_v1';
        const DEFAULT_IMAGE_URL = "{{ asset('images/coralbits.jpg') }}";

        const DEFAULT_MENU_ITEMS = [
            {
                id: 'krabby-patty',
                name: 'Krabby Patty',
                category: 'meal',
                description: 'The legendary signature burger of the Krusty Krab',
                price: 45,
                image: "{{ asset('images/krabpat.jpg') }}",
            },
            {
                id: 'double-krabby-patty',
                name: 'Double Krabby Patty',
                category: 'meal',
                description: 'Two juicy patties stacked with sea-fresh toppings.',
                price: 55,
                image: "{{ asset('images/double_kp.jpg') }}",
            },
            {
                id: 'triple-krabby-patty',
                name: 'Triple Krabby Patty',
                category: 'meal',
                description: 'Three layers of the legendary Krabby Patty flavor.',
                price: 65,
                image: "{{ asset('images/triple_kp.jpg') }}",
            },
            {
                id: 'coral-bits-small',
                name: 'Coral Bits (Small)',
                category: 'meal',
                description: 'Crispy bite-sized coral bits, perfect snack size.',
                price: 30,
                image: "{{ asset('images/small_cb.png') }}",
            },
            {
                id: 'coral-bits-medium',
                name: 'Coral Bits (Medium)',
                category: 'meal',
                description: 'Golden coral bits in a medium sharing-size basket.',
                price: 40,
                image: "{{ asset('images/medium_cb.png') }}",
            },
            {
                id: 'coral-bits-large',
                name: 'Coral Bits (Large)',
                category: 'meal',
                description: 'Large crispy coral bits for the hungriest customers.',
                price: 50,
                image: "{{ asset('images/large_cb.png') }}",
            },
            {
                id: 'kelp-shake-small',
                name: 'Kelp Shake (Small)',
                category: 'drinks',
                description: 'Smooth and cool kelp shake in a small cup.',
                price: 35,
                image: "{{ asset('images/kelpshake.jpg') }}",
            },
            {
                id: 'kelp-shake-medium',
                name: 'Kelp Shake (Medium)',
                category: 'drinks',
                description: 'Refreshing kelp shake for a satisfying sip.',
                price: 45,
                image: "{{ asset('images/kelpshake.jpg') }}",
            },
            {
                id: 'kelp-shake-large',
                name: 'Kelp Shake (Large)',
                category: 'drinks',
                description: 'Big-size kelp shake for maximum refreshment.',
                price: 55,
                image: "{{ asset('images/kelpshake.jpg') }}",
            },
            {
                id: 'seafoam-soda-small',
                name: 'Seafoam Soda (Small)',
                category: 'drinks',
                description: 'Fizzing seafoam soda in a small chilled cup.',
                price: 35,
                image: "{{ asset('images/seafoam.jpg') }}",
            },
            {
                id: 'seafoam-soda-medium',
                name: 'Seafoam Soda (Medium)',
                category: 'drinks',
                description: 'Classic bubbly seafoam soda for everyday meals.',
                price: 40,
                image: "{{ asset('images/seafoam.jpg') }}",
            },
            {
                id: 'seafoam-soda-large',
                name: 'Seafoam Soda (Large)',
                category: 'drinks',
                description: 'Large sparkling seafoam soda to complete your order.',
                price: 45,
                image: "{{ asset('images/seafoam.jpg') }}",
            },
        ];

        const menuGrid = document.getElementById('adminMenuGrid');
        const emptyState = document.getElementById('emptyState');
        const searchInput = document.getElementById('adminSearch');
        const categoryFilter = document.getElementById('adminCategory');
        const totalCount = document.getElementById('totalCount');
        const mealCount = document.getElementById('mealCount');
        const drinkCount = document.getElementById('drinkCount');

        const addModal = document.getElementById('addMenuModal');
        const editModal = document.getElementById('editMenuModal');
        const deleteModal = document.getElementById('deleteMenuModal');
        const deleteMenuText = document.getElementById('deleteMenuText');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        let menuItems = [];
        let deleteTargetId = null;

        function formatPrice(value) {
            const price = Number(value) || 0;
            return `\u20B1${price.toFixed(2)}`;
        }

        function updateCounts(items) {
            const total = items.length;
            const meals = items.filter((item) => item.category === 'meal').length;
            const drinks = items.filter((item) => item.category === 'drinks').length;
            totalCount.textContent = String(total);
            mealCount.textContent = String(meals);
            drinkCount.textContent = String(drinks);
        }

        function openModal(modal) {
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
        }

        function closeModal(modal) {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }

        function closeAllModals() {
            [addModal, editModal, deleteModal].forEach(closeModal);
        }

        function readFileAsDataUrl(file) {
            return new Promise((resolve) => {
                if (!file) return resolve('');
                const reader = new FileReader();
                reader.onload = () => resolve(String(reader.result || ''));
                reader.onerror = () => resolve('');
                reader.readAsDataURL(file);
            });
        }

        function setImagePreview(imgEl, src) {
            if (!imgEl) return;
            if (src) {
                imgEl.src = src;
                imgEl.style.display = 'block';
            } else {
                imgEl.src = '';
                imgEl.style.display = 'none';
            }
        }

        function getFilteredItems() {
            const query = searchInput.value.toLowerCase().trim();
            const category = categoryFilter.value;
            return menuItems.filter((item) => {
                const matchesSearch = item.name.toLowerCase().includes(query);
                const matchesCategory = category === 'all' || item.category === category;
                return matchesSearch && matchesCategory;
            });
        }

        function renderMenu(items) {
            menuGrid.innerHTML = '';
            if (!items.length) {
                emptyState.hidden = false;
                return;
            }
            emptyState.hidden = true;

            items.forEach((item) => {
                const card = document.createElement('article');
                card.className = 'menu-card';
                card.dataset.id = item.id;

                const imageHtml = item.image ? `<img src="${item.image}" alt="${item.name}">` : '';

                card.innerHTML = `
                    ${imageHtml}
                    <div class="menu-card-content">
                        <div class="menu-card-meta">
                            <span class="tag">${item.category}</span>
                            <span class="menu-price">${formatPrice(item.price)}</span>
                        </div>
                        <h3>${item.name}</h3>
                        <p>${item.description}</p>
                        <div class="menu-actions">
                            <button class="btn btn-secondary btn-small" data-action="edit">Edit</button>
                            <button class="btn btn-danger btn-small" data-action="delete">Delete</button>
                        </div>
                    </div>
                `;

                menuGrid.appendChild(card);
            });
        }

        function refreshView() {
            const filtered = getFilteredItems();
            renderMenu(filtered);
            updateCounts(menuItems);
        }

        document.getElementById('addMenuBtn').addEventListener('click', () => {
            document.getElementById('addMenuForm').reset();
            openModal(addModal);
        });

        // ADD ITEM API
        document.getElementById('addMenuForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const name = document.getElementById('addName').value.trim();
            const category = document.getElementById('addCategory').value;
            const description = document.getElementById('addDescription').value.trim();
            const price = Number(document.getElementById('addPrice').value) || 0;
            const addImageFile = document.getElementById('addImageFile')?.files?.[0] || null;
            const image = await readFileAsDataUrl(addImageFile);

            if (!name || !description) return;

            const submitBtn = event.target.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';

            try {
                const res = await fetch('/api/menu', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ name, category, description, price, image })
                });
                const data = await res.json();
                if(data.success && data.item) {
                    menuItems.push(data.item);
                    closeModal(addModal);
                    refreshView();
                }
            } catch(e) {
                console.error('Failed to save', e);
                alert("Failed to save to database!");
            }

            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Item';
        });

        // EDIT ITEM API
        document.getElementById('editMenuForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const id = document.getElementById('editId').value;
            const name = document.getElementById('editName').value.trim();
            const category = document.getElementById('editCategory').value;
            const description = document.getElementById('editDescription').value.trim();
            const price = Number(document.getElementById('editPrice').value) || 0;
            const editImageFile = document.getElementById('editImageFile')?.files?.[0] || null;

            const index = menuItems.findIndex((item) => String(item.id) === String(id));
            if (index === -1) return;

            const submitBtn = event.target.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';

            try {
                const uploadedImage = await readFileAsDataUrl(editImageFile);
                const nextImage = uploadedImage || menuItems[index].image || '';

                const res = await fetch(`/api/menu/${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ name, category, description, price, image: nextImage })
                });

                const data = await res.json();
                if(data.success && data.item) {
                    menuItems[index] = data.item;
                    closeModal(editModal);
                    refreshView();
                }
            } catch(e) {
                console.error('Failed to update', e);
            }

            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Changes';
        });

        menuGrid.addEventListener('click', (event) => {
            const button = event.target.closest('button[data-action]');
            if (!button) return;

            const card = button.closest('.menu-card');
            if (!card) return;

            const itemId = card.dataset.id;
            const item = menuItems.find((entry) => String(entry.id) === String(itemId));
            if (!item) return;

            if (button.dataset.action === 'edit') {
                document.getElementById('editId').value = item.id;
                document.getElementById('editName').value = item.name;
                document.getElementById('editCategory').value = item.category;
                document.getElementById('editDescription').value = item.description;
                document.getElementById('editPrice').value = item.price;

                const editFile = document.getElementById('editImageFile');
                if (editFile) editFile.value = '';
                setImagePreview(document.getElementById('editImagePreview'), item.image);
                openModal(editModal);
            }

            if (button.dataset.action === 'delete') {
                deleteTargetId = item.id;
                deleteMenuText.textContent = `Delete "${item.name}" from the menu?`;
                openModal(deleteModal);
            }
        });

        // DELETE ITEM API
        confirmDeleteBtn.addEventListener('click', async () => {
            if (!deleteTargetId) return;

            confirmDeleteBtn.disabled = true;
            confirmDeleteBtn.textContent = 'Deleting...';

            try {
                const res = await fetch(`/api/menu/${deleteTargetId}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                
                const data = await res.json();
                if(data.success) {
                    menuItems = menuItems.filter((item) => String(item.id) !== String(deleteTargetId));
                    closeModal(deleteModal);
                    refreshView();
                }
            } catch(e) {
                console.error('Failed to delete', e);
            }

            confirmDeleteBtn.disabled = false;
            confirmDeleteBtn.textContent = 'Delete';
            deleteTargetId = null;
        });

        [addModal, editModal, deleteModal].forEach((modal) => {
            modal.addEventListener('click', (event) => {
                if (event.target === modal) closeModal(modal);
                const closeBtn = event.target.closest('[data-close]');
                if (closeBtn) closeModal(modal);
            });
        });

        [searchInput, categoryFilter].forEach((input) => {
            input.addEventListener('input', refreshView);
            input.addEventListener('change', refreshView);
        });

        const editImageFileInput = document.getElementById('editImageFile');
        if (editImageFileInput) {
            editImageFileInput.addEventListener('change', async () => {
                const file = editImageFileInput.files?.[0] || null;
                const preview = document.getElementById('editImagePreview');
                if (!file) return;
                const dataUrl = await readFileAsDataUrl(file);
                setImagePreview(preview, dataUrl);
            });
        }

        // LOAD MENU ITEMS FROM DB
        async function fetchMenuItems() {
            try {
                const res = await fetch('/api/menu');
                let items = await res.json();

                // Auto Seed if blank database
                if (items.length === 0) {
                    menuGrid.innerHTML = '<p style="padding: 2em; color: #888;">Migrating local storage to database...</p>';
                    let seededItems = [];
                    
                    // Recover old local storage items!
                    let oldLocalItems = [];
                    try {
                        const raw = localStorage.getItem('kk_menu_items_v1');
                        if (raw) oldLocalItems = JSON.parse(raw);
                    } catch(e) {}
                    
                    const sourceItems = (oldLocalItems && oldLocalItems.length > 0) ? oldLocalItems : DEFAULT_MENU_ITEMS;

                    for(const item of sourceItems) {
                        try {
                            const postRes = await fetch('/api/menu', {
                                method: 'POST',
                                headers: { 
                                    'Content-Type': 'application/json', 
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    name: item.name,
                                    category: item.category === 'drinks' ? 'drinks' : 'meal',
                                    description: item.description || '',
                                    price: Number(item.price) || 0,
                                    image: item.image || ''
                                })
                            });
                            const postData = await postRes.json();
                            if(postData.item) seededItems.push(postData.item);
                        } catch(e) {}
                    }
                    items = seededItems;
                }

                menuItems = items;
                refreshView();
            } catch (err) {
                console.error('DB Fetch Error:', err);
            }
        }

        fetchMenuItems();
    </script>
</body>
</html>
