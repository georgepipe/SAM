function pagination() {

    // function initPagination() {

        // console.log('this is working!');
        //pagination code
        //setup
        let page = 0;
        let localPage = page;
        let tag;
        let localTag = tag;
        const PAGE_SIZE = 10;
        
        // for the future:
        //const state = {
        //active: { page: 0 },
        //  com: { page: 0 }
        //};
        
        const tableA = document.querySelector(".activeWkos");
        const tableB = document.querySelector(".compWkos");

        if(tableA || tableB) {
            // let pgBtns = document.querySelectorAll(".pgBtn");
            const paginationContainers = document.querySelectorAll('.aPgBtns, .cPgBtns');
            const pageNumberInfoA = document.querySelector(".pageInfoA");
            const pageNumberInfoB = document.querySelector(".pageInfoB");
            const totalAResults = +document.querySelector(".Acount").dataset.count;
            const aPageMax = Math.ceil(totalAResults / PAGE_SIZE);
            const totalCResults = +document.querySelector(".Ccount").dataset.count;
            const cPageMax = Math.ceil(totalCResults / PAGE_SIZE);
            const urlParams = new URLSearchParams(window.location.search)
            if(urlParams.get("p") === "cw") localTag = "com";

            //event binding
            paginationContainers.forEach(container => {
                container.addEventListener("click", handlePaginationClick);
            })

            //click handler
            async function handlePaginationClick(e) {
                
                const clickedButton = e.target.closest('.pgBtn');
                if(!clickedButton) return;
                
                e.preventDefault(); //stop default link behaviour
                if(clickedButton.classList.contains("pgBtnArrow")){ //was the button click an arrow?
                    let delta = Number(clickedButton.dataset.delta);
                    localPage = calculateNextPage(localPage, delta, localTag);
                } else {
                    localPage = Number(clickedButton.dataset.page);
                    localTag = clickedButton.closest('[data-wkos]').dataset.wkos;
                };


                const endpoint = localTag === "com" 
                    ? `../apiworkorders/paginate/completed/${localPage}`
                    : `../apiworkorders/paginate/active/${localPage}`;


                let data;
                try {
                    data = await fetchWorkorders(endpoint);
                } catch (err) {
                   console.error("Pagination fetch failed: ",err); 
                   return;
                };

                renderTable(data, localTag);
                updatePaginationInfo(localPage, data.length, localTag);
                updatePaginationUX(localPage, localTag);
            }

            function calculateNextPage(localPage, delta, tag){
                localPage = localPage;
                let newPage = localPage + delta;
                return validatePageBounds(newPage, tag) 
                    ? newPage 
                    : localPage;
            }

            function validatePageBounds(localPage, tag) {
                const max = tag == 'com' ? cPageMax-1 : aPageMax-1;
                return localPage >= 0 && localPage <= max;
            }

            //fetch data logic
            async function fetchWorkorders(url) {
                const response = await fetch(url, {
                    headers: {"Content-Type": "application/json",}
                });
                if(!response.ok) {
                    throw new Error(`HTTP error ${response.status}`);
                }
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
                if(localTag === 'com') {
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

                    avnLink.addEventListener('click', () =>{
                        // e.preventDefault();
                        window.open(
                            url,
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
                if(localTag !== 'com'){
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
                if(localTag !== 'com') {
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
                const start = page * PAGE_SIZE+1;
                const end = start + length-1;
                const total = tag === 'com' ? totalCResults : totalAResults;
                const el = tag === 'com' ? pageNumberInfoB : pageNumberInfoA;
                //this could be refactored to not use innerHTML
                el.innerHTML = `
                Showing <span class="font-medium"> ${start} </span> 
                to <span class="font-medium">${end}</span> 
                of <span class="font-medium">${total}</span> results
                `;
            }

            function updatePaginationUX(page, tag) {
                //update page buttons to reflect currently selected page
                // let qSelector = tag === "com" ? ".cPgBtn" : ".aPgBtn";
                // const pageBtns = document.querySelectorAll(qSelector);
                // pageBtns.forEach(button => {
                //     if(button.classList.contains("selPgBtn")) button.classList.remove("selPgBtn"); //remove the current page class from previous page button
                //     if(Number(button.dataset.page) === Number(page)) { //if the dataset 'page' matches the selected page
                //         button.classList.add("selPgBtn"); //add the current page class to the button
                //     } 
                // })

                //build new pagination array
                // let newPagination;
                // newPagination = Pagination.build(page, totalCResults);
                // renderPagination(newPagination);

                //render new pagination buttons based on output of pagination builder
                let qSelector = tag === "com" ? ".cPgBtns" : ".aPgBtns";
                const pageBtns = document.querySelector(qSelector);
                let totalPages = tag === 'com' ? Math.ceil(totalCResults/PAGE_SIZE) : Math.ceil(totalAResults/PAGE_SIZE);
                pageBtns.innerHTML = Pagination.render(Pagination.build(localPage, totalPages), tag);
            }

            const Pagination = {

                build(page, totalPages) {
                    //always include 1st 2 and last 2 pages 
                    //set current page
                    //insert ellipses where numbers are not sequential
                    let pages = [];
                    let currentPage = page + 1;
                    if(totalPages <= 7) {
                        for(let i = 1; i <= totalPages; i++) {
                            const isCurrent = i === currentPage;
                            pages.push({
                                type: 'page',
                                page: i,
                                current: isCurrent
                            });
                        }
                    } else {
                        //first 2 pages, selected page and one page either side and last two pages
                        pages.push({
                            type: 'page',
                            page: 1,
                            current: currentPage === 1 
                        });
                        pages.push({
                            type: 'page',
                            page: 2,
                            current: page === 2
                        });
                        if(currentPage > 1 && currentPage < totalPages)
                        {
                            pages.push({
                                type: 'page',
                                page: currentPage-1,
                                current: false
                            }); 
                            pages.push({
                                type: 'page',
                                page: currentPage,
                                current: true
                            });
                            pages.push({
                                type: 'page',
                                page: currentPage+1,
                                current: false
                            });
                        }

                        pages.push({
                            type: 'page',
                            page: totalPages-1,
                            current: (currentPage === totalPages-1) ? true: false
                        });
                        pages.push({
                            type: 'page',
                            page: totalPages,
                            current: (currentPage === totalPages) ? true: false
                        });
                    };
                    //filter pages for any duplicates
                    const uniquePages = [
                        ...new Map(
                            pages.map(item => [item.page, item])
                        ).values()
                    ];
                    //add elipsis where there are non sequential numbers
                    let previousPage = 0;
                    let finalPages = [];
                    uniquePages.forEach(page => {
                        if(previousPage === 0) {
                            finalPages.push(page);
                            previousPage = page.page;
                            return;
                        } else {
                            if(previousPage !== page.page-1){
                                finalPages.push({
                                    type: 'ellipsis'
                                });
                            }
                            finalPages.push(page);
                            previousPage = page.page;
                        }
                    });

                    return finalPages;
                },

                render(paginationArray, tag) {
                    //iterate through array and render buttons
                    let html = '';
                    let pgClass = tag === 'com' ? 'cPgBtn' : 'aPgBtn';
                    html = `<a href="#" class="pgBtn pgBtnArrow rounded-l-md hover:bg-gray-50 focus:z-20 focus:outline-offset-0" data-delta="-1">${ICONS.backArrow}<span class="sr-only">Previous</span></a>`;
                    for (const item of paginationArray) {
                        let current = (item.current) ? 'selPgBtn' : '';
                        if (item.type === 'page') {
                            //need to add either aPgBtns or cPgBtns depending on workorder table
                            html += `<a href="#" class="pgBtn ${pgClass} pgBtnNum hover:bg-gray-50 focus:z-20 focus:outline-offset-0 ${current}" data-page="${item.page-1}">${item.page}</a>`
                        }
                        if(item.type === 'ellipsis') {
                            html += `<span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span>`
                        }
                    }
                    html += `<a href="#" class="pgBtn pgBtnArrow rounded-r-md hover:bg-gray-50 focus:z-20 focus:outline-offset-0" data-delta="1">${ICONS.forwardArrow}<span class="sr-only">Next</span></a>`;
                    // console.log(html);
                    return html;
                }
            }


            const ICONS = {
                split : '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path class="split-order" stroke-linecap="round" stroke-linejoin="round" d="m7.848 8.25 1.536.887M7.848 8.25a3 3 0 1 1-5.196-3 3 3 0 0 1 5.196 3Zm1.536.887a2.165 2.165 0 0 1 1.083 1.839c.005.351.054.695.14 1.024M9.384 9.137l2.077 1.199M7.848 15.75l1.536-.887m-1.536.887a3 3 0 1 1-5.196 3 3 3 0 0 1 5.196-3Zm1.536-.887a2.165 2.165 0 0 0 1.083-1.838c.005-.352.054-.695.14-1.025m-1.223 2.863 2.077-1.199m0-3.328a4.323 4.323 0 0 1 2.068-1.379l5.325-1.628a4.5 4.5 0 0 1 2.48-.044l.803.215-7.794 4.5m-2.882-1.664A4.33 4.33 0 0 0 10.607 12m3.736 0 7.794 4.5-.802.215a4.5 4.5 0 0 1-2.48-.043l-5.326-1.629a4.324 4.324 0 0 1-2.068-1.379M14.343 12l-2.882 1.664" /></svg>',
                complete: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path class="mrk-as-comp" stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>',
                edit: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path class="edit-order edit" stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" /></svg>',
                delete: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path class="dlt-order delete" stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>',
                forwardArrow: '<svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" /></svg>',
                backArrow: '<svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon"><path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" /></svg>'
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
    // }
}

export { pagination };