document.addEventListener('DOMContentLoaded' , function(){

    const modal = document.getElementById('modal');
    const appointments = document.querySelectorAll('.cancel_appt');
    const close = document.querySelector('#close-button');
    
    

    let Apptid = '';
    let doctorsname = ''

    appointments.forEach((app)=>{
        app.addEventListener('click' , function(){
            Apptid = this.getAttribute('data-id');
            doctorsname = this.getAttribute('data-name');
            console.log(Apptid);
            console.log(doctorsname);
            modal.style.display = 'block';
        })
    })

    close.addEventListener('click' , function(){
        modal.style.display = 'none';
    })



    document.querySelector('.modal-footer .cancel').addEventListener('click' , function(){
        modal.style.display = 'none';
    })

    document.querySelector('#confirm').addEventListener('click' , function(e){
        e.preventDefault();

        const url = `dr_name=${encodeURIComponent(doctorsname)}&Apptid=${encodeURIComponent(Apptid)}`


        fetch('../Appointment/deleteAppointment.php',{
            method:'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body:url,
            
        })
        .then(res=>{
            if(!res.ok){
                throw new Error('Something wrong in network');
            }
            return res.json();
        })
        .then(data=>{
            if(data.status === 'success'){
                alert('Appointment deleted successfully');
                 modal.style.display = 'none';
                window.location.reload();
            }
            else{
                alert('something wrong , Delte unsuccesfull');
            }
        })
        .catch(err =>{
            console.log(err);
        })
    })

})