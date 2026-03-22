function initWorkOrders () {
    /**
     * 
     * TODO:
     * set html element(s) as constants 
     * add hover and on click events
     * 
     * handle split order parameters
     * 
     * CHANGE CODE TO QUERY DB FOR MODELS WITH GRILLS &
     * WAVEGUIDES TO DYNAMICALLY EVALUATE USER DROPDOWN INPUT 
     * 
     * Dynamically update result pages based on pagination buttons
     * 
     */

    //Representational State Transfer (REST) -> GET, POST, PUT/PATCH, DELETE
    if(window.location.href === 'http://localhost/SAM/workorders/index') {   
    // if(1===1) {   
        console.log('we have started pagination function')
        const pgBtns = document.querySelectorAll(".pgBtn");
        const pageNumberInfoA = document.querySelector(".pageInfoA")
        const pageNumberInfoB = document.querySelector(".pageInfoB")
        const addWkoBtn = document.querySelector(".addWkoBtn")
        //const addWko
        let totalAResults = document.querySelector(".Acount")
        totalAResults = totalAResults.dataset.count
        let totalCResults = document.querySelector(".Ccount")
        totalCResults = totalCResults.dataset.count
        let currentStart
        let currentEnd
        let h 
        let dateIn
        let wko
        let avn
        let pdesc
        let wheels
        let quantity
        let quantity_built
        let serials
        let wko_status
        let wko_delivery
        let wko_notes
        let apiFile = '../api/workorder/'


        for (h = 0; h < pgBtns.length; h++) {
            pgBtns[h].addEventListener("click", (e) => {
                let page = e.target.dataset.page
                let tag = e.target.parentElement.dataset.wkos

                console.log(tag)
                tag === "com" ? apiFile = '../apiworkorders/paginate/completed/' + page : apiFile = '../apiworkorders/paginate/active/' + page
                //let apiFile = '../api/workorder/' + page
                fetch(apiFile, {
                    headers: {
                    "Content-Type": "application/json",
                    },
                })
                    .then(response => response.json())
                    .then(response => {
                        console.log(response) //testing response=
                        let length = Object.keys(response).length
                        console.log('length of returned object array is: '+length)

                        let trClass
                        let Table = ''
                        tag === 'com' ? Table = document.querySelector("#tblCwo") : Table = document.querySelector("#tblAwo")

                        let rowNo = Table.rows.length
                        console.log("row numbers: "+rowNo)
                        for (i = 1; i < rowNo; i++) {
                            Table.deleteRow(1)
                        }
                    
                        for (i = 1; i < length+1; i++) {
                            if (response[i-1].wko_status) {
                                switch(response[i-1].wko_status) {
                                    case 'In Progress':
                                        trClass = 'inprogress';
                                        break;
                                    case 'To Be Built':
                                        trClass = 'tobebuilt';
                                        break;
                                    case 'On Hold':
                                        trClass = 'onhold';
                                        break;
                                    case 'Waiting For Parts':
                                        trClass = 'waitingforparts';
                                        break;
                                    case 'Upcoming':
                                        trClass = 'upcoming';
                                        break;
                                    default:
                                        trClass = '';
                                        break;
                                }
                            }
                            let Tbody
                            
                            tag === 'com' ? Tbody = document.querySelector("#tbodyCwo") : Tbody = document.querySelector("#tbodyAwo") 

                            let row = Tbody.insertRow(0)
                            row.classList.add("text-center", "items-center", "border-4", "worow", trClass, "min-h-3","slow-fade-in")

                            const tdDate = document.createElement("td")
                                tdDate.classList.add("text-nowrap")
                            const tdWko = document.createElement("td")
                            const tdAvn = document.createElement("td")
                            const tdPdesc = document.createElement("td")
                                tdPdesc.classList.add("text-[10px]")
                            const tdQuantityR = document.createElement("td")
                            const tdQuantityB = document.createElement("td")
                            const tdSerials = document.createElement("td")
                            const tdWkoS = document.createElement("td")
                            const tdWkoD = document.createElement("td")
                            const tdWkoN = document.createElement("td")
                                tdWkoN.classList.add("min-w-24")
                            const tdComp = document.createElement("td")
                            const tdSplit = document.createElement("td")
                            const tdEdit = document.createElement("td")
                            const tdDelete = document.createElement("td")


                            const acomp = document.createElement("a")
                            const asplit = document.createElement("a")
                            const aedit = document.createElement("a")
                            const adelete = document.createElement("a")

                            let dateIn = response[i-1].created_at
                            dateIn = dateIn.slice(0,10)
                            dateIn = document.createTextNode(dateIn)
                            let dateCompleted = response[i-1].completed_at
                            dateCompleted = JSON.stringify(dateCompleted)
                            if (dateCompleted === 'null') {
                                dateCompleted = document.createTextNode('N/A')
                            } else {
                                dateCompleted = dateCompleted.slice(1,11)
                                dateCompleted = document.createTextNode(dateCompleted)
                            }
                            

                            wko = document.createTextNode(response[i-1].wko)
                            avn = document.createTextNode(response[i-1].avn) 
                            pdesc = document.createTextNode(response[i-1].pdesc) 
                            quantity = document.createTextNode(response[i-1].quantity)
                            serials = response[i-1].serials === null ? document.createTextNode('') : document.createTextNode(response[i-1].serials)
                            wko_status = document.createTextNode(response[i-1].wko_status)
                            wko_delivery = document.createTextNode(response[i-1].wko_delivery)
                            wko_notes = document.createTextNode(response[i-1].wko_notes)

                            acomp.setAttribute("href", "http://localhost/SAM/workorders/complete/"+response[i-1].work_order_id)
                                acomp.innerHTML += '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path class="mrk-as-comp" stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>'
                            asplit.setAttribute("href", "javascript:void(0)")
                            asplit.setAttribute("data-qty", response[i-1].quantity)
                                asplit.innerHTML += '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path class="split-order" stroke-linecap="round" stroke-linejoin="round" d="m7.848 8.25 1.536.887M7.848 8.25a3 3 0 1 1-5.196-3 3 3 0 0 1 5.196 3Zm1.536.887a2.165 2.165 0 0 1 1.083 1.839c.005.351.054.695.14 1.024M9.384 9.137l2.077 1.199M7.848 15.75l1.536-.887m-1.536.887a3 3 0 1 1-5.196 3 3 3 0 0 1 5.196-3Zm1.536-.887a2.165 2.165 0 0 0 1.083-1.838c.005-.352.054-.695.14-1.025m-1.223 2.863 2.077-1.199m0-3.328a4.323 4.323 0 0 1 2.068-1.379l5.325-1.628a4.5 4.5 0 0 1 2.48-.044l.803.215-7.794 4.5m-2.882-1.664A4.33 4.33 0 0 0 10.607 12m3.736 0 7.794 4.5-.802.215a4.5 4.5 0 0 1-2.48-.043l-5.326-1.629a4.324 4.324 0 0 1-2.068-1.379M14.343 12l-2.882 1.664" /></svg>'
                            aedit.setAttribute("href", "http://localhost/SAM/workorders/edit/"+response[i-1].work_order_id)
                                aedit.innerHTML += '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path class="edit-order edit" stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" /></svg>'
                            adelete.setAttribute("href", "http://localhost/SAM/workorders/delete/"+response[i-1].work_order_id)
                                adelete.innerHTML += '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path class="dlt-order delete" stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>'


                            if (tag === 'com') {
                                tdDate.appendChild(dateCompleted)
                            } else {
                                tdDate.appendChild(dateIn)
                            }

                            tdWko.appendChild(wko)
                            tdAvn.appendChild(avn)
                            tdPdesc.appendChild(pdesc)
                            tdQuantityR.appendChild(quantity)
                            tdSerials.appendChild(serials)
                            tdWkoS.appendChild(wko_status)
                            tdWkoD.appendChild(wko_delivery)
                            tdWkoN.appendChild(wko_notes)
                            tdComp.appendChild(acomp)
                            tdSplit.appendChild(asplit)
                            tdEdit.appendChild(aedit)
                            tdDelete.appendChild(adelete)
                            if (tag != 'com'){
                                row.appendChild(tdDate)
                                row.appendChild(tdWko)
                                row.appendChild(tdAvn)
                                row.appendChild(tdPdesc)
                                row.appendChild(tdQuantityR)
                                row.appendChild(tdSerials)
                                row.appendChild(tdWkoS)
                                row.appendChild(tdWkoD)
                                row.appendChild(tdWkoN)
                                row.appendChild(tdComp)
                                row.appendChild(tdSplit)
                                row.appendChild(tdEdit)
                                row.appendChild(tdDelete)
                            } else {
                                row.appendChild(tdDate)
                                row.appendChild(tdWko)
                                row.appendChild(tdAvn)
                                row.appendChild(tdPdesc) 
                                row.appendChild(tdQuantityB)
                                row.appendChild(tdSerials)
                                row.appendChild(tdWkoD)
                                row.appendChild(tdWkoN)
                            }
                            

                            Tbody.appendChild(row)

                            switch(page) {
                                case 0: 
                                    currentStart = '1'
                                    currentEnd = '10'
                                    break;
                                case '0': 
                                    currentStart = '1'
                                    currentEnd = '10'
                                    break;
                                default:
                                    currentStart = (page*10) + 1
                                    currentEnd = (currentStart-1) + length
                                    break;
                            }
                            // console.log('page value is: '+page)
                            if (tag === 'com') {
                                pageNumberInfoB.innerHTML = 'Showing <span class="font-medium">'+currentStart+'</span> to <span class="font-medium">'+currentEnd+'</span> of <span class="font-medium">'+totalCResults+'</span> results'
                            } else {
                                pageNumberInfoA.innerHTML = 'Showing <span class="font-medium">'+currentStart+'</span> to <span class="font-medium">'+currentEnd+'</span> of <span class="font-medium">'+totalAResults+'</span> results'
                            }
                            

                        }
                    })
            })
        }

    }; 

    // const addWkoBtn = document.querySelector(".addWkoBtn")
    // const addWkoDialog = document.querySelector("#addWko")
    // if(addWkoBtn) { 
    //     addWkoBtn.addEventListener("click", (e) => {
    //         // window.open('http://localhost/SAM/workorders/add')
    //         // addWkoDialog.classList.remove("hidden")
    //         // addWkoDialog.classList.add("block")  
    //    })
    // }

    const tNoteRows = document.querySelectorAll(".worow");
    let i;

    if(tNoteRows){
        for (i=0; i < tNoteRows.length; i++ ) {
            tNoteRows[i].addEventListener("click", (e) => {
                const woid = e.target.parentElement.dataset.id
                if(!!woid) {
                    window.location.href = "http://localhost/SAM/workorders/viewwo/"+woid
                }
            })
        }
    }   

    const compBtns = document.querySelectorAll(".mrk-as-comp")
    
    let serials
    let rowQty
    if(compBtns) {
        for (let l = 0; l < compBtns.length; l++) {
            compBtns[l].addEventListener("click" ,(e) => {
                const woid = e.target.parentElement.parentElement.parentElement.parentElement.dataset.id
                const serialStatus = e.target.parentElement.parentElement.parentElement.parentElement.children[5].textContent
                console.log(serialStatus)
                if (serialStatus === "To Be Confirmed") {
                    let m
                    let outputSerialRanges = []
                    //get expected quantity
                    rowQty = e.target.parentElement.parentElement.parentElement.parentElement.children[4].textContent
                    console.log('expected quantity: '+rowQty)
                    let inputSerialRanges = window.prompt("Please enter the serials for this work order to mark it as complete","").split(",")
                    console.log("input serial range length: "+inputSerialRanges.length)
                    if(inputSerialRanges = 'Sent without serials'){
                        //add note to wko
                    } else {
                        if(inputSerialRanges.length == 1 && rowQty == 1) {
                            //only one serial for this order
                            // window.alert("nice one!")
                            window.location.href = "http://localhost/SAM/workorders/complete/"+woid+"/"+inputSerialRanges
                        } else {
                            //check range(s) and compare qty to expected
                            console.log('input serial range(s): '+inputSerialRanges)
                            for (m = 0; m < inputSerialRanges.length; m++) {
                                console.log('serial number range: '+inputSerialRanges[m])
                                let numbers = inputSerialRanges[m].split("-")
                                let low = Number(numbers[0].trim())
                                let high = Number(numbers[1].trim())
                                console.log('low: '+low)
                                console.log('high: '+high)
                                for (let n = low; n <= high; n++) {
                                    console.log('number: '+n)
                                    outputSerialRanges.push(n)
                                    //need to save the numbers to an array
                                }
                                if(outputSerialRanges.length != rowQty) {
                                    window.alert("Incorrect number of serials for this WKO!")
                                } else {
                                    window.location.href = "http://localhost/SAM/workorders/complete/"+woid+"/"+inputSerialRanges 
                                }
                            }
                        }
                    }
                } else {
                    window.location.href = "http://localhost/SAM/workorders/complete/"+woid
                }
                
            })
        }
    }

    const splitBtns = document.querySelectorAll(".split-order");
    let splitPoint
    let j;
    if(splitBtns){
        for (j=0; j < splitBtns.length; j++) {
            splitBtns[j].addEventListener("click", (e) => {
                const woid = e.target.parentElement.parentElement.parentElement.parentElement.dataset.id
                const quantity = e.target.parentElement.parentElement.dataset.qty
                splitPoint = Number(window.prompt("After how many cabinets should the WKO be split?",""))
                if(splitPoint > quantity-1 | splitPoint === 0| isNaN(splitPoint)) {
                    window.alert("Error: Can't split from this point.")
                } else {
                    window.location.href = "http://localhost/SAM/workorders/split/"+woid+"/"+splitPoint
                }
                
            })
        }
    }  

    const dltBtns = document.querySelectorAll(".dltBtn");
    let k;
    if(dltBtns){
        for (k=0; k < dltBtns.length; k++) {
            dltBtns[k].addEventListener("click", (e) => {
                const woid = e.target.parentElement.parentElement.parentElement.dataset.id
                if(confirm("Are you sure you want to delete this workorder?") == true) {
                    window.location.href = "http://localhost/SAM/workorders/delete/"+woid
                    // console.log(e.target.parentElement.parentElement.parentElement)
                    console.log(woid)
                    console.log('delete button clicked');
                }
            })
        }
    }
}
export { initWorkOrders }