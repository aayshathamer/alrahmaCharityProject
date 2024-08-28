<?php
include '../components/connect.php';

// Function to notify admin
function notify_admin($project_name) {
    // Add your admin notification logic here, e.g., send an email
    $to = 'admin@example.com';
    $subject = 'Project Completed';
    $message = 'The project "' . $project_name . '" has reached its goal amount and is now marked as completed.';
    $headers = 'From: no-reply@example.com';
    mail($to, $subject, $message, $headers);
}

// Fetch ongoing projects
$select_projects = $conn->prepare("SELECT * FROM `projects` WHERE status = 'ongoing'");
$select_projects->execute();
$projects = $select_projects->fetchAll(PDO::FETCH_ASSOC);

foreach ($projects as $project) {
    if ($project['current_amount'] >= $project['goal_amount']) {
        // Update project status to completed
        $update_status = $conn->prepare("UPDATE `projects` SET status = 'completed' WHERE id = ?");
        $update_status->execute([$project['id']]);
        
        // Notify admin
        notify_admin($project['name']);
    }
}
?>
