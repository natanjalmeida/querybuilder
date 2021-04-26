<?php 

    /**
     * Database
     * 
     * This class set PDO connection.
     * 
     * @author Natanael Almeida <natanjalmeida@gmail.com>
     * @link https://github.com/natanjalmeida/querybuilder
     */
    
    namespace App\Libraries\Embryo\PDO;

    use App\Libraries\Embryo\PDO\Connection;

    class Database
    {
        /**
         * @var array $connections
         */
        private $connections = [];

        /**
         * @var array $database
         */
        private $database;

        /**
         * Set database array.
         *
         * @param array $database
         */
        public function __construct(array $database)
        {
            $this->database = $database;
        }

        /**
         * Set PDO connection from database array.
         *
         * @param string $connectionName
         * @return Connection
         * @throws \InvalidArgumentException
         * @throws \PDOException
         */
        public function connection(string $connectionName = 'local'): Connection
        {
            if (!isset($this->database[$connectionName])) {
                throw new \InvalidArgumentException("Database $connectionName doesn't exists.");
            }

            try {

                $database = $this->database[$connectionName];
                $engine   = $database['engine']; 
                $host     = $database['host'];
                $name     = $database['name'];
                $user     = $database['user']; 
                $password = $database['password'];
                $dsn      = $engine.':dbname='.$host.':'.$name;
                
                if (array_key_exists($connectionName, $this->connections)) {
                    return $this->connections[$connectionName];
                }

                $pdo = new \PDO($dsn, $user, $password);
                $connection = new Connection($pdo);
                $this->connections[$connectionName] = $connection;
                return $connection;
                
            } catch (\PDOException $e) {
                throw new \PDOException($e->getMessage().' - '.$dsn);
            }
        }
    }