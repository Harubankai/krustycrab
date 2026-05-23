<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<style>
.cards .card p { font-variant-numeric: tabular-nums; font-feature-settings: "tnum" 1; }
.inventory-list { list-style: none; padding:0; margin:0; }
.inventory-list li { display:flex; justify-content:space-between; padding:0.25em 0; border-bottom:1px solid #ddd; }
</style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar">
    <div class="brand">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logoImg">
        <span class="brand-title">ADMIN</span>
    </div>

    <div class="user-profile">
        @if(optional(session('user'))->profile_picture)
            <img src="{{ asset(session('user')->profile_picture) }}" alt="Profile" class="profile-img">
        @else
            <img src="{{ asset('images/admin_profile.png') }}" alt="Profile" class="profile-img">
        @endif
        <div class="user-info">
            <span class="user-name">{{ optional(session('user'))->name ?? 'Admin' }}</span>
            <span class="user-role">Administrator</span>
        </div>
    </div>

    <ul class="menu">
        <li><a href="{{ route('admin.dashboard') }}" class="active"><i class='bx bx-trending-up'></i> Dashboard</a></li>
        <li><a href="{{ route('admin.manage-riders') }}"><i class='bx bx-group'></i> Manage Riders</a></li>
        <li><a href="{{ route('admin.modify-menu') }}"><i class="bx bx-edit"></i> Modify Menu</a></li>
    </ul>

    <div class="logout">
        <a href="{{ route('logout') }}" class="logout-btn" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
    </div>
</nav>

<main class="main-content">
    <header class="header">
        <h1>Monthly Statistics (Year to Date)</h1>
        <p>Sales overview from January up to the current month.</p>
    </header>

    <section class="cards">
        <div class="card">
            <h3>Monthly Sales</h3>
            <p id="monthlySalesValue">₱0.00</p>
        </div>
        <div class="card">
            <h3>Total Riders</h3>
            <p id="totalRidersValue">0</p>
        </div>
    </section>

    <div class="dashboard-grid">
        <section class="chart-container recent-orders">
            <h2>Sales by Month (January → Present)</h2>
            <canvas id="salesChart"></canvas>
        </section>

        <section class="top-items inventory-alerts">
            <h2>Top Selling Items (This Month)</h2>
            <ul class="inventory-list" id="topItemsList"></ul>
        </section>
    </div>
</main>

<script>
// SETTINGS & DATA
const currentYear = new Date().getFullYear();
const currentMonth = new Date().getMonth(); // March = 2
const monthLabels = Array.from({ length: currentMonth + 1 }, (_, i) =>
    new Date(currentYear, i, 1).toLocaleString(undefined, { month: 'long' })
);

const escapeHtml = (text) => String(text ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');

// CALCULATE TOTAL RIDERS
function loadTotalRiders() {
    fetch("{{ url('/total-riders') }}")
        .then(res => res.ok ? res.text() : Promise.reject('Request failed'))
        .then(data => {
            const val = document.getElementById("totalRidersValue");
            if (val) val.innerText = Number(data).toLocaleString();
        })
        .catch(err => console.error('Error:', err));
}
loadTotalRiders(); 
setInterval(loadTotalRiders, 60000);

// INIT CHART INSTANCE
let salesChart = null;
const ctx = document.getElementById('salesChart');
if (ctx) {
    salesChart = new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [{
                label: `Sales (${currentYear})`,
                data: Array(currentMonth + 1).fill(0),
                backgroundColor: 'rgba(252, 226, 6, 0.75)',
                borderColor: 'rgba(252, 226, 6, 1)',
                borderWidth: 1,
                borderRadius: 6,
                maxBarThickness: 44
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: c => `₱${c.raw.toLocaleString()}` } }
            },
            scales: {
                y: { beginAtZero:true, ticks:{ callback: val => `₱${val.toLocaleString()}` } },
                x: { ticks:{ color:'#333' } }
            }
        }
    });
}

// FETCH LIVE STATS FROM DB
async function updateAdminStats() {
    try {
        const response = await fetch("{{ route('api.admin.stats') }}");
        const data = await response.json();

        // 1. Update Monthly Sales
        const salesPerMonth = data.monthlySales || [];
        const totalSales = salesPerMonth.reduce((sum, v) => sum + v, 0);
        const slsVal = document.getElementById('monthlySalesValue');
        if (slsVal) {
            slsVal.innerText = `₱${totalSales.toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
        }

        // 2. Update Chart
        if (salesChart) {
            salesChart.data.datasets[0].data = salesPerMonth;
            salesChart.update();
        }

        // 3. Update Top Items
        const topItems = data.topItems || [];
        const ul = document.getElementById('topItemsList');
        if (ul) {
            ul.innerHTML = topItems.map(([name, count]) => 
                `<li><span>${escapeHtml(name)}</span><span>${count} sold</span></li>`
            ).join('') || '<li style="padding:10px 0; color:#888;">No sales yet this month.</li>';
        }

        const monthName = new Date(currentYear, currentMonth, 1).toLocaleString(undefined, { month: 'long' });
        const topItemsHeader = document.querySelector('.top-items h2');
        if (topItemsHeader) {
            topItemsHeader.textContent = `Top Selling Items (${monthName})`;
        }
    } catch(err) {
        console.error('Failed to update stats', err);
    }
}

updateAdminStats();
setInterval(updateAdminStats, 5000);
</script>
</body>
</html>
