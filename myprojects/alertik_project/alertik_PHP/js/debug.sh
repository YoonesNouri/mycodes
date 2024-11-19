#!/bin/sh
echo "arzlive"
cat merge-start.js lang.fa.js core.js tg.js arzlive.js tooltip.js merge-end.js  sparkline.js>../public-arzlive/s/s.js

echo "arzlive Various"
cat merge-start.js arzlive-va.js merge-end.js>../public-arzlive/s/va.js

echo "Atlas"
cat merge-start.js lang.fa.js core.js atlas/atlas.js merge-end.js>/tmp/atlas.js
java -jar ../../tools/external/compiler.jar --charset "utf-8" --compilation_level ADVANCED_OPTIMIZATIONS --externs ../../tools/external/jquery-1.7.js --js_output_file ../public-atlas/s/s.js --js /tmp/atlas.js
cat atlas/bootstrap.js atlas/highcharts.js atlas/jquery.clock.js atlas/jquery.flexslider-min.js atlas/chart.js>../public-atlas/s/lib.js

echo "Atlas Admin"
cat merge-start.js atlas-admin.js merge-end.js>/tmp/atd.js
java -jar ../../tools/external/compiler.jar --charset "utf-8" --compilation_level ADVANCED_OPTIMIZATIONS --externs ../../tools/external/jquery-1.7.js --js_output_file ../public-notify/s/admin.js --js /tmp/atd.js

echo "SMS"
cat merge-start.js sms.js placeholder.js merge-end.js>/tmp/sms.js
java -jar ../../tools/external/compiler.jar --charset "utf-8" --compilation_level ADVANCED_OPTIMIZATIONS --externs ../../tools/external/jquery-1.7.js --js_output_file ../public-notify/s/s.js --js /tmp/sms.js

echo "tg"
cat merge-start.js lang.fa.js core.js tg.js price_archiver.js tooltip.js merge-end.js>/tmp/tg.js
java -jar ../../tools/external/compiler.jar --charset "utf-8" --compilation_level ADVANCED_OPTIMIZATIONS --externs ../../tools/external/jquery-1.7.js --js_output_file ../public-tg/s/s.js --js /tmp/tg.js

echo "Mazanex"
cat merge-start.js lang.fa.js core.js mx.js timemachine.js merge-end.js>/tmp/mazanex.js
java -jar ../../tools/external/compiler.jar --charset "utf-8" --compilation_level ADVANCED_OPTIMIZATIONS --externs ../../tools/external/jquery-1.7.js --js_output_file ../public/static/s.js --js /tmp/mazanex.js

echo "Mazanex Mobile"
cat merge-start.js lang.fa.js core.js mobile.js merge-end.js>/tmp/mobile.js
java -jar ../../tools/external/compiler.jar --charset "utf-8" --compilation_level ADVANCED_OPTIMIZATIONS --externs ../../tools/external/jquery-1.7.js --js_output_file ../public-mobile/s/s.js --js /tmp/mobile.js

echo "MX Various"
cat merge-start.js mx-various.js merge-end.js>/tmp/mazanex-various.js
java -jar ../../tools/external/compiler.jar --charset "utf-8" --compilation_level ADVANCED_OPTIMIZATIONS --externs ../../tools/external/jquery-1.7.js --externs my-extern.js --js_output_file ../public/static/va.js --js /tmp/mazanex-various.js

echo "TGJU Various"
cat merge-start.js tg-various.js merge-end.js>/tmp/tg-various.js
java -jar ../../tools/external/compiler.jar --charset "utf-8" --compilation_level ADVANCED_OPTIMIZATIONS --externs ../../tools/external/jquery-1.7.js --externs my-extern.js --js_output_file ../public-tg/s/va.js --js /tmp/tg-various.js

echo "Ati"
cat merge-start.js lang.fa.js core.js ati.js tooltip.js merge-end.js>/tmp/ati.js
java -jar ../../tools/external/compiler.jar --charset "utf-8" --compilation_level ADVANCED_OPTIMIZATIONS --externs ../../tools/external/jquery-1.7.js --js_output_file ../public-ati/static/s.js --js /tmp/ati.js

echo "Ati Various"
cat merge-start.js ati-various.js merge-end.js>/tmp/ati-various.js
java -jar ../../tools/external/compiler.jar --charset "utf-8" --compilation_level ADVANCED_OPTIMIZATIONS --externs ../../tools/external/jquery-1.7.js --externs my-extern.js --js_output_file ../public-ati/static/va.js --js /tmp/ati-various.js
