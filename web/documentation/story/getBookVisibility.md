**Get Books Visibility**
----
  Returns json data about books visibility.

* **URL**

  /v1/stories/books-visibility:book_ids

* **Method:**

  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
     
*  **URL Params**
    
   **Required:**
    
  `book_ids=[string]`

* **Data Params**

  None

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```
    {
        "status": "success",
        "data": {
            "value": 1,
            "users_custom_visibility": []
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

  ```javascript
    $.ajax({
      url: "/v1/stories/v1/stories/books-visibility?book_ids=1,2",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```