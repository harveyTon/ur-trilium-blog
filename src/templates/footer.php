 </div>
 </div>
 <div id="back-to-top"></div>
 <script>
     const backToTopButton = document.getElementById("back-to-top");

     window.onscroll = function() {
         if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
             backToTopButton.style.display = "block";
         } else {
             backToTopButton.style.display = "none";
         }
     };

     backToTopButton.addEventListener("click", function() {
         document.body.scrollTop = 0; // For Safari
         document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
     });

     window.addEventListener('DOMContentLoaded', () => {
         // Get all menu items with a submenu
         const menuItems = document.querySelectorAll('nav li > a');

         // Add a click event listener to each menu item
         menuItems.forEach(function(menuItem) {
             menuItem.addEventListener('click', function(event) {
                 // Get the submenu
                 const submenu = this.nextElementSibling;

                 // If the submenu is visible...
                 if (getComputedStyle(submenu).display !== 'none') {
                     // ...change the class of this menu item from 'expanded' to 'collapsed'
                     this.classList.remove('expanded');
                     this.classList.add('collapsed');
                 } else {
                     // Otherwise, change the class of this menu item from 'collapsed' to 'expanded'
                     this.classList.remove('collapsed');
                     this.classList.add('expanded');
                 }

                 // Toggle the visibility of the submenu
                 submenu.style.display = (submenu.style.display !== 'none' ? 'none' : 'block');

                 // Prevent the default click action (this will prevent link navigation)
                 event.preventDefault();
             });
         });
     });
 </script>
 </body>

 </html>