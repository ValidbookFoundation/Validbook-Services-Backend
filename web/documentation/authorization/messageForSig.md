**Get Message For Signature Login**
----
  Returns json data.

* **URL**

  /v1/auth/message-for-sig:address

* **Method:**

  `GET`
  
*  **URL Params**

  `address=[string]`  

* **Data Params**

  ```
 
  ```
    
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
  ```
   {
       "status": "success",
       "data": {
           "message": 1312312312
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

  ```javascript
    $.ajax({
      url: "/v1/auth/message-for-sig?address=0x24534523453dgfhdfghdf456754",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```