importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyD3JY3UVos0Xk1sk6VlTExFjpBXbsFNbW0",
    projectId: "webpushkz",
    messagingSenderId: "72631381469",
    appId: "1:72631381469:web:3c299d5e35e39fdac19a34"
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});
