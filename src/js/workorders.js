function initWorkOrders () {

    //GLOBALS
    const URLROOT = 'http://localhost/SAM/'
    let page = 0;
    let delta = 0;
    function initPagination() {
    //pagination code
        //setup
        const tableA = document.querySelector(".activeWkos");
        const tableB = document.querySelector(".compWkos");

        if(tableA || tableB) {
            const pgBtns = document.querySelectorAll(".pgBtn");
            const pageNumberInfoA = document.querySelector(".pageInfoA");
            const pageNumberInfoB = document.querySelector(".pageInfoB");
            const totalAResults = +document.querySelector(".Acount").dataset.count;
            const aPageMax = Math.ceil(totalAResults / 10);
            const totalCResults = +document.querySelector(".Ccount").dataset.count;
            const cPageMax = Math.ceil(totalCResults / 10);
            const urlParams = new URLSearchParams(window.location.search)
            let tag;
            if(urlParams.get("p") === "cw") tag = "com";

            //event binding
            pgBtns.forEach(btn => {
                btn.addEventListener("click", handlePaginationClick);
            })

            //click handler
            async function handlePaginationClick(e) {
                e.preventDefault(); //stop default link behaviour

                if(e.target.closest(".pgBtnArrow")){ //was the button click an arrow?
                    let arrowBtn = e.target.closest(".pgBtnArrow");
                    delta = Number(arrowBtn.dataset.delta);
                    page = calculateNextPage(page, delta, tag);
                } else {
                    page = +e.target.dataset.page;
                    tag = e.target.parentElement.dataset.wkos;
                };



                const endpoint = tag === "com" 
                    ? `../apiworkorders/paginate/completed/${page}`
                    : `../apiworkorders/paginate/active/${page}`;

                const data = await fetchWorkorders(endpoint);

                renderTable(data, tag);
                updatePaginationInfo(page, data.length, tag);
                updatePaginationUX(page, tag);
            }

            function calculateNextPage(page, delta, tag){
                let newPage = page + delta;
                return validatePageBounds(newPage, tag) 
                    ? newPage 
                    : page;
            }

            function validatePageBounds(page, tag) {
                if (tag === 'com') {
                    if(page >= 0 && page <= cPageMax-1) return true;
                } else {
                    if(page >= 0 && page <= aPageMax-1) return true;
                }
                return false;
            }

            //fetch logic
            async function fetchWorkorders(url) {
                const response = await fetch(url, {
                    headers: {"Content-Type": "application/json",}
                });
                return response.json();
            }

            //render table
            function renderTable(data, tag) {
                const Tbody = document.querySelector(
                    tag === "com" ? "#tbodyCwo" : "#tbodyAwo"
                );

                Tbody.innerHTML = "";

                data.forEach(item => {
                    const row = buildRow(item, tag);
                    Tbody.appendChild(row);
                });
            }

            //row builder function
            function buildRow(item, tag) {
                const statusMap = {
                    'In Progress' : 'inprogress',
                    'To Be Built' : 'tobebuilt',
                    'On Hold' : 'onhold',
                    'Waiting For Parts' : 'waitingforparts',
                    'Upcoming' : 'upcoming',
                    '' : ''
                };

                const row = document.createElement('tr');
                row.className = 'text-center border-4 worow';
                row.dataset.id = item.work_order_id;

                //data: workorder status
                const statusClass = statusMap[item.wko_status]
                if (statusClass) row.classList.add(statusClass);
                if(item.wko_status === "Completed") row.classList.add('completed');
                
                //row data helper
                const td = (text = '', classNames = [], id = '', style = {}) => {
                    const cell = document.createElement('td');
                    if (classNames.length) cell.classList.add(...classNames);
                    if (id) cell.id = id;
                    if (style && typeof style === 'object') Object.assign(cell.style, style);
                    cell.textContent = text ?? '';
                    return cell;
                };
                
                //data: date
                const formatDate = (date) => {return date ? date.slice(0,10) : 'N/A'};
                if(tag === 'com') {
                    row.appendChild(td(formatDate(item.completed_at),['text-nowrap']));
                } else {
                    row.appendChild(td(formatDate(item.created_at),['text-nowrap']));
                };
                row.appendChild(td(item.wko, ['text-nowrap']));

                //data: avn
                const avnRow = td();
                avnRow.className = 'text-blue-500 wkoAvn';
                const avnLink = document.createElement('a');
                if(item.avn){
                    const url = `/SAM/advice_notes/AVN_${String(item.avn).padStart(5, '0')}.pdf`;
                    avnLink.href = url;
                    avnLink.target = 'AVNwindow';

                    avnLink.addEventListener('click', () =>{
                        window.open(
                            `/SAM/advice_notes/AVN_${String(item.avn).padStart(5, '0')}.pdf`,
                            'AVNwindow', 
                            'width=400,height=600' 
                        );
                    });
                    avnLink.textContent = item.avn;
                } else {
                    avnLink.textContent = 'N/A'
                }
                avnRow.appendChild(avnLink);
                row.appendChild(avnRow);
                
                //data: pdesc,quantity,serials
                row.appendChild(td(item.pdesc, ['text-[10px]']));
                const qtyCell = td(item.quantity);
                qtyCell.id = 'qty';
                row.appendChild(qtyCell);
                row.appendChild(td(item.serials, ['text-xs', 'wkoSerials']));

                //data: workorder status
                if(tag !== 'com'){
                    const statusCell = td(item.wko_status);
                    statusCell.classList.add('wko-status-cell');
                    statusCell.dataset.wkoId = item.work_order_id;
                    statusCell.dataset.wkoStatus = item.wko_status;
                    row.appendChild(statusCell);
                }

                //data: delivery, notes
                row.appendChild(td(item.wko_delivery));
                row.appendChild(td(item.wko_notes,['min-w-24']));

                //links: svg buttons
                if(tag !== 'com') {
                    const tdSplit = document.createElement('td');
                    const tdEdit = document.createElement('td');
                    const tdComplete = document.createElement('td');
                    const tdDelete = document.createElement('td');
                    
                    tdComplete.appendChild(createActionLink(
                        `javascript:void(0)`,
                        ICONS.complete
                    ));
                    tdSplit.appendChild(createActionLink(
                        'javascript:void(0)',
                        ICONS.split
                    ));
                    tdEdit.appendChild(createActionLink(
                        'javascript:void(0)',
                        ICONS.edit
                    ));
                    tdDelete.appendChild(createActionLink(
                        'javascript:void(0)',
                        ICONS.delete
                    ));
                    row.append(tdComplete, tdSplit, tdEdit, tdDelete);
                }

                return row;
            }

            function updatePaginationInfo(page, length, tag) {
                const start = page * 10 + 1;
                const end = start + length -1;
                const total = tag === 'com' ? totalCResults : totalAResults;
                const el = tag === 'com' ? pageNumberInfoB : pageNumberInfoA;

                el.innerHTML = `
                Showing <span class="font-medium"> ${start} </span> 
                to <span class="font-medium">${end}</span> 
                of <span class="font-medium">${total}</span> results
                `;
            }

            function updatePaginationUX(page, tag) {
                //update page buttons to reflect currently selected page
                // maybe its better to search for the tag first and then choose the query selector based on that
                //so we only need to do this loop once
                if (tag==="com") {
                    const cPageBtns = document.querySelectorAll(".cPgBtn");
                    cPageBtns.forEach(button =>{
                        if(button.classList.contains("selPgBtn")) button.classList.remove("selPgBtn"); //remove the current page class from previous page button
                        if(Number(button.dataset.page) === Number(page)) { //if the dataset 'page' matches the selected page
                            button.classList.add("selPgBtn"); //add the current page class to the button
                        }
                    })
                }
            }

            const ICONS = {
                split : '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path class="split-order" stroke-linecap="round" stroke-linejoin="round" d="m7.848 8.25 1.536.887M7.848 8.25a3 3 0 1 1-5.196-3 3 3 0 0 1 5.196 3Zm1.536.887a2.165 2.165 0 0 1 1.083 1.839c.005.351.054.695.14 1.024M9.384 9.137l2.077 1.199M7.848 15.75l1.536-.887m-1.536.887a3 3 0 1 1-5.196 3 3 3 0 0 1 5.196-3Zm1.536-.887a2.165 2.165 0 0 0 1.083-1.838c.005-.352.054-.695.14-1.025m-1.223 2.863 2.077-1.199m0-3.328a4.323 4.323 0 0 1 2.068-1.379l5.325-1.628a4.5 4.5 0 0 1 2.48-.044l.803.215-7.794 4.5m-2.882-1.664A4.33 4.33 0 0 0 10.607 12m3.736 0 7.794 4.5-.802.215a4.5 4.5 0 0 1-2.48-.043l-5.326-1.629a4.324 4.324 0 0 1-2.068-1.379M14.343 12l-2.882 1.664" /></svg>',
                complete: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path class="mrk-as-comp" stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg',
                edit: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path class="edit-order edit" stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" /></svg>',
                delete: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path class="dlt-order delete" stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>'
            };

            function createActionLink(href, svg, extraAttrs={}) {
                const a = document.createElement('a');
                a.href = href;
                Object.entries(extraAttrs).forEach(([key, value]) => {
                    a.setAttribute(key,value);
                });
                a.innerHTML = svg;
                return a;
            }
        }
    }

//event handler to open 'viewWO' pages by clicking work order rows on WO index page
    document.addEventListener('click', function (e) {
        if(e.target.closest('.wko-status-cell')) return; //stop firing if we're trying to change the wko status
        if(e.target.closest('dltBtn')) return;

        const row = e.target.closest('.worow');
        if(!row) return;
        if(e.target.closest('a')) return;
        
        const woid = row.dataset.id;
        if(!woid) return;
       
        window.location.href = `${URLROOT}workorders/viewwo/${woid}`;
    });


//event handler for marking workorders as complete when clicking the 'tick' SVG
    document.addEventListener('click', function (e) {

        if(!e.target.closest('.compBtn')) return;
        const row = e.target.closest('.worow'); 
        let serials;
        let woid;
        let high;
        let low;
        // console.log(row);return;
        if(row) {
            woid = row.dataset.id
            serials = row.querySelector('.wkoSerials').textContent;
        };
        if(serials === 'To Be Confirmed') {
            let m;
            let outputSerialRanges = [];
            //get expected quantity
            const rowQty = +row.querySelector('#qty').textContent;
            let inputSerialRanges = window.prompt("Please enter the serials for this work order to mark it as complete","");
            if(inputSerialRanges) {
                inputSerialRanges = inputSerialRanges.split(",")
            } else return;
            if(inputSerialRanges[0] === 'Sent without serials'){
                //add note to wko without serials
            }
            //check range(s) and compare qty to expected
            for (m = 0; m < inputSerialRanges.length; m++) {
                let numbers = inputSerialRanges[m].split("-");
                low = Number(numbers[0].trim());
                if(numbers[1]){
                    high = Number(numbers[1].trim())
                    for (let n = low; n <= high; n++) {
                        outputSerialRanges.push(n);
                    };
                };
                let totalSerials = high ? high - low: 1;
                if(totalSerials == 1) {
                    if(rowQty === 1){
                        window.location.href = `${URLROOT}workorders/complete/${woid}/${inputSerialRanges[0]}`;
                    };
                };
                if(outputSerialRanges.length != rowQty) {
                    console.log(outputSerialRanges, outputSerialRanges.length, rowQty);
                    window.alert(`Incorrect number of serials for this workorder! ${rowQty} are required and ${totalSerials} have been supplied.`); 
                    return;
                } else {
                    window.location.href = `${URLROOT}Sworkorders/complete/${woid}/${inputSerialRanges}`;
                };
            }
        } else {
            window.location.href = `${URLROOT}workorders/complete/${woid}/${serials.replace(/\s+/g, '')}`;
        };
    });
    // const compBtns = document.querySelectorAll(".mrk-as-comp")
    // let serials
    // let rowQty
    // if(compBtns) {
    //     for (let l = 0; l < compBtns.length; l++) {
    //         compBtns[l].addEventListener("click" ,(e) => {
    //             const woid = e.target.parentElement.parentElement.parentElement.parentElement.dataset.id
    //             const serialStatus = e.target.parentElement.parentElement.parentElement.parentElement.children[5].textContent
    //             console.log(serialStatus)
    //             if (serialStatus === "To Be Confirmed") {
    //                 let m
    //                 let outputSerialRanges = []
    //                 //get expected quantity
    //                 rowQty = e.target.parentElement.parentElement.parentElement.parentElement.children[4].textContent
    //                 console.log('expected quantity: '+rowQty)
    //                 let inputSerialRanges = window.prompt("Please enter the serials for this work order to mark it as complete","").split(",")
    //                 console.log("input serial range length: "+inputSerialRanges.length)
    //                 if(inputSerialRanges = 'Sent without serials'){
    //                     //add note to wko
    //                 } else {
    //                     if(inputSerialRanges.length == 1 && rowQty == 1) {
    //                         //only one serial for this order
    //                         // window.alert("nice one!")
    //                         window.location.href = "http://localhost/SAM/workorders/complete/"+woid+"/"+inputSerialRanges
    //                     } else {
    //                         //check range(s) and compare qty to expected
    //                         console.log('input serial range(s): '+inputSerialRanges)
    //                         for (m = 0; m < inputSerialRanges.length; m++) {
    //                             console.log('serial number range: '+inputSerialRanges[m])
    //                             let numbers = inputSerialRanges[m].split("-")
    //                             let low = Number(numbers[0].trim())
    //                             let high = Number(numbers[1].trim())
    //                             console.log('low: '+low)
    //                             console.log('high: '+high)
    //                             for (let n = low; n <= high; n++) {
    //                                 console.log('number: '+n)
    //                                 outputSerialRanges.push(n)
    //                                 //need to save the numbers to an array
    //                             }
    //                             if(outputSerialRanges.length != rowQty) {
    //                                 window.alert("Incorrect number of serials for this WKO!")
    //                             } else {
    //                                 window.location.href = "http://localhost/SAM/workorders/complete/"+woid+"/"+inputSerialRanges 
    //                             }
    //                         }
    //                     }
    //                 }
    //             } else {
    //                 window.location.href = "http://localhost/SAM/workorders/complete/"+woid
    //             }
                
    //         })
    //     }
    // }

//event handler for splitting work orders when they are part complete by clicking the 'scissor' SVG
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

    function initDelete() {
    //event handler for deleting workorders by clicking the 'bin' SVG
        const tableA = document.querySelector(".activeWkos");
        if(tableA) {
            document.addEventListener('click', function (e) {
                const row = e.target.closest('.worow');
                let woid; 
                if(row) row.dataset.id;
                if(e.target.closest('.dltBtn')){
                    if(confirm("Are you sure you want to delete this workorder?")) {
                        window.location.href = `${URLROOT}workorders/delete/${woid}`;
                    };
                };
            })
        }
    }

    initDelete();
    initPagination();
}



export { initWorkOrders }