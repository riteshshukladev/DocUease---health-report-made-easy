<?php
session_start();

include '../db/db.php';


$appointmentDetails = [];
$DrDetails = [];
$userDetails = [];

if($_SERVER['REQUEST_METHOD']=='POST')
{
if(isset($_POST['appt_id'] )&& isset($_POST['dr_name']) &&
isset($_POST['username'])){

$appt_id = $_POST['appt_id'];
$dr_name = $_POST['dr_name'];
$username = $_POST['username'];




try{
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT * FROM appointment WHERE appt_id = ?");
    $stmt->execute([$appt_id]);
    $appointmentDetails = $stmt->fetch();

    $stmt2 = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt2->execute([$username]);
    $userDetails = $stmt2->fetch();

    $stmt3 = $pdo->prepare("SELECT * FROM doctorsinfo WHERE dr_name = ?");
    $stmt3->execute([$dr_name]);
    $DrDetails = $stmt3->fetch();


    $pdo->commit();

    echo json_encode(["status" => "success","doctors_email"=> $DrDetails['email'] , "dr_name"=>$dr_name, "doctors_edctn" => $DrDetails['eductn'], "Docotrsimg"=>$DrDetails['img_url'],  "Apptdate" => $appointmentDetails['date'], "ApptTime" => $appointmentDetails['appt_time'], "appt_id" => $appt_id, "username" => $username, "user_email" => $userDetails['email'], "user_name" => $userDetails['name']]);

    // session_start();
    // $_SESSION['projectname'] = $projectname;
    // $_SESSION['username'] = $username;
    // $_SESSION['addressanddate'] = $addressanddate;
    // $_SESSION['sendersdetails'] = $sendersdetails;
    // $_SESSION['services'] = $services;
    // $_SESSION['bankdetails'] = $bankdetails;
}   

catch(Exception $e){
    $pdo->rollBack();
    echo json_encode(["status" => "error", "message" => "An error occurred. Please try again."]);
}
}
else{
    echo json_encode(["status" => "error", "message" => "Invalid parameters."]);
}


}
else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>