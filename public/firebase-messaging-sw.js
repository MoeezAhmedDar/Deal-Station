/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
firebase.initializeApp({
    apiKey: "AIzaSyAzo5GmxqqKyf6m2-uh3VH016gouPdiyDI",
    authDomain: "deal-station-66c0b.firebaseapp.com",
    databaseURL: "https://deal-station-66c0b.firebaseio.com",
    projectId: "deal-station-66c0b",
    storageBucket: "deal-station-66c0b.appspot.com",
    messagingSenderId: "489698765155",
    appId: "1:489698765155:web:7c6a75e31e37ccaae14b56",
    measurementId: "G-T6VZTYS87K"
});

/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload,
    );
    /* Customize notification here */
    const notificationTitle = "Background Message Title";
    const notificationOptions = {
        body: "Background Message body.",
        icon: "/itwonders-web-logo.png",
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions,
    );
});