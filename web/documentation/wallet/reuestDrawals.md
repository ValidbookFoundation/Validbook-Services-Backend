**Create new request with drawal**
----
    Returns json data about request drawable custodial balance

* **URL**

    /v1/wallet/request-drawals

* **Method:**

    `POST`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

* **Data Params**

    ```
     {
       	"hc_address": "0xe3954b59340b92a01a2258251c56098cc6c485cc",
       	"amount": 50000000
      }
    ```

* **Success Response:**

* **Code:** 201 Created <br />
**Content:**
    ```
   {
       "status": "success",
       "data": {
           "data": true
       }
   }
    ```

* **Error Response:**

    * **Code:** 400 Bad Request <br />
    * **Code:** 401 Unauthorized <br />
    * **Code:** 404 NOT FOUND<br />
    * **Code:** 422 Unprocessable Entity <br />
    * **Code:** 500 Internal Server Error<br />
      **Content:** 
    ```
      {
        "status": "error",
        "errors": [
                {
                    "code": Code,
                    "message": string or []
                }
            ]
      }
    ```

* **Sample Call:**

    ```
    $.ajax({
        url: "/v1/wallet/request-drawals",
        dataType: "json",
        type : "POST",
    success : function(r) {
        console.log(r);
    }
    });
    ```