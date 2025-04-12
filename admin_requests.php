<?php
include 'config.php';

$requests = mysqli_query($conn, "SELECT * FROM user_borrowed ORDER BY id DESC");

while ($row = mysqli_fetch_assoc($requests)) {
    echo "<div style='border:1px solid #ccc; margin:10px; padding:10px;'>
        <strong>{$row['name']}</strong><br>
        Books: {$row['book_names']}<br>
        Method: {$row['method']}<br>
        Address: {$row['address']}<br>
        Status: <strong>{$row['status']}</strong><br>
        
        <form method='post' style='display:inline-block'>
            <input type='hidden' name='id' value='{$row['id']}'>
            <button name='approve'>Approve</button>
            <button name='decline'>Decline</button>
        </form>
    </div>";
}

if (isset($_POST['approve'])) {
    $id = $_POST['id'];
    mysqli_query($conn, "UPDATE user_borrowed SET status='Approved' WHERE id='$id'");
    // Optional: Decrease book count here
    $borrow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT book_names FROM user_borrowed WHERE id='$id'"));
    $book_list = explode(', ', $borrow['book_names']);

    foreach ($book_list as $book_name) {
        mysqli_query($conn, "UPDATE books SET books_count = books_count - 1 WHERE name = '$book_name' AND books_count > 0");
}

}
if (isset($_POST['decline'])) {
    $id = $_POST['id'];
    mysqli_query($conn, "UPDATE user_borrowed SET status='Declined' WHERE id='$id'");
}
?>
