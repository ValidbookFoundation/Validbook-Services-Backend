**Pin story in book**
----
    Returns json data about status of pinning story.

* **URL**

    /v1/stories/`story_id`/pin

* **Method:**

    `POST`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

    **Required**
    
    `story_id=[integer]`

* **Data Params**

    ```
    {
        "pins": [
            {
                "book_id": 782,
                "order": 1
            },
            {
                "book_id": 783,
                "order": 8
            }
        ]
    }
    ```

* **Success Response:**

* **Code:** 201 Created <br />
**Content:**
    ```
    {
      "status": "success"
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
                  "code": 404,
                  "message": "Story does not belong this book"
              }
          ]
      }
    ```

* **Sample Call:**

    ```
    $.ajax({
        url: "/v1/stories/353/pin",
        dataType: "json",
        type : "POST",
        data: {
            pins[0][book_id] : 782,
            pins[0][order]: 1,
            pins[1][book_id] : 783,
            pins[1][order]: 8
        },
    
    success : function(r) {
        console.log(r);
    }
    });
    ```