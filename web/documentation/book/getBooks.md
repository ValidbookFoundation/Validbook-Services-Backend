**Get Books**
----
  Returns json data about a user books collection tree. Types of book icon: public, private, custom, bin

* **URL**

  /v1/books
  
* **Method:**

  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`

*  **URL Params**
    
    **Required:**
    
   `user_slug=[string]` <br/>
     AND/OR <br/>
   `book_slug=[string]` <br/>

* **Data Params**

  None

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
     "status": "success",
     "data": [
         {
             "name": "root",
             "key": "root",
             "show": true,
             "children": [
                 {
                     "name": "Wallbook",
                     "key": "65-wallbook",
                     "icon": "public",
                     "cover": {
                                 "picture_original": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/book-covers/810x281_YCZveGJwOnxS0kw_enIoeoWjy-__nD85_KykcO6.jpg",
                                 "picture_small": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/book-covers/597x207_YCZveGJwOnxS0kw_enIoeoWjy-__nD85_KykcO6.jpg",
                                 "color": null
                     },
                     "href": "http://validbook-api.local/v1/books/65-wallbook",
                     "auto_export": 1,
                     "auto_import": 1,
                     "no_drag": true
                 },
                 {
                     "name": "Life",
                     "key": "148-life",
                     "icon": "public",
                     "cover": {
                         "picture": null,
                         "color": "778f9c"
                     },
                     "href": "http://validbook-api.local/v1/books/148-life",
                     "auto_export": 1,
                     "auto_import": 1,
                     "no_drag": false,
                     "children": [],
                     "counts": {
                         "stories": 31,
                         "sub_books": 0,
                         "followers": 0,
                         "images": 1
                     }
                 },
                 {
                     "name": "Closed Book",
                     "key": "147-closed-book",
                     "icon": "public",
                     "cover": {
                         "picture": null,
                         "color": "778f9c"
                     },
                     "href": "http://validbook-api.local/v1/books/147-closed-book",
                     "auto_export": 1,
                     "auto_import": 1,
                     "no_drag": false,
                     "children": [],
                     "counts": {
                         "stories": 9,
                         "sub_books": 0,
                         "followers": 0,
                         "images": 0
                     }
                 },
                 {
                     "name": "Ask me anything",
                     "key": "146-ask-me-anything",
                     "icon": "public",
                     "cover": {
                         "picture": null,
                         "color": "778f9c"
                     },
                     "href": "http://validbook-api.local/v1/books/146-ask-me-anything",
                     "auto_export": 1,
                     "auto_import": 1,
                     "no_drag": false,
                     "children": [],
                     "counts": {
                         "stories": 11,
                         "sub_books": 0,
                         "followers": 0,
                         "images": 0
                     }
                 },
                 {
                     "name": "Karma",
                     "key": "145-karma",
                     "icon": "public",
                     "cover": {
                         "picture": null,
                         "color": "778f9c"
                     },
                     "href": "http://validbook-api.local/v1/books/145-karma",
                     "auto_export": 1,
                     "auto_import": 1,
                     "no_drag": false,
                     "children": [],
                     "counts": {
                         "stories": 10,
                         "sub_books": 0,
                         "followers": 0,
                         "images": 0
                     }
                 },
                 {
                     "name": "Interests",
                     "key": "66-interests",
                     "icon": "public",
                     "cover": {
                         "picture": "https://s3-us-west-2.amazonaws.com/dev.validbook/book-covers/2017/09/27/1/fZxwKRDJnEUa9dG0kVlGlD42jSFTE_xb.jpg",
                         "color": null
                     },
                     "href": "http://validbook-api.local/v1/books/66-interests",
                     "auto_export": 1,
                     "auto_import": 1,
                     "no_drag": false,
                     "children": [],
                     "counts": {
                         "stories": 198,
                         "sub_books": 33,
                         "followers": 0,
                         "images": 0
                     }
                 },
                 {
                     "name": "Example Books",
                     "key": "46-example-books",
                     "icon": "public",
                     "cover": {
                         "picture": null,
                         "color": "8c6d63"
                     },
                     "href": "http://validbook-api.local/v1/books/46-example-books",
                     "auto_export": 1,
                     "auto_import": 1,
                     "no_drag": false,
                     "children": [],
                     "counts": {
                         "stories": 4,
                         "sub_books": 3,
                         "followers": 0,
                         "images": 0
                     }
                 },
                 {
                     "name": "Community activities",
                     "key": "42-community-activities",
                     "icon": "public",
                     "cover": {
                         "picture": null,
                         "color": "778f9c"
                     },
                     "href": "http://validbook-api.local/v1/books/42-community-activities",
                     "auto_export": 1,
                     "auto_import": 1,
                     "no_drag": false,
                     "children": [],
                     "counts": {
                         "stories": 2,
                         "sub_books": 3,
                         "followers": 0,
                         "images": 0
                     }
                 },
                 {
                     "name": "Life",
                     "key": "41-life",
                     "icon": "public",
                     "cover": {
                         "picture": null,
                         "color": "778f9c"
                     },
                     "href": "http://validbook-api.local/v1/books/41-life",
                     "auto_export": 1,
                     "auto_import": 1,
                     "no_drag": false,
                     "children": [],
                     "counts": {
                         "stories": 1,
                         "sub_books": 0,
                         "followers": 0,
                         "images": 0
                     }
                 },
                 {
                     "name": "Validbook Books",
                     "key": "27-validbook-books",
                     "icon": "public",
                     "cover": {
                         "picture": null,
                         "color": "778f9c"
                     },
                     "href": "http://validbook-api.local/v1/books/27-validbook-books",
                     "auto_export": 1,
                     "auto_import": 1,
                     "no_drag": false,
                     "children": [],
                     "counts": {
                         "stories": 14,
                         "sub_books": 5,
                         "followers": 0,
                         "images": 0
                     }
                 },
                 {
                     "name": "Karma Books",
                     "key": "23-karma-books",
                     "icon": "public",
                     "cover": {
                         "picture": null,
                         "color": "778f9c"
                     },
                     "href": "http://validbook-api.local/v1/books/23-karma-books",
                     "auto_export": 1,
                     "auto_import": 1,
                     "no_drag": false,
                     "children": [],
                     "counts": {
                         "stories": 0,
                         "sub_books": 3,
                         "followers": 0,
                         "images": 0
                     }
                 },
                 {
                     "name": "Work",
                     "key": "2-work",
                     "icon": "public",
                     "cover": {
                         "picture": null,
                         "color": "778f9c"
                     },
                     "href": "http://validbook-api.local/v1/books/2-work",
                     "auto_export": 1,
                     "auto_import": 1,
                     "no_drag": false,
                     "children": [],
                     "counts": {
                         "stories": 5,
                         "sub_books": 8,
                         "followers": 0,
                         "images": 0
                     }
                 },
                 {
                     "name": "New Book",
                     "key": "373-new-book",
                     "icon": "public",
                     "cover": {
                                 "picture_original": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/book-covers/810x281_YCZveGJwOnxS0kw_enIoeoWjy-__nD85_KykcO6.jpg",
                                 "picture_small": "https://s3-us-west-2.amazonaws.com/dev.validbook/1/book-covers/597x207_YCZveGJwOnxS0kw_enIoeoWjy-__nD85_KykcO6.jpg",
                                 "color": null
                      },
                     "href": "http://validbook-api.local/v1/books/373-new-book",
                     "auto_export": 1,
                     "auto_import": 1,
                     "no_drag": false,
                     "children": [],
                     "counts": {
                         "stories": 0,
                         "sub_books": 0,
                         "followers": 0,
                         "images": 0
                     }
                 },
                 {
                     "name": "Bin",
                     "key": "bin",
                     "icon": "bin",
                     "no_drag": true,
                     "children": []
                 }
             ]
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

  ```javascript
    $.ajax({
      url: "/v1/books",
      dataType: "json",
      data: {user_slug: "john-smith"},
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```