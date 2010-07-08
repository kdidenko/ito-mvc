<?php 

  define('GRAPH_WIDTH',         400);               // ширина картинки 
  define('GRAPH_HEIGHT',        300);               // высота картинки 
  define('GRAPH_OFFSET_TOP',    40);                // отступ сверху 
  define('GRAPH_OFFSET_LEFT',   40);                // отступ слева 
  define('GRAPH_OFFSET_RIGHT',  5);                 // отстут справа 
  define('GRAPH_OFFSET_BOTTOM', 30);                // отступ снизу 

  define('FONT_NAME', 'arial.ttf');                 // Имя шрифта
  define('FONT_SIZE', 12);                          // Размер шрифта

  $colors = array(0xFF0000,0x00FF00,0x0000FF,     // цвета столбцов 
                  0xFFFF00,0x00FFFF,0xFF00FF); 

  require('data.php');
  require('win2uni.php');

  // Считаем ширину столбцов 
  $col_width = (GRAPH_WIDTH - GRAPH_OFFSET_LEFT - GRAPH_OFFSET_RIGHT) / count($Data); 

  // Считаем высоту столбца, соответствующего максимальному значению 
  $col_maxheight = (GRAPH_HEIGHT - GRAPH_OFFSET_TOP - GRAPH_OFFSET_BOTTOM); 

  // Ищем максимальное значение в массиве, соответствующее столбцу максимальной высоты 
  $max_value = max($Data); 

  $image = imagecreatetruecolor(GRAPH_WIDTH,GRAPH_HEIGHT) // создаем изображение... 
    or die('Cannot create image');     // ...или прерываем работу скрипта в случае ошибки 

  imagefill($image, 0, 0, 0xFFFFFF);  // белый фон

  // рисуем столбцы 
  $x = GRAPH_OFFSET_LEFT; 
  $y = GRAPH_OFFSET_TOP + $col_maxheight; 
  $i = 0; 
  foreach($Data as $name => $value) { 
    imagefilledrectangle(              // рисуем сплошной прямоугольник
      $image, 
      $x, 
      $y - round($value*$col_maxheight/$max_value), 
      $x + $col_width - 1, 
      $y, 
      $colors[$i++%count($colors)]
    );

    // Выводим текст:
    // .. преобразование в Unicode...
    $text = win2uni($name);
    // .. расчет координат...
    $coord = imagettfbbox(FONT_SIZE,0,FONT_NAME,$text);
    $text_x = $x + ($col_width - $coord[2] - $coord[0]) / 2;
    $text_y = GRAPH_HEIGHT - 5;
    // .. и вывод текста
    imagettftext($image,FONT_SIZE,0,$text_x,$text_y,0x000000,FONT_NAME,$text);

    $x += $col_width;
  } 

  // Выводим заголовок
  $text = win2uni($Title);
  $coord = imagettfbbox(FONT_SIZE,0,FONT_NAME,$text);
  $text_x = $x + ($col_width - $coord[2] - $coord[0]) / 2;
  $text_y = (GRAPH_OFFSET_TOP - $coord[1] - $coord[7]) / 2; 
  imagettftext($image,FONT_SIZE,0,$text_x,$text_y,0x000000,FONT_NAME,$text);

  // рисуем координатную ось 
  imageline($image, GRAPH_OFFSET_LEFT - 5, GRAPH_OFFSET_TOP, 
            GRAPH_OFFSET_LEFT - 5, $y, 0xCCCCCC); 
  for($value=0; $value<=$max_value; $value++) {
    imageline($image, GRAPH_OFFSET_LEFT - 7, $Y = $y - round($value*$col_maxheight/$max_value), 
            GRAPH_OFFSET_LEFT - 5, $Y, 0xCCCCCC); 
    imagestring($image, 1, GRAPH_OFFSET_LEFT / 2, $Y - 4, $value, 0x000000);
  }

  header('Content-type: image/png'); 
  imagepng($image); 
  imagedestroy($image);

?>