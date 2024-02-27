<?php

/**
 * Class PdoClass
 *
 * @author seungo.jo
 * @since 2019-11-14
 */
class PdoClass
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
            try {
                self::$instance = new PDO(
                    "mysql:host=" . DATABASE['MASTER']['host'] . ";dbname=" . DATABASE['MASTER']['database'] . ";port=" . DATABASE['MASTER']['port'],
                    DATABASE['MASTER']['username'],
                    DATABASE['MASTER']['password'],
                    $opt
                );
                self::$instance->exec("SET CHARACTER SET utf8");
            } catch (PDOException $e) {

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
