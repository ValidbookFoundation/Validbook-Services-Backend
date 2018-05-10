**Update comment**
----
  Returns json data about status of updating comment.

* **URL**

  /v1/comments/`id`

* **Method:**

  `PATCH`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
    
*  **URL Params**

   **Required:**
   
  `id=[integer]`

* **Data Params**

  ```
    {
        "content": "Updated content",
    }
  ```
    
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
  ```
   {
         "status": "success",
         "data": {
             "id": 21,
             "entity": "story",
             "entity_id": 1,
             "date": "04 Jul 2017",
             "content": "Updated content",
             "parent_id": 7,
             "parent" : [],
             "user": {
                 "id": 2,
                 "first_name": "Olga",
                 "last_name": "Sochneva",
                 "slug": "olga.sochneva",
                 "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/2/230x230.jpg",
                 "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/2/32x32.jpg",
                 "cover": {
                         "picture": "https://s3-us-west-2.amazonaws.com/dev.validbook/user-covers/2017/06/14/4/oZQSmiE9d6JaXpTEQ2V_m2yRIErJsQ59.jpg",
                          "color": null
                   },
                 "isFollowing": false
             }
         }
     }
  ```
 
* **Error Response:**

    * **Code:** 401 Unauthorized <br />
    * **Code:** 404 NOT FOUND<br />
    **Content:** 
  ```
    {
      "status": "error",
      "errors": [
        {
          "message": message,
          "code": Code
        }
      ]
    }
  ```

* **Sample Call:**

  ```
    $.ajax({
      url: "/v1/comments/1",
      dataType: "json",
      data: {
          content: "Updated content", 
      },
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```