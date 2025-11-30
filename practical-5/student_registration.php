<?php
    $servername = "crossover.proxy.rlwy.net";
    $username = "root";
    $password = "ixtbUQMUnKHazdIfBswQQupZxXMLuADx";
    $dbname = "railway";
    
    $conn = new mysqli($servername, $username, $password, $dbname, 52914);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "<h2>Registration Processing Status</h2>";

    if (isset($_POST['submit_post'])) {

        // 2. Retrieve Data using $_POST Superglobal
        $first_name = $_POST['first_name'];
        $last_name  = $_POST['last_name'];
        $gender     = $_POST['gender'];
        $roll_number= $_POST['roll_number'];
        $phone_number= $_POST['phone_number'];
        $course     = $_POST['course'];

        $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, gender, roll_number, phone_number, course) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $first_name, $last_name, $gender, $roll_number, $phone_number, $course);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Student record inserted successfully into the database.</p>";
        } else {
            echo "<p style='color: red;'>Could not insert record. " . $stmt->error . "</p>";
        }

        $stmt->close();

    }

    if (isset($_GET['id'])) {
        $id = htmlspecialchars($_GET['id']);
        $sql = "SELECT first_name, last_name, gender, roll_number, phone_number, course 
        FROM students 
        WHERE roll_number = " . $id;

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $student = $result->fetch_assoc();

                echo "<h2>Student Record Details</h2>";
                echo "<table border='1' cellpadding='10'>";
                echo "<tr><td>Roll Number</td><td>" . $student['roll_number'] . "</td></tr>";
                echo "<tr><td>First Name</td><td>" . $student['first_name'] . "</td></tr>";
                echo "<tr><td>Last Name</td><td>" . $student['last_name'] . "</td></tr>";
                echo "<tr><td>Gender</td><td>" . $student['gender'] . "</td></tr>";
                echo "<tr><td>Phone Number</td><td>" . $student['phone_number'] . "</td></tr>";
                echo "<tr><td>Course</td><td>" . $student['course'] . "</td></tr>";
                echo "</table>";
            } else {
                echo "<p>No student found.</p>";
            }
        } else {
            echo "<p>No student found.</p>";
        }

        $stmt->close();
    }

    $conn->close();
?>
