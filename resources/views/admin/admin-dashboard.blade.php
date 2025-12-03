@extends('layouts.admin_master')
@section('content')

<section id="section-overview" class="section active">
    <div class="page-header">
        <div class="page-header-left">
            <h1>Dashboard Overview</h1>
            <p>Welcome back! Here's what's happening with ClassSync today.</p>
        </div>
        <div class="page-header-actions">
            <button class="btn btn-primary" id="btnOpenUserModal">
                <i class="fas fa-user-plus"></i>
                <span class="btn-text">New User</span>
            </button>
            <button class="btn btn-primary" id="btnOpenClassModal">
                <i class="fas fa-plus"></i>
                <span class="btn-text">New Class</span>
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <!-- Total Users -->
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon blue">
                    <i class="fas fa-users"></i>
                </div>
                <span class="stat-card-trend up" id="usersTrendBadge">
                    <i class="fas fa-arrow-up"></i> <span id="usersTrend">0</span>%
                </span>
            </div>
            <div class="stat-card-value" id="totalUsers">0</div>
            <div class="stat-card-label">Total Users</div>
        </div>

        <!-- Active Classes -->
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon green">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <span class="stat-card-trend up" id="classesTrendBadge">
                    <i class="fas fa-arrow-up"></i> <span id="classesTrend">0</span>%
                </span>
            </div>
            <div class="stat-card-value" id="activeClasses">0</div>
            <div class="stat-card-label">Active Classes</div>
        </div>

        <!-- Password Requests -->
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon yellow">
                    <i class="fas fa-user-clock"></i>
                </div>
                <span class="stat-card-trend up" id="requestsTrendBadge">
                    <i class="fas fa-arrow-up"></i> <span id="requestsTrend">0</span>%
                </span>
            </div>
            <div class="stat-card-value" id="pendingRequests">0</div>
            <div class="stat-card-label">Password Requests</div>
        </div>

        <!-- Total Teachers -->
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon red">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <span class="stat-card-trend up" id="teachersTrendBadge">
                    <i class="fas fa-arrow-up"></i> <span id="teachersTrend">0</span>%
                </span>
            </div>
            <div class="stat-card-value" id="totalTeachers">0</div>
            <div class="stat-card-label">Teachers</div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Recent Users -->
        <div class="panel">
            <div class="panel-header">
                <h3><i class="fas fa-user-plus"></i> Recent Users</h3>
            </div>
            <div class="panel-body">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="recentUsersTable">
                            <tr>
                                <td colspan="3" class="text-center" style="padding: 2rem; color: #6B7280;">
                                    <i class="fas fa-spinner fa-spin"></i> Loading...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Password Reset Requests -->
        <div class="panel">
            <div class="panel-header">
                <h3><i class="fas fa-key"></i> Password Requests</h3>
            </div>
            <div class="panel-body">
                <div class="request-list" id="passwordRequestsList">
                    <div class="text-center" style="padding: 2rem; color: #6B7280;">
                        <i class="fas fa-spinner fa-spin"></i> Loading requests...
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== ENHANCED CREATE USER MODAL - CLEAN VERSION ========== -->
<div class="modal-backdrop" id="userModal">
    <div class="modal-dialog modal-user-create">
        
        <!-- Header with Gradient -->
        <div class="modal-header-gradient-user">
            <div class="modal-header-content">
                <div class="modal-icon-badge">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="modal-title-group">
                    <h2>Create New User</h2>
                    <p>Add a new student or teacher to the system</p>
                </div>
            </div>
            <button class="btn-close-circle" data-close-modal>
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="createUserForm">
            <div class="modal-body-enhanced">
                
                <!-- Basic Information Section -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-id-card"></i>
                        <span>Basic Information</span>
                        <span class="section-badge">Required</span>
                    </div>

                    <div class="form-row">
                        <!-- Full Name -->
                        <div class="form-col">
                            <label class="form-label-modern">
                                <span class="label-icon-circle bg-purple">
                                    <i class="fas fa-user"></i>
                                </span>
                                <span class="label-text-modern">
                                    Full Name <span class="badge-req">Required</span>
                                </span>
                            </label>
                            <input 
                                type="text" 
                                id="userName" 
                                name="name" 
                                class="input-modern" 
                                placeholder="e.g., John Doe" 
                                required
                            >
                        </div>

                        <!-- User Role -->
                        <div class="form-col">
                            <label class="form-label-modern">
                                <span class="label-icon-circle bg-green">
                                    <i class="fas fa-user-tag"></i>
                                </span>
                                <span class="label-text-modern">
                                    User Role <span class="badge-req">Required</span>
                                </span>
                            </label>
                            <select id="userRole" name="role" class="input-modern" required>
                                <option value="">Select role</option>
                                <option value="Student">Student</option>
                                <option value="Teacher">Teacher</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <!-- Gender -->
                        <div class="form-col">
                            <label class="form-label-modern">
                                <span class="label-icon-circle bg-pink">
                                    <i class="fas fa-venus-mars"></i>
                                </span>
                                <span class="label-text-modern">
                                    Gender
                                </span>
                            </label>
                            <select id="userGender" name="gender" class="input-modern">
                                <option value="">Select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <!-- Date of Birth -->
                        <div class="form-col">
                            <label class="form-label-modern">
                                <span class="label-icon-circle bg-indigo">
                                    <i class="fas fa-calendar-day"></i>
                                </span>
                                <span class="label-text-modern">
                                    Date of Birth
                                </span>
                            </label>
                            <input 
                                type="date" 
                                id="userDob" 
                                name="date_of_birth" 
                                class="input-modern"
                            >
                        </div>
                    </div>
                </div>

                <!-- Account Details Section -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-user-circle"></i>
                        <span>Account Details</span>
                        <span class="section-badge">Required</span>
                    </div>

                    <div class="form-row">
                        <!-- Email -->
                        <div class="form-col">
                            <label class="form-label-modern">
                                <span class="label-icon-circle bg-blue">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <span class="label-text-modern">
                                    Email Username <span class="badge-req">Required</span>
                                </span>
                            </label>
                            <div class="input-group-modern">
                                <input 
                                    type="text" 
                                    id="userEmail" 
                                    name="email" 
                                    class="input-modern input-with-suffix" 
                                    placeholder="john.doe" 
                                    required
                                >
                                <span class="input-suffix-modern">@paragoniu.edu.kh</span>
                            </div>
                            <small class="input-hint">
                                <i class="fas fa-info-circle"></i>
                                Enter only the username part
                            </small>
                        </div>

                        <!-- Phone Number -->
                        <div class="form-col">
                            <label class="form-label-modern">
                                <span class="label-icon-circle bg-green">
                                    <i class="fas fa-phone"></i>
                                </span>
                                <span class="label-text-modern">
                                    Phone Number
                                </span>
                            </label>
                            <input 
                                type="tel" 
                                id="userPhone" 
                                name="phone" 
                                class="input-modern" 
                                placeholder="e.g., +855 12 345 678"
                            >
                        </div>
                    </div>
                </div>

                <!-- Academic Information Section -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Academic Information</span>
                    </div>

                    <div class="form-row">
                        <!-- Department/Major -->
                        <div class="form-col">
                            <label class="form-label-modern">
                                <span class="label-icon-circle bg-violet">
                                    <i class="fas fa-building"></i>
                                </span>
                                <span class="label-text-modern">
                                    Department/Major
                                </span>
                            </label>
                            <select id="userDepartment" name="department" class="input-modern">
                                <option value="">Select department</option>
                                <option value="Computer Science">Computer Science</option>
                                <option value="Information Technology">Information Technology</option>
                                <option value="Business Administration">Business Administration</option>
                                <option value="Accounting">Accounting</option>
                                <option value="Engineering">Engineering</option>
                                <option value="Arts & Design">Arts & Design</option>
                            </select>
                        </div>

                        <!-- Academic Year / Position -->
                        <div class="form-col">
                            <label class="form-label-modern">
                                <span class="label-icon-circle bg-orange">
                                    <i class="fas fa-layer-group"></i>
                                </span>
                                <span class="label-text-modern" id="yearPositionLabel">
                                    Academic Year
                                </span>
                            </label>
                            <select id="userYear" name="academic_year" class="input-modern">
                                <option value="">Select year</option>
                                <option value="Year 1">Year 1</option>
                                <option value="Year 2">Year 2</option>
                                <option value="Year 3">Year 3</option>
                                <option value="Year 4">Year 4</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Contact Information</span>
                    </div>

                    <!-- Address -->
                    <div class="form-col-full">
                        <label class="form-label-modern">
                            <span class="label-icon-circle bg-red">
                                <i class="fas fa-home"></i>
                            </span>
                            <span class="label-text-modern">
                                Address
                            </span>
                        </label>
                        <textarea 
                            id="userAddress" 
                            name="address" 
                            class="textarea-modern" 
                            rows="3"
                            placeholder="e.g., Street 123, Sangkat, Khan, Phnom Penh"
                        ></textarea>
                    </div>
                </div>

                <!-- Info Alert -->
                <div class="alert-info-modern">
                    <div class="alert-icon-circle">
                        <i class="fas fa-key"></i>
                    </div>
                    <div class="alert-content-modern">
                        <strong>Automatic Password Generation</strong>
                        <p>A secure temporary password will be generated and displayed after creation. The user must change it on their first login.</p>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="modal-footer-enhanced">
                <button type="button" class="btn-cancel-enhanced" data-close-modal>
                    <i class="fas fa-times"></i>
                    <span>Cancel</span>
                </button>
                <button type="submit" class="btn-submit-enhanced" id="btnCreateUser">
                    <i class="fas fa-user-plus"></i>
                    <span>Create User</span>
                </button>
            </div>
        </form>

    </div>
</div>



<!-- ========== ENHANCED CREATE CLASS MODAL ========== -->
<div class="modal-backdrop" id="classModal">
    <div class="modal-dialog modal-class-create">
        
        <!-- Header with Gradient -->
        <div class="modal-header-gradient">
            <div class="modal-header-content">
                <div class="modal-icon-badge">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="modal-title-group">
                    <h2>Create New Class</h2>
                    <p>Set up a new class with complete details</p>
                </div>
            </div>
            <button class="btn-close-circle" data-close-modal>
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="classForm">
            <div class="modal-body-enhanced">
                
                <!-- Required Section -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-star"></i>
                        <span>Required Information</span>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <label class="form-label-modern">
                                <span class="label-icon-circle bg-purple">
                                    <i class="fas fa-book"></i>
                                </span>
                                <span class="label-text-modern">
                                    Class Name <span class="badge-req">Required</span>
                                </span>
                            </label>
                            <input 
                                type="text" 
                                id="className" 
                                name="name" 
                                class="input-modern" 
                                placeholder="e.g., Web Development 101" 
                                required
                            >
                        </div>

                        <div class="form-col">
                            <label class="form-label-modern">
                                <span class="label-icon-circle bg-orange">
                                    <i class="fas fa-tag"></i>
                                </span>
                                <span class="label-text-modern">
                                    Subject <span class="badge-req">Required</span>
                                </span>
                            </label>
                            <select id="classSubject" name="subject" class="input-modern" required>
                                <option value="">Select subject</option>
                                <option value="Computer Science">Computer Science</option>
                                <option value="Mathematics">Mathematics</option>
                                <option value="Science">Science</option>
                                <option value="English">English</option>
                                <option value="Business">Business</option>
                                <option value="Engineering">Engineering</option>
                                <option value="Arts & Design">Arts & Design</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="form-col">
                            <label class="form-label-modern">
                                <span class="label-icon-circle bg-green">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <span class="label-text-modern">
                                    Academic Year <span class="badge-req">Required</span>
                                </span>
                            </label>
                            <select id="academicYear" name="academic_year" class="input-modern" required>
                                <option value="">Select year</option>
                                <option value="2025-2026" selected>2025-2026</option>
                                <option value="2024-2025">2024-2025</option>
                                <option value="2023-2024">2023-2024</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Optional Section -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-info-circle"></i>
                        <span>Additional Details</span>
                        <span class="section-badge">Optional</span>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <label class="form-label-modern">
                                <span class="label-icon-circle bg-pink">
                                    <i class="fas fa-calendar-week"></i>
                                </span>
                                <span class="label-text-modern">Semester</span>
                            </label>
                            <select id="classSemester" name="semester" class="input-modern">
                                <option value="">Select semester</option>
                                <option value="1st Semester">1st Semester</option>
                                <option value="2nd Semester">2nd Semester</option>
                                <option value="Fall">Fall</option>
                                <option value="Spring">Spring</option>
                                <option value="Summer">Summer</option>
                            </select>
                        </div>

                        <div class="form-col">
                            <label class="form-label-modern">
                                <span class="label-icon-circle bg-red">
                                    <i class="fas fa-clock"></i>
                                </span>
                                <span class="label-text-modern">Schedule</span>
                            </label>
                            <select id="classSchedule" name="schedule" class="input-modern">
                                <option value="">Select schedule</option>
                                <option value="Mon/Wed 8:00-9:30 AM">Mon/Wed 8:00-9:30 AM</option>
                                <option value="Mon/Wed 10:00-11:30 AM">Mon/Wed 10:00-11:30 AM</option>
                                <option value="Mon/Wed 1:00-2:30 PM">Mon/Wed 1:00-2:30 PM</option>
                                <option value="Mon/Wed 3:00-4:30 PM">Mon/Wed 3:00-4:30 PM</option>
                                <option value="Tue/Thu 8:00-9:30 AM">Tue/Thu 8:00-9:30 AM</option>
                                <option value="Tue/Thu 10:00-11:30 AM">Tue/Thu 10:00-11:30 AM</option>
                                <option value="Tue/Thu 1:00-2:30 PM">Tue/Thu 1:00-2:30 PM</option>
                                <option value="Tue/Thu 3:00-4:30 PM">Tue/Thu 3:00-4:30 PM</option>
                                <option value="Fri 8:00-11:00 AM">Fri 8:00-11:00 AM</option>
                                <option value="Fri 1:00-4:00 PM">Fri 1:00-4:00 PM</option>
                            </select>
                        </div>

                        <div class="form-col">
                            <label class="form-label-modern">
                                <span class="label-icon-circle bg-blue">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                <span class="label-text-modern">Room/Location</span>
                            </label>
                            <input 
                                type="text" 
                                id="classRoom" 
                                name="room" 
                                class="input-modern" 
                                placeholder="e.g., Room 301, Building A"
                            >
                        </div>

                        <div class="form-col">
                            <label class="form-label-modern">
                                <span class="label-icon-circle bg-indigo">
                                    <i class="fas fa-users"></i>
                                </span>
                                <span class="label-text-modern">Max Students</span>
                            </label>
                            <input 
                                type="number" 
                                id="maxStudents" 
                                name="max_students" 
                                class="input-modern" 
                                placeholder="e.g., 30" 
                                min="1" 
                                max="500"
                            >
                        </div>

                        <div class="form-col">
                            <label class="form-label-modern">
                                <span class="label-icon-circle bg-yellow">
                                    <i class="fas fa-award"></i>
                                </span>
                                <span class="label-text-modern">Credits</span>
                            </label>
                            <input 
                                type="number" 
                                id="classCredits" 
                                name="credits" 
                                class="input-modern" 
                                placeholder="e.g., 3" 
                                min="0" 
                                max="10" 
                                step="0.5"
                            >
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="form-section">
                    <div class="form-col-full">
                        <label class="form-label-modern">
                            <span class="label-icon-circle bg-violet">
                                <i class="fas fa-align-left"></i>
                            </span>
                            <span class="label-text-modern">Description</span>
                        </label>
                        <textarea 
                            id="classDescription" 
                            name="description" 
                            class="textarea-modern" 
                            rows="4"
                            placeholder="Describe the class objectives, topics covered, prerequisites, and any other important information..."
                        ></textarea>
                        <small class="input-hint">
                            <i class="fas fa-lightbulb"></i>
                            A clear description helps students understand what to expect from this class
                        </small>
                    </div>
                </div>

                <!-- Info Alert -->
                <div class="alert-info-modern">
                    <div class="alert-icon-circle">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="alert-content-modern">
                        <strong>What happens next?</strong>
                        <p>After creating this class, you'll receive a unique class code that students can use to join. You can share this code or display it in class.</p>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="modal-footer-enhanced">
                <button type="button" class="btn-cancel-enhanced" data-close-modal>
                    <i class="fas fa-times"></i>
                    <span>Cancel</span>
                </button>
                <button type="submit" class="btn-submit-enhanced">
                    <i class="fas fa-check-circle"></i>
                    <span>Create Class</span>
                </button>
            </div>
        </form>

    </div>
</div>

<!-- ========== CREDENTIALS POPUP ========== -->
<div class="modal-backdrop" id="modal-credentials">
    <div class="modal-dialog" style="max-width: 500px;">
        <div class="modal-header">
            <h3>User Created Successfully!</h3>
            <button class="btn-icon" data-close-modal><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div style="background: #F3F4F6; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem;">
                <div style="margin-bottom: 1rem;">
                    <label style="font-size: 0.875rem; color: #6B7280;">Name</label>
                    <div id="credName" style="font-weight: 600; font-size: 1.125rem;"></div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="font-size: 0.875rem; color: #6B7280;">Role</label>
                    <div id="credRole" style="font-weight: 600;"></div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="font-size: 0.875rem; color: #6B7280;">Email</label>
                    <div id="credEmail" style="font-family: monospace; color: #4F46E5;"></div>
                </div>
                <div>
                    <label style="font-size: 0.875rem; color: #6B7280;">Temporary Password</label>
                    <div id="credPassword" style="font-family: monospace; font-size: 1.25rem; font-weight: 700; color: #DC2626;"></div>
                </div>
            </div>
            <div style="background: #FEF3C7; border-left: 4px solid #F59E0B; padding: 1rem; border-radius: 4px;">
                <p style="margin: 0; font-size: 0.875rem; color: #92400E;">
                    <i class="fas fa-exclamation-triangle"></i> Please save this password. It cannot be retrieved later.
                </p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="copyAllCredentials()">
                <i class="fas fa-copy"></i> Copy All
            </button>
            <button class="btn btn-primary" onclick="printCredentials()">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

@php
    $currentUser = auth()->check() ? auth()->user()->only(['id', 'name', 'email', 'role']) : null;
@endphp

<script>
    window.Laravel = {
        csrfToken: '{{ csrf_token() }}',
        user: @json($currentUser),
        routes: {
            stats: '{{ route("admin.api.stats") }}',
            users: '{{ route("admin.api.users") }}',
            recentUsers: '{{ route("admin.api.recent-users") }}',
            passwordRequests: '{{ route("admin.api.password-requests") }}',
            classes: '{{ route("admin.api.classes") }}',
        }
    };

    @if(session('credentials'))
    document.addEventListener('DOMContentLoaded', function() {
        const creds = @json(session('credentials'));
        if (typeof dashboard !== 'undefined') {
            dashboard.showCredentialsPopup(creds);
        }
    });
    @endif
</script>

@endsection
