<?php
    $servername = "crossover.proxy.rlwy.net";
    $username = "root";
    $password = "ixtbUQMUnKHazdIfBswQQupZxXMLuADx";
    $dbname = "railway";

    $status_message = "";
    function connect_db($db_name = null) {
    global $servername, $username, $password;
    $conn = new mysqli($servername, $username, $password, $db_name, 52914);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
    }
    function create_database_and_tables() {
    global $dbname, $status_message;

    
    $conn = connect_db(null);

    
    $conn->query("CREATE DATABASE IF NOT EXISTS $dbname") === TRUE;

    $conn->select_db($dbname);

    
    $sql_students = "CREATE TABLE IF NOT EXISTS students_ (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        gender ENUM('Male', 'Female', 'Other') NOT NULL,
        roll_number VARCHAR(20) UNIQUE NOT NULL,
        phone_number VARCHAR(15),
        join_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    
    $sql_enrollments = "CREATE TABLE IF NOT EXISTS enrollments (
        enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
        id INT,
        course_name VARCHAR(50) NOT NULL,
        grade CHAR(2),
        FOREIGN KEY (id) REFERENCES students_(id) ON DELETE CASCADE
    )";

    $conn->query($sql_students);

    $conn->query($sql_enrollments);

    $conn->close();
    }

    function add_student_data($data) {
    global $status_message;
    $conn = connect_db("railway");

    
    $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, gender, roll_number, phone_number) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $data['first_name'], $data['last_name'], $data['gender'], $data['roll_number'], $data['phone_number']);

    if ($stmt->execute()) {
        $status_message = "<span class='success'>✅ The record for student **{$data['first_name']}** is added in the database!</span>";

        
        $new_id = $conn->insert_id;
        $stmt_enroll = $conn->prepare("INSERT INTO enrollments (id, course_name, grade) VALUES (?, ?, ?)");
        $stmt_enroll->bind_param("iss", $new_id, $data['course'], $data['grade']);
        $stmt_enroll->execute();
        $stmt_enroll->close();

    } else {
        
        if ($conn->errno == 1062) {
                $status_message = "<span class='error'>❌ Error: Roll Number **{$data['roll_number']}** already exists.</span>";
        } else {
            $status_message = "<span class='error'>❌ Error adding record: " . $stmt->error . "</span>";
        }
    }

    $stmt->close();
    $conn->close();
    }

    
    function delete_student_data($roll_number) {
    global $status_message;
    $conn = connect_db("railway");

    
    $stmt = $conn->prepare("DELETE FROM students WHERE roll_number = ?");
    $stmt->bind_param("s", $roll_number);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            
            $status_message = "<span class='warning'>🗑️ The record for Roll Number **$roll_number** is deleted from the database!</span>";
        } else {
            $status_message = "<span class='error'>⚠️ No student found with Roll Number: **$roll_number**.</span>";
        }
    } else {
        $status_message = "<span class='error'>❌ Error deleting record: " . $stmt->error . "</span>";
    }

    $stmt->close();
    $conn->close();
    }

    
    function display_all_data($order_by_col = 'last_name') {
    global $status_message;
    $conn = connect_db("railway");

    
    $sql = "SELECT id, first_name, last_name, roll_number, gender, phone_number 
            FROM students 
            ORDER BY $order_by_col ASC";

    $result = $conn->query($sql);
    $conn->close();

    if ($result && $result->num_rows > 0) {
        $output = "<h3>All Students (Ordered by $order_by_col)</h3>";
        $output .= "<table><thead><tr><th>ID</th><th>Roll No.</th><th>Name</th><th>Gender</th><th>Phone</th></tr></thead><tbody>";
        while($row = $result->fetch_assoc()) {
            $output .= "<tr>
                <td>{$row['id']}</td>
                <td>{$row['roll_number']}</td>
                <td>{$row['first_name']} {$row['last_name']}</td>
                <td>{$row['gender']}</td>
                <td>{$row['phone_number']}</td>
            </tr>";
        }
        $output .= "</tbody></table>";
        return $output;
    } else {
        return "<p class='warning'>No students found in the database.</p>";
    }
    }

    
    function display_advanced_data() {
    global $status_message;
    $conn = connect_db("railway");

    
    $sql_advanced = "SELECT 
        s.first_name, 
        s.last_name, 
        s.roll_number, 
        COUNT(e.enrollment_id) AS total_courses_enrolled,
        GROUP_CONCAT(e.course_name SEPARATOR ', ') AS courses
    FROM students s
    INNER JOIN enrollments e ON s.id = e.id
    GROUP BY s.id
    ORDER BY total_courses_enrolled DESC";

    $result = $conn->query($sql_advanced);
    $conn->close();

    if ($result && $result->num_rows > 0) {
        $output = "<h3>Enrollment Summary (JOIN & GROUP BY)</h3>";
        $output .= "<table><thead><tr><th>Student Name</th><th>Roll No.</th><th>Courses Enrolled</th><th>Total Courses</th></tr></thead><tbody>";
        while($row = $result->fetch_assoc()) {
            $output .= "<tr>
                <td>{$row['first_name']} {$row['last_name']}</td>
                <td>{$row['roll_number']}</td>
                <td>{$row['courses']}</td>
                <td>{$row['total_courses_enrolled']}</td>
            </tr>";
        }
        $output .= "</tbody></table>";
        return $output;
    } else {
        return "<p class='warning'>No enrollment data found for the advanced report.</p>";
    }
    }

    
    
    

    
    create_database_and_tables();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

    
    if (isset($_POST['add_data'])) {
        
        $student_data = [
            'first_name' => htmlspecialchars($_POST['first_name']),
            'last_name' => htmlspecialchars($_POST['last_name']),
            'gender' => htmlspecialchars($_POST['gender']),
            'roll_number' => htmlspecialchars($_POST['roll_number']),
            'phone_number' => htmlspecialchars($_POST['phone_number']),
            'course' => htmlspecialchars($_POST['course']), 
            'grade' => 'A' 
        ];
        add_student_data($student_data);
    }

    
    if (isset($_POST['delete_data'])) {
        $roll_to_delete = htmlspecialchars($_POST['roll_to_delete']);
        delete_student_data($roll_to_delete);
    }
    }

    
    
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP MySQLi Database Manager</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        h1 { color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
        h2 { color: #343a40; margin-top: 30px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        h3 { color: #6c757d; }
        form { background-color: #f8f9fa; padding: 20px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #e9ecef; }
        label { display: block; margin-top: 10px; font-weight: bold; color: #495057; }
        input[type="text"], input[type="tel"], select { width: 100%; padding: 10px; margin-top: 5px; margin-bottom: 10px; border: 1px solid #ced4da; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.3s; margin-right: 10px; }
        button:hover { background-color: #218838; }
        .delete-btn { background-color: #dc3545; }
        .delete-btn:hover { background-color: #c82333; }
        .display-btn { background-color: #007bff; }
        .display-btn:hover { background-color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #dee2e6; padding: 12px; text-align: left; }
        th { background-color: #e9ecef; color: #495057; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        .status-box { padding: 15px; margin-bottom: 20px; border-radius: 5px; font-weight: bold; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    </style>
</head>
<body>
    <h2>Name: Harsh Dubey</h2>
    <h2>Roll No: 20232713</h2>

<div class="container">
    <h1>Database Manager for Students</h1>
    <p>Database and tables are automatically initialized upon loading this page.</p>

    <?php
    
    if (!empty($status_message)) {
        echo "<div class='status-box'>$status_message</div>";
    }
    ?>

    <h2>1. Add New Student (Insertion)</h2>
    <form method="POST">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" id="first_name" required value="John">

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" id="last_name" required value="Doe">

        <label>Gender:</label>
        <select name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>

        <label for="roll_number">Roll Number (Unique):</label>
        <input type="text" name="roll_number" id="roll_number" required value="CS<?= rand(100, 999) ?>">

        <label for="phone_number">Phone Number:</label>
        <input type="tel" name="phone_number" id="phone_number" value="1234567890">

        <label for="course">Sample Enrollment Course:</label>
        <input type="text" name="course" id="course" required value="Physics 101">
        
        <button type="submit" name="add_data">➕ Add Data</button>
    </form>

    <h2>2. Delete Student Record (Deletion)</h2>
    <form method="POST">
        <label for="roll_to_delete">Enter Roll Number to Delete:</label>
        <input type="text" name="roll_to_delete" id="roll_to_delete" placeholder="e.g., CS101" required>
        <button type="submit" name="delete_data" class="delete-btn">🗑️ Delete Data</button>
    </form>

    <h2>3. Display Data (Retrieval)</h2>

    <?php echo display_all_data('roll_number'); ?>
    
    <?php echo display_advanced_data(); ?>

</div>
</body>
</html>
