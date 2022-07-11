<?php

class utils{

static function getFileVar($a, $b)
{
    $c = str_replace('..', '', self::getAsciiVar($a, $b));
    return strlen($c) > 0 ? $c : null;
}

static function getAsciiVar($a, $b)
{
    return preg_replace('/[^(\x20-\x7F)]*/', '', self::getVar($a, $b));
}

static function getLatinVar($a, $b)
{
    return preg_replace('/[^a-zA-Z]*/', '', self::getVar($a, $b));
}

static function getVar($a, $b, $trim = true)
{
    if (isset($a[$b])) {
        if ($trim && !is_array($a[$b])) {
            return trim($a[$b]);
        } else {
            return $a[$b];
        }
    }
    return null;
}
static function file_post_http(string $filename, $content = '', $connectTimeout = 10, $headers = [])
{

    $hostParse = parse_url($filename);
    if (!empty($hostParse['host'])) {
        $headers['Host'] = $hostParse['host'];
    }
    $headers['Content-Length'] = strlen($content);
    $headers['User-Agent'] = 'GuzzleHttp/6.2.1 curl/7.29.0 PHP/7.1.12';
    $httpHeaders = [];
    foreach ($headers as $k => $v) {
        $httpHeaders[] = sprintf('%s: %s', $k, $v);
    }
    $ret = @file_get_contents($filename, false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => implode("\r\n", $httpHeaders),
            'content' => $content,
            'timeout' => $connectTimeout,
        ], 'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
            'verify_depth' => 0
        ],
    ]));
    return $ret;
}
static function getCheckboxVar($a, $b)
{
    return (self::getVar($a, $b) == 'on' ? 1 : 0);
}
static function getCountryVar($a, $b)
{
    $code = strtoupper(substr(self::getAsciiVar($a, $b), 0, 2));
    $allCountryList = self::getCountriesList();
    if (isset($allCountryList[$code])) {
        return $code;
    }
    return 'XX';
}
static function getCountriesList()
{
    return array('0' => 'All countries', 'AD' => 'Andorra', 'AE' => 'United Arab Emirates', 'AF' => 'Afghanistan', 'AG' => 'Antigua and Barbuda',
        'AI' => 'Anguilla', 'AL' => 'Albania', 'AM' => 'Armenia', 'AO' => 'Angola', 'AP' => 'Asia/Pacific Region', 'AQ' => 'Antarctica', 'AR' => 'Argentina',
        'AS' => 'American Samoa', 'AT' => 'Austria', 'AU' => 'Australia', 'AW' => 'Aruba', 'AX' => 'Aland Islands', 'AZ' => 'Azerbaijan', 'BA' => 'Bosnia and Herzegovina',
        'BB' => 'Barbados', 'BD' => 'Bangladesh', 'BE' => 'Belgium', 'BF' => 'Burkina Faso', 'BG' => 'Bulgaria', 'BH' => 'Bahrain',
        'BI' => 'Burundi', 'BJ' => 'Benin', 'BL' => 'Saint Bartelemey', 'BM' => 'Bermuda', 'BN' => 'Brunei Darussalam',
        'BO' => 'Bolivia', 'BQ' => 'Bonaire, Saint Eustatius and Saba', 'BR' => 'Brazil', 'BS' => 'Bahamas', 'BT' => 'Bhutan', 'BV' => 'Bouvet Island', 'BW' => 'Botswana',
        'BY' => 'Belarus', 'BZ' => 'Belize', 'CA' => 'Canada', 'CC' => 'Cocos (Keeling) Islands',
        'CD' => 'Congo, The Democratic Republic of the', 'CF' => 'Central African Republic', 'CG' => 'Congo', 'CH' => 'Switzerland', 'CI' => 'Cote d\'Ivoire',
        'CK' => 'Cook Islands', 'CL' => 'Chile', 'CM' => 'Cameroon', 'CN' => 'China', 'CO' => 'Colombia', 'CR' => 'Costa Rica', 'CU' => 'Cuba', 'CV' => 'Cape Verde',
        'CW' => 'Curacao', 'CX' => 'Christmas Island', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'DE' => 'Germany', 'DJ' => 'Djibouti', 'DK' => 'Denmark', 'DM' => 'Dominica',
        'DO' => 'Dominican Republic', 'DZ' => 'Algeria', 'EC' => 'Ecuador', 'EE' => 'Estonia', 'EG' => 'Egypt', 'EH' => 'Western Sahara', 'ER' => 'Eritrea', 'ES' => 'Spain', 'ET' => 'Ethiopia',
        'EU' => 'Europe', 'FI' => 'Finland', 'FJ' => 'Fiji', 'FK' => 'Falkland Islands (Malvinas)', 'FM' => 'Micronesia, Federated States of', 'FO' => 'Faroe Islands',
        'FR' => 'France', 'GA' => 'Gabon', 'GB' => 'United Kingdom', 'GD' => 'Grenada', 'GE' => 'Georgia', 'GF' => 'French Guiana', 'GG' => 'Guernsey', 'GH' => 'Ghana',
        'GI' => 'Gibraltar', 'GL' => 'Greenland', 'GM' => 'Gambia', 'GN' => 'Guinea', 'GP' => 'Guadeloupe', 'GQ' => 'Equatorial Guinea', 'GR' => 'Greece',
        'GS' => 'South Georgia and the South Sandwich Islands', 'GT' => 'Guatemala', 'GU' => 'Guam', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HK' => 'Hong Kong',
        'HM' => 'Heard Island and McDonald Islands', 'HN' => 'Honduras', 'HR' => 'Croatia', 'HT' => 'Haiti', 'HU' => 'Hungary', 'ID' => 'Indonesia', 'IE' => 'Ireland',
        'IL' => 'Israel', 'IM' => 'Isle of Man', 'IN' => 'India', 'IO' => 'British Indian Ocean Territory', 'IQ' => 'Iraq', 'IR' => 'Iran',
        'IS' => 'Iceland', 'IT' => 'Italy', 'JE' => 'Jersey', 'JM' => 'Jamaica', 'JO' => 'Jordan', 'JP' => 'Japan', 'KE' => 'Kenya', 'KG' => 'Kyrgyzstan', 'KH' => 'Cambodia', 'KI' => 'Kiribati', 'KM' => 'Comoros',
        'KN' => 'Saint Kitts and Nevis', 'KP' => 'Korea, Democratic People\'s Republic of', 'KR' => 'Korea', 'KW' => 'Kuwait', 'KY' => 'Cayman Islands',
        'KZ' => 'Kazakhstan', 'LA' => 'Lao People\'s Democratic Republic',
        'LB' => 'Lebanon', 'LC' => 'Saint Lucia', 'LI' => 'Liechtenstein', 'LK' => 'Sri Lanka', 'LR' => 'Liberia', 'LS' => 'Lesotho', 'LT' => 'Lithuania', 'LU' => 'Luxembourg',
        'LV' => 'Latvia', 'LY' => 'Libyan Arab Jamahiriya', 'MA' => 'Morocco', 'MC' => 'Monaco', 'MD' => 'Moldova', 'ME' => 'Montenegro', 'MF' => 'Saint Martin', 'MG' => 'Madagascar',
        'MH' => 'Marshall Islands', 'MK' => 'Macedonia', 'ML' => 'Mali', 'MM' => 'Myanmar', 'MN' => 'Mongolia', 'MO' => 'Macao', 'MP' => 'Northern Mariana Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania',
        'MS' => 'Montserrat', 'MT' => 'Malta', 'MU' => 'Mauritius', 'MV' => 'Maldives', 'MW' => 'Malawi', 'MX' => 'Mexico', 'MY' => 'Malaysia', 'MZ' => 'Mozambique', 'NA' => 'Namibia', 'NC' => 'New Caledonia',
        'NE' => 'Niger', 'NF' => 'Norfolk Island', 'NG' => 'Nigeria', 'NI' => 'Nicaragua', 'NL' => 'Netherlands', 'NO' => 'Norway', 'NP' => 'Nepal', 'NR' => 'Nauru', 'NU' => 'Niue', 'NZ' => 'New Zealand',
        'OM' => 'Oman', 'PA' => 'Panama', 'PE' => 'Peru', 'PF' => 'French Polynesia', 'PG' => 'Papua New Guinea', 'PH' => 'Philippines', 'PK' => 'Pakistan', 'PL' => 'Poland', 'PM' => 'Saint Pierre and Miquelon',
        'PN' => 'Pitcairn', 'PR' => 'Puerto Rico', 'PS' => 'Palestinian Territory', 'PT' => 'Portugal', 'PW' => 'Palau', 'PY' => 'Paraguay', 'QA' => 'Qatar', 'RE' => 'Reunion', 'RO' => 'Romania', 'RS' => 'Serbia',
        'RU' => 'Russia', 'RW' => 'Rwanda', 'SA' => 'Saudi Arabia', 'SB' => 'Solomon Islands', 'SC' => 'Seychelles', 'SD' => 'Sudan', 'SE' => 'Sweden', 'SG' => 'Singapore', 'SH' => 'Saint Helena',
        'SI' => 'Slovenia', 'SJ' => 'Svalbard and Jan Mayen', 'SK' => 'Slovakia', 'SL' => 'Sierra Leone', 'SM' => 'San Marino', 'SN' => 'Senegal', 'SO' => 'Somalia', 'SR' => 'Suriname', 'SS' => 'South Sudan',
        'ST' => 'Sao Tome and Principe', 'SV' => 'El Salvador', 'SX' => 'Sint Maarten', 'SY' => 'Syria', 'SZ' => 'Swaziland', 'TC' => 'Turks and Caicos Islands', 'TD' => 'Chad',
        'TF' => 'French Southern Territories', 'TG' => 'Togo', 'TH' => 'Thailand', 'TJ' => 'Tajikistan', 'TK' => 'Tokelau', 'TL' => 'Timor-Leste', 'TM' => 'Turkmenistan', 'TN' => 'Tunisia', 'TO' => 'Tonga',
        'TR' => 'Turkey', 'TT' => 'Trinidad and Tobago', 'TV' => 'Tuvalu', 'TW' => 'Taiwan', 'TZ' => 'Tanzania', 'UA' => 'Ukraine', 'UG' => 'Uganda', 'UM' => 'United States Minor Outlying Islands',
        'US' => 'United States', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VA' => 'Holy See (Vatican City State)', 'VC' => 'Saint Vincent and the Grenadines', 'VE' => 'Venezuela', 'VG' => 'Virgin Islands, British',
        'VI' => 'Virgin Islands, U.S.',
        'VN' => 'Vietnam', 'VU' => 'Vanuatu', 'WF' => 'Wallis and Futuna', 'WS' => 'Samoa',
        'XK' => 'Kosovo',
        'YE' => 'Yemen', 'YT' => 'Mayotte', 'ZA' => 'South Africa', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe',
    );
}


static function getCountryListVar($a, $b)
{
    $r = [];
    $countryCodeList = array_unique(self::getDelimVarArray($a, $b, 'all'));
    $allCountryList = getCountriesList();
    foreach ($countryCodeList as $cCode) {
        $cCode = strtoupper($cCode);
        if (array_key_exists($cCode, $allCountryList)) {
            $r[] = $cCode;
        }
    }
    return $r;
}

static function getUuidVar($a, $b)
{
    $c = self::getVar($a, $b);
    $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
    if ($c && 1 === preg_match($UUIDv4, $c)) {
        return $c;
    }
    return null;
}

static function getDelimVarArray($a, $b, $d = ',')
{
    $k = self::getVar($a, $b);
    return self::parseDelimArray($k, $d);
}

static function parseDelimArray($k, $d)
{
    if ('' === $k) {
        return [];
    }
    $v = array();
    if ($d == 'all') {
        $v = preg_split('/[\s,;]+/', $k);
    } else {
        $a = preg_replace('/\s+/u', '', $k);
        if ('' !== $a) {
            $v = explode($d, $a);
        }
    }
    $r = [];
    foreach ($v as $rv) {
        if ('' !== $rv) {
            $r[] = $rv;
        }
    }

    return $r;
}
static function getDelimVarArrayCleanStr($a, $b, $d = ',')
{
    return implode($d, self::getDelimVarArray($a, $b, $d));
}

static function getDefaultVar($a, $b, $c = '', $trim = true)
{
    $v = self::getVar($a, $b, $trim);
    if (!$v) return $c;
    return $v;
}

static function getCleanVar($a, $b)
{
    return self::escapeHtml(self::getVar($a, $b));
}

static function escapeHtml($a)
{
    return htmlspecialchars(self::utf8($a), ENT_QUOTES, 'UTF-8', true);
}

static function utf8($value)
{
    if (is_string($value) && !mb_detect_encoding($value, 'UTF-8', true)) {
        return mb_convert_encoding($value, 'UTF-8');
    }

    return $value;
}

static function getIntVar($a, $b)
{
    return intval(self::getVar($a, $b));
}

static function getBinVar($a, $b)
{
    return (self::getIntVar($a, $b) ? 1 : 0);
}

static function getJsVar($a, $b)
{
    return self::escapeJs(self::getVar($a, $b));
}

static function escapeJs($a)
{
    return utils::JSONEncode(self::utf8($a));
}

static function getAction()
{
    return ("GET" == $_SERVER["REQUEST_METHOD"]);
}

static function postAction($csrf_check = false)
{
    $postAction = ("POST" == $_SERVER["REQUEST_METHOD"]);
    if ($postAction) {
        if ($csrf_check && !self::check_csrf_token()) {
            return false;
        }
    }
    return $postAction;
}

static function getDateTimeVar($a, $b, $ts = 0)
{
    $date = self::getVar($a, $b);
    if (!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $date)) {
        if (!$ts) {
            $ts = CURRENT_TIME;
        }
        $date = date('Y-m-d H:i:s', $ts);
    }
    return $date;
}
static function getDateVar($a, $b, $ts = 0)
{
    $date = self::getVar($a, $b);
    if (!$ts) {
        $ts = CURRENT_TIME;
    }
    switch ($date) {
        case 'today':
            $date = date('Y-m-d', CURRENT_TIME);
            break;
        default:
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                $date = date('Y-m-d', $ts);
            }
    }
    return $date;
}
static function setcookie_r($name, $value, $expire = 0, $domain = null, $secure = null, $httponly = false)
{
    return setcookie($name, $value, $expire, '/', $domain, $secure, $httponly);
}
static function debuglog($log = '')
{
    ob_start();
    debug_print_backtrace();
    $trace = ob_get_contents();
    ob_end_clean();
    $traceArray = explode(PHP_EOL, trim($trace));
    $traceArray[0] = $log;
    //if (strpos($trace, $GLOBALS['cookieNamePop']) === false)
    file_put_contents(CACHE_PATH . 'debug.log', PHP_EOL . date('[Y-m-d H:i:s] ') . utils::getAsciiVar($_SERVER, 'HTTP_REFERER') . PHP_EOL . implode(PHP_EOL, array_reverse($traceArray)) . PHP_EOL, FILE_APPEND);
}
static function session_log()
{
    global $eSession;
    $logFile = CACHE_PATH . 'auth.log';
    $post = $_POST;
    if (isset($post['b'])) {
        $post['b'] = '***';
    }
    $log = '-----REQUEST BEGIN-----' . PHP_EOL;
    $log .= sprintf('[%s] %s %s %s %s' . PHP_EOL, date('Y-m-d H:i:s'), $_SERVER['GEOIP_COUNTRY_CODE'], $_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_METHOD'], self::getCurrentUrl());
    $log .= sprintf('$_GET %s' . PHP_EOL, self::JSONEncode($_GET));
    $log .= sprintf('$_POST %s' . PHP_EOL, self::JSONEncode($post));
    $log .= sprintf('$_COOKIE %s' . PHP_EOL, self::JSONEncode($_COOKIE));
    $log .= sprintf('$_SESSION %s' . PHP_EOL, self::JSONEncode($_SESSION));
    $log .= sprintf('REFERER %s' . PHP_EOL, self::getVar($_SERVER, 'HTTP_REFERER'));
    $log .= sprintf('AGENT %s' . PHP_EOL, self::getVar($_SERVER, 'HTTP_USER_AGENT'));
    $log .= '-----REQUEST END-----' . PHP_EOL;
    file_put_contents($logFile, $log . PHP_EOL, FILE_APPEND);
}

static function running_score($log = '', $line = 0, $file = '', $output = 'php')
{
    global $test;

    static $uniqId = 0;
    static $startTime = 0;
    static $scriptLastTime = 0;

    $mi = microtime(true);
    if (0 === $startTime) {
        $startTime = $mi;
    }
    if (0 === $uniqId) {
        $uniqId = substr(md5($mi), 0, 6);
    }
    if (0 === $scriptLastTime) {
        $lap = $diff = 0;
        $scriptLastTime = $startTime;
    } else {
        $lap = $mi - $startTime;
        $diff = $mi - $scriptLastTime;
        $scriptLastTime = $mi;
    }
    if ($file) {
        $line = str_replace('.php', '', basename($file)) . ':' . $line;
    }
    $logformat = 'mini';
    if ($output != 'php') {
        $logformat = 'full';
    }
    $log = trim($log);
    if ($log) {
        $logStr = '';
        if ($test) $logStr .= 'TesT ';
        $logHeader = '';
        if ('cli' === php_sapi_name()) {
            $logStr = sprintf('>PHP [%s] ', date('Y-m-d H:i:s'));
        } else {
            if ($logformat == 'full') {
                $logStr = sprintf('[%s] ', date('Y-m-d H:i:s'));
                $logHeader = sprintf('%s %s ref: %s req: %s', utils::getVar($_SERVER, 'GEOIP_COUNTRY_CODE'), utils::getVar($_SERVER, 'REMOTE_ADDR'), utils::getVar($_SERVER, 'HTTP_REFERER'), utils::getVar($_SERVER, 'REQUEST_URI')) . PHP_EOL;
            }
        }
        $logStr .= sprintf('%s %03.02f %.4f[+%.4f] %s %s %s', $uniqId, utils::sys_getloadavg(), $lap, $diff, $line, $logHeader, $log);
        if ($output == 'php') {
            if ('cli' === php_sapi_name()) {
                echo $logStr . PHP_EOL;
            } else {
                foreach (utils::split_string($logStr) as $log) trigger_error($log);
            }
        } else {
            file_put_contents($output, $logStr . PHP_EOL, FILE_APPEND);
        }
    }
}

static function cache_headers($cachdExpireSec = 59)
{
    $mtime = CURRENT_TIME;
    $gmdate_mod = gmdate('D, d M Y H:i:00', $mtime) . ' GMT';

    /*    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            $if_modified_since = preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE']);
        } else {
            $if_modified_since = '';
        }
        if ($if_modified_since == $gmdate_mod) {
            http_response_code(304);
            exit;
        }*/

    self::header_array([
        'Content-Type' => 'text/html; charset=UTF-8',
        'Last-Modified' => $gmdate_mod,
        'Cache-Control' => sprintf('max-age=%d, public', $cachdExpireSec),
        'Expires' => gmdate('D, d M Y H:i:s', $mtime + $cachdExpireSec) . ' GMT',
        'Pragma' => 'cache',
    ]);
}

static function nocache_headers()
{
    self::header_array([
        'Expires' => '0',
        'Cache-Control' => 'private, no-cache, must-revalidate, no-store, max-age=0',
        'Pragma' => 'no-cache',
        'Content-Type' => 'text/html; charset=UTF-8'
    ]);
}

static function header_array($headers)
{
    foreach ($headers as $name => $field_value) {
        header(sprintf('%s: %s', $name, $field_value));
    }
}

static function criticalSection($sectionStarts = true, $timeLimit = true)
{
    if ($timeLimit) {
        if ($sectionStarts) {
            set_time_limit(0);
        } else {
            set_time_limit(ini_get('max_execution_time'));
        }
    }
    ignore_user_abort($sectionStarts);
}


static function redirect_headers($url, $code = 200, $headerOnly = false){
if ($code != 200) {
    header("Location: $url", true, $code);
} else {
    header("Location: $url");
}
if ($headerOnly) {
    exit;
}
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="robots" content="noarchive, noindex, nofollow">
    <meta http-equiv="refresh" content="0;URL='<?php echo $url; ?>'"/>
</head>
<body>
</body>
</html><?php

exit;
}

static function redirect_tohttps()
{
    if (strtolower(utils::getVar($_SERVER, 'HTTPS')) != 'on') {
        utils::redirect_headers(self::getCurrentUrl());
    }
}

static function getCurrentUrl()
{
    $phpself = str_replace('info.php', '', $_SERVER['PHP_SELF']);
    $queryString = '';
    if (!empty($_SERVER['QUERY_STRING'])) {
        $queryString = '?' . http_build_query($_GET);
    }
    return sprintf("https://%s%s%s", self::getAsciiVar($_SERVER, 'HTTP_HOST'), $phpself, $queryString);
}

static function json_headers($data, $andExit = true)
{
    $json = utils::JSONEncode($data);
    header('Content-type: text/json');
    header(sprintf('Content-Length: %d', strlen($json)));
    print($json);
    if ($andExit) {
        exit;
    }
}

static function xml_headers($rawXml)
{

    header('Content-type: text/xml');
    header(sprintf('Content-Length: %d', strlen($rawXml)));
    print($rawXml);
    exit;
}

static function start_session()
{
    if (isset($_SERVER['REMOTE_ADDR'])) {
        session_start();
        if (!isset($_SESSION['SESSION_IP'])) {
            $_SESSION['SESSION_IP'] = $_SERVER['REMOTE_ADDR'];
        } else if ($_SESSION['SESSION_IP'] != $_SERVER['REMOTE_ADDR']) {
            self::destory_session();
            utils::redirect_headers('/ent.php');
        }
        if (!isset($_SESSION['SESSION_CREATED'])) {
            $_SESSION['SESSION_CREATED'] = CURRENT_TIME;
        } else if (CURRENT_TIME - $_SESSION['SESSION_CREATED'] > 1800) {
            session_regenerate_id(true);
            $_SESSION['SESSION_CREATED'] = CURRENT_TIME;
        }
        utils::init_csrf_token();
    }
}
static function destory_session()
{
    $_SESSION = array();
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', CURRENT_TIME - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}

static function test_print_pre($v)
{
    if (!empty($GLOBALS['test'])) {
        self::print_pre($v);
    }
}

static function print_pre($v)
{
    echo '<pre>' . utils::escapeHtml(var_export($v, true)) . '</pre>';
}

static function split_string($s, $limit = 1000)
{
    $splitS = [$s];
    if (is_string($s) && strlen($s) > $limit) {
        $splitS = str_split($s, $limit);
    }
    return $splitS;
}
static function split_jsval($s)
{
    $splitS = array($s);
    if (is_string($s) && strlen($s) > 6) {
        $splitS = str_split($s, mt_rand(2, 4));
    }

    return implode(' + ', array_map('json_encode', $splitS));
}
static function generatePassword($len = 12)
{
    if (!$len) {
        $len = 8;
    }
    $chars = str_repeat(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), mt_rand(4, 16));
    return substr(str_shuffle($chars), 0, $len);
}

static function cryptPassword($a, $salt = '')
{
    $p = self::pbkdf2('sha512', $a, $salt, 99);
    if (!$p) {
        $p = hash('sha512', $a . $salt);
    }
    return $p;
}

/**
 * Implementation of the PBKDF2 key derivation function as described in
 * RFC 2898.
 *
 * @param string $PRF Hash algorithm.
 * @param string $P Password.
 * @param string $S Salt.
 * @param int $c Iteration count.
 * @param mixed $dkLen Derived key length (in octets). If $dkLen is FALSE
 *                     then length will be set to $PRF output length (in
 *                     octets).
 * @param bool $raw_output When set to TRUE, outputs raw binary data. FALSE
 *                         outputs lowercase hexits.
 * @return mixed Derived key or FALSE if $dkLen > (2^32 - 1) * hLen (hLen
 *               denotes the length in octets of $PRF output).
 */
static function pbkdf2($PRF, $P, $S, $c, $dkLen = false, $raw_output = false)
{
    //default $hLen is $PRF output length
    $hLen = strlen(hash($PRF, '', true));
    if ($dkLen === false) $dkLen = $hLen;

    if ($dkLen <= (pow(2, 32) - 1) * $hLen) {
        $DK = '';

        //create key
        for ($block = 1; $block <= $dkLen; $block++) {
            //initial hash for this block
            $ib = $h = hash_hmac($PRF, $S . pack('N', $block), $P, true);

            //perform block iterations
            for ($i = 1; $i < $c; $i++) {
                $ib ^= ($h = hash_hmac($PRF, $h, $P, true));
            }

            //append iterated block
            $DK .= $ib;
        }

        $DK = substr($DK, 0, $dkLen);
        if (!$raw_output) $DK = bin2hex($DK);

        return $DK;

        //derived key too long
    } else {
        return false;
    }
}
static function finish_request()
{
    if (function_exists('fastcgi_finish_request')) {
        if (isset($_SESSION)) {
            session_write_close();
        }
        fastcgi_finish_request();
    }

}

static function count_in_array($needle = array(), $haystack = array())
{
    $in_array_count = 0;
    foreach ($needle as $n) {
        if (in_array($n, $haystack)) {
            $in_array_count++;
        }
    }
    return $in_array_count;
}
static function all_in_array($needle = array(), $haystack = array())
{
    return (count($needle) == self::count_in_array($needle, $haystack));
}
static function any_in_array($needle = array(), $haystack = array())
{
    foreach ($needle as $n) {
        $key = array_search($n, $haystack);
        if ($key !== false) {
            return $key;
        }
    }
    return false;
}
static function strpos_array($haystack, array $needle)
{
    foreach ($needle as $value) {
        if (false !== strpos($haystack, $value)) {
            return true;
        }
    }
    return false;
}

static function random_key($array)
{
    $keys = array_keys($array);
    return $keys[mt_rand(0, count($keys) - 1)];
}
static function random_value($array)
{
    $values = array_values($array);
    return $values[mt_rand(0, count($values) - 1)];
}
static function shuffle_assoc($array)
{
    $shuffled_array = array();
    $shuffled_keys = array_keys($array);
    shuffle($shuffled_keys);
    foreach ($shuffled_keys as $shuffled_key) {
        $shuffled_array[$shuffled_key] = $array[$shuffled_key];
    }
    return $shuffled_array;
}
static function array_copyvals($array)
{
    $joinedCol = [];
    foreach ($array as $v) {
        $joinedCol[$v] = $v;
    }
    return $joinedCol;
}

static function array_inrement_key(&$array, $key, $step = 1)
{
    if (!isset($array[$key])) {
        $array[$key] += $step;
    }
    $array[$key]++;
}

static protected $aesKey = 'UTILS::AES';

static function aes128Decrypt($key, $data)
{
    if (16 !== strlen($key)) $key = hash('MD5', $key, true);
    $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, str_repeat("\0", 16));
    $padding = ord($data[strlen($data) - 1]);
    return substr($data, 0, -$padding);
}

static function aes128Encrypt($key, $data)
{
    if (16 !== strlen($key)) $key = hash('MD5', $key, true);
    $padding = 16 - (strlen($data) % 16);
    $data .= str_repeat(chr($padding), $padding);
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, str_repeat("\0", 16)));
}

static function aes256Decrypt($key, $data, $iv)
{
    if (16 !== strlen($key)) $key = hash('MD5', $key, true);
    $data = openssl_decrypt(base64_decode($data), 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return $data;
}

static function aes256Encrypt($key, $data, $iv)
{
    if (16 !== strlen($key)) $key = hash('MD5', $key, true);
    return base64_encode(openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv));
}

static function AESDecrypt($data)
{
    $gz = @gzinflate(self::aes128Decrypt(self::$aesKey, base64_decode($data)));
    if (empty($gz)) {
        //utils::test_runtime_score($_SERVER['GEOIP_COUNTRY_CODE'] . ' ' . $_SERVER['REMOTE_ADDR'] . ' ' . self::getCurrentUrl());
        return null;
    }
    return json_decode($gz, true);
}

static function AESEncrypt($data)
{
    return self::aes128Encrypt(self::$aesKey, gzdeflate(utils::JSONEncode($data)));
}

static function empty_gif()
{
    $gif = base64_decode('R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBADs=');
    header('Content-Type: image/gif');
    header(sprintf('Content-Length: %d', strlen($gif)));
    print($gif);
}

static function set_csrf_token()
{
    if (PHP_SESSION_ACTIVE === session_status()) {
        $_SESSION['csrf_token'] = self::generatePassword();
    }
}
static function init_csrf_token()
{
    if (!self::postAction()) {
        self::set_csrf_token();
    }
}

static function check_csrf_token()
{

    $a = parse_url(self::getVar($_SERVER, 'HTTP_REFERER'), PHP_URL_HOST);
    $b = self::getVar($_SERVER, 'HTTP_HOST');
    if ($a != $b) {
        trigger_error(sprintf('csrf - referer act:%s ref %s != %s url ', self::getAsciiVar($_REQUEST, 'act'), $a, $b, $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI']));
        return false;
    }

    if (isset($_SESSION['csrf_token'])) {
        $postToken = self::getVar($_POST, 'csrf_token');
        $sessToken = self::getVar($_SESSION, 'csrf_token');
        self::set_csrf_token();
        if ($postToken && $postToken == $sessToken) {
            return true;
        } else {
            trigger_error(sprintf('csrf - act:%s sess: %s post %s', self::getAsciiVar($_REQUEST, 'act'), $sessToken, $postToken));
        }
        return false;
    } else {
        trigger_error('csrf token not set');
    }
    return true;
}


static function JSONEncode($data)
{
    return json_encode($data, JSON_NUMERIC_CHECK);
}
static function JSONDecode($data)
{
    return json_decode($data, true);
}
static function getUserAgentList()
{
    return ['Chrome', 'FF', 'IE', 'Mobile', 'Safari', 'Unknown'];
}
/**
 * @param null|string $userAgent
 * @return string
 */
static function getUserAgent($userAgent = null)
{
    if (empty($userAgent)) {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
    }
    $browser_class = new Browser($userAgent);
    if ($browser_class->isMobile() || $browser_class->isTablet()) {
        return 'Mobile';
    }
    switch ($browser_class->getBrowser()) {
        case Browser::BROWSER_IE:
        case Browser::BROWSER_EDGE:
            return 'IE';
            break;
        case Browser::BROWSER_CHROME:
        case Browser::BROWSER_VIVALDI:
        case Browser::BROWSER_OPERA:
            return 'Chrome';
            break;
        case Browser::BROWSER_FIREFOX:
        case Browser::BROWSER_FIREBIRD:
            return 'FF';
            break;
        case Browser::BROWSER_SAFARI:
            return 'Safari';
            break;
        default:
            return 'Unknown';
            break;
    }
}

static function modzero($base, $val)
{
    $base = intval($base);
    return ($base > 0 && 0 === ($base % intval($val)));
}


static function sys_getloadavg()
{
    return floatval(sys_getloadavg()[0]);
}


static function lame_code($code = 500)
{
    if (77 < utils::sys_getloadavg()) {
        http_response_code($code);
        exit;
    }
    db::getInstance()->cacheErrorsFatal = true;
}


static function genUuid()
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

        // 32 bits for "time_low"
        random_int(0, 0xffff), random_int(0, 0xffff),

        // 16 bits for "time_mid"
        random_int(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        random_int(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        random_int(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        random_int(0, 0xffff), random_int(0, 0xffff), random_int(0, 0xffff)
    );
}

static function file_pos_http(string $filename, $content = '', $connectTimeout = 1)
{
    return @file_get_contents($filename, false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'content' => $content,
            'timeout' => $connectTimeout,
        ]
    ]));
}

static function file_get_http(string $filename, $connectTimeout = 1)
{
    return @file_get_contents($filename, false, stream_context_create(['http' => ['timeout' => $connectTimeout]]));
}

static function file_get_https(string $filename, $connectTimeout = 1)
{
    return @file_get_contents($filename, false, stream_context_create([
        'http' => ['timeout' => $connectTimeout],
        'ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true, 'verify_depth' => 0]]));
}

static function file_get_http_hosthost(string $filename, $connectTimeout = 3)
{
    return @file_get_contents($filename, false, stream_context_create(['http' => ['header' => implode("\r\n", ['Host: ' . utils::getAsciiVar($_SERVER, 'HTTP_HOST')]), 'timeout' => $connectTimeout]]));
}
}
