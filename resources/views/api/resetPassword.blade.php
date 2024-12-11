<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>

<div class="container">
    <div class="row">
        <form  method="POST">
        @csrf

        @if(!empty($users) && isset($users[0]['id']))
        <input type="text" name="id" value="{{ $users[0]['id'] }}">
        <br>
        @else
            <p>User not found</p>
        @endif

        <br>
        <div class="row g-3">
           <div class="col-6 mt-3"> 
           <input type="password" name="password" id="password" placeholder="Please enter New Password" required>           
           </div><br>
          <div class="col-6 mt-3"> 
         <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Please enter Confirm Password" required>
          </div>
         </div><br>
         <input type="submit" value="ResetPassword" id="submit" class="btn btn-primary">
       </form>

    </div>
</div>
    

</body>
</html>