HOST="wheatley"
YEARS=()
RE='^-?[0-9]+([.][0-9]+)?$'

strindex() { 
  x="${1%%$2*}"
  [[ $x = $1 ]] && echo -1 || echo ${#x}
}

for FILE in ./*.mkv; do
	if [[ -f $FILE ]]; then
		#echo "FILE: $FILE"
		START=$(($(strindex "$FILE" "(")+1))
		END=$(strindex "$FILE" ")")
		YEAR_LEN=$(($END-$START))
		if [[ $YEAR_LEN == 4 ]]; then 
			YEAR=${FILE:$START:$YEAR_LEN}
			if [[ $YEAR =~ $RE ]] ; then
			#	echo "YEAR: $YEAR"
				YEARS+=("$YEAR")
			#else
			#	echo "ERROR: Not a number - $YEAR"
			fi
		#else
		#	echo "ERROR: Year not found in filename - $FILE"
		fi
	#else
	#	echo "DIR: $FILE"
	fi
done

UNIQUE_YEARS=($(echo "${YEARS[@]}" | tr ' ' '\n' | sort -u | tr '\n' ' '))

for i in "${UNIQUE_YEARS[@]}"; do
	CMD="scp *$i*.mkv $HOST:/mine/Movies/$i/"
	#echo $CMD
	$CMD
done
