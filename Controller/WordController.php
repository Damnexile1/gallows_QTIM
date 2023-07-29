<?php

class WordController{

    public $themes = ['Еда', 'Одежда'];
    public $wordsOfFood = [
        'хлеб', 'суп', 'салат', 'пирог', 'торт', 'пицца', 'макароны', 'рис',
        'картофель', 'мясо', 'рыба', 'яйцо', 'сыр', 'молоко', 'йогурт',
        'фрукты', 'яблоко', 'апельсин', 'банан', 'виноград', 'помидор',
        'огурец', 'лук', 'чеснок', 'морковь', 'капуста', 'грибы',
        'чай', 'кофе', 'сахар', 'соль', 'перец', 'масло', 'кетчуп',
        'горчица', 'вода', 'сок', 'кола', 'пиво','вино', 'торт',
        'пирожное', 'мороженое', 'шоколад', 'конфеты', 'орехи',
        'изюм', 'мёд', 'десерт'
    ];
    public $wordsOfClothing = ['куртка',
        'пальто', 'пуховик', 'свитер', 'джемпер', 'рубашка', 'футболка',
        'майка', 'блуза', 'платье', 'юбка', 'брюки', 'джинсы', 'шорты',
        'колготки', 'носки', 'трусы', 'лифчик', 'тапочки', 'туфли', 'ботинки',
        'сапоги', 'шапка', 'шарф', 'перчатки', 'костюм', 'пиджак', 'галстук', 'платок',
        'сумка', 'рюкзак', 'кошелек', 'очки', 'часы', 'украшения', 'пояс', 'шляпа',
         ];

    public $words;

    public function __construct()
    {

    }


    public function handle_words(){
        header('Content-Type: application/json; charset=utf-8');

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $conn = $this->connectToDb();
            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data['letter'])) {
                http_response_code(400);
                exit();
            }

            $letter = $data['letter'];

            $sql = "SELECT * FROM words";
            $result = $conn->query($sql);

            $word = $result->fetch_array()["word"];

//            $word = strtolower($this->getWord());
//            var_dump($word);

            $positions = [];

            $offset = 0;
            while(($pos = strpos($word, $letter, $offset)) !== false) {
                if($pos != 0){
                    $positions[] = $pos/2;
                }
                else{
                    $positions[] = $pos;
                }

                $offset = $pos + 1;
            }

            if(count($positions) > 0) {
                $response = [
                  'status' => true,
                  'letter' =>   implode(", ", $positions)
                ];

            } else {
                $response = [
                  'status' => false
                ];
            }

            http_response_code(201);

            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    }

    public function isFullWords(){
        header('Content-Type: application/json; charset=utf-8');

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $conn = $this->connectToDb();
            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data['word'])) {
                http_response_code(400);
                exit();
            }

            $userWord = $data["word"];

            $sql = "SELECT * FROM words";
            $result = $conn->query($sql);

            $word = $result->fetch_array()["word"];

            if( $userWord == $word){
                $response = [
                    'status' => true,
                    'word' => $word,
                ];
                http_response_code(201);

                echo json_encode($response, JSON_UNESCAPED_UNICODE);
            }
            else {
                $response = [
                    'status' => false,
                    'word' => $word,
                ];
                http_response_code(201);

                echo json_encode($response, JSON_UNESCAPED_UNICODE);
            }



        }

    }


    public function getWordsLen()
    {
        header('Content-Type: application/json; charset=utf-8');
        $conn = $this->connectToDb();
//        var_dump($conn);


        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $rKey = array_rand($this->themes);
            $randTheme = $this->themes[$rKey];
            if($randTheme == 'Еда') {

                $key = array_rand($this->wordsOfFood);
                $word = $this->wordsOfFood[$key];

                $sql = "INSERT INTO words (word) VALUES ('$word')";
                $conn->query($sql);



            } else if ($randTheme == 'Одежда') {
                $key = array_rand($this->wordsOfClothing);
                $word = $this->wordsOfClothing[$key];

                $sql = "INSERT INTO words (word) VALUES ('$word')";
                $conn->query($sql);

            }

            http_response_code(201);

            $response = [
                'word len' => strlen($word)/2,
                'слово' => $word,
                'theme' => $randTheme
            ];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    }

    public function  connectToDb(){
        $host = "localhost";
        $username = "root";
        $password = "9506213585";
        $db = "gallows";

        $conn = new mysqli($host, $username, $password, $db);

        if($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

}