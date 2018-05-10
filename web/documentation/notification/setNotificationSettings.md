**Set notification settings**
----
    Returns json data about status of updating notification settings.
* **URL**

    /v1/notifications/settings

* **Method:**

    `POST`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    
    
* **Data Params**

  ```
    {
        "notification_type": "settings",
        "settings": [
            {
                "label": "When someone followed me",
                "email": true,
                "web": true
            },
            {
                "label": "When someone commented on my story",
                "email": true,
                "web": true
            },
            {
                "label": "When someone liked my story",
                "email": false,
                "web": true
            },
            {
                "label": "When someone commented on story I commented",
                "email": true,
                "web": true
            },
            {
                "label": "When someone commented on story I liked",
                "email": false,
                "web": true
            },
            {
                "label": "When someone liked comment I wrote",
                "email": false,
                "web": true
            },
            {
                "label": "When someone sent me private message",
                "email": true,
                "web": true
            },
            {
                "label": "When someone sent me token",
                "email": true,
                "web": true
            }
        ]
    }
  ```

* **Success Response:**

* **Code:** 201 <br />
**Content:**
    ```
    {
        "status": "success",
    }
    ```

* **Error Response:**

* **Code:** 404 Not Found <br />
* **Code:** 422 <br />
**Content:**
    ```
    {
      "status": "error",
      "errors": [
        {
          "code": Code,
          "message": "Your request was made with invalid credentials."
        }
      ]
    }
    ```

* **Sample Call:**

    ```
    $.ajax({
        url: "/v1/notification/settings",
        dataType: "json",
        type : "POST",
        data: {settings: settings, notification_type: "settings"},
        success : function(r) {
            console.log(r);
        }
    });
    ```