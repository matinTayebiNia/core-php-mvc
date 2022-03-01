<?php

namespace app\core\db;


use app\core\Application;

class Database
{
    public \PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = $config["dsn"];
        $user = $config["user"];
        $password = $config["password"];
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $files = scandir(Application::$ROOT_DIR . "/database/migrations");
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        $newMigration = [];
        foreach ($toApplyMigrations as $migration) {
            if ($migration === "." || $migration === "..") {
                continue;
            }
            require_once Application::$ROOT_DIR . "/database/migrations/" . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className;
            $this->log("Applying migration $migration");
            $instance->up();
            $this->log("Applied migration $migration");
            $newMigration[] = $migration;
        }

        if (!empty($newMigration)) {
            $this->saveMigrations($newMigration);
        } else {
            $this->log("nothing to migrate!");
        }
    }

    protected function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migrations VARCHAR(255), 
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )  ENGINE=INNODB;");
    }

    protected function getAppliedMigrations(): bool|array
    {
        $statement = $this->prepare("SELECT migrations FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    protected function saveMigrations(array $migrations)
    {
        $str = $this->convertMigrationsArrayToString($migrations);
        $statement = $this->prepare("INSERT INTO migrations (migrations) VALUES $str");
        $statement->execute();

    }

    public function prepare($sql): bool|\PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

    /**
     * @param array $migrations
     * @return string
     */
    private function convertMigrationsArrayToString(array $migrations): string
    {
        return implode(", ", array_map(fn($m) => "('$m')", $migrations));
    }

    protected function log($message)
    {
        echo "[" . date("Y-m-d H:i:s") . "] - " . $message . PHP_EOL;
    }


}