**Get Access Token**
----
  Returns json data about status of user authorization via social network.

* **URL**

  /v1/auth/connect

* **Method:**

  `POST`
  
*  **URL Params**

  None  

* **Data Params**

  ```
    {
        "provider": "facebook",
        "avatar": "http://graph.facebook.com/67563683055/picture?type=normal",
        "token": "AJADY7ZCsmyzZAuiJwdUPuBUP0g7iNwsT2p3mZCLj1G3oCQEzO1pBBNkrXkA8ZBHDY9epLvJElrO3XlPRWGXqj3S7IZB"
    }
  ```
    
* **Success Response:**

  * **Code:** 200 Ok <br />
  * **Code:** 201 Created <br />
    **Content:** 
  ```
    {
      "status": "success",
      "data": {
        "id": 1,
        "access_token": "axppGa62A5Y1t7fka99nSq4b5GTTiYpl",
        "email": "john@smith.com",
        "first_name": "John",
        "last_name": "Smith",
        "slug": "john-smith",
        "avatar_url": "http://placehold.it/200x200"
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
      url: "/v1/auth/connect",
      dataType: "json",
      data: {
          provider: "facebook", 
          avatar: "http://graph.facebook.com/67563683055/picture?type=normal", 
          token: "AJADY7ZCsmyzZAuiJwdUPuBUP0g7iNwsT2p3mZCLj1G3oCQEzO1pBBNkrXkA8ZBHDY9epLvJElrO3XlPRWGXqj3S7IZB"
      },
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```