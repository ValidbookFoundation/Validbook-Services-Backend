**Create new message**
----
  Returns json data of creating new message.

* **URL**

  /v1/messages


*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
* **Method:**

  `POST`
  
*  **URL Params**

* **Data Params**

  ```
  
      	{
          "text" : "hi", 
          "conversation_id": id or `null`,
          "receivers" : [1]
        }
      
   
  ```
    
* **Success Response:**

  * **Code:** 201 Created <br />
    **Content:** 
  ```
   {
       "status": "success",
       "data": {
           "id": 22,
           "text": "hi",
           "date": "17 Jul 2017",
           "is_new": 1,
           "user": {
               "id": 2,
               "first_name": "Olga",
               "last_name": "Sochneva",
               "slug": "olga.sochneva",
               "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/2/32x32.jpg"
           },
           "conversation_id": 1
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
             "code": Code,
             "message": {message}
           }
      ]
    }
  ```

* **Sample Call:**

  ```
    $.ajax({
      url: "/v1/messages",
      dataType: "json",
      data: {
                  "text" : "hi", 
                  "conversation_id": id or `null`,
                  "receivers" : [1]
              
               },
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```