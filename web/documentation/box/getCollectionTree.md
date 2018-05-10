**Get Box Tree**
----
  Returns json data about a user boxes collection tree. Types of box icon: public, private, custom, bin

* **URL**

  /v1/boxes:user_slug
  
* **Method:**

  `GET`
  
*  **Request Headers**

   `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
  
*  **URL Params**
       
   `user_slug=[string]` <br/>

* **Data Params**

  None

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
     {
         "status": "success",
         "data": [
                 "name": "root",
                 "key": "root",
                 "show": true,
                 "desk": {
                     "id": 2,
                     "name": "Desk",
                     "key": "desk",
                     "icon": "desk",
                     "href": "http://validbook-api.local/v1/boxes/desk",
                     "children": [
                         {
                             "id": 3,
                             "name": "Backup box for signed documents",
                             "key": "backup-box-for-signed-documents",
                             "icon": "backup",
                             "href": "http://validbook-api.local/v1/boxes/backup-box-for-signed-documents",
                             "no_drag": true
                         }
                     ]
                 },
                 "bin": {
                     "name": "Bin",
                     "key": "bin",
                     "icon": "bin",
                     "no_drag": true,
                     "children": []
                 }
         ]
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
      url: "/v1/boxes?user_slug=jimbo.fry",
      dataType: "json",
      data: {user_slug: "john-smith"},
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```