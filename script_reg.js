const loginTab = document.getElementById('login-tab');
const registerTab = document.getElementById('register-tab');
const loginForm = document.getElementById('login-form');
const registerForm = document.getElementById('register-form');

function switchForms(activeForm, hiddenForm) {
    // Анимация исчезновения текущей формы
    hiddenForm.classList.add('fade-out');

    // После завершения анимации исчезновения
    setTimeout(() => {
        hiddenForm.classList.add('hidden');
        hiddenForm.classList.remove('fade-out');

        // Показываем новую форму с анимацией появления
        activeForm.classList.remove('hidden');
        activeForm.classList.add('fade-in');

        // Убираем класс `fade-in` после завершения анимации
        setTimeout(() => {
            activeForm.classList.remove('fade-in');
        }, 500); 
    }, 500); 
}

loginTab.addEventListener('click', () => {
    loginTab.classList.add('active');
    registerTab.classList.remove('active');
    switchForms(loginForm, registerForm);
});

registerTab.addEventListener('click', () => {
    registerTab.classList.add('active');
    loginTab.classList.remove('active');
    switchForms(registerForm, loginForm);
});


