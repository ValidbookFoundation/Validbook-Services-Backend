**Show Following Books in Channel**
----
  Returns json data about a channel following books.

* **URL**

  /v1/channels/`channel_id`/following-books

* **Method:**

  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

   `page=[integer]` - page = 1 by default

   **Required:**
 
   `channel_id=[integer]`

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```
    {
       "status": "success",
       "data": [
           {
               "id": 222,
               "name": "Cases",
               "slug": "222-cases",
               "description": "",
               "counters": {
                   "stories": 0,
                   "sub_books": 0,
                   "follows": 6
               },
               "settings": {
                   "can_add_stories": 0,
                   "can_delete_stories": 0,
                   "can_manage_settings": 0,
                   "can_see_content": 1,
                   "can_see_exists": 1,
                   "users_array": {
                       "users_can_see_exists": [],
                       "users_can_see_content": [],
                       "users_can_add_stories": [],
                       "users_can_delete_stories": [],
                       "users_can_manage_settings": []
                   }
               }
           }
       ]
    }
    ```
 
* **Error Response:**

  * **Code:** 404 <br />
    **Content:** 
    ```
    {
      "status": "error",
      "errors": [
        {
          "message": "Channel doesn't exist",
          "code": 404
        }
      ]
    }
    ```

* **Sample Call:**

  ```javascript
    $.ajax({
      url: "/v1/channels/222/following-books",
      dataType: "json",
      type : "GET",
      data: {"page": 2},
      success : function(r) {
        console.log(r);
      }
    });
  ```