# Миграция с родительской темы Astra

Этот документ содержит инструкции по миграции функциональности из родительской темы Astra в дочернюю тему.

## Что было сделано

### 1. Рефакторинг бонусной программы

**Было в `functions.php`:**
- Все функции бонусной программы были разбросаны по файлу
- Инлайновые вставки JavaScript в PHP
- Смешанная логика в одном файле

**Стало:**
- Вынесено в отдельный класс `Alean_Loyalty_Plugin`
- Созданы отдельные классы для платежных шлюзов
- Разделена логика на модули

### 2. Оптимизация ресурсов

**Было:**
- Инлайновые CSS и JS в `footer.php`
- Неорганизованная структура файлов
- Отсутствие минификации

**Стало:**
- Создан класс `Theme_Assets` для управления ресурсами
- Вынесены все стили в отдельные CSS файлы
- Вынесены все скрипты в отдельные JS файлы
- Добавлена минификация и оптимизация загрузки

### 3. Кастомизация корзины

**Было:**
- Стандартный шаблон WooCommerce
- Отсутствие интеграции с лояльностью

**Стало:**
- Создан кастомный шаблон корзины
- Интеграция с системой лояльности
- Современный адаптивный дизайн

## Пошаговая миграция

### Шаг 1: Активация дочерней темы

1. Скопируйте папку `astra-child` в `/wp-content/themes/`
2. В админ-панели перейдите в "Внешний вид" → "Темы"
3. Активируйте тему "Astra Child"

### Шаг 2: Отключение функциональности родительской темы

В файле `themes/astra/functions.php` закомментируйте или удалите следующие функции:

```php
// Закомментируйте эти функции в родительской теме:

// function replace_money_with_points($price, $product) { ... }
// function add_points_payment_gateway($gateways) { ... }
// function init_points_payment_gateway() { ... }
// function add_user_points_column($columns) { ... }
// function fill_user_points_column($value, $column_name, $user_id) { ... }
// function get_loyalty_data_callback() { ... }
// function handle_bonus_deduction(WP_REST_Request $request) { ... }
// function init_alean_loyalty_gateway() { ... }
// function add_alean_loyalty_gateway($methods) { ... }
// class Alean_Loyalty_WooCommerce { ... }
// class Alean_Loyalty_Payment_Gateway extends WC_Payment_Gateway { ... }
```

### Шаг 3: Очистка footer.php

В файле `themes/astra/footer.php` удалите инлайновые вставки:

```php
// Удалите эти блоки из footer.php:

// <script>
//     // JavaScript код
// </script>

// <style>
//     // CSS код
// </style>
```

### Шаг 4: Настройка API

В файле `themes/astra-child/inc/class-alean-loyalty-plugin.php` настройте реальные API эндпоинты:

```php
private function get_loyalty_data($email) {
    // Замените тестовые данные на реальный API вызов
    $response = wp_remote_get('https://api.alean.ru/loyalty/' . urlencode($email));
    
    if (is_wp_error($response)) {
        return array(
            'lp-status' => 'FALSE',
            'total_points' => 0,
        );
    }
    
    $body = wp_remote_retrieve_body($response);
    return json_decode($body, true);
}

private function spend_loyalty_points($email, $sum, $order_id, $comment) {
    // Замените на реальный API вызов
    $response = wp_remote_post('https://api.alean.ru/loyalty/spend', array(
        'body' => array(
            'email' => $email,
            'amount' => $sum,
            'order_id' => $order_id,
            'comment' => $comment
        )
    ));
    
    return !is_wp_error($response);
}
```

### Шаг 5: Настройка платежных шлюзов

1. Перейдите в WooCommerce → Настройки → Платежи
2. Найдите новые методы оплаты:
   - "Оплата баллами"
   - "Alean Loyalty"
3. Настройте их параметры

### Шаг 6: Проверка функциональности

1. Проверьте работу корзины
2. Проверьте отображение баллов лояльности
3. Проверьте работу платежных шлюзов
4. Проверьте административную панель

## Структура файлов после миграции

```
themes/
├── astra/                    # Родительская тема (очищенная)
│   ├── functions.php         # Без функциональности лояльности
│   ├── footer.php           # Без инлайновых вставок
│   └── ...
└── astra-child/             # Дочерняя тема
    ├── assets/
    │   ├── css/
    │   │   ├── main.css
    │   │   ├── woocommerce.css
    │   │   └── admin.css
    │   └── js/
    │       ├── main.js
    │       ├── loyalty.js
    │       └── admin.js
    ├── inc/
    │   ├── class-alean-loyalty-plugin.php
    │   ├── class-theme-assets.php
    │   ├── class-cart-template.php
    │   └── gateways/
    │       ├── class-wc-points-payment-gateway.php
    │       └── class-wc-alean-loyalty-gateway.php
    ├── template-parts/
    │   └── woocommerce/
    │       └── cart/
    │           └── cart.php
    ├── functions.php
    ├── style.css
    └── README.md
```

## Проверка после миграции

### Фронтенд
- [ ] Корзина отображается корректно
- [ ] Баллы лояльности показываются
- [ ] Платежные шлюзы работают
- [ ] Адаптивность на мобильных устройствах

### Админ-панель
- [ ] Колонка баллов в списке пользователей
- [ ] Настройки платежных шлюзов
- [ ] Управление баллами пользователей

### Производительность
- [ ] CSS и JS файлы загружаются корректно
- [ ] Нет дублирования ресурсов
- [ ] Оптимизация загрузки работает

## Возможные проблемы и решения

### Проблема: Не отображаются баллы лояльности
**Решение:** Проверьте настройки API в `class-alean-loyalty-plugin.php`

### Проблема: Не работает оплата баллами
**Решение:** Убедитесь, что платежные шлюзы активированы в WooCommerce

### Проблема: Стили не применяются
**Решение:** Проверьте, что дочерняя тема активирована и файлы CSS существуют

### Проблема: JavaScript ошибки
**Решение:** Проверьте консоль браузера и убедитесь, что все зависимости подключены

## Откат изменений

Если что-то пошло не так, вы можете:

1. Активировать родительскую тему Astra
2. Восстановить функции в `functions.php`
3. Восстановить инлайновые вставки в `footer.php`

## Дополнительные настройки

### Кастомизация цветов
Отредактируйте CSS переменные в `assets/css/main.css`:

```css
:root {
    --primary-color: #007cba;
    --secondary-color: #005a87;
    /* ... */
}
```

### Добавление новых функций
1. Создайте новый класс в папке `inc/`
2. Подключите его в `functions.php`
3. Добавьте необходимые хуки

### Оптимизация производительности
1. Включите кэширование
2. Настройте минификацию CSS/JS
3. Оптимизируйте изображения