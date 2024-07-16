# API RESTful PHP

Una API RESTful realizado con php puro.

## Genera una key para JWT

Generar con OpenSSL (en terminal Linux/Mac):
```openssl rand -base64 32```

Generar con PHP
Puedes usar el siguiente script PHP para generar una clave secreta:

```PHP
<?php

echo base64_encode(random_bytes(32));
```