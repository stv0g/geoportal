class TUpdater {
public:
	TUpdater(boost::asio::io_service& io) :
		timer(io, boost::posix_time::seconds(1)), count(0) {

		timer.async_wait(boost::bind(&updater::update, this));
		CURL * ch = curl_easy_init();
		curl_easy_setopt(ch, CURLOPT_URL, geoUrl.c_str());
		curl_easy_setopt(ch, CURLOPT_POSTFIELDS, postStr.c_str());
		curl_easy_setopt(ch, CURLOPT_WRITEFUNCTION, writer);
		curl_easy_setopt(ch, CURLOPT_WRITEDATA, &buffer);
		curl_easy_setopt(ch, CURLOPT_TIMEOUT, 5);
		curl_easy_setopt(ch, CURLOPT_ERRORBUFFER, curl_error);
	}

	~printer() {
		std::cout << "Final count is " << count << "\n";
	}

	void update() {
		int curl_rc = curl_easy_perform(ch);
		if (curl_rc != 0) {
			std::cerr << curl_rc << ": " << curl_error << std::endl;
			return -1;
		}

		curl_easy_cleanup(ch);
		std::cout << buffer << std::endl;
		buffer.clear();

		timer.expires_at(timer.expires_at() + boost::posix_time::seconds(1));
		timer.async_wait(boost::bind(&printer::print, this));
	}

private:
	boost::asio::deadline_timer timer;
	int count;
	char curl_error[CURL_ERROR_SIZE];
	std::string buffer;

	static int writer(char *data, size_t size, size_t nmemb,
			std::string *stream) {
		if (stream == NULL)
			return 0;

		stream->append(data, size * nmemb);
		return size * nmemb;
	}
};
