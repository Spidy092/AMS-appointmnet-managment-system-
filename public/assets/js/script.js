

//      // add hovered class to selected list item
//      let list = document.querySelectorAll(".navigation li");

//      function activeLink() {
//        list.forEach((item) => {
//          item.classList.remove("hovered");
//        });
//        this.classList.add("hovered");
//      }

//      list.forEach((item) => item.addEventListener("mouseover", activeLink));

//      // Menu Toggle
//      let toggle = document.querySelector(".toggle");
//      let navigation = document.querySelector(".navigation");
//      let main = document.querySelector(".main");

//      toggle.onclick = function () {
//        navigation.classList.toggle("active");
//        main.classList.toggle("active");
//      };


// document.addEventListener('DOMContentLoaded', function() {
//   const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

//   dropdownToggles.forEach(function(toggle) {
//       toggle.addEventListener('click', function(event) {
//           event.preventDefault(); // Prevent the default anchor behavior

//           // Get the associated dropdown
//           const dropdown = toggle.nextElementSibling;

//           // Check if this dropdown is already open
//           const isOpen = dropdown.classList.contains('show');

//           // Close all open dropdowns
//           dropdownToggles.forEach(function(item) {
//               const otherDropdown = item.nextElementSibling;
//               if (otherDropdown.classList.contains('show')) {
//                   otherDropdown.classList.remove('show'); // Hide if already shown
//                   item.parentElement.classList.remove('dropdown-open'); // Remove the open class
//               }
//           });

//           // Toggle the clicked dropdown
//           if (!isOpen) {
//               dropdown.classList.add('show'); // Show if hidden
//               toggle.parentElement.classList.add('dropdown-open'); // Add open class for styling
//           } else {
//               dropdown.classList.remove('show'); // Hide if already shown
//               toggle.parentElement.classList.remove('dropdown-open'); // Remove the open class
//           }
//       });
//   });
// });

// Add hovered class to the selected list item
let list = document.querySelectorAll(".navigation li");

// Function to activate the selected link
function activeLink() {
    list.forEach((item) => {
        item.classList.remove("hovered");
    });
    this.classList.add("hovered");
}

list.forEach((item) => item.addEventListener("mouseover", activeLink));

// Menu Toggle
let toggle = document.querySelector(".toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");

toggle.onclick = function () {
    navigation.classList.toggle("active");
    main.classList.toggle("active");
};

document.addEventListener('DOMContentLoaded', function() {

  const activeLinks = document.querySelectorAll('.navigation > ul > li > a');
  const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
  const currentPage = window.location.pathname.split("/").pop();

  dropdownToggles.forEach(function(toggle) {
      toggle.addEventListener('click', function(event) {
          event.preventDefault();

          // Get the associated dropdown
          const dropdown = toggle.nextElementSibling;

          // Check if this dropdown is already open
          const isOpen = dropdown.classList.contains('show');

          // Close all open dropdowns
          dropdownToggles.forEach(function(item) {
              const otherDropdown = item.nextElementSibling;
              if (otherDropdown.classList.contains('show')) {
                  otherDropdown.classList.remove('show');
                  item.parentElement.classList.remove('dropdown-open', 'hovered');
              }
          });

          // Toggle the clicked dropdown
          if (!isOpen) {
              dropdown.classList.add('show');
              toggle.parentElement.classList.add('dropdown-open', 'hovered');
          } else {
              dropdown.classList.remove('show');
              toggle.parentElement.classList.remove('dropdown-open', 'hovered');
          }
      });
  });

  // Set active link based on the current page
  activeLinks.forEach(link => {
    const linkHref = link.getAttribute("href").split("/").pop();

    if (linkHref === currentPage) {
        link.classList.add("active");
        link.parentElement.classList.add("hovered","active");
    }
  });

  // Set active for dropdown links
  dropdownToggles.forEach(toggle => {
      const dropdownItems = toggle.nextElementSibling.querySelectorAll('a');
      
      dropdownItems.forEach(item => {
          const linkHref = item.getAttribute("href").split("/").pop();

          if (linkHref === currentPage) {
              item.classList.add("active");
              toggle.parentElement.classList.add("dropdown-open", "hovered");
              item.parentElement.classList.add("hovered");

              // Show dropdown if link is matched
              toggle.nextElementSibling.classList.add('show');
          }
      });
  });
});




    













