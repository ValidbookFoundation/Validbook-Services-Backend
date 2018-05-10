**Create new book**
----
  Returns json data about status of creating new book.

* **URL**

  /v1/books

* **Method:**

  `POST`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

* **Data Params**

  ```
    {
        "name": "Test book",
        "description": "Book description",
        "parent_slug": "398-parent-book",
        "cover": {
                   "picture_original": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/book-covers/810x281_YCZveGJwOnxS0kw_enIoeoWjy-__nD85_KykcO6.jpg",
                   "picture_small": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/book-covers/597x207_YCZveGJwOnxS0kw_enIoeoWjy-__nD85_KykcO6.jpg",
                   "color": null
        },
         "counts": {
            "stories": 9,
            "sub_books": 0,
            "followers": 0,
            "images": 0
         }
        "can_see_exists" : 1,
        "can_see_content" : 2,
        "can_add_stories" : 0,
        "can_delete_stories" : 0,
        "can_manage_settings" : 0,
        "auto_export" : 1,
        "auto_import" : 1,
        "users_can_see_exists" : [],
        "users_can_see_content" : [4,77,109],
        "users_can_add_stories" : [],
        "users_can_delete_stories" : [],
        "users_can_manage_settings" : []
    }
  ```
    
* **Success Response:**

  * **Code:** 201 Created <br />
    **Content:** 
  ```
    {
        "success": true,
        "data": {
            "id": 369,
             "name": "Test book",
             "slug": "369-test-book",
             "description": "Book description",
             "cover": {
                  "picture": "https://s3-us-west-2.amazonaws.com/dev.validbook/user-covers/2017/06/14/4/oZQSmiE9d6JaXpTEQ2V_m2yRIErJsQ59.jpg",
                   "color": null
                   },
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
      url: "/v1/books",
      dataType: "json",
      type : "POST",
      data: {
          name: "Test book", 
          parent_slug: "398-parent-book",
          "description": "Book description"
      },
      success : function(r) {
        console.log(r);
      }
    });
  ```