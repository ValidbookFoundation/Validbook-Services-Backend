**Save signature document**
----
  Returns json data about a status of save sig of doc.

* **URL**

  /v1/documents/`doc_id`/save-sig
  
* **Method:**

  `PATCH`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    

* **Data Params**
    ```
    {
   	"public_address": "0xe3954b59340b92a01a2258251c56098cc6c485cc",
   	"signature": "0xcbcc2384ea3dc35b5c086d99b0dbe7489ae1d99f65d4d1d96f340c2e045ea26f33fed1d0e20efda133bee4ae877c20b0e888080404c2bfe2648d39faecadb6181b"
    }
    ```

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
     {
       "status": "success",
       "data": {
           "id": 1,
           "title": "Test Document",
           "type": "custom",
           "box_id": 2,
           "user_id": 1,
           "url": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/documents/Test%20Document_jimbo.fry.md",
           "created": "14 Sep 2017",
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
           ]
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

  ```javascript
    $.ajax({
      url: "/v1/documents/12/save-sig",
      dataType: "json",
      type : "PATCH",
      success : function(r) {
        console.log(r);
      }
    });
  ```