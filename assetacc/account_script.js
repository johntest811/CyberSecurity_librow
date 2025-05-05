//  Register and Login Interface by Mikhaela Louise Bernardo

const container = document.querySelector(".container"),
      pwShowHide = document.querySelectorAll(".showHidePw"),
      pwFields = document.querySelectorAll(".password"),
      signUp = document.querySelector(".signup-link"),
      login = document.querySelector(".login-link");

    //   js code to show/hide password and change icon
    pwShowHide.forEach(eyeIcon =>{
    eyeIcon.addEventListener("click", ()=>{
    pwFields.forEach(pwField =>{
    if(pwField.type ==="password"){
    pwField.type = "text";

    pwShowHide.forEach(icon =>{
    icon.classList.replace("uil-eye-slash", "uil-eye");
     })
    }else{
    pwField.type = "password";

    pwShowHide.forEach(icon =>{
    icon.classList.replace("uil-eye", "uil-eye-slash");
     })
  }
    }) 
    })
    })
   // js code to appear signup and login form
   signUp.addEventListener("click", ( )=>{
    container.classList.add("active");
   });
    login.addEventListener("click", ( )=>{
    container.classList.remove("active");
   });



    // js code to appear signup and login form
    var password = document.getElementById("password")
    , confirm_password = document.getElementById("confirm_password");

  function validatePassword(){
    if(password.value != confirm_password.value) {
      confirm_password.setCustomValidity("The password did not match.");
    } else {
      confirm_password.setCustomValidity('');
    }
  }

  password.onchange = validatePassword;
  confirm_password.onkeyup = validatePassword;

  // If the user submits the form when the password and confirm password fields do not match, display an alert and prevent the form from being submitted.
  function handleSubmit() {
    if (password.value != confirm_password.value) {
      alert('The password did not match.');
      return false;
    }
    return true;
  }

  var form = document.getElementById('form1');
  form.onsubmit = handleSubmit;

// email and Name Required 
  function validateForm() {
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;

    if (name == "" || email == "") {
      alert('Please enter your name and email.');
      return false;
    }

    if (email.indexOf('@') < 0) {
      alert('Please enter a valid email address.');
      return false;
    }

    return true;
 }

 var form = document.getElementById('form1');
 form.onsubmit = validateForm;

  
