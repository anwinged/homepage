---
title: Организация доступа к nullable полям класса
description: Заметка о том, лучше организовать доступ к полям класса, которые могут содержать значение null
keywords: [чистый код, php, null, поля класса]
---

Нередкая ситуация, когда в классе есть поле, которое может содержать `null`.

```php
class User
{
    private Email $email;
    private ?string $name;
}
```

Пользователь может указать имя, а может и не указывать,
ограничившись только почтовым адресом.

А далее мы пишем код, которые работает с полем имени.

```php
class User
{
    private ?string $name;

    public function hasName(): bool
    {
        return $this->name !== null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
```

И использование этого кода:

```php
/** @var User $user */

if ($user->hasName()) {
    do_something_with_name($user->getName());
}

function do_something_with_name(string $name) {}
```

Выглядит хорошо.
Сначала убедились, что имя установлено, а потом использовали его.

Но статический анализатор нам обязательно припомнит, что мы пытаемся передать
в функцию `do_something_with_name` значение типа `string|null`, хотя функция
ожидает значение типа `string`.
И получается дурацкая ситуация, что формально мы должны дописать
еще одну проверку.

```php
/** @var User $user */

if ($user->hasName()) {
    $name = $user->getName();
    if ($name !== null) {
        do_something_with_name($name);
    }
}

function do_something_with_name(string $name) {}
```

Статический анализатор наш друг, он помогает находить ошибки и несоответствия
в коде.
И здесь он нашел такое формальное несоответствие типов.

Статический анализатор прав, а мы, как проектировщики интерфейса, не правы.
На самом деле мы смешали два подхода, когда описывали методы в нашем классе:

1. Получить и проверить
2. Проверить и получить

## Получить и проверить

И сразу начнем с примера использования.

```php
$name = $user->getName();
if ($name !== null) {
    do_something_with_name($name);
}
```

Сначала мы получаем значение поля, а потом проверяем, соответствует ли это
значение нашим требованиям. Класс при этом будет построен вот так:

```php
class User
{
    private ?string $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
```

Заметьте, здесь уже нет метода `hasName()`, потому что этот метод перестал быть
нужным. Его роль исполняет метод `getName()`.

## Проверить и получить

Второй подход: сначала проверяем значение, а потом работаем с ним:

```php
if ($user->hasName()) {
    do_something_with_name($user->getName());
}
```

Структура класса:

```php
class User
{
    private ?string $name;

    public function hasName(): bool
    {
        return $this->name !== null;
    }

    public function getName(): string
    {
        if ($this->name === null) {
            throw new \LogicException('Name is not set');
        }

        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
```

Смотрите отличия.
Метод `hasName()` остается.
А вот метод `getName()` теперь возвращает значение типа `string`.
Он выбросит исключение, если мы попытаемся получить значение,
которое не установлено.

## Использование

Теперь встает вопрос, когда и какой подход следует использовать.

-   Если ситуация, когда поле не установлено, скорее исключительная, нежели
    обычная, то можно использовать второй подход, а проверку опустить.
    Исключение в методе `getName()` позволит обнаружить странное поведение.
-   Если в пустом поле нет ничего не обычного, то подход "получить и проверить"
    будет удобнее, все равно нужно делать проверку.

В любом случае, нужно смотреть на уместность того или иного подхода в каждом
случае, и не использовать их одновременно.
