<?php


namespace App\Core;


use App\Models\User;

abstract class DatabaseModel extends Model {

    public static abstract function getTableName(): string;
    public abstract function getAttributes(): array;
    public static abstract function getPrimaryKey(): string;

    protected Database $db;

    public function __construct(Application $app) {
        parent::__construct($app);
        $this->db = $app->db;
    }

    public function save(): bool {
        $tableName = static::getTableName();
        $attrs = $this->getAttributes();
        $params = array_map(fn($attr) => ":$attr", $attrs);
        $this->db->query("INSERT INTO $tableName (" . implode(',', $attrs) . ") VALUES (" . implode(',', $params) .")");
        foreach ($attrs as $attr) {
            $values[":$attr"] = [
                'value' => $this->{$attr}
            ];
        }
        $this->db->bindAll($values);
        return $this->db->execute();
    }

    public function findOne(array $params) {
        $tableName = User::getTableName();
        $attrs = array_keys($params);
        $selectAttrs = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attrs));
        $this->db->query("SELECT * FROM $tableName WHERE $selectAttrs");
        foreach ($params as $key => $val) {
            $values[":$key"] = [
                'value' => $val
            ];
        }
        $this->db->bindAll($values);
        return $this->db->fetchObject(User::class);
    }

}