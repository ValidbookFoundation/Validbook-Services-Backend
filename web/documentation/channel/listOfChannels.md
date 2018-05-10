**Get list of user channels**
----
  Returns json data about list of user channels.

* **URL**

  /v1/channels

* **Method:**

  `GET`
 
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
     
*  **URL Params**

* **Data Params**

  ```
   
  ```
    
* **Success Response:**

  * **Code:** 200 OK <br />
    **Content:** 
  ```
   {
       "status": "success",
       "data": [
           {
               "id": 1,
               "name": "Mashup",
               "slug": "mashup",
               "description": ""
           },
           {
               "id": 44,
               "name": "123",
               "slug": "123",
               "description": ""
           },
           {
               "id": 48,
               "name": "456",
               "slug": "456",
               "description": ""
           },
           {
               "id": 49,
               "name": "789",
               "slug": "789",
               "description": ""
           }
       ]
   }
  ```
 
* **Error Response:**

  * **Code:** 404 <br />
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
      url: "/v1/channels",
      dataType: "json",
      type : "GET"
      success : function(r) {
        console.log(r);
      }
    });
  ```