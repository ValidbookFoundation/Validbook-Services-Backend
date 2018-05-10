**Change delete time messages to Conversation**
----
  Returns json data of about message for change delete time to conversation user
* **URL**

  /v1/conversations/change-delete-time/`id`
  
* **Method:**

  `PATCH`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    
   **Required:**
    
   `id =[integer]` - conversation id

* **Data Params**

  `user_id=[integer]`,
  `delete_hours=[integer]` - time in hours integer (24, 48, 72, 512)

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
        "status": "success",
        "data": {
            "id": 1543,
            "text": "Jimbo Fry changed delete time messages to 48 hours",
            "date": "21 Dec 2017 10:05:06",
            "is_tech": 0,
            "user": {
                "id": 1,
                "first_name": "Jimbo",
                "last_name": "Fry",
                "slug": "jimbo.fry",
                "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/avatars/32x32_Timon_1510242630.jpg"
            },
            "conversation_id": 114
        }
    }
    ```
 
* **Error Response:**

    * **Code:** 401 Unauthorized <br />
    * **Code:** 404 NOT FOUND<br />
    * **Code:** 422 Unprocessable Entity <br />
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
      url: "v1/conversations/change-delete-time/12",
      dataType: "json",
       data: { user_id: [4,34], delete_hours: 72},
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```