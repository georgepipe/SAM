function initTransport() {

    function checkboxStyler() {

        const divTrans = document.querySelector(".divTrans");
        if(divTrans) {
            const checkBoxes = document.querySelectorAll(".tCheck");
            checkBoxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const row = checkbox.closest("tr");
                    if (checkbox.checked) {
                        row.classList.add("checked");
                    } else {
                        row.classList.remove("checked");
                    }
                })
            })
        }

    }



    checkboxStyler();

}

export {initTransport};