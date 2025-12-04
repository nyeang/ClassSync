<div class="sidebar-content">
    <div class="sidebar-section">
        <div class="sidebar-section-title">Main</div>
        <div class="sidebar-menu">
            <a href="{{route('admin.dashboard')}}" class="sidebar-item active">
                <i class="fas fa-gauge-high"></i>
                <span>Overview</span>
            </a>
            <a href="{{route('admin.all-users')}}" class="sidebar-item">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
            <a href="" class="sidebar-item">
                <i class="fas fa-chalkboard"></i>
                <span>Classes</span>
            </a>
        </div>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-section-title">Management</div>
        <div class="sidebar-menu">
            <a href="/admin/password-requests" class="sidebar-item">
                <i class="fas fa-key"></i>
                <span>Password Requests</span>
                <span class="badge">3</span>
            </a>
            <a href="/admin/audit-logs" class="sidebar-item">
                <i class="fas fa-clipboard-list"></i>
                <span>Audit Logs</span>
            </a>
        </div>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-section-title">System</div>
        <div class="sidebar-menu">
            <a href="/admin/settings" class="sidebar-item">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </div>
    </div>
</div>
