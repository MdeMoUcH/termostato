# Termostato con arduino y raspberry pi





## Crontab
`
# m h  dom mon dow   command
@reboot                 /home/pi/termostato/reboot.sh >> /home/pi/scripts/log/em.log
0 0     * * *           php /home/pi/termostato/worker.php >> /home/pi/scripts/log/worker.log
`
