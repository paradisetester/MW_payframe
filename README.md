# Project Title

Merchant worrier Payment code

## Getting Started

Getting fields data from form:

`````````
https://pyramiddisplays.com.au/testmw/ 
````````````

In POST form.

When form submitted the card details sent to MW which will get HASH,payframeToken number and payframeKey.

Find Code in MW-payonline.php

If we get all the above fields from MW then the form details with card details are sent back to MW using curl...

Then in the end MW send us transaction Message whether the payment is success of declined.

Find code in Custom_mw-function.php

### Installing

You need API details

Add your Merchant details here:

```
merchantUUID = 'MERCHANDUUID';
apiKey = 'APIKEY';
passphrase = 'PASSWORD';

```
And if you are working with live API

You need to add these API urls:
``````
https://base.merchantwarrior.com/payframe/ 
````````
``````
https://api.merchantwarrior.com/payframe/
``````

## Running the tests

Test Credit cards:

Card Number	Expiry Date	CVN	Description
`````````
4564710000000004	02/29	847	Visa Approved
``````
``````
5163200000000008	08/20	070	MC Approved
``````
``````
4564710000000012	02/05	963	Visa Expired
````````
```````
4564710000000020	05/20	234	Visa Low Funds ($10 credit limit)
````````
``````
5163200000000016	12/19	728	MC Stolen
````````
``````
4564720000000037	09/29	030	Visa Invalid CVV2
````````
``````
376000000000006	06/20	2349	Amex
````````
``````
343400000000016	01/29	9023	Amex Restricted
````````
``````
36430000000007	06/22	348	Diners
````````
``````
36430000000015	08/21	988	Diners Stolen
````````
``````
All Others	N/A	N/A	All unknown cards are rejected
````````


