<?php 

    /**
     * Database
     * 
     * This class actives query builder from table or query.
     * 
     * @author Natanael Almeida <natanjalmeida@gmail.com>
     * @link https://github.com/natanjalmeida/querybuilder
     */

    namespace Natanjalmeida\QueryBuilder;

    use Natanjalmeida\QueryBuilder\QueryBuilder\QueryBuilder;
    use Natanjalmeida\QueryBuilder\Query;

    class Connection 
    {
        /**
         * @var \PDO $pdo
         */
        private $pdo;
        
        /**
         * Set PDO connection.
         *
         * @param \PDO $pdo
         */
        public function __construct(\PDO $pdo)
        {
            $this->pdo = $pdo;
        }

        /**
         * Set query builder from table.
         *
         * @param string $table
         * @return QueryBuilder
         */
        public function table(string $table): QueryBuilder
        {
            return new QueryBuilder($this->pdo, $table);
        }

        /**
         * Set query from string
         *
         * @param string $query
         * @return Query
         */
        public function query(string $query): Query
        {
            return (new Query($this->pdo))->query($query);
        }

        /**
         * Transaction
         * 
         * @param \Closure $callback
         * @return mixed
         * @throws \PDOException
         */
        public function transaction(\Closure $callback)
        {
            $callback = \Closure::bind($callback, $this);
            try {

                $this->pdo->beginTransaction();
                $result = $callback();
                $this->pdo->commit();
                return $result;

            } catch (\PDOException $e) {
                
                $this->pdo->rollback();
                throw new \PDOException($e->getMessage());
                
            }
        }
    }