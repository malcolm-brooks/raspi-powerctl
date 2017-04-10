#!/usr/bin/env python

"""
Control the Raspberry Pi's onboard GPIO ports to control
your device's power state.
"""

from time import sleep

import sys
import traceback

try:
	import RPi.GPIO as GPIO

	p=int(sys.argv[1])
	t=int(sys.argv[2])
	
	GPIO.setmode(GPIO.BOARD)
	GPIO.setup(p, GPIO.OUT)
	GPIO.output(p, True)
	sleep(t)
	GPIO.output(p, False)
	GPIO.cleanup()
except:
        traceback.print_exc(file=sys.stdout)
