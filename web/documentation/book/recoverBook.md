**Recover book from bin**
----
  Returns json data about status of recover book.

* **URL**

  /v1/books/`book_slug`/recover

* **Method:**

  `PATCH`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

* **Data Params**

    None
    
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
  ```
    {
      "status": "success",
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
      url: "/v1/books/1-sport/recover",
      dataType: "json",
      type : "DELETE",
      success : function(r) {
        console.log(r);
      }
    });
  ```