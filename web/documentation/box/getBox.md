**Get Box**
----
  Returns json data about a user box.

* **URL**

  /v1/boxes/`box_slug`:user_slug
  
* **Method:**

  `GET`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
   
   `box_slug=[string]` <br/>
   (optional)`user_slug=[string]` <br/>

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
           "name": "Desk",
           "key": "desk",
           "description": "",
           "documents": [],
           "children": [
               {
                   "id": 3,
                   "name": "Backup of signed documents",
                   "key": "backup-of-signed-documents",
                   "icon": "backup",
                   "href": "http://validbook-api.local/v1/boxes/backup-of-signed-documents",
                   "no_drag": true
               }
           ],
           "settings": {
               "can_add_documents": 0,
               "can_delete_documents": 0,
               "can_see_content": 1,
               "can_see_exists": 1,
               "users_array": {
                   "users_can_see_exists": [],
                   "users_can_see_content": [],
                   "users_can_add_documents": [],
                   "users_can_delete_documents": []
               }
           },
           "bin": {
               "name": "Bin",
               "key": "bin",
               "icon": "bin",
               "no_drag": true,
               "children": []
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

  ```javascript
    $.ajax({
      url: "/v1/boxes/board?user_slug=jimbo.fry",
      dataType: "json",
      data: {},
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```