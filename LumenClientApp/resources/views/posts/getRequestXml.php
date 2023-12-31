<html>
<head>
  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
</head>
<body>
  <div class="container">
    <h1>List Post</h1>
    <table class="table table-striped">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>User ID</th>
          <th>Title</th>
          <th>Status</th>
          <th>Content</th>
            <th>Image</th>
            <th>Video</th>
            <th>Categories ID</th>
            <th>Students ID</th>
          <th>Created At</th>
          <th>Updated At</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody> <!-- Add this opening <tbody> tag -->
        <?php
          foreach ($results as $result) {
            echo "<tr>";
            echo "<td>" . $result['id'] . "</td>";
            echo "<td>" . $result['user_id'] . "</td>";
            echo "<td>" . $result['title'] . "</td>";
            echo "<td>" . $result['status'] . "</td>";
            echo "<td>" . $result['content'] . "</td>";
            echo "<td>" . $result['image'] . "</td>";
            echo "<td>" . $result['video'] . "</td>";
            echo "<td>" . $result['categories_id'] . "</td>";
            echo "<td>" . $result['students_id'] . "</td>";
            echo "<td>" . $result['created_at'] . "</td>";
            echo "<td>" . $result['updated_at'] . "</td>";
            echo "<td>" . "<a href='http://localhost:9000/posts/show-request-xml/" . $result['id'] . "'>View</a>" . "</td>";
            echo "</tr>";
          }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>
