** Submit recover password **
----
  Returns json data about user recover password.

* **URL**

  /v1/auth/submit-recover

* **Method:**

  `POST`
  
*  **URL Params**

  None  

* **Data Params**

  ```
    {
        "hash" : "pK_Hpfz8Xde1gGdssMZROmojIfOgBM6X",
   	     "new_password": "123123",
   	     "confirm_password": "123123"
    } 
  ```
    
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
  ```
   {
       "status": "success",
       "data": []
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
      url: "/v1/auth/submit-recover",
      dataType: "json",
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```