**Get statements**
----
  Returns json data: identity public statements

* **URL**

  /v1/identities/get-statements

* **Method:**

  `GET`

*  **Request Headers**

*  **URL Params**

  `identity=[string]` <br/>

* **Data Params**


* **Success Response:**

  * **Code:** 200 <br />
    **Content:**
  ```
   {
       "status": "success",
       "data":
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

  ```javascript
    $.ajax({
      url: "/v1/identities/get-statements",
      dataType: "json",
      type : "GET",
      data : {
        "identity": "jimbocry777"
      },
      success : function(r) {
        console.log(r);
      }
    });
  ```