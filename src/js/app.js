import { initNav } from "./navigation";
import { initWorkOrders } from "./workorders";
import { initTransferNotes } from "./transfernotes";
import { initAddOrder } from "./addOrder";
import { initUpdateStatus } from "./statusUpdate";
import { initTransport } from "./transport.js";

const URLROOT = 'http://localhost/SAM/';
// const APPROOT = __dirname


(function () {
    initNav();
    initTransferNotes();
    initWorkOrders();
    initAddOrder();
    initUpdateStatus();
    initTransport();
  })();


