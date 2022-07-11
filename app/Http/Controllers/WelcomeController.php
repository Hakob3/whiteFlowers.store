<?php


namespace App\Http\Controllers;


use App\FlowersItems;
use App\FlowersRubrics;
use App\Contacts;

class WelcomeController extends Controller
{
    public $rubrics;
    public $contacts;
    public $welcomeData = [];
    public $metroStations = [];

    public function __construct()
    {
        $this->rubrics = FlowersRubrics::where('vis', '1')->get()->keyBy('id');
        $this->contacts = Contacts::first();
        $this->metroStations = [

            "Авиамоторная",
            "Автозаводская",
            "Академическая",
            "Александровский сад",
            "Алексеевская",
            "Алма-Атинская",
            "Алтуфьево",
            "Аникеевка",
            "Арбатская",
            "Бабушкинская",
            "Багратионовская",
            "Баковка",
            "Баррикадная",
            "Бауманская",
            "Белокаменная",
            "Беломорская",
            "Белорусская",
            "Беляево",
            "Бескудниково",
            "Бибирево",
            "Библиотека им. Ленина",
            "Битца",
            "Битцевский парк",
            "Борисово",
            "Боровицкая",
            "Боровское шоссе",
            "Ботанический сад",
            "Братиславская",
            "Бульвар Адмирала Ушакова",
            "Бульвар Дмитрия Донского",
            "Бульвар Рокоссовского",
            "Бунинская аллея",
            "Бутово",
            "ВДНХ",
            "Варшавская",
            "Верхние Котлы",
            "Верхние Лихоборы",
            "Владыкино",
            "Водники",
            "Водный стадион",
            "Войковская",
            "Волгоградский проспект",
            "Волжская",
            "Волоколамская",
            "Воробьевы горы",
            "Выставочная",
            "Выхино",
            "Говорово",
            "Гражданская",
            "Дегунино",
            "Деловой центр",
            "Динамо",
            "Дмитровская",
            "Добрынинская",
            "Долгопрудная",
            "Домодедовская",
            "Достоевская",
            "Дубровка",
            "Жулебино",
            "ЗИЛ",
            "Зорге",
            "Зябликово",
            "Измайлово",
            "Измайловская",
            "Каланчевская",
            "Калитники",
            "Калужская",
            "Кантемировская",
            "Каховская",
            "Каширская",
            "Киевская",
            "Китай-город",
            "Кожуховская",
            "Коломенская",
            "Коммунарка",
            "Комсомольская",
            "Коньково",
            "Коптево",
            "Косино",
            "Котельники",
            "Красногвардейская",
            "Красногорская",
            "Краснопресненская",
            "Красносельская",
            "Красные Ворота",
            "Красный Балтиец",
            "Красный Строитель ",
            "Крестьянская застава",
            "Кропоткинская",
            "Крылатское",
            "Крымская",
            "Кубанская",
            "Кузнецкий мост",
            "Кузьминки",
            "Кунцевская",
            "Курская",
            "Курьяново",
            "Кутузовская",
            "Ленинский проспект",
            "Лермонтовский проспект",
            "Лесопарковая",
            "Лефортово",
            "Лианозово",
            "Лихоборы",
            "Локомотив",
            "Ломоносовский проспект",
            "Лубянка",
            "Лужники",
            "Лухмановская",
            "Люблино",
            "Марк",
            "Марксистская",
            "Марьина роща",
            "Марьино",
            "Маяковская",
            "Медведково",
            "Международная",
            "Менделеевская",
            "Минская",
            "Митино",
            "Мичуринский проспект",
            "Молодежная",
            "Москва-Товарная",
            "Москворечье",
            "Мякинино",
            "Нагатинская",
            "Нагорная",
            "Нахабино",
            "Нахимовский проспект",
            "Некрасовка",
            "Немчиновка",
            "Нижегородская",
            "Новогиреево",
            "Новодачная",
            "Новокосино",
            "Новокузнецкая",
            "Новопеределкино",
            "Новослободская",
            "Новохохловская",
            "Новоясеневская",
            "Новые Черемушки",
            "Одинцово",
            "Озёрная",
            "Окружная",
            "Окская",
            "Октябрьская",
            "Октябрьское поле",
            "Ольховая",
            "Опалиха",
            "Орехово",
            "Остафьево",
            "Отрадное",
            "Охотный ряд",
            "Павелецкая",
            "Павшино",
            "Панфиловская",
            "Парк Культуры",
            "Парк Победы",
            "Партизанская",
            "Пенягино",
            "Первомайская",
            "Перерва",
            "Перово",
            "Петровский парк",
            "Петровско-Разумовская",
            "Печатники",
            "Пионерская",
            "Планерная",
            "Площадь Гагарина",
            "Площадь Ильича",
            "Площадь Революции",
            "Подольск",
            "Покровское",
            "Покровское-Стрешнево",
            "Полежаевская",
            "Полянка",
            "Пражская",
            "Преображенская площадь",
            "Прокшино",
            "Проспект Вернадского",
            "Проспект Мира",
            "Профсоюзная",
            "Пушкинская",
            "Пятницкое шоссе",
            "Раменки",
            "Рассказовка",
            "Речной вокзал",
            "Рижская",
            "Римская",
            "Ростокино",
            "Румянцево",
            "Рязанский проспект",
            "Савеловская",
            "Саларьево",
            "Свиблово",
            "Севастопольская",
            "Семеновская",
            "Серпуховская",
            "Славянский бульвар",
            "Смоленская",
            "Сокол",
            "Соколиная гора",
            "Сокольники",
            "Солнцево",
            "Спартак",
            "Спортивная",
            "Сретенский бульвар",
            "Стрешнево",
            "Строгино",
            "Студенческая",
            "Сухаревская",
            "Сходненская",
            "Таганская",
            "Тверская",
            "Театральная",
            "Текстильщики",
            "Теплый Стан",
            "Технопарк",
            "Тимирязевская",
            "Третьяковская",
            "Тропарево",
            "Трубная",
            "Тульская",
            "Тургеневская",
            "Тушинская",
            "Угрешская",
            "Улица 1905 года",
            "Улица Академика Янгеля",
            "Улица Горчакова",
            "Улица Дмитриевского",
            "Улица Скобелевская",
            "Улица Старокачаловская",
            "Университет",
            "Филатов луг",
            "Филевский парк",
            "Фили",
            "Фонвизинская",
            "Фрунзенская",
            "Хорошево",
            "Хорошевская",
            "ЦСКА",
            "Царицыно",
            "Цветной бульвар",
            "Черкизовская",
            "Чертановская",
            "Чеховская",
            "Чистые пруды",
            "Чкаловская",
            "Шаболовская",
            "Шелепиха",
            "Шипиловская",
            "Шоссе Энтузиастов",
            "Щелковская",
            "Щукинская",
            "Электрозаводская",
            "Юго-Западная",
            "Южная",
            "Ясенево",
        ];

        $this->welcomeData = [
            'rubrics' => $this->rubrics,
            'contacts' => $this->contacts,
        ];

    }

    public function getFlowersByRubrics()
    {

        $flowers = FlowersItems::selectRaw('flowersItems.* ,flowersRubrics.ordr as f_order')
            ->leftJoin('flowersRubrics', 'flowersRubrics.id', '=', 'flowersItems.rubricId')
            ->where('status', 'visible')
            ->where('flowersRubrics.vis', '1')
            ->where('flowersItems.parentId', '0')
            ->orderBy('ordr', 'asc')->get();

        $flowersByRubric = [];


        foreach ($flowers as $key => $val) {
            if (!isset($flowersByRubric[$val->rubricId])) {
                $flowersByRubric[$val->rubricId] = [];
            }
            $flowersByRubric[$val->rubricId][] = $val;
        }


        uasort($flowersByRubric, function ($item1, $item2) {
            return $item1[0]->f_order <=> $item2[0]->f_order;
        });

        return $flowersByRubric;
    }
}