// Функция для установки cookie
function setCookie(name, value, days = 30, ) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/web/index.html";
}

// Функция для чтения cookie
function getCookie(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

// Функция для удаления cookie
function clearCookie(name) {
    document.cookie = name + "=; Max-Age=-99999999;";
}

// Функция для скрытия всплывающего окна предупреждения о cookies
function hideCookieWarning() {
    let cookieWarning = document.getElementById("cookie-warning");
    cookieWarning.style.display = "none";
}

// Проверяем, существует ли cookie "cookieAccepted"
let cookieAccepted = getCookie("cookieAccepted");

// Если cookie "cookieAccepted" не существует, показываем всплывающее окно предупреждения о cookies
if (!cookieAccepted) {
    let cookieWarning = document.getElementById("cookie-warning");
    cookieWarning.style.display = "block";
}

// Устанавливаем cookie "cookieAccepted" со значением "true", сроком действия 1 год и доступом на всем сайте при нажатии на кнопку "OK"
let cookieAcceptButton = document.getElementById("cookie-accept");
cookieAcceptButton.addEventListener("click", function() {
    setCookie("cookieAccepted", "true", 365);
    hideCookieWarning();
});

// Удаляем cookie "cookieAccepted" при нажатии на кнопку "Очистить"
let cookieClearButton = document.getElementById("cookie-clear");
cookieClearButton.addEventListener("click", function() {
    clearCookie("cookieAccepted");
    let cookieWarning = document.getElementById("cookie-warning");
    cookieWarning.style.display = "block";
    hideCookieWarning();
});