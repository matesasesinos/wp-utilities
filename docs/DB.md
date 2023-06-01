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

## Uso

**Nota importante**: los nombres de las tablas deben ir sin prefijo, la clase lo agrega

```PHP
//Crear tablas
    $db->createTable('helper_test', [
        'id' => 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY',
        'name' => 'VARCHAR(255) NOT NULL',
        'email' => 'VARCHAR(255) NOT NULL',
        'phone' => 'VARCHAR(255) DEFAULT NULL',
    ]);
    $db->createTable('helper_test_user', [
        'id' => 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY',
        'name' => 'VARCHAR(255) NOT NULL',
        'email' => 'VARCHAR(255) NOT NULL',
        'phone' => 'VARCHAR(255) DEFAULT NULL',
        'helper_test_id' => 'INT NOT NULL',
    ]);
    $db->createTable('helper_test_post', [
        'id' => 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY',
        'name' => 'VARCHAR(255) NOT NULL',
        'email' => 'VARCHAR(255) NOT NULL',
        'phone' => 'VARCHAR(255) DEFAULT NULL',
        'helper_test_id' => 'INT NOT NULL',
    ]);
    

    //Relación 1 a 1
    $relation = $db->addRelation('helper_test_user', 'helper_test_id', 'helper_test', 'id', 'CASCADE');
    $relation .= $db->addRelation('helper_test_post', 'helper_test_id', 'helper_test', 'id', 'CASCADE');
    $db->mysqlManager($relation);

    //Agregar un indice
    $db->addIndex('helper_test', 'name', 'name');

    //Borrar tablas
    $db->deleteTables(['helper_test_user', 'helper_test_post', 'helper_test']);

    //Agregar un registro
    $db->insert('helper_test', [
        'name' => 'test 1',
        'email' => 'XXXXXXXXXXXXX',
        'phone' => 'XXXXXXXXXXXXX',
    ]);

    $db->insert('helper_test', [
        'name' => 'test 2',
        'email' => 'XXXXXXXXXXXXX',
        'phone' => 'XXXXXXXXXXXXX',
    ]);

    $db->insert('helper_test', [
        'name' => 'test 3',
        'email' => 'XXXXXXXXXXXXX',
        'phone' => 'XXXXXXXXXXXXX',
    ]);


    //Insertar muchos registros juntos
    $fields = ['name', 'email', 'phone', 'helper_test_id'];
    $values = [
        [
            'name 1',
            'email 1',
            'phone 1',
            2
        ],
        [
            'name 1',
            'email 1',
            'phone 1',
            3
        ]
    ];

    $db->insertBulk('helper_test_user', $fields, $values);
    $db->insertBulk('helper_test_post', $fields, $values); 

    //Actualizar un registro
    $db->update('helper_test', ['name' => 'test update'], ['id' => 2]);

    //Borrar un registro
    $db->delete('helper_test', ['id' => 1]);

     //Obtener todos los registros
    $result = $db->getResults('helper_test');
    var_dump('getResults ' . json_encode($result));
    //Obtener algunos campos de todos los registors
    $some = $db->getResults('helper_test', ['id', 'name']);
    var_dump('getResults Some ' . json_encode($some));
    //Contar registros
    $count = $db->count('helper_test');
    var_dump('count ' . json_encode($count));
    //Obtener un registro
    $view = $db->getBy('helper_test', 'id=%d', 3);
    var_dump('getBy ' . json_encode($view));
    //Buscar: retorna un array vacio si no encuentra nada
    $search = $db->search('helper_test', 'name', 'test 2', '%s');
    var_dump('search ' . json_encode($search));
    //Query, equivalente a: https://developer.wordpress.org/reference/classes/wpdb/query/
    $query = $db->query("SELECT * FROM " . $db->prefix() . 'helper_test');
    var_dump('query ' . $query);

    //Otros metodos utiles
    $db->prefix(); //Obtener prefijo de la base de datos
    $db->charset(); //Obtener el charset de la base de datos
    $db->mysqlManager($aca_la_consulta); //Ejecuta una consulta, ej: SELECT * FROM table
    $db->deleteBulk('helper_test', 'id', [1,2,3]); //Borrar muchos registros, los paremetros son: nombre_tabla, clave a comparar, valores a comparar que es un array
    $db->queryError(); //retorna un error en la consulta si hay, es bueno para depurar

```
