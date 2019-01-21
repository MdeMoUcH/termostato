# Termostato con arduino y raspberry pi
Termostato con arduino que manda la información a la raspberry pi que la va almacenando.


## Esquema Arduino

![Esquema](arduino.png)
[circuit.io](https://www.circuito.io/app?components=512,11021,149486,341099,855863,3061987)










## Configuración para el crontab

```
# m h  dom mon dow   command

@reboot   /home/pi/termostato/reboot.sh >> /home/pi/scripts/log/em.log

0 0   * * *   php /home/pi/termostato/html/worker.php >> /home/pi/scripts/log/worker.log
```




© [MdeMoUcH](http://www.twitter.com/mdemouch) | [La Gran M](http://www.lagranm.com) | [Ubuntu Fácil](http://www.ubuntufacil.com)
