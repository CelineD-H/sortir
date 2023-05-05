function deleteFlash() {
    const allFlash = document.querySelectorAll('.flash');

    for (let flash of allFlash) {
        let value = 99;
        setInterval(frame, 10);
        function frame() {
            if(value === 10) {
                flash.remove();
            } else {
                flash.style.opacity = "0." + value;
                value--;
            }
        }
    }
}

setTimeout(function () {
    deleteFlash();
}, 5000);

