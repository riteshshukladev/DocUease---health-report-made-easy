<?php 

session_start();
include '../db/db.php';




if($_SERVER['REQUEST_METHOD']=='POST')
{
    if(isset($_POST['Apptid'])&& isset($_POST['dr_name']))
    {
        $appt_id = $_POST['Apptid'];
        $dr_name = $_POST['dr_name'];
        
        $stmt = $pdo->prepare("DELETE FROM appointment WHERE appt_id = ? and dr_name = ?");
        $stmt->execute([$appt_id,$dr_name]);
        
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
else
{
    echo json_encode(['status' => 'error' , 'message' => 'You are not authorized to access this page.']);
}
?>