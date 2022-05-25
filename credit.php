<?php
session_start();
if(!isset($_SESSION['managerId'])){ header('location:login.php');}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Banking</title>
  <?php require 'assets/autoloader.php'; ?>
  <?php require 'assets/db.php'; ?>
  <?php require 'assets/function.php'; ?>
  <?php if (isset($_GET['delete'])) 
  {
    if ($con->query("delete from useraccounts where id = '$_GET[id]'"))
    {
      header("location:mindex.php");
    }
  } 
  ?>

<?php $note ="";
    
    if (isset($_POST['deposit']))
    {
      setBalance($_POST['amount'],'credit','accountNo');
      makeTransactionManager('deposit',$_POST['amount'],$_POST['checkno'],'userId');
      $note = "<div class='alert alert-success'>successfully transaction done</div>";

    }
   ?>
    
</head>
<body style="background:#96D678;background-size: 100%">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
 <a class="navbar-brand" href="#">
    <img src="images/envelope.png" width="30" height="30" class="d-inline-block align-top" alt="">
   <!--  <i class="d-inline-block  fa fa-building fa-fw"></i> --><?php echo bankname; ?>
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item ">
        <a class="nav-link active" href="mindex.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item ">  <a class="nav-link" href="maccounts.php">Accounts</a></li>
      <li class="nav-item ">  <a class="nav-link" href="maddnew.php">Add New Account</a></li>
      <li class="nav-item ">  <a class="nav-link" href="mfeedback.php">Feedback</a></li>
      <!-- <li class="nav-item ">  <a class="nav-link" href="transfer.php">Funds Transfer</a></li> -->
      <!-- <li class="nav-item ">  <a class="nav-link" href="profile.php">Profile</a></li> -->


    </ul>
    <?php include 'msideButton.php'; ?>
    
  </div>
</nav><br><br><br>
<?php 
  $array = $con->query("select * from useraccounts,branch where useraccounts.id = '$_GET[id]' AND useraccounts.branch = branch.branchId");
  $row = $array->fetch_assoc();
 ?>
<div class="container">
<div class="card w-100 text-center shadowBlue">
  <div class="card-header">
    <h1>Credit Account</h1>
  </div>

  <div class="card-body">
    <table class="">

 <!-- Trials -->
<div class="row">
<div class="col-md-6">
  Account Number:
  <input type="text" value="<?php echo $row['accountNo'] ?>" name="otherNo" class="form-control " readonly="" required="">
  Account Name:
  <input type="text" value="<?php echo $row['name'] ?>" name="otherNo" class="form-control " readonly="" required="">
   Account Balance:
  <input type="text" value="<?php echo $row['balance'] ?>" name="otherNo" class="form-control " readonly="" required="">
</div>
<div class="col-md-6">
  Transaction Process
  <form method="POST">
  <input type="number" class="form-control my-1" name="checkno" placeholder="Write Check Number" required="">
  <input type="number" class="form-control my-1" name="amount" placeholder="Enter Amount for credit" max="7000" required="">
  <button type="submit" name="deposit" class="btn btn-primary btn-bloc btn-sm my-1"> Credit</button>
  </form>

<?php $note ="";
    // if (isset($_POST['withdrawOther']))
    // { 
    //   $accountNo = $_POST['otherNo'];
    //   $checkNo = $_POST['checkno'];
    //   $amount = $_POST['amount'];
    //   if(setOtherBalance($amount,'debit',$accountNo))
    //   $note = "<div class='alert alert-success'>successfully transaction done</div>";
    //   else
    //   $note = "<div class='alert alert-danger'>Failed</div>";

    // }
    // if (isset($_POST['withdraw']))
    // {
    //   setBalance($_POST['amount'],'debit',$_POST['accountNo']);
    //   makeTransactionManager('withdraw',$_POST['amount'],$_POST['checkno'],$_POST['userId']);
    //   $note = "<div class='alert alert-success'>successfully transaction done</div>";

    // }
    if (isset($_POST['deposit']))
    {
      setBalance($_POST['amount'],'credit','accountNo');
      makeTransactionManager('deposit',$_POST['amount'],$_POST['checkno'],'userId');
      $note = "<div class='alert alert-success'>successfully transaction done</div>";

    }
   ?>
</div>  
</div>
<!-- End of Trials -->

    </table>
  </div>

  <div class="card-footer text-muted">
    <?php echo bankname; ?>
  </div>
</div>

</body>
</html>


