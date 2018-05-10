**Books search**
----
    Returns json data about result of books search.
    Sorting
    1. friend's books
    2. followed user's books
    3. other books

* **URL**

    v1/search/books:q

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
                  },
                  {
                      "id": 273,
                      "name": "Content and blog sites",
                      "slug": "273-content-and-blog-sites",
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
                          "stories": "0",
                          "followers": "0"
                      }
                  },
                  {
                      "id": 145,
                      "name": "Karma",
                      "slug": "145-karma",
                      "description": "This is my Karma book. It is an experiment to see if such books can be useful. Let's see! If you worked or interacted with me somehow, feel free to leave in this book a feedback-story about this. I promise to not delete stories, unless they are spam.",
                      "cover": "",
                      "created": "20 Jun 2017",
                      "owner": {
                          "id": 1,
                          "fullName": "Jimbo Fry",
                          "slug": "jimbo.fry",
                          "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                      },
                      "counters": {
                          "stories": "2",
                          "followers": "0"
                      }
                  },
                  {
                      "id": 146,
                      "name": "Ask me anything",
                      "slug": "146-ask-me-anything",
                      "description": "Feel free to log any questions to me in this book.",
                      "cover": "",
                      "created": "20 Jun 2017",
                      "owner": {
                          "id": 1,
                          "fullName": "Jimbo Fry",
                          "slug": "jimbo.fry",
                          "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                      },
                      "counters": {
                          "stories": "10",
                          "followers": "0"
                      }
                  },
                  {
                      "id": 71,
                      "name": "Open badges and credentials",
                      "slug": "71-open-badges-and-credentials",
                      "description": "",
                      "cover": "",
                      "created": "20 Jun 2017",
                      "owner": {
                          "id": 1,
                          "fullName": "Jimbo Fry",
                          "slug": "jimbo.fry",
                          "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                      },
                      "counters": {
                          "stories": "1",
                          "followers": "0"
                      }
                  },
                  {
                      "id": 85,
                      "name": "All things startup",
                      "slug": "85-all-things-startup",
                      "description": "",
                      "cover": "",
                      "created": "20 Jun 2017",
                      "owner": {
                          "id": 1,
                          "fullName": "Jimbo Fry",
                          "slug": "jimbo.fry",
                          "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                      },
                      "counters": {
                          "stories": "37",
                          "followers": "0"
                      }
                  },
                  {
                      "id": 86,
                      "name": "Quotes and credos",
                      "slug": "86-quotes-and-credos",
                      "description": "",
                      "cover": "",
                      "created": "20 Jun 2017",
                      "owner": {
                          "id": 1,
                          "fullName": "Jimbo Fry",
                          "slug": "jimbo.fry",
                          "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                      },
                      "counters": {
                          "stories": "46",
                          "followers": "0"
                      }
                  },
                  {
                      "id": 90,
                      "name": "Lifestyle hacks",
                      "slug": "90-lifestyle-hacks",
                      "description": "All about lifestyle hacks, exercise, diet etc ",
                      "cover": "",
                      "created": "20 Jun 2017",
                      "owner": {
                          "id": 1,
                          "fullName": "Jimbo Fry",
                          "slug": "jimbo.fry",
                          "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                      },
                      "counters": {
                          "stories": "6",
                          "followers": "0"
                      }
                  },
                  {
                      "id": 93,
                      "name": "Economics and Psychology",
                      "slug": "93-economics-and-psychology",
                      "description": "",
                      "cover": "",
                      "created": "20 Jun 2017",
                      "owner": {
                          "id": 1,
                          "fullName": "Jimbo Fry",
                          "slug": "jimbo.fry",
                          "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/25/1/G7gUU-j2cA6YQ8XHJzRFMBedd0SnLWzn.jpg"
                      },
                      "counters": {
                          "stories": "9",
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
        url: "/v1/search/books?q=a",
        dataType: "json",
        type : "GET",
        data: {q: "a"},
        success : function(r) {
            console.log(r);
        }
    });
    ```