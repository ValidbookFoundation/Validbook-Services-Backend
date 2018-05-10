**Upload Book Cover**
----
    Returns url of uploaded image

* **URL**

    /v1/upload/book-cover

* **Method:**

    `POST`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

 * **Data Params**
   
     ```
       {
          "color": string(hex color - `FFFFFF`) or `null`
          "book_id" : 10,
          "image_size" : ["original": "2000x3500"]
       }
     ```

* **Body Content**

   `file`

* **Success Response:**

* **Code:** 201 Created <br />
**Content:**
    ```
  
      {
          "status": "success",
           "data": {
                    "picture_small": null,
                    "color": null
            },
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

