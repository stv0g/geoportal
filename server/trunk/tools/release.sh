#!/bin/sh
# vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:

##
# GeoPortal Server
#
# release script
#
# @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/tools/release.sh $
# @package		tools
# @author		Steffen Vogel (info@steffenvogel.de)
# @modifedby	$LastChangedBy: steffen $
# @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
# @license		http://www.gnu.org/licenses/gpl.html
# @link			http://www.geoportal.griesm.de
# @since		1
# @version		$Revision: 42 $
# @lastmodifed	$Date: 2009-04-25 10:23:48 +0200 (Sa, 25 Apr 2009) $
##

# variables
VERSION=0.1
 
# extract from svn
cd /tmp
mkdir geoportal-server-$VERSION
svn export --force http://svn.griesm.de/geo/server/trunk/ geoportal-server-$VERSION

# minimize javascripts

# replace placeholders

# create docs

# create archive
tar -c --no-same-owner --no-same-permissions -z -f geoportal-server-$VERSION.tar.gz geoportal-server-$VERSION