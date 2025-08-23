</div>
<!-- END layout-wrapper -->
<!--start back-to-top-->
<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>
<!--end back-to-top-->

<!--preloader-->
<div id="preloader">
    <div id="status">
        <div class="spinner-border text-primary avatar-sm" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>



<!-- JAVASCRIPT -->
<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>
<script src="assets/libs/node-waves/waves.min.js"></script>
<script src="assets/libs/feather-icons/feather.min.js"></script>
<script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="assets/js/plugins.js"></script>

<!-- Leaflet map JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- apexcharts -->
<script src="assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Vector map-->
<script src="assets/libs/jsvectormap/jsvectormap.min.js"></script>
<script src="assets/libs/jsvectormap/maps/world-merc.js"></script>

<!--Swiper slider js-->
<script src="assets/libs/swiper/swiper-bundle.min.js"></script>

<!-- Dashboard init -->
<script src="assets/js/pages/dashboard-ecommerce.init.js"></script>

<!-- App js -->
<script src="assets/js/app.js"></script>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>




<script>
    function togglePassword(fieldId, btn) {
        let field = document.getElementById(fieldId);
        if (field.type === "password") {
            field.type = "text";
            btn.innerText = "🙈"; // change icon when visible
        } else {
            field.type = "password";
            btn.innerText = "👁"; // back to eye when hidden
        }
    }
</script>



<script>
    var swiper1 = new Swiper(".mySwiper1", {
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        loop: true,
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Delete User
        let deleteId = null;
        document.querySelectorAll(".remove-item-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                deleteId = this.getAttribute("data-id");
            });
        });

        document.getElementById("delete-record").addEventListener("click", function () {
            if (deleteId) {
                fetch("delete_user.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "id=" + deleteId
                })
                    .then(res => res.text())
                    .then(data => {
                        if (data === "success") {
                            location.reload();
                        } else {
                            alert("Error deleting user");
                        }
                    });
            }
        });

        // Edit User (Load data into modal)
        document.querySelectorAll(".edit-item-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                let row = this.closest("tr");
                document.getElementById("name-field").value = row.querySelector(".name").innerText;
                document.getElementById("username-field").value = row.querySelector(".username").innerText;
                document.getElementById("email-field").value = row.querySelector(".email").innerText;
                document.getElementById("role-field").value = row.querySelector(".role span").innerText;

                // Store user id in hidden field
                let hiddenId = document.createElement("input");
                hiddenId.type = "hidden";
                hiddenId.name = "id";
                hiddenId.value = this.getAttribute("data-id");
                document.querySelector(".tablelist-form").appendChild(hiddenId);

                // Change form action to edit
                document.querySelector(".tablelist-form").action = "edit_user.php";
            });
        });
    });
</script>


<!-- 
<script>
    (function () {
        'use strict'

        const form = document.getElementById('propertyForm');
        const errorMessages = form.querySelectorAll('.error-msg');

        errorMessages.forEach(msg => msg.style.display = 'none');

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            let isValid = true;

            form.querySelectorAll('input, textarea, select').forEach(input => {
                const errorMsg = input.parentElement.querySelector('.error-msg');
                if (input.checkValidity() === false) {
                    isValid = false;
                    if (errorMsg) errorMsg.style.display = 'block';
                } else {
                    if (errorMsg) errorMsg.style.display = 'none';
                }
            });

            if (isValid) {
                alert("Form submitted successfully!");
                form.reset();
            }
        });
    })()
</script> -->



<script>
    function showFileName(input) {
        const fileName = input.files[0] ? input.files[0].name : '';
        document.getElementById('file-name-' + input.id).textContent = fileName;
    }

    function handleDragOver(event) {
        event.preventDefault();
        event.currentTarget.classList.add('dragover');
    }

    function handleDragLeave(event) {
        event.currentTarget.classList.remove('dragover');
    }

    function handleDrop(event, inputId) {
        event.preventDefault();
        event.currentTarget.classList.remove('dragover');
        const files = event.dataTransfer.files;
        if (files.length) {
            document.getElementById(inputId).files = files;
            showFileName(document.getElementById(inputId));
        }
    }
</script>




</body>

</html>