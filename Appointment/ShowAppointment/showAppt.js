document.addEventListener('DOMContentLoaded', function() {
    const viewBtn = document.querySelectorAll('.view_appt');
    const viewAppointment = document.getElementById('viewappt');
    const closeButton = document.getElementById('close-button_apt');
    let apptid = '';
    let drname = '';
    let username = '';


    // All fields
    const doctorsName = document.querySelector('.doctorsname > span');
    const doctorsspecility = document.querySelector('.doctorspeciality > span');
    const doctorsimg = document.querySelector('.doctorsimage');
    const doctorsEmail = document.querySelector('.doctorsEmail > span');

    const appontTime = document.querySelector('.Appt_time > span');
    const appontDate = document.querySelector('.Appt_date > span');
    const appontNo = document.querySelector('.Appt_no > span');


    const apponiteeName = document.querySelector('.Appti_name > span');
    const apponiteeUserid = document.querySelector('.Appti_Userid > span');
    const apponiteeEmail = document.querySelector('.Appti_email > span');

    
    viewBtn.forEach(btn => {
        btn.addEventListener('click', function() {
            apptid = this.getAttribute('data-id');
            drname = this.getAttribute('data-name');
            username = this.getAttribute('data-username');
            viewAppointment.style.display = 'block';
            console.log(apptid + ' ' + drname + ' ' + username);

            
            const url = `appt_id=${encodeURIComponent(apptid)}&dr_name=${encodeURIComponent(drname)}&username=${encodeURIComponent(username)}`;

            
            fetch('../Appointment/getAppointmentData.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: url
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Network response was not ok');
                }
                return res.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    console.log(data);
                    console.log(data.doctors_edctn)
                    doctorsspecility.innerText = data.doctors_edctn;
                    doctorsName.innerText = drname;
                    doctorsEmail.innerText = data.doctors_email;

                    appontDate.innerText = data.Apptdate;
                    appontTime.innerText = data.
                    ApptTime;
                     appontNo.innerText = apptid;
                          
                     
                     apponiteeName.innerText = data.user_name;
                     apponiteeUserid.innerText = username;
                     apponiteeEmail.innerText = data.user_email;


                } else {
                    console.log(data);
                    alert('There was an error fetching data');
                }
            })
            .catch((err) => { 
                console.log('Error:', err);
            });
        }); 
    });

    
    closeButton.addEventListener('click', function() {
        viewAppointment.style.display = 'none';
    });
});
