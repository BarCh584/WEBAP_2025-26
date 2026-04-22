<?php
include "dbconn.php";

$stmt = $conn->prepare("SELECT * FROM games WHERE status = 'waiting' ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<tr><td colspan='6'>No games found</td></tr>";
}

while ($row = $result->fetch_assoc()) {

    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . selectusername($row['user1_id']) . "</td>";
    echo "<td>" . selectusername($row['user2_id']) . "</td>";
    echo "<td>" . $row['score'] . "</td>";
    echo "<td>" . $row['status'] . "</td>";

    if ($row['status'] == "waiting") {
        echo "<td><button onclick='joinGame(" . $row['id'] . ")'>Join</button></td>";
    } else {
        echo "<td><button disabled>Join</button></td>";
    }

    echo "</tr>";
}
function selectusername($userid)
{
    global $conn;
    $selectusername = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $selectusername->bind_param("i", $userid);
    $selectusername->execute();
    $row = $selectusername->get_result()->fetch_assoc();
    if(isset($row['username'])){
    return $row['username'];
    } else {
        return "N/A";
    }
}
