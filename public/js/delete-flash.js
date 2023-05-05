document.addEventListener("DOMContentLoaded", function() {
    setTimeout(function () {
        const allFlash = document.querySelectorAll('.flash');
        const flashArray = Array.from(allFlash); // convertit la NodeList en tableau

        flashArray.forEach(function (flash) {
            let value = 99;
            const intervalId = setInterval(frame, 10);
            function frame() {
                if(value === 10) {
                    clearInterval(intervalId);
                    flash.remove();
                } else {
                    flash.style.opacity = "0." + value;
                    value--;
                }
            }
        });
    }, 5000);
});