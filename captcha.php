<?php
  session_start();

  //Определение нескольких важных констант CAPTCHA
  define('CAPTCHA_NUMCHARS', 5);  //количество символов в идентификационной фразе pass-phrase
  define('CAPTCHA_WIDTH', 100);   //ширина изображения
  define('CAPTCHA_HEIGHT', 40);   //высота изображения

  //Создание рандомной фразы
  $pass_phrase = "";
  for ($i = 0; $i < CAPTCHA_NUMCHARS; $i++) {
    $pass_phrase .= chr(rand(97, 122));//латиница (a-z = 97-122)
  }

  //Сохранение идентификац.фразы в переменной сессии в зашифрованном виде
  $_SESSION['pass_phrase'] = SHA1($pass_phrase);

  //Создание изображения
  $img = imagecreatetruecolor(CAPTCHA_WIDTH, CAPTCHA_HEIGHT);

  //Установка цветов: белого фона, черного текста и серого для графических элементов
  //аргументы (идентифик., с которым будет использоваться цвет, rgb цвет)
  $bg_color = imagecolorallocate($img, 255, 255, 255);     // белый
  $text_color = imagecolorallocate($img, 0, 0, 0);         // черный
  $graphic_color = imagecolorallocate($img, 84, 81, 84);   // темно-серый

  // Заполнение фона

  imagefilledrectangle($img, 0, 0, CAPTCHA_WIDTH, CAPTCHA_HEIGHT, $bg_color);//Рисуем прямоугольник, с координатами точек углов (0,0),установив посл.аргументом цвет
  //Рисуем случайные линии
  for ($i = 0; $i < 14; $i++) {
    imageline($img, 0, rand() % CAPTCHA_HEIGHT, CAPTCHA_WIDTH, rand() % CAPTCHA_HEIGHT, $graphic_color);
  }

  // Добовляем рандомные точки
  for ($i = 0; $i < 100; $i++) {
    imagesetpixel($img, rand() % CAPTCHA_WIDTH, rand() % CAPTCHA_HEIGHT, $graphic_color);
  }
  // Рисуем фразу с применением следующих аргументов
  // размер шрифта , угол наклона ,координаты левого нижнего угла текста (18,0,5)
  imagettftext($img, 20, -2, 0, CAPTCHA_HEIGHT - 15, $text_color, 'Courier New Bold.ttf', $pass_phrase);

  //Вывод изображения в формате PNG с помощью HTTP-заголовка
  // Output the image as a PNG using a header
  header("Content-type: image/png");
  //Создание изображения, использую нарисованные прежде элементы
  imagepng($img);

  //Удаление созд.изображения из памяти
  imagedestroy($img);
?>
