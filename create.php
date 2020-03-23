<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<?php
require_once "config.php";

$taskTitle = $taskDesc = $project = $severity = "";
$taskTitle_err = $taskDesc_err = $project_err = $severity_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    //Validating Title
    $input_title = trim($_POST["taskTitle"]);
    if(empty($input_title))
    {
        $taskTitle_err = "Please Enter Task Title.";
    }
    elseif(!filter_var($input_title, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/"))))
    {
        $taskTitle_err = "Please Enter Valid Name.";
    }
    else
    {
        $taskTitle = $input_title;
    }

    //Validating 
    $input_desc = trim($_POST["taskDesc"]);
    if(empty($input_desc))
    {
        $taskDesc_err = "Please Enter Description";
    }
    else
    {
        $taskDesc = $input_desc;
    }

    //validate project
    $input_project = trim($_POST["project"]);
    if(empty($input_project))
    {
        $project_err = "Please Enter Project Name.";
    }
    else
    {
        $project = $input_project;
    }

    //validate severity
    $input_severity = trim($_POST["severity"]);
    if(empty($input_severity))
    {
        $severity_err = "Please Select Severity of project.";
    }
    else
    {
        $severity = $input_severity;
    }

    if(empty($taskTitle_err) && empty($taskDesc_err) && empty($project_err))
    {
        $sql = "INSERT INTO tasks (taskTitle, project, taskDesc, severity) values (?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql))
        {
            mysqli_stmt_bind_param($stmt, "ssss", $param_title, $param_project, $param_desc, $param_severity);

            $param_title = $taskTitle;
            $param_desc = $taskDesc;
            $param_project = $project;
            $param_severity = $severity;

            if(mysqli_stmt_execute($stmt))
            {
                header("location: index.php");
                exit();
            }
            else
            {
                echo "Something went wrong. Plese try again later.";
            }

        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css"> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="/style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
        body
        { 
            font: 14px sans-serif;
           
        }
        .wrapper
        {
            width: 80%;
            margin: 0 auto;
        }
        .page-header h2
        {
            margin-top: 0;
        }
        /*table tr td:last-child a{
            margin-right: 15px;
        }*/
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Task Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle Navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="#navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item-active">
                    <a class="nav-link" href="/taskdashboard/index.php">Home<span class="sr-only"></span></a>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Issue
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/taskdashboard/create.php">Add Issue</a>
                    <a class="dropdown-item" href="#">All Issues</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                  </div>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo htmlspecialchars($_SESSION["username"]); ?></a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a href="reset-password.php" class="dropdown-item">Reset Password</a>
                        <a href="logout.php" class="dropdown-item">Sign Out</a>
                    </div>
                </li>

            </ul>
        </div>
    </nav>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>Add Task</h1>
                    </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="form-group <?php echo (!empty($taskTitle_err)) ? 'has-error' : ''; ?>">
                        <label>Title</label>
                        <input type="text" name="taskTitle" class="form-control" value="<?php echo $taskTitle?>">
                        <span class="help-block"><?php echo $taskTitle_err; ?></span>
                    </div> 
                    <div class="form-group <?php echo (!empty($taskDesc_err)) ? 'has-error' : ''; ?>">
                        <label>Description</label>
                        <input type="text" name="taskDesc" class="form-control" value="<?php echo $taskDesc?>">
                        <span class="help-block"><?php echo $taskDesc_err; ?></span>
                    </div> 
                    <div class="form-group <?php echo (!empty($project_err)) ? 'has-error' : ''; ?>">
                        <label>Project</label>
                        <input type="text" name="project" class="form-control" value="<?php echo $project_err?>">
                        <span class="help-block"><?php echo $project_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($severity_err)) ? 'has-error' : ''; ?>">
                        <label>Severity</label>
                        <input type="text" name="severity" class="form-control" value="<?php echo $severity?>">
                        <span class="help-block"><?php echo $severity_err; ?></span>
                    </div> 
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.php" class="btn btn-default">Cancel</a>
                </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>