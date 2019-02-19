Yii2 YouTube extension
======================
This is an extension for manipulating video through api YouTube.
[Author origin](https://github.com/lesha724/yii2-youtube-widget)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist saschati/yii2-youtube-api-widget "dev-master"
```

or add

```
"saschati/yii2-youtube-api-widget": "dev-master"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?php
use saschati\youtube\YouTube;

echo YouTube::widget([
    'video' => "https://www.youtube.com/watch?v=nFZP8zQ5kzk",
                    'iframeOptions' => [
                        'class' => 'youtube-video'
                    ],
                    'divOptions' => [
                        'class' => 'youtube-video-div'
                    ],
                    'height' => 390,
                    'width' => 640,
                    'playerVars' => [
                        /**
                          *  Значения: DISABLE, BASE_FUNCTIONAL или FULL_FUNCTIONAL. Значение по умолчанию: BASE_FUNCTIONAL. Этот параметр определяет,
                          *  будут ли отображаться элементы управления проигрывателем.
                          *  При встраивании IFrame с загрузкой проигрывателя Flash он также определяет,
                          *  когда элементы управления отображаются в проигрывателе и когда загружается проигрыватель:
                          */
                        'controls' => YouTube::FULL_FUNCTIONAL,
                        
                        /**
                          *  Значения: DISABLE или ACTIVE. Значение по умолчанию: DISABLE. Определяет,
                          *  начинается ли воспроизведение исходного видео сразу после загрузки проигрывателя.
                          */
                        'autoplay' => YouTube::DISABLE,
                        
                        /**
                          *  Значения: DISABLE или ACTIVE. Значение по умолчанию: ACTIVE.
                          *  При значении DISABLE проигрыватель перед началом воспроизведения не выводит информацию о видео,
                          *  такую как название и автор видео.
                          */
                        'showinfo' => YouTube::ACTIVE,
                        
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
                        'loop ' =>YouTube::DISABLE,
                        
                        /**
                          *  Этот параметр позволяет использовать проигрыватель YouTube,
                          *  в котором не отображается логотип YouTube. Установите значение ACTIVE,
                          *  чтобы логотип YouTube не отображался на панели управления.
                          *  Небольшая текстовая метка YouTube будет отображаться в правом верхнем
                          *  углу при наведении курсора на проигрыватель во время паузы
                          */
                        'modestbranding' => YouTube::DISABLE,
                        
                        /**
                          *  Значения: DISABLE или ACTIVE. Значение по умолчанию ACTIVE отображает кнопку полноэкранного режима.
                          *  Значение DISABLE скрывает кнопку полноэкранного режима.
                          */
                        'fs' => YouTube::ACTIVE,
                        
                        /**
                          *  Значения: DISABLE или ACTIVE. Значение по умолчанию: ACTIVE. Этот параметр определяет,
                          *  будут ли воспроизводиться похожие видео после завершения показа исходного видео.
                          */
                        'rel' => YouTube::DISABLE,
                        
                        /**
                          *  Значения: DISABLE или ACTIVE. Значение по умолчанию: DISABLE. Значение ACTIVE отключает клавиши управления проигрывателем.
                          *  Предусмотрены следующие клавиши управления.
                          *  Пробел: воспроизведение/пауза
                          *  Стрелка влево: вернуться на 10% в текущем видео
                          *  Стрелка вправо: перейти на 10% вперед в текущем видео
                          *  Стрелка вверх: увеличить громкость
                          *  Стрелка вниз: уменьшить громкость
                          */
                        'disablekb' => YouTube::DISABLE
                    ],
                    'events'=>[
                                /*https://developers.google.com/youtube/iframe_api_reference?hl=ru*/
                                'onReady'=> 'function (event){
                                            event.target.playVideo();
                                }',
                            ]                 
                ]); 
?>

```
