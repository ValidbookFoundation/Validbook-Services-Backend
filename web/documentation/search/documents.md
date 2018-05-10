**Documents search**
----
    Returns json data about result of documents search.

* **URL**

    v1/search/documents:q

* **Method:**

    `GET`

*  **URL Params**

    **Required:**
    
    `q=[string]`

* **Success Response:**

* **Code:** 200 <br />
**Content:**
    ```
   {
       "status": "success",
       "data": {
           "documents": [
                     {
                         "id": 115,
                         "title": "Test Document",
                         "type": "custom",
                         "box_id": 2,
                         "user_id": 1,
                         "url": null,
                          "icon": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/documents/aQfsfk/previews/doder.jpg",
                         "created": "<span class=\"not-set\">(not set)</span>",
                         "signatures": [],
                         "hash": null,
                         "is_open_for_sign": 1,
                         "is_encrypted": 0,
                         "settings": {
                             "can_see_content": 0,
                             "can_sign": 0,
                             "users_array": {
                                 "users_can_see_content": [],
                                 "users_can_sign": []
                             }
                         }
                     }
                 ]
       }
   }
    ```

* **Error Response:**

 * **Code:** 400 Bad Request <br />
 * **Code:** 404 NOT FOUND <br />
 * **Code:** 401 Unauthorized <br />
    **Content:** 
  ```
    {
      "status": "error",
      "errors": [
           {
             "code": Code,
             "message": {message}
           }
      ]
    }
  ```

* **Sample Call:**

    ```
    $.ajax({
        url: "/v1/search/documents?q=t",
        dataType: "json",
        type : "GET",
        data: {q: "work"},
        success : function(r) {
            console.log(r);
        }
    });
    ```