<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ClassSync - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#1a1a2e">

    <link rel="stylesheet" href="{{ asset('admin_style/css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">


</head>
<body>

<!-- Top Bar -->
<header class="topbar">
    @include('layouts.admin_topbar')
</header>

<!-- Sidebar Overlay (Mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Main Layout -->
<div class="admin-layout">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        @include('layouts.admin_sidebar')
    </aside>

    <!-- Main Content -->
    <main class="main-content">

    @yield('content')

    </main>
</div>

<footer>
    ClassSync &copy; <span id="year"></span> • Paragon International University
</footer>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<!-- ========== CREATE USER MODAL ========== -->
<div class="modal-backdrop" id="modal-user">
    <div class="modal">
        <div class="modal-header">
            <h3>Create New User</h3>
            <button class="modal-close" data-close-modal>&times;</button>
        </div>
        <div class="modal-body">
            <form id="createUserForm">
                <div class="form-group">
                    <label>Full Name <span class="required">*</span></label>
                    <input type="text" class="form-input" id="userName" placeholder="Enter full name" required>
                </div>
                <div class="form-group">
                    <label>Email <span class="required">*</span></label>
                    <div class="input-group">
                        <input type="text" class="form-input" id="userEmail" placeholder="username" required>
                        <span class="input-suffix">@paragon.edu.kh</span>
                    </div>
                    <p class="form-hint">Only @paragon.edu.kh emails are allowed</p>
                </div>
                <div class="form-group">
                    <label>Role <span class="required">*</span></label>
                    <select class="form-input" id="userRole" required>
                        <option value="">Select role</option>
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-close-modal>Cancel</button>
            <button class="btn btn-primary" id="btnCreateUser">
                <i class="fas fa-user-plus"></i>
                Create User
            </button>
        </div>
    </div>
</div>

<!-- ========== CREATE CLASS MODAL ========== -->
<div class="modal-backdrop" id="modal-class">
    <div class="modal">
        <div class="modal-header">
            <h3>Create New Class</h3>
            <button class="modal-close" data-close-modal>&times;</button>
        </div>
        <div class="modal-body">
            <form id="createClassForm">
                <div class="form-group">
                    <label>Class Name <span class="required">*</span></label>
                    <input type="text" class="form-input" id="className" placeholder="e.g., Web Development 101" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Academic Year <span class="required">*</span></label>
                        <select class="form-input" id="classYear" required>
                            <option value="2025-2026">2025-2026</option>
                            <option value="2024-2025">2024-2025</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Assign Teacher</label>
                        <select class="form-input" id="classTeacher">
                            <option value="">Select teacher</option>
                            <option value="1">Dara Kim</option>
                            <option value="2">Sokha Chea</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <input type="text" class="form-input" id="classDescription" placeholder="Brief description of the class">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-close-modal>Cancel</button>
            <button class="btn btn-primary" id="btnCreateClass">
                <i class="fas fa-plus"></i>
                Create Class
            </button>
        </div>
    </div>
</div>

<!-- ========== CREDENTIAL POPUP MODAL ========== -->
<div class="modal-backdrop" id="modal-credentials">
    <div class="credential-popup">
        <div class="credential-header">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h3>Account Created Successfully!</h3>
            <p>Please save these credentials securely</p>
        </div>
        <div class="credential-body">
            <div class="credential-section">
                <div class="credential-section-title">Account Details</div>
                <div class="credential-item">
                    <span class="credential-item-label">Name</span>
                    <span class="credential-item-value" id="credName">-</span>
                </div>
                <div class="credential-item">
                    <span class="credential-item-label">Role</span>
                    <span class="credential-item-value" id="credRole">-</span>
                </div>
            </div>

            <div class="credential-section">
                <div class="credential-section-title">Login Credentials</div>
                <div class="credential-box">
                    <div class="credential-row">
                        <span class="credential-label">Email</span>
                        <span class="credential-value">
                            <span id="credEmail">-</span>
                            <button class="copy-btn" onclick="copyToClipboard('credEmail')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </span>
                    </div>
                    <div class="credential-row">
                        <span class="credential-label">Password</span>
                        <span class="credential-value">
                            <span id="credPassword">-</span>
                            <button class="copy-btn" onclick="copyToClipboard('credPassword')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="credential-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <p><strong>Important:</strong> This password will not be shown again. Please copy or print these credentials before closing.</p>
            </div>
        </div>
        <div class="credential-footer">
            <button class="btn btn-secondary" onclick="printCredentials()">
                <i class="fas fa-print"></i>
                Print
            </button>
            <button class="btn btn-primary" onclick="copyAllCredentials()">
                <i class="fas fa-copy"></i>
                Copy All
            </button>
            <button class="btn btn-success" data-close-modal>
                <i class="fas fa-check"></i>
                Done
            </button>
        </div>
    </div>
</div>

<script src="{{ asset('admin_style/js/admin-dashboard.js') }}"></script>
</body>
</html>
