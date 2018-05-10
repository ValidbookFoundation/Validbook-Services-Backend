**Get Book**
----
  Returns json data about a user book.

* **URL**

  /v1/books/`book_slug`
  
* **Method:**

  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
   
   `stories_page=[integer]`

* **Data Params**

  None

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
        "status": "success",
        "data": {
            "id": 2,
            "name": "Work",
            "slug": "2-work",
            "description": "update",
            "counts": {
                      "stories": 179,
                      "sub_books": 0,
                      "followers": 0,
                      "images": 12,
                      "knockers": 0
                  },
            "cover": {
                         "picture_original": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/book-covers/810x281_YCZveGJwOnxS0kw_enIoeoWjy-__nD85_KykcO6.jpg",
                         "picture_small": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/book-covers/597x207_YCZveGJwOnxS0kw_enIoeoWjy-__nD85_KykcO6.jpg",
                         "color": null
             },
            "stories": [],
            "settings": {
                "can_add_stories": 0,
                "can_delete_stories": 0,
                "can_manage_settings": 0,
                "can_see_content": 1,
                "can_see_exists": 2,
                "users_array": {
                    "users_can_see_exists": [
                        3,
                        5,
                        4
                    ],
                    "users_can_see_content": [],
                    "users_can_add_stories": [],
                    "users_can_delete_stories": [],
                    "users_can_manage_settings": []
                }
            }
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
      url: "/v1/books/2-work",
      dataType: "json",
      data: {stories_page: 2},
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```