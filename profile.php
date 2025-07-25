<?php
session_start();

// Mock user data
// In a real application, you would fetch this from the database using the logged-in user's ID
$user = [
    'username' => 'johndoe',
    'email' => 'john.doe@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe'
];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Here you would include code to update the user profile in the database
    // For the demonstration, we're just going to update the $user array

    $user['first_name'] = $_POST['first_name'];
    $user['last_name'] = $_POST['last_name'];
    $user['email'] = $_POST['email'];

    $success_message = "Profile updated successfully.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <h1>User Profile</h1>

    <?php if (isset($success_message)): ?>
        <p style="color: green;">{{ $success_message }}</p>
    <?php endif; ?>

    <form method="post" action="">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?= $user['username']; ?>" disabled>
        </div>
        <div>
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?= $user['first_name']; ?>" required>
        </div>
        <div>
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?= $user['last_name']; ?>" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= $user['email']; ?>" required>
        </div>
        <button type="submit">Update Profile</button>
    </form>
</body>
</html>
