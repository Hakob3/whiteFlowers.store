<?php

class easyDb
{
    public $database_name;
    public $database_host;
    public $database_user;
    public $database_pass;
    public $debug;
    private $dbh;
    private $initDone = false;
    private $bootQuery = array();
    /** @var Redis $redisInstance */
    private $redisInstance = false;
    private $cacheEnabled = true;
    public $cacheErrorsFatal = false;
    public $pushCache = false;
    public $pconnectDB = false;
    public $pconnectCache = true;

    public $statRedisCalls = 0;
    public $statRedisCallList = [];
    public $statMysqlCalls = 0;

    public $cacheExpire = 0;

    function __construct()
    {
    }

    function no__destruct()
    {
        if ($this->statMysqlCalls > 10 || $this->statRedisCalls > 100) {
            $log = sprintf('easyDb stat mysql: %s redis: %s', number_format($this->statMysqlCalls), number_format($this->statRedisCalls));
            utils::running_score($log, __LINE__, __FILE__);
            utils::debuglog($log . PHP_EOL . var_export($this->statRedisCallList, true) . ';' . PHP_EOL);
        }
        if ($this->dbh && !$this->pconnectDB) {
            mysqli_close($this->dbh);
        }

        if ($this->redisInstance && !$this->pconnectCache) {
            try {
                $this->redisInstance->close();
            } catch (Exception $closeEx) {
            }
        }
    }

    public function bootQuery($q)
    {
        $this->bootQuery[] = $q;
    }

    function currentDateISO($ts = 0)
    {
        if (!$ts) {
            if (defined('CURRENT_TIME')) {
                $ts = CURRENT_TIME;
            } else {
                $ts = time();
            }
        }
        return date('Y-m-d H:i:s', $ts);
    }

    function rows($res)
    {
        return mysqli_num_rows($res);
    }

    function insert_id()
    {
        return mysqli_insert_id($this->dbh);
    }

    function fetch_array($res)
    {
        return mysqli_fetch_array($res);

    }

    function free($res)
    {
        mysqli_free_result($res);
    }

    function escape_by_ref(&$string)
    {
        $string = $this->escapeSql($string);
    }

    function escapeSql($q)
    {
        //if ($this->dbh)            return mysqli_real_escape_string($this->dbh, $q);        else
        return $this->mysql_escape_native($q);
    }

    function mysql_escape_native($qVal)
    {
        return str_replace(['\\', "\0", "\n", "\r", "'", '"', "\x1a"], ['\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'], $qVal);
    }

    function queryArray($q)
    {

        $res = array();
        $qh = $this->query($q);
        while ($tmp = $this->fetch_aarray($qh)) {
            $res[] = $tmp;
        }
        return $res;
    }

    function queryArrayWhere($q, $whereArray, $glue = 'AND')
    {

        return $this->queryArray($q . $this->buildWhereQueryArray($whereArray, $glue));
    }

    function query($query)
    {
        if (!$this->initDone) {
            $this->initDone = true;
            $this->bootstrap();
            foreach ($this->bootQuery as $bq) {
                mysqli_query($this->dbh, $bq);
            }
            if (false) {
                $this->runCacheQueue();
            }
        }
        $this->statMysqlCalls++;

        $qh = false;
        if ($this->dbh) {
            $qh = mysqli_query($this->dbh, $query);
            if ($this->debug && !$qh) {
                $debugStr = $this->debug($query);
                if ($debugStr) {
                    trigger_error($debugStr);
                    if (strpos($debugStr, 'Lock wait timeout exceeded')) {
                        http_response_code(500);
                        exit();
                    }
                }
            }
        }
        return $qh;
    }

    function bootstrap()
    {
        if (!$this->connect()) {
            if (mt_rand(0, 10) == 1) utils::debuglog();
            http_response_code(500);
            trigger_error('Unable connect to database', E_USER_ERROR);
            exit();
        }
    }

    function connection_stats()
    {
        $this->bootstrap();
        return mysqli_get_connection_stats($this->dbh);
    }

    function connect()
    {
        $this->dbh = mysqli_init();
        if (!$this->dbh) {
            return false;
        }
        $host = $this->database_host;
        if ($this->pconnectDB) {
            $host = 'p:' . $host;
        }
        if (!mysqli_real_connect($this->dbh, $host, $this->database_user, $this->database_pass, $this->database_name)) {
            $this->dbh = false;
            return false;
        }
        return true;
    }

    function debug($query)
    {
        $errorno = mysqli_errno($this->dbh);
        if ($errorno && !in_array($errorno, array(1062, 1050, 1060))) {
            return $errorno . mysqli_error($this->dbh) . ' ' . $query;
        }
        return '';
    }

    function fetch_aarray($res)
    {
        return mysqli_fetch_array($res, MYSQLI_ASSOC);

    }

    function queryHash($q, $hashId)
    {

        $res = array();
        $qh = $this->query($q);
        while ($tmp = $this->fetch_aarray($qh)) {
            $hashKey = $tmp[$hashId];
            if (!isset($res[$hashKey])) {
                $res[$hashKey] = array();
            }
            $res[$hashKey][] = $tmp;
        }
        return $res;
    }

    function queryPair($q)
    {
        $res = array();
        $qh = $this->query($q);
        while ($tmp = $this->fetch_row($qh)) {
            $res[$tmp[0]] = $tmp[1];
        }

        return $res;
    }

    function fetch_row($res)
    {
        return mysqli_fetch_row($res);
    }

    function queryPairArray($q)
    {
        $res = array();
        $qh = $this->query($q);
        while ($tmp = $this->fetch_row($qh)) {
            if (!isset($res[$tmp[0]])) {
                $res[$tmp[0]] = array();
            }
            $res[$tmp[0]][] = $tmp[1];
        }

        return $res;
    }

    function querySingle($q)
    {

        $res = array();
        $qh = $this->query($q);
        while ($tmp = $this->fetch_row($qh)) {
            $res[] = $tmp[0];
        }
        return $res;
    }

    function queryHashOne($q, $hashId)
    {

        $res = array();
        $qh = $this->query($q);
        while ($tmp = $this->fetch_aarray($qh)) {
            $hashKey = $tmp[$hashId];
            $res[$hashKey] = $tmp;
        }
        return $res;
    }

    function queryHashOneFirst($q, $hashId)
    {

        $res = array();
        $qh = $this->query($q);
        while ($tmp = $this->fetch_aarray($qh)) {
            $hashKey = $tmp[$hashId];
            if (!isset($res[$hashKey])) {
                $res[$hashKey] = $tmp;
            }
        }
        return $res;
    }

    function queryHashValue($q, $hashId)
    {

        $res = array();
        $qh = $this->query($q);
        while ($tmp = $this->fetch_aarray($qh)) {
            $hashKey = $tmp[$hashId];
            $res[] = $hashKey;
        }
        return $res;
    }

    function doArray($table, $params)
    {

        $q = $this->buildInsertQuery($table, $params);
        $this->query($q);
        return $this->arows();
    }

    function buildInsertQuery($table, $params)
    {

        $q = "INSERT INTO $table " . '(`';
        $q .= implode('`, `', array_keys($params));
        $q .= "`) VALUES(";
        foreach (array_values($params) as $v)
            $q .= $this->prepare('%s', $v) . ', ';
        $q = substr($q, 0, strlen($q) - 2);
        $q .= ")";

        return $q;
    }

    function prepare($query = null)
    { // ( $query, *$args )
        if (is_null($query))
            return false;
        $args = func_get_args();
        array_shift($args);
        // If args were passed as an array (as in vsprintf), move them up
        if (isset($args[0]) && is_array($args[0]))
            $args = $args[0];
        $query = str_replace("'%s'", '%s', $query); // in case someone mistakenly already singlequoted it
        $query = str_replace('"%s"', '%s', $query); // doublequote unquoting
        $query = preg_replace('|(?<!%)%s|', "'%s'", $query); // quote the strings, avoiding escaped strings like %%s
        return @vsprintf($query, array_map([$this, 'escapeSql'], $args));
    }

    function arows()
    {
        return mysqli_affected_rows($this->dbh);
    }

    function doDeleteArray($table, $where)
    {
        if (empty($where)) {
            return false;
        }
        $q = "DELETE FROM $table " . $this->buildWhereQueryArray($where);
        $this->query($q);
        return $this->arows();
    }

    function buildWhereQueryArray($params, $glue = 'AND')
    {
        if (is_array($params) && !empty($params)) {
            return ' WHERE ' . $this->buildSearchQuery($params, $glue);
        } else {
            return ' WHERE 1=0';
        }
    }

    function buildSearchQuery($params, $glue = 'AND')
    {
        $qSearch = array();
        foreach ($params as $k => $v) {
            $qSearch[] = sprintf('`%s`=%s', $k, $this->prepare('%s', $v));
        }
        return implode(" $glue ", $qSearch);
    }

    function doDuplicateArray($table, $params, $updateParams = array())
    {

        $q = $this->buildInsertQuery($table, $params) .
            ' ON DUPLICATE KEY UPDATE ' . $this->buildParamSetQuery((empty($updateParams) ? $params : $updateParams));
        $this->query($q);
        return $this->arows();
    }

    function doDuplicateSql($table, $params, $updateQuery)
    {

        $q = $this->buildInsertQuery($table, $params) . ' ON DUPLICATE KEY UPDATE ' . $updateQuery;
        $this->query($q);
        return $this->arows();
    }

    function buildParamSetQuery($params)
    {
        $q = '';
        foreach ($params as $k => $v) {
            $q .= "`$k`=" . $this->prepare('%s', $v) . ', ';
        }
        $q = substr($q, 0, strlen($q) - 2);
        return $q;
    }

    function doUpdateArray($table, $params, $where)
    {
        $q = $this->buildUpdateQuery($table, $params) . " WHERE $where";

        $this->query($q);
        return $this->arows();
    }

    function buildUpdateQuery($table, $params)
    {
        $q = 'UPDATE ' . $table . ' SET ';
        $q .= $this->buildParamSetQuery($params);
        return $q;
    }

    function buildIncrementQuery($table, $params)
    {
        $q = 'UPDATE ' . $table . ' SET ';
        foreach ($params as $k => $v) {
            $q .= "`$k`=`$k`+" . $this->prepare('1*%s', $v) . ', ';
        }
        $q = substr($q, 0, strlen($q) - 2);
        return $q;
    }

    function doUpdateArray2($table, $params, array $where)
    {
        $q = $this->buildUpdateQuery($table, $params) . $this->buildWhereQueryArray($where);
//        if(isset($_GET['sdfsdfsdffsfsfd'])){
//            die($q);
//        }

        $this->query($q);
        return $this->arows();
    }

    function doIncrementArray($table, $params, array $where)
    {
        $q = $this->buildIncrementQuery($table, $params) . $this->buildWhereQueryArray($where);
        $this->query($q);
    }

    function doIncrementUpdate($table, $params, array $where, array $initParams = array())
    {
        $this->doIncrementArray($table, $params, $where);
        if (1 > $this->arows()) {
            $this->doArray($table, array_merge($initParams, $params, $where));
        }
        return $this->arows();
    }

    function collectIncrementUpdate($table, $params, array $where)
    {
        $this->cacheCall('rPush', 'DBINCR', [
            'table' => $table,
            'params' => $params,
            'where' => $where,
        ]);
    }

    function aggregateIncrementUpdate($limit = 50000)
    {
        $incrementData = [];
        $this->cacheCreateLock('DBINCR.lock', 159, function () use ($limit, &$incrementData) {
            //utils::running_score('aggregateIncrementUpdate begin', __LINE__, __FILE__);
            $queueLen = intval($this->cacheCall('lLen', 'DBINCR'));
            $redCount = 0;
            $akeys = [];
            while ($limit >= 0 && $d = $this->cacheCall('lPop', 'DBINCR')) {
                $this->aggregateIncrementData($d, $incrementData, $akeys);
                $limit--;
                $redCount++;
            }
            if ($queueLen) {
                utils::running_score(sprintf('aggregateIncrementUpdate done: %s before: %s after: %s keys: %s', number_format($redCount), number_format($queueLen), number_format(intval($this->cacheCall('lLen', 'DBINCR'))), utils::JSONEncode($akeys)), __LINE__, __FILE__);
                $queryCount = 0;
                foreach ($incrementData as $tbl => $tblData) {
                    foreach ($tblData as $d) {
                        db::getInstance()->doIncrementUpdate($tbl, $d['params'], $d['where']);
                        $queryCount++;
                    }
                }
                utils::running_score(sprintf('aggregateIncrementUpdate query: %s done', number_format($queryCount)), __LINE__, __FILE__);
            }
        });
        return $incrementData;
    }

    function collectOfflineIncrement($table, $params, array $where)
    {
        $ts = time();
        file_put_contents(CACHE_PATH . sprintf('offlinedbincr-%s.datalog', date('YmdHis', $ts)), utils::JSONEncode([
                'time' => $ts,
                'table' => $table,
                'params' => $params,
                'where' => $where,
            ]) . PHP_EOL, FILE_APPEND);
    }


    function aggregateIncrementData($d, &$incrementData, &$akeys)
    {
        ini_set('memory_limit', '20G');
        if (empty($d['where']) || empty($d['table'])) {
            utils::running_score('aggregateIncrementData' . var_export($d, true), __LINE__, __FILE__);
            return;
        }
        $sortWhere = $d['where'];
        ksort($sortWhere);
        $key = http_build_query($sortWhere);
        $tbl = $d['table'];
        if (!isset($incrementData[$tbl])) {
            $incrementData[$tbl] = [];
        }
        isset($akeys[$tbl]) ? $akeys[$tbl]++ : $akeys[$tbl] = 1;
        foreach ($d['params'] as $k => $v) {
            if (!isset($incrementData[$tbl][$key])) {
                $incrementData[$tbl][$key] = ['params' => [], 'where' => $d['where']];
            }
            if (!isset($incrementData[$tbl][$key]['params'][$k])) {
                $incrementData[$tbl][$key]['params'][$k] = 0;
            }
            $incrementData[$tbl][$key]['params'][$k] += $v;
        }
    }

    function aggregateOfflineIncrement($fileLimit = 1000)
    {
        $incrementData = [];
        $redCount = $fileCount = 0;
        utils::criticalSection();
        utils::running_score('');
        $dataLogs = glob(CACHE_PATH . 'offlinedbincr-*.datalog');
        $akeys = [];
        foreach ($dataLogs as $dataLog) {
            $tempDataLog = CACHE_PATH . utils::generatePassword(32) . md5(mt_rand()) . '.dlogtmp';
            rename($dataLog, $tempDataLog);
            $log = fopen($tempDataLog, 'r');
            if (!$log) continue;
            while (!feof($log)) {
                $d = rtrim(fgets($log, 10000));
                if (!$d) continue;
                $d = utils::JSONDecode($d);
                $this->aggregateIncrementData($d, $incrementData, $akeys);
                $redCount++;
            }
            $fileCount++;
            fclose($log);
            unlink($tempDataLog);
            if ($fileCount >= $fileLimit) {
                break;
            }
        }
        utils::running_score(sprintf('aggregateOfflineIncrement total: %s done: %s rows: %s keys: %s', number_format(count($dataLogs)), number_format($fileCount), number_format($redCount), utils::JSONEncode($akeys)), __LINE__, __FILE__);
        utils::json_headers($incrementData);
    }

    function LastID()
    {
        return mysqli_insert_id($this->dbh);
    }

    function fetch($res)
    {
        return mysqli_fetch_row($res);
    }

    function buildWhereQuery($params, $glue = 'AND')
    {
        if (is_array($params) && !empty($params)) {
            return ' WHERE ' . implode(" $glue ", $params);
        }
        return '';
    }

    function buildInQuery($params)
    {

        $qInSearch = array();
        if (is_array($params) && !empty($params)) {
            foreach ($params as $p) {
                $qInSearch[] = $this->prepare('%s', $p);
            }
        } else {
            $qInSearch[] = 'NULL';
        }
        return ' IN (' . implode(', ', $qInSearch) . ')';
    }

    function parseTypeEnum($table, $row, $joinkeys = false)
    {

        $enumDesc = $this->queryOne(sprintf('desc `%s` `%s`', $table, $row), false);
        $enumCols = explode(',', $enumDesc['Type']);
        $enumCols[0] = preg_replace('/^enum\(/', '', $enumCols[0]);
        $enumCols[count($enumCols) - 1] = preg_replace('/\)$/', '', $enumCols[count($enumCols) - 1]);
        array_walk($enumCols, function (&$v) {
            $v = trim($v, "'");
        });

        if ($joinkeys) {
            $joinedCol = array();
            foreach ($enumCols as $v) {
                $joinedCol[$v] = $v;
            }
            return $joinedCol;
        }
        return $enumCols;
    }

    function queryOne($q, $addlimit = true)
    {

        if ($addlimit)
            $q .= ' LIMIT 1';
        $qh = $this->query($q);
        $res = $this->fetch_aarray($qh);

        return $res;
    }

    function cacheRedisConnect()
    {
        if (!$this->cacheEnabled) {
            return false;
        }
        if (!class_exists('Redis')) {
            return false;
        }
        if (!$this->redisInstance) {
            $this->redisInstance = new Redis();
            if ($this->pconnectCache) {
                $conn = $this->redisInstance->pconnect('10.133.93.6', 6379, 0.3);
            } else {
                $conn = $this->redisInstance->connect('10.133.93.6', 6379, 0.3);
            }
            if (!$conn) {
                $this->redisInstance = false;
                $this->cacheEnabled = false;
                //utils::debuglog();
                return false;
            }
            if (!$this->redisInstance->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP)) {
                $this->redisInstance = false;
                return false;
            }
        }

        return true;
    }

    function cacheCall($method/**, @args */)
    {
        if (!$this->cacheRedisConnect()) {
            return false;
        }
        $this->statRedisCalls++;
        $methodParameter = func_get_args();
        //$this->statRedisCallList[] = json_encode($methodParameter);
        array_shift($methodParameter);
        try {
            $callStatus = call_user_func_array(array($this->redisInstance, $method), $methodParameter);
        } catch (Exception $e) {
            $exMsg = $e->getMessage();
            $errType = E_USER_NOTICE;
            if ($this->cacheErrorsFatal) {
                $errType = E_USER_ERROR;
            }
            if (!$this->pushCache) {
                if ('read error on connection' == $exMsg) {
                    //$errType = E_USER_ERROR;
                }
            }
            trigger_error(sprintf('Redis Exception: \'%s\' %s %s load: %s', $exMsg, $method, utils::JSONEncode($methodParameter), utils::sys_getloadavg()), $errType);
            $callStatus = false;
        }
        //utils::test_runtime_score($method . ': ' . json_encode($methodParameter));
        return $callStatus;
    }

    function cacheGet($key)
    {
        return $this->cacheCall('get', $key);
    }

    function cacheSet($key, $value, $seconds = 0)
    {
        if (!empty($key)) {
            if ($this->pushCache) {
                return $this->cacheCall('setex', $key, $seconds, $value);
            } else {
                return $this->cacheCall('set', $key, $value, ['NX', 'EX' => $seconds]);
            }
        }
        return false;
    }

    function cacheGetOrSet($key, callable $valueFunction, $seconds = 0)
    {
        $cacheVal = false;
        if (!$this->pushCache) {
            $cacheVal = $this->cacheGet($key);
        };
        if (false === $cacheVal) {
            $cacheVal = $valueFunction();
            $this->cacheSet($key, $cacheVal, $seconds);
        }
        return $cacheVal;
    }

    function cacheQuery($methodName /*, $methodParameter, ...*/)
    {

        if (!$this->cacheExpire) {
            $this->cacheExpire = mt_rand(370, 390);
        }

        $methodParameter = func_get_args();

        $lastElement = end($methodParameter);
        if(isset($lastElement['cacheExpire']) ){
            $this->cacheExpire = $lastElement['cacheExpire'];
            array_pop($methodParameter);
        }


        array_shift($methodParameter);
        $cacheKey = sprintf('DBCACHE-%s-%s', $methodName, md5(implode(',', $methodParameter)));
        $methodResult = false;
        if (!$this->pushCache) {
            $methodResult = $this->cacheGet($cacheKey);
        }
        if (false === $methodResult) {
            $methodResult = $this->cacheCallMethod($methodName, $methodParameter);
            if (false !== $methodResult) {
                $this->cacheSet($cacheKey, $methodResult, $this->cacheExpire);
            }
        }
        return $methodResult;
    }

    function cacheQueue($methodName/*, $methodParameter, ...*/)
    {
        $methodParameter = func_get_args();
        array_shift($methodParameter);
        $callStatus = $this->cacheCall('rPush', 'DBQUEUE', array('method' => $methodName, 'methodParameter' => $methodParameter));
        if (!$callStatus) {
            $callStatus = $this->cacheCallMethod($methodName, $methodParameter);
        }
        return $callStatus;
    }

    function runCacheQueue($limit = 36)
    {
        $queueLen = false;
        $this->cacheCreateLock('DBQUEUE.lock', 60, function () use ($limit) {
            $queueLen = intval($this->cacheCall('lLen', 'DBQUEUE'));
            $akeys = [];
            while ($limit >= 0 && $qq = $this->cacheCall('lPop', 'DBQUEUE')) {
                isset($akeys[$qq['methodParameter'][0]]) ? $akeys[$qq['methodParameter'][0]]++ : $akeys[$qq['methodParameter'][0]] = 1;
                $this->cacheCallMethod($qq['method'], $qq['methodParameter']);
                $limit--;
            }
            if ($queueLen) {
                utils::running_score(sprintf('runCacheQueue total: %s after: %s keys: %s', number_format($queueLen), number_format(intval($this->cacheCall('lLen', 'DBQUEUE'))), utils::JSONEncode($akeys)), __LINE__, __FILE__);
            }
        });
        return $queueLen;
    }

    function cacheCallMethod($method, $methodParameter = array())
    {
        return call_user_func_array(array($this, $method), $methodParameter);
    }

    function cacheCreateLock($lockKey, $seconds, callable $callback)
    {
        if (!$this->cacheGet($lockKey)) {
            $this->cacheSet($lockKey, '1', $seconds);
            $callback();
            if (!$seconds) {
                $this->cacheCall('del', $lockKey);
            }
        }
    }

    function phpCacheGet($key)
    {
        $keyHash = md5($key);
        $phpCodeFile = sprintf('%sphp_c.%s.php', CACHE_PATH, $keyHash);
        if (file_exists($phpCodeFile)) {
            /** @noinspection PhpIncludeInspection */
            require $phpCodeFile;
            $varname = 'CACHE_CODE_' . $keyHash;
            $varVal = $$varname;
            return $varVal;
        }
        return null;
    }

    function phpCacheSet($key, $value, $seconds = 0)
    {
        $keyHash = md5($key);
        $phpCodeFile = sprintf('%sphp_c.%s.php', CACHE_PATH, $keyHash);
        $phpCode = sprintf('<?php /* key %s expire %s */ $%s=%s;', var_export($key, true), CURRENT_TIME + $seconds, 'CACHE_CODE_' . $keyHash, var_export($value, true));
        file_put_contents($phpCodeFile . '.new', $phpCode . PHP_EOL);
        rename($phpCodeFile . '.new', $phpCodeFile);
        if (file_exists('opcache_invalidate')) {
            opcache_invalidate($phpCodeFile);
        }
    }

    function phpCacheSetEx($key, $value, $seconds = 0)
    {
        if ($this->phpCacheGet($key) !== $value) {
            $this->phpCacheSet($key, $value, $seconds);
        }
    }
    function shmCacheSet($key, $value, $seconds = 0)
    {
    }
    function shmCacheGet($key)
    {
    }
}

class photo_db extends easyDb
{
    private static $instance;
    private $cacheConfig;

    public function __construct()
    {
        $this->database_host = 'localhost';
        $this->database_name = 'srv27879_flowers';
        $this->database_user = 'srv27879_studio';
        $this->database_pass = 'qwe123';
        parent::__construct();
        $this->bootQuery('SET sql_mode = \'NO_AUTO_VALUE_ON_ZERO\'');
        $this->bootQuery('SET NAMES utf8mb4');
        $this->bootQuery('SET CHARACTER SET utf8mb4');
        $this->bootQuery('SET collation_connection=utf8mb4_general_ci');
        $this->debug = true;
        $this->cacheConfig = null;
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function setCacheConfig($cacheConfig)
    {
        $this->cacheConfig = $cacheConfig;
    }

    function getConfig($k = null)
    {
        $qConfig = 'SELECT configKey, configValue, configDesc FROM config_config';
        if (is_null($k)) {
            return $this->queryHashOne($qConfig, 'configKey');
        } else {
            return $this->queryOne($qConfig . $this->prepare(' WHERE configKey=%s', $k), 'configKey');
        }

    }

    public function getConfigNs($ns)
    {

        $ns = $ns . '.';
        $ns = db::getInstance()->escapeSql($ns);
        $qConfig = "SELECT configKey, configValue, configDesc FROM config_config WHERE configKey LIKE '$ns%'";
        return $this->queryHashOne($qConfig, 'configKey');
    }

    function getConfigKey($k = null, $noCache = false)
    {

        $qConfig = 'SELECT configKey, configValue FROM config_config';
        if (is_null($this->cacheConfig)) {
            $this->cacheConfig = $this->cacheQuery('queryPair', $qConfig);
        }
        if (is_null($k)) {
            return $this->cacheConfig;
        }

        if (array_key_exists($k, $this->cacheConfig)) {
            if ($noCache) {
                $dbConfigValue = $this->queryOne($qConfig . $this->prepare(' WHERE configKey=%s', $k));
                $cacheConfig[$k] = utils::getVar($dbConfigValue, $k);
            }
            return $this->cacheConfig[$k];
        }

        return null;
    }

    function defineConfigKeys($keys)
    {
        foreach ($keys as $configKey => $configDesc) {
            $val = $this->getConfigKey($configKey);
            if (is_null($val)) {
                $qConfig = array('configValue' => '', 'configKey' => $configKey, 'configDesc' => $configDesc);
                $this->doArray('config_config', $qConfig);
            }
        }
    }

    function setConfigKey($k, $v, $autoCreate = false)
    {

        $updateConfig = array('configValue' => $v);
        $this->doUpdateArray('config_config', $updateConfig, $this->prepare('configKey=%s', $k));
        $this->cacheConfig = null;
        $this->pushCache = true;
        if ($this->arows()) {
        } elseif ($autoCreate) {
            $updateConfig['configKey'] = $k;
            $this->doArray('config_config', $updateConfig);
        }
        return $this->getConfigKey($k);
    }

    function trashConfigKey($k)
    {
        if ($k) {
            $this->doDeleteArray('config_config', array('configKey' => $k));
            unset($this->cacheConfig[$k]);
        }
    }

    function getConfigFromCache()
    {
        $appConfig = $this->phpCacheGet('appConfig');
        if (empty($appConfig)) {
            trigger_error('appConfig is empty', E_USER_ERROR);
        }
        $this->setCacheConfig($appConfig);
    }

    private function __clone()
    {
    }

}

