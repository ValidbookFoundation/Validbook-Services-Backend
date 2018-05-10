**Get Count New Notifications**
----
  Returns json data about a count of new notifications and conversations.

* **URL**

  /v1/notifications/count-new:user_id

* **Method:**

  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

    `user_id=[integer]`

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
        "status": "success",
        "data": {
            "countNewNotification": 329,
            "countNewConversation": 18
        }
    }
    ```
 
* **Error Response:**

  * **Code:** 400 <br />
    **Content:** 
    
    ```
    {
       "status": "error",
       "errors": [
           {
               "code": 400,
               "message": "bad request options"
           }
       ]
    }
    ```

* **Sample Call:**

  ```javascript
    $.ajax({
      url: "/v1/notifications/count-new?user_id=1",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```