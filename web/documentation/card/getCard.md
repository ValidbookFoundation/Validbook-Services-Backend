**Get Card**
----
  Returns json data about account card.

* **URL**

  /v1/card/`public_address`
  
* **Method:**

  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    
   `public_address=[string]` <br/>

* **Data Params**

  None

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
     {
       "status": "success",
       "data": {
           "public_address": "0x7e2b048628ee1e00c8fc85e32caa323028b7c79f",
           "account_name": "Test Testovich",
           "created": "20 Nov 2017",
           "full_card_url": "https://s3.us-west-2.amazonaws.com/dev.validbook/102/card/full_card_0x7e2b048628ee1e00c8fc85e32caa323028b7c79f.md",
           "claims": [
               {
                   "type": "I am human",
                   "supports": [
                       {
                           "id": 1,
                           "support_address": "0x7a182db6b0205e144c84dc409463eddcaa57c565",
                           "created": "21 Nov 2017",
                           "user": {
                               "id": 104,
                               "first_name": "Test",
                               "last_name": "Testovich",
                               "slug": "test.testovich.4",
                               "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/default-avatars/user-230.png",
                               "avatar48": "https://s3-us-west-2.amazonaws.com/dev.validbook/default-avatars/user-48.png",
                               "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/default-avatars/user-32.png"
                           }
                       }
                   ]
               }
           ],
           "linked_digital_properties": null
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
      url: "/v1/card/0xe3954b59340b92a01a2258251c56098cc6c485cc",
      dataType: "json",
      data: {},
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```