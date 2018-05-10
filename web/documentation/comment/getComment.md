**Show Comment**
----
  Returns json data about a single comment.

* **URL**

  /v1/comments/`id`

* **Method:**

  `GET`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

   **Required:**
   
      `id=[integer]`
  

* **Success Response:**

  * **Code:** 200 <br />
  
    **Content:** 
    ```
    {
        "status": "success",
        "data": {
            "id": 3,
            "entity": "story",
            "entity_id": 1,
            "date": "03 Jul 2017",
            "content": "content",
            "parent_id": 0,
            "parent": [],
            "user": {
                "id": 1,
                "first_name": "Bohdan",
                "last_name": "Andriyiv",
                "slug": "bohdan.andriyiv",
                "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/26/1/d_s7ZynB0oyNM4O_Spypwcw2Hl-Le4k1.jpg",
                "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/26/1/skbKx2Jx-2678QKSvokcPa_7AFxRxaDq.jpg",
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

   * **Code:** 404 NOT FOUND<br />
    **Content:** 
    ```
    {
      "status": "error",
      "errors": [
        {
          "message": "Comment doesn't exist",
          "code": 404
        }
      ]
    }
    ```

* **Sample Call:**

  ```javascript
    $.ajax({
      url: "/v1/comments/3",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```