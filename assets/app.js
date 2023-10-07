import "bootstrap-icons/font/bootstrap-icons.css"
import "./styles/style.scss";

import {
    Alert
} from "bootstrap";

document.addEventListener("DOMContentLoaded", function() {
    document.querySelector("#use-js").style.visibility = "visible";
});

function checkCookiesEnabled() {
    let cookieEnabled = (navigator.cookieEnabled) ? true : false;
    if (typeof navigator.cookieEnabled == "undefined" && !cookieEnabled) {
        document.cookie = "testcookie";
        cookieEnabled = (document.cookie.indexOf("testcookie") != -1) ? true : false;
        expireCookie("testcookie");
    }
    return cookieEnabled;
}

function getCookie(name) {
    let parts = document.cookie.split(name + "=");
    if (parts.length == 2) return parts.pop().split(";").shift();
}

function expireCookie(cName) {
    document.cookie =
        encodeURIComponent(cName) + "=deleted; expires=" + new Date(0).toUTCString();
}

function setFormToken() {
    let downloadToken = new Date().getTime();
    document.getElementById("favicon_form_downloadToken").value = downloadToken;
    return downloadToken;
}

function showSpinner() {
    document.getElementById("main").style.transition = "opacity 0.4s";
    document.getElementById("main").style.opacity = "0.35";
    document.getElementById("main").style.pointerEvents = "none";
    document.getElementsByClassName("spinner-container")[0].style.transition = "opacity 0.4s";
    document.getElementsByClassName("spinner-container")[0].style.visibility = "visible";
    document.getElementsByClassName("spinner-container")[0].style.opacity = "1";
}

function removeSpinner() {
    document.getElementById("main").style.transition = "opacity 0.4s";
    document.getElementById("main").style.opacity = "1";
    document.getElementById("main").style.pointerEvents = "all";
    document.getElementsByClassName("spinner-container")[0].style.transition = "opacity 0.4s";
    document.getElementsByClassName("spinner-container")[0].style.opacity = "0";
    document.getElementsByClassName("spinner-container")[0].style.visibility = "hidden";
}

function removeAlerts() {
    let alertList = document.querySelectorAll('.alert')
    let alerts = [].slice.call(alertList).map(function(element) {
        new Alert(element).close();
    })
}

window.onpageshow = function() {
    removeSpinner();
}

window.onsubmit = function() {
    removeAlerts();
    if (checkCookiesEnabled() == true) {
        let downloadToken = setFormToken();
        let downloadTimer;
        let closeTimer;
        let alert = document.querySelector("#successAlert");
        showSpinner();
        downloadTimer = window.setInterval(function() {
            var cookie = getCookie("downloadToken");
            if (cookie == downloadToken) {
                expireCookie("downloadToken");
                removeSpinner();
                alert.innerHTML =
                    `<div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check" aria-hidden="true"></i>
                        A ZIP file containing your favicons and related HTML code has been successfully created.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
                clearInterval(downloadTimer);
            }
        }, 1000);
        closeTimer = window.setTimeout(function() {
            let alertSelector = document.querySelector('.alert-success');
            if (alertSelector != null) {
                let alert = new Alert(alertSelector);
                alert.close()
            }
        }, 5000);
    }
}

// start the Stimulus application
import './bootstrap';