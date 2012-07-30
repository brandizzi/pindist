#!/usr/bin/env bash
if [ "$PROJECT_HOME" ]
then
  HOMEDIR=$PROJECT_HOME
else
  HOMEDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
fi

TO_PACK=${1///}

case "$TO_PACK" in
	'clean')
		find . -name '*~' -exec rm {} \;
		exit 0
	;;
	'pack')
		VERSION=$(sed -n '/<version>/!d;s|<version>\(.*\)</version>|\1|p;q' \
		            module.xml | tr -d '\t\n ')
		DISTFILE=$HOMEDIR/dist/pindist-$VERSION.tgz
		[ -f "$DISTFILE" ] && rm $DISTFILE
		cd $HOMEDIR/..
		tar zvcf $DISTFILE \
			$(find pindist -name '*.php' -o -name '*.css' -o -name '*.png'  \
			        | egrep -v 'pindist/(doc|dist)' | sed 's|^\./||') \
			pindist/{install.sql,uninstall.sql,module.xml}
	;;
	*)
		echo "Uso:"
		echo "    $0 clean|scc"
esac
