<?php 

  define('GRAPH_WIDTH',         400);               // ������ �������� 
  define('GRAPH_HEIGHT',        300);               // ������ �������� 
  define('GRAPH_OFFSET_TOP',    40);                // ������ ������ 
  define('GRAPH_OFFSET_LEFT',   40);                // ������ ����� 
  define('GRAPH_OFFSET_RIGHT',  5);                 // ������ ������ 
  define('GRAPH_OFFSET_BOTTOM', 30);                // ������ ����� 

  define('FONT_NAME', 'arial.ttf');                 // ��� ������
  define('FONT_SIZE', 12);                          // ������ ������

  $colors = array(0xFF0000,0x00FF00,0x0000FF,     // ����� �������� 
                  0xFFFF00,0x00FFFF,0xFF00FF); 

  require('data.php');
  require('win2uni.php');

  // ������� ������ �������� 
  $col_width = (GRAPH_WIDTH - GRAPH_OFFSET_LEFT - GRAPH_OFFSET_RIGHT) / count($Data); 

  // ������� ������ �������, ���������������� ������������� �������� 
  $col_maxheight = (GRAPH_HEIGHT - GRAPH_OFFSET_TOP - GRAPH_OFFSET_BOTTOM); 

  // ���� ������������ �������� � �������, ��������������� ������� ������������ ������ 
  $max_value = max($Data); 

  $image = imagecreatetruecolor(GRAPH_WIDTH,GRAPH_HEIGHT) // ������� �����������... 
    or die('Cannot create image');     // ...��� ��������� ������ ������� � ������ ������ 

  imagefill($image, 0, 0, 0xFFFFFF);  // ����� ���

  // ������ ������� 
  $x = GRAPH_OFFSET_LEFT; 
  $y = GRAPH_OFFSET_TOP + $col_maxheight; 
  $i = 0; 
  foreach($Data as $name => $value) { 
    imagefilledrectangle(              // ������ �������� �������������
      $image, 
      $x, 
      $y - round($value*$col_maxheight/$max_value), 
      $x + $col_width - 1, 
      $y, 
      $colors[$i++%count($colors)]
    );

    // ������� �����:
    // .. �������������� � Unicode...
    $text = win2uni($name);
    // .. ������ ���������...
    $coord = imagettfbbox(FONT_SIZE,0,FONT_NAME,$text);
    $text_x = $x + ($col_width - $coord[2] - $coord[0]) / 2;
    $text_y = GRAPH_HEIGHT - 5;
    // .. � ����� ������
    imagettftext($image,FONT_SIZE,0,$text_x,$text_y,0x000000,FONT_NAME,$text);

    $x += $col_width;
  } 

  // ������� ���������
  $text = win2uni($Title);
  $coord = imagettfbbox(FONT_SIZE,0,FONT_NAME,$text);
  $text_x = $x + ($col_width - $coord[2] - $coord[0]) / 2;
  $text_y = (GRAPH_OFFSET_TOP - $coord[1] - $coord[7]) / 2; 
  imagettftext($image,FONT_SIZE,0,$text_x,$text_y,0x000000,FONT_NAME,$text);

  // ������ ������������ ��� 
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