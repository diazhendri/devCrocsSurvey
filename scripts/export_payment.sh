#!/bin/bash
FILE="/var/www/simpi_new/downloads/payment_simpi_123_51.csv /File/SIMPI/OUT/simpi.csv"
ftp -inv <<EOF

open 10.35.65.191
user anonymous anonymous
put $FILE
exit
EOF
