function initAddOrder () {
    const ce = document.querySelector(".cabSel");
    const ge = document.querySelector(".grilleSel");
    const we = document.querySelector(".waveguideSel");
    if(ce) {checkSelectors(ce.value); console.log('ce exists')}
    if(ce) { 
        ce.addEventListener("click", (e) => {
            var cValue = ce.value;
            var cText = ce.options[ce.selectedIndex].text;
            console.log(cText);
            console.log(cValue);
            checkSelectors(cValue, cText);
       
        })
    }

    if(we) { }

//this needs refactoring to dynamically check database values rather than checking against static values
                        //!! NOT FUTURE FRIENDLY!!

    function checkSelectors(cValue, cText) {
        console.log('checking grille')
        switch (cValue) { //grille selector toggle
            case "1":
            case "2":
            case "3":
            case "4":
            case "5":
            case "6":
            case "7":
            case "8":
            case "9":
            case "10":
            case "11":
            case "12":
            case "13":
            case "13":
            case "14":
            case "15":
            case "16":
            case "20":
            case "21":
            case "22":
            case "23":
            case "24":
            case "25":
            case "26":
            case "27":
            case "28":
            case "29":
            case "30":
            case "31":
            case "32":
            case "33":
            case "34":
            case "35":
            case "36":
            case "37":
            case "38":
            case "39":
            case "43":
            case "44":
            case "45":
            case "46":
            case "52":
            case "53":
                ge.classList.remove('hidden');
                ge.classList.add('block');
                break;
            default:
                ge.classList.remove('block');
                ge.classList.add('hidden');
                ge.value = "";
                break;
        }
        console.log('checking WG')
        switch (cValue) { //waveguide selector toggle
            case "38":
            case "39":
            case "40":
            case "41":
            case "42":
            case "43":
            case "44":
            case "45":
            case "46":
            case "47":
            case "48":
            case "49":
            case "50":
            case "52":
            case "53":
            case "54":
            case "55":
            case "56":
            case "57":
            case "59":
                we.classList.remove('hidden');
                we.classList.add('block');
                break;
            default:
                we.classList.remove('block');
                we.classList.add('hidden');
                we.value = "";
                break;
        }
    }
}
    export {initAddOrder}