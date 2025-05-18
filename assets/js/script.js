/**
 * CronyTech Karaoke Reservation System
 * Main JavaScript File
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Room Availability Check
    const dateInput = document.getElementById('reservation-date');
    const roomSelect = document.getElementById('room-id');
    
    if (dateInput && roomSelect) {
        dateInput.addEventListener('change', checkAvailability);
        roomSelect.addEventListener('change', checkAvailability);
    }

    function checkAvailability() {
        const date = dateInput.value;
        const roomId = roomSelect.value;
        const timeSlots = document.getElementById('time-slots');
        
        if (!date || !roomId) return;
        
        fetch('check_availability.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `date=${date}&room_id=${roomId}`
        })
        .then(response => response.json())
        .then(data => {
            // Clear previous time slots
            timeSlots.innerHTML = '';
            
            // Generate time slots based on availability
            if (data.available_slots && data.available_slots.length > 0) {
                data.available_slots.forEach(slot => {
                    const option = document.createElement('div');
                    option.className = 'col-md-3 mb-3';
                    option.innerHTML = `
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="time_slot" 
                                id="slot-${slot.start_time}" value="${slot.start_time}-${slot.end_time}">
                            <label class="form-check-label" for="slot-${slot.start_time}">
                                ${slot.start_time} - ${slot.end_time}
                            </label>
                        </div>
                    `;
                    timeSlots.appendChild(option);
                });
            } else {
                timeSlots.innerHTML = '<div class="col-12 text-danger">No available time slots for this date</div>';
            }
        })
        .catch(error => {
            console.error('Error checking availability:', error);
            timeSlots.innerHTML = '<div class="col-12 text-danger">Error checking availability. Please try again.</div>';
        });
    }

    // Form Validation
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });

    // Package Selection
    const packageItems = document.querySelectorAll('.package-selection .card');
    
    if (packageItems.length > 0) {
        packageItems.forEach(item => {
            item.addEventListener('click', function() {
                // Remove selection from all cards
                packageItems.forEach(pkg => pkg.classList.remove('border-primary'));
                
                // Add selection to clicked card
                this.classList.add('border-primary');
                
                // Update hidden input with selected package id
                const packageId = this.getAttribute('data-package-id');
                document.getElementById('selected-package').value = packageId;
            });
        });
    }

    // Display confirmation modals
    const confirmationBtns = document.querySelectorAll('[data-confirm="true"]');
    
    confirmationBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to perform this action?')) {
                e.preventDefault();
            }
        });
    });

    // Dynamic toast notifications
    const toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container';
    document.body.appendChild(toastContainer);

    // Show toast notification
    window.showToast = function(message, type = 'success') {
        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center text-white bg-${type} border-0`;
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');
        
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        toastContainer.appendChild(toastEl);
        
        const toast = new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 5000
        });
        
        toast.show();
        
        // Remove toast element after it's hidden
        toastEl.addEventListener('hidden.bs.toast', function() {
            toastEl.remove();
        });
    }

    // If there's a PHP success or error message, display as toast
    const successMsg = document.querySelector('.alert-success');
    const errorMsg = document.querySelector('.alert-danger');

    if (successMsg) {
        window.showToast(successMsg.textContent, 'success');
        successMsg.remove();
    }

    if (errorMsg) {
        window.showToast(errorMsg.textContent, 'danger');
        errorMsg.remove();
    }
});