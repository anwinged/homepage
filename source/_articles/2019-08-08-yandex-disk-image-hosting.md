---
title: Яндекс.Диск для хостинга картинок
keywords: [яндекс.диск, хостинг картинок, yandex disk, image hosting, hosting]
---

У [Яндекс.Диска](ya-disk) есть замечательная функция. Он может создавать превью
загруженных фотографий. Эта функциональность не афишируется, но описана
в [документации](ya-api-preview).

У меня есть фотография на Диске `/img/kemsky.jpg`. Чтобы получить ее превью,
нужно выполнить запрос:

```
GET /img/kemsky.jpg?preview&size=XS
User-Agent: my_application/0.0.1
Host: webdav.yandex.ru
Authorization: OAuth 0c4182a7c2cf4521964a72ff57a34a07
```

Но есть проблема. Для запросов нужен токен. Без токена не получится использовать
это API для публичного хостинга.

Решение - сервер [Caddy](caddy) в качестве прокси. Caddy очень
удобно использовать в качестве фронтенда для внутренних сервисов.
Он просто настраивается, а самое главное - поддерживает автоматический
выпуск и обновление SSL-сертификатов буквально одной строчкой конфига.
Скроем токен в конфигурации сервера, и будем передавать его при обращении
к Яндекс.Диску:

```
preview.vakhrushev.me {
    proxy /img https://webdav.yandex.ru {
        transparent
        header_upstream User-Agent "yandex-disk-previewer/1.0"
        header_upstream Authorization "OAuth 0c4182a7c2cf4521964a72ff57a34a07"
    }

    tls anwinged@ya.ru
}
```

Директива `proxy /img` будет направлять все запросы с `preview.vakhrushev.me/img`
на `https://webdav.yandex.ru/img`. Таким образом во внешний
мир будет смотреть только директория `img`, а остальные останутся скрытыми.

Кроме OAuth авторизации можно использовать Basic, передавая логин и
[пароль приложения](app-password). Мне этот способ удобнее,
чтобы не заморачиваться с OAuth. Логин и пароль я храню
зашифрованными с помощью [Ansible Vault](vault).
И строчка с заголовком тогда будет выглядеть так:

```
header_upstream Authorization "Basic {{ '{{' }} (yandex_disk.login ~ ':' ~ yandex_disk.password) | b64encode {{ '}}' }}"
```

А так будет выглядеть ссылка на картинку:

```
https://preview.vakhrushev.me/img/kemsky.jpg?preview&size=XXL
```

![Кемский поселок](https://preview.vakhrushev.me/img/kemsky.jpg?preview&size=XXL)

[ya-disk]: https://disk.yandex.ru
[ya-api]: https://yandex.ru/dev/disk/doc/dg/concepts/quickstart-docpage/
[ya-api-preview]: https://yandex.ru/dev/disk/doc/dg/reference/preview-docpage/
[caddy]: https://caddyserver.com/
[app-password]: https://yandex.ru/support/passport/authorization/app-passwords.html
