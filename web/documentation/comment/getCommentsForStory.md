**Get Story Comments**
----
  Returns json data comments for story

* **URL**

  /v1/comments/story
  
* **Method:**

  `GET`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    
   `page=[integer]` default = 1
    
    **Required:**
    
   `entity_id=[integer]`

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
               "id": 6,
               "entity": "story",
               "entity_id": 1273,
               "date": "10 Jul 2017",
               "parent_id": 0,
               "parent": [],
               "content": "6",
               "children": null,
               "user": {
                   "id": 1,
                   "first_name": "Jimbo",
                   "last_name": "Fry",
                   "slug": "jimbo.fry",
                   "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/08/01/1/KmtG6WPozz_cMDqTdBMNm6F2N5BgOcGm.jpg",
                   "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/08/01/1/H7sj1Q512otnuC2SU3pXe4Ap1YX0sfT8.jpg"
               }
           },
           {
               "id": 7,
               "entity": "story",
               "entity_id": 1273,
               "date": "10 Jul 2017",
               "parent_id": 0,
               "parent": [],
               "content": "7",
               "children": [
                   {
                       "id": 32,
                       "entity": "story",
                       "entity_id": 1,
                       "date": "11 Jul 2017",
                       "content": "content",
                       "children": null,
                       "user": {
                           "id": 2,
                           "first_name": "Olga",
                           "last_name": "Sochneva",
                           "slug": "olga.sochneva",
                           "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/2/230x230.jpg",
                           "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/2/32x32.jpg"
                       }
                   },
                   {
                       "id": 31,
                       "entity": "story",
                       "entity_id": 1,
                       "date": "11 Jul 2017",
                       "content": "content",
                       "children": null,
                       "user": {
                           "id": 2,
                           "first_name": "Olga",
                           "last_name": "Sochneva",
                           "slug": "olga.sochneva",
                           "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/2/230x230.jpg",
                           "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/2/32x32.jpg"
                       }
                   },
                   {
                       "id": 30,
                       "entity": "story",
                       "entity_id": 1,
                       "date": "11 Jul 2017",
                       "content": "content",
                       "children": null,
                       "user": {
                           "id": 2,
                           "first_name": "Olga",
                           "last_name": "Sochneva",
                           "slug": "olga.sochneva",
                           "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/2/230x230.jpg",
                           "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/2/32x32.jpg"
                       }
                   },
                   {
                       "id": 29,
                       "entity": "story",
                       "entity_id": 1,
                       "date": "11 Jul 2017",
                       "content": "content",
                       "children": null,
                       "user": {
                           "id": 2,
                           "first_name": "Olga",
                           "last_name": "Sochneva",
                           "slug": "olga.sochneva",
                           "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/2/230x230.jpg",
                           "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/2/32x32.jpg"
                       }
                   }
               ],
               "user": {
                   "id": 1,
                   "first_name": "Jimbo",
                   "last_name": "Fry",
                   "slug": "jimbo.fry",
                   "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/08/01/1/KmtG6WPozz_cMDqTdBMNm6F2N5BgOcGm.jpg",
                   "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/08/01/1/H7sj1Q512otnuC2SU3pXe4Ap1YX0sfT8.jpg"
               }
           },
           {
               "id": 8,
               "entity": "story",
               "entity_id": 1273,
               "date": "10 Jul 2017",
               "parent_id": 0,
               "parent": [],
               "content": "8",
               "children": null,
               "user": {
                   "id": 1,
                   "first_name": "Jimbo",
                   "last_name": "Fry",
                   "slug": "jimbo.fry",
                   "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/08/01/1/KmtG6WPozz_cMDqTdBMNm6F2N5BgOcGm.jpg",
                   "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/08/01/1/H7sj1Q512otnuC2SU3pXe4Ap1YX0sfT8.jpg"
               }
           },
           {
               "id": 9,
               "entity": "story",
               "entity_id": 1273,
               "date": "10 Jul 2017",
               "parent_id": 0,
               "parent": [],
               "content": "9",
               "children": null,
               "user": {
                   "id": 1,
                   "first_name": "Jimbo",
                   "last_name": "Fry",
                   "slug": "jimbo.fry",
                   "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/08/01/1/KmtG6WPozz_cMDqTdBMNm6F2N5BgOcGm.jpg",
                   "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/08/01/1/H7sj1Q512otnuC2SU3pXe4Ap1YX0sfT8.jpg"
               }
           }
       ]
   }
    ```
 
* **Error Response:**

    * **Code:** 401 Unauthorized <br />
    * **Code:** 404 NOT FOUND<br />
    * **Code:** 422 Unprocessable Entity <br />
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
      url: "/v1/comments/story?entity_id=1",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```