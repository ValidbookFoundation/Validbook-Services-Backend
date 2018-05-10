**Get notification settings**
----
    Returns json data of settings options for Notification Settings Page.
* **URL**

    /v1/notifications/settings

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
        "status": "success",
        "data": {
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
            ],
            "updates": [
                {
                    "label": "News about Validbook and feature updates",
                    "value": true
                },
                {
                    "label": "Tips on getting more from Validbook",
                    "value": true
                },
                {
                    "label": "Things happened on Validbook last week",
                    "email": true
                }
            ]
        }
    }
    ```

* **Error Response:**

* **Code:** 200 <br />
**Content:**
    ```
    {
      "status": "error",
      "errors": [
        {
          "code": 401,
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
        type : "GET",
        success : function(r) {
            console.log(r);
        }
    });
    ```