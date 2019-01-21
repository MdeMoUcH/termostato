# Termostato con Arduino y Raspberry Pi

Placa Arduino con Termistor para controlar la temperatura, pantalla LCD para mostrar información varia, LED RGB para mostrar el estado y relé para activar la calefacción. Conectada a una Raspberry Pi Zero W (puede usarse cualquier otra) para almacenar la informacion de la temperatura y controlar la calefacción con un interfaz web que también muestra información sobre la temperatura.





## Archivos

**arduino.ino** Código para meter en la placa Arduino.

**db.sql** Script para crear la base de datos MySQL.

**reboot.sh** Script para ejecutar en cada inicio de la Raspberry.

**termostato.py** Script para conectar la placa Arduino con la Raspbery.

**html** Carpeta con el código PHP para le interfaz web.




## Configuración para el crontab

```
# m h  dom mon dow   command

@reboot   /home/pi/termostato/reboot.sh >> /home/pi/scripts/log/em.log

0 0   * * *   php /home/pi/termostato/html/worker.php >> /home/pi/scripts/log/worker.log
```




## Partes usadas
* Raspberry Pi
* Arduino UNO
  * LCD 16x2
  * LED RGB
  * Relé
  * Termistor
  * Potenciometro, resistencias...
* Cables







## Esquema Arduino

![Esquema](arduino.png)

[circuit.io](https://www.circuito.io/app?components=512,11021,149486,341099,855863,3061987)








© [MdeMoUcH](http://www.twitter.com/mdemouch) | [La Gran M](http://www.lagranm.com) | [Ubuntu Fácil](http://www.ubuntufacil.com)
