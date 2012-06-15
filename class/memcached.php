<?php
class Memcached
{	
    ////MemCache服务器配置
    ////define('MEMCACHE_HOST', 'localhost'); //MemCache服务器主机
    ////define('MEMCACHE_PORT', 11211); //MemCache服务器端口
    ////define('MEMCACHE_TIMEOUT', 1); //S，MemCache服务器连接超时

    //// 私有静态变量,保存DbConn类唯一实例  
    //private static $instance;  
    //private $host = 'localhost';
    //private $port = '11211';
    //private $memcache_timeout = 1;
    //private $mem;
    //private function __construct(){
    //$this->mem = new Memcache;
    //$this->mem->connect("192.168.1.5", 11211);
    //}
    //// 公有的静态方法
    //public static function getInstance() {  
    //if (!isset(self::$instance)) {  
    //self::$instance = new Application_Model_Memcached();  
    //}  
    //return self::$instance;  
    //}
    //public function set($key,$value,$ttl = 0){
    //return $this->mem->set($key, $value, 0, $ttl);
    //}
    //function get($key){
    //return $this->mem->get($key);
    //}
    //function rm($key)
    //{
    //return $this->mem->delete($key);
    //}
    //function clear()
    //{
    //return $this->mem->flush();
    //}

    //}
/**

 *

 * Setup:

 *

    edit the singleton() metod 

    and define the list of memcached servers in a 2-d array

    in the format

    array(

        array('192.168.0.1'=>'11211'),

        array('192.168.0.2'=>'11211'),

    );

 *

 *

 * Usage:

 *

<?php

//include the class name

include ('memcache.class.php');



//store the variable

Cache::set('key','abc');



//increment/decrement the integer value

Cache::increment('key');

Cache::decrement('key');



//fetch the value by it's key

echo Cache::get('key');





//delete the data

echo Cache::delete('key');



//Clear the cache memory on all servers

Cache::flush();



?>



Cache::replace() and Cache::add are implemented also.



More information can be obtained here:

http://www.danga.com/memcached/

http://www.php.net/memcache



 */



/**

 * The class makes it easier to work with memcached servers and provides hints in the IDE like Zend Studio

 * @author Grigori Kochanov http://www.grik.net/

 * @version 1

 *

 */

    //class Cache {

/**

 * Resources of the opend memcached connections

 * @var array [memcache objects]

 */

    protected $mc_servers = array();

/**

 * Quantity of servers used

 * @var int

 */

    protected $mc_servers_count;



    static $instance;



/**

 * Singleton to call from all other functions

 */

    static function singleton(){

        //Write here where from to get the servers list from, like 

        // global $servers

        $servers = array(
            array('192.168.1.5'=>'11211'),
            //array('192.168.1.10'=>'11211'),

        );



        self::$instance || 

            self::$instance = new Memcached($servers);

        return self::$instance;

    }



/**

 * Accepts the 2-d array with details of memcached servers

 *

 * @param array $servers

 */

    protected function __construct(array $servers){

        if (!$servers){

            trigger_error('No memcache servers to connect',E_USER_WARNING);

        }

        for ($i = 0, $n = count($servers); $i < $n; ++$i){

            ( $con = memcache_pconnect(key($servers[$i]), current($servers[$i])) )&& 

                $this->mc_servers[] = $con;

        }
        $this->mc_servers_count = count($this->mc_servers);

        if (!$this->mc_servers_count){

            $this->mc_servers[0]=null;

        }

    }

/**

 * Returns the resource for the memcache connection

 *

 * @param string $key

 * @return object memcache

 */

    protected function getMemcacheLink($key){

        if ( $this->mc_servers_count <2 ){

            //no servers choice

            return $this->mc_servers[0];
        }
        return $this->mc_servers[(crc32($key) & 0x7fffffff)%$this->mc_servers_count];

    }



/**

 * Clear the cache

 *

 * @return void

 */

    static function flush() {

        $x = self::singleton()->mc_servers_count;

        for ($i = 0; $i < $x; ++$i){

            $a = self::singleton()->mc_servers[$i];

            self::singleton()->mc_servers[$i]->flush();

        }

    }



/**

 * Returns the value stored in the memory by it's key

 *

 * @param string $key

 * @return mix

 */

    static function get($key) {

        return self::singleton()->getMemcacheLink($key)->get($key);

    }



/**

 * Store the value in the memcache memory (overwrite if key exists)

 *

 * @param string $key

 * @param mix $var

 * @param bool $compress

 * @param int $expire (seconds before item expires)

 * @return bool

 */

    static function set($key, $var, $compress=0, $expire=0) {

        return self::singleton()->getMemcacheLink($key)->set($key, $var, $compress?MEMCACHE_COMPRESSED:null, $expire);

    }

/**

 * Set the value in memcache if the value does not exist; returns FALSE if value exists

 *

 * @param sting $key

 * @param mix $var

 * @param bool $compress

 * @param int $expire

 * @return bool

 */

    static function add($key, $var, $compress=0, $expire=0) {

        return self::singleton()->getMemcacheLink($key)->add($key, $var, $compress?MEMCACHE_COMPRESSED:null, $expire);

    }



/**

 * Replace an existing value

 *

 * @param string $key

 * @param mix $var

 * @param bool $compress

 * @param int $expire

 * @return bool

 */

    static function replace($key, $var, $compress=0, $expire=0) {

        return self::singleton()->getMemcacheLink($key)->replace($key, $var, $compress?MEMCACHE_COMPRESSED:null, $expire);

    }

/**

 * Delete a record or set a timeout

 *

 * @param string $key

 * @param int $timeout

 * @return bool

 */

    static function delete($key, $timeout=0) {

        return self::singleton()->getMemcacheLink($key)->delete($key, $timeout);

    }

/**

 * Increment an existing integer value

 *

 * @param string $key

 * @param mix $value

 * @return bool

 */

    static function increment($key, $value=1) {

        return self::singleton()->getMemcacheLink($key)->increment($key, $value);

    }



/**

 * Decrement an existing value

 *

 * @param string $key

 * @param mix $value

 * @return bool

 */

    static function decrement($key, $value=1) {

        return self::singleton()->getMemcacheLink($key)->decrement($key, $value);

    }





    //class end

}
?>
