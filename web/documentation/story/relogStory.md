**Relog story**
----
    Returns json data about status of story relog.

* **URL**

    /v1/stories/`story_id`/relog

* **Method:**

    `POST`
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

    **Required:**
       
    `story_id=[integer]`

* **Data Params**

    ```
    {
      "book_slug": "1-interests",
      "is_logged_story": true
    }
    ```

* **Success Response:**

* **Code:** 200 <br />
**Content:**
    ```
    {
        "status": "success",
        "data": {
            "name": "Work",
            "key": "2-work",
            "icon": "custom",
            "href": "http://validbook-api.local/v1/books?book_slug=2-work",
            "auto_export": 1,
            "auto_import": 1,
            "is_logged_story": true
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
        url: "/v1/stories/1/relog",
        dataType: "json",
        type : "POST",
        data: {
           "book_slug": "2-work",
           "is_logged_story": true
        },
    
    success : function(r) {
        console.log(r);
    }
    });
    ```