**Change Quiet Mode Notification**
----
  Returns json data about status of changing quiet mode notifications.

* **URL**

  /v1/users/change-quiet-mode

* **Method:**

  `PATCH`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

* **Data Params**

  ```
    {
     "calm_mode_notifications": 1,
    }
  ```
    
* **Success Response:**

  * **Code:** 200 OK <br />
    **Content:** 
  ```
    {
      "status": "success",
      "data": [
      "calm_mode_notifications" : 1
      ]
    }
  ```
 
* **Error Response:**

   * **Code:** 404 <br />
    **Content:** 
  ```
    {
        "status": "error",
        "errors": [ ]
    }
  ```

* **Sample Call:**

  ```javascript
    $.ajax({
      url: "/v1/users/change-quiet-mode",
      dataType: "json",
      type : "PATH",
      success : function(r) {
        console.log(r);
      }
    });
  ```