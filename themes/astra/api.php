<?php
/*
Template Name: Регистрация в ЛНК
*/
?>

<style>
    body {
        margin: 0;
        padding: 20px;
        background-image: url('https://test-merch.alean.ru/wp-content/uploads/rst-news-10-07-25-5-1.png');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        font-family: 'Montserrat', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100%;
    }
@media (min-width: 1201px) {
  .form-container {
    width: 100%;
    max-width: 1240px;
    margin-left: 0;
    display: flex;
    justify-content: flex-end;
  }
}
@media (max-width: 1200px) {
  .form-container {
    width: 100%;
     margin: auto;
  }
}

    .form-container {


    }

    .google-form {
    display: flex;
    flex-direction: column;
    padding: 40px;
    gap: 20px;
    width: 100%;
    background: #FFFFFF;
    border-radius: 20px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.07);
    transition: all 0.3s ease;
    opacity: 1;
    max-width: 540px;
    }

    .success-message {
        display: none;
        flex-direction: column;
        padding: 40px;
        gap: 20px;
        width: 100%;
        background: #FFFFFF;
        border-radius: 20px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.07);
        text-align: center;
        transition: all 0.3s ease;
        opacity: 0;
    }

    .success-message.active {
        display: flex;
        opacity: 1;
    }

    .form-field {
        position: relative;
        width: 100%;
        margin-bottom: 15px;
    }

    .form-field input {
        transition: all 0.3s ease;
        padding: 0 16px;
        width: 100%;
        height: 48px;
        background: #FFFFFF;
        border: 1px solid #DDDDDD;
        border-radius: 12px;
        font-size: 16px;
        font-family: 'Montserrat', sans-serif;
    }

    .form-field input:focus {
        border-color: #1a73e8;
        outline: none;
    }

    .form-field label {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: white;
        padding: 0 4px;
        color: #666;
        font-size: 16px;
        transition: all 0.2s ease;
        pointer-events: none;
    }

    .form-field input:focus + label,
    .form-field input:focus label,
    .form-field input:not(:placeholder-shown) + label {
        top: 0!important;
        font-size: 12px;
        color: #1a73e8;
    }


    .submit-btn {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 16px 24px;
        gap: 8px;
        background: linear-gradient(254.63deg, #65D4FB -63.66%, #8777E9 113.86%);
        border-radius: 16px;
        font-family: 'Montserrat';
        font-weight: 600;
        font-size: 14px;
        line-height: 120%;
        text-transform: uppercase;
        color: #FFFFFF;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }

    .submit-btn:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    .error-message {
        color: #d32f2f;
        font-size: 12px;
        margin-top: 4px;
        display: none;
    }

    .error-field {
        border-color: #d32f2f !important;
    }

    .form__header {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        text-align: center;
        margin-bottom: 20px;
    }

    .form__title, .success-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 22px;
        line-height: 120%;
        color: #0F172A;
        margin: 0;
    }

    .form__subtitle, .success-text {
        font-family: 'Montserrat', sans-serif;
        font-weight: 400;
        font-size: 14px;
        line-height: 20px;
        color: #0F172A;
        margin: 0;
    }

    .success-image {
        max-width: 200px;
        margin: 20px auto;
    }

    .button-catalog {
        display: inline-block;
        padding: 16px 24px;
        background: linear-gradient(254.63deg, #65D4FB -63.66%, #8777E9 113.86%);
        border-radius: 16px;
        font-family: 'Montserrat';
        font-weight: 600;
        font-size: 14px;
        line-height: 120%;
        text-transform: uppercase;
        color: #FFFFFF;
        text-decoration: none;
        transition: all 0.3s ease;
    }
a[href="https://dadata.ru/suggestions/?utm_source=dadata&utm_medium=module&utm_campaign=suggestions-jquery"] {
  display: none !important;
}
.suggestions-suggestions{
    border-color: #1a73e8;
    outline: none;
    transition: all 0.3s ease;
    padding: 0 16px;
    width: 100%;
    background: #FFFFFF;
    border: 1px solid #1a73e8!important;
    font-size: 16px;
    font-family: 'Montserrat', sans-serif;
    border-radius: 12px;
     border-color: #DDDDDD;
}
.suggestions-input:focus{

    
}
input[name="organization"]:focus + .suggestions-wrapper > .suggestions-suggestions:not([style="display: none;"]){
    

}
:has(.suggestions-wrapper > .suggestions-suggestions:not([style="display: none;"])) input[name="organization"]:focus{
    
}
</style>

<div class="form-container">
    <form class="google-form" id="registrationForm">
        <aside class="form__header">
            <div class="form__title">
                Присоединиться к бонусной<br>программе
            </div>
            <div class="form__subtitle">
                Для регистрации в бонусной программе, пожалуйста, заполните данные
            </div>
        </aside>
        
        <div class="form-field">
            <input type="text" id="lastName" name="lastName" placeholder=" " required>
            <label for="lastName">Фамилия</label>
        </div>
        
        <div class="form-field">
            <input type="text" id="firstName" name="firstName" placeholder=" " required>
            <label for="firstName">Имя</label>
        </div>

        <div class="form-field">
            <input type="text" id="dolzhnost" name="dolzhnost" placeholder=" " required>
            <label for="dolzhnost">Должность</label>
        </div>

        <div class="form-field organization-field">
            <input type="text" id="organization" name="organization" placeholder=" " required>
            <label for="organization">ИНН или Наименование Юридического лица</label>
            <div id="organization-error" class="error-message">Пожалуйста, выберите организацию из списка предложенных</div>
        </div>

        <input type="hidden" id="eventSessionId" name="eventSessionId" value="651720442" required>
        <input type="hidden" id="inn" name="inn" value="">

        <?php
        $current_user = wp_get_current_user();
        if ($current_user->exists()) : 
            $user_email = esc_attr($current_user->user_email);
            ?>
            <input type="hidden" id="email" name="email" value="<?php echo $user_email; ?>">
        <?php else: ?>
            <div class="form-field">
                <input type="email" id="email" name="email" placeholder=" " required>
                <label for="email">Email</label>
            </div>
        <?php endif; ?>

        <button type="submit" class="submit-btn">Подтвердить</button>
    </form>

    <div class="success-message" id="successMessage">
        <div class="success-title">Благодарим за регистрацию</div>
        <div class="success-text">Вот вам приветственные 100 хартиков!</div>
        <img src="https://test-merch.alean.ru/wp-content/uploads/image-43.png" alt="Success" class="success-image">
        <a href="/" class="button-catalog">Потратить хартики</a>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@22.6.0/dist/css/suggestions.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@22.6.0/dist/js/jquery.suggestions.min.js"></script>

<script>
    // Флаг для отслеживания валидности организации
    let isOrganizationValid = false;

    // Инициализация подсказок для поля организации
    $("#organization").suggestions({
        token: "c1b8de4e8ead86c71e8613cb5decd1247e848aee",
        type: "PARTY",
        params: {
            status: ["ACTIVE"]
        },
        count: 12,
        onSelect: function(suggestion) {
            console.log(suggestion.data.inn);
            document.getElementById('inn').value = suggestion.data.inn;
            isOrganizationValid = true;
            hideOrganizationError();
        },
        onClear: function() {
            document.getElementById('inn').value = '';
            isOrganizationValid = false;
        }
    });

    // Обработчик изменения поля организации
    document.getElementById('organization').addEventListener('input', function() {
        isOrganizationValid = false;
        if (this.value.trim() === '') {
            document.getElementById('inn').value = '';
            hideOrganizationError();
        }
    });

    // Функция для показа ошибки организации
    function showOrganizationError() {
        const field = document.getElementById('organization');
        const error = document.getElementById('organization-error');
        field.classList.add('error-field');
        error.style.display = 'block';
    }

    // Функция для скрытия ошибки организации
    function hideOrganizationError() {
        const field = document.getElementById('organization');
        const error = document.getElementById('organization-error');
        field.classList.remove('error-field');
        error.style.display = 'none';
    }

    // Валидация формы перед отправкой
    document.getElementById('registrationForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Получаем значения полей
        const firstName = document.getElementById('firstName').value.trim();
        const lastName = document.getElementById('lastName').value.trim();
        const organization = document.getElementById('organization').value.trim();
        const email = document.getElementById('email').value.trim();
        const eventSessionId = document.getElementById('eventSessionId').value.trim();
        const dolzhnost = document.getElementById('dolzhnost').value.trim();
        const inn = document.getElementById('inn').value.trim();

        // Проверка валидности организации
        if (!isOrganizationValid || inn === '') {
            showOrganizationError();
            return;
        }

        // Валидация email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Пожалуйста, введите корректный email адрес');
            return;
        }

        // Получаем UTM-метки
        const urlParams = new URLSearchParams(window.location.search);
        const utmParams = {
            utm_source: urlParams.get('utm_source') || '',
            utm_medium: urlParams.get('utm_medium') || '',
            utm_campaign: urlParams.get('utm_campaign') || '',
            utm_term: urlParams.get('utm_term') || '',
            utm_content: urlParams.get('utm_content') || ''
        };

        try {
            // Показываем индикатор загрузки
            const submitBtn = document.querySelector('.submit-btn');
            const originalBtnText = submitBtn.textContent;
            submitBtn.textContent = 'Отправка...';
            submitBtn.disabled = true;

            const response = await fetch('https://n8n.alean.ru/webhook/aleanlpreg', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    firstName,
                    lastName,
                    organization,
                    inn,
                    email,
                    dolzhnost,
                    eventSessionId,
                    ...utmParams
                })
            });

            // Восстанавливаем кнопку
            submitBtn.textContent = originalBtnText;
            submitBtn.disabled = false;

            if (response.ok) {
                // Плавное скрытие формы и показ сообщения об успехе
                document.getElementById('registrationForm').style.opacity = '0';
                setTimeout(() => {
                    document.getElementById('registrationForm').style.display = 'none';
                    document.getElementById('successMessage').classList.add('active');
                }, 300);
            } else {
                const errorData = await response.json();
                alert('Произошла ошибка при отправке данных: ' + (errorData.message || 'Неизвестная ошибка'));
            }
        } catch (error) {
            alert('Произошла ошибка при отправке данных');
            console.error('Ошибка:', error);
            // Восстанавливаем кнопку в случае ошибки
            const submitBtn = document.querySelector('.submit-btn');
            submitBtn.textContent = 'Подтвердить';
            submitBtn.disabled = false;
        }
    });

    // Функция для передачи параметров URL в ссылки
    function yalTransferParams() {
        var allowed = ["utm_", "yclid", "amo_lead_id"];
        var query = window.location.search;
        if (query.length < 1) return false;

        var referrerParts = query.substring(1).split('&');

        $("a").each(function() {
            var href = $(this).attr("href");
            if (href != undefined) {
                var href_domain = '';
                var href_tmp = href.split(/\/\//);
                if (href_tmp[1] !== undefined) {
                    if (href_tmp[0] == 'http:' || href_tmp[0] == 'https:' || href_tmp[0] == '') {
                        href_domain = href_tmp[1].split('/')[0];
                    }
                }
                if (href.indexOf("#") < 0 && href.toLowerCase().indexOf("tel:") < 0) {
                    referrerParts.forEach(function(value) {
                        var param = value.split('=');
                        if (allowed.some(function(v) { return param[0].indexOf(v) >= 0; }) || allowed.length == 0) {
                            if (!param[1]) param[1] = "";
                            href = yalSetUrlParam(href, param[0], param[1]);
                        }
                    });
                    $(this).attr("href", href);
                }
            }
        });
    }

    function yalSetUrlParam(url, key, value) {
        var key = encodeURIComponent(key),
            value = encodeURIComponent(value);

        var baseUrl = url.split('?')[0],
            newParam = key + '=' + value,
            params = '?' + newParam;

        if (url.split('?')[1] === undefined) {
            urlQueryString = '';
            params = '?' + newParam;
        } else {
            urlQueryString = '?' + url.split('?')[1];
            var updateRegex = new RegExp('([\?&])' + key + '[^&]*');
            var removeRegex = new RegExp('([\?&])' + key + '=[^&;]+[&;]?');
            if (value === undefined || value === null || value === '') {
                params = urlQueryString.replace(removeRegex, "$1");
                params = params.replace(/[&;]$/, "");
            } else if (urlQueryString.match(updateRegex) !== null) {
                params = urlQueryString.replace(updateRegex, "$1" + newParam);
            } else {
                params = urlQueryString + '&' + newParam;
            }
        }

        params = params === '?' ? '' : params;
        return baseUrl + params;
    }

    // Инициализация передачи параметров при загрузке страницы
    $(document).ready(function() {
        yalTransferParams();
    });
</script>