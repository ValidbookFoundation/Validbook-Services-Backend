**Type A Head Search**
----
    Returns json data about result of typing head tab search.
    Sorting
    1. users(friends, following, followers, other)
    2. followed user's books and oother

* **URL**

    v1/search:q

* **Method:**

    `GET`

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
               }
           ],
           "books": [
               {
                   "id": 246,
                   "name": "API",
                   "slug": "246-api",
                   "description": "",
                   "cover": "",
                   "created": "20 Jun 2017",
                   "owner": {
                       "id": 4,
                       "fullName": "Alex Tykhonchuk",
                       "slug": "alex.tykhonchuk",
                       "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/4/32x32.jpg"
                   },
                   "counters": {
                       "stories": "5",
                       "followers": "0"
                   }
               },
               {
                   "id": 267,
                   "name": "Loreal Facebook App",
                   "slug": "267-loreal-facebook-app",
                   "description": "",
                   "cover": "",
                   "created": "20 Jun 2017",
                   "owner": {
                       "id": 4,
                       "fullName": "Alex Tykhonchuk",
                       "slug": "alex.tykhonchuk",
                       "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/4/32x32.jpg"
                   },
                   "counters": {
                       "stories": "1",
                       "followers": "0"
                   }
               }
           ]
       }
   }
    ```

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
        url: "/v1/search?q=a",
        dataType: "json",
        type : "GET",
        data: {q: "a"},
        success : function(r) {
            console.log(r);
        }
    });
    ```