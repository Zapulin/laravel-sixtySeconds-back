<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        

        <style>
         body {
             font-family: 'Nunito', sans-serif;
	     display: flex;
	     justify-content:center;
         }
	 fielset{
	     border: 1px solid black;
	     display: flex;
	     flex-direction: column;
	     padding: 10px;
	     /*width: 50%;*/
	     justify-content: center;
	     border-radius:10px;
	     margin: 3px;
	 }
	 
        </style>
    </head>
    <body class="antialiased">
	<main>
	    
	    <fielset>

		<legend>Post CRUD</legend>
		<fielset>
		    <legend>Get All Post</legend>
		    <label> Url: https://sixtyseconds-backend.herokuapp.com/api/posts</label>
		    <label>Method: GET</label>
		    <label>Returns: {Post Array}</label>
		</fielset>
		<fielset>
		    <legend>Get One Post</legend>
		    <label> Url: https://sixtyseconds-backend.herokuapp.com/api/post/{idPost}</label>
		    <label>Method: GET</label>
		    <label>Params: idPost(query)</label>
		    <label>Returns {Post}</label>
		</fielset>
		<fielset>
		    <legend>Create One Post</legend>
		    <label> Url: https://sixtyseconds-backend.herokuapp.com/api/post/create</label>
		    <label>Method: POST</label>
		    <label>Content-Type: multipart/form-data</label>
		    <label>Body Params:
			{ 
			visibility : "Public",
			file: {Audio File},
			title: "Example Post Create",
			creationDate: {Date},
			category: "Humor,Deportes,Noticias"
			}
			
		    </label>
		    <label>Requires: Autorization bearer token</label>
		    <label>Returns: {"id": 165}</label>
		    
		</fielset>
		<fielset>
		    <legend>Update One Post</legend>
		    <label> Url: https://sixtyseconds-backend.herokuapp.com/api/post/create</label>
		    <label>Method: POST</label>
		    <label>Content-Type: multipart/form-data</label>
		    <label>Body Params:
			{ 
			visibility : "Public",
			title: "Example Post Create",
			category: "Humor,Deportes,Noticias"
			}
			
		    </label>
		    <label>Needs Autorization bearer token</label>
		    <label>Returns: {"id": 165}</label>
		    
		</fielset>
		
		
		<fielset>
		    <legend>Delete one Post</legend>
		    <label> Url: https://sixtyseconds-backend.herokuapp.com/api/post/{idPost}</label>
		    <label>Method: DELETE</label>

		    <label>Params: idPost(query)</label>
		    <label>Needs Autorization bearer token</label>
		    <label>Returns: Ok Message if deletes</label>
		    
		</fielset>
	    </fielset>
	</main>
    </body>
</html>
