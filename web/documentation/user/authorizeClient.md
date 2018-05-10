**Authorize Client**
----
  Returns json data about status of authorize client.

* **URL**

  /v1/users/authorize-client

* **Method:**

  `POST`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

* **Data Params**

  ```
    {
      "client_id": "wiki",
      "response_type": "code",
      "redirect_uri": "http://wiki.local/index.php?title=Special:OAuth2Client/callback",
      "scope": "user_info",
      "state" : "rtwtwrgfbsdfgerewrtew",
      "nonce" : "3454545324wegsdfgdsfgdsf"
    }
  ```
    
* **Success Response:**

  * **Code:** 201 Created <br />
    **Content:** 
  ```
    {
      "status": "success",
      "data": {
      "redirect" : "http://wiki.local/index.php?title=Special:OAuth2Client/callback&code=tqwvasfvas2152345234efvasdf"
      } 
    }
  ```
 
* **Error Response:**

   * **Code:** 404 <br />
    **Content:** 

* **Sample Call:**

  ```javascript
    $.ajax({
      url: "/v1/users/authorize-client",
      dataType: "json",
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```