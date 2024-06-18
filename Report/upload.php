<?php

include '../db/db.php';

session_start();
$username = "";
if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['user_id'];
}

$appointment = [];

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM appointment WHERE username = ?");
    $stmt->execute([$username]);
    $appointment = $stmt->fetchAll();
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>DOCUEASE - Simplify Health Reports Using AI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="./style.css">
    <!-- <link href="https://assets-global.website-files.com/62c46fcf1ee26edee3200ff6/css/docuease.webflow.83008ab15.css" rel="stylesheet" type="text/css">  -->
</head>

<body>
    <div id="upload-block" class="block">
        <h1 class="user-name">Welcome ,<?php echo htmlspecialchars($username) ?></h1>
        <img src="https://assets-global.website-files.com/62c46fcf1ee26edee3200ff6/6561257da52bf95cfd11bc7e_docuease-logo.svg" loading="lazy" width="64" alt="">
        <h1>Health Reports Made Easy!</h1>
        <p class="content">
            Upload your medical reports here, and let us translate them into simple, easy-to-understand language within 1min.
        </p>
        <button class="upload">Upload Document</button>
    </div>
    <div id="processing-wp" class="block is-hidden">
        <h1 id="description">You report is being generated</h1>
        <p class="content">- Take around 30 seconds<br />- All files are stored on your machine and deleted with each session<br />- Take this information only for reference<br />- Take care of yourself : )</p>
    </div>

    <!-- Appointment -->
    <div class="Allappointment">
        <h1>Your Appointments</h1>
       
        <?php if (count($appointment) > 0) : ?>
            <div class="appointment">
            <?php foreach ($appointment as $app) : ?>
                <div class="app">
                    <div class="first-section">
                        <h6><span>Doctor's Name : </span><?php echo $app['dr_name'] ?></h6>
                        <h6><span>Appointment Date : </span><?php echo $app['date'] ?></h6>
                        <h6><span>Appointment Time : </span><?php echo $app['appt_time'] ?></h6>
                        <h6><span>Appointment No : </span><?php echo $app['appt_id'] ?></h6>

                    </div>
                    <div class="second-section">
                        <button class="view_appt" data-id="<?php echo $app['appt_id'] ?>" data-name="<?php echo $app['dr_name'] ?>"
                        data-username="<?php echo htmlspecialchars($username) ?>"
                        >View</button>
                        <button class="cancel_appt" data-id="<?php echo $app['appt_id'] ?>" data-name="<?php echo $app['dr_name'] ?>">Cancel</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
            <h6 class="no_appt">No Appointments</h6>
        <?php endif; ?>
    </div>

    <!-- Appointment Delete Modal -->
    <div>
        <div class="modal" id="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Cancel Appointment</h2>
                    <span class="close-button" id="close-button">&times;</span>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel the appointment?</p>
                </div>
                <div class="modal-footer">
                    <button class="cancel" id="cancel">Cancel</button>
                    <button class="confirm" id="confirm">Confirm</button>
                </div>
            </div>
        </div>

        <!-- ViewAppointment  -->

        <section id="viewappt">
            <div class="viewAppointment">
                <div class="first_section">
                    <div class="first_sub_section">
                        <img src="../images/img1.jpg" alt="" class="doctorsimage">
                        <h6 class="doctorspeciality"> <span></span></h6>
                    </div>
                    <h4 class="doctorsname">Doctor : <span></span></h4>
                    <h4 class="doctorsEmail">Email : <span></span></h4>
                </div>

                <div class="second_section Appt_section">
                    
                    <h4 class="Appt_date">Appointment Date : <span>njbbn</span></h4>
                    <h4 class="Appt_time">Appointment Time : <span></span></h4>
                    <h4 class="Appt_no">Appointment No : <span></span></h4>
            </div>

            <div class="third_section">
                <h4 class="Appti_name">Appointee's Name: <span></span></h4>
                <h4 class="Appti_Userid">Appointee's userid: <span></span></h4>
                <h4 class="Appti_email">Appointee's Email: <span></span></h6>

            </div>

            <span class="close-button_apt" id="close-button_apt">&times;</span>


        </section>



        <script src="https://cdn.jsdelivr.net/npm/showdown/dist/showdown.min.js"></script>
        <script src="../Appointment/AppointmentDelete/AppointmentDelete.js"></script>
        <script src="../Appointment/ShowAppointment/showAppt.js"></script>
        

        <!-- <script src="./ReportFetch/ReportResult.js"></script> -->

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const uploadButton = document.querySelector(".upload");

                uploadButton.addEventListener("click", function(e) {
                    e.preventDefault();
                    let fileInput = document.createElement("input");
                    fileInput.type = "file";
                    fileInput.accept = "image/*";
                    fileInput.onchange = (e) => {
                        let file = e.target.files[0];

                        if (file && file.size <= 5 * 1024 * 1024) { // 5MB limit
                            processFile(file);
                        } else {
                            alert("Please select an image file smaller than 5MB");
                        }
                    };
                    fileInput.click();
                });
            });

            function processFile(file) {
                // Hide the upload block and show the processing block
                document.getElementById("upload-block").style.display = "none";
                document.getElementById("processing-wp").style.display = "flex";

                // Process the file and generate the description
                generateImageDescription(file);
            }

            function generateImageDescription(file) {
                const apiKey = "*"; // Replace with your actual API key
                const endpoint = "https://api.openai.com/v1/chat/completions";

                toDataURL(file).then((imageUrl) => {
                    document.getElementById("description").innerText = "Your report is being generated";

                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", endpoint, true);
                    xhr.setRequestHeader("Authorization", `Bearer ${apiKey}`);
                    xhr.setRequestHeader("Content-Type", "application/json");

                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            const converter = new showdown.Converter();
                            const descriptionHtml = converter.makeHtml(response.choices[0].message.content);
                            console.log(descriptionHtml);

                            document.getElementById("processing-wp").style.display = "none";
                            const jsonResult = JSON.stringify({
                                descriptionHtml
                            });
                            // document.getElementById("report-block").style.display = "flex";
                            //    document.querySelector(".generated-report").innerHTML = descriptionHtml;
                            fetch('/website/Docuease/Report/LogReport/report.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: jsonResult,
                                })
                                .then((res) => {
                                    if (res.ok) {
                                        console.log(res);
                                        return res.json();
                                    }
                                    throw new Error('Something went wrong');
                                })
                                .then((data) => {
                                    if (data.success) {
                                        console.log(data);
                                        window.location.href = '../Report/LogReport/viewReport.php';

                                    } else {
                                        console.log(data);
                                    }
                                })
                                .catch((error) => {
                                    console.error('Error:', error);
                                });

                        } else {
                            console.error("Error generating image description:", xhr.responseText);
                            document.getElementById("description").innerText = "Error generating description. Please try again.";
                        }
                    };

                    xhr.onerror = function() {
                        console.error("Network Error");
                        document.getElementById("description").innerText = "Network error, please try again.";
                    };

                    const data = JSON.stringify({
                        model: "gpt-4-vision-preview",
                        messages: [{
                            role: "user",
                            content: [{
                                    type: "text",
                                    text: "Provide md with minimal md style: Use h1, h2, h3 and para where neeeded : You are an app designed to simplify health reports for older people. Task: I have a medical report shared by my doctor. I need you to review, summarize, and highlight the details in very simple words. Structure for Response: 1. Overall score out of 10 (wrap only the score text in span with class of hlg and metion score out of 10) (is it good and something that is Concerning) 2. Brief and Helpful Summary: Provide a concise summary of the report in easy-to-understand language. 3. Positive Aspects: List the aspects of the report that are good or normal. 4. Areas of Concern: Identify any areas that need attention or are problematic. 5. Conclusion: End with a note advising to reach back to the doctor for further help or clarification if needed. - In the end add: Incase more help needed - Connect with you doctor (Don't say things like, I am bot, I am sorry, consult to doctor, I am can't help and all those things too much)"
                                },
                                {
                                    type: "image_url",
                                    image_url: {
                                        url: imageUrl,
                                    },
                                },
                            ],
                        }, ],
                        max_tokens: 1200,
                    });

                    xhr.send(data);
                }).catch((error) => {
                    console.error("Error converting file to data URL:", error);
                });
            }

            function toDataURL(file) {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onloadend = () => resolve(reader.result);
                    reader.onerror = () => reject(new Error("Error reading file"));
                    reader.readAsDataURL(file);
                });
            }
        </script>

</body>

</html>