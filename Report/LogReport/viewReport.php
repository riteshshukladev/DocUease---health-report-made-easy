<?php
session_start();

include '../../db/db.php';

$doctors = [];

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT * FROM doctorsinfo");
    $stmt->execute();
    $doctors = $stmt->fetchAll();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Report View</title>
    <link rel="stylesheet" href="./index.css">
</head>

<body>
    <?php

    $filePath = "report.html";
    $content = "";

    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
    } else {
        echo "Report not found.";
    }
    ?>

    <div id="report-block" class="block">
        <div class="generated-report">
            <?php echo $content; ?>
        </div>
    </div>


    <div class="doctor-block">
        <h3>Incase more help needed - Connect with doctor.</h3>
        <div id="doctors">
            <?php foreach ($doctors as $doctor) : ?>
                <div class="doctor">
                    <div class="first-section">
                        <img src=<?php echo htmlspecialchars($doctor['img_url']) ?> alt="">
                        <h6><?php echo $doctor['dr_name'] ?></h6>
                        <h6><?php echo $doctor['dr_ratings'] ?></h6>
                        <p><?php echo $doctor['email'] ?></p>

                    </div>
                    <div class="sec-section">
                        <p><?php echo $doctor['eductn'] ?></p>
                        <button class="doctors_submit_button" data-doctorsname="<?php echo htmlspecialchars($doctor['dr_name']) ?>" data-doctorsid="<?php echo htmlspecialchars($doctor['dr_id']) ?>">Book Appointment</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Appointment Confirmation -->
    <div class="apptConfirmation">
        <div>
            <h1>Confirm Your Appointment Mr.<?php echo htmlspecialchars($username) ?></h1>
            <form class="inputFormSubmission">
                <label for="date">Select Date:</label>
                <input type="date" id="date" name="appt_date" required>
                <label for="time">Select Time:</label>
                <input type="time" id="time" name="appt_time" required>
                <input type="submit" value="Confirm Appointment">
                <input type="cancel" value="Cancel Appointment" class="cancelfilling">
                <input type="hidden" name="username" id="userid" value="<?php echo htmlspecialchars($username) ?>">
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            let doctorsname = '';
            let doctorsid = '';

            const cancelButton = document.querySelector('.cancelfilling');


            // document.querySelectorAll('.doctors_submit_button').forEach(button => {
            //     button.addEventListener('click', function(){
            //         doctorsname = this.getAttribute('data-doctorsname');
            //         doctorsid = this.getAttribute('data-doctorsid');

            //     })
            // })


            const appointment = document.querySelector('.apptConfirmation');
            // document.querySelector('.doctors_submit_button').addEventListener('click' , function(){
            //     appointment.style.display = 'block';

            // })
            document.querySelectorAll('.doctors_submit_button').forEach(button => {
                button.addEventListener('click', function() {
                    doctorsname = this.getAttribute('data-doctorsname');
                    doctorsid = this.getAttribute('data-doctorsid');
                    appointment.style.display = 'block';


                })
            })
            cancelButton.addEventListener('click', function() {
                appointment.style.display = 'none';
            })


            document.querySelector('.inputFormSubmission').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                formData.append('doctorsname', doctorsname);
                formData.append('doctorsid', doctorsid);

                for (let [name, value] of formData.entries()) {
                    console.log(name, value);
                }

                fetch('../../Appointment/appointment.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => {
                        if (!res.ok) {
                            console.log(res);
                            throw new Error('There was an issue with the request');

                        }
                        console.log(res);
                        return res.json();
                    })
                    
                    .then((data) => {
                        if(data.status === 'success'){
                            alert('Appointment Confirmed');
                            appointment.style.display = 'none';
                            window.location.href = '../upload.php';
                        }
                        else{
                            alert('Something went wrong');
                        }
                       
                    })
                    .catch(error => {
                        console.error(error);
                    })


            })

        })
    </script>

</body>

</html>