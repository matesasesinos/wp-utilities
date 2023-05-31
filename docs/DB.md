# Db class

La clase **Db** es una wrapper a la clase `wpdb` de WordPress: [Ver Docs](https://developer.wordpress.org/reference/classes/wpdb/).

## Cómo utilizarla

En el archivo que necesites hacer una consulta o trabajar con **MySQL**, requerir el namespace primero:

```php
namespace Mt\Wputils\Helpers\Db;
```
Una vez requerida la clase, se debe iniciarlizar con:

```php
new Db();
```

## Metodos

**`getResults`**: este metodo es equivalente a la función [wpdb::get_results](https://developer.wordpress.org/reference/classes/wpdb/get_results/).  
Recibe como parametros:

1. $table: nombre de la tabla (string)
2. $fields: un array de los campos a listar, por defecto trae todos,
3. $limit: númerico, el limite de registros por consulta
4. $offset: el offset de la consulta, número
5. $where: sentencia where de la consulta con datos escapados, string
6. $where_params: los datos sin escapar, este puede ser un string o un array

### Ejemplos getResults()

```php
   //traer todos los datos de una tabla:
   $db->getResults('helper_test'); 
   $db->getResults('helper_test', ['name','phone'], 10, 5);
```
