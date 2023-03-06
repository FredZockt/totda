import './bootstrap';


// Flyout 

const flyout = document.querySelector('.flyout');
const toggle = document.querySelector('.toggle');




toggle.addEventListener('click', () => {
  if(flyout.classList.contains('fade-in')) {
    	flyout.classList.remove('fade-in');
      return
  }
  flyout.classList.add('fade-in');
})