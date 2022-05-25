<?php
session_start();
if(!isset($_SESSION['managerId'])){ header('location:login.php');}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Banking</title>
  <style type="text/css">
    .MyTable tr:nth-of-type(2n+1) {
      background-color: #dfe1e3; 
    }
  </style>
  <link rel="stylesheet" type="text/css" href="transactions.php">
  <?php require 'assets/autoloader.php'; ?>
  <?php require 'assets/db.php'; ?>
  <?php require 'assets/function.php'; ?>
  <?php if (isset($_GET['delete'])) 
  {
    if ($con->query("delete from useraccounts where id = '$_GET[delete]'"))
    {
      header("location:mindex.php");
    }
  } ?>

  <?php $note ="";
    if (isset($_POST['withdrawOther']))
    { 
      $accountNo = $_POST['otherNo'];
      $checkNo = $_POST['checkno'];
      $amount = $_POST['amount'];
      if(setOtherBalance($amount,'debit',$accountNo))
      $note = "<div class='alert alert-success'>successfully transaction done</div>";
      else
      $note = "<div class='alert alert-danger'>Failed</div>";

    }
    if (isset($_POST['withdraw']))
    {
      setBalance($_POST['amount'],'debit',$_POST['accountNo']);
      makeTransactionManager('withdraw',$_POST['amount'],$_POST['checkno'],$_POST['userId']);
      $note = "<div class='alert alert-success'>successfully transaction done</div>";

    }
    if (isset($_POST['deposit']))
    {
      setBalance($_POST['amount'],'credit',$_POST['accountNo']);
      makeTransactionManager('deposit',$_POST['amount'],$_POST['checkno'],$_POST['userId']);
      $note = "<div class='alert alert-success'>successfully transaction done</div>";

    }
   ?>
</head>
<body style="background:#c2edcc;background-size: 100%">
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
<div class="container">
<div class="card w-100 text-center shadowBlue">
  <div class="card-header">
    All accounts

    <div class="card-body">
    <p class="card-text"><?php echo $note; ?>
    <form method="POST">
      <div class="">
      <label>Search</label>
      <input type="text" name="otherNo" class=" " placeholder="Enter  Account number" required>
      <button type="submit" name="get" class="btn btn-primary ">Go</button>
    </div>
    </form>
  </p>
</div>
   <?php if(isset($_POST['submit'])){
   //echo show.php
   }
    ?>

     <?php if (isset($_POST['get'])) 
      {
        $array2 = $con->query("select * from otheraccounts where accountNo = '$_POST[otherNo]'");
        $array3 = $con->query("select * from userAccounts where accountNo = '$_POST[otherNo]'");
        {
          if ($array2->num_rows > 0) 
          { $row2 = $array2->fetch_assoc();
            echo "<div class='row'>
                  <div class='col'>
                  <form method='POST'>
                    Account No.
                    <input type='text' value='$row2[accountNo]' name='otherNo' class='form-control ' readonly required>
                    Account Holder Name.
                    <input type='text' class='form-control' value='$row2[holderName]' readonly required>
                    Account Holder Bank Name.
                    <input type='text' class='form-control' value='$row2[bankName]' readonly required>
                     
                  
                  </div>
                  <div class='col'>
                    Bank Balance
                    <input type='text' class='form-control my-1'  value='GH¢$row2[balance]' readonly required>
                    <input type='number' class='form-control my-1' name='checkno' placeholder='Write Check Number' required>
                    <input type='number' class='form-control my-1' name='amount' placeholder='Write Amount' max='$row2[balance]' required>
                   <button type='submit' name='withdrawOther' class='btn btn-success btn-bloc btn-sm my-1'> Withdraw</button></form>
                  </div>
                </div>";
          }elseif ($array3->num_rows > 0) {
           $row2 = $array3->fetch_assoc();
            echo "
            <div class='row'>
                  <div class='col'>
                  
                    Account No.
                    <input type='text' value='$row2[accountNo]' name='otherNo' class='form-control ' readonly required>
                    Account Holder Name.
                    <input type='text' class='form-control' value='$row2[name]' readonly required>
                    Account Holder Bank Name.
                    <input type='text' class='form-control' value='".bankName."' readonly required>Bank Balance
                    <input type='text' class='form-control my-1'  value='GH¢$row2[balance]' readonly required>
                     
                  
                  </div>
                  <div class='col'>
                    Transaction Process.
                    <form method='POST'>
                     
                    <input type='hidden' value='$row2[accountNo]' name='accountNo' class='form-control ' required>
                    <input type='hidden' value='$row2[id]' name='userId' class='form-control ' required>
                    <input type='number' class='form-control my-1' name='checkno' placeholder='Write Check Number' required>
                    <input type='number' class='form-control my-1' name='amount' placeholder='Write Amount for withdraw' max='$row2[balance]' required>
                   <button type='submit' name='withdraw' class='btn btn-primary btn-bloc btn-sm my-1'> Withdraw</button></form><form method='POST'> 
                    <input type='hidden' value='$row2[accountNo]' name='accountNo' class='form-control ' required>
                    <input type='hidden' value='$row2[id]' name='userId' class='form-control ' required>
                   <input type='number' class='form-control my-1' name='checkno' placeholder='Write Check Number' required>
                    <input type='number' class='form-control my-1' name='amount' placeholder='Write Amount for deposit'  required>

                   <button type='submit' name='deposit' class='btn btn-success btn-bloc btn-sm my-1'> Deposit</button></form>
                  </div>
                </div>
            ";
          }
          else
            echo "<div class='alert alert-success w-50 mx-auto'>Account No. $_POST[otherNo] Does not exist</div>";
        }
  } 
      ?>
  </div>
  <div class="card-body">
   <table class="table table-bordered table-sm">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Holder Name</th>
      <th scope="col">Account No.</th>
      <th scope="col">Branch Name</th>
      <th scope="col">Current Balance</th>
      <th scope="col">Account type</th>
      <th scope="col">Contact</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody class="MyTable">
    <?php
      $i=0;
      $array = $con->query("select * from useraccounts,branch where useraccounts.branch = branch.branchId");
      if ($array->num_rows > 0)
      {
        while ($row = $array->fetch_assoc())
        {$i++;
    ?>
      <tr>
        <th scope="row"><?php echo $i ?></th>
        <td style="font-weight: bold;"> <a href="show.php?id=<?php echo $row['id'] ?>"  title="View More info"><?php echo $row['name'] ?></a></td>
        <td><?php echo $row['accountNo'] ?></td>
        <td><?php echo $row['branchName'] ?></td>
        <td> GH¢<?php echo $row['balance'] ?></td>
        <td><?php echo $row['accountType'] ?></td>
        <td><?php echo $row['number'] ?></td>
        <td>
          <a href="show.php?id=<?php echo $row['id'] ?>" class='btn btn-success btn-sm' data-toggle='tooltip' title="View More info" style= "background-color: #87d599;">View</a>

          <a href="?id=<?php echo $row['id'] ?>" class='btn btn-success btn-sm' data-toggle='tooltip' title="Update account info" style= "background-color: #a6ab10;">Update</a>
          <a href="mnotice.php?id=<?php echo $row['id'] ?>" class='btn btn-primary btn-sm' data-toggle='tooltip' title="Send notice to this" style="background-color: #567aa0;">Notify</a>
          <a href="mindex.php?delete=<?php echo $row['id'] ?>" class='btn btn-danger btn-sm' data-toggle='tooltip' title="Delete this account">Delete</a>
        </td>
        
      </tr>
    <?php
        }
      }
     ?>
  </tbody>
</table>
  <div class="card-footer text-muted">
    <?php echo bankname; ?>
  </div>
</div>

<?php
$con =new PDO("mysql:host=localhost;dbname=mybank",'root','');
if (isset($_POST["submit"])) {
  $str = $_POST["search"];
  $sth = $con->prepare("SELECT * FROM 'search' WHERE name = $'str'");
  $sth->setFetchMode(PDO::FETCH_OBJ);
  $sth ->execute();

  if($row = $sth->fetch())
  {
    ?>
    <br><br><br>
    <table>
      <tr>
        <th>name</th>
        <th>accountNo</th>
      </tr>
      <tr>
        <td><?php echo $row->name; ?></td>
        <td><?php echo $row->AccountNo; ?></td>
      </tr>
    </table>
   <?php
  }

 
  else{
    echo "no results found";
  }

}
 ?> 
</body>
</html>