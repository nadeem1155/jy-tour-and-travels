/**
 * JY TOUR and TRAVELS - Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Initialize Travel Date Pickers
    const travelDateInput = document.getElementById('travel_date');
    const returnDateInput = document.getElementById('return_date');

    if (travelDateInput) {
        const today = new Date().toISOString().split('T')[0];
        travelDateInput.min = today;

        travelDateInput.addEventListener('change', function () {
            if (returnDateInput) {
                returnDateInput.min = this.value;
                if (returnDateInput.value && returnDateInput.value < this.value) {
                    returnDateInput.value = this.value;
                }
            }
        });
    }

    // 2. Auto-select Vehicle in Booking Form from Query Param or Click
    const urlParams = new URLSearchParams(window.location.search);
    const preVehicle = urlParams.get('vehicle');
    const vehicleSelect = document.getElementById('vehicle_name');

    if (preVehicle && vehicleSelect) {
        for (let i = 0; i < vehicleSelect.options.length; i++) {
            if (vehicleSelect.options[i].value.toLowerCase().includes(preVehicle.toLowerCase())) {
                vehicleSelect.selectedIndex = i;
                break;
            }
        }
    }

    // 3. Smooth scrolling for hash links
    document.querySelectorAll('a[href^="#"]:not([href="#"]):not([data-bs-toggle])').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            const targetEl = document.querySelector(targetId);
            if (targetEl) {
                e.preventDefault();
                targetEl.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // 4. Gallery Filter Tabs
    const filterButtons = document.querySelectorAll('.gallery-btn');
    const galleryItems = document.querySelectorAll('.gallery-col');

    if (filterButtons.length && galleryItems.length) {
        filterButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                filterButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filter = this.getAttribute('data-filter');

                galleryItems.forEach(item => {
                    const category = item.getAttribute('data-category');
                    if (filter === 'all' || category === filter) {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.style.opacity = '1';
                        }, 50);
                    } else {
                        item.style.opacity = '0';
                        setTimeout(() => {
                            item.style.display = 'none';
                        }, 250);
                    }
                });
            });
        });
    }

    // 5. Gallery Lightbox Modal
    const galleryModals = document.querySelectorAll('.gallery-item');
    const lightboxModal = document.getElementById('galleryModal');
    const lightboxImg = document.getElementById('galleryModalImg');
    const lightboxCaption = document.getElementById('galleryModalCaption');

    if (lightboxModal && lightboxImg) {
        galleryModals.forEach(item => {
            item.addEventListener('click', function () {
                const img = this.querySelector('img');
                const title = this.getAttribute('data-title') || img.alt || '';
                const fullSrc = this.getAttribute('data-full') || img.src;

                lightboxImg.src = fullSrc;
                lightboxImg.alt = title;
                if (lightboxCaption) {
                    lightboxCaption.textContent = title;
                }

                const bsModal = new bootstrap.Modal(lightboxModal);
                bsModal.show();
            });
        });
    }

    // 6. Asynchronous AJAX Booking Form Submission (with fallback)
    const bookingForm = document.getElementById('jyBookingForm');
    const bookingAlertBox = document.getElementById('bookingAlertBox');

    if (bookingForm && bookingAlertBox) {
        bookingForm.addEventListener('submit', function (e) {
            // Native HTML5 validation check
            if (!bookingForm.checkValidity()) {
                return; // Let browser show tooltips
            }

            // If fetch is available, submit asynchronously
            e.preventDefault();
            const submitBtn = bookingForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn ? submitBtn.innerHTML : 'Submit Enquiry';

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting Enquiry...';
            }

            bookingAlertBox.innerHTML = '';
            bookingAlertBox.className = 'd-none';

            const formData = new FormData(bookingForm);

            fetch(bookingForm.getAttribute('action') || 'process-booking.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    bookingAlertBox.className = 'alert alert-jy-success alert-dismissible fade show my-3';
                    bookingAlertBox.innerHTML = `<strong>Booking Enquiry Received!</strong><br>${data.message} ${data.booking_number ? '<br>Reference: <strong>' + data.booking_number + '</strong>' : ''}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
                    bookingForm.reset();
                    // Scroll to alert
                    bookingAlertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    bookingAlertBox.className = 'alert alert-jy-danger alert-dismissible fade show my-3';
                    bookingAlertBox.innerHTML = `<strong>Please correct the following:</strong><br>${data.message || 'Unable to submit enquiry. Please call us directly.'}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
                }
            })
            .catch(err => {
                console.warn('AJAX submit error, falling back to standard submit:', err);
                bookingForm.submit();
            })
            .finally(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            });
        });
    }

    // 7. Quick Vehicle Click handler
    document.querySelectorAll('.select-vehicle-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            const vname = this.getAttribute('data-vehicle');
            if (vname && vehicleSelect) {
                for (let i = 0; i < vehicleSelect.options.length; i++) {
                    if (vehicleSelect.options[i].value.toLowerCase().includes(vname.toLowerCase())) {
                        vehicleSelect.selectedIndex = i;
                        break;
                    }
                }
                const bookingSection = document.getElementById('booking');
                if (bookingSection) {
                    bookingSection.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });
});
