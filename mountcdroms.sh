echo "Eject cdrom0"
sudo eject /dev/sr0
echo "Eject cdrom1"
sudo eject /dev/sr1
echo "Close cdrom0"
sudo eject -t /dev/sr0
echo "Close cdrom1"
sudo eject -t /dev/sr1
echo "Sleep 10 seconds"
sleep 10
echo "Mount cdrom0"
sudo mount /dev/sr0 /media/cdrom0
echo "Mount cdrom1"
sudo mount /dev/sr1 /media/cdrom1
