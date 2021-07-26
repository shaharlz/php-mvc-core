<?php


namespace App\Core;


use \PDO;
use \PDOStatement;

class Database {

    private PDO $conn;
    private PDOStatement $stmt;
    protected Application $app;

    public function __construct(Application $app, array $configs) {
        $this->app = $app;
        $dsn = $configs['dsn'];
        $user = $configs['user'];
        $password = $configs['password'];
        $options = array(
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        try {
            $this->conn = new PDO($dsn, $user, $password, $options);
        } catch (\PDOException $e) {
            print_r($e->errorInfo);
        }

    }

    //Query the db
    public function query(string $sql) {
        $this->stmt = $this->conn->prepare($sql);
    }

    //Bind the params
    public function bind(string|int $param, mixed $val, int $type = null): bool {
        switch (is_null($type)) {
            case is_int($val):
                $type = PDO::PARAM_INT;
                break;

            case is_bool($val):
                $type = PDO::PARAM_BOOL;
                break;

            case is_null($val):
                $type = PDO::PARAM_NULL;
                break;

            default:
                $type = PDO::PARAM_STR;
                break;
        }
        return $this->stmt->bindValue($param, $val, $type);
    }

    public function bindAll($params): bool {
        foreach ($params as $param => $data) {
            if (!$this->bind($param, $data['value'], $data['type'] ?? null))
                return false;
        }
        return true;
    }

    //execute the prepared stmt
    public function execute(): bool {
        return $this->stmt->execute();
    }

    //return the row count
    public function rowCount() {
        return $this->stmt->rowCount();
    }

    //return a specific row as an object
    public function single($fetchMode = PDO::FETCH_OBJ) {
        $this->execute();
        return $this->stmt->fetch($fetchMode);
    }

    //return the last inserted id
    public function getLastInsertId() {
        return $this->conn->lastInsertId();
    }

    //return specific as an object of the specified class
    public function fetchObject(string $class) {
        $this->execute();
        return $this->stmt->fetchObject($class, [$this->app]);
    }

}