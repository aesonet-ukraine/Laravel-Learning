<?php

namespace App\Faker;

use Faker\Factory;
use Faker\Generator;
use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;

class ProductProvider extends Base
{
    private const array CATEGORIES = [
        'apparel' => 'Одяг та взуття',
        'electronics' => 'Електроніка',
        'auto' => 'Автозапчастини',
        'tools' => 'Інструменти',
        'appliances' => 'Побутова техніка',
        'outdoor' => 'Туризм та спорт',
        'cosmetics' => 'Косметика та догляд',
        'home' => 'Дім та декор',
        'furniture' => 'Меблі',
        'gaming' => 'Ігри та консолі',
        'toys' => 'Іграшки',
        'books' => 'Книги',
        'pet' => 'Зоотовари',
        'stationery' => 'Канцелярія',
        'groceries' => 'Продукти',
        'garden' => 'Сад та город',
        'baby' => 'Дитячі товари',
        'health' => 'Здоровʼя та гігієна',
    ];

    public function __construct(Generator $generator)
    {
        parent::__construct($generator);
    }

    public function productCategoryKey(): string
    {
        return static::randomElement(array_keys(self::CATEGORIES));
    }

    public function productCategoryLabel(?string $key = null): string
    {
        $key ??= $this->productCategoryKey();

        return self::CATEGORIES[$key] ?? 'Інше';
    }

    /** @return array{key: string, label: string} */
    public function productCategory(): array
    {
        $key = $this->productCategoryKey();

        return [
            'key' => $key,
            'label' => $this->productCategoryLabel($key),
        ];
    }

    public function getCategoryByKey(?string $key = null): array
    {
        $key = ! is_null($key) && ! array_key_exists($key, self::CATEGORIES)
            ? $this->productCategoryKey()
            : $key;

        return [
            'key' => $key,
            'label' => $this->productCategoryLabel($key),
        ];
    }

    public function productName(?string $category = null): string
    {
        $category ??= $this->productCategoryKey();

        return match ($category) {
            'apparel' => $this->apparel(),
            'electronics' => $this->electronics(),
            'auto' => $this->autoParts(),
            'tools' => $this->tools(),
            'appliances' => $this->appliances(),
            'outdoor' => $this->outdoor(),
            'cosmetics' => $this->cosmetics(),
            'home' => $this->home(),
            'furniture' => $this->furniture(),
            'gaming' => $this->gaming(),
            'toys' => $this->toys(),
            'books' => $this->books(),
            'pet' => $this->pet(),
            'stationery' => $this->stationery(),
            'groceries' => $this->groceries(),
            'garden' => $this->garden(),
            'baby' => $this->baby(),
            'health' => $this->health(),

            default => $this->electronics(),
        };
    }

    // ----------------------------
    // Existing categories
    // ----------------------------

    private function apparel(): string
    {
        $brands = ['Nike', 'Adidas', 'Puma', 'Under Armour', 'Levi\'s', 'The North Face', 'Columbia', 'Reebok'];
        $items = ['футболка', 'худі', 'світшот', 'куртка', 'джинси', 'кросівки', 'черевики', 'шорти', 'пальто'];
        $lines = ['Essential', 'Classic', 'Original', 'Premium', 'Urban', 'Sport', 'Trail', 'Slim Fit', 'Regular Fit'];
        $extra = ['', '', '', ' (унісекс)', ' (чоловіча)', ' (жіноча)'];

        return sprintf(
            '%s %s %s%s',
            static::randomElement($brands),
            mb_convert_case(static::randomElement($lines), MB_CASE_TITLE, 'UTF-8'),
            static::randomElement($items),
            static::randomElement($extra),
        );
    }

    private function electronics(): string
    {
        return static::randomElement([
            $this->phone(),
            $this->laptop(),
            $this->headphones(),
            $this->router(),
            $this->peripheral(),
        ]);
    }

    private function phone(): string
    {
        $variants = [
            fn () => 'Apple iPhone '.static::randomElement(['13', '14', '15', '16']).
                ' '.static::randomElement(['', 'Plus', 'Pro', 'Pro Max']).' '.
                static::randomElement(['32GB', '64GB', '128GB', '256GB', '512GB']),
            fn () => 'Samsung Galaxy '.static::randomElement(['S22', 'S23', 'S24', 'A54', 'A55']).
                ' '.static::randomElement(['5G', '']).' '.static::randomElement(['32GB', '64GB', '128GB', '256GB']),
            fn () => 'Xiaomi Redmi Note '.static::randomElement(['12', '13', '14']).
                ' '.static::randomElement(['Pro', 'Pro+']).' '.
                static::randomElement(['5G', '']).' '.static::randomElement(['32GB', '64GB', '128GB', '256GB']),
            fn () => 'Google Pixel '.static::randomElement(['7', '8', '9']).
                ' '.static::randomElement(['', 'Pro']).' '.static::randomElement(['32GB', '64GB', '128GB', '256GB']),
        ];

        return trim(preg_replace('/\s+/', ' ', ($variants[array_rand($variants)])()));
    }

    private function laptop(): string
    {
        $brands = [
            'ASUS' => ['TUF Gaming', 'ROG Strix', 'VivoBook'],
            'Lenovo' => ['IdeaPad', 'ThinkPad', 'Legion'],
            'HP' => ['Pavilion', 'Victus', 'ProBook'],
            'Acer' => ['Aspire', 'Nitro', 'Swift'],
            'Dell' => ['Inspiron', 'Latitude', 'G15'],
        ];

        $shuffled = $this->shuffleAssoc($brands);
        $brand = array_key_first($shuffled);
        $line = static::randomElement($shuffled[$brand]);

        $cpu = static::randomElement(['i5', 'i7', 'Ryzen 5', 'Ryzen 7']);
        $ram = static::randomElement([8, 16, 32]);
        $ssd = static::randomElement([256, 512, 1024]);
        $gpu = static::randomElement(['', '', 'RTX 4050', 'RTX 4060', 'RTX 4070']);

        $model = static::bothify('??####');
        $size = static::randomElement(['14"', '15.6"', '16"', '17.3"']);

        return trim(preg_replace('/\s+/', ' ', sprintf(
            '%s %s %s %s %s/%dGB/%dGB %s',
            $brand,
            $line,
            $model,
            $size,
            $cpu,
            $ram,
            $ssd,
            $gpu,
        )));
    }

    private function headphones(): string
    {
        $variants = [
            'Sony WH-1000XM'.static::randomElement(['4', '5']),
            'Bose QuietComfort '.static::randomElement(['45', 'Ultra']),
            'Apple AirPods '.static::randomElement(['Pro 2', 'Max']),
            'JBL Tune '.static::randomElement(['760NC', '770NC', '510BT']),
        ];

        return static::randomElement($variants).' (Bluetooth, шумозаглушення)';
    }

    private function router(): string
    {
        return 'TP-Link Archer '.static::randomElement(['AX23', 'AX55', 'AX73']).' Wi-Fi 6 роутер';
    }

    private function peripheral(): string
    {
        $items = [
            'Logitech MX Master '.static::randomElement(['3', '3S']).' бездротова миша',
            'Logitech K'.static::randomElement(['380', '540', '780']).' клавіатура',
            'Samsung T'.static::randomElement(['7', '9']).' SSD '.static::randomElement(['1TB', '2TB']),
            'Anker PowerCore '.static::randomElement(['10000', '20000']).' PD павербанк',
        ];

        return static::randomElement($items);
    }

    private function autoParts(): string
    {
        $brands = ['Bosch', 'NGK', 'MANN-FILTER', 'Brembo', 'SKF', 'KYB', 'Gates', 'Liqui Moly'];
        $parts = [
            'гальмівні колодки', 'масляний фільтр', 'повітряний фільтр', 'свічка запалювання',
            'амортизатор', 'ремінь генератора', 'підшипник ступиці', 'щітки склоочисника',
        ];

        return sprintf(
            '%s %s %s',
            static::randomElement($brands),
            static::randomElement($parts),
            static::bothify('?? ###/##-##'),
        );
    }

    private function tools(): string
    {
        $brands = ['DeWalt', 'Makita', 'Bosch Professional', 'Milwaukee', 'Stanley', 'Wera', 'Knipex'];
        $items = [
            'акумуляторний шурупокрут', 'перфоратор', 'кутова шліфмашина', 'лазерний рівень',
            'набір викруток', 'рулетка', 'плоскогубці', 'дриль-шурупокрут',
        ];

        return sprintf(
            '%s %s %s',
            static::randomElement($brands),
            static::randomElement($items),
            static::bothify('##V-##??'),
        );
    }

    private function appliances(): string
    {
        $variants = [
            'Philips Airfryer Series '.static::randomElement(['2000', '3000', '5000']).' '.static::randomElement(['4.1L', '6.2L']),
            'Bosch Serie '.static::randomElement(['4', '6']).' посудомийка 60см',
            'Samsung мікрохвильова піч '.static::bothify('MS##K####').' 23L',
            'LG пральна машина '.static::bothify('F#J#H###').' '.static::randomElement(['7kg', '8kg', '9kg']),
            'Dyson V'.static::randomElement(['10', '11', '12']).' бездротовий пилосос',
        ];

        return static::randomElement($variants);
    }

    private function outdoor(): string
    {
        $variants = [
            'Osprey Talon '.static::randomElement(['22', '26']).' рюкзак',
            'Salewa Alp Trainer Mid GTX трекінгові черевики',
            'Garmin Forerunner '.static::randomElement(['245', '255', '265']).' GPS годинник',
            'Shimano Deore XT M'.static::randomElement(['8100', '8120']).' ланцюг 12-speed',
            'Topeak JoeBlow Sport III насос підлоговий',
        ];

        return static::randomElement($variants);
    }

    private function cosmetics(): string
    {
        $variants = [
            'La Roche-Posay Effaclar Duo+ '.static::randomElement(['40ml', '75ml']),
            'The Ordinary Niacinamide 10% + Zinc 1% '.static::randomElement(['30ml', '60ml']),
            'L\'Oréal Paris Revitalift Serum '.static::randomElement(['30ml', '50ml']),
            'Nivea Men Sensitive бальзам після гоління 100ml',
            'CeraVe Hydrating Cleanser '.static::randomElement(['236ml', '473ml']),
        ];

        return static::randomElement($variants);
    }

    // ----------------------------
    // New categories
    // ----------------------------

    private function home(): string
    {
        $brands = ['IKEA', 'VitrA', 'Tefal', 'Luminarc', 'Karcher', 'Brita', 'Tescoma'];
        $items = [
            'набір посуду', 'сковорода', 'каструля', 'постільна білизна',
            'рушники', 'настільна лампа', 'фільтр-глечик для води', 'контейнер для зберігання',
        ];

        return sprintf('%s %s %s', static::randomElement($brands), static::randomElement($items), static::bothify('##-??'));
    }

    private function furniture(): string
    {
        $items = [
            'крісло офісне', 'стіл письмовий', 'шафа-купе', 'комод', 'ліжко двоспальне',
            'стілець кухонний', 'тумба під ТВ', 'полиця настінна',
        ];
        $series = ['MALM', 'HEMNES', 'KALLAX', 'LACK', 'BILLY', 'LINNMON', 'POÄNG'];

        return sprintf('%s %s (%s)', static::randomElement($series), static::randomElement($items), static::randomElement(['білий', 'чорний', 'дуб', 'сірий']));
    }

    private function gaming(): string
    {
        $variants = [
            'Sony PlayStation '.static::randomElement(['5', '5 Slim']).' '.static::randomElement(['Digital Edition', '']),
            'Microsoft Xbox Series '.static::randomElement(['S', 'X']),
            'Nintendo Switch '.static::randomElement(['OLED', '']),
            'DualSense Wireless Controller (PS5)',
            'Seagate Game Drive '.static::randomElement(['1TB', '2TB']).' для консолі',
            'Razer BlackShark V2 X ігрова гарнітура',
            'Logitech G502 HERO ігрова миша',
            'SteelSeries QcK килимок для миші (Large)',
        ];

        return trim(preg_replace('/\s+/', ' ', static::randomElement($variants)));
    }

    private function toys(): string
    {
        $brands = ['LEGO', 'Hasbro', 'Mattel', 'Fisher-Price', 'Spin Master'];
        $items = [
            'набір конструктора', 'лялька', 'настільна гра', 'іграшкова машинка',
            'пазл 1000 деталей', 'мʼяка іграшка', 'робот на пульті',
        ];

        return sprintf('%s %s %s', static::randomElement($brands), static::randomElement($items), static::randomElement(['3+', '6+', '8+', '10+']));
    }

    private function books(): string
    {
        $topics = ['психологія', 'бізнес', 'фантастика', 'детектив', 'історія', 'саморозвиток', 'кулінарія', 'програмування'];
        $formats = ['тверда обкладинка', 'мʼяка обкладинка', 'подарункове видання'];

        return sprintf(
            'Книга: %s (%s)',
            mb_convert_case(static::randomElement($topics), MB_CASE_TITLE, 'UTF-8'),
            static::randomElement($formats)
        );
    }

    private function pet(): string
    {
        $brands = ['Royal Canin', 'Purina Pro Plan', 'Brit Care', 'Trixie', 'Flexi'];
        $items = [
            'сухий корм для собак', 'сухий корм для котів', 'шлея', 'повідець-рулетка',
            'миска', 'кігтеточка', 'наповнювач для котячого туалету',
        ];

        return sprintf('%s %s %s', static::randomElement($brands), static::randomElement($items), static::randomElement(['1.5kg', '3kg', '10kg', 'M', 'L']));
    }

    private function stationery(): string
    {
        $brands = ['Moleskine', 'Leuchtturm1917', 'Pilot', 'Parker', 'Stabilo', 'Faber-Castell'];
        $items = ['блокнот', 'щоденник', 'ручка', 'олівці (набір)', 'маркер', 'стікери'];

        return sprintf('%s %s %s', static::randomElement($brands), static::randomElement($items), static::randomElement(['A5', 'A4', '0.5mm', '0.7mm']));
    }

    private function groceries(): string
    {
        $items = [
            'кава зернова', 'чай чорний', 'оливкова олія', 'паста', 'соус томатний',
            'шоколад', 'горіхи (мікс)', 'рис басматі', 'вівсянка',
        ];
        $brands = ['Lavazza', 'Jacobs', 'Ahmad Tea', 'Barilla', 'Mutti', 'Lindt', 'Alce Nero'];

        return sprintf('%s %s %s', static::randomElement($brands), static::randomElement($items), static::randomElement(['250g', '500g', '1kg', '750ml']));
    }

    private function garden(): string
    {
        $brands = ['Gardena', 'Fiskars', 'Bosch', 'Kärcher', 'Einhell'];
        $items = ['садові ножиці', 'ланцюгова пила', 'шланг', 'газонокосарка', 'обприскувач', 'тример'];

        return sprintf('%s %s %s', static::randomElement($brands), static::randomElement($items), static::bothify('##??-###'));
    }

    private function baby(): string
    {
        $brands = ['Pampers', 'Huggies', 'Chicco', 'Philips Avent', 'Nuk'];
        $items = ['підгузки', 'пляшечка', 'пустушка', 'дитячий крем', 'серветки вологі'];

        return sprintf('%s %s %s', static::randomElement($brands), static::randomElement($items), static::randomElement(['0-3m', '3-6m', '6-12m', 'Size 3', 'Size 4', '72шт']));
    }

    private function health(): string
    {
        $brands = ['Oral-B', 'Listerine', 'Sensodyne', 'CeraVe', 'Gillette', 'Colgate'];
        $items = [
            'зубна щітка', 'зубна паста', 'ополіскувач для рота', 'гель для душу',
            'антиперспірант', 'бритви (змінні касети)', 'крем для рук',
        ];

        return sprintf('%s %s %s', static::randomElement($brands), static::randomElement($items), static::randomElement(['100ml', '250ml', '500ml', '2шт', '4шт']));
    }

    /** @param array<string, array<int, string>> $arr */
    private function shuffleAssoc(array $arr): array
    {
        $keys = array_keys($arr);
        shuffle($keys);

        $out = [];
        foreach ($keys as $k) {
            $out[$k] = $arr[$k];
        }

        return $out;
    }

    public function generateThumbnail(string $slug): string
    {
        $dirName = "faker/products/$slug";
        $faker = Factory::create();
        $faker->addProvider(new FakerPicsumImagesProvider($faker));

        if (! Storage::disk('public')->exists($dirName)) {
            Storage::disk('public')->createDirectory($dirName);
        }

        /**
         * @var FakerPicsumImagesProvider $faker
         */
        return $dirName.'/'.$faker->image(
            dir: Storage::disk('public')->path($dirName),
            isFullPath: false
        );
    }
}
