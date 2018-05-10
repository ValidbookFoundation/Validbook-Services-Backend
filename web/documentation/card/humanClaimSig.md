**Human Claim Signature**
----
  Returns json data about a status of human claim signature of card.

* **URL**

  /v1/card/`address`/human-claim-sig
  
* **Method:**

  `PATCH`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    
   **Required:**
    
   `address=[string]` <br/>

* **Data Params**
    ```
      {
      	"signature": "0x8760811a8183768eb612b4965c83c820ab8cabc06649af265c9ce24bf694765a25a54e37f00d0011922092b9e938b4d4c4e024c3f0d71930f4fc0635893931371b"
      }
    ```

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
        "status": "success",
        "data": {
            "public_address": "0x7e2b048628ee1e00c8fc85e32caa323028b7c79f",
            "url": "https://s3.us-west-2.amazonaws.com/dev.validbook/102/card/card_0x7e2b048628ee1e00c8fc85e32caa323028b7c79f.md",
            "created": "20 Nov 2017",
            "full_card_url": "https://s3.us-west-2.amazonaws.com/dev.validbook/102/card/full_card_0x7e2b048628ee1e00c8fc85e32caa323028b7c79f.md",
            "claims": [
                {
                    "type": "I am human"
                }
            ]
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
      url: "/v1/human-card/0xe3954b59340b92a01a2258251c56098cc6c485cc/human-claim-sig",
      dataType: "json",
      type : "PATCH",
      success : function(r) {
        console.log(r);
      }
    });
  ```