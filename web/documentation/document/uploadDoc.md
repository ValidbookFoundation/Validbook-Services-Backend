**Upload Document**
----
    Returns status of upload document

* **URL**

    /v1/documents/upload

* **Method:**

    `POST`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

* **Body Content**

   `file`

* **Success Response:**

  * **Code:**  201 Created <br />
**Content:**
    ```
      {
         "status": "success",
         "data": {
             "id": 1,
             "title": "Test Document",
             "type": "custom",
             "box_id": 2,
             "user_id": 1,
             "icon": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/documents/aQfsfk/previews/doder.jpg",
             "url": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/documents/Test%20Document_jimbo.fry.md",
             "created": "14 Sep 2017",
             "signatures": [
                 {
                     "id": 28,
                     "public_address": "0xe3954b59340b92a01a2258251c56098cc6c485cc",
                     "short_format_url": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/documents/Test%20Document_jimbo.fry/signatures/sf_signature_0xe3954b59340b92a01a2258251c56098cc6c485cc.md",
                     "long_format_url": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/documents/Test%20Document_jimbo.fry/signatures/lg_signature_0xe3954b59340b92a01a2258251c56098cc6c485cc.md",
                     "created": "25 Sep 2017",
                     "user": {
                         "id": 1,
                         "first_name": "Jimbo",
                         "last_name": "Fry",
                         "slug": "jimbo.fry",
                         "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/09/04/1/x0Mf1bNG7evc1XCmsoG7PZ92m6f1OPgl.jpg",
                         "avatar48": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/09/04/1/1WR4BwqelYmndw4fkB3wQnl-Y2YKWfM1.jpg",
                         "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/09/04/1/1hBJuXphgk3V9USaYcUr4AKNcd6Zu-8L.jpg"
                     }
                 }
             ]
         }
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

