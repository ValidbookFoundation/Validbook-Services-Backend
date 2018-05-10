**Upload User Avatar**
----
    Returns urls for different sizes of user avatar

* **URL**

    /v1/upload/avatar

* **Method:**

    `POST`
    
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
   
 * **Data Params**
   
     ```
       {
          "image_size" : ["original": "2000x3500"]
       }
     ```
     
*  **URL Params**

* **Body Content**

   `file`

* **Success Response:**

* **Code:** 201 Created <br />
**Content:**
    ```
    {
        "status": "success",
        "data": {
            "avatar32": "https://s3-us-west-2.amazonaws.com/2.jpg",
            "avatar48": "https://s3-us-west-2.amazonaws.com/3.jpg",
            "avatar100": "https://s3-us-west-2.amazonaws.com/4.jpg",
            "avatar220": "https://s3-us-west-2.amazonaws.com/4.jpg",
            "avatar230": "https://s3-us-west-2.amazonaws.com/5.jpg"
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

