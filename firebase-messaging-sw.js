// Give the service worker access to Firebase Messaging.
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyBtb6OSkTsypZnJ2o1hVTJvGQw9JV__sc0",
    authDomain: "tour-vision-5a892.firebaseapp.com",
    projectId: "tour-vision-5a892",
    storageBucket: "tour-vision-5a892.appspot.com",
    messagingSenderId: "904313745421",
    appId: "1:904313745421:web:ccd18829c454a88a3e6544",
    measurementId: "G-6XDB12B751"
});

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {
    const title = (payload.notification && payload.notification.title) || (payload.data && payload.data.title) || 'New Lead';
    const body = (payload.notification && payload.notification.body) || (payload.data && payload.data.body) || '';
    const options = {
        body: body,
        icon: 'dist/img/AdminLTELogo.png',
        data: payload.data || {},
        requireInteraction: true,
        tag: 'lead-' + ((payload.data && payload.data.lead_id) || Date.now())
    };
    return self.registration.showNotification(title, options);
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    const url = (event.notification.data && (event.notification.data.url || event.notification.data.click_action)) || './';
    event.waitUntil(clients.openWindow(url));
});
