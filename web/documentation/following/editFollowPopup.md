**Follow edit popup**
----
    Returns json data for edit data in follow user popup.

* **URL**

    v1/follows/edit-follow

* **Method:**

    `POST`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
            

*  **URL Params**

* **Data Params**
   
   ```
        {
        "user_channels": [
             		  {
                        "channel_id": 43,
                        "is_block": 0,
                        "is_follow": 1
                    }, 
                      {
                        "channel_id": 1,
                        "is_block": 1,
                        "is_follow": 0
                    } 
             		],
           "books_channels": [
                 {
                     "book_id": 195,
                     "channels": [
                         {
                             "channel_id": 44,
                             "is_block": 0,
                             "is_follow": 1
                         }
                     ]
                 },
                 {
                     "book_id": 200,
                     "channels": [
                         {
                             "channel_id": 44,
                             "is_block": 1,
                             "is_follow": 0
                         },
                         {
                             "channel_id": 43,
                             "is_block": 0,
                             "is_follow": 1
                         }
                     ]
                 },
                 {
                     "book_id": 198,
                     "channels": [
                         {
                             "channel_id": 44,
                             "is_block": 0,
                             "is_follow": 1
                         },
                         {
                             "channel_id": 43,
                             "is_block": 0,
                             "is_follow": 1
                         }
                     ]
                 },
                 {
                     "book_id": 197,
                     "channels": [
                         {
                             "channel_id": 44,
                             "is_block": 0,
                             "is_follow": 1
                         },
                         {
                             "channel_id": 43,
                             "is_block": 0,
                             "is_follow": 1
                         }
                     ]
                 },
                 {
                     "book_id": 196,
                     "channels": [
                         {
                             "channel_id": 44,
                             "is_block": 0,
                             "is_follow": 1
                         },
                         {
                             "channel_id": 43,
                             "is_block": 0,
                             "is_follow": 1
                         }
                     ]
                 }
             ],
          	"user_id" : 3
        }
        ```

* **Success Response:**

* **Code:** 201 Created <br />
**Content:**
    ```
  {
      "status": "success",
      "data": {
          "channels": {
              "1": "Mashup",
              "44": "123",
              "48": "456",
              "49": "789"
          },
          "books_channels": [
              {
                  "book_id": 195,
                  "channels": [
                      {
                          "channel_id": 44,
                          "is_block": 1,
                          "is_follow": 0
                      }
                  ]
              }
          ]
      }
  }
    ```

* **Error Response:**

    * **Code:** 400 Bad Request <br />
    * **Code:** 401 Unauthorized <br />
    * **Code:** 404 NOT FOUND<br />
    * **Code:** 422 Unprocessable Entity <br />
    * **Code:** 500 Internal Server Error<br />
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

    ```
    $.ajax({
        url: "v1/follows/edit-follow",
        dataType: "json",
        type : "POST",
        success : function(r) {
            console.log(r);
        }
    });
    ```