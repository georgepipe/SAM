function initTransport() {

    function checkboxStyler() {

        const divTrans = document.querySelector(".divTrans");

        if(!divTrans) return;

        const checkBoxes = document.querySelectorAll(".tCheck");

        checkBoxes.forEach(checkbox => {

            const row = checkbox.closest("tr");

            checkbox.addEventListener('change', function() { //there's a native toggle function avaliable for this .classList.toggle
                if (checkbox.checked) {
                    row.classList.add("checked");
                } else {
                    row.classList.remove("checked");
                }
            })

            row.addEventListener('click', function (e) {
                if(e.target == checkbox) return;

                checkbox.checked = !checkbox.checked;

                checkbox.dispatchEvent(new Event('change'));
            });
        })



    }



    checkboxStyler();

}

export {initTransport};