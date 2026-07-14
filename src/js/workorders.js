function initWorkOrders () {

    //GLOBALS
    const URLROOT = 'http://localhost/SAM/'
    let page = 0;
    let delta = 0;
    let tag;
    



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
function handleSerials() { 

    async function setSerials(payload) {
        const response = await fetch(`${URLROOT}apiworkorders/setSerials`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        if(!response.ok) {
            throw new Error(`HTTP error ${response.status}`);
        }
        return await response.json()
    }

    document.addEventListener('click', async function (e) {
        //PARSE USER INPUT
        //VALIDATE SERIALS
        //SUBMIT
        /**
         * input
         * split ranges
         * validate syntax
         * expand ranged
         * deduplicate
         * validatee quantity
         * post to backend
         * (re-validate at backend
         * save serials)
         * mark as complete
         */

        if(!e.target.closest('.compBtn')) return; 
        const row = e.target.closest('tr');

        //check whether workorder has serials or not
        const serials = row.querySelector('.wkoSerials').textContent;
        
        if(serials === 'To Be Confirmed'){

            //handle input
            let input = window.prompt("Please enter the serials for this work order to mark it as complete","");
            if(!input) return; //check for blank input

            input = input.replace(/\s+/g,''); //remove any spaces
            input = input.replace(/\//g, ','); //swap forward slashes for commas
            
            function getNumbersFromRange(range) {

                //validate range inputs:
                validateRange(range);
                if(range.indexOf('-') === -1) return [Number(range)];
                //get start and end of the range
                let splitRange = range.split('-');
                let first = Number(splitRange[0]);
                let second = Number(splitRange[1]);

                //create array of sequential numbers
                let rangeNumbers = [];
                for (let n = first; n <= second; n++) {
                    rangeNumbers.push(n);
                };
                return rangeNumbers;
            }

            function validateRange(range) {
                //detect invalid inputs
                /**
                 * check for:
                 * NaN
                 * multiple hyphens
                 * missing first
                 * missing second
                 * reverse range
                 */
                // console.log(`range: ${range}`)
                if(range.indexOf('-') === -1) {
                    //single serial validation required
                    if(isNaN(range)) throw new Error('Invalid input: range may only contain numbers!');
                    console.log(`returning range ${range}`)
                    return [Number(range)];
                }

                let explodedRange = range.split('-');
                if(explodedRange.length > 2) {throw new Error('Malformed input: only use one hyphen per range.')};

                explodedRange.forEach(input => {
                    if(Number(input) === 0 || input === null || input === '') { //check for missing input or 0
                        throw new Error('Malformed input: range missing number. Serials cannot be 0');
                    }
                    if(isNaN(input)) { //check for non-numbers
                        throw new Error('Invalid input: range may only contain numbers!')
                    }
                })
                let intRange = explodedRange.map(Number);

                if (intRange[0] > intRange[1]) {
                    throw new Error('Inverted serial range detected!'); 
                }
                //VALIDATION PASS
                return;
            }

            function checkForDuplicates(serials) {
                const duplicates = serials.filter((item, index) => serials.indexOf(item) !== index);
                console.log(`duplicates: ${duplicates}`)
                if(duplicates.length > 0) throw new Error(`Duplicate serials detected!`);
            }

            function validateQty(numbers) {
                let expectedQty = Number(row.querySelector('#qty').dataset.qty);
                console.log(expectedQty);
                const actualQty = numbers.length ?? 1;

                if (expectedQty === actualQty) {
                    //VALIDATION PASSED
                    return;
                } else {
                    throw new Error(`Number of serials provided does not match the required quantity of serials. Provided: ${actualQty} Expected: ${expectedQty}`);
                }
            };

            //split input into ranges
            let inputRanges;
            let rangeNumbers = [];
            let numbers;
            try {
                if(input.indexOf(',') > -1) {
                    //split ranges and count up all the serials in the range
                
                    inputRanges = input.split(','); //split input into ranges
                    for(const range of inputRanges) { //create array of sequential numbers from ranges one by one
                        numbers = getNumbersFromRange(range);
                        rangeNumbers.push(numbers);
                    }

                    let spreadNumbers = rangeNumbers.flat(); //spread the arrays into one long array
                    checkForDuplicates(spreadNumbers); 
                    console.log(`spread range: ${spreadNumbers}`);
                    numbers = spreadNumbers;
                } else { //only one range!
                    console.log(`input: ${input}`)
                    numbers = getNumbersFromRange(input);
                    console.log(numbers);
                };
                //check quantity of serials against expected quantity
                validateQty(numbers);
                //send to the backend API!
                const workorder_id = row.dataset.id;

                console.log('fetching!');
                let data;
                const payload = {
                    workorder_id,
                    numbers
                };

                try {
                    data = await setSerials(payload);
                } catch (error) {
                    console.error();
                    return;
                };

                console.log(data);
                if(!data.success) {
                    throw new Error(data.message);
                };
               
                if(data.success){
                    window.location.href = `http://localhost/SAM/workorders`
                }

            } catch (error) {
                console.log(error);
                alert(error.message);
                return;
            }  
        } else {
            window.location.href = `http://localhost/SAM/workorders/complete/${row.dataset.id}`
        }
    });
}

    

//event handler for splitting work orders when they are part complete by clicking the 'scissor' SVG
//NEEEEEEEEDs REFACTORING!!!!!!!!!!!!!!!!!!
    const splitBtns = document.querySelectorAll(".split-order");
    let splitPoint
    let j;
    if(splitBtns){
        for (j=0; j < splitBtns.length; j++) {
            splitBtns[j].addEventListener("click", (e) => {
                const woid = e.target.closest(".worow").dataset.id;
                const quantity = e.target.closest("#qty").dataset.qty;

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
                if(row) woid = row.dataset.id;
                if(e.target.closest('.dltBtn')){
                    if(confirm("Are you sure you want to delete this workorder?")) {
                        window.location.href = `${URLROOT}workorders/delete/${woid}`;
                    };
                };
            })
        }
    }

    initDelete();
    handleSerials();
}   



export { initWorkOrders }