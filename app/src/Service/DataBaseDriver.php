<?php

namespace App\Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Configuration;

class DataBaseDriver
{
    private ?Connection $connection;
    /**
     * @var array|string[]
     */
    private array $configTempDBData;

    /**
     * @param string $host
     * @param string $port
     * @param string $dbname
     * @param string $user
     * @param string $password
     */
    public function __construct(
        string $host,
        string $port,
        string $dbname,
        string $user,
        string $password,
    )
    {
        $this->configTempDBData = [
            'driver' => 'pdo_mysql',
            'host' => $host,
            'port' => $port,
            'dbname' => $dbname,
            'user' => $user,
            'password' => 'ovPhqOJrUKMuC6B',
        ];
    }


    /**
     * @throws Exception
     */
    public function prepareTempDataBase(): void
    {
        $this->connection = DriverManager::getConnection($this->configTempDBData, new Configuration());
    }

    /**
     * @throws ConnectionException
     * @throws Exception
     */
    public function fullFillTempDBWithData(string $sqlFilePath): void
    {
        if (!$this->connection) {
            throw new ConnectionException('Connection not establish prepare database first');
        }
        $sqlContent = file_get_contents($sqlFilePath);
        $this->connection->executeStatement($sqlContent);
    }

    /**
     * @throws Exception
     */
    public function getDataFromTempDB(string $table, string $contentColumn, string $titleColumn,)
    {
        return $this->connection->executeQuery("SELECT $contentColumn,$titleColumn FROM $table")->fetchAllAssociative();
    }

    public function rollBackToStartInstance(): void
    {
        //TODO in future it's not required in task
        return;
    }
}