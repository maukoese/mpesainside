// Use base64 to encode the username and password.
String app_key = "YOUR_CONSUMER_KEY";
String app_secret = "YOUR_CONSUMER_SECRET";
String appKeySecret = app_key + ":" + app_secret;
byte[] bytes = usernameAndPassword.getBytes("ISO-8859-1");
String auth = Base64.encode(bytes);

OkHttpClient client = new OkHttpClient();

Request request = new Request.Builder()
  .url("https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials")
  .get()
  .addHeader("authorization", "Basic " + auth)
  .addHeader("cache-control", "no-cache")
  .build();

Response response = client.newCall(request).execute();