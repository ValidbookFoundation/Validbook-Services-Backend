**Get One Conversation For User**
----
  Returns json data one conversation by authorized user

* **URL**

  /v1/conversations/`id`
  
* **Method:**

  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
   `page=[integer]` </br>
    
    **Required:**
    
   `id =[integer]` - conversation id

* **Data Params**

  None

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
     {
         "status": "success",
         "data": {
             "conversation_id": 16,
             "messages": [
                 {
                     "id": 17,
                     "text": "hello_all",
                     "date": "17 Jul 2017",
                     "is_new": 1,
                     "user": {
                         "id": 2,
                         "first_name": "Olga",
                         "last_name": "Sochneva",
                         "slug": "olga.sochneva",
                         "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/2/32x32.jpg"
                     },
                     "conversation_id": 16
                 }
             ]
             "receivers": [
                 {
                     "id": 24,
                     "first_name": "Vitalii",
                     "last_name": "Boiko",
                     "slug": "vitalii.boiko",
                     "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/24/32x32.jpg"
                 }
             ]
         }
     }
    ```
 
* **Error Response:**

    * **Code:** 401 Unauthorized <br />
    * **Code:** 404 NOT FOUND<br />
    * **Code:** 422 Unprocessable Entity <br />
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
      url: "v1/conversations/12",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```