<?php
require 'security_config.php';
startSecureSession();

if ($_SESSION['role'] !== 'Admin') {
    header('Location: index.php');
    exit();
}

require 'db.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $csrf_token_post = $_POST['csrf_token'] ?? '';
    if (!validateCsrfToken($csrf_token_post)) {
        echo "<script>alert('Invalid CSRF token. Please try again.');</script>";
    } else {
        $first_name = trim($_POST["first_name"]);
        $last_name = trim($_POST["last_name"]);
        $phone = trim($_POST["phone"]);
        $email = trim($_POST["email"]);
        $checkin = trim($_POST["checkin"]);
        $checkout = trim($_POST["checkout"]);
        $adult = trim($_POST["adult"]);
        $children = trim($_POST["children"]);

        if ($_POST['action'] == 'create') {
            try {
                $query = $pdo->prepare('INSERT INTO booking (first_name, last_name, phone, email, check_in, check_out, adult, children) VALUES (:first_name, :last_name, :phone, :email, :checkin, :checkout, :adult, :children)');
                $query->bindParam(':first_name', $first_name, PDO::PARAM_STR);
                $query->bindParam(':last_name', $last_name, PDO::PARAM_STR);
                $query->bindParam(':phone', $phone, PDO::PARAM_STR);
                $query->bindParam(':email', $email, PDO::PARAM_STR);
                $query->bindParam(':checkin', $checkin);
                $query->bindParam(':checkout', $checkout);
                $query->bindParam(':adult', $adult, PDO::PARAM_INT);
                $query->bindParam(':children', $children, PDO::PARAM_INT);
                $query->execute();
                echo "<script>alert('Booking created successfully');</script>";
                header('Location: booking_crud.php');
                exit();
            } catch (PDOException $e) {
                echo 'Booking creation failed: ' . $e->getMessage();
            }
        }

        if ($_POST['action'] == 'update') {
            $id = $_POST['id'];
            try {
                $query = $pdo->prepare('UPDATE booking SET first_name = :first_name, last_name = :last_name, phone = :phone, email = :email, check_in = :checkin, check_out = :checkout, adult = :adult, children = :children WHERE id = :id');
                $query->bindParam(':first_name', $first_name, PDO::PARAM_STR);
                $query->bindParam(':last_name', $last_name, PDO::PARAM_STR);
                $query->bindParam(':phone', $phone, PDO::PARAM_STR);
                $query->bindParam(':email', $email, PDO::PARAM_STR);
                $query->bindParam(':checkin', $checkin);
                $query->bindParam(':checkout', $checkout);
                $query->bindParam(':adult', $adult, PDO::PARAM_INT);
                $query->bindParam(':children', $children, PDO::PARAM_INT);
                $query->bindParam(':id', $id, PDO::PARAM_INT);
                $query->execute();
                echo "<script>alert('Booking updated successfully');</script>";
                header('Location: booking_crud.php');
                exit();
            } catch (PDOException $e) {
                echo 'Booking update failed: ' . $e->getMessage();
            }
        }

        if ($_POST['action'] == 'delete') {
            $id = $_POST['id'];
            try {
                $query = $pdo->prepare('DELETE FROM booking WHERE id = :id');
                $query->bindParam(':id', $id, PDO::PARAM_INT);
                $query->execute();
                echo "<script>alert('Booking deleted successfully');</script>";
                header('Location: booking_crud.php');
                exit();
            } catch (PDOException $e) {
                echo 'Booking deletion failed: ' . $e->getMessage();
            }
        }
    }
}

if ($action == 'edit' && $id) {
    $stmt = $pdo->prepare('SELECT * FROM booking WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
}

$stmt = $pdo->query('SELECT * FROM booking');
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$csrf_token = generateCsrfToken();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-5">
    <h1 class="mb-4">Admin Dashboard</h1>
    <h3 class="mb-4">Manage Bookings</h3>
    <?php if ($action == 'create' || $action == 'edit'): ?>
        <form action="booking_crud.php" method="POST">
            <input type="hidden" name="action" value="<?php echo $action == 'edit' ? 'update' : 'create'; ?>">
            <?php if ($action == 'edit'): ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($booking['id']); ?>">
            <?php endif; ?>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" id="first_name" value="<?php echo $action == 'edit' ? htmlspecialchars($booking['first_name']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" id="last_name" value="<?php echo $action == 'edit' ? htmlspecialchars($booking['last_name']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" id="phone" value="<?php echo $action == 'edit' ? htmlspecialchars($booking['phone']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" value="<?php echo $action == 'edit' ? htmlspecialchars($booking['email']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="checkin" class="form-label">Check-in Date</label>
                <input type="date" name="checkin" class="form-control" id="checkin" value="<?php echo $action == 'edit' ? htmlspecialchars($booking['check_in']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="checkout" class="form-label">Check-out Date</label>
                <input type="date" name="checkout" class="form-control" id="checkout" value="<?php echo $action == 'edit' ? htmlspecialchars($booking['check_out']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="adult" class="form-label">No. of Adults</label>
                <input type="number" name="adult" class="form-control" id="adult" value="<?php echo $action == 'edit' ? htmlspecialchars($booking['adult']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="children" class="form-label">No. of Children</label>
                <input type="number" name="children" class="form-control" id="children" value="<?php echo $action == 'edit' ? htmlspecialchars($booking['children']) : ''; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $action == 'edit' ? 'Update Booking' : 'Create Booking'; ?></button>
            <a href="booking_crud.php" class="btn btn-secondary">Cancel</a>
        </form>
    <?php elseif ($action == 'delete' && $id): ?>
        <form action="booking_crud.php" method="POST">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
            <p>Are you sure you want to delete this booking?</p>
            <button type="submit" class="btn btn-danger">Delete</button>
            <a href="booking_crud.php" class="btn btn-secondary">Cancel</a>
        </form>
    <?php else: ?>
        <div class="d-flex justify-content-between mb-3">
            <a href="booking_crud.php?action=create" class="btn btn-success">Add New Booking</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Adults</th>
                <th>Children</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?php echo htmlspecialchars($booking['id']); ?></td>
                    <td><?php echo htmlspecialchars($booking['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($booking['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($booking['phone']); ?></td>
                    <td><?php echo htmlspecialchars($booking['email']); ?></td>
                    <td><?php echo htmlspecialchars($booking['check_in']); ?></td>
                    <td><?php echo htmlspecialchars($booking['check_out']); ?></td>
                    <td><?php echo htmlspecialchars($booking['adult']); ?></td>
                    <td><?php echo htmlspecialchars($booking['children']); ?></td>
                    <td>
                        <a href="booking_crud.php?action=edit&id=<?php echo $booking['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="booking_crud.php?action=delete&id=<?php echo $booking['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
