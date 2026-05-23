<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider - Delivery</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/rider.css') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>

    <nav class="sidebar">
        <div class="brand">
            <img src="{{ asset('images/logo.png') }}" class="logoImg">
            <span class="brand-title">RIDER</span>
        </div>

        <ul class="menu">
            <li><a href="{{ route('rider.dashboard') }}" ><i class="bx bx-basket"></i>  Orders</a></li>
            <li><a href="{{ route('rider.delivery') }}" class="active"><i class="bx bx-cycling"></i>  Delivery</a></li>
            <li><a href="{{ route('rider.profile') }}"><i class="bx bx-user"></i>  Profile</a></li>
        </ul>

        <div class="logout">
            <a href="{{ route('logout') }}" class="logout-btn" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
        </div>
    </nav>

    <main class="main-content">
        <div class="workflow">
            <div class="stepper">
                <div class="step active" id="s1">Pickup</div>
                <div class="step" id="s2">In Transit</div>
                <div class="step" id="s3">Arrived</div>
                <div class="step" id="s4">Done</div>
            </div>

            <section class="content-section active" id="step1">
                <h2 style="margin-top:0">Delivery Checklist</h2>
                <p class="muted">Verify all items are in the bag.</p>
                <div class="info-card">
                    <strong style="font-size: 1.1rem;">Customer Details</strong><br>
                    <p style="margin: 8px 0; line-height: 1.4;">
                        Name: <span id="deliveryCustomerName">—</span><br>
                        Mobile: <span id="deliveryCustomerPhone">—</span><br>
                        Address: <span id="deliveryCustomerAddress">—</span>
                    </p>
                    <hr style="border: 0; border-top: 1px solid #ddd; margin: 12px 0;">
                    <strong style="font-size: 1.05rem;">Order</strong><br>
                    <div id="deliveryOrderItems"></div>
                </div>
                <div class="info-card" id="deliveryChecklist"></div>
            </section>

            <section class="content-section" id="step2">
                <h2 style="margin-top:0">Heading to Customer</h2>
                <p class="muted">Deliver to the following address:</p>
                <div class="info-card">
                    <strong style="font-size: 1.1rem;" id="deliveryCustomerName2">—</strong><br>
                    <p style="margin: 8px 0; line-height: 1.4;">
                        <span id="deliveryCustomerAddress2">—</span>
                    </p>
                    <hr style="border: 0; border-top: 1px solid #ddd; margin: 15px 0;">
                    <small class="note">NOTE: Gate code is 1234</small>
                </div>
            </section>

            <section class="content-section" id="step3">
                <h2 style="margin-top:0">Payment Collection</h2>
                <p class="muted">Ensure cash is received before handing over food.</p>
                <div class="info-card" style="text-align: center; border: 2px dashed #f39c12; background: #fff9f0;">
                    <p style="margin-bottom: 0; font-weight: bold; color: #611909;">COLLECT TOTAL CASH</p>
                    <span class="cash-highlight" id="deliveryCashTotal">₱0.00</span>
                    <p style="font-size: 0.8rem; color: #777;">(Cash on Delivery)</p>
                </div>
            </section>

            <section class="content-section" id="step4">
                <div style="text-align: center; padding-top: 50px;">
                    <div style="font-size: 4rem;">✅</div>
                    <h1 style="color: #4caf50; margin-top: 10px;">Delivery Done!</h1>
                    <p class="muted">The order has been successfully processed and paid.</p>
                    <a href="{{ route('rider.dashboard') }}" id="finishDeliveryBtn" class="main-btn" style="display: inline-block; text-align: center; background: #611909; color: #fff; margin-top: 30px;">Go to Next Order</a>
                </div>
            </section>

            <footer class="workflow-footer" id="footer-nav">
                <button class="main-btn" id="nextBtn" onclick="nextStep()">Confirm Pickup</button>
            </footer>
        </div>
    </main>

<script>
    let currentStep = 1;
    const LOCK_KEY = 'rider_active_order';
    const ORDERS_STORAGE_KEY = 'kk_orders_v1';

    const customerNameEl = document.getElementById('deliveryCustomerName');
    const customerPhoneEl = document.getElementById('deliveryCustomerPhone');
    const customerAddressEl = document.getElementById('deliveryCustomerAddress');
    const customerName2El = document.getElementById('deliveryCustomerName2');
    const customerAddress2El = document.getElementById('deliveryCustomerAddress2');
    const orderItemsEl = document.getElementById('deliveryOrderItems');
    const checklistEl = document.getElementById('deliveryChecklist');
    const cashTotalEl = document.getElementById('deliveryCashTotal');

    const nextBtn = document.getElementById('nextBtn');
    const footer = document.getElementById('footer-nav');
    const finishDeliveryBtn = document.getElementById('finishDeliveryBtn');

    const escapeHtml = (text) => String(text ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/\"/g, '&quot;')
        .replace(/'/g, '&#39;');

    const readOrders = () => {
        try {
            const raw = localStorage.getItem(ORDERS_STORAGE_KEY);
            const parsed = raw ? JSON.parse(raw) : [];
            return Array.isArray(parsed) ? parsed : [];
        } catch (_) {
            return [];
        }
    };

    const writeOrders = (orders) => {
        try { localStorage.setItem(ORDERS_STORAGE_KEY, JSON.stringify(orders)); } catch (_) {}
    };

    const getActiveOrderId = () => {
        const val = localStorage.getItem(LOCK_KEY);
        if (!val) return null;
        // Backward-compat: previous version stored "1" as a simple lock flag.
        if (val === '1') {
            localStorage.removeItem(LOCK_KEY);
            return null;
        }
        return String(val);
    };

    const clearActiveOrder = () => {
        localStorage.removeItem(LOCK_KEY);
    };

    const formatCurrency = (amount) => `₱${Number(amount || 0).toFixed(2)}`;

    const setText = (el, value) => { if (el) el.textContent = value ?? '—'; };

    const setHtml = (el, html) => { if (el) el.innerHTML = html; };

    const setStepUi = (step) => {
        for (let i = 1; i <= 4; i += 1) {
            const section = document.getElementById(`step${i}`);
            const stepper = document.getElementById(`s${i}`);
            if (section) section.classList.toggle('active', i === step);
            if (stepper) {
                stepper.classList.toggle('active', i === step);
                stepper.classList.toggle('completed', i < step);
            }
        }

        if (!nextBtn || !footer) return;
        nextBtn.classList.remove('btn-success');

        if (step === 1) {
            nextBtn.innerText = "Confirm Pickup";
            footer.style.display = '';
        } else if (step === 2) {
            nextBtn.innerText = "Arrived at Destination";
            footer.style.display = '';
        } else if (step === 3) {
            nextBtn.innerText = "Confirm Cash & Complete";
            nextBtn.classList.add('btn-success');
            footer.style.display = '';
        } else {
            footer.style.display = 'none';
        }

        if (nextBtn) {
            nextBtn.disabled = false; // Re-enable the button after updating UI
        }
    };

    let activeDbId = null;

    const renderForOrder = (order) => {
        const cust = order.customer || {};
        const items = Array.isArray(order.items) ? order.items : [];

        setText(customerNameEl, cust.name || 'Customer');
        setText(customerPhoneEl, cust.phone || '—');
        setText(customerAddressEl, cust.address || '—');
        setText(customerName2El, cust.name || 'Customer');
        setText(customerAddress2El, cust.address || '—');

        setHtml(orderItemsEl, items.map((i) => {
            const qty = Number(i.qty) || 0;
            const name = escapeHtml(i.name);
            return `<p style="margin: 6px 0;">${qty}x ${name}</p>`;
        }).join('') || '<p style="margin: 6px 0;">—</p>');

        setHtml(checklistEl, items.map((i) => {
            const qty = Number(i.qty) || 0;
            const name = escapeHtml(i.name);
            return `<label class="check-item"><input type="checkbox" class="item-check"> ${qty}x ${name}</label>`;
        }).join('') || '<p class="muted">No items.</p>');

        const totalPrice = items.reduce((sum, i) => sum + (Number(i.price) || 0) * (Number(i.qty) || 0), 0);
        setText(cashTotalEl, formatCurrency(totalPrice));
    };

    const init = async () => {
        const activeId = getActiveOrderId();
        if (!activeId) {
            const workflow = document.querySelector('.workflow');
            if (workflow) {
                workflow.innerHTML = `
                    <div class="recent-orders" style="text-align:center;">
                        <h2 style="margin:0 0 8px;">No active delivery</h2>
                        <p class="muted" style="opacity:0.85;">Accept an order first from the Orders tab.</p>
                        <a href="{{ route('rider.dashboard') }}" class="main-btn" style="display:inline-block; margin-top:14px; background:#fce206; color:#611909;">Go to Orders</a>
                    </div>
                `;
            }
            if (footer) footer.style.display = 'none';
            return;
        }

        try {
            const response = await fetch("{{ route('api.orders.available') }}");
            const allOrders = await response.json();
            const order = allOrders.find((o) => String(o.id) === String(activeId));
            
            if (!order) {
                clearActiveOrder();
                init();
                return;
            }

            activeDbId = order.db_id;
            renderForOrder(order);
            currentStep = Math.min(4, Math.max(1, parseInt(order.deliveryStep, 10) || 1));
            setStepUi(currentStep);
        } catch (err) {
            console.error(err);
        }
    };

    async function nextStep() {
        const activeId = getActiveOrderId();
        if (!activeId || !activeDbId) return;

        let newStatus = '';
        let stepNum = 0;

        if (currentStep === 1) {
            const checks = document.querySelectorAll('.item-check');
            const allChecked = Array.from(checks).every((c) => c.checked);
            if (!allChecked) {
                alert("Please check all items before leaving the restaurant!");
                return;
            }
            newStatus = 'In Transit';
            stepNum = 2;
        } else if (currentStep === 2) {
            newStatus = 'Arrived';
            stepNum = 3;
        } else if (currentStep === 3) {
            newStatus = 'Completed';
            stepNum = 4;
        } else {
            return;
        }

        if (nextBtn) {
            nextBtn.disabled = true;
            nextBtn.innerText = 'Updating...';
        }

        try {
            const response = await fetch(`/api/orders/${activeDbId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    _token: '{{ csrf_token() }}',
                    status: newStatus,
                    delivery_step: stepNum
                })
            });

            const result = await response.json();
            if (result.success) {
                currentStep = stepNum;
                setStepUi(currentStep);
                if (currentStep === 4) {
                    clearActiveOrder();
                }
            } else {
                alert('Failed to update status');
                setStepUi(currentStep);
            }
        } catch (err) {
            alert('Connection error');
            setStepUi(currentStep);
        }
    }

    if (finishDeliveryBtn) {
        finishDeliveryBtn.addEventListener('click', function () {
            clearActiveOrder();
        });
    }

    init();
</script>

</body>
</html>
