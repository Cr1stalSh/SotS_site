const menuBtn = document.querySelector('.menu-btn');
const sidePanel = document.getElementById('sidePanel');
const overlay = document.getElementById('overlay');
const contentBlocks = document.querySelectorAll('.content-block');

window.addEventListener('scroll', () => {
    contentBlocks.forEach((block, index) => {
        const rect = block.getBoundingClientRect();
        if (rect.top < window.innerHeight - 100) {
            if (index % 2 === 0) {
                // Если блок чётный, выезжает слева
                block.classList.add('left');
            } else {
                // Если блок нечётный, выезжает справа
                block.classList.add('right');
            }
        }
    });
});
// Открыть боковую панель
menuBtn.addEventListener('click', () => {
    sidePanel.classList.add('active');
    overlay.style.display = 'block'; // Отображаем overlay перед анимацией
    setTimeout(() => overlay.classList.add('active'), 10); // Делаем overlay видимым с анимацией
});

// Закрыть боковую панель при клике на затемнение
overlay.addEventListener('click', () => {
    sidePanel.classList.remove('active');
    overlay.classList.remove('active');
    setTimeout(() => overlay.style.display = 'none', 300); // Прячем overlay после анимации
});

document.getElementById('loginButton').addEventListener('click', function() {
    window.location.href = 'reg.php';
});

