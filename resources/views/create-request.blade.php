<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <h1 class="text-center">Add Request</h1>
        <form action="/store-request" method="POST">
            @csrf
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="" class="font-weight-bolder">Name</label>
                        <input type="text" class="form-control" id="" name="name" aria-describedby="">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="" class="font-weight-bolder">Type</label>
                        <select class="form-control" id="" name="type">
                            <option>Please Select</option>
                            <option value="Material">Material</option>
                            <option value="Repair">Repair</option>
                            <option value="Salary Issue">Salary Issue</option>
                            <option value="Iqama Issue">Iqama Issue</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="font-weight-bolder">Issue</label>
                <textarea type="" class="form-control" id="" rows="2" name="issue"></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</body>

</html>
