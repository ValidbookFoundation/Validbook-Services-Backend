**Follow edit popup**
----
    Returns json data for edit data in follow book popup.

* **URL**

    v1/follows/edit-follow-book

* **Method:**

    `POST`

*  **URL Params**

   
    
 * **Data Params**
   ```
       {
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
        url: "v1/follows/edit-follow-book",
        dataType: "json",
        type : "POST",
        success : function(r) {
            console.log(r);
        }
    });
    ```