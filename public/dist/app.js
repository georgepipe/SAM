/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/js/addOrder.js":
/*!****************************!*\
  !*** ./src/js/addOrder.js ***!
  \****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initAddOrder: () => (/* binding */ initAddOrder)
/* harmony export */ });
function initAddOrder() {
  var ce = document.querySelector(".cabSel");
  var ccDiv = document.querySelector(".ccDiv");
  var gDiv = document.querySelector(".gDiv");
  var wDiv = document.querySelector(".wDiv");
  var whDiv = document.querySelector(".whDiv");
  if (ce) {
    ce.addEventListener("change", function (e) {
      var cText = 'text innit';
      var cValue = ce.selectedIndex;
      checkSelectors(cValue, cText);
    });
  }

  //this needs refactoring to dynamically check database values rather than checking against static values
  //!! NOT FUTURE FRIENDLY!!

  function checkSelectors(cValue, cText) {
    switch (cValue) {
      //grille selector toggle
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
    console.log('checking WG');
    switch (cValue) {
      //waveguide selector toggle
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
        wDiv.classList.remove('hidden');
        wDiv.classList.add('form-group');
        break;
      default:
        wDiv.classList.remove('form-group');
        wDiv.classList.add('hidden');
        wDiv.value = "";
        break;
    }
    console.log('checking cab colour');
    switch (cValue) {
      //waveguide selector toggle
      case 42: //R2sh
      case 48: //E6sh
      case 56: //E7sh
      case 59:
        //E2sh
        ccDiv.classList.add('hidden');
        ccDiv.classList.remove('form-group');
        break;
      default:
        ccDiv.classList.add('form-group');
        ccDiv.classList.remove('hidden');
        ccDiv.value = "";
        break;
    }
    console.log('checking wheel checkbox');
    switch (cValue) {
      //wheel checkbox toggle
      case 24:
      case 25:
      case 26:
      case 27:
      case 28:
      case 29:
      case 30:
      case 31:
      case 32:
        whDiv.classList.remove('hidden');
        whDiv.classList.add('form-group');
        break;
      default:
        whDiv.classList.remove('form-group');
        whDiv.classList.add('hidden');
        whDiv.value = "";
        break;
    }
  }
}


/***/ }),

/***/ "./src/js/navigation.js":
/*!******************************!*\
  !*** ./src/js/navigation.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initNav: () => (/* binding */ initNav)
/* harmony export */ });
function initNav() {
  /**
   * TODO:
   * - Set nav button as a const
   * - Set nav list/el to be toggled as a const
   * - Add on click to button and pass btn & nav el
   */
  var navBtnEl = document.querySelector('[data-target-action="nav"]');
  if (navBtnEl) {
    var navListEl = document.querySelector('[type="menu"]');
    navBtnEl.addEventListener('click', function () {
      toggleNav(navBtnEl, navListEl);
    }, false);
  }
}
function toggleNav(btnEl, listEl) {
  /**
   * TODO:
   * Create a toggle nav function to show hide the navigation list.
   * - Should accept btn and nav el as parameters
   */
  if (listEl) {} else {
    console.warn('Naa BRUV! Toggle-nav list EL not found');
  }
  if (btnEl) {
    var svgClosed = btnEl.querySelector('.nav-closed');
    var svgOpen = btnEl.querySelector('.nav-open');
    if (svgClosed.classList.contains('block')) {
      svgClosed.classList.remove('block');
      svgClosed.classList.add('hidden');
      svgOpen.classList.remove('hidden');
      svgOpen.classList.add('block');
      listEl.classList.remove('hidden');
      listEl.classList.add('block');
    } else {
      svgClosed.classList.add('block');
      svgClosed.classList.remove('hidden');
      svgOpen.classList.add('hidden');
      svgOpen.classList.remove('block');
      listEl.classList.add('hidden');
      listEl.classList.remove('block');
    }
  }
}
document.addEventListener('keydown', function (event) {
  console.log("Key pressed: ".concat(event.key));
  var key = event.key;
  if (key === '†') {
    window.location.href = "http://localhost/SAM/tools";
  }
  ;
});


/***/ }),

/***/ "./src/js/statusUpdate.js":
/*!********************************!*\
  !*** ./src/js/statusUpdate.js ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   updateStatus: () => (/* binding */ updateStatus)
/* harmony export */ });
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return e; }; var t, e = {}, r = Object.prototype, n = r.hasOwnProperty, o = Object.defineProperty || function (t, e, r) { t[e] = r.value; }, i = "function" == typeof Symbol ? Symbol : {}, a = i.iterator || "@@iterator", c = i.asyncIterator || "@@asyncIterator", u = i.toStringTag || "@@toStringTag"; function define(t, e, r) { return Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }), t[e]; } try { define({}, ""); } catch (t) { define = function define(t, e, r) { return t[e] = r; }; } function wrap(t, e, r, n) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype), c = new Context(n || []); return o(a, "_invoke", { value: makeInvokeMethod(t, r, c) }), a; } function tryCatch(t, e, r) { try { return { type: "normal", arg: t.call(e, r) }; } catch (t) { return { type: "throw", arg: t }; } } e.wrap = wrap; var h = "suspendedStart", l = "suspendedYield", f = "executing", s = "completed", y = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var p = {}; define(p, a, function () { return this; }); var d = Object.getPrototypeOf, v = d && d(d(values([]))); v && v !== r && n.call(v, a) && (p = v); var g = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(p); function defineIteratorMethods(t) { ["next", "throw", "return"].forEach(function (e) { define(t, e, function (t) { return this._invoke(e, t); }); }); } function AsyncIterator(t, e) { function invoke(r, o, i, a) { var c = tryCatch(t[r], t, o); if ("throw" !== c.type) { var u = c.arg, h = u.value; return h && "object" == _typeof(h) && n.call(h, "__await") ? e.resolve(h.__await).then(function (t) { invoke("next", t, i, a); }, function (t) { invoke("throw", t, i, a); }) : e.resolve(h).then(function (t) { u.value = t, i(u); }, function (t) { return invoke("throw", t, i, a); }); } a(c.arg); } var r; o(this, "_invoke", { value: function value(t, n) { function callInvokeWithMethodAndArg() { return new e(function (e, r) { invoke(t, n, e, r); }); } return r = r ? r.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(e, r, n) { var o = h; return function (i, a) { if (o === f) throw Error("Generator is already running"); if (o === s) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var c = n.delegate; if (c) { var u = maybeInvokeDelegate(c, n); if (u) { if (u === y) continue; return u; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (o === h) throw o = s, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = f; var p = tryCatch(e, r, n); if ("normal" === p.type) { if (o = n.done ? s : l, p.arg === y) continue; return { value: p.arg, done: n.done }; } "throw" === p.type && (o = s, n.method = "throw", n.arg = p.arg); } }; } function maybeInvokeDelegate(e, r) { var n = r.method, o = e.iterator[n]; if (o === t) return r.delegate = null, "throw" === n && e.iterator["return"] && (r.method = "return", r.arg = t, maybeInvokeDelegate(e, r), "throw" === r.method) || "return" !== n && (r.method = "throw", r.arg = new TypeError("The iterator does not provide a '" + n + "' method")), y; var i = tryCatch(o, e.iterator, r.arg); if ("throw" === i.type) return r.method = "throw", r.arg = i.arg, r.delegate = null, y; var a = i.arg; return a ? a.done ? (r[e.resultName] = a.value, r.next = e.nextLoc, "return" !== r.method && (r.method = "next", r.arg = t), r.delegate = null, y) : a : (r.method = "throw", r.arg = new TypeError("iterator result is not an object"), r.delegate = null, y); } function pushTryEntry(t) { var e = { tryLoc: t[0] }; 1 in t && (e.catchLoc = t[1]), 2 in t && (e.finallyLoc = t[2], e.afterLoc = t[3]), this.tryEntries.push(e); } function resetTryEntry(t) { var e = t.completion || {}; e.type = "normal", delete e.arg, t.completion = e; } function Context(t) { this.tryEntries = [{ tryLoc: "root" }], t.forEach(pushTryEntry, this), this.reset(!0); } function values(e) { if (e || "" === e) { var r = e[a]; if (r) return r.call(e); if ("function" == typeof e.next) return e; if (!isNaN(e.length)) { var o = -1, i = function next() { for (; ++o < e.length;) if (n.call(e, o)) return next.value = e[o], next.done = !1, next; return next.value = t, next.done = !0, next; }; return i.next = i; } } throw new TypeError(_typeof(e) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, o(g, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), o(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, u, "GeneratorFunction"), e.isGeneratorFunction = function (t) { var e = "function" == typeof t && t.constructor; return !!e && (e === GeneratorFunction || "GeneratorFunction" === (e.displayName || e.name)); }, e.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, define(t, u, "GeneratorFunction")), t.prototype = Object.create(g), t; }, e.awrap = function (t) { return { __await: t }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, c, function () { return this; }), e.AsyncIterator = AsyncIterator, e.async = function (t, r, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(wrap(t, r, n, o), i); return e.isGeneratorFunction(r) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, defineIteratorMethods(g), define(g, u, "Generator"), define(g, a, function () { return this; }), define(g, "toString", function () { return "[object Generator]"; }), e.keys = function (t) { var e = Object(t), r = []; for (var n in e) r.push(n); return r.reverse(), function next() { for (; r.length;) { var t = r.pop(); if (t in e) return next.value = t, next.done = !1, next; } return next.done = !0, next; }; }, e.values = values, Context.prototype = { constructor: Context, reset: function reset(e) { if (this.prev = 0, this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(resetTryEntry), !e) for (var r in this) "t" === r.charAt(0) && n.call(this, r) && !isNaN(+r.slice(1)) && (this[r] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0].completion; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(e) { if (this.done) throw e; var r = this; function handle(n, o) { return a.type = "throw", a.arg = e, r.next = n, o && (r.method = "next", r.arg = t), !!o; } for (var o = this.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i.completion; if ("root" === i.tryLoc) return handle("end"); if (i.tryLoc <= this.prev) { var c = n.call(i, "catchLoc"), u = n.call(i, "finallyLoc"); if (c && u) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } else if (c) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); } else { if (!u) throw Error("try statement without catch or finally"); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } } } }, abrupt: function abrupt(t, e) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var o = this.tryEntries[r]; if (o.tryLoc <= this.prev && n.call(o, "finallyLoc") && this.prev < o.finallyLoc) { var i = o; break; } } i && ("break" === t || "continue" === t) && i.tryLoc <= e && e <= i.finallyLoc && (i = null); var a = i ? i.completion : {}; return a.type = t, a.arg = e, i ? (this.method = "next", this.next = i.finallyLoc, y) : this.complete(a); }, complete: function complete(t, e) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && e && (this.next = e), y; }, finish: function finish(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.finallyLoc === t) return this.complete(r.completion, r.afterLoc), resetTryEntry(r), y; } }, "catch": function _catch(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.tryLoc === t) { var n = r.completion; if ("throw" === n.type) { var o = n.arg; resetTryEntry(r); } return o; } } throw Error("illegal catch attempt"); }, delegateYield: function delegateYield(e, r, n) { return this.delegate = { iterator: values(e), resultName: r, nextLoc: n }, "next" === this.method && (this.arg = t), y; } }, e; }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
function updateStatus() {
  var WORKORDER_STATUSES = ['In Progress', 'On Hold', 'To Be Built', 'Upcoming'];

  /**
   * 
   * create event listener:click on status cell
   * set workorder id and status vars from DOM
   * create selectEL in DOM and assign class
   * populate select with statuses from above
   * set current status as selected
   * 
   * append child and focus on it
   * create function to restore selectEL to textEL
   * add event listener:change for select
   * on select: grab new status, send to API endpoint in workorder controller
   * set error handling
   */

  document.addEventListener('click', /*#__PURE__*/function () {
    var _ref = _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee2(e) {
      var cell, workorderId, workorderStatus, select, restoreText;
      return _regeneratorRuntime().wrap(function _callee2$(_context2) {
        while (1) switch (_context2.prev = _context2.next) {
          case 0:
            cell = e.target.closest('.wko-status-cell'); //<--- this sets the cell to be the closest wko status cell
            if (cell) {
              _context2.next = 3;
              break;
            }
            return _context2.abrupt("return");
          case 3:
            if (!cell.querySelector('select')) {
              _context2.next = 5;
              break;
            }
            return _context2.abrupt("return");
          case 5:
            workorderId = cell.dataset.wkoId;
            workorderStatus = cell.dataset.wkoStatus;
            console.log(workorderId);
            console.log(workorderStatus);
            select = document.createElement('select');
            select.className = 'wko-status-select';
            WORKORDER_STATUSES.forEach(function (status) {
              var option = document.createElement('option');
              option.value = status;
              option.textContent = status;
              if (status === workorderStatus) {
                option.selected = true;
              }
              select.appendChild(option);
            });
            cell.innerHTML = '';
            cell.appendChild(select);
            select.focus();
            restoreText = function restoreText(statusText) {
              cell.dataset.currentStatus = statusText;
              cell.innerHTML = "<span class=\"wko-status-text\">".concat(statusText, "</span>"); // ` <-- this is a back tick or grave accent, known in js as a template liberal
            };
            select.addEventListener('blur', function () {
              restoreText(currentStatus);
            }, {
              once: true
            }); //<-- this makes the listener automatically remove itself after firing once

            select.addEventListener('change', /*#__PURE__*/_asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee() {
              var newStatus;
              return _regeneratorRuntime().wrap(function _callee$(_context) {
                while (1) switch (_context.prev = _context.next) {
                  case 0:
                    //now trigger the API
                    newStatus = select.value; // try {
                    //     const response = await fetch(`{$URLROOT}workorders`)
                    // }
                  case 1:
                  case "end":
                    return _context.stop();
                }
              }, _callee);
            })));
          case 18:
          case "end":
            return _context2.stop();
        }
      }, _callee2);
    }));
    return function (_x) {
      return _ref.apply(this, arguments);
    };
  }());
}


/***/ }),

/***/ "./src/js/transfernotes.js":
/*!*********************************!*\
  !*** ./src/js/transfernotes.js ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initTransferNotes: () => (/* binding */ initTransferNotes)
/* harmony export */ });
function initTransferNotes() {
  /**
   * 
   * TODO:
   * set html element(s) as constants 
   * add hover and on click events 
   */
  var URLROOT = 'http://localhost/SAM/';
  var tNoteRows = document.querySelectorAll(".tnoterow");
  var tnoteChecks = document.querySelectorAll(".tCheck");
  var colBtn = document.querySelector(".colBtn");
  var delBtn = document.querySelector(".delBtn");
  var deleteBtn = document.querySelector(".delete");
  var i;
  var j;
  var k;
  var l;
  var checks;
  if (tNoteRows.length > 1) {
    for (i = 0; i < tNoteRows.length; i++) {
      tNoteRows[i].addEventListener("click", function (e) {
        var tnid = e.target.parentElement.dataset.id;
        window.location.href = URLROOT + "transport/viewtn/" + tnid;
      });
    }
  }
  var visibleBtns = 0;
  var wkos = [];
  if (tnoteChecks.length > 1) {
    for (j = 0; j < tnoteChecks.length; j++) {
      //need to check value of all check boxes and sum total
      tnoteChecks[j].addEventListener("change", function (e) {
        //if total is > 0 then show btns otherwise hide them
        var isChecked = e.target.checked;
        var wkoid = e.target.parentElement.dataset.id;
        isChecked ? visibleBtns++ : visibleBtns--;
        if (isChecked && visibleBtns > 0) {
          colBtn.classList.remove('hidden');
          colBtn.classList.add('block');
          delBtn.classList.remove('hidden');
          delBtn.classList.add('block');
          return;
        } else if (visibleBtns < 1) {
          colBtn.classList.remove('block');
          colBtn.classList.add('hidden');
          delBtn.classList.remove('block');
          delBtn.classList.add('hidden');
          return;
        }
      });
    }
    colBtn.addEventListener('click', function (e) {
      wkos = [];
      for (k = 0; k < tnoteChecks.length; k++) {
        if (tnoteChecks[k].checked) {
          wkos.push(tnoteChecks[k].parentElement.dataset.id);
        }
      }
      console.log(wkos);
      window.location.href = "http://localhost/SAM/transport/tnote/c/" + wkos;
    });
    delBtn.addEventListener('click', function (e) {
      //do the delivery note stuff innit
      wkos = [];
      for (l = 0; l < tnoteChecks.length; l++) {
        if (tnoteChecks[l].checked) {
          wkos.push(tnoteChecks[l].parentElement.dataset.id);
        }
      }
      console.log(wkos);
      window.location.href = "http://localhost/SAM/transport/tnote/d/" + wkos;
    });
  }

  //document.write('test point');
}


/***/ }),

/***/ "./src/js/workorders.js":
/*!******************************!*\
  !*** ./src/js/workorders.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initWorkOrders: () => (/* binding */ initWorkOrders)
/* harmony export */ });
function initWorkOrders() {
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
  if (window.location.href === 'http://localhost/SAM/workorders/index') {
    // if(1===1) {   
    console.log('we have started pagination function');
    var pgBtns = document.querySelectorAll(".pgBtn");
    var pageNumberInfoA = document.querySelector(".pageInfoA");
    var pageNumberInfoB = document.querySelector(".pageInfoB");
    var addWkoBtn = document.querySelector(".addWkoBtn");
    //const addWko
    var totalAResults = document.querySelector(".Acount");
    totalAResults = totalAResults.dataset.count;
    var totalCResults = document.querySelector(".Ccount");
    totalCResults = totalCResults.dataset.count;
    var currentStart;
    var currentEnd;
    var h;
    var dateIn;
    var wko;
    var avn;
    var pdesc;
    var wheels;
    var quantity;
    var quantity_built;
    var _serials;
    var wko_status;
    var wko_delivery;
    var wko_notes;
    var apiFile = '../api/workorder/';
    for (h = 0; h < pgBtns.length; h++) {
      pgBtns[h].addEventListener("click", function (e) {
        var page = e.target.dataset.page;
        var tag = e.target.parentElement.dataset.wkos;
        console.log(tag);
        tag === "com" ? apiFile = '../apiworkorders/paginate/completed/' + page : apiFile = '../apiworkorders/paginate/active/' + page;
        //let apiFile = '../api/workorder/' + page
        fetch(apiFile, {
          headers: {
            "Content-Type": "application/json"
          }
        }).then(function (response) {
          return response.json();
        }).then(function (response) {
          console.log(response); //testing response=
          var length = Object.keys(response).length;
          console.log('length of returned object array is: ' + length);
          var trClass;
          var Table = '';
          tag === 'com' ? Table = document.querySelector("#tblCwo") : Table = document.querySelector("#tblAwo");
          var rowNo = Table.rows.length;
          console.log("row numbers: " + rowNo);
          for (i = 1; i < rowNo; i++) {
            Table.deleteRow(1);
          }
          for (i = 1; i < length + 1; i++) {
            if (response[i - 1].wko_status) {
              switch (response[i - 1].wko_status) {
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
            var Tbody = void 0;
            tag === 'com' ? Tbody = document.querySelector("#tbodyCwo") : Tbody = document.querySelector("#tbodyAwo");
            var row = Tbody.insertRow(0);
            row.classList.add("text-center", "items-center", "border-4", "worow", trClass, "min-h-3", "slow-fade-in");
            var tdDate = document.createElement("td");
            tdDate.classList.add("text-nowrap");
            var tdWko = document.createElement("td");
            var tdAvn = document.createElement("td");
            var tdPdesc = document.createElement("td");
            tdPdesc.classList.add("text-[10px]");
            var tdQuantityR = document.createElement("td");
            var tdQuantityB = document.createElement("td");
            var tdSerials = document.createElement("td");
            var tdWkoS = document.createElement("td");
            var tdWkoD = document.createElement("td");
            var tdWkoN = document.createElement("td");
            tdWkoN.classList.add("min-w-24");
            var tdComp = document.createElement("td");
            var tdSplit = document.createElement("td");
            var tdEdit = document.createElement("td");
            var tdDelete = document.createElement("td");
            var acomp = document.createElement("a");
            var asplit = document.createElement("a");
            var aedit = document.createElement("a");
            var adelete = document.createElement("a");
            var _dateIn = response[i - 1].created_at;
            _dateIn = _dateIn.slice(0, 10);
            _dateIn = document.createTextNode(_dateIn);
            var dateCompleted = response[i - 1].completed_at;
            dateCompleted = JSON.stringify(dateCompleted);
            if (dateCompleted === 'null') {
              dateCompleted = document.createTextNode('N/A');
            } else {
              dateCompleted = dateCompleted.slice(1, 11);
              dateCompleted = document.createTextNode(dateCompleted);
            }
            wko = document.createTextNode(response[i - 1].wko);
            avn = document.createTextNode(response[i - 1].avn);
            pdesc = document.createTextNode(response[i - 1].pdesc);
            quantity = document.createTextNode(response[i - 1].quantity);
            _serials = response[i - 1].serials === null ? document.createTextNode('') : document.createTextNode(response[i - 1].serials);
            wko_status = document.createTextNode(response[i - 1].wko_status);
            wko_delivery = document.createTextNode(response[i - 1].wko_delivery);
            wko_notes = document.createTextNode(response[i - 1].wko_notes);
            acomp.setAttribute("href", "http://localhost/SAM/workorders/complete/" + response[i - 1].work_order_id);
            acomp.innerHTML += '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path class="mrk-as-comp" stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>';
            asplit.setAttribute("href", "javascript:void(0)");
            asplit.setAttribute("data-qty", response[i - 1].quantity);
            asplit.innerHTML += '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path class="split-order" stroke-linecap="round" stroke-linejoin="round" d="m7.848 8.25 1.536.887M7.848 8.25a3 3 0 1 1-5.196-3 3 3 0 0 1 5.196 3Zm1.536.887a2.165 2.165 0 0 1 1.083 1.839c.005.351.054.695.14 1.024M9.384 9.137l2.077 1.199M7.848 15.75l1.536-.887m-1.536.887a3 3 0 1 1-5.196 3 3 3 0 0 1 5.196-3Zm1.536-.887a2.165 2.165 0 0 0 1.083-1.838c.005-.352.054-.695.14-1.025m-1.223 2.863 2.077-1.199m0-3.328a4.323 4.323 0 0 1 2.068-1.379l5.325-1.628a4.5 4.5 0 0 1 2.48-.044l.803.215-7.794 4.5m-2.882-1.664A4.33 4.33 0 0 0 10.607 12m3.736 0 7.794 4.5-.802.215a4.5 4.5 0 0 1-2.48-.043l-5.326-1.629a4.324 4.324 0 0 1-2.068-1.379M14.343 12l-2.882 1.664" /></svg>';
            aedit.setAttribute("href", "http://localhost/SAM/workorders/edit/" + response[i - 1].work_order_id);
            aedit.innerHTML += '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path class="edit-order edit" stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" /></svg>';
            adelete.setAttribute("href", "http://localhost/SAM/workorders/delete/" + response[i - 1].work_order_id);
            adelete.innerHTML += '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path class="dlt-order delete" stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>';
            if (tag === 'com') {
              tdDate.appendChild(dateCompleted);
            } else {
              tdDate.appendChild(_dateIn);
            }
            tdWko.appendChild(wko);
            tdAvn.appendChild(avn);
            tdPdesc.appendChild(pdesc);
            tdQuantityR.appendChild(quantity);
            tdSerials.appendChild(_serials);
            tdWkoS.appendChild(wko_status);
            tdWkoD.appendChild(wko_delivery);
            tdWkoN.appendChild(wko_notes);
            tdComp.appendChild(acomp);
            tdSplit.appendChild(asplit);
            tdEdit.appendChild(aedit);
            tdDelete.appendChild(adelete);
            if (tag != 'com') {
              row.appendChild(tdDate);
              row.appendChild(tdWko);
              row.appendChild(tdAvn);
              row.appendChild(tdPdesc);
              row.appendChild(tdQuantityR);
              row.appendChild(tdSerials);
              row.appendChild(tdWkoS);
              row.appendChild(tdWkoD);
              row.appendChild(tdWkoN);
              row.appendChild(tdComp);
              row.appendChild(tdSplit);
              row.appendChild(tdEdit);
              row.appendChild(tdDelete);
            } else {
              row.appendChild(tdDate);
              row.appendChild(tdWko);
              row.appendChild(tdAvn);
              row.appendChild(tdPdesc);
              row.appendChild(tdQuantityB);
              row.appendChild(tdSerials);
              row.appendChild(tdWkoD);
              row.appendChild(tdWkoN);
            }
            Tbody.appendChild(row);
            switch (page) {
              case 0:
                currentStart = '1';
                currentEnd = '10';
                break;
              case '0':
                currentStart = '1';
                currentEnd = '10';
                break;
              default:
                currentStart = page * 10 + 1;
                currentEnd = currentStart - 1 + length;
                break;
            }
            // console.log('page value is: '+page)
            if (tag === 'com') {
              pageNumberInfoB.innerHTML = 'Showing <span class="font-medium">' + currentStart + '</span> to <span class="font-medium">' + currentEnd + '</span> of <span class="font-medium">' + totalCResults + '</span> results';
            } else {
              pageNumberInfoA.innerHTML = 'Showing <span class="font-medium">' + currentStart + '</span> to <span class="font-medium">' + currentEnd + '</span> of <span class="font-medium">' + totalAResults + '</span> results';
            }
          }
        });
      });
    }
  }
  ;

  // const addWkoBtn = document.querySelector(".addWkoBtn")
  // const addWkoDialog = document.querySelector("#addWko")
  // if(addWkoBtn) { 
  //     addWkoBtn.addEventListener("click", (e) => {
  //         // window.open('http://localhost/SAM/workorders/add')
  //         // addWkoDialog.classList.remove("hidden")
  //         // addWkoDialog.classList.add("block")  
  //    })
  // }

  var tNoteRows = document.querySelectorAll(".worow");
  var i;
  if (tNoteRows) {
    for (i = 0; i < tNoteRows.length; i++) {
      tNoteRows[i].addEventListener("click", function (e) {
        var woid = e.target.parentElement.dataset.id;
        if (!!woid) {
          window.location.href = "http://localhost/SAM/workorders/viewwo/" + woid;
        }
      });
    }
  }
  var compBtns = document.querySelectorAll(".mrk-as-comp");
  var serials;
  var rowQty;
  if (compBtns) {
    for (var l = 0; l < compBtns.length; l++) {
      compBtns[l].addEventListener("click", function (e) {
        var woid = e.target.parentElement.parentElement.parentElement.parentElement.dataset.id;
        var serialStatus = e.target.parentElement.parentElement.parentElement.parentElement.children[5].textContent;
        console.log(serialStatus);
        if (serialStatus === "To Be Confirmed") {
          var m;
          var outputSerialRanges = [];
          //get expected quantity
          rowQty = e.target.parentElement.parentElement.parentElement.parentElement.children[4].textContent;
          console.log('expected quantity: ' + rowQty);
          var inputSerialRanges = window.prompt("Please enter the serials for this work order to mark it as complete", "").split(",");
          console.log("input serial range length: " + inputSerialRanges.length);
          if (inputSerialRanges = 'Sent without serials') {
            //add note to wko
          } else {
            if (inputSerialRanges.length == 1 && rowQty == 1) {
              //only one serial for this order
              // window.alert("nice one!")
              window.location.href = "http://localhost/SAM/workorders/complete/" + woid + "/" + inputSerialRanges;
            } else {
              //check range(s) and compare qty to expected
              console.log('input serial range(s): ' + inputSerialRanges);
              for (m = 0; m < inputSerialRanges.length; m++) {
                console.log('serial number range: ' + inputSerialRanges[m]);
                var numbers = inputSerialRanges[m].split("-");
                var low = Number(numbers[0].trim());
                var high = Number(numbers[1].trim());
                console.log('low: ' + low);
                console.log('high: ' + high);
                for (var n = low; n <= high; n++) {
                  console.log('number: ' + n);
                  outputSerialRanges.push(n);
                  //need to save the numbers to an array
                }
                if (outputSerialRanges.length != rowQty) {
                  window.alert("Incorrect number of serials for this WKO!");
                } else {
                  window.location.href = "http://localhost/SAM/workorders/complete/" + woid + "/" + inputSerialRanges;
                }
              }
            }
          }
        } else {
          window.location.href = "http://localhost/SAM/workorders/complete/" + woid;
        }
      });
    }
  }
  var splitBtns = document.querySelectorAll(".split-order");
  var splitPoint;
  var j;
  if (splitBtns) {
    for (j = 0; j < splitBtns.length; j++) {
      splitBtns[j].addEventListener("click", function (e) {
        var woid = e.target.parentElement.parentElement.parentElement.parentElement.dataset.id;
        var quantity = e.target.parentElement.parentElement.dataset.qty;
        splitPoint = Number(window.prompt("After how many cabinets should the WKO be split?", ""));
        if (splitPoint > quantity - 1 | splitPoint === 0 | isNaN(splitPoint)) {
          window.alert("Error: Can't split from this point.");
        } else {
          window.location.href = "http://localhost/SAM/workorders/split/" + woid + "/" + splitPoint;
        }
      });
    }
  }
  var dltBtns = document.querySelectorAll(".dltBtn");
  var k;
  if (dltBtns) {
    for (k = 0; k < dltBtns.length; k++) {
      dltBtns[k].addEventListener("click", function (e) {
        var woid = e.target.parentElement.parentElement.parentElement.dataset.id;
        if (confirm("Are you sure you want to delete this workorder?") == true) {
          window.location.href = "http://localhost/SAM/workorders/delete/" + woid;
          // console.log(e.target.parentElement.parentElement.parentElement)
          console.log(woid);
          console.log('delete button clicked');
        }
      });
    }
  }
}


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
/*!***********************!*\
  !*** ./src/js/app.js ***!
  \***********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _navigation__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./navigation */ "./src/js/navigation.js");
/* harmony import */ var _workorders__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./workorders */ "./src/js/workorders.js");
/* harmony import */ var _transfernotes__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./transfernotes */ "./src/js/transfernotes.js");
/* harmony import */ var _addOrder__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./addOrder */ "./src/js/addOrder.js");
/* harmony import */ var _statusUpdate__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./statusUpdate */ "./src/js/statusUpdate.js");





var URLROOT = 'http://localhost/SAM/';
// const APPROOT = __dirname

(function () {
  (0,_navigation__WEBPACK_IMPORTED_MODULE_0__.initNav)();
  (0,_transfernotes__WEBPACK_IMPORTED_MODULE_2__.initTransferNotes)();
  (0,_workorders__WEBPACK_IMPORTED_MODULE_1__.initWorkOrders)();
  (0,_addOrder__WEBPACK_IMPORTED_MODULE_3__.initAddOrder)();
  (0,_statusUpdate__WEBPACK_IMPORTED_MODULE_4__.updateStatus)();
})();
/******/ })()
;