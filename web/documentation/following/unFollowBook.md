**UnFollow Book in channels**
----
    Returns json data about status of unfollowing book and her childs from all channels. 


* **URL**

   **URL**
  
      v1/follows/simple-book-unfollow:access_token
  
* **Method:**
  
      `POST`
  
*  **Request Headers**
  
      `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
      
*  **URL Params**
  
  * **Data Params**
  
      ```
      {
          book_id: 3
      }
      ```
  
  * **Success Response:**
  
  * **Code:** 201 Created <br />
  **Content:**
      ```
    {
        "status": "success",
        "data": {
            "is_follow": false
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
          url: "v1/follows/simple-book-unfollow",
          dataType: "json",
          type : "POST",
          data: {
              book_id: 3
          },
      
          success : function(r) {
              console.log(r);
          }
      });
      ```