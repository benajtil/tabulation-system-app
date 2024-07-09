<?php
require_once 'db/db_connection.php'; // MySQL connection
require_once 'db/db_connection_sqlite.php'; // SQLite connection

// Ensure PDO is used for both connections
try {
    // Sync data from SQLite to MySQL for 'contestant' table
    $results = $db->query("SELECT * FROM contestant");
    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
        $sql = "INSERT INTO contestant (id, name, entry_num, created_at, updated_at, scored)
                VALUES (:id, :name, :entry_num, :created_at, :updated_at, :scored)
                ON DUPLICATE KEY UPDATE name=:name, entry_num=:entry_num, created_at=:created_at, updated_at=:updated_at, scored=:scored";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $row['id'], PDO::PARAM_INT);
        $stmt->bindValue(':name', $row['name'], PDO::PARAM_STR);
        $stmt->bindValue(':entry_num', $row['entry_num'], PDO::PARAM_STR);
        $stmt->bindValue(':created_at', $row['created_at'], PDO::PARAM_STR);
        $stmt->bindValue(':updated_at', $row['updated_at'], PDO::PARAM_STR);
        $stmt->bindValue(':scored', $row['scored'], PDO::PARAM_INT);
        if (!$stmt->execute()) {
            echo "Error syncing contestant data: " . implode(", ", $stmt->errorInfo()) . "\n";
        }
    }

    // Sync data from SQLite to MySQL for 'overallscores' table
    $results = $db->query("SELECT * FROM overallscores");
    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
        $sql = "INSERT INTO overallscores (id, judge_id, entry_num, score, created_at, updated_at, compiled_scores)
                VALUES (:id, :judge_id, :entry_num, :score, :created_at, :updated_at, :compiled_scores)
                ON DUPLICATE KEY UPDATE judge_id=:judge_id, entry_num=:entry_num, score=:score, created_at=:created_at, updated_at=:updated_at, compiled_scores=:compiled_scores";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $row['id'], PDO::PARAM_INT);
        $stmt->bindValue(':judge_id', $row['judge_id'], PDO::PARAM_INT);
        $stmt->bindValue(':entry_num', $row['entry_num'], PDO::PARAM_STR);
        $stmt->bindValue(':score', $row['score'], PDO::PARAM_STR);
        $stmt->bindValue(':created_at', $row['created_at'], PDO::PARAM_STR);
        $stmt->bindValue(':updated_at', $row['updated_at'], PDO::PARAM_STR);
        $stmt->bindValue(':compiled_scores', $row['compiled_scores'], PDO::PARAM_STR);
        if (!$stmt->execute()) {
            echo "Error syncing overallscores data: " . implode(", ", $stmt->errorInfo()) . "\n";
        }
    }

    // Sync data from SQLite to MySQL for 'scores' table
    $results = $db->query("SELECT * FROM scores");
    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
        $sql = "INSERT INTO scores (entry_num, judge_name, overall_appearance, artistry_design, craftsmanship, relevance_theme, created, updated, total_score)
                VALUES (:entry_num, :judge_name, :overall_appearance, :artistry_design, :craftsmanship, :relevance_theme, :created, :updated, :total_score)
                ON DUPLICATE KEY UPDATE overall_appearance=:overall_appearance, artistry_design=:artistry_design, craftsmanship=:craftsmanship, relevance_theme=:relevance_theme, created=:created, updated=:updated, total_score=:total_score";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':entry_num', $row['entry_num'], PDO::PARAM_STR);
        $stmt->bindValue(':judge_name', $row['judge_name'], PDO::PARAM_STR);
        $stmt->bindValue(':overall_appearance', $row['overall_appearance'], PDO::PARAM_STR);
        $stmt->bindValue(':artistry_design', $row['artistry_design'], PDO::PARAM_STR);
        $stmt->bindValue(':craftsmanship', $row['craftsmanship'], PDO::PARAM_STR);
        $stmt->bindValue(':relevance_theme', $row['relevance_theme'], PDO::PARAM_STR);
        $stmt->bindValue(':created', $row['created'], PDO::PARAM_STR);
        $stmt->bindValue(':updated', $row['updated'], PDO::PARAM_STR);
        $stmt->bindValue(':total_score', $row['total_score'], PDO::PARAM_STR);
        if (!$stmt->execute()) {
            echo "Error syncing scores data: " . implode(", ", $stmt->errorInfo()) . "\n";
        }
    }

    // Sync data from SQLite to MySQL for 'user' table
    $results = $db->query("SELECT * FROM user");
    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
        $sql = "INSERT INTO user (id, username, password, name, role, status, created_at, updated_at)
                VALUES (:id, :username, :password, :name, :role, :status, :created_at, :updated_at)
                ON DUPLICATE KEY UPDATE username=:username, password=:password, name=:name, role=:role, status=:status, created_at=:created_at, updated_at=:updated_at";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $row['id'], PDO::PARAM_INT);
        $stmt->bindValue(':username', $row['username'], PDO::PARAM_STR);
        $stmt->bindValue(':password', $row['password'], PDO::PARAM_STR);
        $stmt->bindValue(':name', $row['name'], PDO::PARAM_STR);
        $stmt->bindValue(':role', $row['role'], PDO::PARAM_INT);
        $stmt->bindValue(':status', $row['status'], PDO::PARAM_STR);
        $stmt->bindValue(':created_at', $row['created_at'], PDO::PARAM_STR);
        $stmt->bindValue(':updated_at', $row['updated_at'], PDO::PARAM_STR);
        if (!$stmt->execute()) {
            echo "Error syncing user data: " . implode(", ", $stmt->errorInfo()) . "\n";
        }
    }

    echo "Data sync completed.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the SQLite connection
$db->close();

// Close the MySQL connection
$conn = null;
?>
