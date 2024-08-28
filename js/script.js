let navbar = document.querySelector('.header .flex .navbar');
let profile = document.querySelector('.header .flex .profile');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   profile.classList.remove('active');
}

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active');
   navbar.classList.remove('active');
}

window.onscroll = () =>{
   navbar.classList.remove('active');
   profile.classList.remove('active');
}

let mainImage = document.querySelector('.quick-view .box .row .image-container .main-image img');
let subImages = document.querySelectorAll('.quick-view .box .row .image-container .sub-image img');

subImages.forEach(images =>{
   images.onclick = () =>{
      src = images.getAttribute('src');
      mainImage.src = src;
   }

   document.addEventListener("DOMContentLoaded", function() {
      const forms = document.querySelectorAll('form');
  
      forms.forEach(form => {
          form.addEventListener('submit', function(event) {
              const submitButton = form.querySelector('input[type="submit"]');
              if (submitButton) {
                  submitButton.disabled = true;
              }
          });
      });
  });
   
});
