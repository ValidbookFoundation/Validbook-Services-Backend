**Add member to Conversation**
----
  Returns json data of added message to conversation user
* **URL**

  /v1/conversations/add-member/`id`
  
* **Method:**

  `PATCH`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    
   **Required:**
    
   `id =[integer]` - conversation id

* **Data Params**

  `user_id=[integer]`

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
        "status": "success",
        "data": {
            "id": 302,
            "text": "Jimbo Fry added Dima Platonov",
            "date": "01 Aug 2017 08:25",
            "is_tech": true,
            "user": {
                "id": 5,
                "first_name": "Dima",
                "last_name": "Platonov",
                "slug": "dima.platonov",
                "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/5/45x45.jpg"
            },
            "conversation_id": "12"
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
      url: "v1/conversations/add-member/12",
      dataType: "json",
       data: { user_id: [4,34]},
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```