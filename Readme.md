# Info

Este "paquete" es una utilidad para creación de plugins y themes en WordPress, son un grupo de clases re utilizables, para evitar crear código de más. También ayuda a buenas practicas de desarrollo.

A diferencia de WordPress, este paquete sigue el estandar **PSR-12** para el estilo de código: [PSR-12: Extended Coding Style](https://www.php-fig.org/psr/psr-12/)

Esta versión tiene compatibilidad con **PHP >=7.4** por lo que no se declaran tipos estrictos por ejemplo.

## Cómo utilizar

Cree un proyecto de **composer** con `composer init`, una vez creado el archivo `composer.json`, agregue lo siguiente:

```json
"repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/matesasesinos/wp-utilities"
    }
  ]
```

```json
"require": {
    "mt/wputils": "dev-dev"
  }
```

Una vez hecho esto, correr `composer dump -o`.

## Indice documentación

[Db Class](docs/DB.md)