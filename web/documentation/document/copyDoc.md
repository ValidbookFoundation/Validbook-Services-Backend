**Copy document**
----
  Returns json data about status of copy doc.

* **URL**

  /v1/documents/`doc_id`/copy

* **Method:**

  `PATCH`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
    
*  **URL Params**


* **Data Params**

    `box_slug=[string]` 
    
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
  ```
    {
        "status": "success",
        "data": {
            "id": 9,
            "title": "Test Document",
            "type": "custom",
            "box_id": 12,
            "user_id": 1,
            "url": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/documents/Test%20Document_jimbo.fry.md",
            "created": "27 Sep 2017",
            "signatures": [
                {
                    "id": 28,
                    "public_address": "0xe3954b59340b92a01a2258251c56098cc6c485cc",
                    "short_format_url": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/documents/Test%20Document_jimbo.fry/signatures/sf_signature_0xe3954b59340b92a01a2258251c56098cc6c485cc.md",
                    "long_format_url": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/documents/Test%20Document_jimbo.fry/signatures/lg_signature_0xe3954b59340b92a01a2258251c56098cc6c485cc.md",
                    "created": "25 Sep 2017",
                    "is_mutual": false,
                    "user": {
                        "id": 1,
                        "first_name": "Jimbo",
                        "last_name": "Fry",
                        "slug": "jimbo.fry",
                        "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/09/04/1/x0Mf1bNG7evc1XCmsoG7PZ92m6f1OPgl.jpg",
                        "avatar48": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/09/04/1/1WR4BwqelYmndw4fkB3wQnl-Y2YKWfM1.jpg",
                        "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/09/04/1/1hBJuXphgk3V9USaYcUr4AKNcd6Zu-8L.jpg"
                    }
                }
            ],
            "settings": {
                "can_see_content": 1,
                "can_sign": 0,
                "users_array": {
                    "users_can_see_content": [],
                    "users_can_sign": []
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
      url: "/v1/documents/12/copy",
      dataType: "json",
      type : "PATCH",
      success : function(r) {
        console.log(r);
      }
    });
  ```