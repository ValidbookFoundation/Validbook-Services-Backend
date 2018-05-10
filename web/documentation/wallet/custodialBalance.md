**Get custodial balance**
----
    Returns json data about custodial balance

* **URL**

    /v1/wallet/custodial-balance

* **Method:**

    `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

* **Data Params**


* **Success Response:**

* **Code:** 200 Ok <br />
**Content:**
    ```
  {
      "status": "success",
      "data": {
          "custodial_balance": 12721365491.66
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
        url: "/v1/wallet/custodial-balance",
        dataType: "json",
        type : "GET",
    success : function(r) {
        console.log(r);
    }
    });
    ```