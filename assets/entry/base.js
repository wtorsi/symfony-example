if ("serviceWorker" in navigator) {
    window.addEventListener("load", () => {
        navigator.serviceWorker.register("/sw.js", {
            scope: '/'
        });
    })
}


// import './webp-support'
import './fontawesome'
// import ('Hinclude/hinclude');
// import ('../js/jssocials/jssocials');
// import ('../js/bootstrap/collapse');
