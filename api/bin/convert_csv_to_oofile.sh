#!/bin/bash

SCRIPT_DIR=`dirname $0`
export LANG=C

source ${SCRIPT_DIR}/xvfb_common.sh

find_free_servernum 

WHOAMI=`whoami`
if [ "$WHOAMI" = "apache" ]
then
    export HOME=/home/apache
    export DISPLAY=:${SERVERNUM}
fi

Xvfb :${SERVERNUM} -screen scrn0 800x600x16 2>/dev/null &
XVFB_PID=$!
echo "SaveAsOO(\"$1\", \"$2\", \"$3\", \"$4\", \"$5\", \"$6\")"
# --nofirststartwizard --nocrashreport --norestore --headless --nodefault --nologo
#ooffice --nofirststartwizard --nocrashreport --norestore --headless --nodefault --nologo --display :${SERVERNUM} ${SCRIPT_DIR}/convert_csv_to_openoffice_macros.ods  "macro://convert_csv_to_openoffice_macros/Standard.quiz.SaveAsOO(\"$1\", \"$2\", \"$3\", \"$4\", \"$5\", \"$6\")"
echo $?
kill ${XVFB_PID}

