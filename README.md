# API RESTful PHP

Una API RESTful realizado con php puro.

## Genera una key para JWT

### Generar con OpenSSL (en terminal Linux/Mac):

```bash
openssl rand -base64 32
```

### Generar con PHP
Puedes usar el siguiente script PHP para generar una clave secreta:

```PHP
<?php

echo base64_encode(random_bytes(32));
```

## Testing

Se uso PHPUnit para los testing, para correr las pruebas usa el siguiente comando

```bash
vendor/bin/phpunit
```