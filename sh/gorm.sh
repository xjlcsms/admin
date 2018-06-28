#!/bin/bash

# 你可能需要修改下这个路径
prefix='/web/wwwroot/sifangqian.com/sfqv3/www/script'

arg=();

for((i=1;i<=$#;i++)); do
    var=$i
    pv="${!var}"
    prl=`expr length $pv`
    prv=`expr substr "$pv" 1 1`
    prc=`expr substr "$pv" 2 "$prl"`

    if [[ $prv = "-" ]]; then
        pv='+'${prc}
    fi

    arg[i]=$pv
done

/web/php70/bin/php -f ${prefix}"/gorm.php" ${arg[*]}
