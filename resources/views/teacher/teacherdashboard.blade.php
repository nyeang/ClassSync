<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClassSync - Teacher Dashboard</title>
    <link rel="stylesheet" href="{{ asset('teacher_style/css/teacher-dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="top-nav">
        <div class="nav-left">
            <button class="menu-btn" id="menuBtn">
                <i class="fas fa-bars"></i>
            </button>
            <span class="logo">ClassSync</span>
        </div>
        <div class="nav-center">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search classes...">
            </div>
        </div>
        <div class="nav-right">
            <button class="icon-btn">
                <i class="fas fa-calendar"></i>
            </button>
            <button class="icon-btn notification-btn">
                <i class="fas fa-bell"></i>
                <span class="badge">3</span>
            </button>
            <div class="profile-menu">
                <img src="https://ui-avatars.com/api/?name=Prof+Smith&background=4F46E5&color=fff" alt="Profile">
                <span>Prof. Smith</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </nav>

    <!-- Sidebar Menu -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-content">
            <a href="#" class="sidebar-item active">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="sidebar-item">
                <i class="fas fa-book"></i>
                <span>All Classes</span>
            </a>
            <a href="#" class="sidebar-item" onclick="showCreateClassModal()">
                <i class="fas fa-plus"></i>
                <span>Create Class</span>
            </a>
            <hr>
            <a href="#" class="sidebar-item">
                <i class="fas fa-clipboard-list"></i>
                <span>My Assignments</span>
            </a>
            <a href="#" class="sidebar-item">
                <i class="fas fa-chart-bar"></i>
                <span>Analytics</span>
            </a>
            <a href="#" class="sidebar-item">
                <i class="fas fa-folder"></i>
                <span>Rubrics Library</span>
            </a>
            <a href="#" class="sidebar-item">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
            <hr>
            <div class="sidebar-section">
                <h4>MY CLASSES</h4>
                <a href="#" class="sidebar-item small">Web Development 101</a>
                <a href="#" class="sidebar-item small">Database Systems</a>
                <a href="#" class="sidebar-item small">Mobile Development</a>
                <a href="#" class="sidebar-item small">+ View All</a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="dashboard-container">
            <!-- Left Column (70%) -->
            <div class="main-column">
                <!-- Filter Tabs -->
                <div class="filter-tabs">
                    <button class="tab-btn active" data-filter="all">All Classes</button>
                    <button class="tab-btn" data-filter="2024-2025">2024-2025</button>
                    <button class="tab-btn" data-filter="2023-2024">2023-2024</button>
                    <button class="tab-btn" data-filter="archived">Archived</button>
                </div>

                <!-- Urgent Alerts Banner -->
                <div class="alert-banner">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h3>Urgent Attention Needed:</h3>
                        <ul>
                            <li>3 assignments past deadline - students waiting for grades</li>
                            <li>5 students sent messages in team chats</li>
                            <li>2 team join requests pending approval</li>
                        </ul>
                    </div>
                </div>

                <!-- Class Cards Grid -->
                <div class="class-grid" id="classGrid">
                    <!-- Class Card 1 -->
                    <div class="class-card" data-year="2024-2025">
                        <div class="class-cover" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=400&h=200&fit=crop" alt="Web Dev">
                        </div>
                        <div class="class-body">
                            <h3 class="class-title">Web Development 101</h3>
                            <p class="class-meta">2024-2025 • Section A</p>
                            <div class="class-code-section">
                                <span class="class-code">ABC123XY</span>
                                <button class="btn-icon" onclick="copyCode('ABC123XY')" title="Copy code">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <button class="btn-icon" onclick="showQRModal('ABC123XY')" title="View QR">
                                    <i class="fas fa-qrcode"></i>
                                </button>
                                <button class="btn-icon" title="Settings">
                                    <i class="fas fa-cog"></i>
                                </button>
                            </div>
                            <hr>
                            <div class="class-stats">
                                <div class="stat-item">
                                    <i class="fas fa-users"></i>
                                    <span>45 Students</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-clipboard-list"></i>
                                    <span>8 Assignments</span>
                                </div>
                            </div>
                            <div class="class-stats">
                                <div class="stat-item success">
                                    <i class="fas fa-check-circle"></i>
                                    <span>32 Graded</span>
                                </div>
                                <div class="stat-item warning">
                                    <i class="fas fa-clock"></i>
                                    <span>13 Pending</span>
                                </div>
                            </div>
                            <div class="class-attention">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>5 Need Attention</span>
                            </div>
                            <button class="btn-primary btn-full" onclick="enterClass('Web Development 101')">
                                Enter Class <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Class Card 2 -->
                    <div class="class-card" data-year="2024-2025">
                        <div class="class-cover" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <img src="https://images.unsplash.com/photo-1544383835-bda2bc66a55d?w=400&h=200&fit=crop" alt="Database">
                        </div>
                        <div class="class-body">
                            <h3 class="class-title">Database Systems</h3>
                            <p class="class-meta">2024-2025 • Section B</p>
                            <div class="class-code-section">
                                <span class="class-code">XYZ789AB</span>
                                <button class="btn-icon" onclick="copyCode('XYZ789AB')" title="Copy code">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <button class="btn-icon" onclick="showQRModal('XYZ789AB')" title="View QR">
                                    <i class="fas fa-qrcode"></i>
                                </button>
                                <button class="btn-icon" title="Settings">
                                    <i class="fas fa-cog"></i>
                                </button>
                            </div>
                            <hr>
                            <div class="class-stats">
                                <div class="stat-item">
                                    <i class="fas fa-users"></i>
                                    <span>38 Students</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-clipboard-list"></i>
                                    <span>6 Assignments</span>
                                </div>
                            </div>
                            <div class="class-stats">
                                <div class="stat-item success">
                                    <i class="fas fa-check-circle"></i>
                                    <span>25 Graded</span>
                                </div>
                                <div class="stat-item warning">
                                    <i class="fas fa-clock"></i>
                                    <span>13 Pending</span>
                                </div>
                            </div>
                            <div class="class-attention">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>8 Need Attention</span>
                            </div>
                            <button class="btn-primary btn-full" onclick="enterClass('Database Systems')">
                                Enter Class <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Class Card 3 -->
                    <div class="class-card" data-year="2024-2025">
                        <div class="class-cover" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <img src="https://images.unsplash.com/photo-1551650975-87deedd944c3?w=400&h=200&fit=crop" alt="Mobile">
                        </div>
                        <div class="class-body">
                            <h3 class="class-title">Mobile Development</h3>
                            <p class="class-meta">2024-2025 • Section C</p>
                            <div class="class-code-section">
                                <span class="class-code">MOB456CD</span>
                                <button class="btn-icon" onclick="copyCode('MOB456CD')" title="Copy code">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <button class="btn-icon" onclick="showQRModal('MOB456CD')" title="View QR">
                                    <i class="fas fa-qrcode"></i>
                                </button>
                                <button class="btn-icon" title="Settings">
                                    <i class="fas fa-cog"></i>
                                </button>
                            </div>
                            <hr>
                            <div class="class-stats">
                                <div class="stat-item">
                                    <i class="fas fa-users"></i>
                                    <span>42 Students</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-clipboard-list"></i>
                                    <span>7 Assignments</span>
                                </div>
                            </div>
                            <div class="class-stats">
                                <div class="stat-item success">
                                    <i class="fas fa-check-circle"></i>
                                    <span>35 Graded</span>
                                </div>
                                <div class="stat-item warning">
                                    <i class="fas fa-clock"></i>
                                    <span>7 Pending</span>
                                </div>
                            </div>
                            <div class="class-attention success-attention">
                                <i class="fas fa-check-circle"></i>
                                <span>All caught up!</span>
                            </div>
                            <button class="btn-primary btn-full" onclick="enterClass('Mobile Development')">
                                Enter Class <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Create New Class Button -->
                <button class="btn-create-class" onclick="showCreateClassModal()">
                    <i class="fas fa-plus"></i> Create New Class
                </button>
            </div>

            <!-- Right Sidebar (30%) -->
            <div class="right-sidebar">
                <!-- To-Do List -->
                <div class="sidebar-card">
                    <h3><i class="fas fa-tasks"></i> To-Do List</h3>
                    <div class="todo-section urgent">
                        <h4>⚠️ Urgent (3)</h4>
                        <ul class="todo-list">
                            <li>
                                <input type="checkbox" id="todo1">
                                <label for="todo1">Grade Final Project</label>
                            </li>
                            <li>
                                <input type="checkbox" id="todo2">
                                <label for="todo2">Review Team Chat</label>
                            </li>
                            <li>
                                <input type="checkbox" id="todo3">
                                <label for="todo3">Approve Join Requests</label>
                            </li>
                        </ul>
                    </div>
                    <div class="todo-section">
                        <h4>📝 This Week (5)</h4>
                        <ul class="todo-list">
                            <li>
                                <input type="checkbox" id="todo4">
                                <label for="todo4">Create Assignment 4</label>
                            </li>
                            <li>
                                <input type="checkbox" id="todo5">
                                <label for="todo5">Grade Quiz #3</label>
                            </li>
                            <li>
                                <input type="checkbox" id="todo6" checked>
                                <label for="todo6">Update Course Materials</label>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Upcoming Deadlines -->
                <div class="sidebar-card">
                    <h3><i class="fas fa-calendar-alt"></i> Upcoming Deadlines</h3>
                    <div class="deadline-list">
                        <div class="deadline-item urgent">
                            <div class="deadline-date">
                                <strong>Today</strong>
                                <span>11:59 PM</span>
                            </div>
                            <div class="deadline-info">
                                <p class="deadline-title">Final Project</p>
                                <p class="deadline-class">Web Development 101</p>
                                <p class="deadline-status">12 teams pending</p>
                            </div>
                        </div>
                        <div class="deadline-item">
                            <div class="deadline-date">
                                <strong>Dec 5</strong>
                                <span>11:59 PM</span>
                            </div>
                            <div class="deadline-info">
                                <p class="deadline-title">Quiz #3</p>
                                <p class="deadline-class">Database Systems</p>
                                <p class="deadline-status">28 students pending</p>
                            </div>
                        </div>
                        <div class="deadline-item">
                            <div class="deadline-date">
                                <strong>Dec 7</strong>
                                <span>11:59 PM</span>
                            </div>
                            <div class="deadline-info">
                                <p class="deadline-title">Midterm Assignment</p>
                                <p class="deadline-class">Mobile Development</p>
                                <p class="deadline-status">15 teams in progress</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="sidebar-card">
                    <h3><i class="fas fa-chart-pie"></i> Quick Stats</h3>
                    <div class="quick-stats">
                        <div class="stat-row">
                            <span>Active Classes</span>
                            <strong>8</strong>
                        </div>
                        <div class="stat-row">
                            <span>Total Students</span>
                            <strong>240</strong>
                        </div>
                        <div class="stat-row">
                            <span>This Week</span>
                            <strong>15 due</strong>
                        </div>
                        <div class="stat-row">
                            <span>Need Grading</span>
                            <strong class="urgent-text">23</strong>
                        </div>
                    </div>
                </div>

                <!-- Weekly Activity -->
                <div class="sidebar-card">
                    <h3><i class="fas fa-chart-line"></i> Weekly Activity</h3>
                    <div class="activity-chart">
                        <canvas id="activityChart"></canvas>
                    </div>
                    <div class="activity-stats">
                        <p>📊 Submissions: <strong>45 this week</strong></p>
                        <p>💬 Messages: <strong>128 new</strong></p>
                        <p>👥 Enrollments: <strong>3 new students</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- QR Code Modal -->
    <div class="modal" id="qrModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Class QR Code</h2>
                <button class="close-btn" onclick="closeModal('qrModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body qr-modal-body">
                <div class="qr-display">
                    <div id="qrcode"></div>
                </div>
                <div class="qr-info">
                    <p>Class Code: <strong id="modalClassCode"></strong></p>
                    <p class="help-text">Students can scan this QR code with their phone camera to join the class instantly.</p>
                </div>
                <div class="qr-actions">
                    <button class="btn-secondary" onclick="downloadQR()">
                        <i class="fas fa-download"></i> Download QR
                    </button>
                    <button class="btn-secondary" onclick="printQR()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button class="btn-danger" onclick="resetCode()">
                        <i class="fas fa-sync"></i> Reset Code
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Class Modal -->
    <div class="modal" id="createClassModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Create New Class</h2>
                <button class="close-btn" onclick="closeModal('createClassModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="createClassForm">
                    <div class="form-group">
                        <label for="className">Class Name *</label>
                        <input type="text" id="className" required placeholder="e.g., Web Development 101">
                    </div>
                    <div class="form-group">
                        <label for="academicYear">Academic Year *</label>
                        <select id="academicYear" required>
                            <option value="">Select Year</option>
                            <option value="2024-2025" selected>2024-2025</option>
                            <option value="2023-2024">2023-2024</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="section">Section</label>
                        <input type="text" id="section" placeholder="e.g., Section A">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" rows="4" placeholder="Enter class description..."></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="closeModal('createClassModal')">Cancel</button>
                        <button type="submit" class="btn-primary">Create Class</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script src="{{ asset('teacher_style/js/teacher-dashboard.js') }}"></script>
</body>
</html>