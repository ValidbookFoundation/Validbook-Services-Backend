**Get transaction records**
----
    Returns json data about transaction history

* **URL**

    /v1/wallet/transaction-records:page

* **Method:**

    `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

    `page=[integert]`

* **Data Params**


* **Success Response:**

* **Code:** 200 Ok <br />
**Content:**
    ```
   {
    "status": "success",
    "data": [
        {
            "amount": 7632819325,
            "type": "+",
            "created": "15 Nov 2017"
        }
    ]
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
        url: "/v1/wallet/transaction-records?page=2",
        dataType: "json",
        type : "GET",
    success : function(r) {
        console.log(r);
    }
    });
    ```