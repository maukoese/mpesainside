require 'net/http'
require 'net/https'
require 'uri'

uri = URI('https://sandbox.safaricom.co.ke/mpesa/b2b/v1/paymentrequest')

http = Net::HTTP.new(uri.host, uri.port)
http.use_ssl = true
http.verify_mode = OpenSSL::SSL::VERIFY_NONE

request = Net::HTTP::Get.new(uri)
request["accept"] = 'application/json'
request["content-type"] = 'application/json'
request["authorization"] = 'Bearer <Access-Token>'
request.body = "{\"Initiator\": \" \",
      \"SecurityCredential\": \" \",
      \"CommandID\": \" \",
      \"SenderIdentifierType\": \" \",
      \"RecieverIdentifierType\": \" \",
      \"Amount\": \" \",
      \"PartyA\": \" \",
      \"PartyB\": \" \",
      \"AccountReference\": \" \",
      \"Remarks\": \" \",
      \"QueueTimeOutURL\": \"http://your_timeout_url\",
      \"ResultURL\": \"http://your_result_url\"}"

response = http.request(request)
puts response.read_body