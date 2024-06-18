<?php

session_start();

include '../db/db.php';

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_SESSION['user_id']))
{
   
    if(isset($_POST['doctorsname']) && isset($_POST['appt_date']) && isset($_POST['appt_time']) && isset($_POST['doctorsid']) && isset($_POST['username']))
      {
        $doctorsname = $_POST['doctorsname'];
        $appt_date = $_POST['appt_date'];
        $appt_time = $_POST['appt_time'];
        $doctorsid = $_POST['doctorsid'];
        $username = $_POST['username'];
        
        $stmt = $pdo->prepare("INSERT INTO appointment (dr_name,date, appt_time, dr_id, username) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$doctorsname, $appt_date, $appt_time, $doctorsid, $username]);
        
        if($stmt->rowCount())
        {
            echo json_encode(['status' => 'success']);
        }
        else
        {
            echo json_encode(['status' => 'error' , 'message' => 'Something went wrong.']);
      }
    }
    else
    {
        echo json_encode(['status' => 'error' , 'message' => 'All fields are required.']);
    }
    
}

?>