</div><!-- End Main Content Container -->

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>CronyTech Karaoke Reservation System</h5>
                    <p>Book your favorite karaoke rooms online and enjoy a seamless experience.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-light">Home</a></li>
                        <li><a href="reserve_room.php" class="text-light">Reserve Room</a></li>
                        <?php if (!$logged_in): ?>
                            <li><a href="login.php" class="text-light">Login</a></li>
                            <li><a href="register.php" class="text-light">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact Us</h5>
                    <address>
                        <p><i class="fas fa-map-marker-alt me-2"></i> 123 Karaoke Street, Shah Alam</p>
                        <p><i class="fas fa-phone me-2"></i> +601234567890</p>
                        <p><i class="fas fa-envelope me-2"></i> info@cronytech.com</p>
                    </address>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; <?php echo date('Y'); ?> CronyTech Karaoke. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>
</body>
</html>