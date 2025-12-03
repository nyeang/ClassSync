// ========== DASHBOARD STATE ==========
const dashboard = {
    stats: {},
    users: [],
    classes: [],
    
    // Initialize
    init() {
        this.loadStats();
        this.loadRecentUsers();
        this.loadPasswordRequests();
        this.setupEventListeners();
    },

    // Setup Event Listeners
    setupEventListeners() {
        // User Modal
        document.getElementById('btnOpenUserModal')?.addEventListener('click', () => {
            this.openModal('userModal');
        });
        
        // Class Modal - UPDATED
        document.getElementById('btnOpenClassModal')?.addEventListener('click', () => {
            this.openModal('classModal');
        });

        // User Form Submit
        document.getElementById('btnCreateUser')?.addEventListener('click', (e) => {
            e.preventDefault();
            this.createUser();
        });

        // Class Form Submit - UPDATED
        document.getElementById('classForm')?.addEventListener('submit', (e) => {
            e.preventDefault();
            this.createClass();
        });

        // Close modals
        document.querySelectorAll('[data-close-modal]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const modal = e.target.closest('.modal-backdrop');
                if (modal) this.closeModal(modal.id);
            });
        });

        // Close modal on backdrop click
        document.querySelectorAll('.modal-backdrop').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.closeModal(modal.id);
                }
            });
        });
    },

    // Load Stats
    async loadStats() {
        try {
            const response = await fetch(window.Laravel.routes.stats);
            const data = await response.json();
            
            if (data.success) {
                this.stats = data.data;
                this.renderStats();
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    },

    // Render Stats
    renderStats() {
        document.getElementById('totalUsers').textContent = this.stats.total_users || 0;
        document.getElementById('activeClasses').textContent = this.stats.active_classes || 0;
        document.getElementById('pendingRequests').textContent = this.stats.pending_password_requests || 0;
        document.getElementById('totalTeachers').textContent = this.stats.total_teachers || 0;

        // Update trends
        this.updateTrend('usersTrend', 'usersTrendBadge', this.stats.users_trend || 0);
        this.updateTrend('classesTrend', 'classesTrendBadge', this.stats.classes_trend || 0);
        this.updateTrend('requestsTrend', 'requestsTrendBadge', this.stats.requests_trend || 0);
        this.updateTrend('teachersTrend', 'teachersTrendBadge', this.stats.teachers_trend || 0);
    },

    // Update Trend
    updateTrend(trendId, badgeId, value) {
        const trendEl = document.getElementById(trendId);
        const badgeEl = document.getElementById(badgeId);
        
        if (trendEl && badgeEl) {
            trendEl.textContent = Math.abs(value);
            badgeEl.className = 'stat-card-trend';
            badgeEl.classList.add(value >= 0 ? 'up' : 'down');
            badgeEl.innerHTML = `<i class="fas fa-arrow-${value >= 0 ? 'up' : 'down'}"></i> <span>${Math.abs(value)}%</span>`;
        }
    },

    // Load Recent Users
    async loadRecentUsers() {
        try {
            const response = await fetch(window.Laravel.routes.recentUsers);
            const data = await response.json();
            
            if (data.success) {
                this.users = data.data;
                this.renderRecentUsers();
            }
        } catch (error) {
            console.error('Error loading users:', error);
            document.getElementById('recentUsersTable').innerHTML = `
                <tr><td colspan="3" class="text-center" style="color: var(--danger);">Failed to load users</td></tr>
            `;
        }
    },

    // Render Recent Users
    renderRecentUsers() {
        const tbody = document.getElementById('recentUsersTable');
        
        if (!this.users.length) {
            tbody.innerHTML = `<tr><td colspan="3" class="text-center">No users found</td></tr>`;
            return;
        }

        tbody.innerHTML = this.users.map(user => `
            <tr>
                <td>
                    <div class="user-cell">
                        <div class="user-avatar ${user.role.toLowerCase()}">
                            ${user.name.charAt(0).toUpperCase()}
                        </div>
                        <div class="user-info">
                            <div class="user-name">${user.name}</div>
                            <div class="user-email">${user.email}</div>
                        </div>
                    </div>
                </td>
                <td><span class="role-badge ${user.role.toLowerCase()}">${user.role}</span></td>
                <td><span class="status-badge active"><i class="fas fa-circle"></i> Active</span></td>
            </tr>
        `).join('');
    },

    // Load Password Requests
    async loadPasswordRequests() {
        try {
            const response = await fetch(window.Laravel.routes.passwordRequests);
            const data = await response.json();
            
            if (data.success) {
                this.renderPasswordRequests(data.data);
            }
        } catch (error) {
            console.error('Error loading password requests:', error);
            document.getElementById('passwordRequestsList').innerHTML = `
                <div class="text-center" style="color: var(--danger);">Failed to load requests</div>
            `;
        }
    },

    // Render Password Requests
    renderPasswordRequests(requests) {
        const container = document.getElementById('passwordRequestsList');
        
        if (!requests.length) {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
                    <h4>No Pending Requests</h4>
                    <p>All password requests have been processed</p>
                </div>
            `;
            return;
        }

        container.innerHTML = requests.map(req => `
            <div class="request-item">
                <div class="request-item-left">
                    <div class="request-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="request-info">
                        <h4>${req.name}</h4>
                        <p>${req.email} • ${req.created_at}</p>
                    </div>
                </div>
                <div class="request-actions">
                    <button class="btn btn-sm btn-success" onclick="dashboard.approveRequest(${req.id})">
                        <i class="fas fa-check"></i> Approve
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="dashboard.rejectRequest(${req.id})">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </div>
            </div>
        `).join('');
    },

    // ========== CREATE USER WITH VALIDATION ==========
async createUser() {
    const form = document.getElementById('createUserForm');
    const formData = new FormData(form);
    
    const data = {
        name: formData.get('name')?.trim(),
        email: formData.get('email')?.trim(),
        role: formData.get('role'),
        gender: formData.get('gender') || null,
        date_of_birth: formData.get('date_of_birth') || null,
        phone: formData.get('phone')?.trim() || null,
        department: formData.get('department') || null,
        academic_year: formData.get('academic_year') || null,
        address: formData.get('address')?.trim() || null
    };

    // ===== VALIDATION =====
    const errors = [];

    // Required: Name
    if (!data.name || data.name.length === 0) {
        errors.push('Full name is required');
    } else if (data.name.length > 255) {
        errors.push('Full name must be less than 255 characters');
    }

    // Required: Email
    if (!data.email || data.email.length === 0) {
        errors.push('Email is required');
    } else if (data.email.length > 255) {
        errors.push('Email must be less than 255 characters');
    }

    // Required: Role
    if (!data.role) {
        errors.push('User role is required');
    } else if (!['Teacher', 'Student'].includes(data.role)) {
        errors.push('Invalid user role selected');
    }

    // Optional: Gender
    if (data.gender && data.gender.length > 20) {
        errors.push('Gender must be less than 20 characters');
    }

    // Optional: Date of Birth
    if (data.date_of_birth) {
        const birthDate = new Date(data.date_of_birth);
        const today = new Date();
        if (birthDate > today) {
            errors.push('Date of birth cannot be in the future');
        }
    }

    // Optional: Phone
    if (data.phone && data.phone.length > 20) {
        errors.push('Phone number must be less than 20 characters');
    }

    // Optional: Department
    if (data.department && data.department.length > 100) {
        errors.push('Department must be less than 100 characters');
    }

    // Optional: Academic Year
    if (data.academic_year && data.academic_year.length > 20) {
        errors.push('Academic year must be less than 20 characters');
    }

    // Show errors if any
    if (errors.length > 0) {
        this.showToast(errors[0], 'error');
        return;
    }

    // ===== SUBMIT =====
    try {
        const response = await fetch('/admin/users', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            this.showToast('User created successfully!', 'success');
            this.closeModal('userModal');
            form.reset();

            // Show credentials popup
            if (result.data.password) {
                this.showCredentialsPopup(result.data);
            }

            // Reload data
            this.loadStats();
            this.loadRecentUsers();
        } else {
            this.showToast(result.message || 'Failed to create user', 'error');
        }
    } catch (error) {
        console.error('Error creating user:', error);
        this.showToast('An error occurred. Please try again.', 'error');
    }
},

// ========== CREATE CLASS WITH VALIDATION ==========
async createClass() {
    const form = document.getElementById('classForm');
    const formData = new FormData(form);
    
    const data = {
        name: formData.get('name')?.trim(),
        subject: formData.get('subject')?.trim(),
        academic_year: formData.get('academic_year')?.trim(),
        semester: formData.get('semester')?.trim() || null,
        schedule: formData.get('schedule')?.trim() || null,
        room: formData.get('room')?.trim() || null,
        max_students: formData.get('max_students') || null,
        credits: formData.get('credits') || null,
        description: formData.get('description')?.trim() || null
    };

    // ===== VALIDATION =====
    const errors = [];

    // Required: Name
    if (!data.name || data.name.length === 0) {
        errors.push('Class name is required');
    } else if (data.name.length > 100) {
        errors.push('Class name must be less than 100 characters');
    }

    // Required: Subject
    if (!data.subject || data.subject.length === 0) {
        errors.push('Subject is required');
    } else if (data.subject.length > 100) {
        errors.push('Subject must be less than 100 characters');
    }

    // Required: Academic Year
    if (!data.academic_year || data.academic_year.length === 0) {
        errors.push('Academic year is required');
    } else if (data.academic_year.length > 20) {
        errors.push('Academic year must be less than 20 characters');
    }

    // Optional: Semester
    if (data.semester && data.semester.length > 50) {
        errors.push('Semester must be less than 50 characters');
    }

    // Optional: Schedule
    if (data.schedule && data.schedule.length > 100) {
        errors.push('Schedule must be less than 100 characters');
    }

    // Optional: Room
    if (data.room && data.room.length > 100) {
        errors.push('Room must be less than 100 characters');
    }

    // Optional: Max Students
    if (data.max_students) {
        const maxStudents = parseInt(data.max_students);
        if (isNaN(maxStudents)) {
            errors.push('Max students must be a number');
        } else if (maxStudents < 1) {
            errors.push('Max students must be at least 1');
        } else if (maxStudents > 500) {
            errors.push('Max students cannot exceed 500');
        }
    }

    // Optional: Credits
    if (data.credits) {
        const credits = parseFloat(data.credits);
        if (isNaN(credits)) {
            errors.push('Credits must be a number');
        } else if (credits < 0) {
            errors.push('Credits cannot be negative');
        } else if (credits > 10) {
            errors.push('Credits cannot exceed 10');
        }
    }

    // Optional: Description
    if (data.description && data.description.length > 1000) {
        errors.push('Description must be less than 1000 characters');
    }

    // Show errors if any
    if (errors.length > 0) {
        this.showToast(errors[0], 'error');
        return;
    }

    // ===== SUBMIT =====
    try {
        const response = await fetch('/admin/classes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            this.showToast('Class created successfully!', 'success');
            this.closeModal('classModal');
            form.reset();

            // Show class code
            if (result.data.class_code) {
                this.showToast(`Class Code: ${result.data.class_code}`, 'success', 5000);
            }

            // Reload stats
            this.loadStats();
        } else {
            this.showToast(result.message || 'Failed to create class', 'error');
        }
    } catch (error) {
        console.error('Error creating class:', error);
        this.showToast('An error occurred. Please try again.', 'error');
    }
},


    // Show Credentials Popup
    showCredentialsPopup(credentials) {
        document.getElementById('credName').textContent = credentials.name;
        document.getElementById('credRole').textContent = credentials.role;
        document.getElementById('credEmail').textContent = credentials.email;
        document.getElementById('credPassword').textContent = credentials.password;
        
        this.openModal('modal-credentials');
    },

    // Modal Functions
    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
    },

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('open');
            document.body.style.overflow = '';
        }
    },

    // Show Toast
    showToast(message, type = 'success', duration = 3000) {
        const container = document.getElementById('toastContainer') || this.createToastContainer();
        
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        const icon = type === 'success' ? 'fa-check-circle' : 
                     type === 'error' ? 'fa-exclamation-circle' : 
                     'fa-info-circle';
        
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas ${icon}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">${type.charAt(0).toUpperCase() + type.slice(1)}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('hiding');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    },

    createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container';
        document.body.appendChild(container);
        return container;
    },

    // Approve Password Request
    async approveRequest(id) {
        if (!confirm('Approve this password reset request?')) return;

        try {
            const response = await fetch(`/admin/password-requests/${id}/approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.Laravel.csrfToken,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                this.showToast('Request approved successfully', 'success');
                this.loadPasswordRequests();
                this.loadStats();
            } else {
                this.showToast(result.message || 'Failed to approve request', 'error');
            }
        } catch (error) {
            console.error('Error approving request:', error);
            this.showToast('An error occurred', 'error');
        }
    },

    // Reject Password Request
    async rejectRequest(id) {
        if (!confirm('Reject this password reset request?')) return;

        try {
            const response = await fetch(`/admin/password-requests/${id}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.Laravel.csrfToken,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                this.showToast('Request rejected', 'success');
                this.loadPasswordRequests();
                this.loadStats();
            } else {
                this.showToast(result.message || 'Failed to reject request', 'error');
            }
        } catch (error) {
            console.error('Error rejecting request:', error);
            this.showToast('An error occurred', 'error');
        }
    }
};

// Copy credentials to clipboard
function copyAllCredentials() {
    const name = document.getElementById('credName').textContent;
    const role = document.getElementById('credRole').textContent;
    const email = document.getElementById('credEmail').textContent;
    const password = document.getElementById('credPassword').textContent;
    
    const text = `Name: ${name}\nRole: ${role}\nEmail: ${email}\nPassword: ${password}`;
    
    navigator.clipboard.writeText(text).then(() => {
        dashboard.showToast('Credentials copied to clipboard!', 'success');
    });
}

// Print credentials
function printCredentials() {
    window.print();
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    dashboard.init();
});

// Mobile menu toggle
document.querySelector('.menu-toggle')?.addEventListener('click', () => {
    document.querySelector('.sidebar').classList.toggle('open');
    document.querySelector('.sidebar-overlay')?.classList.toggle('open');
});

document.querySelector('.sidebar-overlay')?.addEventListener('click', () => {
    document.querySelector('.sidebar').classList.remove('open');
    document.querySelector('.sidebar-overlay').classList.remove('open');
});
