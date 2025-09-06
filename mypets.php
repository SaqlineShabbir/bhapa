<?php
include 'db.php';
include 'navbar.php';


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user information from session
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user'] ?? 'User';

// Fetch user's pets from database
$pets_query = "SELECT * FROM pets WHERE user_id = $user_id ORDER BY name";
$pets_result = $conn->query($pets_query);

// Handle form submission for adding new pet
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_pet'])) {
    $pet_name = $conn->real_escape_string($_POST['pet_name']);
    $pet_type = $conn->real_escape_string($_POST['pet_type']);
    $pet_breed = $conn->real_escape_string($_POST['pet_breed']);
    $pet_age = $conn->real_escape_string($_POST['pet_age']);

    // Set last_visit to today
    $last_visit = date('Y-m-d');

    // Insert new pet
    $insert_query = "INSERT INTO pets (user_id, name, type, breed, age, last_visit) 
                     VALUES ($user_id, '$pet_name', '$pet_type', '$pet_breed', $pet_age, '$last_visit')";
    
    if ($conn->query($insert_query)) {
        $success_message = "Pet added successfully!";
        // Refresh pets list
        $pets_result = $conn->query($pets_query);
    } else {
        $error_message = "Error adding pet: " . $conn->error;
    }
}

// Handle pet deletion
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    
    // Check if the pet belongs to the user
    $check_query = "SELECT * FROM pets WHERE id = $delete_id AND user_id = $user_id";
    $check_result = $conn->query($check_query);
    
    if ($check_result->num_rows > 0) {
        $delete_query = "DELETE FROM pets WHERE id = $delete_id";
        if ($conn->query($delete_query)) {
            $success_message = "Pet deleted successfully!";
            // Refresh pets list
            $pets_result = $conn->query($pets_query);
        } else {
            $error_message = "Error deleting pet: " . $conn->error;
        }
    } else {
        $error_message = "Pet not found or you don't have permission to delete it.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCareHub - My Pets</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f8; color: #212529; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        header { background: #3a7ca5; color: white; padding: 30px 20px; margin-bottom: 30px; border-radius: 10px; }
        header h1 { margin-bottom: 10px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
        @media (max-width: 900px) { .grid { grid-template-columns: 1fr; } }
        .card { background: white; border-radius: 10px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; }
        input, select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; }
        button { padding: 12px 25px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; }
        .btn-primary { background: #3a7ca5; color: white; }
        .btn-primary:hover { background: #2c6185; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; }
        .message { padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: 500; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .pet-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 20px; }
        .pet-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.08); padding: 20px; }
        .pet-card:hover { transform: translateY(-5px); }
        .pet-name { font-size: 1.4rem; font-weight: 700; color: #3a7ca5; margin-bottom: 5px; }
        .pet-details { color: #6c757d; margin-bottom: 15px; }
        .pet-actions { display: flex; gap: 10px; }
        .empty-state { text-align: center; padding: 40px; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); grid-column: 1 / -1; }
    </style>
</head>
<body>
    

    <div class="container">
        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="grid">
            <!-- Add Pet Form -->
            <div class="card">
                <h2><i class="fas fa-plus-circle"></i> Add New Pet</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="pet_name">Pet Name *</label>
                        <input type="text" id="pet_name" name="pet_name" required placeholder="Enter your pet's name">
                    </div>
                    <div class="form-group">
                        <label for="pet_type">Type *</label>
                        <select id="pet_type" name="pet_type" required>
                            <option value="">Select type</option>
                            <option value="Dog">Dog</option>
                            <option value="Cat">Cat</option>
                            <option value="Bird">Bird</option>
                            <option value="Rabbit">Rabbit</option>
                            <option value="Fish">Fish</option>
                            <option value="Hamster">Hamster</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pet_breed">Breed</label>
                        <input type="text" id="pet_breed" name="pet_breed" placeholder="Enter breed">
                    </div>
                    <div class="form-group">
                        <label for="pet_age">Age (years)</label>
                        <input type="number" id="pet_age" name="pet_age" min="0" max="50" step="0.1" placeholder="Age in years">
                    </div>
                    <button type="submit" name="add_pet" class="btn-primary"><i class="fas fa-plus"></i> Add Pet</button>
                </form>
            </div>

            <!-- Pets List -->
            <div class="card">
                <h2><i class="fas fa-paw"></i> My Pets</h2>
                <div class="pet-list">
                    <?php if ($pets_result->num_rows > 0): ?>
                        <?php while ($pet = $pets_result->fetch_assoc()): ?>
                            <div class="pet-card">
                                <h3 class="pet-name"><?php echo htmlspecialchars($pet['name']); ?></h3>
                                <div class="pet-details">
                                    <p><i class="fas fa-dog"></i> <?php echo htmlspecialchars($pet['type']); ?>
                                        <?php if (!empty($pet['breed'])): ?> (<?php echo htmlspecialchars($pet['breed']); ?>)<?php endif; ?>
                                    </p>
                                    <?php if (!empty($pet['age'])): ?>
                                        <p><i class="fas fa-birthday-cake"></i> <?php echo htmlspecialchars($pet['age']); ?> years old</p>
                                    <?php endif; ?>
                                    <?php if (!empty($pet['last_visit'])): ?>
                                        <p><i class="fas fa-calendar-alt"></i> Last Visit: <?php echo htmlspecialchars($pet['last_visit']); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="pet-actions">
                                    <a href="?delete_id=<?php echo $pet['id']; ?>" class="btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars($pet['name']); ?>?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-paw"></i>
                            <h3>No pets added yet</h3>
                            <p>Add your first pet using the form on the left</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
