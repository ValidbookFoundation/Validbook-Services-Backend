**User photo covers**
----
  Returns json data about status of cover photos user.

* **URL**

  /v1/photos/cover:user_id

* **Method:**
  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
     
*  **URL Params**
    `page=[integer]` </br>
    
   **Required**
   
    `user_id=[integer]` </br>
    

* **Data Params**
    
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
  ```
   {
       "status": "success",
       "data": [
           {
                "id": 1,
                "picture_original": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/08/01/1/gihnPU8TPvuShU-brwhRyHIYUCNV9bD1.jpg",
                "picture_small": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/08/01/1/gihnPU8TPvuShU-brwhRyHIYUCNV9bD1.jpg",
                "created": "11 Sep 2017"
           }
           {
                "id": 2,
                "picture_original": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/08/01/1/gihnPU8TPvuShU-brwhRyHIYUCNV9bD1.jpg",
                "picture_small": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/08/01/1/gihnPU8TPvuShU-brwhRyHIYUCNV9bD1.jpg",
                "created": "11 Sep 2017"
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

  ```
    $.ajax({
      url: "/v1/photos/cover?user_id=1",
      dataType: "json",
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```