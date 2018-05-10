**Mark Notification As Read**
----
    Returns json data about status of marking notification as read. 

* **URL**

    v1/notifications/read/`notification_id`

* **Method:**

    `POST`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

    **Required:**
    `notification_id=[integer]`

* **Success Response:**

* **Code:** 201 <br />
**Content:**
    ```
    {
        "status": "success"
    }
    ```

* **Error Response:**

    * **Code:** 401 Unauthorized <br />
    * **Code:** 404 NOT FOUND<br />
    
      **Content:** 
    ```
      {
          "status": "error",
          "errors": [
              {
                  "code": 404,
                  "message": ""
              }
          ]
      }
    ```

* **Sample Call:**

    ```
    $.ajax({
        url: "/v1/notifications/read/2",
        dataType: "json",
        type : "POST",
    
        success : function(r) {
            console.log(r);
        }
    });
    ```