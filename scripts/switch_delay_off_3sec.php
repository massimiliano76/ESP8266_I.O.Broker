<?php
function setPWM($url, $channel, $value) {
  getURL($url . "pwm?ch=" . $channel . "&set=" . $value, 0);
}
$ip = gg("ESP8266009b63a3.wanip");
$sw = gg("EESSPP.Switch");
$url = "http://$ip/";
$t_on = 3; // общее время включения
$c_on = 51; // количество шагов включения
$t_off = 3; // общее время выключения
//say($sw);
if ($sw == 1) {
  $value = 0;
  
  for ($i = 0; $i < $c_on; ++$i) { 
    $value += 5;
    setPWM($url, 0, $value);
    setPWM($url, 1, $value);
    setPWM($url, 2, $value);
    
    sleep(ceil($t_on / $c_on, 2));
  }
 setGlobal(("EESSPP.pwm0"),255); // устанавливаем принудительно слайдер канала 0 в макс.значение
 setGlobal(("EESSPP.pwm1"),255); // устанавливаем принудительно слайдер канала 1 в макс.значение
 setGlobal(("EESSPP.pwm2"),255); // устанавливаем принудительно слайдер канала 2 в макс.значение
}
if ($sw == 0) {
  $level0 = gg("EESSPP.pwm0");
  $level1 = gg("EESSPP.pwm1");
  $level2 = gg("EESSPP.pwm2");
  $max = max(array($level0, $level1, $level2));
  $step = ceil(($t_off * 1000) /  $max);
  $cycles = ceil($max / $step);
  for ($i = 0; $i < $cycles; ++$i) { 
    $level0 = $level0 - $step;
    $level1 = $level1 - $step;
    $level2 = $level2 - $step;
    
    setPWM($url, 0, $level0 <= 0 ? 0 : $level0);
    setPWM($url, 1, $level1 <= 0 ? 0 : $level1);
    setPWM($url, 2, $level2 <= 0 ? 0 : $level2);
    
   sleep(ceil($t_off / $cycles, 2));
  }
 setGlobal(("EESSPP.pwm0"),0); // устанавливаем принудительно слайдер канала 0 в мин.значение
 setGlobal(("EESSPP.pwm1"),0); // устанавливаем принудительно слайдер канала 1 в мин.значение
 setGlobal(("EESSPP.pwm2"),0); // устанавливаем принудительно слайдер канала 2 в мин.значение
}
