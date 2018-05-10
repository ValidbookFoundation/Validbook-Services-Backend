**Get User's Profile**
----
    Returns json data about user's profile data.

* **URL**

    v1/users/`user_slug`/profile

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
           "first_name": "Jimbo",
           "last_name": "Fry",
           "bio": "123",
           "occupation": "",
           "company": "",
           "country_id": 231,
           "location": "",
           "birthDay": 0,
           "birthMonth": 0,
           "birthDateVisibility": 1,
           "birthYear": 0,
           "birthYearVisibility": 1,
           "twitter": "",
           "facebook": "",
           "linkedin": "",
           "website": "",
           "phone": "",
           "skype": "",
           "card": {
             "id": 20,
             "public_address": "0xe3954b59340b92a01a2258251c56098cc6c485cc"
             "account_name": "Jimbo Fry",
             "created": "18 Sep 2017"
            },
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
        url: "/v1/users/john.smith/profile",
        dataType: "json",
        type : "GET",
        success : function(r) {
            console.log(r);
        }
    });
    ```