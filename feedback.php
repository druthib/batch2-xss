<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'xssbanking'; // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the form submission and insert feedback into the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Prepare the SQL statement (no sanitization, vulnerable to XSS)
    $stmt = $conn->prepare("INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message); // Bind user input to the SQL query
    $stmt->execute();

    echo "<script>alert('Thank you for your feedback!');</script>";
    $stmt->close();
}

// Fetch and display all feedback (no sanitization, vulnerable to XSS)
$sql = "SELECT * FROM feedback";
$result = $conn->query($sql);

$feedbackData = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $feedbackData[] = $row; // Store feedback in an array
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - Plugo</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <style>
        /* Your existing CSS styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
       
        .feedback-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .feedback-container h2 {
            margin-bottom: 20px;
            color: #004a99;
            text-align: center;
        }
        .feedback-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .feedback-container input, .feedback-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .feedback-container button {
            width: 100%;
            padding: 10px;
            background-color: #004a99;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .feedback-container button:hover {
            background-color: #003366;
        }
        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 20px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div id="header" class="header">
        <!-- Your existing header content -->
        <form id="frmSearch" method="get" action="search.php">
          <table width="100%">
            <tr>
              <td rowspan="2"><img src="bankk.png" alt="Logo" width="150" height="80"></td>
              <td align="right">
                <a href="signin.html"><strong style="color: red;">Sign In</strong></a> | 
                <a href="about.html">Contact Us</a> | 
                <a href="feedback.html">Feedback</a> | 
                <label for="query">Search:</label>
                <input type="text" name="query" id="query">
                <input type="submit" value="Go">
              </td>
            </tr>
       
          </table>
        </form>
    </div>
    <a href="index.html">Home</a>

    <div class="feedback-container">
        <h2>Feedback</h2>
        <form method="POST" action="">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="5" required></textarea>
            
            <button type="submit">Submit Feedback</button>
        </form>

        <!-- Display submitted feedback (vulnerable to XSS) -->
        <div class="feedback-list" id="feedbackList">
            <h3>Submitted Feedback</h3>
            <?php
            // Display feedback from the database (no sanitization)
            foreach ($feedbackData as $feedback) {
                echo "<div class='feedback-item'>
                        <strong>{$feedback['name']}</strong> ({$feedback['email']}):<br>
                        {$feedback['message']}
                      </div>";
            }
            ?>
        </div>
    </div>
    <div class="footer">
        Phone/Policy | Security Statement | Server Status Check | REST API | @2024 BVC Banking, Inc
    </div>
</body>
</html>