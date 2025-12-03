    <div class="topbar-left">
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="topbar-logo">🎓</div>
        <div class="topbar-brand">
            <span class="topbar-title">ClassSync</span>
            <span class="topbar-subtitle">Admin Dashboard</span>
        </div>
    </div>

    <div class="topbar-right">
        <div class="topbar-search">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search users, classes...">
        </div>
        <button class="topbar-notifications">
            <i class="fas fa-bell"></i>
            <span class="notification-badge">3</span>
        </button>
        <div class="topbar-user">
            <div class="topbar-avatar">A</div>
            <div class="topbar-user-info">
                <span class="topbar-user-name">Admin User</span>
                <span class="topbar-user-role">Administrator</span>
            </div>
        </div>
<!-- Logout Form (hidden) -->
<form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Logout Button -->
<button class="topbar-btn" onclick="event.preventDefault(); if(confirm('Are you sure you want to logout?')) document.getElementById('logoutForm').submit();">
    <i class="fas fa-sign-out-alt"></i>
    <span>Logout</span>
</button>