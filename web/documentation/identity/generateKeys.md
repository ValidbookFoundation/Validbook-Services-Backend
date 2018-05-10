**Generate keys**
----
  Returns json data about status of keys generation

* **URL**

  /v1/identities/generate-keys

* **Method:**

  `POST`
  
*  **Request Headers**
  
  `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
  
*  **URL Params**

  None  

* **Data Params**

  ```
   {
     "address": "0x7e2b048628ee1e00c8fc85e32caa323028b7c79f",
     "recovery_address": "0xe1200267dc5ef98ca13f710338688c5553cffe19ce462"
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
      url: "/v1/identities/generate-keys",
      dataType: "json",
      type : "POST",
      data : {
        "address": "0x7e2b048628ee1e00c8fc85e32caa323028b7c79f",
        "recovery_address": "0xe1200267dc5ef98ca13f710338688c5553cffe19ce462"
      },
      success : function(r) {
        console.log(r);
      }
    });
  ```