function initTransferNotes () {
    /**
     * 
     * TODO:
     * set html element(s) as constants 
     * add hover and on click events 
     */
    const URLROOT = 'http://localhost/SAM/';
    const tNoteRows = document.querySelectorAll(".tnoterow");
    const tnoteChecks = document.querySelectorAll(".tCheck")
    const colBtn = document.querySelector(".colBtn");
    const delBtn = document.querySelector(".delBtn");
    const deleteBtn = document.querySelector(".delete");

    let i;
    let j;
    let k;
    let l;
    let checks;

    if(tNoteRows.length > 1) {
        for (i=0; i < tNoteRows.length; i++ ) {
            tNoteRows[i].addEventListener("click", (e) => {
                const tnid = e.target.parentElement.dataset.id
                window.location.href = URLROOT+"transport/viewtn/"+tnid
            })
        }
    }
    let visibleBtns = 0
    let wkos = []
    if(tnoteChecks.length > 1) {
        for (j=0; j < tnoteChecks.length; j++) { //need to check value of all check boxes and sum total
            tnoteChecks[j].addEventListener("change", (e) => { //if total is > 0 then show btns otherwise hide them
                let isChecked = e.target.checked
                let wkoid = e.target.parentElement.dataset.id
                isChecked ? visibleBtns++ : visibleBtns--
                if (isChecked && visibleBtns>0) {
                    colBtn.classList.remove('hidden');
                    colBtn.classList.add('block');
                    delBtn.classList.remove('hidden');
                    delBtn.classList.add('block');
                    return; 
                } else if(visibleBtns<1) {
                    colBtn.classList.remove('block');
                    colBtn.classList.add('hidden');
                    delBtn.classList.remove('block');
                    delBtn.classList.add('hidden');
                    return; 
                } 
            })
        }

        colBtn.addEventListener('click', (e) => {
            wkos = []
            for (k=0; k < tnoteChecks.length; k++) {
                if(tnoteChecks[k].checked) {
                    wkos.push(tnoteChecks[k].parentElement.dataset.id)
                }
            }
            console.log(wkos)
            window.location.href = "http://localhost/SAM/transport/tnote/c/"+wkos
        })
        
        delBtn.addEventListener('click', (e) => {
            //do the delivery note stuff innit
            wkos = []
            for (l=0; l < tnoteChecks.length; l++) {
                if(tnoteChecks[l].checked) {
                    wkos.push(tnoteChecks[l].parentElement.dataset.id)
                }
            }
            console.log(wkos)
            window.location.href = "http://localhost/SAM/transport/tnote/d/"+wkos
        })

        // deleteBtn.addEventListener('click', (e) => {
        //         //do the delivery note stuff innit
        //         //event.preventDefault();
        //         tnid = deleteBtn.parentElement.dataset.id
        //         console.log('Hello!');
        //         console.log(tnid);
        //         // window.location.href = "http://localhost/SAM/transport/tnote/d/"+wkos
        //     })
        }

//document.write('test point');
}

export { initTransferNotes }