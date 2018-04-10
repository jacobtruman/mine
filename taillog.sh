log_dir="/raid/logs"

hashes="#############################################"
log_file="$log_dir/TVShowFetch_$(date +%Y-%m-%d).log"
log_file_yesterday="$log_dir/TVShowFetch_$(date +%Y-%m-%d --date='yesterday').log"
echo $hashes
echo "### $log_file_yesterday ###"
echo $hashes
cmd="grep \[+\] -A 10 $log_file_yesterday"
echo $cmd
$cmd

echo $hashes
echo "### $log_file ###"
echo $hashes
cmd="grep \[+\] -A 10 $log_file"
echo $cmd
$cmd
#grep '\[-\]' -A 20 TVShowFetch_$(date +%Y-%m-%d).log

cmd="tail $log_file_yesterday"
echo $cmd
$cmd

cmd="tail -n 20 $log_file"
echo $cmd
$cmd
