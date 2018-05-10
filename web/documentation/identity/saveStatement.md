**Save statement**
----
  Returns json data: status of linking statement to identity

* **URL**

  /v1/identities/save-statement

* **Method:**

  `POST`

*  **Request Headers**

  `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`

*  **URL Params**

  None

* **Data Params**

  ```
   {
     "statement": "__FILE__"
   }
  ```

* **Success Response:**

  * **Code:** 200 <br />
    **Content:**
  ```
   {
       "status": "success",
       "data": true
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
      url: "/v1/identities/save-statement",
      dataType: "json",
      type : "POST",
      data : {
        "statement": "__FILE__"
      },
      success : function(r) {
        console.log(r);
      }
    });
  ```