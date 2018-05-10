**Save statement**
----
  Returns json data: status of creating purpose key

* **URL**

  /v1/identities/create-purpose-key

* **Method:**

  `POST`

*  **Request Headers**

  `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`

*  **URL Params**

  None

* **Data Params**

  ```
   {
     "address" : "0x0C28aF1ccB4298c4305A6376A050Cb7234Eae3B0",
     "purpose" : "Test purpose"
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
      url: "/v1/identities/create-purpose-key",
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