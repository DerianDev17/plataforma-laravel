(function () {
    'use strict';

    window.APP_URL = window.APP_URL || '';

    window.mostrarNotif = function (mensaje) {
        if (!mensaje || !('Notification' in window) || Notification.permission !== 'granted') {
            return;
        }

        new Notification(mensaje);
    };

    function setSidebarState(sidebar, overlay, toggle, isOpen) {
        sidebar.classList.toggle('open', isOpen);
        overlay.classList.toggle('open', isOpen);
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    }

    function initSidebar() {
        var sidebar = document.querySelector('[data-sidebar]');
        var overlay = document.querySelector('[data-sidebar-overlay]');
        var toggle = document.querySelector('[data-sidebar-toggle]');

        if (!sidebar || !overlay || !toggle) {
            return;
        }

        toggle.addEventListener('click', function () {
            setSidebarState(sidebar, overlay, toggle, !sidebar.classList.contains('open'));
        });

        overlay.addEventListener('click', function () {
            setSidebarState(sidebar, overlay, toggle, false);
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                setSidebarState(sidebar, overlay, toggle, false);
            }
        });
    }

    function notifyCurrentSessions() {
        var sessions = window.todaySessions;

        if (!Array.isArray(sessions) || !('Notification' in window)) {
            return;
        }

        var notify = function () {
            var currentHour = new Date().getHours();

            sessions.forEach(function (session) {
                if (!Array.isArray(session) || !session[0] || !session[1]) {
                    return;
                }

                var sessionHour = parseInt(String(session[0]).slice(0, 2), 10);

                if (sessionHour === currentHour) {
                    window.mostrarNotif('Tiene clase de ' + session[1]);
                }
            });
        };

        if (Notification.permission === 'granted') {
            notify();
            return;
        }

        if (Notification.permission !== 'denied' && Notification.requestPermission) {
            Notification.requestPermission().then(function (permission) {
                if (permission === 'granted') {
                    notify();
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        initSidebar();
        notifyCurrentSessions();
    });
}());
