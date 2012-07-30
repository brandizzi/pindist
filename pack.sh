#!/usr/bin/env bash
# PIN Distributor (aka pindist): a FreePBX module for registering who has 
# received which PIN from various PIN sets.
# Copyright (C) 2012  Adam Victor Nazareth Brandizzi <brandizzi@gmail.com>
# 
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
# You can get the latest version of this file at 
# http://bitbucket.org/brandizzi/pindist
#
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
