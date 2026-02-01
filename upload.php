<?php
// PHP Errors display 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Database Connection
$conn = new mysqli("localhost", "root", "Arunoda2001#", "copd_db");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

if (isset($_FILES['audio'])) {
    $targetDir = "uploads/";
    
    // file name
    $fileName = "cough_" . time() . "_" . rand(10, 99) . ".webm";
    $targetFilePath = $targetDir . $fileName;

    // 2. save file
    if (move_uploaded_file($_FILES['audio']['tmp_name'], $targetFilePath)) {
        
        // 3. process data
        $test_id = "T_0008";
        $sample_id = "S_" . rand(1000, 9999);
        $patient_id = "P_0001"; 
        $currentDate = date("Y-m-d");
        $currentTime = date("H:i:s");

        // INSERT Query 
        $sql = "INSERT INTO samples (Test_id, Sample_ID, Audio_URL, Preprocess_URL, Spectrogram_URL, Probability_of_positive, Probability_of_negative, Sample_date, Sample_time, Patient_ID) 
                VALUES ('$test_id', '$sample_id', '$targetFilePath', 'pending', 'pending', 0.0, 0.0, '$currentDate', '$currentTime', '$patient_id')";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "File and DB updated!"]);
        } else {
            //ai dtabase ekata data watenntte check
            echo json_encode(["status" => "error", "message" => "Database Error: " . $conn->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "File move failed"]);
    }
}
$conn->close();
?>