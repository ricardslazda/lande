## Lande Home Task

This application allows users to calculate loan repayment schedules based on various input parameters such as principal amount, interest rate, loan term, and more.

## Getting Started

Before diving into the home task, let's ensure you have everything you need to get started smoothly.

### Prerequisites

- PHP >= 8.2.x
- Composer
- Docker >= 20.10.x

### Setup

- Clone .env-example file to .env.
    ```sh
    mv .env.example .env
    ```    
- Install composer dependencies.
    ```sh
    composer install
    ```
- Generate Laravel application key.
    ```sh
    php artisan key:generate
    ```
- Start docker containers.
    ```sh
    ./vendor/bin/sail up
    ```
- Run database migrations.
    ```sh
    php artisan migrate
    ```
- Generate Laravel Passport authentication keys.
    ```sh
    php artisan passport:keys
    ```
- Generate Swagger API documentation.
    ```sh
    php artisan l5-swagger:generate
    ```
  

### Tests
This application was developed using Test-Driven Development (TDD), and you can run the tests to ensure its functionality and reliability.

To run the tests, use the following command:

```sh
   php artisan test
```

### Accessing the API
API documentation is available at http://localhost/api/documentation.

![api_documentation.png](api_documentation.png)

1. Create new API user to generate a JWT token.

Request:
 ```sh
curl --location --request POST 'http://localhost:80/api/register' \
--form 'name="Ričards"' \
--form 'email="one@user.com"' \
--form 'password="123456789"' \
--form 'c_password="123456789"'
```

Response:
```sh
{"success":true,"data":{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZjc5ZjViOTdmNzc1NzliZGQzY2U3OWMzZmI1MzhiYTg2NGIwNzU1MDg0NGI5MjI3YTRkNzRmZjJlN2JhOGE5M2VkYjc2ZjU2ZTk2ZjE5MjMiLCJpYXQiOjE2OTY3ODU4ODcuOTg2NTE5LCJuYmYiOjE2OTY3ODU4ODcuOTg2NTIsImV4cCI6MTcyODQwODI4Ny45ODMzMTIsInN1YiI6IjIyIiwic2NvcGVzIjpbXX0.FyRKiJNoQTSjvkFUtcJYZh5jHCB-8VXQGvbeEf0-fZmjTnF4IBh67JnVc5lyq-fFmitR-tFmDbq1xuUvZ-hmSFKnk0W0TDhqnj3aZSgM0UEgIJsAMXWavK2XBSWWQXcKiHkXcoH1MOM_OwOaHFsLeLP-ttiRB8PC-J9ra4Yjt5dvVnxut9vSBI7THIlyHhOM_tl5BETXDYyq_Oo5nhQZPSIUiEtA4w6OuPObV1sUyM-bO_MT8uV9n57hGveRbhRL240K78Va5T0_MkqGvJI3ybytRXLQ2aA6ZuLdtmIt8y1LlxPqPYawjXCU9ieIj0N3aVrlw5V1ClXJgsCKwfhCWoJuNl_1K72VavcI26ee_IhUo_1UQqbZCYB89d9aZZhq9kehzodZDg5yVMmQt5xP70v_U7Uw9cYqkaYHff1Vp7r-9q91ZjKb-5XXt8SDdKrJNf1JexUZoI2r75Wyimor6xxadvAp5EbLh1n3YgofUSyZoO6AWh-uuvzO9Ezrm3JhtUMp8_ycPysvaMO3x1l7Ti8EZSjLCIjl3NgSmjABYPwN41B1bgNtVQIlTFcK5jmQlkMGTHXFmSC3sZva4N7fVX9r7D12vSOxTRCkXeSAkC4yq1Uw3kDGubh8lxofqv5bf3Ag63WkPtxZ0Y3LweRDmBZofs4ZO95gRy5ExnsUc8s","name":"Ri\u010dards"},"message":"User registered successfully."}
```

The generated JWT token will have to be added to each HTTP request header.

2. Calculate the loan schedule with provided parameters.

Request:
```sh
   curl --location --request POST 'http://localhost:80/api/loan-schedule/calculate' \
   --header 'Accept: application/json' \
   --header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZWMzNDNmMWEyNGU3MDM3MzhiOWM3YTFmYjg5ZjZmOTU4NGVkYjhiNzM2N2E0M2NmY2I4YjdmZjZhMDhlMThkNGZhNzEyOGU3NGVmZTViNDEiLCJpYXQiOjE2OTY3ODU4MTQuNDkwMjk4LCJuYmYiOjE2OTY3ODU4MTQuNDkwMywiZXhwIjoxNzI4NDA4MjE0LjQ2MjYzOCwic3ViIjoiMTkiLCJzY29wZXMiOltdfQ.kNzIDiV8zXPtdBFW58MHfLuyc5aD9gqEDIMPAxCxt6YfRLnS_QAus08Ur3dYv_MSBtsG_LnOplA1fQ8bQJt1vn6D5hlckh8ItMcxZfouPixLHqRv39R8TYmKkylHodbuouzRwOrNt-uUi9vizxLl53Il8tBi2mlKU8th0KRmXFlzP1ysl4ql9tvaywNlgzXlawcfspZkpFuNqJH02830SNPRctZ73jTzyO-UKwse1TskzkKNqbxoEwP9gi_d69Iau9emFx8dP_SESw4jDtxKdvWyyxhJBuFmlu4PFAb31-fjy4wR8yYwv4cF424Iu4imb3XLnpEZpkBhCHDt5MQlYUO6apg7omhepXAahKznjo0sv9C8d20HzdJOaxK_u0qTdWmOJ3e72P11uGOQhyoOzkVv5_jM1stxunAcITLIRGIhyKe89Ki6kucH--rZmm-QU3HPkUgM3Eyxfg9CaYYpDY9LPxSIPCVrj8vQF6kfbcXQpmSv2X4p5SG4IW_WEfBZjhHWU9D9IT48VubTj8w3ab1RzXgdR_KiCV9Gpv63VbZ1aV1pKPht48TE17Ctk38r5b003Uo0EgaXr7uEc9qJ5ukAHFubTQO6PUDwbIf46jY4mFuuIQa_MepdAABPLTlstpjS_wkyTNsDjADTG-c7WDzaR70kc_8Qo4clJII2dc8' \
   --form 'amount="50000"' \
   --form 'interestRate="534"' \
   --form 'euriborRate="254"' \
   --form 'term="16"'
```
Response:
```sh
{"success":true,"data":{"loanId":13,"segments":[{"segmentNumber":1,"principalPaymentInCents":2973,"interestPaymentInCents":222,"euriborPaymentInCents":105,"totalPaymentInCents":3302,"remainingPrincipalInCents":47026},{"segmentNumber":2,"principalPaymentInCents":2993,"interestPaymentInCents":209,"euriborPaymentInCents":99,"totalPaymentInCents":3302,"remainingPrincipalInCents":44032},{"segmentNumber":3,"principalPaymentInCents":3013,"interestPaymentInCents":195,"euriborPaymentInCents":93,"totalPaymentInCents":3302,"remainingPrincipalInCents":41019},{"segmentNumber":4,"principalPaymentInCents":3032,"interestPaymentInCents":182,"euriborPaymentInCents":86,"totalPaymentInCents":3302,"remainingPrincipalInCents":37986},{"segmentNumber":5,"principalPaymentInCents":3052,"interestPaymentInCents":169,"euriborPaymentInCents":80,"totalPaymentInCents":3302,"remainingPrincipalInCents":34933},{"segmentNumber":6,"principalPaymentInCents":3072,"interestPaymentInCents":155,"euriborPaymentInCents":73,"totalPaymentInCents":3302,"remainingPrincipalInCents":31860},{"segmentNumber":7,"principalPaymentInCents":3093,"interestPaymentInCents":141,"euriborPaymentInCents":67,"totalPaymentInCents":3302,"remainingPrincipalInCents":28767},{"segmentNumber":8,"principalPaymentInCents":3113,"interestPaymentInCents":128,"euriborPaymentInCents":60,"totalPaymentInCents":3302,"remainingPrincipalInCents":25654},{"segmentNumber":9,"principalPaymentInCents":3133,"interestPaymentInCents":114,"euriborPaymentInCents":54,"totalPaymentInCents":3302,"remainingPrincipalInCents":22520},{"segmentNumber":10,"principalPaymentInCents":3154,"interestPaymentInCents":100,"euriborPaymentInCents":47,"totalPaymentInCents":3302,"remainingPrincipalInCents":19366},{"segmentNumber":11,"principalPaymentInCents":3175,"interestPaymentInCents":86,"euriborPaymentInCents":40,"totalPaymentInCents":3302,"remainingPrincipalInCents":16191},{"segmentNumber":12,"principalPaymentInCents":3195,"interestPaymentInCents":72,"euriborPaymentInCents":34,"totalPaymentInCents":3302,"remainingPrincipalInCents":12995},{"segmentNumber":13,"principalPaymentInCents":3216,"interestPaymentInCents":57,"euriborPaymentInCents":27,"totalPaymentInCents":3302,"remainingPrincipalInCents":9778},{"segmentNumber":14,"principalPaymentInCents":3238,"interestPaymentInCents":43,"euriborPaymentInCents":20,"totalPaymentInCents":3302,"remainingPrincipalInCents":6540},{"segmentNumber":15,"principalPaymentInCents":3259,"interestPaymentInCents":29,"euriborPaymentInCents":13,"totalPaymentInCents":3302,"remainingPrincipalInCents":3280},{"segmentNumber":16,"principalPaymentInCents":3280,"interestPaymentInCents":14,"euriborPaymentInCents":6,"totalPaymentInCents":3302,"remainingPrincipalInCents":0}]},"message":"Loan schedule has been calculated successfully."}
```

3. Adjust euribor rate to an already existing loan.

euriborAdjustments[key]=value
- key - Segment index.
- value - Adjusted euribor rate in points.

Request:
```sh
   curl --location --request POST 'http://localhost:80/api/loan-schedule/adjust-euribor' \
   --header 'Accept: application/json' \
   --header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZWMzNDNmMWEyNGU3MDM3MzhiOWM3YTFmYjg5ZjZmOTU4NGVkYjhiNzM2N2E0M2NmY2I4YjdmZjZhMDhlMThkNGZhNzEyOGU3NGVmZTViNDEiLCJpYXQiOjE2OTY3ODU4MTQuNDkwMjk4LCJuYmYiOjE2OTY3ODU4MTQuNDkwMywiZXhwIjoxNzI4NDA4MjE0LjQ2MjYzOCwic3ViIjoiMTkiLCJzY29wZXMiOltdfQ.kNzIDiV8zXPtdBFW58MHfLuyc5aD9gqEDIMPAxCxt6YfRLnS_QAus08Ur3dYv_MSBtsG_LnOplA1fQ8bQJt1vn6D5hlckh8ItMcxZfouPixLHqRv39R8TYmKkylHodbuouzRwOrNt-uUi9vizxLl53Il8tBi2mlKU8th0KRmXFlzP1ysl4ql9tvaywNlgzXlawcfspZkpFuNqJH02830SNPRctZ73jTzyO-UKwse1TskzkKNqbxoEwP9gi_d69Iau9emFx8dP_SESw4jDtxKdvWyyxhJBuFmlu4PFAb31-fjy4wR8yYwv4cF424Iu4imb3XLnpEZpkBhCHDt5MQlYUO6apg7omhepXAahKznjo0sv9C8d20HzdJOaxK_u0qTdWmOJ3e72P11uGOQhyoOzkVv5_jM1stxunAcITLIRGIhyKe89Ki6kucH--rZmm-QU3HPkUgM3Eyxfg9CaYYpDY9LPxSIPCVrj8vQF6kfbcXQpmSv2X4p5SG4IW_WEfBZjhHWU9D9IT48VubTj8w3ab1RzXgdR_KiCV9Gpv63VbZ1aV1pKPht48TE17Ctk38r5b003Uo0EgaXr7uEc9qJ5ukAHFubTQO6PUDwbIf46jY4mFuuIQa_MepdAABPLTlstpjS_wkyTNsDjADTG-c7WDzaR70kc_8Qo4clJII2dc8' \
   --form 'loanId="12"' \
   --form 'euriborAdjustments[4]="400"' \
   --form 'euriborAdjustments[8]="700"'
```

Response:
```sh
{"success":true,"data":{"loanId":12,"segments":[{"segmentNumber":1,"principalPaymentInCents":2973,"interestPaymentInCents":222,"euriborPaymentInCents":105,"totalPaymentInCents":3302,"remainingPrincipalInCents":47026},{"segmentNumber":2,"principalPaymentInCents":2993,"interestPaymentInCents":209,"euriborPaymentInCents":99,"totalPaymentInCents":3302,"remainingPrincipalInCents":44032},{"segmentNumber":3,"principalPaymentInCents":3013,"interestPaymentInCents":195,"euriborPaymentInCents":93,"totalPaymentInCents":3302,"remainingPrincipalInCents":41019},{"segmentNumber":4,"principalPaymentInCents":3032,"interestPaymentInCents":182,"euriborPaymentInCents":86,"totalPaymentInCents":3302,"remainingPrincipalInCents":37986},{"segmentNumber":5,"principalPaymentInCents":3052,"interestPaymentInCents":169,"euriborPaymentInCents":126,"totalPaymentInCents":3302,"remainingPrincipalInCents":34933},{"segmentNumber":6,"principalPaymentInCents":3072,"interestPaymentInCents":155,"euriborPaymentInCents":116,"totalPaymentInCents":3302,"remainingPrincipalInCents":31860},{"segmentNumber":7,"principalPaymentInCents":3093,"interestPaymentInCents":141,"euriborPaymentInCents":106,"totalPaymentInCents":3302,"remainingPrincipalInCents":28767},{"segmentNumber":8,"principalPaymentInCents":3113,"interestPaymentInCents":128,"euriborPaymentInCents":95,"totalPaymentInCents":3302,"remainingPrincipalInCents":25654},{"segmentNumber":9,"principalPaymentInCents":3133,"interestPaymentInCents":114,"euriborPaymentInCents":149,"totalPaymentInCents":3302,"remainingPrincipalInCents":22520},{"segmentNumber":10,"principalPaymentInCents":3154,"interestPaymentInCents":100,"euriborPaymentInCents":131,"totalPaymentInCents":3302,"remainingPrincipalInCents":19366},{"segmentNumber":11,"principalPaymentInCents":3175,"interestPaymentInCents":86,"euriborPaymentInCents":112,"totalPaymentInCents":3302,"remainingPrincipalInCents":16191},{"segmentNumber":12,"principalPaymentInCents":3195,"interestPaymentInCents":72,"euriborPaymentInCents":94,"totalPaymentInCents":3302,"remainingPrincipalInCents":12995},{"segmentNumber":13,"principalPaymentInCents":3216,"interestPaymentInCents":57,"euriborPaymentInCents":75,"totalPaymentInCents":3302,"remainingPrincipalInCents":9778},{"segmentNumber":14,"principalPaymentInCents":3238,"interestPaymentInCents":43,"euriborPaymentInCents":57,"totalPaymentInCents":3302,"remainingPrincipalInCents":6540},{"segmentNumber":15,"principalPaymentInCents":3259,"interestPaymentInCents":29,"euriborPaymentInCents":38,"totalPaymentInCents":3302,"remainingPrincipalInCents":3280},{"segmentNumber":16,"principalPaymentInCents":3280,"interestPaymentInCents":14,"euriborPaymentInCents":19,"totalPaymentInCents":3302,"remainingPrincipalInCents":0}]},"message":"Euribor has been adjusted successfully."}
```
