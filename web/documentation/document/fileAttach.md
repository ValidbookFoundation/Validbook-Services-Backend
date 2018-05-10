**Attach File to Document**
----
    Returns url of attached file

* **URL**

    /v1/documents/`doc_id`/file-attach

* **Method:**

    `PATCH`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

    **Required**
      
    `story_id=[integer]`

* **Body Content**

   `file`

* **Success Response:**

  * **Code:**  201 Created <br />
**Content:**
    ```
    {
        "status": "success",
        "data": [
        "file_url": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/documents/6/files/e5c944f5c2c1250eac3d5116bc4e9abbf47a5951f1ed6f19b67365b0dedeb807.zip"
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

