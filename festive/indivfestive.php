<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JUDGING SHEET</title>
    <link rel="stylesheet" href="/festive/css/festive.css">
    </style>
</head>

<body>
    <div class="tnalaklogo">
        <img src="../tnalak.png" alt="t'nalak image">
    </div>
    <div class="emblem">
        <img src="../emblem.png" alt="t'nalak image">
    </div>

    <div class="container">
        <?php
        // Retrieve judge name from query string
        $judge = isset($_GET['judge']) ? htmlspecialchars($_GET['judge']) : '';

        // Include database connection
        require('../db/db_connection_festive.php');

        // Fetch all judges (role 1) and assign judge numbers
        $judge_query = "SELECT id, name FROM user WHERE role = 1 ORDER BY id ASC";
        $stmt = $conn->prepare($judge_query);

        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->execute();
        $stmt->bind_result($judge_id, $judge_name);
        
        $judges = [];
        $judge_number = 1;

        while ($stmt->fetch()) {
            $judges[$judge_name] = $judge_number++;
        }

        $stmt->close();
        $query = "SELECT entry_num, festive_spirit, costume_and_props, relevance_to_the_theme FROM scores WHERE judge_name = ?";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("s", $judge);
        $stmt->execute();
        $stmt->bind_result($entry_num, $festive_spirit, $costume_and_props, $relevance_to_the_theme);

        echo "<table>";
        echo "<thead><tr><th>Entry No.</th><th>Festive Spirit Of Parade Participants (50%) <p>(Festive-feel, Festive-look, Festivity, Color, Use of Liveners, Enthusiasm)</p> </th><th>Costume and Props (30%) <p>(Creativity & Uniqueness)</p> </th><th>Relevance to the Theme (20%) <p>(Theme: Onward South Cotabato: Dreaming Big, Weaving more progress. Rising above challenges)</p> </th><th>Total</th></tr></thead>";
        echo "<tbody>";

    
        while ($stmt->fetch()) {
            $total_score = $festive_spirit + $costume_and_props + $relevance_to_the_theme;
            echo "<tr>";
            echo "<td>" . htmlspecialchars($entry_num) . "</td>";
            echo "<td>" . htmlspecialchars($festive_spirit) . "</td>";
            echo "<td>" . htmlspecialchars($costume_and_props) . "</td>";
            echo "<td>" . htmlspecialchars($relevance_to_the_theme) . "</td>";
            echo "<td>" . htmlspecialchars($total_score) . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
        $stmt->close();
        $conn->close();

        if (isset($judges[$judge])) {
            $judge_num = $judges[$judge];
            echo "<h2>Judge $judge_num: <u>$judge</u></h2>";
        } else {
            echo "<h2>Judge: <u>$judge</u></h2>";
        }
        ?>
    </div>
</body>

</html>
