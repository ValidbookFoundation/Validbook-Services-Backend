**Upload Story File**
----
    Returns url of uploaded file

* **URL**

    /v1/upload/story

* **Method:**

    `POST`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**


* **Body Content**

   `story_id=[integer]`,
   `file[]`

* **Success Response:**

* **Code:** 201 Created <br />
**Content:**
    ```
    {
        "status": "success",
        "data": [
         {
            "id": 1,
            "story_id" : 334,
            "type" : "image/jpeg",
            "picture_small": "https://s3-us-west-2.amazonaws.com/dev.validbook/book-covers/2017/06/14/4/oZQSmiE9d6JaXpTEQ2V_m2yRIErJsQ59.jpg",
            "created": "18 Aug, 2017"
         }
        ]
    }
    ```

* **Error Response:**

* **Code:** 401 <br />
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

