#!/bin/bash

#
# Script to update statsvn statistics
#

HTDOCS=/home/steffen/htdocs/geoportal/
REPO=/var/svn/repos/geo

cd $HTDOCS
svn update ./svn
svn log -v --xml file://$REPO > geo.log

statsvn -output-dir stats/ -trac http://www.trac.griesm.de/geo -title GeoPortal -exclude "**/*.svg" -exclude "**/openlayers/*" geo.log svn/