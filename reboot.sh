#!/bin/bash
#MdeMoUcH 2018
#Script para poner en el arranque de la raspberry

sleep 2
sudo service mysql restart
sleep 2
sudo chmod a+rw /dev/ttyACM0
sleep 2
python /home/pi/termostato/termostato.py &
