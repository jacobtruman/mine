#echo "backing up videos from lightning to chell"
#rsync -azv --delete --progress /mine/backup/Videos/ /chell/d/Videos/
#echo "backing up everything from lightning to tidus"
#rsync -azv --delete --progress /mine/backup/* jtruman@tidus:/mnt/mine/
echo "backing up everything from /mine/backup/ to /mnt/backup/"
rsync -azv --delete --progress /mine/backup/* /mnt/backup/
