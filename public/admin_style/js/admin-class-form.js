// ========================================
//  ADMIN MODALS HANDLER - USER & CLASS
// ========================================

const adminModals = {

    // Initialize
    init() {
        this.setupModalTriggers();
        this.setupModalClose();
        this.setupUserForm();
        this.setupClassForm();
        this.loadTeachers();
    },

    // Setup Modal Triggers
    setupModalTriggers() {
        // Open User Modal
        document.getElementById('btnOpenUserModal')?.addEventListener('click', () => {
            this.openModal('userModal');
        });

        // Open Class Modal
        document.getElementById('btnOpenClassModal')?.addEventListener('click', () => {
            this.openModal('classModal');
        });
    },

    // Setup Modal Close
    setupModalClose() {
        // Close buttons
        document.querySelectorAll('[data-close-modal]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const modal = e.target.closest('.modal-backdrop');
                if (modal) this.closeModal(modal.id);
            });
        });

        // Close on backdrop click
        document.querySelectorAll('.modal-backdrop').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.closeModal(modal.id);
                }
            });
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const openModal = document.querySelector('.modal-backdrop.open');
                if (openModal) {
                    this.closeModal(openModal.id);
                }
            }
        });
    },

    // Setup User Form
    setupUserForm() {
        const form = document.getElementById('createUserForm');
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            await this.handleUserSubmit(form);
        });

        // Role change handler
        const roleSelect = document.getElementById('userRole');
        roleSelect?.addEventListener('change', (e) => {
            this.handleRoleChange(e.target.value);
        });
    },

    // Setup Class Form
    setupClassForm() {
        const form = document.getElementById('classForm');
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            await this.handleClassSubmit(form);
        });
    },

    // Handle User Form Submit
    async handleUserSubmit(form) {
        const submitBtn = document.getElementById('btnCreateUser');
        const originalHTML = submitBtn.innerHTML;

        // Disable and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';

        try {
            const formData = new FormData(form);

            // Combine email username with domain
            const emailUsername = formData.get('email');
            formData.set('email', emailUsername + '@paragoniu.edu.kh');

            const data = Object.fromEntries(formData.entries());

            const response = await fetch('/admin/users', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.Laravel.csrfToken
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                this.showToast('User created successfully!', 'success');

                // Show credentials popup if provided
                if (result.credentials) {
                    this.showCredentials(result.credentials, result.data);
                }

                this.closeModal('userModal');
                form.reset();

                // Reload users if dashboard exists
                if (typeof dashboard !== 'undefined' && dashboard.loadRecentUsers) {
                    dashboard.loadRecentUsers();
                    dashboard.loadStats();
                }
            } else {
                throw new Error(result.message || 'Failed to create user');
            }
        } catch (error) {
            console.error('Error creating user:', error);
            this.showToast(error.message || 'Failed to create user', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHTML;
        }
    },

    // Handle Class Form Submit
    async handleClassSubmit(form) {
        const submitBtn = document.getElementById('btnCreateClass');
        const originalHTML = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';

        try {
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            const response = await fetch('/admin/classes', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.Laravel.csrfToken
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                this.showToast('Class created successfully!', 'success');
                this.closeModal('classModal');
                form.reset();

                // Reload classes if dashboard exists
                if (typeof dashboard !== 'undefined' && dashboard.loadStats) {
                    dashboard.loadStats();
                }
            } else {
                throw new Error(result.message || 'Failed to create class');
            }
        } catch (error) {
            console.error('Error creating class:', error);
            this.showToast(error.message || 'Failed to create class', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHTML;
        }
    },

    // Load Teachers for Class Form
    async loadTeachers() {
        try {
            const response = await fetch('/admin/api/teachers');
            const data = await response.json();

            if (data.success) {
                const select = document.getElementById('teacherSelect');
                if (!select) return;

                select.innerHTML = '<option value="">Select a teacher (optional)</option>';

                data.data.forEach(teacher => {
                    const option = document.createElement('option');
                    option.value = teacher.id;
                    option.textContent = `${teacher.name}${teacher.department ? ' - ' + teacher.department : ''}`;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading teachers:', error);
        }
    },

    // Handle Role Change
    handleRoleChange(role) {
        const yearPositionLabel = document.getElementById('yearPositionLabel');
        const yearSelect = document.getElementById('userYear');

        if (role === 'Teacher') {
            yearPositionLabel.textContent = 'Position';
            yearSelect.innerHTML = `
                <option value="">Select position</option>
                <option value="Professor">Professor</option>
                <option value="Associate Professor">Associate Professor</option>
                <option value="Assistant Professor">Assistant Professor</option>
                <option value="Lecturer">Lecturer</option>
                <option value="Teaching Assistant">Teaching Assistant</option>
            `;
        } else {
            yearPositionLabel.textContent = 'Academic Year';
            yearSelect.innerHTML = `
                <option value="">Select year</option>
                <option value="Year 1">Year 1</option>
                <option value="Year 2">Year 2</option>
                <option value="Year 3">Year 3</option>
                <option value="Year 4">Year 4</option>
            `;
        }
    },

    // Show Credentials Popup
    showCredentials(credentials, userData) {
        const modal = document.getElementById('modal-credentials');
        if (!modal) return;

        document.getElementById('credName').textContent = userData.name;
        document.getElementById('credRole').textContent = userData.role;
        document.getElementById('credEmail').textContent = credentials.email;
        document.getElementById('credPassword').textContent = credentials.password;

        this.openModal('modal-credentials');
    },

    // Open Modal
    // Open Modal
    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('open');
            document.body.classList.add('modal-open'); // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }
    },

    // Close Modal
    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('open');
            document.body.classList.remove('modal-open'); // Restore body scroll
            document.body.style.overflow = '';
        }
    },


    // Show Toast Notification
    showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer') || this.createToastContainer();

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;

        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle'
        };

        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas ${icons[type] || icons.success}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close">
                <i class="fas fa-times"></i>
            </button>
        `;

        container.appendChild(toast);

        // Close button
        toast.querySelector('.toast-close').addEventListener('click', () => {
            toast.classList.add('hiding');
            setTimeout(() => toast.remove(), 300);
        });

        // Auto remove
        setTimeout(() => {
            toast.classList.add('hiding');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    },

    // Create Toast Container
    createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toastContainer';
        container.style.cssText = 'position: fixed; top: 80px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px;';
        document.body.appendChild(container);
        return container;
    }
};

// Copy Credentials Function
function copyAllCredentials() {
    const name = document.getElementById('credName').textContent;
    const role = document.getElementById('credRole').textContent;
    const email = document.getElementById('credEmail').textContent;
    const password = document.getElementById('credPassword').textContent;

    const text = `Name: ${name}\nRole: ${role}\nEmail: ${email}\nPassword: ${password}`;

    navigator.clipboard.writeText(text).then(() => {
        adminModals.showToast('Credentials copied to clipboard!', 'success');
    });
}

// Print Credentials Function
function printCredentials() {
    window.print();
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    adminModals.init();
});
