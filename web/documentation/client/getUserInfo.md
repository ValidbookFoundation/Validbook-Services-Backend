** User Info Resource**
----
  Returns json data about a authorized user.

* **URL**

  /v1/client/user-info

* **Method:**

  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
        "id": 1,
        "username": "John Smith",
        "email": "john.smith@example.com"
        "avatar": "http://placehold.it/32x32"
    }
    ```
 
* **Error Response:**

  * **Code:** 404 <br />
    **Content:** 
    
    ```
    {
      "status": "error",
      "errors": [
        {
          "message": "User doesn't exist",
          "code": 404
        }
      ]
    }
    ```

* **Sample Call:**

  ```javascript
    $.ajax({
      url: "/v1/client/user-info",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```