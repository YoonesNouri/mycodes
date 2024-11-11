#!/bin/sh

echo "arzlive"
cat normalizer-light.css layout-one.css flags2.css arzlive.css fonts.css >/tmp/sms.css
lessc /tmp/sms.css >/tmp/sms2.css
csso  /tmp/sms2.css >../public-arzlive/s/s.css

echo "atlas"
cat atlas/bootstrap-rtl.css atlas/bootstrap-responsive-rtl.css atlas/bootstrap-rtl.css atlas/analog.css atlas/flexslider.css atlas/flags.css atlas/main.css atlas/additional.css >../public-atlas/s/s.css

echo "andisheh"
cat atlas/bootstrap-rtl.css atlas/bootstrap-responsive-rtl.css atlas/bootstrap-rtl.css atlas/analog.css atlas/flexslider.css atlas/flags.css atlas/main.css atlas/additional.css atlas/andisheh.css >../public-andisheh/s/s.css

echo "tgju sms"
cat init.css sms.css >/tmp/sms.css
lessc /tmp/sms.css >/tmp/sms2.css
csso  /tmp/sms2.css >../public-notify/s/s.css

echo "tgju"
cat init.css tg.css tooltip.css flags.css >/tmp/tg.css
lessc /tmp/tg.css>/tmp/tg2.css
csso /tmp/tg2.css >../public-tg/s/s.css 

echo "mazanex"
cat init.css mx.css flags.css >/tmp/mazanex.css
csso /tmp/mazanex.css >../public/static/s.css 

echo "atirate"
cat init.css ati.css tooltip.css >/tmp/ati.css
csso /tmp/ati.css >../public-ati/static/s.css
