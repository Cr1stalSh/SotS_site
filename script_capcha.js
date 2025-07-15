let captchaText = "";

function showCaptcha() {
  captchaText = generateCaptcha();
  document.getElementById('captchaText').innerText = captchaText;
  document.querySelector('.modal').style.display = 'block';
}

function closeCaptcha() {
  document.getElementById('captchaModal').style.display = 'none';
}

function generateCaptcha() {
  const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  let result = "";
  for (let i = 0; i < 6; i++) {
    result += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  return result;
}

function validateCaptcha() {
  const userInput = document.getElementById('captchaInput').value;
  const errorMessage = document.getElementById('errorMessage');

  if (userInput.startsWith('http://') || userInput.startsWith('https://')) {
    errorMessage.innerText = 'Введена ссылка, попробуйте снова.';
    captchaText = generateCaptcha();
    document.getElementById('captchaText').innerText = captchaText;
    return; 
  }

  if (userInput === captchaText) {
    errorMessage.innerText = '';
    closeCaptcha();
    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.src = 'download_game.php?file=SotS.exe';
    document.body.appendChild(iframe);
  } else {
    errorMessage.innerText = 'Неверный ввод. Попробуйте снова.';
    captchaText = generateCaptcha();
    document.getElementById('captchaText').innerText = captchaText;
  }
}
