var request = require('request'),
  consumer_key = "YOUR_APP_CONSUMER_KEY",
  consumer_secret = "YOUR_APP_CONSUMER_SECRET",
  url = "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials"
  auth = "Basic " + new Buffer(consumer_key + ":" + consumer_secret).toString("base64");

  request(
    {
      url : url,
      headers : {
        "Authorization" : auth
      }
    },
    function (error, response, body) {
      // TODO: Use the body object to extract OAuth access token
    }
  )