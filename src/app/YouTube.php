<?php

namespace saschati\youtube\app;

use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;
use yii\base\Widget;
use saschati\youtube\assets\YoutubeAsset;

/**
 * This is just an example.
 */
class YouTubeBase extends Widget
{
    /**
     * @const ID_JS api id
     */
    const ID_JS = 'YoutubeAPIReady';

    /**
     * @const HOST api host
     */
    const HOST = 'https://www.youtube.com';
    /**
     * @const POS_JS position js in project
     */
    const POS_JS = View::POS_HEAD;

    /**
     * @const ACTIVE активизирует тот или иной функционал
     */
    const ACTIVE = 1;

    /**
     * @const DISABLE деактивирует тот или иной функціонал
     */
    const DISABLE = 0;

    /**
     * @const BASE_FUNCTIONAL дает урезанные настройки видео
     */
    const BASE_FUNCTIONAL = 1;

    /**
     * @const FULL_FUNCTIONAL дает полное управления по проигрыванию видео
     */
    const FULL_FUNCTIONAL = 2;

    /**
     * @var string $autoIdPrefix хз шо це вопше таке
     */
    public static $autoIdPrefix = 'wyoutube';

    /**
     * @var string $video youtube video url
     */
    public $video;

    /**
     * default video height player
     * @var $height
     */
    public $height = 390;

    /**
     * default video width player
     * @var $width
     */
    public $width = 640;

    /**
     * @link https://developers.google.com/youtube/player_parameters?playerVersion=HTML5&hl=ru#playerapiid
     * @var array настройки плеера
     */
    public $playerVars = [];

    /**
     * @var array события плеера
     */
    public $events = [];

    /**
     * @var array опцыи для контейнера div
     */
    public $divOptions = [];

    /**
     * @var array опцыи для контейнера iframe
     */
    public $iframeOptions = [];

    /**
     * @var bool|string $_videoId youtube video id
     */
    protected $_videoId = false;

    /**
     *  Шаблон скпирта
     * @link  https://developers.google.com/youtube/iframe_api_reference?hl=ru
     */
    protected $_jsFormat = <<<JS
        __player_id__ = new YT.Player('__div_id__', {
                host: '%s',
                height: '%s',
                width: '%s',
                videoId: '%s',
                playerVars: JSON.parse('%s'),
                events:'replace',
            });
JS;

    /**
     * @var $_jsStartFunction init's start js function
     */
    protected $_jsStartFunction = "function onYouTubePlayerAPIReady() {";

    /**
     * @link https://developers.google.com/youtube/player_parameters?playerVersion=HTML5&hl=ru#playerapiid
     * @var array настройки по умолчанию
     */
    protected $_defaultSettings = [

        /**
         *  Значения: DISABLE, BASE_FUNCTIONAL или FULL_FUNCTIONAL. Значение по умолчанию: BASE_FUNCTIONAL. Этот параметр определяет,
         *  будут ли отображаться элементы управления проигрывателем.
         *  При встраивании IFrame с загрузкой проигрывателя Flash он также определяет,
         *  когда элементы управления отображаются в проигрывателе и когда загружается проигрыватель:
         */
        'controls' => self::BASE_FUNCTIONAL,

        /**
         *  Значения: DISABLE или ACTIVE. Значение по умолчанию: DISABLE. Определяет,
         *  начинается ли воспроизведение исходного видео сразу после загрузки проигрывателя.
         */
        'autoplay' => self::DISABLE,

        /**
         *  Значения: DISABLE или ACTIVE. Значение по умолчанию: ACTIVE.
         *  При значении DISABLE проигрыватель перед началом воспроизведения не выводит информацию о видео,
         *  такую как название и автор видео.
         */
        'showinfo' => self::ACTIVE,

        /**
         *  Значение: положительное целое число.
         *  Если этот параметр определен, то проигрыватель начинает воспроизведение видео с указанной секунды.
         *  Обратите внимание, что, как и для функции seekTo,
         *  проигрыватель начинает воспроизведение с ключевого кадра,
         *  ближайшего к указанному значению. Это означает,
         *  что в некоторых случаях воспроизведение начнется в момент,
         *  предшествующий заданному времени (обычно не более чем на 2 секунды).
         */
        'start' => 0,

        /**
         *  Значение: положительное целое число. Этот параметр определяет время,
         *  измеряемое в секундах от начала видео, когда проигрыватель должен остановить воспроизведение видео.
         *  Обратите внимание на то, что время измеряется с начала видео, а не со значения параметра start или startSeconds,
         *  который используется в YouTube Player API для загрузки видео или его добавления в очередь воспроизведения.
         */
        'end' => 0,

        /**
         *  Значения: DISABLE или ACTIVE. Значение по умолчанию: DISABLE. Если значение равно ACTIVE,
         *  то одиночный проигрыватель будет воспроизводить видео по кругу, в бесконечном цикле.
         *  Проигрыватель плейлистов (или пользовательский проигрыватель) воспроизводит по кругу содержимое плейлиста.
         */
        'loop ' => self::DISABLE,

        /**
         *  Этот параметр позволяет использовать проигрыватель YouTube,
         *  в котором не отображается логотип YouTube. Установите значение ACTIVE,
         *  чтобы логотип YouTube не отображался на панели управления.
         *  Небольшая текстовая метка YouTube будет отображаться в правом верхнем
         *  углу при наведении курсора на проигрыватель во время паузы
         */
        'modestbranding' => self::ACTIVE,

        /**
         *  Значения: DISABLE или ACTIVE. Значение по умолчанию ACTIVE отображает кнопку полноэкранного режима.
         *  Значение DISABLE скрывает кнопку полноэкранного режима.
         */
        'fs' => self::ACTIVE,

        /**
         *  Значения: DISABLE или ACTIVE. Значение по умолчанию: ACTIVE. Этот параметр определяет,
         *  будут ли воспроизводиться похожие видео после завершения показа исходного видео.
         */
        'rel' => self::ACTIVE,

        /**
         *  Значения: DISABLE или ACTIVE. Значение по умолчанию: DISABLE. Значение ACTIVE отключает клавиши управления проигрывателем.
         *  Предусмотрены следующие клавиши управления.
         *  Пробел: воспроизведение/пауза
         *  Стрелка влево: вернуться на 10% в текущем видео
         *  Стрелка вправо: перейти на 10% вперед в текущем видео
         *  Стрелка вверх: увеличить громкость
         *  Стрелка вниз: уменьшить громкость
         */
        'disablekb' => self::DISABLE,
    ];

    /**
     * Переменная которая хранит id проигрывателя
     * @var string $_playerId
     */
    private $_playerId;

    /**
     * Начало запуска виджета
     */
    public function init()
    {
        parent::init();
        $this->_videoId = $this->_getVideoId();
        $this->_playerId = 'player_' . $this->id;
    }

    /**
     * получить id video (проверка являеться ли ссылкой или сразу уже айди)
     * @return string
     */
    protected function _getVideoId()
    {
        if (filter_var($this->video, FILTER_VALIDATE_URL) !== false) {
            return $this->_getVideoIdByUrl($this->video);
        } else {
            return $this->video;
        }
    }

    /**
     * Получения id Youtube видео по его ссылке
     * @param $url string Video url
     * @return bool|string
     */
    protected function _getVideoIdByUrl($url)
    {
        $videoId = false;
        $url = parse_url($url);
        if (strcasecmp($url['host'], 'youtu.be') === 0) {

            /**
             * Если переданная ссылка является короткой ссылкой на TouTube
             * (dontcare)://youtu.be/<video id>
             */
            $videoId = substr($url['path'], 1);

        } elseif (strcasecmp($url['host'], 'www.youtube.com') === 0) {

            /**
             * Если переданная ссылка является ссылкой на стандартный хост TouTube
             * (dontcare)://www.youtube.com/(dontcare)?v=<video id>
             */
            if (isset($url['query'])) {
                parse_str($url['query'], $url['query']);
                if (!empty($url['query']['v'])) {
                    $videoId = $url['query']['v'];
                }
            }
            if ($videoId === false) {
                $url['path'] = explode('/', substr($url['path'], 1));

                /**
                 * Если есть ссылка на api YouTube
                 * (dontcare)://www.youtube.com/(whitelist)/<video id>
                 */
                if (in_array($url['path'][0], ['e', 'embed', 'v'])) {
                    $videoId = $url['path'][1];
                }
            }
        }
        return $videoId;
    }

    /**
     * Возвращает уже сгенерированный страницу с подключенными к нему скриптами
     */
    public function run()
    {
        if ($this->_videoId === false) {
            $html = Html::tag('div', \Yii::t('yii', 'Error'));
            return $html;
        }
        $view = $this->getView();
        YoutubeAsset::register($view);
        return $this->_runWidget();
    }

    /**
     * Вывод виджета
     * @return string html widget
     */
    protected function _runWidget()
    {

        $js = "var " . $this->_playerId . ";";
        $this->getView()->registerJs($js, View::POS_HEAD);

        $this->_registerJs();

        $html =
            Html::tag('div',
                Html::tag('div', '', ArrayHelper::merge(
                    [
                        'id' => 'div_' . $this->id
                    ],
                    $this->iframeOptions
                )),
                ArrayHelper::merge(
                    [
                        'id' => $this->id
                    ],
                    $this->divOptions
                )
            );

        return $html;
    }

    /**
     * Регистрация скрипта для вывода iframe с функцыоналома переданым в виджет
     */
    protected function _registerJs()
    {
        $settings = $this->_mergeSettings($this->_defaultSettings);

        $_settingsStr = Json::encode($settings);

        $_playerId = $this->_playerId;
        $_script = sprintf(
            $this->_jsFormat,
            self::HOST,
            $this->height,
            $this->width,
            $this->_videoId,
            $_settingsStr
        );

        $_script = $this->_event($_script);
        $_script = str_replace('__player_id__', $_playerId, $_script);
        $_script = str_replace('__div_id__', 'div_' . $this->id, $_script);

        $view = $this->getView();

        $script = '';
        if (!isset($this->view->js[self::POS_JS][self::ID_JS])) {
            $script .= $this->_jsStartFunction . $_script;
            $script .= "}";
        } else {
            $script = $this->addJs($_script);
        }

        $view->registerJs($script, View::POS_HEAD, self::ID_JS);
    }


    /**
     * Проверяет существует ли в переменной $events массив событий
     * @param $script string являющейся структурной запуска приложения
     * @return mixed строку с добавленными события в скрипт
     */
    protected function _event($script)
    {
        if (!empty($this->events)) {
            $_eventsStr = 'events:{';
            foreach ($this->events as $name => $event) {
                $_function = new JsExpression($event);
                $_eventsStr .= "'$name': $_function,";
            }
            $_eventsStr .= '}';
            $script = $this->_eventReplace($script, $_eventsStr);
        }
        return $script;
    }

    /**
     * Замена шаблона событий на сами события
     * @param $script
     * @param $events
     * @return mixed
     */
    protected function _eventReplace($script, $events)
    {
        if (strpos($script, "events:'replace'") !== false) {
            return str_replace("events:'replace'", $events, $script);
        }
        return $script;
    }

    /**
     * Мерж дефолтных настроек и пользовательских
     * @param $settings array setting video
     * @return array marge setting
     */
    protected function _mergeSettings($settings)
    {
        if (!isset($this->playerVars['hl'])) {
            $this->playerVars['hl'] = substr(\Yii::$app->language, 0, 2);
        }
        return array_merge($settings, $this->playerVars);
    }

    /**
     * Обеднает скрипт для несколько видео
     * @param $js
     * @return mixed
     */
    protected function addJs($js)
    {
        $script = $this->view->js[self::POS_JS][self::ID_JS];
        $new_script = str_replace($this->_jsStartFunction, $this->_jsStartFunction . ' ' . $js, $script);
        return $new_script;
    }
}
