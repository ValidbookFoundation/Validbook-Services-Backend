 **Users search**
----
    Returns json data about result of users search.
    Sorting
    1. show friends 
    2. show following users
    3. show users who follow you
    4. show friends of my friends
    5. other users
* **URL**

    v1/search/users:q

* **Method:**

    `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

    **Required:**
    
    `q=[string]`

* **Success Response:**

* **Code:** 200 <br />
**Content:**
    ```
  {
     "status": "success",
     "data": {
         "users": [
             {
                 "id": 41,
                 "first_name": "Alex",
                 "last_name": "Alex",
                 "slug": "alex.alex",
                 "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/200x200.png",
                 "relation": "following"
             },
             {
                 "id": 20,
                 "first_name": "Andreia",
                 "last_name": "Lage",
                 "slug": "andreia.lage",
                 "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/32x32.jpg",
                 "relation": "following"
             },
             {
                 "id": 4,
                 "first_name": "Alex",
                 "last_name": "Tykhonchuk",
                 "slug": "alex.tykhonchuk",
                 "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/4/32x32.jpg",
                 "relation": "following"
             },
             {
                 "id": 6,
                 "first_name": "Andriy",
                 "last_name": "Borshchuk",
                 "slug": "andriy.borshchuk",
                 "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/6/32x32.jpg",
                 "relation": "following"
             },
             {
                 "id": 13,
                 "first_name": "Andrei",
                 "last_name": "Podik",
                 "slug": "andrei.podik",
                 "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/13/32x32.jpg",
                 "relation": "following"
             },
             {
                 "id": 7,
                 "first_name": "Galya",
                 "last_name": "An",
                 "slug": "galya.an",
                 "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/7/32x32.jpg",
                 "relation": "following"
             },
             {
                 "id": 14,
                 "first_name": "Vasily",
                 "last_name": "Amelyanenka",
                 "slug": "vasily.amelyanenka",
                 "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/14/32x32.jpg",
                 "relation": "following"
             },
             {
                 "id": 33,
                 "first_name": "Andrew",
                 "last_name": "Zinchenko",
                 "slug": "andrew.zinchenko",
                 "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/33/32x32.jpg",
                 "relation": "following"
             },
             {
                 "id": 23,
                 "first_name": "Alex",
                 "last_name": "Movchan",
                 "slug": "alex.movchan",
                 "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/23/32x32.jpg",
                 "relation": "following"
             },
             {
                 "id": 9,
                 "first_name": "Oleh",
                 "last_name": "Aloshkin",
                 "slug": "oleh.aloshkin",
                 "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/9/32x32.jpg",
                 "relation": "following"
             }
         ]
     }
 }
    ```

* **Error Response:**

* **Error Response:**

* **Error Response:**

 * **Code:** 400 Bad Request <br />
 * **Code:** 404 NOT FOUND <br />
 * **Code:** 401 Unauthorized <br />
    **Content:** 
  ```
    {
      "status": "error",
      "errors": [
           {
             "code": Code,
             "message": {message}
           }
      ]
    }
  ```


* **Sample Call:**

    ```
    $.ajax({
        url: "/v1/search/users?q=a",
        dataType: "json",
        type : "GET",
        data: {q: "a"},
        success : function(r) {
            console.log(r);
        }
    });
    ```