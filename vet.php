<?php
include 'db.php';
include 'navbar.php';


// Check if user is logged in, otherwise redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user information from session
$user_id = $_SESSION['user_id'];

$user_email = $_SESSION['email'];

// Fetch user's pets from database
$pets_query = "SELECT * FROM pets WHERE user_id = $user_id";
$pets_result = $conn->query($pets_query);

// Fetch user's appointments from database
$appointments_query = "SELECT a.*, v.name as vet_name, v.clinic_name 
                       FROM appointments a 
                       JOIN vets v ON a.vet_id = v.id 
                       WHERE a.user_id = $user_id 
                       ORDER BY a.appointment_date DESC, a.appointment_time DESC";
$appointments_result = $conn->query($appointments_query);

// Fetch available veterinarians
$vets_query = "SELECT * FROM vets ORDER BY name";
$vets_result = $conn->query($vets_query);

// Handle form submission for new appointment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_appointment'])) {
    $pet_id = $_POST['pet_id'];
    $vet_id = $_POST['vet_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $reason = $_POST['reason'];
    
    // Insert new appointment
    $insert_query = "INSERT INTO appointments (user_id, pet_id, vet_id, appointment_date, appointment_time, reason, status) 
                     VALUES ($user_id, $pet_id, $vet_id, '$appointment_date', '$appointment_time', '$reason', 'scheduled')";
    
    if ($conn->query($insert_query)) {
        $success_message = "Appointment booked successfully!";
        // Refresh appointments
        $appointments_result = $conn->query($appointments_query);
    } else {
        $error_message = "Error booking appointment: " . $conn->error;
    }
}

// Handle appointment cancellation
if (isset($_GET['cancel_id'])) {
    $cancel_id = $_GET['cancel_id'];
    $cancel_query = "UPDATE appointments SET status = 'cancelled' WHERE id = $cancel_id AND user_id = $user_id";
    
    if ($conn->query($cancel_query)) {
        $success_message = "Appointment cancelled successfully!";
        // Refresh appointments
        $appointments_result = $conn->query($appointments_query);
    } else {
        $error_message = "Error cancelling appointment: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCareHub - Veterinary Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
     
            --secondary: #5cb85c;
            --accent: #f0ad4e;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --success: #5cb85c;
            --danger: #dc3545;
            --warning: #f0ad4e;
            --info: #5bc0de;
            --shadow: 0 5px 15px rgba(0,0,0,0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

      body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f4f6f8;
    color: var(--dark);
    line-height: 1.6;
    /* Add top padding so header & tabs don't overlap navbar */
   
}

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        header {
            background: linear-gradient(135deg, var(--primary) 0%, #2c6185 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid var(--accent);
        }

        h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: var(--shadow);
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        @media (max-width: 900px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(58, 124, 165, 0.2);
        }

        button {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #2c6185;
            transform: translateY(-3px);
            box-shadow: var(--shadow);
        }

        .btn-accent {
            background: var(--accent);
            color: white;
        }

        .btn-accent:hover {
            background: #ec971f;
            transform: translateY(-3px);
            box-shadow: var(--shadow);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-3px);
            box-shadow: var(--shadow);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.875rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 0.9em;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        thead tr {
            background-color: var(--primary);
            color: white;
            text-align: left;
        }

        th, td {
            padding: 12px 15px;
        }

        tbody tr {
            border-bottom: 1px solid #ddd;
            background-color: white;
        }

        tbody tr:nth-of-type(even) {
            background-color: #f8f9fa;
        }

        tbody tr:last-of-type {
            border-bottom: 2px solid var(--primary);
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-scheduled {
            background-color: #e0f7ea;
            color: #2e7d32;
        }

        .status-completed {
            background-color: #e3f2fd;
            color: #1565c0;
        }

        .status-cancelled {
            background-color: #ffebee;
            color: #c62828;
        }

        .status-pending {
            background-color: #fff3e0;
            color: #ef6c00;
        }

        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .vet-card {
            display: flex;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .vet-image {
            width: 150px;
            background-color: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .vet-image i {
            font-size: 60px;
            color: var(--primary);
        }

        .vet-info {
            padding: 20px;
            flex-grow: 1;
        }

        .vet-name {
            font-size: 1.4rem;
            margin-bottom: 5px;
        }

        .vet-specialty {
            color: var(--gray);
            margin-bottom: 10px;
        }

        .vet-distance {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 15px;
        }

        .tab-container {
            margin: 30px 0;
        }

        .tabs {
            display: flex;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .tab {
            padding: 12px 25px;
            background: #e9ecef;
            border: none;
            border-radius: 5px 5px 0 0;
            margin-right: 5px;
            cursor: pointer;
            font-weight: 600;
        }

        .tab.active {
            background: var(--primary);
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .booking-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .pet-list {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .pet-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            width: calc(33.333% - 15px);
            min-width: 250px;
        }

        @media (max-width: 768px) {
            .pet-item {
                width: 100%;
            }
        }

        .pet-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary);
        }

        .pet-type {
            color: var(--gray);
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
   

    <div class="container">
        <!-- Display success/error messages -->
        <?php if (isset($success_message)): ?>
            <div class="message success">
                <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="message error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="tab-container">
            <div class="tabs">
                <button class="tab active" onclick="openTab('booking')">Book Consultation</button>
                <button class="tab" onclick="openTab('nearby')">Nearby Vets</button>
                <button class="tab" onclick="openTab('appointments')">My Appointments</button>
                <button class="tab" onclick="openTab('pets')">My Pets</button>
            </div>

            <!-- Booking Tab -->
            <div id="booking" class="tab-content active">
                <div class="grid">
                    <div>
                        <div class="card">
                            <h2><i class="fas fa-calendar-check"></i> Book a Consultation</h2>
                            <form method="POST" action="">
                                <div class="form-group">
                                    <label for="pet_id">Select Pet</label>
                                    <select id="pet_id" name="pet_id" required>
                                        <option value="">Select a pet</option>
                                        <?php while ($pet = $pets_result->fetch_assoc()): ?>
                                            <option value="<?php echo $pet['id']; ?>">
                                                <?php echo htmlspecialchars($pet['name'] . ' (' . $pet['type'] . ')'); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="vet_id">Select Veterinarian</label>
                                    <select id="vet_id" name="vet_id" required>
                                        <option value="">Select a veterinarian</option>
                                        <?php while ($vet = $vets_result->fetch_assoc()): ?>
                                            <option value="<?php echo $vet['id']; ?>">
                                                <?php echo htmlspecialchars('Dr. ' . $vet['name'] . ' - ' . $vet['clinic_name']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="appointment_date">Date</label>
                                    <input type="date" id="appointment_date" name="appointment_date" required>
                                </div>
                                <div class="form-group">
                                    <label for="appointment_time">Time</label>
                                    <select id="appointment_time" name="appointment_time" required>
                                        <option value="">Select time</option>
                                        <option value="09:00:00">9:00 AM</option>
                                        <option value="10:00:00">10:00 AM</option>
                                        <option value="11:00:00">11:00 AM</option>
                                        <option value="13:00:00">1:00 PM</option>
                                        <option value="14:00:00">2:00 PM</option>
                                        <option value="15:00:00">3:00 PM</option>
                                        <option value="16:00:00">4:00 PM</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="reason">Reason for Visit</label>
                                    <textarea id="reason" name="reason" rows="3" required placeholder="Describe the reason for the consultation"></textarea>
                                </div>
                                <button type="submit" name="book_appointment" class="btn-primary"><i class="fas fa-calendar-plus"></i> Book Appointment</button>
                            </form>
                        </div>
                    </div>
                    <div>
                        <div class="card">
                            <h2><i class="fas fa-info-circle"></i> Booking Information</h2>
                            <div class="booking-details">
                                <h3>What to Expect</h3>
                                <p>When you book a consultation with our veterinarians, you can expect:</p>
                                <ul>
                                    <li>Thorough examination of your pet</li>
                                    <li>Professional diagnosis and treatment plan</li>
                                    <li>Discussion of preventive care</li>
                                    <li>Time for all your questions</li>
                                </ul>
                                
                                <h3>Emergency Services</h3>
                                <p>For emergency cases, please call directly:</p>
                                <p><strong>Emergency Hotline: 1-800-PET-CARE</strong></p>
                                
                                <h3>Preparation Tips</h3>
                                <ul>
                                    <li>Bring any medical records for your pet</li>
                                    <li>Note down any symptoms or behaviors you're concerned about</li>
                                    <li>Bring a list of any medications your pet is taking</li>
                                    <li>If possible, bring a fresh stool sample</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nearby Vets Tab -->
            <div id="nearby" class="tab-content">
                <div class="card">
                    <h2><i class="fas fa-map-marker-alt"></i> Nearby Veterinarians</h2>
                    <p>Based on your location, we found these veterinary clinics nearby:</p>
                    
                    <?php
                    // Reset pointer for vets result
                    $vets_result->data_seek(0);
                    while ($vet = $vets_result->fetch_assoc()): 
                    ?>
                    <div class="vet-card">
                        <div class="vet-image">
                            <i class="fas fa-clinic-medical"></i>
                        </div>
                        <div class="vet-info">
                            <h3 class="vet-name"><?php echo htmlspecialchars($vet['clinic_name']); ?></h3>
                            <p class="vet-specialty">Dr. <?php echo htmlspecialchars($vet['name']); ?> - <?php echo htmlspecialchars($vet['specialty']); ?></p>
                            <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($vet['phone']); ?></p>
                            <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($vet['address']); ?></p>
                            <p><i class="fas fa-clock"></i> <?php echo htmlspecialchars($vet['hours']); ?></p>
                            <button class="btn-primary btn-sm" onclick="openTab('booking')">Book Appointment</button>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Appointments Tab -->
            <div id="appointments" class="tab-content">
                <div class="card">
                    <h2><i class="fas fa-calendar-alt"></i> My Appointments</h2>
                    <?php if ($appointments_result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Veterinarian</th>
                                <th>Pet</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($appointment = $appointments_result->fetch_assoc()): 
                                // Get pet name for this appointment
                                $pet_id = $appointment['pet_id'];
                                $pet_query = "SELECT name FROM pets WHERE id = $pet_id";
                                $pet_result = $conn->query($pet_query);
                                $pet = $pet_result->fetch_assoc();
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                                <td><?php echo htmlspecialchars(date('g:i A', strtotime($appointment['appointment_time']))); ?></td>
                                <td>Dr. <?php echo htmlspecialchars($appointment['vet_name']); ?></td>
                                <td><?php echo htmlspecialchars($pet['name']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['reason']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo htmlspecialchars($appointment['status']); ?>">
                                        <?php echo ucfirst(htmlspecialchars($appointment['status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($appointment['status'] == 'scheduled'): ?>
                                    <a href="?cancel_id=<?php echo $appointment['id']; ?>" class="btn-danger btn-sm" 
                                       onclick="return confirm('Are you sure you want to cancel this appointment?')">Cancel</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <p>You don't have any appointments yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Pets Tab -->
            <div id="pets" class="tab-content">
                <div class="card">
                    <h2><i class="fas fa-paw"></i> My Pets</h2>
                    <div class="pet-list">
                        <?php
                        // Reset pointer for pets result
                        $pets_result->data_seek(0);
                        if ($pets_result->num_rows > 0):
                            while ($pet = $pets_result->fetch_assoc()): 
                        ?>
                        <div class="pet-item">
                            <div class="pet-name"><?php echo htmlspecialchars($pet['name']); ?></div>
                            <div class="pet-type"><?php echo htmlspecialchars($pet['type']); ?> - <?php echo htmlspecialchars($pet['breed']); ?></div>
                            <div>Age: <?php echo htmlspecialchars($pet['age']); ?> years</div>
                            <div>Last Visit: <?php echo htmlspecialchars($pet['last_visit'] ?? 'Never'); ?></div>
                            <button class="btn-primary btn-sm" style="margin-top: 10px;">View Health Records</button>
                        </div>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <p>You haven't added any pets yet.</p>
                        <?php endif; ?>
                    </div>
                    <button class="btn-accent"><i class="fas fa-plus"></i> Add New Pet</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab navigation
        function openTab(tabName) {
            const tabs = document.getElementsByClassName('tab-content');
            for (let i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove('active');
            }
            
            const tabButtons = document.getElementsByClassName('tab');
            for (let i = 0; i < tabButtons.length; i++) {
                tabButtons[i].classList.remove('active');
            }
            
            document.getElementById(tabName).classList.add('active');
            event.currentTarget.classList.add('active');
        }
        
        // Set default date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('appointment_date').value = tomorrow.toISOString().split('T')[0];
    </script>
</body>
</html>