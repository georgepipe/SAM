import { initNav } from "./navigation";
import { initWorkOrders } from "./workorders";
import { initTransferNotes } from "./transfernotes";
import { initAddOrder } from "./addOrder";

const URLROOT = 'http://localhost/SAM/';
// const APPROOT = __dirname


(function () {
    initNav();
    initTransferNotes();
    initWorkOrders();
    initAddOrder();
    // const apifile = URLROOT+'api/workordersApi.php';
    // fetch(apifile,{
    //   headers: {
    //     'X-Requested-With': 'XMLHttpRequest',
    //   }
    // })
    //     .then(response => {
    //         if (!response.ok) {
    //             throw new Error(`HTTP error! Status: ${response.status}`);
    //         } else {console.log(response)}
    //         //return response.json();
    //     })
    //     .then(data => {
    //         // Display data
    //         console.log(data);
    //     })
    //     .catch(error => {
    //         console.error('Fetch error:', error);
    //     });
  })();

// var deleteLinks = document.querySelectorAll('.delete');

// for (var i = 0; i < deleteLinks.length; i++) {
//   deleteLinks[i].addEventListener('click', function(event) {
//       //event.preventDefault();
//       var choice = confirm(this.getAttribute('data-confirm'));
// if (choice) {
//         window.location.href = this.getAttribute('href');
//       }
//   });
// }
