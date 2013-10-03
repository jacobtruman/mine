#echo "backing up videos from lightning to chell"
#rsync -azv --delete --progress /mine/backup/Videos/ /chell/d/Videos/
echo "backing up everything from chell to lightning"
rsync -azv --delete --progress /chell/d/* /mine/backup/
