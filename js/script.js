let userBox = document.querySelector('.header .header-2 .user-box');

document.querySelector('#user-btn').onclick = () => {
  userBox.classList.toggle('active');

  document.addEventListener('DOMContentLoaded', function() {
    // Get the toggle button
    const darkModeToggle = document.getElementById('user-btn');
    
    // Toggle dark mode on button click
    darkModeToggle.addEventListener('click', function() {
      if (document.body.classList.contains('dark-mode')) {
        // Switch to light mode
        document.body.classList.remove('dark-mode');
        localStorage.setItem('theme', 'light');
        darkModeToggle.classList.replace('bx-sun', 'bx-moon');
      } else {
        // Switch to dark mode
        document.body.classList.add('dark-mode');
        localStorage.setItem('theme', 'dark');
        darkModeToggle.classList.replace('bx-moon', 'bx-sun');
      }
    });
  });
}
