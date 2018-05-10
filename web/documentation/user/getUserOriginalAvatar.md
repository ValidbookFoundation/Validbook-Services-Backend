**Get User's Original Avatar**
----
    Returns json data about user's origin avatar.

* **URL**

    v1/users/`user_slug`/original-avatar

* **Method:**

    `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

    `user_slug=[string]`<br/>

* **Success Response:**

* **Code:** 200 <br />
**Content:**
    ```
   {
       "status": "success",
       "data": {
           "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/human_card/2017/09/18/1/hc_Jimbo_Fry_0xe3954b59340b92a01a2258251c56098cc6c485cc"
       }
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
          "code": 404,
          "message": "Your request was made with invalid credentials."
        }
      ]
    }
    ```

* **Sample Call:**

    ```
    $.ajax({
        url: "/v1/users/john.smith/original-avatar",
        dataType: "json",
        type : "GET",
        success : function(r) {
            console.log(r);
        }
    });
    ```