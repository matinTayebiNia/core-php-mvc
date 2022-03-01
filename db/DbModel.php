<?php

namespace app\core\db;

use app\core\Application;
use app\core\Model;

abstract class DbModel extends Model
{
    abstract public static function tableName(): string;

    abstract public function attributes(): array;

    abstract public static function primaryKey():string;

    protected function save(): bool
    {
        try {
            $table = static::tableName();
            $attributes = $this->attributes();
            $columns = implode(",", $attributes);
            $params = join(",", array_map(fn($item) => ":$item", $attributes));
            $sql = "INSERT INTO `{$table}` ({$columns}) VALUES ({$params});";
            $statement = self::prepare($sql);
            foreach ($attributes as $attribute) {
                $statement->bindValue(":$attribute", $this->{$attribute});
            }
            $statement->execute();
            return true;
        } catch (\Exception $exception) {
            throw new \Error($exception->getMessage());
        }
    }

    public static function findOne(array $where)
    {
        $table = static::tableName();
        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn($attr) => "$attr=:$attr", $attributes));
        $statement = self::prepare("SELECT * FROM {$table} WHERE {$sql};");
        $statement->execute($where);
        return $statement->fetchObject(static::class);
    }

    public static function prepare(string $sql): bool|\PDOStatement
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}