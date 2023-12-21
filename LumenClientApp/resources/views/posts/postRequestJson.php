<html>
    <head>
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
    </head>

    <body>
        <div class="container">
            <h1>List Post</h1>
            <table class = "table table-striped">
                <tbody>
                    
                    <tr>
                        <td>ID</td>
                        <td><?php echo $result['data']['id'] ?></td>
                    </tr>
                    <tr>
                        <td>User ID</td>
                        <td><?php echo $result['data']['user_id'] ?></td>
                    </tr>
                    <tr>
                        <td>Title</td>
                        <td><?php echo $result['data']['title'] ?></td>
                    </tr>
                    <tr>
                        <td>Content</td>
                        <td><?php echo $result['data']['content'] ?></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td><?php echo $result['data']['status'] ?></td>
                    </tr>
                    <tr>
                        <td>Image</td>
                        <td><?php echo $result['data']['image'] ?></td>
                    </tr>
                    <tr>
                        <td>Video</td>
                        <td><?php echo $result['data']['video'] ?></td>
                    </tr>
                    <tr>
                        <td>Categories ID</td>
                        <td><?php echo $result['data']['categories_id'] ?></td>
                    </tr>
                    <tr>
                        <td>Students ID</td>
                        <td><?php echo $result['data']['students_id'] ?></td>
                    </tr>
                    <tr>
                        <td>Created At</td>
                        <td><?php echo $result['data']['created_at'] ?></td>
                    </tr>
                    <tr>
                        <td>Updated At</td>
                        <td><?php echo $result['data']['updated_at'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>