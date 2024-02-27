<?php

/**
 * Class DB
 *
 * @author seungo.jo
 * @since 20220608
 */
class DB
{
    
    protected static $instance;
    
    protected $pdo;
    
    /**
     * PdoClass constructor.
     */
    protected function __construct(){}
    
    /**
     *
     */
    protected function __clone(){}
    
    /**
     * @return PDO
     */
    public static function instance()
    {
        if (is_null(self::$instance) === true) {
            $opt = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $config = [
                'host' => '172.31.125.113',
                'username' => 'smbit_ctc_exch',
                'password' => '1234',
                'database' => 'exchange_db',
                'port' => 3306
            ];
            try {
                self::$instance = new PDO(
                    "mysql:host=" . $config['host'] . ";dbname=" . $config['database'] . ";port=" . $config['port'],
                    $config['username'],
                    $config['password'],
                    $opt
                );
            } catch (Exception $e) {
                header("HTTP/1.0 500 Internal Server Error");
                die;
            }
        }
        
        return self::$instance;
    }
    
    /**
     * @param $method
     * @param $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        return call_user_func_array([self::instance(), $method], $args);
    }
}
