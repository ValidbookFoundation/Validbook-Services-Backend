**Get Application Authorize Access Token**
----
  Returns json data about user in application authorization token.

* **URL**

  /v1/auth/authorize-token

* **Method:**

  `POST`
  
*  **URL Params**

  None  

* **Data Params**

  ```
   {
      "client_id":   	"test-wiki",
      "client_secret": 	"123456789",
      "redirect_uri": 	"http://wiki.local/index.php?title=Special:OAuth2Client/callback",
      "grant_type": 	"authorization_code",
      "code":       	"y_g0CWvXGZPcUh6N"
   }
  ```
    
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
  ```
   {
       "access_token" : "412342134fdsaf3ew54634563456",
       "expires_in" : 240000,
       "token_type" : "bearer"
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
      url: "/v1/auth/authorize-token",
      dataType: "json",
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```