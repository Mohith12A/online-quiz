<?php
// dashboard_data.php - Get all data for the dashboard
require_once 'config.php';

function getDashboardData($userId) {
    $conn = getDbConnection();
    $data = [];
    
    // Get user info
    $sql = "SELECT username, email, profile_image FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $data['user'] = $result->fetch_assoc();
    
    // Get total quizzes attempted
    $sql = "SELECT COUNT(*) as total FROM user_attempts WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $data['quizzes_attempted'] = $result->fetch_assoc()['total'];
    
    // Get average score
    $sql = "SELECT AVG(score) as average FROM user_attempts WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $avg = $result->fetch_assoc()['average'];
    $data['average_score'] = $avg ? round($avg, 0) : 0;
    
    // Get current streak
    $sql = "SELECT current_streak FROM user_streaks WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $streak = $result->fetch_assoc();
    $data['current_streak'] = $streak ? $streak['current_streak'] : 0;
    
    // Get solving progress (correct vs incorrect answers)
    $sql = "SELECT 
                SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) as correct,
                SUM(CASE WHEN is_correct = 0 THEN 1 ELSE 0 END) as incorrect
            FROM user_answers 
            JOIN user_attempts ON user_answers.attempt_id = user_attempts.attempt_id
            WHERE user_attempts.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $progress = $result->fetch_assoc();
    $data['solving_progress'] = [
        'correct' => (int)($progress['correct'] ?? 0),
        'incorrect' => (int)($progress['incorrect'] ?? 0)
    ];
    
    // Get difficulty levels counts
    $sql = "SELECT 
                q.difficulty,
                COUNT(DISTINCT ua.attempt_id) as count
            FROM user_attempts ua
            JOIN quizzes q ON ua.quiz_id = q.quiz_id
            WHERE ua.user_id = ?
            GROUP BY q.difficulty";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $difficulties = ['easy' => 0, 'medium' => 0, 'hard' => 0];
    while ($row = $result->fetch_assoc()) {
        $difficulties[$row['difficulty']] = (int)$row['count'];
    }
    $data['difficulties'] = $difficulties;
    
    // Get recent quizzes
    $sql = "SELECT 
                q.title, 
                ua.attempt_date
            FROM user_attempts ua
            JOIN quizzes q ON ua.quiz_id = q.quiz_id
            WHERE ua.user_id = ?
            ORDER BY ua.attempt_date DESC
            LIMIT 4";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $data['recent_quizzes'] = [];
    while ($row = $result->fetch_assoc()) {
        $data['recent_quizzes'][] = [
            'title' => $row['title'],
            'date' => date('F j', strtotime($row['attempt_date']))
        ];
    }
    
    // Get upcoming quizzes
    $sql = "SELECT 
                title, 
                scheduled_date
            FROM quizzes
            WHERE scheduled_date >= CURDATE()
            ORDER BY scheduled_date
            LIMIT 3";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $data['upcoming_quizzes'] = [];
    while ($row = $result->fetch_assoc()) {
        $data['upcoming_quizzes'][] = [
            'title' => $row['title'],
            'date' => date('F j', strtotime($row['scheduled_date']))
        ];
    }
    
    // Get achievements
    $sql = "SELECT 
                a.name,
                a.icon,
                a.level
            FROM user_achievements ua
            JOIN achievements a ON ua.achievement_id = a.achievement_id
            WHERE ua.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $data['achievements'] = [];
    while ($row = $result->fetch_assoc()) {
        $data['achievements'][] = [
            'name' => $row['name'],
            'icon' => $row['icon'],
            'level' => $row['level']
        ];
    }
    
    $conn->close();
    return $data;
}
?>