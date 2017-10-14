# MPESAOPay
Simplified Lipa Na MPESA Online integration.

## Getting Started

Please read [USING.md](https://gist.github.com/maukoese/b24679402957c63ec426) for details on how to integrate this class in your custom project.

There is sample code in [use.php](#) to get you started

### Prerequisites

A running PHP server.
A database system - MySQL/MariaDB/SQLite/PostgreSQL/Mongo

```
Give examples
```

### Installing

Grab a copy of Jabali from here

```
Give the example
```

And repeat

```
until finished
```

End with an example of getting some data out of the system or using it for a little demo

## Running the tests

Explain how to run the automated tests for this system

### Break down into end to end tests

Explain what these tests test and why

```
Give an example
```

### And coding style tests

Explain what these tests test and why

```
Give an example
```

## Deployment

Add additional notes about how to deploy this on a live system

## Built With

* [PHP](http://php.net) -PHP Hypertext Preprocessor
* [JavaScript](https://javascript.net) - JavaScript

## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [CalVer]( http://calver.org ) for versioning. For the versions available, see the [tags on this repository](https://github.com/maukoese/mpesapay/tags). 

## Authors

* **Mauko Maunde** - *Initial work* - [Jabali CMS](https://github.com/maukoese/jabali)

See also the list of [contributors](https://github.com/maukoese/jabali/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

## Acknowledgments

* Hat tip to anyone who's code was used
* Inspiration
* etc

## Command ID Description
* [TransactionReversal]	Reversal for an erroneous C2B transaction.
* [SalaryPayment] Used to send money from an employer to employees e.g. salaries
* [BusinessPayment] Used to send money from business to customer e.g. refunds
* [PromotionPayment] Used to send money when promotions take place e.g. raffle winners
* [AccountBalance] Used to check the balance in a paybill/buy goods account (includes utility, MMF, Merchant, Charges paid account).
* [CustomerPayBillOnline] Used to simulate a transaction taking place in the case of C2B Simulate Transaction or to initiate a transaction on behalf of the customer (STK Push).
* [TransactionStatusQuery] Used to query the details of a transaction.
* [CheckIdentity]	Similar to STK push, uses M-Pesa PIN as a service.
* [BusinessPayBill]	Sending funds from one paybill to another paybill
* [BusinessBuyGoods]	sending funds from buy goods to another buy goods.
* [DisburseFundsToBusiness]	Transfer of funds from utility to MMF account.
* [BusinessToBusinessTransfer]	Transferring funds from one paybills MMF to another paybills MMF account.
* [BusinessTransferFromMMFToUtility]	Transferring funds from paybills MMF to another paybills utility account.

## B2C Query Parameters

* Parameter	Description
* InitiatorName	This is the credential/username used to authenticate the transaction request.
* SecurityCredential	Base64 encoded string of the B2C short code and password, which is encrypted using M-Pesa public key and validates the transaction on M-Pesa Core system.
* CommandID	Unique command for each transaction type e.g. SalaryPayment, BusinessPayment, PromotionPayment
* Amount	The amount being transacted
* PartyA	Organization’s shortcode initiating the transaction.
* PartyB	Phone number receiving the transaction
* Remarks	Comments that are sent along with the transaction.
* QueueTimeOutURL	The timeout end-point that receives a timeout response.
* ResultURL	The end-point that receives the response of the transaction
* Occasion	Optional

## B2B - Request Parameters

* Parameter	Description
* Initiator	This is the credential/username used to authenticate the transaction request.
* SecurityCredential	Base64 encoded string of the B2B short code and password, which is encrypted using M-Pesa public key and validates the transaction on M-Pesa Core system.
* CommandID	Unique command for each transaction type, possible values are: BusinessPayBill, MerchantToMerchantTransfer, MerchantTransferFromMerchantToWorking, MerchantServicesMMFAccountTransfer, AgencyFloatAdvance
* Amount	The amount being transacted.
* PartyA	Organization’s short code initiating the transaction.
* SenderIdentifier	Type of organization sending the transaction.
* PartyB	Organization’s short code receiving the funds being transacted.
* RecieverIdentifierType	Type of organization receiving the funds being transacted.
* Remarks	Comments that are sent along with the transaction.
* QueueTimeOutURL	The path that stores information of time out transactions.it should be properly validated to make sure that it contains the port, URI and domain name or publicly available IP.
* ResultURL	The path that receives results from M-Pesa it should be properly validated to make sure that it contains the port, URI and domain name or publicly available IP.
* AccountReference	Account Reference mandatory for “BusinessPaybill” CommandID.

## B2B Response Parameters

* ConversationID	A unique numeric code generated by the M-Pesa system of the response to a request.
* OriginatorConversationID	A unique numeric code generated by the M-Pesa system of the request.
* ResponseDescription	A response message from the M-Pesa system accompanying the response to a request.

## C2B Register URL - Request Parameters

* ValidationURL	Validation URL for the client.
* ConfirmationURL	Confirmation URL for the client.
* ResponseType	Default response type for timeout.
* ShortCode	The short code of the organization.
* Register URL - Response Parameters

* ConversationID	A unique numeric code generated by the M-Pesa system of the response to a request.
* OriginatorConversationID	A unique numeric code generated by the M-Pesa system of the request.
* ResponseDescription	A response message from the M-Pesa system accompanying the response to a request.

## Identifier Types

Identifier types - both sender and receiver - identify an M-Pesa transaction’s sending and receiving party as either a shortcode, a till number or a MSISDN (phone number). There are three identifier types that can be used with M-Pesa APIs.

Identifier	Identity
* 1	MSISDN
* 2	Till Number
* 4	Shortcode