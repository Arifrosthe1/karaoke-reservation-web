<?php
// Start session
session_start();

// Page title
$page_title = "Home - Karaoke Reservation System";

// Include header
include_once 'includes/header.php';
?>

<main class="container mt-5">
    <!-- Hero Section -->
    <section class="row justify-content-center mb-5">
        <div class="col-md-10">
            <div class="card shadow border-0">
                <div class="card-body text-center p-5">
                    <h1 class="display-4 mb-4">Welcome to Our Karaoke Reservation System</h1>
                    <p class="lead">Book your favorite karaoke room online, anytime, anywhere.</p>
                    <div class="mt-4">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="reserve_room.php" class="btn btn-primary btn-lg">Reserve a Room Now</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary btn-lg me-3">Login</a>
                            <a href="register.php" class="btn btn-outline-primary btn-lg">Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="row mb-5">
        <div class="col-12 text-center mb-4">
            <h2>Why Choose Our Karaoke Service?</h2>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-microphone-alt fa-3x mb-3 text-primary"></i>
                    <h3 class="h4">Premium Rooms</h3>
                    <p>High-quality sound systems and comfortable spaces for all group sizes.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-check fa-3x mb-3 text-primary"></i>
                    <h3 class="h4">Easy Booking</h3>
                    <p>Simple online reservation system with real-time availability.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-credit-card fa-3x mb-3 text-primary"></i>
                    <h3 class="h4">Secure Payments</h3>
                    <p>Pay online safely and receive instant confirmation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Rooms Section -->
    <section class="row mb-5">
        <div class="col-12 text-center mb-4">
            <h2>Our Featured Rooms</h2>
        </div>
        
        <?php
        // Connect to database
        require_once 'db_connect.php';
        
        // Get featured rooms (limit to 3)
        $sql = "SELECT r.id, r.capacity, p.name as package_name, p.price_per_hour, p.description 
                FROM rooms r 
                JOIN packages p ON r.package_id = p.id 
                WHERE r.status = 'available' 
                LIMIT 3";
        
        $result = false;
        if (isset($conn) && $conn instanceof mysqli) {
            $result = $conn->query($sql);
        }
        
        if ($result instanceof mysqli_result && $result->num_rows > 0) {
            while ($room = $result->fetch_assoc()) {
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h3 class="h4"><?php echo htmlspecialchars($room['package_name']); ?></h3>
                            <p class="text-muted">Capacity: <?php echo htmlspecialchars($room['capacity']); ?> people</p>
                            <p><?php echo htmlspecialchars($room['description']); ?></p>
                            <p class="h5">$<?php echo htmlspecialchars($room['price_per_hour']); ?> / hour</p>
                            <a href="reserve_room.php?room_id=<?php echo $room['id']; ?>" class="btn btn-sm btn-outline-primary mt-3">Book Now</a>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<div class="col-12 text-center"><p>No available rooms at the moment. Please check back later.</p></div>';
        }
        ?>
    </section>

    <!-- How It Works Section -->
    <section class="row mb-5">
        <div class="col-12 text-center mb-4">
            <h2>How It Works</h2>
        </div>
        <div class="col-md-3 text-center mb-4">
            <div class="rounded-circle bg-light p-4 d-inline-block mb-3">
                <i class="fas fa-user-plus fa-2x text-primary"></i>
            </div>
            <h3 class="h5">1. Create an Account</h3>
            <p>Register to access our booking system.</p>
        </div>
        <div class="col-md-3 text-center mb-4">
            <div class="rounded-circle bg-light p-4 d-inline-block mb-3">
                <i class="fas fa-search fa-2x text-primary"></i>
            </div>
            <h3 class="h5">2. Find a Room</h3>
            <p>Browse available rooms and packages.</p>
        </div>
        <div class="col-md-3 text-center mb-4">
            <div class="rounded-circle bg-light p-4 d-inline-block mb-3">
                <i class="far fa-calendar-alt fa-2x text-primary"></i>
            </div>
            <h3 class="h5">3. Choose Date & Time</h3>
            <p>Select your preferred schedule.</p>
        </div>
        <div class="col-md-3 text-center mb-4">
            <div class="rounded-circle bg-light p-4 d-inline-block mb-3">
                <i class="fas fa-check-circle fa-2x text-primary"></i>
            </div>
            <h3 class="h5">4. Confirm & Pay</h3>
            <p>Make a secure payment and you're set!</p>
        </div>
    </section>
</main>

<?php
// Include footer
include_once 'includes/footer.php';
?>