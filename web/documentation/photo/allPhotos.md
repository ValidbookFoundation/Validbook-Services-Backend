**Get All Photos of User**
----
  Returns json data about photos.

* **URL**

  /v1/photos:user_id
  
* **Method:**

  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
      
*  **URL Params**
    
   `user_id=[integer]` <br/>
   `page=[integer]` <br/>

* **Data Params**

  None

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
           },
           {
               "id": 6,
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

  ```javascript
    $.ajax({
      url: "/v1/photos?user_id=1",
      dataType: "json",
      data: {},
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```