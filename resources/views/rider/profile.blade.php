<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider - Profile</title>

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
        <li><a href="{{ route('rider.dashboard') }}"><i class="bx bx-basket"></i> Orders</a></li>
        <li><a href="{{ route('rider.delivery') }}"><i class="bx bx-cycling"></i> Delivery</a></li>
        <li><a href="{{ route('rider.profile') }}" class="active"><i class="bx bx-user"></i> Profile</a></li>
    </ul>

    <div class="logout">
        <a href="{{ route('logout') }}" class="logout-btn" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
    </div>
</nav>

<main class="main-content">
    <section id="profile" class="recent-orders" style="margin-top: 24px;">
        <h2>Profile</h2>

        @if(session('success'))
            <p style="color: #4caf50; font-weight: bold; margin-bottom: 20px;">{{ session('success') }}</p>
        @endif
        @if(session('error'))
            <p style="color: #f44336; font-weight: bold; margin-bottom: 20px;">{{ session('error') }}</p>
        @endif
        @if ($errors->any())
            <div style="color: #f44336; font-weight: bold; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="profileForm" class="profile-form" method="POST" action="{{ route('rider.profile.update') }}" enctype="multipart/form-data">
            @csrf

            <div style="text-align: center; margin-bottom: 20px;">
                @if(isset($user->profile_picture) && $user->profile_picture)
                    <img src="{{ asset($user->profile_picture) }}" alt="Profile Picture" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin-bottom: 10px;">
                @else
                    <i class='bx bx-user-circle' style="font-size: 100px; color: #ccc;"></i>
                @endif
                <br>
                <input type="file" name="profile_picture" accept="image/*" disabled style="display: block; margin: 0 auto; width: auto;">
                @error('profile_picture') <span style="color: #f44336;">{{ $message }}</span> @enderror
            </div>

            <table>
                <tbody>
                    <tr>
                        <td>Full Name</td>
                        <td>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" disabled required>
                        </td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" disabled required>
                        </td>
                    </tr>
                    <tr>
                        <td>Mobile Number</td>
                        <td>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" disabled>
                        </td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>
                            <input type="text" name="address" value="{{ old('address', $user->address) }}" disabled>
                        </td>
                    </tr>
                  
                </tbody>
            </table>

            <div class="profile-actions" style="margin-top: 20px;">
                <button type="button" id="editDetailsBtn" class="logout-btn edit-btn">Edit Details</button>
                <button type="submit" id="saveChangesBtn" class="save-btn" style="display: none;">Save Changes</button>
            </div>
        </form>

        <h2 style="margin-top: 40px;">Change Password</h2>
        <form class="profile-form" method="POST" action="{{ route('rider.profile.password') }}">
            @csrf

            <table>
                <tbody>
                    <tr>
                        <td>Current Password</td>
                        <td>
                            <input type="password" name="current_password" required>
                        </td>
                    </tr>
                    <tr>
                        <td>New Password</td>
                        <td>
                            <input type="password" name="password" required>
                        </td>
                    </tr>
                    <tr>
                        <td>Confirm Password</td>
                        <td>
                            <input type="password" name="password_confirmation" required>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="profile-actions" style="margin-top: 20px;">
                <button type="submit" class="save-btn">Change Password</button>
            </div>
        </form>
    </section>
</main>

<script>
const editBtn = document.getElementById('editDetailsBtn');
const saveBtn = document.getElementById('saveChangesBtn');
const form = document.getElementById('profileForm');

function setEditable(editable) {
    form.querySelectorAll('input').forEach(input => {
        input.disabled = !editable;
    });
}

let editing = false;

editBtn.addEventListener('click', () => {
    editing = !editing;
    setEditable(editing);
    if (editing) {
        saveBtn.style.display = 'inline-block';
        editBtn.textContent = 'Cancel Edit';
        editBtn.style.backgroundColor = '#6c757d';
    } else {
        saveBtn.style.display = 'none';
        editBtn.textContent = 'Edit Details';
        editBtn.style.backgroundColor = '';
        form.reset(); // Revert to original visible values
    }
});
</script>
</body>
</html>