# Alean Loyalty Payment Plugin

Плагин для интеграции системы лояльности Alean с WooCommerce, позволяющий клиентам оплачивать заказы баллами лояльности.

## Описание

Плагин Alean Loyalty Payment предоставляет полную интеграцию системы лояльности Alean с интернет-магазином на базе WooCommerce. Пользователи могут:

- Просматривать свой баланс баллов лояльности
- Оплачивать заказы баллами
- Получать уведомления о статусе программы лояльности

## Возможности

### Для клиентов:
- Отображение баланса баллов в личном кабинете
- Оплата заказов баллами лояльности
- Проверка достаточности баллов перед оплатой
- Автоматическая валидация баланса

### Для администраторов:
- Настройка API интеграции
- Просмотр логов операций
- Тестирование API соединения
- Управление настройками плагина

## Установка

1. Загрузите файлы плагина в папку `/wp-content/plugins/alean-loyalty-payment/`
2. Активируйте плагин через меню 'Плагины' в WordPress
3. Перейдите в WooCommerce > Настройки > Платежи
4. Настройте методы оплаты "Оплата баллами" и "Alean Loyalty"

## Настройка

### Основные настройки

1. Перейдите в **WooCommerce > Alean Loyalty**
2. Настройте следующие параметры:
   - **API URL**: URL для получения данных лояльности
   - **API Ключ**: Ключ для аутентификации API
   - **Включить плагин**: Активировать/деактивировать плагин

### Настройка платежных методов

1. Перейдите в **WooCommerce > Настройки > Платежи**
2. Найдите методы "Оплата баллами" и "Alean Loyalty"
3. Настройте заголовки и описания методов оплаты
4. Активируйте нужные методы

## API Интеграция

Плагин использует следующие API эндпоинты:

### Получение баланса
```
POST /get-lp-email
Content-Type: application/json

{
  "email": "user@example.com"
}
```

### Списание баллов
```
POST /spend-points
Content-Type: application/json

{
  "email": "user@example.com",
  "amount": 100,
  "order_id": "order_123",
  "comment": "Оплата заказа #123"
}
```

## Структура файлов

```
alean-loyalty-payment/
├── alean-loyalty-payment.php          # Основной файл плагина
├── includes/
│   ├── class-wc-points-payment-gateway.php    # Платежный шлюз для баллов
│   ├── class-wc-alean-loyalty-gateway.php     # Платежный шлюз Alean
│   └── class-alean-loyalty-api.php            # Класс для работы с API
├── assets/
│   ├── js/
│   │   ├── admin.js                   # JavaScript для админки
│   │   └── frontend.js                # JavaScript для фронтенда
│   └── css/
│       ├── admin.css                  # Стили для админки
│       └── frontend.css               # Стили для фронтенда
└── README.md                          # Документация
```

## Хуки и фильтры

### Действия (Actions)

```php
// После успешной оплаты баллами
do_action('alean_loyalty_payment_success', $order_id, $amount, $user_email);

// После неудачной оплаты баллами
do_action('alean_loyalty_payment_failed', $order_id, $error_message);

// При получении баланса пользователя
do_action('alean_loyalty_balance_retrieved', $user_email, $balance_data);
```

### Фильтры (Filters)

```php
// Изменение API URL
add_filter('alean_loyalty_api_url', function($url) {
    return 'https://custom-api.example.com/';
});

// Изменение заголовка метода оплаты
add_filter('alean_loyalty_payment_title', function($title) {
    return 'Мои баллы';
});

// Валидация баланса
add_filter('alean_loyalty_validate_balance', function($is_valid, $balance, $required) {
    return $balance >= $required;
}, 10, 3);
```

## Шорткоды

### Отображение виджета лояльности
```php
[alean_auth]
```

### Отображение баланса баллов
```php
[alean_balance]
```

## Логирование

Плагин ведет подробные логи всех операций в файле `/wp-content/alean-loyalty.log`:

- Успешные платежи
- Ошибки API
- Валидация баланса
- Тестовые операции

## Безопасность

- Все API запросы защищены nonce токенами
- Валидация входных данных
- Проверка прав доступа пользователей
- Безопасное хранение настроек

## Совместимость

- WordPress 5.0+
- WooCommerce 5.0+
- PHP 7.4+
- Совместим с большинством тем

## Поддержка

При возникновении проблем:

1. Проверьте логи в файле `/wp-content/alean-loyalty.log`
2. Убедитесь в правильности настроек API
3. Проверьте совместимость с темой и другими плагинами
4. Обратитесь к документации API Alean

## Версии

### 1.0.0
- Первоначальный релиз
- Базовая интеграция с API Alean
- Два платежных метода
- Админ-панель с настройками
- Логирование операций

## Лицензия

GPL v2 или более поздняя версия

## Авторы

Alean Team