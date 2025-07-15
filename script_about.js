let currentIndex = 0;

function navigate(direction) {
    const slides = document.querySelectorAll('.info-slide');
    slides[currentIndex].classList.remove('active');

    currentIndex += direction;

    // Зацикливание слайдов
    if (currentIndex < 0) {
        currentIndex = slides.length - 1;
    } else if (currentIndex >= slides.length) {
        currentIndex = 0;
    }

    slides[currentIndex].classList.add('active');
}
