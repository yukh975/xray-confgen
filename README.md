# VLESS → xray-core config generator

Веб-инструмент для генерации `config.json` для [xray-core](https://github.com/XTLS/Xray-core) из VLESS-ссылки.

A web tool for generating `config.json` for [xray-core](https://github.com/XTLS/Xray-core) from a VLESS link.

---

## Описание / Description

### RU

Позволяет вставить VLESS-ссылку, задать параметры SOCKS5 inbound и настроить правила маршрутизации — и получить готовый `config.json`, который можно скачать или скопировать.

**Поддерживаемые транспорты:**
- TCP + Reality
- TCP + TLS
- XHTTP (SplitHTTP) + Reality / TLS
- WebSocket + TLS
- gRPC + TLS / Reality
- HTTP/2 + TLS

**Функционал:**
- Автоматическое определение типа транспорта и безопасности из VLESS URI
- SOCKS5 inbound с настраиваемым IP и портом (по умолчанию `10808`)
- Секция маршрутизации с динамическими правилами
- Выбор баз геоданных (`geosite.dat`, `geoip.dat` и пользовательские)
- Загрузка категорий прямо из `.dat` файлов (protobuf-парсер на PHP)
- Поиск по категориям с возможностью добавить кастомное значение
- Сохранение состояния формы в `localStorage`
- Скачивание и копирование результата

---

### EN

Paste a VLESS link, configure the SOCKS5 inbound parameters and routing rules — and get a ready-to-use `config.json` to download or copy.

**Supported transports:**
- TCP + Reality
- TCP + TLS
- XHTTP (SplitHTTP) + Reality / TLS
- WebSocket + TLS
- gRPC + TLS / Reality
- HTTP/2 + TLS

**Features:**
- Auto-detection of transport type and security from VLESS URI
- SOCKS5 inbound with configurable IP and port (default `10808`)
- Routing section with dynamic rules
- Geo database selector (`geosite.dat`, `geoip.dat` and custom files)
- Categories loaded directly from `.dat` files (PHP protobuf parser)
- Category search with ability to add custom values
- Form state persistence via `localStorage`
- Download and copy result

---

## Требования / Requirements

- Nginx + PHP-FPM (PHP 8.1+)
- `.dat` файлы геобаз в директории `db/`

---

## Установка / Installation

```bash
git clone https://github.com/yukh975/vless-parser.git
cd vless-parser
```

Положи `.dat` файлы в директорию `db/`:

Place `.dat` files into the `db/` directory:

```
vless-parser/
└── db/
    ├── geosite.dat
    ├── geoip.dat
    └── geosite_RU.dat   # опционально / optional
```

Настрой nginx:

Configure nginx:

```nginx
server {
    listen 80;
    root /path/to/vless-parser;
    index index.html;

    location /api/ {
        fastcgi_pass unix:/run/php/php-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

Если путь к `db/` отличается — измени константу `GEO_DIR` в файлах `api/tags.php` и `api/databases.php`.

If your `db/` path differs — update the `GEO_DIR` constant in `api/tags.php` and `api/databases.php`.

---

## Структура проекта / Project structure

```
vless-parser/
├── index.html              # Веб-интерфейс / Web interface
├── assets/
│   ├── app.js              # Логика фронтенда / Frontend logic
│   └── style.css           # Стили / Styles
├── api/
│   ├── parse.php           # Парсинг VLESS URI + генерация config.json
│   ├── tags.php            # Чтение категорий из .dat файлов
│   └── databases.php       # Список доступных баз геоданных
└── db/
    └── *.dat               # Файлы геобаз / Geo database files
```

---

## API

### `POST /api/parse.php`

Генерирует `config.json`.

**Тело запроса / Request body:**
```json
{
  "inbound_ip": "0.0.0.0",
  "inbound_port": 10808,
  "vless_link": "vless://uuid@host:port?...",
  "routing_rules": [
    { "rule_type": "ip",     "db": "geoip.dat",   "values": ["private"], "action": "direct" },
    { "rule_type": "domain", "db": "geosite.dat",  "values": ["ru"],      "action": "direct" }
  ]
}
```

### `GET /api/tags.php?db=geosite.dat`

Возвращает список категорий из указанного `.dat` файла.

Returns the list of categories from the specified `.dat` file.

### `GET /api/databases.php`

Возвращает список доступных `.dat` файлов из директории `db/`.

Returns the list of available `.dat` files from the `db/` directory.

---

## Лицензия / License

MIT
