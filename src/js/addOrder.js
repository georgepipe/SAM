function initAddOrder () {
    const ce = document.querySelector(".cabSel");
    const ccDiv = document.querySelector(".ccDiv");
    const gDiv = document.querySelector(".gDiv");
    const wDiv = document.querySelector(".wDiv");
    const whDiv = document.querySelector(".whDiv");

    console.log('start of innAddOrder JS');
    // if(ce) {
    //     console.log('ce exists, checking state');
    //     checkSelectors(ce.value); 
    //     }
    console.log(ce)
    if(ce) { 
        ce.addEventListener("change", (e) => {
            var cText = 'text innit';
            var cValue = ce.selectedIndex;
            console.log(cText);
            console.log(cValue);
            checkSelectors(cValue, cText);
       
        })
    }

//this needs refactoring to dynamically check database values rather than checking against static values
                        //!! NOT FUTURE FRIENDLY!!

    function checkSelectors(cValue, cText) {
        console.log('checking grille')
        console.log(cValue);
        switch (cValue) { //grille selector toggle
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
            case 10:
            case 11:
            case 12:
            case 13:
            case 13:
            case 14:
            case 15:
            case 16:
            case 20:
            case 21:
            case 22:
            case 23:
            case 24:
            case 25:
            case 26:
            case 27:
            case 28:
            case 29:
            case 30:
            case 31:
            case 32:
            case 33:
            case 34:
            case 35:
            case 36:
            case 37:
            case 38:
            case 39:
            case 43:
            case 44:
            case 45:
            case 46:
            case 52:
            case 53:
                console.log('show grille');
                gDiv.classList.remove('hidden');
                gDiv.classList.add('form-group');
                break;
            default:
                console.log('hide grille');
                gDiv.classList.remove('form-group');
                gDiv.classList.add('hidden');
                gDiv.value = "";
                break;
        }
        console.log('checking WG')
        switch (cValue) { //waveguide selector toggle
            case 38:
            case 39:
            case 40:
            case 41:
            case 42:
            case 43:
            case 44:
            case 45:
            case 46:
            case 47:
            case 48:
            case 49:
            case 50:
            case 52:
            case 53:
            case 54:
            case 55:
            case 56:
            case 57:
            case 59:
                console.log('show Waveguide');
                wDiv.classList.remove('hidden');
                wDiv.classList.add('form-group');
                break;
            default:
                console.log('hide waveguide');
                wDiv.classList.remove('form-group');
                wDiv.classList.add('hidden');
                wDiv.value = "";
                break;
        }
        console.log('checking cab colour')
        switch (cValue) { //waveguide selector toggle
            case 42: //R2sh
            case 48: //E6sh
            case 56: //E7sh
            case 59: //E2sh
                console.log('hide cab colour');
                ccDiv.classList.add('hidden');
                ccDiv.classList.remove('form-group');
                break;
            default:
                console.log('show cab colour');
                ccDiv.classList.add('form-group');
                ccDiv.classList.remove('hidden');
                ccDiv.value = "";
                break;
        }
        console.log('checking wheel checkbox')
        switch (cValue) { //wheel checkbox toggle
            case 24:
            case 25:
            case 26:
            case 27:
            case 28:
            case 29:
            case 30:
            case 31:
            case 32:
                console.log('show wheel checkbox');
                whDiv.classList.remove('hidden');
                whDiv.classList.add('form-group');
                break;
            default:
                console.log('hide wheel checkbox');
                whDiv.classList.remove('form-group');
                whDiv.classList.add('hidden');
                whDiv.value = "";
                break;
        }
    }
}
    export {initAddOrder}