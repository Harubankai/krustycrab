<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Krusty Krab - Profile</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer/profile.css') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <nav class="sidebar">
        <div class="brand">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logoImg">
            <span class="brand-title">CUSTOMER</span>
        </div>

        <ul class="menu">
            <li><a href="{{ route('customer.dashboard') }}"><i class="bx bx-store"></i>  Menu</a></li>
            <li><a href="{{ route('customer.cart') }}"><i class="bx bx-cart"></i>  Cart</a></li>
            <li><a href="{{ route('customer.orders') }}"><i class="bx bx-basket"></i>  Orders</a></li>
            <li><a href="{{ route('customer.profile') }}" class="active"><i class="bx bx-user"></i>  Profile</a></li>
        </ul>

        <div class="logout">
            <a href="{{ route('logout') }}" class="logout-btn" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
        </div>
    </nav>

    <main class="main-content">
        <header class="header">
            <h1>Account Settings</h1>
            <p>Customers can manage their account.</p>
        </header>

        @if (session('success'))
            <div class="alert alert--success" role="status" id="profileSuccessAlert">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert--error" role="alert">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert--error" role="alert">
                <strong>Please fix the errors below.</strong>
            </div>
        @endif

        <section class="dashboard-grid profile-grid">
            <div class="panel">
                <div class="panel-head">
                    <h2>Edit Profile</h2>
                    <button type="button" class="action-btn action-btn--ghost" id="editProfileBtn">Edit profile</button>
                </div>
<form method="POST" action="{{ route('customer.profile.update') }}" class="profile-form" id="profileForm" enctype="multipart/form-data">
    @csrf

    <div class="form-grid">
        <label class="field field--full" style="text-align: center; margin-bottom: 20px;">
            @if(isset($user->profile_picture) && $user->profile_picture)
                <img src="{{ asset($user->profile_picture) }}" alt="Profile Picture" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin-bottom: 10px;">
            @else
                <i class='bx bx-user-circle' style="font-size: 100px; color: #ccc;"></i>
            @endif
            <br>
            <input
                type="file"
                name="profile_picture"
                class="field-input profile-input"
                accept="image/*"
                disabled
                style="display: block; margin: 0 auto; width: auto;"
            >
            @error('profile_picture')<span class="field-error">{{ $message }}</span>@enderror
        </label>
        <label class="field">
            <span class="field-label">Name</span>
            <input
                type="text"
                name="name"
                value="{{ old('name', $user->name ?? '') }}"
                class="field-input profile-input"
                disabled
                required
            >
            @error('name')<span class="field-error">{{ $message }}</span>@enderror
        </label>

        <label class="field">
            <span class="field-label">Email</span>
            <input
                type="email"
                name="email"
                value="{{ old('email', $user->email ?? '') }}"
                class="field-input profile-input"
                disabled
                required
            >
            @error('email')<span class="field-error">{{ $message }}</span>@enderror
        </label>

        <label class="field">
            <span class="field-label">Phone number</span>
            <input
                type="text"
                name="phone"
                value="{{ old('phone', $user->phone ?? '') }}"
                class="field-input profile-input"
                disabled
            >
            @error('phone')<span class="field-error">{{ $message }}</span>@enderror
        </label>

        <label class="field field--full">
            <span class="field-label">Address</span>
            <textarea
                name="address"
                class="field-input field-textarea profile-input"
                rows="3"
                disabled
            >{{ old('address', $user->address ?? '') }}</textarea>
            @error('address')<span class="field-error">{{ $message }}</span>@enderror
        </label>
    </div>

    <div class="form-actions">
        <button type="submit" class="action-btn" id="saveProfileBtn" disabled>Save</button>
    </div>
</form>
            </div>

            <div class="panel">
                <h2>Change password</h2>

                <form method="POST" action="{{ route('customer.profile.password') }}" class="profile-form">
                    @csrf

                    <div class="form-grid">
                        <label class="field field--full">
                            <span class="field-label">Current password</span>
                            <input type="password" name="current_password" class="field-input" required>
                            @error('current_password')<span class="field-error">{{ $message }}</span>@enderror
                        </label>

                        <label class="field">
                            <span class="field-label">New password</span>
                            <input type="password" name="password" class="field-input" required>
                            @error('password')<span class="field-error">{{ $message }}</span>@enderror
                        </label>

                        <label class="field">
                            <span class="field-label">Confirm password</span>
                            <input type="password" name="password_confirmation" class="field-input" required>
                        </label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="action-btn">Save</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script>
       const editBtn = document.getElementById('editProfileBtn');
const profileForm = document.getElementById('profileForm');
const saveBtn = document.getElementById('saveProfileBtn');
const inputs = document.querySelectorAll('.profile-input');

let editing = false;

function toggleEdit() {
    editing = !editing;

    inputs.forEach(input => {
        input.disabled = !editing;
    });

    saveBtn.disabled = !editing;
    editBtn.textContent = editing ? "Cancel" : "Edit Profile";

    if (editing) {
        inputs[0].focus();
    } else {
        profileForm.reset();
    }
}

editBtn.addEventListener('click', toggleEdit);
    </script>
</body>
</html>
