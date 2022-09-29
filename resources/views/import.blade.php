<!DOCTYPE html>
<html>
<head>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <style>
div {
  background-color: lightgrey;
  width: 300px;
  border: 1px solid green;
  padding: 50px;
  margin-left: 550px;
  margin-top: 200px;
}
</style>
</head>
<body>
   
<div class="box">
            <form action="{{ route('importLenderSections') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="csv_file">
                <br>
                <button class="btn btn-success">Import Section Data</button>
               
            </form>
           
            
            <br>
            <form action="{{ route('importLendersubSections') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="csv_file">
                <br>
                <button class="btn btn-success">Import Sub Section Data</button>
               
            </form>
            <br>
            <form action="{{ route('counties') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="csv_file">
                <br>
                <button class="btn btn-success">Import counties Data</button>
               
            </form>
            </div>
        </div>
    </div>
</div>
   
</body>
</html>