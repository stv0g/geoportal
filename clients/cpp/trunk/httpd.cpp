class THttpd {

public:
	THttpd(boost::asio::io_service& io) : acceptor(io, tcp::endpoint(tcp::v4(), 6302)) {
		tcp::socket socket(io_service);
		acceptor.accept(socket);

		boost::asio::streambuf buf;
		boost::asio::read_until(socket, buf, "\r\n");

		istream request(&buf);

		string httpMethod;
		request >> httpMethod;
		string requestString;
		request >> requestString;

		string callback = requestString.substr(requestString.find("callback=")
				+ 9);
		callback = callback.substr(0, callback.find("&"));
		cout << callback << endl;

		std::ostringstream response;
		response << callback << "({\"lat\": " << gpsData->fix.latitude
				<< ", \"lon\": " << gpsData->fix.longitude << ", \"alt\": "
				<< gpsData->fix.altitude << "})";

		boost::system::error_code ignored_error;
		boost::asio::write(socket, boost::asio::buffer(response.str()),
				boost::asio::transfer_all(), ignored_error);
	}

private:
	tcp::acceptor acceptor;
}
