// ========================================
// ClassSync Teacher Dashboard - JavaScript
// ========================================

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
    setupEventListeners();
});

// ========================================
// INITIALIZATION
// ========================================

function initializeDashboard() {
    console.log('ClassSync Dashboard initialized');
}

// ========================================
// EVENT LISTENERS
// ========================================

function setupEventListeners() {
    // Menu toggle
    const menuBtn = document.getElementById('menuBtn');
    const sidebar = document.getElementById('sidebar');

    if (menuBtn) {
        menuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            sidebar.classList.toggle('hidden');
        });
    }

    // Filter tabs
    const filterTabs = document.querySelectorAll('.tab-btn');
    filterTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active from all tabs
            filterTabs.forEach(t => t.classList.remove('active'));
            // Add active to clicked tab
            tab.classList.add('active');

            // Filter classes
            const filter = tab.dataset.filter;
            filterClasses(filter);
        });
    });

    // Create class form
    const createClassForm = document.getElementById('createClassForm');
    if (createClassForm) {
        createClassForm.addEventListener('submit', handleCreateClass);
    }

    // Close modals on outside click
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal(modal.id);
            }
        });
    });
}

// ========================================
// FILTER CLASSES
// ========================================

function filterClasses(filter) {
    const classCards = document.querySelectorAll('.class-card');

    classCards.forEach(card => {
        const year = card.dataset.year;

        if (filter === 'all') {
            card.style.display = 'block';
        } else if (filter === 'archived') {
            // Show only archived classes (you'd check a data-archived attribute)
            card.style.display = 'none';
        } else {
            // Filter by year
            if (year === filter) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        }
    });
}

// ========================================
// COPY CLASS CODE
// ========================================

function copyCode(code) {
    // Copy to clipboard
    navigator.clipboard.writeText(code).then(() => {
        // Show success message
        showToast('Class code copied: ' + code, 'success');
    }).catch(err => {
        console.error('Failed to copy:', err);
        showToast('Failed to copy code', 'error');
    });
}

// ========================================
// QR CODE MODAL
// ========================================

function showQRModal(classCode) {
    const modal = document.getElementById('qrModal');
    const qrcodeContainer = document.getElementById('qrcode');
    const modalClassCode = document.getElementById('modalClassCode');

    // Clear previous QR code
    qrcodeContainer.innerHTML = '';

    // Set class code
    modalClassCode.textContent = classCode;

    // Generate QR code
    const qrCodeUrl = `https://classsync.app/join?code=${classCode}`;

    // Check if QRCode library is loaded
    if (typeof QRCode !== 'undefined') {
        new QRCode(qrcodeContainer, {
            text: qrCodeUrl,
            width: 256,
            height: 256,
            colorDark: '#4F46E5',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    } else {
        // Fallback: Show text
        qrcodeContainer.innerHTML = `<div style="padding: 40px; background: #f3f4f6; border-radius: 8px;">
            <p style="margin-bottom: 12px; font-weight: 600;">QR Code URL:</p>
            <p style="font-size: 12px; word-break: break-all; color: #6B7280;">${qrCodeUrl}</p>
            <p style="margin-top: 16px; font-size: 13px; color: #9CA3AF;">QR Code library not loaded</p>
        </div>`;
    }

    // Show modal
    modal.classList.add('active');
}

function downloadQR() {
    const qrcodeCanvas = document.querySelector('#qrcode canvas');

    if (qrcodeCanvas) {
        // Convert canvas to image and download
        const link = document.createElement('a');
        link.download = 'class-qr-code.png';
        link.href = qrcodeCanvas.toDataURL();
        link.click();

        showToast('QR code downloaded', 'success');
    } else {
        showToast('QR code not available', 'error');
    }
}

function printQR() {
    const printWindow = window.open('', '', 'height=600,width=800');
    const qrcodeCanvas = document.querySelector('#qrcode canvas');
    const classCode = document.getElementById('modalClassCode').textContent;

    if (qrcodeCanvas) {
        const imgData = qrcodeCanvas.toDataURL();

        printWindow.document.write(`
            <html>
            <head>
                <title>Print QR Code - ${classCode}</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        text-align: center;
                        padding: 40px;
                    }
                    h1 {
                        font-size: 32px;
                        margin-bottom: 20px;
                        color: #4F46E5;
                    }
                    .code {
                        font-size: 24px;
                        font-family: 'Courier New', monospace;
                        font-weight: bold;
                        margin: 20px 0;
                        color: #1F2937;
                    }
                    img {
                        margin: 30px 0;
                    }
                    .instructions {
                        font-size: 16px;
                        color: #6B7280;
                        margin-top: 30px;
                    }
                    @media print {
                        body { padding: 20px; }
                    }
                </style>
            </head>
            <body>
                <h1>Join Class</h1>
                <div class="code">Class Code: ${classCode}</div>
                <img src="${imgData}" alt="QR Code" />
                <div class="instructions">
                    <p>Scan this QR code with your phone camera to join the class</p>
                    <p>Or enter the class code manually</p>
                </div>
            </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.focus();

        // Print after a short delay to ensure content is loaded
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 250);
    } else {
        showToast('QR code not available', 'error');
    }
}

function resetCode() {
    const classCode = document.getElementById('modalClassCode').textContent;

    if (confirm(`Are you sure you want to reset the class code for ${classCode}? The old code will no longer work and students will need the new code to join.`)) {
        // Here you would make an API call to reset the code
        // For demo purposes, we'll just show a message

        showToast('Generating new code...', 'info');

        // Simulate API call
        setTimeout(() => {
            const newCode = generateRandomCode();
            showToast(`New code generated: ${newCode}`, 'success');
            closeModal('qrModal');
        }, 1000);
    }
}

function generateRandomCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';
    for (let i = 0; i < 8; i++) {
        code += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return code;
}

// ========================================
// CREATE CLASS MODAL
// ========================================

function showCreateClassModal() {
    const modal = document.getElementById('createClassModal');
    modal.classList.add('active');
}

function handleCreateClass(e) {
    e.preventDefault();

    const className = document.getElementById('className').value;
    const academicYear = document.getElementById('academicYear').value;
    const section = document.getElementById('section').value;
    const description = document.getElementById('description').value;

    console.log('Creating class:', { className, academicYear, section, description });

    // Here you would make an API call to create the class
    // For demo purposes, we'll just show a success message

    showToast('Creating class...', 'info');

    // Simulate API call
    setTimeout(() => {
        showToast(`Class "${className}" created successfully!`, 'success');
        closeModal('createClassModal');

        // Reset form
        document.getElementById('createClassForm').reset();

        // In a real app, you would add the new class card to the grid
        addNewClassCard({ className, academicYear, section, description });
    }, 1000);
}

function addNewClassCard(classData) {
    const classGrid = document.getElementById('classGrid');
    const newCode = generateRandomCode();

    const newCard = document.createElement('div');
    newCard.className = 'class-card';
    newCard.dataset.year = classData.academicYear;

    const gradients = [
        'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
        'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
        'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)'
    ];
    const randomGradient = gradients[Math.floor(Math.random() * gradients.length)];

    newCard.innerHTML = `
        <div class="class-cover" style="background: ${randomGradient};">
            <img src="https://images.unsplash.com/photo-1501504905252-473c47e087f8?w=400&h=200&fit=crop" alt="${classData.className}">
        </div>
        <div class="class-body">
            <h3 class="class-title">${classData.className}</h3>
            <p class="class-meta">${classData.academicYear}${classData.section ? ' • ' + classData.section : ''}</p>
            <div class="class-code-section">
                <span class="class-code">${newCode}</span>
                <button class="btn-icon" onclick="copyCode('${newCode}')" title="Copy code">
                    <i class="fas fa-copy"></i>
                </button>
                <button class="btn-icon" onclick="showQRModal('${newCode}')" title="View QR">
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
                    <span>0 Students</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-clipboard-list"></i>
                    <span>0 Assignments</span>
                </div>
            </div>
            <div class="class-attention success-attention">
                <i class="fas fa-check-circle"></i>
                <span>New class created!</span>
            </div>
            <button class="btn-primary btn-full" onclick="enterClass('${classData.className}')">
                Enter Class <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    `;

    // Add animation
    newCard.style.opacity = '0';
    newCard.style.transform = 'scale(0.9)';

    classGrid.insertBefore(newCard, classGrid.firstChild);

    // Animate in
    setTimeout(() => {
        newCard.style.transition = 'all 0.3s ease';
        newCard.style.opacity = '1';
        newCard.style.transform = 'scale(1)';
    }, 10);
}

// ========================================
// ENTER CLASS
// ========================================

function enterClass(className) {
    showToast(`Entering ${className}...`, 'info');

    // In a real app, this would navigate to the class detail page
    setTimeout(() => {
        alert(`This would navigate to the class page for "${className}"\n\nThere you would see:\n- Stream tab\n- Classwork tab\n- People tab\n- Grades tab\n- Teams tab`);
    }, 500);
}

// ========================================
// MODAL UTILITIES
// ========================================

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
    }
}

// Close modal on ESC key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        const activeModal = document.querySelector('.modal.active');
        if (activeModal) {
            activeModal.classList.remove('active');
        }
    }
});

// ========================================
// TOAST NOTIFICATIONS
// ========================================

function showToast(message, type = 'info') {
    // Remove existing toasts
    const existingToast = document.querySelector('.toast');
    if (existingToast) {
        existingToast.remove();
    }

    // Create toast
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;

    const icons = {
        success: 'fa-check-circle',
        error: 'fa-times-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };

    const colors = {
        success: '#10B981',
        error: '#EF4444',
        warning: '#F59E0B',
        info: '#4F46E5'
    };

    toast.innerHTML = `
        <i class="fas ${icons[type]}"></i>
        <span>${message}</span>
    `;

    toast.style.cssText = `
        position: fixed;
        bottom: 24px;
        right: 24px;
        background: white;
        color: ${colors[type]};
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        font-weight: 500;
        z-index: 10000;
        animation: slideIn 0.3s ease;
        border-left: 4px solid ${colors[type]};
    `;

    document.body.appendChild(toast);

    // Remove after 3 seconds
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

// Add animations to CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// ========================================
// UTILITY FUNCTIONS
// ========================================

// Format date
function formatDate(date) {
    const options = { month: 'short', day: 'numeric', year: 'numeric' };
    return new Date(date).toLocaleDateString('en-US', options);
}

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Search functionality (if needed)
const searchInput = document.querySelector('.search-box input');
if (searchInput) {
    const handleSearch = debounce((e) => {
        const query = e.target.value.toLowerCase();
        const classCards = document.querySelectorAll('.class-card');

        classCards.forEach(card => {
            const title = card.querySelector('.class-title').textContent.toLowerCase();
            if (title.includes(query)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }, 300);

    searchInput.addEventListener('input', handleSearch);
}

console.log('ClassSync Dashboard JavaScript loaded successfully!');
