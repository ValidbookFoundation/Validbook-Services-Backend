**Get Document**
----
  Returns json data about a user document.

* **URL**

  /v1/documents/`doc_id`
  
* **Method:**

  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    
   `doc_id=[integer]`<br/>

* **Data Params**

  None

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
    "status": "success",
    "data": {
        "id": 116,
        "title": "Test Document4",
        "type": "custom",
        "box_id": 286,
        "user_id": 106,
        "icon": "https://s3.us-west-2.amazonaws.com/dev.validbook/106/documents/previews/vaXTbh.jpg",
        "url": "https://s3.us-west-2.amazonaws.com/dev.validbook/106/documents/116/Test%20Document4.md",
        "created": "24 Nov 2017",
        "signatures": [
            {
                "id": 1,
                "public_address": "0x25962b72fbf29f586dbaebcc3c9fb5e6bdb2380a",
                "short_format_url": "https://s3.us-west-2.amazonaws.com/dev.validbook/106/documents/116/signatures/0x66364d85b089aeb4f8253c26545a155d040ee3e6006f128dd4b007e7e97e1ec0_0x25962b72fbf29f586dbaebcc3c9fb5e6bdb2380asf_signature_0x25962b72fbf29f586dbaebcc3c9fb5e6bdb2380a.md",
                "long_format_url": "https://s3.us-west-2.amazonaws.com/dev.validbook/106/documents/116/signatures/0x66364d85b089aeb4f8253c26545a155d040ee3e6006f128dd4b007e7e97e1ec0_0x25962b72fbf29f586dbaebcc3c9fb5e6bdb2380alg_signature_0x25962b72fbf29f586dbaebcc3c9fb5e6bdb2380a.md",
                "created": "24 Nov 2017",
                "user": {
                    "id": 106,
                    "first_name": "Sign",
                    "last_name": "Signovich",
                    "slug": "sign.signovich",
                    "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/default-avatars/user-230.png",
                    "avatar48": "https://s3-us-west-2.amazonaws.com/dev.validbook/default-avatars/user-48.png",
                    "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/default-avatars/user-32.png"
                }
            }
        ],
        "hash": "0x66364d85b089aeb4f8253c26545a155d040ee3e6006f128dd4b007e7e97e1ec0",
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

* **Sample Call 1:**

  ```javascript
    $.ajax({
      url: "/v1/documents/116",
      dataType: "json",
      data: {},
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```