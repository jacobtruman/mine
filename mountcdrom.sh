#!/bin/bash
if [ $1 ] ; then
	echo "Eject cdrom$1"
	sudo eject /dev/sr$1
	echo "Close cdrom$1"
	sudo eject -t /dev/sr$1
	echo "Sleep 10 seconds"
	sleep 10
	echo "Mount cdrom$1"
	sudo mount /dev/sr$1 /media/cdrom$1
else
        echo "You need to specify a drive number (0/1)"
        echo "Ex:       mountcdrom 0"
fi
