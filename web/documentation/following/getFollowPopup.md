**Follow popup**
----
    Returns json data for follow popup.

* **URL**

    v1/follows/`user_id`/popup

* **Method:**

    `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

    **Required:**
    `user_id=[integer]`

* **Success Response:**

* **Code:** 200 <br />
**Content:**
    ```
  {
    "status": "success",
    "data": {
        "all_channels": {
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
        "user_channels": []
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
        url: "/v1/follows/3/popup?access_token=Fkc5AVudvdGj1dHUEy6w3tTwVqYjkues",
        dataType: "json",
        type : "GET",
        success : function(r) {
            console.log(r);
        }
    });
    ```