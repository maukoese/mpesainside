require 'net/http'
require 'net/https'
require 'uri'

uri = URI('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials')

Net::HTTP.start(uri.host, uri.port,
  :use_ssl => uri.scheme == 'https',
  :verify_mode => OpenSSL::SSL::VERIFY_NONE)

do |http|

  request = Net::HTTP::Get.new uri.request_uri
  request.basic_auth 'YOUR_APP_CONSUMER_KEY', 'YOUR_APP_CONSUMER_SECRET'

  response = http.request request # Net::HTTPResponse object

  puts response
  puts response.body
end