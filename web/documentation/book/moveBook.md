**Move book**
----
  Returns json data about status of moving book node.

* **URL**

  /v1/books/`book_slug`

* **Method:**
  `POST`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    

* **Data Params**

   **Required**
    
    `book_before_slug=[string]`<br/>
    OR <br/>
    `book_parent_slug=[string]`
    
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
  ```
    {
      "status": "success",
      "data": []
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
      url: "/v1/books/1-test_book",
      dataType: "json",
      type : "POST",
      data: {
          book_parent_slug: "398-wallbook"
      },
      success : function(r) {
        console.log(r);
      }
    });
  ```