/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal C++ Client
 *
 * Main
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/index.php $
 * @package		core
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		1
 * @version		$Revision: 42 $
 * @lastmodifed	$Date: 2009-04-25 10:23:48 +0200 (Sa, 25 Apr 2009) $
 */

#include <iostream>
#include <fstream>
#include <string>
#include <cstdio>
#include <openssl/sha.h>
#include <libgpsmm.h>

#include <boost/lexical_cast.hpp>
#include <boost/regex.hpp>
#include <boost/asio.hpp>

#include <unistd.h>
#include <curl/curl.h>

using namespace boost;
using boost::asio::ip::tcp;

int main(int argc, char* argv[]) {
	// default values
	std::string gpsdHost = "localhost";
	std::string gpsdPort = "2947";
	std::string geoUrl =
			"http://localhost/workspace/GeoAPI/trunk/driver/input/update.php";
	std::string username = "steffen";
	std::string password = "testme";
	unsigned char pwHash[SHA_DIGEST_LENGTH];
	int updateIntervall = 60; // in seconds
	int markerId = 22; // which marker should i update?

	// read cli arguments
	int i = 0;
	while (argv[++i] != NULL) {
		boost::regex re("^(-[a-z]|-{2}[a-z]*)(?:=([a-zA-ZäöüßÄÖÜ0-9:./]+))?$");
		boost::cmatch matches;

		if (boost::regex_match(argv[i], matches, re)) {
			std::string par = matches[1].str();

			if (par == "-h" || par == "--help") {
				std::cout << argv[0] << " Version 1.0" << std::endl
						<< "usage: GeoApiClient [options]" << std::endl
						<< "  -u, --username\t username for the server"
						<< std::endl
						<< "  -p, --password\t password for the server"
						<< std::endl
						<< "  -m, --marker\t\t which marker do you want to update?"
						<< std::endl
						<< "  -i, --interval\t interval in seconds"
						<< std::endl << std::endl
						<< "  -g, --gpsd\t\t host[:port]" << std::endl;
				return 0;
			} else if (par == "-u" || par == "--username") {
				username = matches[2].str();
			} else if (par == "-p" || par == "--password") {
				password = matches[2].str();
			} else if (par == "-m" || par == "--marker") {
				markerId = lexical_cast<int> (matches[2].str());
			} else if (par == "-i" || par == "--interval") {
				updateIntervall = lexical_cast<int> (matches[2].str());
			} else if (par == "-g" || par == "--gpsd") {
				boost::regex
						re(
								"^((?:[0-9a-zA-Z][-\\w]*[0-9a-zA-Z]\\.)+[a-zA-Z]{2,9}|\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3})(?::(\\d{1,5}))?$");
				boost::cmatch sub_matches;
				if (boost::regex_match(matches[2].str().c_str(), sub_matches,
						re)) {
					gpsdHost = sub_matches[1].str();
					gpsdPort = sub_matches[2].str();
				}
			} else {
				std::cerr << "unrecognized parameter: " << argv[i] << std::endl;
			}
		} else
			std::cerr << "unrecognized parameter: " << argv[i] << std::endl;
	}

	// sha1 pw hash
	SHA1(reinterpret_cast<const unsigned char *> (password.c_str()),
			password.length(), pwHash);

	// initialize gps
	gpsmm gpsDevice;
	struct gps_data_t *gpsData;
	if (gpsDevice.open(gpsdHost.c_str(), gpsdPort.c_str()) == NULL) {
		std::cerr << "Connection to gpsd deamon failed!" << std::endl;
		return -1;
	}

	try {
		boost::asio::io_service io;

		TUpdater upd(io);
		THttpd httpd(io);

		struct SLatLon getPos(gpsmm device) {
			gpsData = gpsDevice.query("o");

		}

		io.run();
	} catch (std::exception& e) {
		std::cerr << e.what() << std::endl;
	}
	return 0;
}

