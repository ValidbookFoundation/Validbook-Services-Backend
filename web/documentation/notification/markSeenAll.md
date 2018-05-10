**Mark All New Notifications As Seen **
----
    Returns json data about status of marking all notifications as seen. 

* **URL**

    v1/notifications/seen-all

* **Method:**

    `POST`
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

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
        url: "/v1/notifications/seen-all?access_token=98mYMl1q8URYYnFOpuWlLQztKnjrPoJA",
        dataType: "json",
        type : "POST",
    
        success : function(r) {
            console.log(r);
        }
    });
    ```