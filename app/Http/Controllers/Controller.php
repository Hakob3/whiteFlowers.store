<?php

namespace App\Http\Controllers;

use App\CartItems;
use App\FlowersRubrics;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $deliveryPriceOutMKAD = 1000;
    public $deliveryPriceInMKAD = 500;
    public $emailServer = 'smtp.mail.ru';
    public $emailAddress = 'info@whitestudios.ru';
    public $emailPassword = 'nWL19o5';
    public $emailFlowersServer = 'smtp.yandex.ru';
    public $emailFlowersAddress = 'wfbar@yandex.ru';
    public $emailFlowersPassword = '242216Loft';
    public $tinifyKey = 'gMJf8F3clXcwxqZstLQzb1nqH449ykP0';
    public $mapHost = 'https://api-evo.mapcard.pro/';
    public $mapKey = 'KYBkmae0979jynhf646Z8hgG8u65jydf';
    public $skyLogin = 'aiartworks@inbox.ru';
    public $skyPass = 'tuk111000222';


    public $inv_name = "ООО «ВайтСтудио» (для цветов)";
    public $inv_infoCard = "Наименование предприятия: ООО «ВайтСтудио»
                        Юридический адрес: 123100, Москва г, Звенигородская 2-я ул, дом № 12, строение 21, помещение II, III, IV, V
                        Почтовый адрес: 127434, г. Москва, Красностуденческий проезд, д.6, кв. 32
                        Тел Бух.: 8-985-346-70-65
                        ИНН: 7715684464
                        КПП: 770301001
                        ОГРН: 1087746108042
                        ОКПО: 84725671
                        ОКВЭД: 14.13 ПРОИЗВОДСТВО ПРОЧЕЙ ВЕРХНЕЙ ОДЕЖДЫ
                        Генеральный директор: Ольховой Алексей Викторович";
    public $inv_RSCode = "9294712195";
    public $inv_mkbCode = "600000000002154";
    public $inv_mkbCode2 = "0gA9eATo";
    public $inv_mapKey = "WhitestudiosOOOAV3DS";
    public $inv_mapPass = "DEijx61";

    public function __construct()
    {

    }

    public function megaValidate($f, $string)
    {

        if ($f === 'pop_device_id') {
            if (gettype($string) === "string" && !preg_match("/[^A-Za-z0-9\-]/u", trim($string))) {
                return true;
            } else {
                return false;
            }
        }

        if ($f === 'c_group') {
            if (gettype($string) === "string" && !preg_match("/[^A-Za-z0-9а-яА-Я\_\-\=]/u", trim($string))) {
                return true;
            } else {
                return false;
            }
        }

        if ($f === 'n_s_blist') {
            if (gettype($string) === "string" && !preg_match("/[^A-Za-zа-яА-Я0-9\-\$\,\.\_\+\ \(\)\+\%\-\s\=]/u", trim($string))) {
                return true;
            } else {
                return false;
            }
        }

        if ($f === 'n_s_sender_id') {
            if (gettype($string) === "string" && !preg_match("/[^A-Za-z0-9\-\.\_\s=]/u", trim($string))) {
                return true;
            } else {
                return false;
            }
        }

        if ($f == 'sms_phones') {
            if (gettype($string) === "string" && !preg_match("/[^A-Za-z0-9\!\@\#\$\%\&\*\(\)\+\/\-\s\;\:\.\_\=]/u", trim($string))) {
                return true;
            } else {
                return false;
            }
        }

        if ($f == 'n_s') {
            if (gettype($string) === "string" && !preg_match('/[^A-Za-z0-9а-яА-Я_]/u', $string)) {
                return true;
            } else {
                return false;
            }
        }
        if ($f == 'n_s_d') {
            if (gettype($string) === "string" && !preg_match('/[^A-Za-z0-9а-яА-Я_]/u', $string)) {
                return true;
            } else {
                return false;
            }
        }
        if ($f == 'fx1') {
            if (gettype($string) === "string" && !preg_match("/[^A-Za-z0-9а-яА-Я\@\(\)\+\ \.\/\&\_\[\]\{\}\-\=]/u", trim($string))) {
                return true;
            } else {
                return false;
            }
        }
        if ($f == 'c_name') {
            if (gettype($string) === "string" && !preg_match("/[^A-Za-z0-9а-яА-Я\ \-\_\=]/u", trim($string))) {
                return true;
            } else {
                return false;
            }
        }
        if ($f == 'fx2') {
            if (gettype($string) === "string" && !preg_match("/[^0-9\-\_]/u", trim($string))) {
                return true;
            } else {
                return false;
            }
        }
        if ($f == 's') { //string
            if (gettype($string) === "string" && !preg_match('/[^A-Za-z_]/', trim($string))) {
                return true;
            } else {
                return false;
            }
        }
        if ($f == 'ps') { //passwordChecker
            if (gettype($string) === "string" && !preg_match('/[^A-Za-z0-9а-яА-Я\@\(\)\+\.\/\_\[\]\{\}\*\^\%\$\#\!\(\?\-]/u', trim($string))) {
                return true;
            } else {
                return false;
            }
        }
        if ($f == 'ipRange') { //ipRange
            if (gettype($string) === "string" && !preg_match('/[^0-9\/\\r\\n\.]/', trim($string))) {
                $cidr = explode("\n", $string);
                $t = false;
                foreach ($cidr as $key => $val) {
                    $r1 = explode("/", $val);
                    if (isset($r1[1]) && intval($r1[1]) >= 0 && intval($r1[1]) <= 32) {
                        $f = explode(".", $val);
                        if (count($f) === 4
                            &&
                            (intval($f[0]) >= 0 && intval($f[0]) <= 255)
                            &&
                            (intval($f[1]) >= 0 && intval($f[1]) <= 255)
                            &&
                            (intval($f[2]) >= 0 && intval($f[2]) <= 255)
                        ) {
                            $t = true;
                        } else {
                            $t = false;
                            break;
                        }
                    }
                }
            } else {
                $t = false;
            }
            return $t;
        }
        if ($f == 'isDate') { //isDate
            if (!$string) {
                return false;
            }

            try {
                new \DateTime($string);
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
        if ($f == 'email') {
            if (!filter_var($string, FILTER_VALIDATE_EMAIL)) {
                return false;
            } else {
                return true;
            }
        }
        if ($f == 'n') { //onlyNumbers
            if (!preg_match("#[^0-9]#", trim($string))) {
                return true;
            } else {
                return false;
            }
        }
        if ($f == 'ph') { //phoneFormat
            if (!preg_match("/[^0-9\+\-\ \(\)\.]/", trim($string))) {
                return true;
            } else {
                return false;
            }
        }
        if ($f == 'ad') { //address
            if (!preg_match("/[^A-Za-zа-яА-Я0-9\ \_\.\:\,\-]/", trim($string))) {
                return true;
            } else {
                return false;
            }
        }

    }

    public function setSession($key, $value)
    {
        Session::put($key, $value);
    }

    public function getFingerprint()
    {

        if (Session::get('fingerprint', null) === null) {
            $this->setSession('fingerprint', md5(time()));
        }
        return Session::get('fingerprint', null);
    }


}
