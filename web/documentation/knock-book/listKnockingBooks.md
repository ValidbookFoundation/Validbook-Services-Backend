**List of knocking requests by User**
----
  Returns json data about status of list knocking books.

* **URL**

  /v1/knock-books

* **Method:**

  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
      
*  **URL Params**


* **Data Params**

    
* **Success Response:**

  * **Code:** 200 OK <br />
    **Content:** 
  ```
  {
      "status": "success",
      "data": [
          {
              "id": 10,
              "book": {
                  "id": 147,
                  "name": "Closed Book",
                  "slug": "closed-book",
                  "icon": "custom"
              },
              "knocker": {
                  "id": 10,
                  "first_name": "Ruslan",
                  "last_name": "Nikiforov",
                  "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/10/45x45.jpg"
              }
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
      url: "/v1/knock-books",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```