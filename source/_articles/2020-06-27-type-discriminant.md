---
title: О полях-дискриминаторах
description: Заметка о том, как записывать конфигурацию для сложных объектов
keywords: [чистый код, дискриминатор, php, конфигурация]
---

Поля-дискриминаторы - это удобный прием для обработки нескольких типов
данных со схожей структурой.

Лучше начать с примера.

Допустим, у нас есть объект-фильтр для целых чисел. Можно применить фильтр
к последовательности чисел и получить новую последовательность.
Его параметры выглядят следующим образом:

```json
{
    "from": 0,
    "to": 10
}
```

Отлично.
Фильтр пропустит только числа от 0 до 10.
По такой конфигурации без проблем можно создать объект.

Теперь добавим второй фильтр - он будет отсекать нечетные числа.

```json
{
    "odd": false
}
```

## Создаем фильтр на основе структуры

Теперь есть два фильтра, и нужно понимать какой их них создать.
Простое и наивное решение - смотреть на структуру полей.
Если есть поле `odd`, то фильтр нечетных чисел, иначе - фильтр по диапазону.

```php
if (isset($config['odd'])) {
    // фильтр нечетных чисел
} else {
    // фильтр диапазона
}
```

У этого решения масса недостатков.
Например, если поле `odd` понадобится двум фильтрам сразу, то условие усложнится.
Или появятся поля с разными названиями, но одинаковым смыслом.

## Добавляем поле-дискриминатор

Решение проблемы в добавление специального поля, в котором содержится имя фильтра.
Назовем это поле `type`. Тогда конфигурации фильтров будут выглядеть следующим образом:

```json
{
    "type": "range",
    "from": 0,
    "to": 10
}
```

```json
{
    "type": "odd_control",
    "odd": false
}
```

Поле `type` - это и есть поле-дискриминатор.
По нему можно точно определить какой фильтр перед нами.

```php
switch ($config['type']) {
    case 'range':
        // фильтр диапазона
        break;
    case 'odd_control':
        // фильтр нечетных чисел
        break;
    default:
        throw new \LogicException(sprintf(
            'Unknown filter type "%s"', $config['type']));
}
```

## Выделить зависимые поля

Решение еще можно улучшить.
Сейчас на одном уровне в структуре конфига есть и обязательные поля, и необязательные поля.
Мы можем перенести все необязательные поля на дополнительный уровень,
например, в поле `params`.
Эти поля необязательны для всей конфигурации, но обязательны для конкретного фильтра.

```json
{
    "type": "range",
    "params": {
        "from": 0,
        "to": 10
    }
}
```

Что дает такой маневр?
На верхнем уровне конфигурации у нас будет постоянная структура.
Поле `params` можно до определенного времени рассматривать как черный ящик,
просто зная, что это некий словарь параметров.
Когда будет понятно какой фильтр создавать, тогда этот набор параметров
послужит базой для создания объекта-фильтра.

## Заключение

-   Если структура конфигурации предполагает создание нескольких разных объектов,
    то лучше использовать специальное поле-дискриминатор для точного указания
    типа создаваемого объекта.
-   Все поля, которые зависят от типа, лучше перенести на дополнительный уровень,
    тем самым сохранив структуру верхнего уровня постоянной (на верхнем уровне
    будет всегда один и тот же набор полей).
