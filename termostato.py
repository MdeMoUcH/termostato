#!/usr/bin/python
# -*- coding: utf-8 -*-
# MdeMoUcH 2018


'''
http://zetcode.com/db/mysqlpython/
sudo apt-get install python-mysqldb python-serial
'''

import serial;
import time;
from datetime import date;
import MySQLdb as mdb;
import sys;


temp_caldera = 24.50


try:
	con = mdb.connect('localhost', 'termostato', 'termostato', 'em');
	cur = con.cursor();
	ser = serial.Serial('/dev/ttyACM0',9600);
	while(1):
		linea = ser.readline();
		linea = linea.replace('\r', '').replace('\n', '');
		tiempo = str(date.fromtimestamp(time.time())) + ' ' + time.strftime("%H:%M:00");
		cur.execute(str('INSERT INTO em_data (lugar,temperatura,fecha) VALUES ("test","'+linea+'","'+tiempo+'");'));
		time.sleep(1);

		status = '0';
		cur.execute(str('SELECT status FROM em_status ORDER BY fecha DESC LIMIT 1;'));
		for row in cur.fetchall():
			status = row[0];
		
		if status == '1':
			if float(linea) > float(temp_caldera):
				#print str(linea)+' > '+str(temp_caldera)
				status = '2';
			else:
				#print str(linea)+' < '+str(temp_caldera)
				status = '1';
		else:
			status = '0';

		ser.flush();
		#ser.write(status.encode());
		ser.write(status);
		time.sleep(2);
		ser.flush();
		
		print tiempo+' - '+linea+'ºC - '+status;

except mdb.Error, e:
	print "Error %d: %s" % (e.args[0],e.args[1])
	sys.exit(1)


