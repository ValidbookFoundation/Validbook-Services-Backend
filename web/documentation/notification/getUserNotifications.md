**Get User Notifications**
----
  Returns json data about a user notifications.

* **URL**

  /v1/notifications

* **Method:**

  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
      
*  **URL Params**
   `page=[integer]` </br>

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
        "status": "success",
        "data": [
            {
                "id": 1,
                "text": "<a target=\"_blank\" href=\"/john.smith\">John Smith</a> liked your story",
                "created": "04 Jul 2017",
                "user": {
                    "id": 5,
                    "fullName": "Michel Jard",
                    "slug": "michel.jard",
                    "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/200x200.png"
                }
            },
            {
                "id": 2,
                "text": "<a target=\"_blank\" href=\"/john.smith\">John Smith</a> commented on story",
                "created": "04 Jul 2017",
                "user": {
                    "id": 5,
                    "fullName": "Michel Jard",
                    "slug": "michel.jard",
                    "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/200x200.png"
                }
            }
        ]
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
      url: "/v1/notifications",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```