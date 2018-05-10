**Get Access Token**
----
  Returns json data about user authorization token.

* **URL**

  /v1/auth/login

* **Method:**

  `POST`
  
*  **URL Params**

  None  

* **Data Params**

  ```
   {
     "address": "0x7e2b048628ee1e00c8fc85e32caa323028b7c79f",
     "signature": "0xe1200267dc5ef98ca13f710338688c5553cffe19ce4623ebbbea682ddd3c15d55a1d2995a2bcc2e0a3fe54139c8408a956efbac3d811aa4d6df665e769017eef1c"
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
           "first_name": "Jimbo",
           "last_name": "Fry",
           "slug": "jimbo.fry",
           "public_address": "0x7e2b048628ee1e00c8fc85e32caa323028b7c79f",
           "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/avatars/230x230_126-156_2.jpg",
           "avatar48": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/avatars/48x48_126-156_2.jpg",
           "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/avatars/32x32_126-156_2.jpg",
           "is_follow": false,
           "cover": {
               "picture_original": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/user-covers/1900x235_aOg_BDbQmApCv-iXVd4HKvnJmbXDSG0B.jpg",
               "picture_small": null,
               "color": null
           },
           "generate_keys": false,
           "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC92YWxpZGJvb2stYXBpLmxvY2FsIiwiYXVkIjoiaHR0cDpcL1wvdmFsaWRib29rLWFwaS5sb2NhbCIsImlhdCI6MTUwOTAwMzE4NCwibmJmIjoxNTA5MDAzMTg0LCJ1aWQiOjEsImV4cCI6MTYwMzYxMTE4NH0.gjh1DMU_sSlX2knAGX--t3xzlc5fCEus5U-K3TGySQw"
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
      url: "/v1/auth/login",
      dataType: "json",
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```