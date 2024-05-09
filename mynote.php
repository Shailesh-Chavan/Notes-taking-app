<?php
$warning=false;
$insert= false;
$update=false;
$delete=false;

$servername="localhost";
$username="root";
$password="";
$database="notes";

$conn=mysqli_connect($servername, $username, $password, $database);


if (isset($_GET['delete'])){
  $sno=$_GET['delete'];
  
  $sql="DELETE FROM `notes` WHERE `notes`.`Sno` = $sno";
  $result=mysqli_query($conn, $sql);
  $delete=true;
}
if ($_SERVER['REQUEST_METHOD']=='POST')
{
  if(isset($_POST['snoEdit']))
  {
    $sno=$_POST['snoEdit'];
    $title=$_POST['titleEdit'];
    $description=$_POST['descriptionEdit'];

    $sql = "UPDATE `notes` SET `Title` = '$title', `Description` = '$description' WHERE `notes`.`Sno` = $sno";
    $result = mysqli_query($conn, $sql);

    if($result)
      {
        $update=true;
      }
      else
      {
        echo "Note does not updated because of error--->". mysqli_error($conn);
      }
  }
  else
  {
    $title=$_POST['title'];
    $description=$_POST['description'];
    if($title!="" && $description!="")
    {
      $sql = "INSERT INTO `notes` (`Title`, `Description`) VALUES ('$title', '$description')"; 
      $result = mysqli_query($conn, $sql);
      if($result)
      {
        $insert=true;
      }
    }
    else
    {
      $warning=true;
    }
  }
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>myNotes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="//cdn.datatables.net/2.0.1/css/dataTables.dataTables.min.css">
  <style>
    .warning{
      color:red;
    }
  </style>
</head>

<body>
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editModalLabel">Edit your note</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/notes/mynote.php" method="POST">
            <input type="hidden" name="snoEdit" id="snoEdit">
            <div class="mb-3">
              <label for="titleEdit" class="form-label">Note title</label>
              <input type="text" class="form-control" id="titleEdit" name="titleEdit">
            </div>
            <div class="form-group">
              <label for="descriptionEdit">Note description</label>
              <textarea class="form-control" id="descriptionEdit" name="descriptionEdit"
                style="height: 100px"></textarea>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
        </div>  
      </div>
    </div>
  </div>
  <nav class="navbar bg-dark navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="mynote.php">iNote</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="mynote.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact us</a>
          </li>
        </ul>
        <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>

  <?php 
    if ($insert)
    {
      echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your note has been inserted successfully.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    }
  ?>

  <?php 
    if ($update)
    {
      echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your note has been updated successfully.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    }
  ?>

  <?php 
    if ($delete)
    {
      echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Your note has been deleted successfully.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    }
  ?>

  <div class="container my-4">
    <h2>Add a note</h2>
    <form action="/notes/mynote.php" method="POST">
      <div class="mb-3">
        <label for="title" class="form-label">Note title</label>
        <input type="text" class="form-control" id="title" name="title">
      </div>
      <div class="form-group">
        <label for="description">Note description</label>
        <textarea class="form-control" id="description" name="description" style="height: 100px"></textarea>
      </div>
      <div class="warning">
        <p><?php if($warning) echo "Please add both title and description"?></p>
      </div>
      <button type="submit" class="btn btn-primary">Add note</button>
    </form>
  </div>

  <div class="container">
    <table id="myTable" class="table table-striped" style="width:100%">
      <thead>
        <tr>
          <th scope="col">S.no</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $sql= "SELECT * FROM `notes`";
          $result =mysqli_query($conn, $sql);
          $num=1;
          while($row=mysqli_fetch_assoc($result))
          {
            echo "<tr>
                    <th scope='row'>".$num."</th>
                    <td>".$row['Title']."</td>
                    <td>".$row['Description']."</td>
                    <td>
                      <button class='edit btn btn-sm btn-primary' id=".$row['Sno'].">Edit</button>
                      <button class='delete btn btn-sm btn-primary' id=d".$row['Sno'].">Delete</button>
                    </td>
                  </tr>";
            $num++;
          }
        ?>
      </tbody>
    </table>
  </div>
  <hr>
  <script src="https://code.jquery.com/jquery-3.7.1.slim.js"
    integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/2.0.1/js/dataTables.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#myTable').DataTable();
    });
  </script>
  <script>
    edits = document.getElementsByClassName('edit');

    Array.from(edits).forEach((element) => {
      element.addEventListener('click', (e) => {
        console.log("clicked");
        tr = e.target.parentNode.parentNode;
        title = tr.getElementsByTagName("td")[0].innerText;
        description = tr.getElementsByTagName("td")[1].innerText;
        // console.log(title, description);
        snoEdit.value = e.target.id;
        // console.log(e.target.id);
        titleEdit.value = title;
        descriptionEdit.value = description;
        $('#editModal').modal('toggle');
      })
    });

    delets = document.getElementsByClassName('delete');

    Array.from(delets).forEach((element) => {
      element.addEventListener('click', (e) => {
        console.log("clicked");
        sno = e.target.id.substr(1,);
        if (confirm("Are you sure you want to delete note!")) {
          console.log("yes");
          window.location = `/notes/mynote.php?delete=${sno}`;
        }
        else {
          console.log("No");
        }
      })
    });
  </script>
</body>
</html>