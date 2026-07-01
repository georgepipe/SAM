function initTransport() {

    function checkboxStyler() {

        const divTrans = document.querySelector(".divTrans");

        if(!divTrans) return;

        const checkBoxes = document.querySelectorAll(".tCheck");

        checkBoxes.forEach(checkbox => {

            const row = checkbox.closest("tr");

            checkbox.addEventListener('change', function() { 
                row.classList.toggle('checked');
            })

            row.addEventListener('click', function (e) {
                if(e.target == checkbox) return;

                checkbox.checked = !checkbox.checked;

                checkbox.dispatchEvent(new Event('change'));
            });
        })



    }

    // function deliveryButtonToggle() {
    //     const deliveryButton = document.querySelector();
    // }


    checkboxStyler();

}

export {initTransport};