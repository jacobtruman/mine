cd /raid/logs/
grep '\[+\]' -A 20 TVShowFetch_$(date +%Y-%m-%d).log
#grep '\[-\]' -A 20 TVShowFetch_$(date +%Y-%m-%d).log
tail TVShowFetch_$(date +%Y-%m-%d --date="yesterday").log
tail -n 20 TVShowFetch_$(date +%Y-%m-%d).log
