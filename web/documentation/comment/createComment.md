**Create new comment**
----
  Returns json data of creating new comment.

* **URL**

  /v1/comments
  
*  **Request Headers**

   `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`

* **Method:**

  `POST`
  
*  **URL Params**


* **Data Params**

  ```
  {
          "entity" : "story",
          "entity_id" : 1,
          "content":"content",
          "parent_id" : 7,
          "created_by" : 1
   }
  ```
    
* **Success Response:**

  * **Code:** 201 Created <br />
    **Content:** 
  ```
  {
      "status": "success",
      "data": {
          "id": 36,
          "entity": "story",
          "entity_id": 1,
          "date": "18 Jul 2017",
          "content": "content",
          "parent_id": 34,
          "children": null,
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
              "isFollowing": false,
          }
      }
  }
  ```
 
* **Error Response:**

  * **Code:** 404 NOT FOUND <br />
  * **Code:** 401 Unauthorized <br />
  * **Code:** 422 Unprocessable Entity <br />
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

* **Sample Call:**

  ```
    $.ajax({
      url: "/v1/comments",
      dataType: "json",
      data: {
         "entity" : "story",
               "entity_id" : 1,
               "content":"content",
               "parent_id" : 7,
               "created_by" : 1
      },
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```