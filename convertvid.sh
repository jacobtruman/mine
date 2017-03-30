#!/bin/bash
if [ $1 ] && [ $2 ] ; then
	INFO=$(mediainfo $1 --Output=XML)
	#echo $INFO
	# disable date auto correct
	ORIG_DATE=$(date)
	echo $ORIG_DATE
	sudo timedatectl set-ntp 0
	DATE=$(xmllint --xpath "string(//Encoded_date)" - <<< "$INFO")
	if [ "$DATE" == "" ] ; then
		echo "No encode date found; using file last modified date"
		DATE=$(stat -c %y $1)
		echo $DATE
	fi
	if [ "$DATE" != "" ] ; then
		echo $DATE
		sudo date -s "$DATE"
	else
		echo "Still no date found; using current system time"
	fi
	ROTATION=$(xmllint --xpath "string(//Rotation)" - <<< "$INFO")
	#echo $ROTATION
	if [ $ROTATION == "270°" ] ; then
		#ROTATION_OPTION="--rotate=7"
		ROTATION_OPTION="--rotate=angle=270"
	elif [ $ROTATION == "90°" ] ; then
		#ROTATION_OPTION="--rotate=4"
		ROTATION_OPTION="--rotate=angle=90"
	else
		ROTATION_OPTION=""
	fi
	CMD="HandBrakeCLI -i $1 -o $2 $HANDBRAKE_OPTIONS $ROTATION_OPTION"
	echo $CMD
	if [ $3 ] && [ $3 === "false" ] ; then
		echo "DRY RUN MODE"
	else
		$CMD
	fi
	# re-enable date auto correct
	sudo date -s "$ORIG_DATE"
	sudo timedatectl set-ntp 1
	sudo ntpdate -s time.nist.gov
else
	echo "Missing either the source or destination parameter"
fi
