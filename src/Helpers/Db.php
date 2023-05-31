<?php

namespace Mt\Wputils\Helpers;


class Db
{
    protected $wpdb;
    protected $query;
    protected $prefix;
    protected $charset;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        $this->prefix = $this->prefix();
        $this->charset = $this->charset();
    }

    public function mysqlManager($sql)
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function prefix()
    {
        return $this->wpdb->prefix;
    }

    public function charset()
    {
        return $this->wpdb->get_charset_collate();
    }

    public function getResults(
        string $table,
        array $fields = ['*'],
        int $limit = null,
        int $offset = 0,
        $where = null,
        $where_params = null
    ) {
        $query = 'SELECT ' . implode(', ', $fields) . ' FROM ' . $this->prefix . $table;

        if (null !== $where) {
            $query .= ' WHERE ' . $where;
        }

        if (null !== $limit && $limit > 0) {
            $query .= ' LIMIT ' . $offset . ', ' . $limit;
        }

        if (null !== $where) {
            return $this->wpdb->get_results(
                $this->wpdb->prepare($query, $where_params)
            );
        }

        return $this->wpdb->get_results($query);
    }

    public function getBy(string $table, string $where, $params, array $fields = ['*'])
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->prefix}{$table} WHERE {$where}",
                $params
            )
        );
    }

    public function insert(string $table, array $data, $format = null)
    {
        if (!is_array($data)) {
            return new \WP_Error('not_array', 'The data must be a array.');
        }

        $insert = $this->wpdb->insert($this->prefix . $table, $data, $format);

        if (!$insert) {
            return $this->wpdb->last_error;
        }

        return $this->lastInsertId();
    }

    public function insertBulk(string $table, array $fields, array $values)
    {
        if (sizeof($fields) <= 0 || sizeof($values) <= 0) {
            return new \WP_Error('error_data', 'The data must be a array.');
        }

        $query = 'INSERT IGNORE INTO ' . $this->prefix . $table . ' (' . implode(',', $fields) . ') VALUES ';
        $vals = [];
        for ($i = 0; $i < count($values); $i++) {
            $data = implode('","', $values[$i]);
            array_push($vals, '("' . $data . '")');
        }

        $query .= implode(',', $vals);
        $insert = $this->wpdb->query($query);

        if (!$insert) {
            return new \WP_Error('insert_error', 'Insert bulk error: ' . json_encode($this->wpdb->last_error));
        }

        return $insert;
    }

    public function update(string $table, array $data, array $where, $format = null, $where_format = null)
    {
        if (!is_array($data)) {
            return new \WP_Error('not_array', 'The data must be a array.');
        }
        $update = $this->wpdb->update($this->prefix . $table, $data, $where, $format, $where_format);

        if (!$update) {
            return new \WP_Error('update_error', 'Update error: ' . json_encode($this->wpdb->print_error()));
        }

        return $update;
    }

    public function delete(string $table, array $where, $where_format = null)
    {
        $delete = $this->wpdb->delete($this->prefix . $table, $where, $where_format);

        if (!$delete) {
            return new \WP_Error('delete_error', 'Delete error: ' . json_encode($this->wpdb->print_error()));
        }

        return $delete;
    }

    public function deleteBulk(string $table, string $key, array $term_ids)
    {
        return $this->query('DELETE FROM ' . $this->prefix . $table . ' WHERE ' . $key . ' IN (' . implode(',', $term_ids) . ')');
    }

    public function count(string $table, string $fields = null, $params = null)
    {
        $query = 'SELECT COUNT(*) FROM ' . $this->prefix . $table;

        if (null !== $fields) {
            $query .=  " WHERE {$fields}";

            return $this->wpdb->get_var(
                $this->wpdb->prepare($query, $params)
            );
        }

        return $this->wpdb->get_var($query);
    }

    public function getByRow(string $query, $where)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare($query, $where)
        );
    }

    public function query(string $query)
    {
        return $this->wpdb->query($query);
    }

    public function search(string $table, string $column, string $value, $format): array
    {
        $like = '%' . $this->wpdb->esc_like($value) . '%';
        $search = $this->wpdb->get_results(
            $this->wpdb->prepare('SELECT * FROM ' . $this->prefix . $table . ' WHERE ' . $column . ' LIKE ' . $format, [$like])
        );

        if (!$search) {
            return [];
        }

        return $search;
    }

    public function lastInsertId()
    {
        return $this->wpdb->insert_id;
    }

    public function createTable(string $table, array $data)
    {
        if (!mtIsAssociativeArray($data)) {
            return new \WP_Error('not_array', 'The data must be a associative array.');
        }

        $fields = mtArrayAssocToString($data);

        $sql = "CREATE TABLE IF NOT EXISTS {$this->prefix}{$table} ({$fields}) {$this->charset};";
        $this->mysqlManager($sql);
    }

    public function deleteTable(array $tables)
    {
        $prefix = $this->prefix;
        $names = implode(', ', array_map(function ($tableName) use ($prefix) {
            return $prefix . $tableName;
        }, $tables));

        $sql = "DROP TABLE IF EXISTS $names";
        $this->wpdb->query($sql);
    }

    public function addRelation(string $table, string $field, string $reference, string $reference_field, string $update = 'NO ACTION', string $delete = 'NO ACTION')
    {
        return  "ALTER TABLE `{$this->prefix}{$table}` ADD FOREIGN KEY (`{$field}`) REFERENCES `{$this->prefix}{$reference}` (`{$reference_field}`) ON DELETE {$delete} ON UPDATE {$update};";
    }

    public function addIndex(string $table, string $index, string $column)
    {
        return $this->query("ALTER TABLE `{$this->prefix}{$table}` ADD INDEX `{$index}` (`{$column}`);");
    }
}
