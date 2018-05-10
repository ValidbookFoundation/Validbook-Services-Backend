**Update Book**
----
  Returns json data about a status of updating book.

* **URL**

  /v1/books/`book_slug`
  
* **Method:**

  `PATCH`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

* **Data Params**
    ```
      {
          "name": "Edited Interests",
          "description": "New description"  
      }
    ```

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
      "status": "success",
      "data": {
        "id": 419,
        "name": "Edited Interests",
        "slug": "419-edited-interests",
        "description": "New description",
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
      url: "/v1/books/419-interests",
      dataType: "json",
      data: {name: "Edited Interests", description: "Lorem Ipsum"},
      type : "PATCH",
      success : function(r) {
        console.log(r);
      }
    });
  ```