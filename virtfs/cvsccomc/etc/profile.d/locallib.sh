#cPanel Added local::lib -- BEGIN
LOCALLIBUSER=$USER
if [ -e "/usr/bin/whoami" ]; then
        LOCALLIBUSER=`/usr/bin/whoami`
fi
if [ "$LOCALLIBUSER" != "root" ]; then
    eval $(perl -Mlocal::lib)
fi
#cPanel Added local::lib -- END
